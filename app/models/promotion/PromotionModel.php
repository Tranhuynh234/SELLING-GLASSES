<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/promotion/Promotion.php";

class PromotionModel extends BaseModel {
    protected $table = "promotion";

    // Cải tiến: Thêm tham số $name để lọc
    public function getPaginated($page = 1, $limit = 5, $name = "") {
        $offset = ($page - 1) * $limit;
        
        // Sử dụng LIKE để tìm kiếm theo tên, nếu $name trống thì sẽ lấy tất cả
        $sql = "SELECT * FROM {$this->table}
                WHERE name LIKE :name
                ORDER BY promotionId DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng dựa trên từ khóa tìm kiếm
    public function countAllWithSearch($name = "") {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE name LIKE :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':name' => "%{$name}%"]);
        return $this->queryOne($sql, [':name' => "%{$name}%"])['total'];
    }
    public function deletePromotion($id) {
        return $this->delete($id, "promotionId");
    }
        // Lấy thông tin chi tiết để đổ vào form sửa
    public function getById($id) {
        return $this->find($id, "promotionId"); 
    }

    // Cập nhật dữ liệu mới
    public function updatePromotion($id, $data) {
        return $this->update($id, $data, "promotionId");
    }
}