<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/product/Product.php";

class ProductModel extends BaseModel {
    protected $table = "product";

    // Tìm theo tên sản phẩm
    public function findByName($name) {
        $data = $this->findBy("name", $name);
        return $data ? new Product($data) : null;
    }

    public function findProduct($id) {
        $data = $this->find($id, "productId");
        return $data ? new Product($data) : null;
    }

    public function getAllProducts() {
        $rows = $this->all();
        $products = [];
        foreach ($rows as $row) {
            $products[] = new Product($row);
        }
        return $products;
    }
    
public function getProducts($limit, $offset) {
    $sql = "SELECT
                p.*,
                pv.variantId,
                pv.price,
                pv.stock,
                pv.color,
                pv.size
            FROM product p
            LEFT JOIN product_variant pv
                ON pv.variantId = (
                    SELECT pv2.variantId
                    FROM product_variant pv2
                    WHERE pv2.productId = p.productId
                    ORDER BY pv2.variantId ASC
                    LIMIT 1
                )
            ORDER BY p.productId DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $products = [];
    foreach ($rows as $row) {
        $products[] = new Product($row);
    }
    return $products;
}


   // CRUD SẢN PHẨM 
public function addProduct($data) {
        return $this->create([
            'name'        => $data['name'],
            'description' => $data['description'],
            'categoryId'  => $data['categoryId'],
            'imagePath'   => $data['imagePath'] ?? null,
            'staffId'     => $data['staffId'],
            'price'       => $data['price']
        ]);
    }
    // 2. Thêm biến thể (Bảng product_variant)
    public function addVariant($data) {
        $sql = "INSERT INTO product_variant (color, size, price, productId, stock) 
                VALUES (:color, :size, :price, :productId, :stock)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':color'     => $data['color'],
            ':size'      => $data['size'],
            ':price'     => $data['price'],
            ':productId' => $data['productId'],
            ':stock'     => $data['stock']
        ]);
    }

    // 3. Xóa sản phẩm hoàn toàn 
    public function deleteProductComplete($id) {
        try {
            $this->conn->beginTransaction(); 

            // Xóa biến thể trước
            $sqlV = "DELETE FROM product_variant WHERE productId = :id";
            $stmtV = $this->conn->prepare($sqlV);
            $stmtV->execute([':id' => $id]);

            // Xóa sản phẩm chính
            $this->delete($id, "productId"); 

            $this->conn->commit(); 
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack(); 
            return false;
        }
    }

    // 4. Lấy dữ liệu kèm giá/kho và Tên danh mục để hiện lên bảng Manager
    public function getAllProductsWithVariants($categoryId = null) {
        if ($categoryId) {
            $sql = "SELECT p.*, c.name as categoryName,
                GROUP_CONCAT(CONCAT(v.color, ' (', v.size, ') - ', v.price, 'đ') SEPARATOR ', ') as variantSummary,
                MIN(v.price) as minPrice,
                SUM(v.stock) as totalStock
                FROM product p 
                LEFT JOIN product_variant v ON p.productId = v.productId 
                LEFT JOIN category c ON p.categoryId = c.categoryId
                WHERE p.categoryId = :categoryId
                GROUP BY p.productId
                ORDER BY p.productId DESC"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sql = "SELECT p.*, c.name as categoryName,
                GROUP_CONCAT(CONCAT(v.color, ' (', v.size, ') - ', v.price, 'đ') SEPARATOR ', ') as variantSummary,
                MIN(v.price) as minPrice,
                SUM(v.stock) as totalStock
                FROM product p 
                LEFT JOIN product_variant v ON p.productId = v.productId 
                LEFT JOIN category c ON p.categoryId = c.categoryId
                GROUP BY p.productId
                ORDER BY p.productId DESC"; 
            return $this->queryAll($sql);
        }
    }

    public function deleteProduct($id) {
        return $this->deleteProductComplete($id);
    }

    public function updateProduct($id, $data) {
        if (isset($data['imagePath']) && $data['imagePath'] !== null) {
            $sql = "UPDATE product SET name = :name, description = :desc, categoryId = :cat, imagePath = :img WHERE productId = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':img', $data['imagePath']);
        } else {
            $sql = "UPDATE product SET name = :name, description = :desc, categoryId = :cat WHERE productId = :id";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':desc', $data['description']);
        $stmt->bindValue(':cat', $data['categoryId']);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function deleteVariantsByProductId($id) {
        $sql = "DELETE FROM product_variant WHERE productId = ?";
        return $this->conn->prepare($sql)->execute([$id]);
    }

   public function countProducts() {
        $sql = "SELECT COUNT(*) AS total FROM product";
        $result = $this->queryOne($sql);
        return $result['total'];
    }
public function searchProducts($keyword) {
    $sql = "SELECT productId, name, price FROM product
            WHERE name LIKE ? OR productId LIKE ? 
            LIMIT 50";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$keyword, $keyword]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}