<?php
require_once __DIR__ . "/../models/promotion/PromotionModel.php";
require_once __DIR__ . "/../entities/promotion/Promotion.php";
require_once __DIR__ . "/../models/promotion/PromotionProductModel.php";

class PromotionService {
    private $model;
    private $promotionProductModel;

    public function __construct() {
        $this->model = new PromotionModel();
        $this->promotionProductModel = new PromotionProductModel();
    }

    // Nhận thêm tham số $name
    public function getPromotions($page = 1, $limit = 5, $name = "") {
        $rows = $this->model->getPaginated($page, $limit, $name);
        $data = [];

        foreach ($rows as $row) {
            $promotion = new Promotion();
            $promotion->setPromotionId($row['promotionId']);
            $promotion->setName($row['name']);
            $promotion->setDiscount($row['discount']);
            $promotion->setDiscountType($row['discountType']);
            $promotion->setStartDate($row['startDate']);
            $promotion->setEndDate($row['endDate']);
            $promotion->setStatus($row['status']);
            $promotion->setStaffId($row['staffId']);
            $data[] = $promotion;
        }
        return $data;
    }

    // Tính tổng số trang dựa trên từ khóa
    public function getTotalPages($limit, $name = "") {
        $total = $this->model->countAllWithSearch($name);
        return ceil($total / $limit);
    }
    public function deletePromotion($id) {
    return $this->model->deletePromotion($id);
}
/// Lấy thông tin chi tiết để sửa
    public function getPromotionById($id) {
        return $this->model->find($id, "promotionId"); // Sử dụng find() từ BaseModel của bạn
    }

    // Xử lý logic cập nhật
    public function updatePromotion($id, $data) {
        // Bạn có thể thêm logic kiểm tra ở đây
        // Ví dụ: if ($data['discount'] > 100) return false;
        
        return $this->model->update($id, $data, "promotionId"); // Sử dụng update() từ BaseModel của bạn
    }
    public function createPromotion($data) {
    // Logic kiểm tra ngày tháng như bạn đã làm
    if (new DateTime($data['startDate']) > new DateTime($data['endDate'])) {
        throw new Exception("Ngày bắt đầu không thể sau ngày kết thúc.");
    }
    
    // Gọi hàm create có sẵn từ BaseModel thông qua PromotionModel
    return $this->model->create($data);
}
public function applyToProducts($promotionId, $productIds) {
        // 1. Kiểm tra đầu vào cơ bản
        if (empty($promotionId)) {
            return [
                'success' => false,
                'message' => 'Vui lòng chọn chương trình khuyến mãi.'
            ];
        }

        if (empty($productIds) || !is_array($productIds)) {
            return [
                'success' => false,
                'message' => 'Vui lòng chọn ít nhất một sản phẩm để áp dụng.'
            ];
        }

        // 2. Ép kiểu dữ liệu để đảm bảo an toàn (tất cả ID phải là số nguyên)
        $cleanProductIds = array_map('intval', $productIds);
        $promotionId = intval($promotionId);

        // 3. Gọi Model xử lý Transaction
        // Model sẽ lo việc: Begin Transaction -> Check Khuyến mãi -> Check Giá gốc -> Update -> Commit/Rollback
        return $this->promotionProductModel->applyPromotion($promotionId, $cleanProductIds);
    }
    public function cancelForProducts($productIds) {
    if (empty($productIds) || !is_array($productIds)) {
        return [
            'success' => false,
            'message' => 'Vui lòng chọn sản phẩm cần hủy khuyến mãi.'
        ];
    }

    $cleanProductIds = array_map('intval', $productIds);
    return $this->promotionProductModel->removePromotion($cleanProductIds);
}
public function cancelPromotion() {
    $productIds = $_POST['productIds'] ?? [];

    $service = new PromotionService();
    $result = $service->cancelForProducts($productIds);

    echo json_encode($result);
}
}