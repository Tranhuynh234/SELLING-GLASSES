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
        ob_start();

        try {
            $data = [];
            $data['orderId'] = $_POST['orderId'] ?? $_POST['orderItemId'] ?? null;
            $data['reason'] = $_POST['reason'] ?? '';
            $data['note'] = $_POST['note'] ?? '';
            $data['imagePath'] = null;

            if (!empty($_FILES['return_img']) && ($_FILES['return_img']['error'] ?? 1) === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . "/../../public/assets/images/returns/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $orig = $_FILES['return_img']['name'];
                $ext = pathinfo($orig, PATHINFO_EXTENSION);
                $filename = 'return_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $target = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['return_img']['tmp_name'], $target)) {
                    $data['imagePath'] = 'assets/images/returns/' . $filename; 
                }
            }

            $success = $this->service->requestReturn($data);

            ob_end_clean();
            echo json_encode(["success" => $success]);
        } catch (Throwable $e) {
            if (ob_get_level()) ob_end_clean();
            error_log('requestReturn error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function getComplaints() {
        ob_clean();
        header("Content-Type: application/json");
        $type = $_GET['type'] ?? 'all';
        $data = $this->service->getComplaints($type);
        echo json_encode(["success" => true, "data" => $data]);
        exit();
    }

    public function processRequest() {
        ob_clean();
        header("Content-Type: application/json");
        $returnId = $_POST['returnId'] ?? null;
        $action = $_POST['action'] ?? null;

        if (!$returnId || !$action) {
            echo json_encode(["success" => false, "message" => "Thiếu dữ liệu xử lý."]);
            exit();
        }

        $result = $this->service->processRequest($returnId, $action);
        echo json_encode($result);
        exit();
    }
}


