<?php
require_once __DIR__ . "/../../config/db_connect.php";
require_once __DIR__ . "/../services/PromotionServices.php";

class PromotionController {
    private $service;

    public function __construct() {
          $conn = Database::connect(); 
        $this->service = new PromotionServices($conn);
    }

    public function createPromotion() {
        header("Content-Type: application/json");
        echo json_encode([
            "success" => $this->service->createPromotion($_POST)
        ]);
    }

    public function applyPromotion() {
        header("Content-Type: application/json");
        echo json_encode([
            "success" => $this->service->applyPromotion(
                $_POST['promotionId'] ?? null,
                $_POST['productId'] ?? null
            )
        ]);
    }

    public function uploadPrescription() {
        header("Content-Type: application/json");
        echo json_encode([
            "success" => $this->service->uploadPrescription($_POST)
        ]);
    }

    public function requestReturn() {
        header("Content-Type: application/json");
        echo json_encode([
            "success" => $this->service->requestReturn($_POST)
        ]);
    }
}
