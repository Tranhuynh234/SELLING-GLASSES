    <?php
    require_once __DIR__ . "/../../core/BaseModel.php";
    require_once __DIR__ . "/../../entities/promotion/Promotion.php"; 

    class PromotionModel extends BaseModel {
        protected $table = "promotion";

        /**
         * Lấy chi tiết khuyến mãi và map vào mảng hoặc Entity
         */
        public function getPromotionDetail($id) {
            // Sử dụng hàm find() có sẵn của BaseModel
            $promotion = $this->find($id, "promotionId");
            if (!$promotion) return null;

            // Lấy danh sách sản phẩm thuộc khuyến mãi
            $sqlProd = "SELECT p.productId, p.name, p.price
                        FROM promotion_product pp
                        JOIN product p ON pp.productId = p.productId
                        WHERE pp.promotionId = ?";
            
            $products = $this->queryAll($sqlProd, [$id]);

            return [
                "promotion" => $promotion, // Nếu có Entity: new Promotion($promotion)
                "products" => $products
            ];
        }

        /**
         * Tìm kiếm khuyến mãi (Giống searchUsers)
         */
        public function searchPromotions($filters = [], $page = 1, $limit = 10) {
            $offset = ($page - 1) * $limit;
            
            $sql = "SELECT * FROM {$this->table} WHERE name LIKE :kw";
            $params = [':kw' => "%" . ($filters['keyword'] ?? '') . "%"];

            if (!empty($filters['status'])) {
                $sql .= " AND status = :status";
                $params[':status'] = $filters['status'];
            }

            $sql .= " ORDER BY promotionId DESC LIMIT $limit OFFSET $offset";

            return $this->queryAll($sql, $params);
        }

        /**
         * Đếm tổng số để phân trang
         */
        public function countSearch($filters = []) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE name LIKE :kw";
            $params = [':kw' => "%" . ($filters['keyword'] ?? '') . "%"];

            $res = $this->queryOne($sql, $params);
            return $res['total'] ?? 0;
        }

        /**
         * Lấy khuyến mãi đang chạy cho sản phẩm (Promotion Engine)
         */
        public function getActivePromotionByProductId($productId) {
            $sql = "SELECT p.* FROM {$this->table} p
                    JOIN promotion_product pp ON p.promotionId = pp.promotionId
                    WHERE pp.productId = :pid 
                    AND p.status = 'active'
                    AND NOW() BETWEEN p.startDate AND p.endDate
                    ORDER BY p.discount DESC LIMIT 1";
                    
            return $this->queryOne($sql, [':pid' => $productId]);
        }

        /**
         * Tạo khuyến mãi (Sử dụng create của BaseModel)
         */
   public function createPromotion($data) {

    $promotionId = $this->create([
        "name"         => $data['name'],
        "discount"     => $data['discount'],
        "discountType" => $data['discountType'],
        "startDate"    => $data['startDate'],
        "endDate"      => $data['endDate'],
        "staffId"      => $data['staffId'],
        "status"       => $data['status'] ?? 'active'
    ]);

    return $promotionId;
}

        /**
         * Cập nhật khuyến mãi (Sử dụng update của BaseModel)
         */
        public function updatePromotion($id, $data, $productIds = []) {
            // 1. Dùng hàm update() của BaseModel
            $result = $this->update($id, $data, "promotionId");

            // 2. Xóa cũ và thêm mới quan hệ sản phẩm
            $delSql = "DELETE FROM promotion_product WHERE promotionId = ?";
            $stmt = $this->conn->prepare($delSql);
            $stmt->execute([$id]);

            if (!empty($productIds)) {
                $this->saveRelationProducts($id, $productIds);
            }

            return $result;
        }

        /**
         * Xóa khuyến mãi (Sử dụng delete của BaseModel)
         */
        public function deletePromotion($id) {
            // Xóa quan hệ trước
            $this->conn->prepare("DELETE FROM promotion_product WHERE promotionId = ?")->execute([$id]);
            // Xóa bảng chính
            return $this->delete($id, "promotionId");
        }

        /**
         * Helper chèn nhiều sản phẩm vào bảng trung gian
         */
        public function saveRelationProducts($promotionId, $productIds) {
            $placeholders = [];
            $values = [];
            foreach ($productIds as $pid) {
                $placeholders[] = "(?, ?)";
                array_push($values, $promotionId, $pid);
            }
            $sql = "INSERT INTO promotion_product (promotionId, productId) VALUES " . implode(',', $placeholders);
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($values);
        }
        
    }