<?php
require_once __DIR__ . "/../../config/db_connect.php";
require_once __DIR__ . "/../services/PromotionServices.php";

class PromotionController {
    private $service;

    public function __construct($conn) {
        $this->service = new PromotionServices($conn);
    }

    public function handleRequest() {
        header("Content-Type: application/json");

        $action = $_GET['action'] ?? '';

        switch ($action) {

            case 'create':
                echo json_encode([
                    "success" => $this->service->createPromotion($_POST)
                ]);
                break;

            case 'apply':
                echo json_encode([
                    "success" => $this->service->applyPromotion(
                        $_POST['promotionId'],
                        $_POST['productId']
                    )
                ]);
                break;

            case 'prescription':
                echo json_encode([
                    "success" => $this->service->uploadPrescription($_POST)
                ]);
                break;

            case 'return':
                echo json_encode([
                    "success" => $this->service->requestReturn($_POST)
                ]);
                break;

            default:
                echo json_encode([
                    "error" => "Invalid action"
                ]);
        }
    }
}