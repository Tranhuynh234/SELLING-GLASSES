    <?php

    require_once __DIR__ . "/../models/promotion/PromotionModel.php";

    class PromotionService {

        private $promotionModel;

        public function __construct() {
            $this->promotionModel = new PromotionModel();
        }

        // =========================
        // CREATE PROMOTION (ADMIN)
        // =========================
     public function createPromotion($data) {

    // VALIDATION
    if (empty($data['name'])) {
        return $this->response(false, "Thiếu tên khuyến mãi");
    }

    if (!isset($data['discount']) || $data['discount'] < 0) {
        return $this->response(false, "Giảm giá không hợp lệ");
    }

    if (empty($data['startDate']) || empty($data['endDate'])) {
        return $this->response(false, "Thiếu ngày bắt đầu/kết thúc");
    }

    if (strtotime($data['startDate']) > strtotime($data['endDate'])) {
        return $this->response(false, "Ngày bắt đầu phải nhỏ hơn ngày kết thúc");
    }

    try {
        $this->promotionModel->beginTransaction();

        // CHỈ TẠO PROMOTION
        $promotionId = $this->promotionModel->createPromotion([
            "name"         => $data['name'],
            "discount"     => $data['discount'],
            "discountType" => $data['discountType'] ?? 'percent',
            "startDate"    => $data['startDate'],
            "endDate"      => $data['endDate'],
            "staffId"      => $data['staffId'] ?? null,
            "status"       => $data['status'] ?? 'active'
        ]);

        if (!$promotionId) {
            throw new Exception("Tạo khuyến mãi thất bại");
        }

        $this->promotionModel->commit();

        return $this->response(true, "Tạo khuyến mãi thành công", [
            "promotionId" => $promotionId
        ]);

    } catch (Exception $e) {
        $this->promotionModel->rollBack();

        return $this->response(false, "Lỗi: " . $e->getMessage());
    }
}
        // =========================
        // UPDATE PROMOTION
        // =========================
        public function updatePromotion($id, $data) {

            if (!$id) {
                return $this->response(false, "Thiếu promotionId");
            }

            if (isset($data['discount']) && $data['discount'] < 0) {
                return $this->response(false, "Discount không hợp lệ");
            }

            try {
                $this->promotionModel->beginTransaction();

                $result = $this->promotionModel->updatePromotion(
                    $id,
                    $data,
                    $data['productIds'] ?? []
                );

                $this->promotionModel->commit();

                return $this->response(
                    $result > 0,
                    $result > 0 ? "Cập nhật thành công" : "Không có thay đổi"
                );

            } catch (Exception $e) {
                $this->promotionModel->rollBack();

                return $this->response(false, "Lỗi: " . $e->getMessage());
            }
        }

        // =========================
        // DELETE PROMOTION
        // =========================
        public function deletePromotion($id) {

            if (!$id) {
                return $this->response(false, "Thiếu promotionId");
            }

            try {
                $this->promotionModel->beginTransaction();

                $result = $this->promotionModel->deletePromotion($id);

                $this->promotionModel->commit();

                return $this->response(
                    $result > 0,
                    $result > 0 ? "Xóa thành công" : "Không tìm thấy khuyến mãi"
                );

            } catch (Exception $e) {
                $this->promotionModel->rollBack();

                return $this->response(false, "Lỗi: " . $e->getMessage());
            }
        }

        // =========================
        // GET DETAIL PROMOTION
        // =========================
        public function getPromotionDetail($id) {

            if (!$id) {
                return $this->response(false, "Thiếu promotionId");
            }

            $data = $this->promotionModel->getPromotionDetail($id);

            if (!$data) {
                return $this->response(false, "Không tìm thấy khuyến mãi");
            }

            return $this->response(true, "Lấy chi tiết thành công", $data);
        }

        // =========================
        // SEARCH PROMOTION
        // =========================
        public function searchPromotions($filters = [], $page = 1, $limit = 10) {

            $data = $this->promotionModel->searchPromotions($filters, $page, $limit);
            $total = $this->promotionModel->countSearch($filters);

            return $this->response(true, "Search success", [
                "data" => $data,
                "total" => $total,
                "page" => $page,
                "limit" => $limit
            ]);
        }

        // =========================
        // ACTIVE PROMOTION FOR PRODUCT
        // =========================
        public function getActivePromotionByProduct($productId) {

            if (!$productId) {
                return $this->response(false, "Thiếu productId");
            }

            $data = $this->promotionModel->getActivePromotionByProductId($productId);

            return $this->response(true, "OK", $data);
        }
        public function applyPromotion($promotionId, $productIds) {
            return $this->promotionModel->saveRelationProducts($promotionId, $productIds);
        }
        // =========================
        // RESPONSE FORMAT
        // =========================
        private function response($success, $message, $data = null) {
            return [
                "success" => $success,
                "message" => $message,
                "data" => $data
            ];
        }

    }
 ?>