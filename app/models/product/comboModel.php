<?php

require_once __DIR__ . '/../../core/BaseModel.php';
require_once __DIR__ . '/../../entities/product/Combo.php';

/**
 * ComboModel
 * Quản lý các hoạt động liên quan đến combo sản phẩm
 */
class ComboModel extends BaseModel
{
    protected $table = 'combo';
    protected $comboItemTable = 'combo_item';

    /**
     * Tạo combo mới
     */
    public function create($combo, $products = [])
    {
        try {
            $this->conn->beginTransaction();

            // 1. Tạo combo
            $query = "INSERT INTO {$this->table} (name, description, imagePath, price, isActive, staffId, createdAt, updatedAt) 
                      VALUES (:name, :description, :imagePath, :price, :isActive, :staffId, NOW(), NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':name' => $combo->name,
                ':description' => $combo->description ?? null,
                ':imagePath' => $combo->imagePath ?? null,
                ':price' => $combo->price,
                ':isActive' => $combo->isActive ? 1 : 0,
                ':staffId' => $combo->staffId
            ]);

            $comboId = (int)$this->conn->lastInsertId();

            // 2. Thêm sản phẩm vào combo
            if (!empty($products)) {
                $this->addItems($comboId, $products);
            }

            $this->conn->commit();
            return $comboId;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật combo
     */
    public function update($comboId, $data, $products = [])
    {
        try {
            $this->conn->beginTransaction();

            // 1. Cập nhật combo
            $updateFields = [];
            $params = [':comboId' => $comboId];

            foreach (['name', 'description', 'imagePath', 'price', 'isActive', 'staffId'] as $field) {
                if (array_key_exists($field, $data)) {
                    $updateFields[] = "$field = :$field";
                    $params[":$field"] = $data[$field];
                }
            }

            if (!empty($updateFields)) {
                $updateFields[] = "updatedAt = NOW()";
                $query = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE comboId = :comboId";
                $stmt = $this->conn->prepare($query);
                $stmt->execute($params);
            }

            // 2. Xóa tất cả sản phẩm cũ và thêm sản phẩm mới
            if (!empty($products)) {
                error_log("ComboModel::update - Updating items for comboId=$comboId");
                
                // Use updateItems which handles duplicate key better
                $this->updateItems($comboId, $products);
                
                error_log("ComboModel::update - Items updated successfully");
            }

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Lấy combo theo ID (kèm danh sách sản phẩm)
     */
    public function getById($comboId, $includeDeleted = false)
    {
        $query = "SELECT * FROM {$this->table} WHERE comboId = :comboId";
        
        if (!$includeDeleted) {
            $query .= " AND deletedAt IS NULL";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':comboId' => $comboId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $combo = Combo::fromArray($row);

        // Lấy danh sách sản phẩm
        $items = $this->getComboItems($comboId);
        $combo->items = $items;

        return $combo;
    }

    /**
     * Lấy tất cả combo (hoạt động)
     */
    public function getAll($onlyActive = true, $limit = 100, $offset = 0)
    {
        $query = "SELECT * FROM {$this->table} WHERE deletedAt IS NULL";
        
        if ($onlyActive) {
            $query .= " AND isActive = 1";
        }

        $query .= " ORDER BY createdAt DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $combos = [];

        foreach ($rows as $row) {
            $combo = Combo::fromArray($row);
            $combo->items = $this->getComboItems($combo->comboId);
            $combos[] = $combo;
        }

        return $combos;
    }

    /**
     * Xóa mềm combo (soft delete)
     */
    public function softDelete($comboId)
    {
        $query = "UPDATE {$this->table} SET deletedAt = NOW() WHERE comboId = :comboId";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':comboId' => $comboId]);
    }

    /**
     * Xóa cứng combo (hard delete)
     */
    public function hardDelete($comboId)
    {
        try {
            $this->conn->beginTransaction();

            // Xóa combo_item trước (due to foreign key)
            $this->conn->prepare("DELETE FROM {$this->comboItemTable} WHERE comboId = :comboId")
                ->execute([':comboId' => $comboId]);

            // Xóa combo
            $query = "DELETE FROM {$this->table} WHERE comboId = :comboId";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([':comboId' => $comboId]);

            $this->conn->commit();
            return $result;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Khôi phục combo bị xóa mềm
     */
    public function restore($comboId)
    {
        $query = "UPDATE {$this->table} SET deletedAt = NULL WHERE comboId = :comboId";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':comboId' => $comboId]);
    }

    /**
     * Cập nhật items của combo (xóa cũ, thêm mới - dùng INSERT ON DUPLICATE KEY)
     */
    public function updateItems($comboId, $products)
    {
        try {
            error_log("updateItems - Starting for comboId=$comboId with " . count($products) . " products");
            
            // Get ProductIds to keep
            $newProductIds = array_column($products, 'productId');
            $newProductIds = array_map('intval', $newProductIds);
            
            error_log("updateItems - newProductIds: " . json_encode($newProductIds));

            // Delete items that are NOT in the new list
            if (!empty($newProductIds)) {
                $placeholders = implode(',', array_fill(0, count($newProductIds), '?'));
                $deleteQuery = "DELETE FROM {$this->comboItemTable} WHERE comboId = ? AND productId NOT IN ($placeholders)";
                $params = array_merge([$comboId], $newProductIds);
                
                $stmt = $this->conn->prepare($deleteQuery);
                $stmt->execute($params);
                error_log("updateItems - Deleted old items not in new list");
            } else {
                // If no products, delete all
                $stmt = $this->conn->prepare("DELETE FROM {$this->comboItemTable} WHERE comboId = :comboId");
                $stmt->execute([':comboId' => $comboId]);
                error_log("updateItems - Deleted all items (empty products list)");
            }

            // Now insert or update existing items using ON DUPLICATE KEY
            $query = "INSERT INTO {$this->comboItemTable} (comboId, productId, quantity, sortOrder) 
                      VALUES (:comboId, :productId, :quantity, :sortOrder)
                      ON DUPLICATE KEY UPDATE quantity = VALUES(quantity), sortOrder = VALUES(sortOrder)";
            $stmt = $this->conn->prepare($query);

            $sortOrder = 0;
            foreach ($products as $product) {
                $result = $stmt->execute([
                    ':comboId' => $comboId,
                    ':productId' => (int)$product['productId'],
                    ':quantity' => (int)($product['quantity'] ?? 1),
                    ':sortOrder' => $sortOrder++
                ]);
                error_log("updateItems - Insert product {$product['productId']}: " . ($result ? 'success' : 'failed'));
            }

            error_log("updateItems - Complete success");
            return true;
        } catch (\Exception $e) {
            error_log("updateItems - Exception: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Thêm sản phẩm vào combo
     */
    public function addItems($comboId, $products)
    {
        if (empty($products)) {
            return true;
        }

        try {
            // Check for duplicate entries first
            foreach ($products as $product) {
                $productId = (int)$product['productId'];
                $stmt = $this->conn->prepare("SELECT COUNT(*) as cnt FROM {$this->comboItemTable} WHERE comboId = :comboId AND productId = :productId");
                $stmt->execute([':comboId' => $comboId, ':productId' => $productId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row['cnt'] > 0) {
                    error_log("addItems - Combo $comboId already has product $productId, deleting old entry first");
                    // Delete existing entry
                    $delStmt = $this->conn->prepare("DELETE FROM {$this->comboItemTable} WHERE comboId = :comboId AND productId = :productId");
                    $delStmt->execute([':comboId' => $comboId, ':productId' => $productId]);
                }
            }

            $query = "INSERT INTO {$this->comboItemTable} (comboId, productId, quantity, sortOrder) 
                      VALUES (:comboId, :productId, :quantity, :sortOrder)";
            $stmt = $this->conn->prepare($query);

            $sortOrder = 0;
            foreach ($products as $product) {
                $stmt->execute([
                    ':comboId' => $comboId,
                    ':productId' => (int)$product['productId'],
                    ':quantity' => (int)($product['quantity'] ?? 1),
                    ':sortOrder' => $sortOrder++
                ]);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Xóa sản phẩm khỏi combo
     */
    public function removeItem($comboId, $productId)
    {
        $query = "DELETE FROM {$this->comboItemTable} WHERE comboId = :comboId AND productId = :productId";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':comboId' => $comboId,
            ':productId' => $productId
        ]);
    }

    /**
     * Lấy danh sách sản phẩm trong combo
     */
    public function getComboItems($comboId)
    {
        $query = "SELECT ci.comboItemId, ci.productId, ci.quantity, ci.sortOrder, 
                         p.name, p.description, p.imagePath
                  FROM {$this->comboItemTable} ci
                  JOIN product p ON ci.productId = p.productId
                  WHERE ci.comboId = :comboId
                  ORDER BY ci.sortOrder ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':comboId' => $comboId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tính tổng giá sản phẩm trong combo
     */
    public function calculateTotalProductPrice($comboId)
    {
        $query = "SELECT SUM(pv.price * ci.quantity) as total
                  FROM {$this->comboItemTable} ci
                  JOIN product_variant pv ON ci.productId = pv.productId
                  WHERE ci.comboId = :comboId
                  GROUP BY ci.comboId";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':comboId' => $comboId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float)($result['total'] ?? 0);
    }

    /**
     * Tìm combo theo tên
     */
    public function searchByName($name)
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE name LIKE :name AND deletedAt IS NULL
                  ORDER BY createdAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':name' => "%$name%"]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $combos = [];
        foreach ($rows as $row) {
            $combo = Combo::fromArray($row);
            $combo->items = $this->getComboItems($combo->comboId);
            $combos[] = $combo;
        }

        return $combos;
    }

    /**
     * Kiểm tra xem combo có tồn tại không
     */
    public function exists($comboId)
    {
        $query = "SELECT 1 FROM {$this->table} WHERE comboId = :comboId AND deletedAt IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':comboId' => $comboId]);
        return $stmt->rowCount() > 0;
    }
}
