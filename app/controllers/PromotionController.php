<?php

require_once dirname(__DIR__) . "/services/PromotionServices.php";

class PromotionController {

    private $promotionService;

    public function __construct() {
        $this->promotionService = new PromotionService();
    }

    // =========================
    // HELPER: GET JSON INPUT
    // =========================
    private function getJsonInput() {
        return json_decode(file_get_contents("php://input"), true);
    }

    // =========================
    // CREATE PROMOTION
    // =========================
    public function createPromotion() {
        header("Content-Type: application/json; charset=utf-8");

        $data = $this->getJsonInput() ?? $_POST;

        echo json_encode(
            $this->promotionService->createPromotion($data)
        );
    }

    // =========================
    // UPDATE PROMOTION
    // =========================
    public function updatePromotion() {
        header("Content-Type: application/json; charset=utf-8");

        $data = $this->getJsonInput() ?? $_POST;

        $id = $data['promotionId'] ?? null;

        if (!$id) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu promotionId"
            ]);
            return;
        }

        unset($data['promotionId']);

        echo json_encode(
            $this->promotionService->updatePromotion($id, $data)
        );
    }

    // =========================
    // DELETE PROMOTION
    // =========================
    public function deletePromotion() {
        header("Content-Type: application/json; charset=utf-8");

        $data = $this->getJsonInput() ?? $_POST;

        $id = $data['promotionId'] ?? null;

        if (!$id) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu promotionId"
            ]);
            return;
        }

        echo json_encode(
            $this->promotionService->deletePromotion($id)
        );
    }

    // =========================
    // GET DETAIL PROMOTION
    // =========================
    public function getPromotionDetail() {
        header("Content-Type: application/json; charset=utf-8");

        $id = $_GET['promotionId'] ?? null;

        if (!$id) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu promotionId"
            ]);
            return;
        }

        echo json_encode(
            $this->promotionService->getPromotionDetail($id)
        );
    }

    // =========================
    // SEARCH PROMOTION
    // =========================
    public function searchPromotions() {
        header("Content-Type: application/json; charset=utf-8");

        $filters = [
            "keyword" => $_GET['keyword'] ?? '',
            "status"  => $_GET['status'] ?? null
        ];

        $page  = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;

        echo json_encode(
            $this->promotionService->searchPromotions($filters, $page, $limit)
        );
    }

    // =========================
    // ACTIVE PROMOTION BY PRODUCT
    // =========================
    public function getActivePromotionByProduct() {
        header("Content-Type: application/json; charset=utf-8");

        $productId = $_GET['productId'] ?? null;

        if (!$productId) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu productId"
            ]);
            return;
        }

        echo json_encode(
            $this->promotionService->getActivePromotionByProduct($productId)
        );
    }
public function applyPromotion() {
    header("Content-Type: application/json");

    $input = json_decode(file_get_contents("php://input"), true);

    $promotionId = $input['promotionId'] ?? null;
    $productIds = $input['productIds'] ?? [];

    if (!$promotionId || empty($productIds)) {
        echo json_encode([
            "success" => false,
            "message" => "Thiếu dữ liệu"
        ]);
        return;
    }

    $result = $this->promotionService->applyPromotion($promotionId, $productIds);

    echo json_encode([
        "success" => true,
        "message" => "Áp dụng thành công",
        "data" => $result
    ]);
}
}
?>