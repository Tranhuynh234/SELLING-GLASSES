<?php

require_once __DIR__ . "/../services/PaymentService.php";
require_once __DIR__ . "/../models/StaffModel.php";
require_once __DIR__ . "/../middleware/AuthMiddleware.php";

class PaymentController {
    private $paymentService;
    private $staffModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->paymentService = new PaymentService();
        $this->staffModel = new StaffModel();
    }

    private function sendJson($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function requireManager($json = true) {
        if (!isset($_SESSION['user'])) {
            if ($json) {
                $this->sendJson([
                    "success" => false,
                    "message" => "Unauthorized",
                    "redirect" => "/SELLING-GLASSES/public/auth"
                ]);
            }

            header("Location: /SELLING-GLASSES/public/auth");
            exit();
        }

        $user = $_SESSION['user'];
        $isManager = ($user['role'] ?? null) === 'staff'
            && $this->staffModel->isManagerByUserId($user['userId'] ?? 0);

        if (!$isManager) {
            if ($json) {
                $this->sendJson([
                    "success" => false,
                    "message" => "Access denied"
                ]);
            }

            http_response_code(403);
            echo "Access denied";
            exit();
        }

        return $user;
    }

    public function showCheckoutPage() {
        if (!isset($_SESSION['user'])) {
            header("Location: /SELLING-GLASSES/public/auth");
            exit();
        }

        require_once __DIR__ . "/../views/order/checkout.html";
        exit();
    }

    public function getCheckoutSummary() {
        $user = AuthMiddleware::handle();
        $selectedCartItems = $_GET['selected'] ?? '';
        $this->sendJson(
            $this->paymentService->getCheckoutSummary($user['userId'], $selectedCartItems)
        );
    }

    public function createPendingPayment() {
        $user = AuthMiddleware::handle();
        $this->sendJson(
            $this->paymentService->createPendingPayment($user['userId'], $_POST)
        );
    }

    public function showAdminPage() {
        $this->requireManager(false);
        require_once __DIR__ . "/../views/Dashboard/manager/payment_admin.php";
        exit();
    }

    public function getPaymentRequests() {
        $this->requireManager(true);
        $status = $_GET['status'] ?? 'pending';
        $this->sendJson(
            $this->paymentService->getAdminPayments($status)
        );
    }

    public function approvePayment() {
        $user = $this->requireManager(true);
        $paymentId = $_POST['paymentId'] ?? null;

        if (!$paymentId) {
            $this->sendJson([
                "success" => false,
                "message" => "Thiếu paymentId"
            ]);
        }

        $this->sendJson(
            $this->paymentService->approvePayment((int) $paymentId, (int) $user['userId'])
        );
    }
}
