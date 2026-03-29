<?php
require_once __DIR__ . "/../services/OrderServices.php";

class OrderController {
    private $orderService;

    public function __construct() {
        $this->orderService = new OrderService();
    }

    // CREATE (POST)
    public function create() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->createOrder($_POST));
    }

    // GET BY STATUS (GET)
    public function getByStatus() {
        header("Content-Type: application/json");

        $status = $_GET['status'] ?? null;

        if (!$status) {
            echo json_encode(["success" => false, "message" => "Thiếu status"]);
            return;
        }

        echo json_encode($this->orderService->getOrdersByStatus($status));
    }

    // UPDATE STATUS (POST)
    public function updateStatus() {
        header("Content-Type: application/json");

        $orderId = $_POST['orderId'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$orderId || !$status) {
            echo json_encode(["success" => false, "message" => "Thiếu dữ liệu"]);
            return;
        }

        echo json_encode($this->orderService->updateStatus($orderId, $status));
    }

    // CANCEL
    public function cancel() {
        header("Content-Type: application/json");

        $orderId = $_POST['orderId'] ?? null;

        if (!$orderId) {
            echo json_encode(["success" => false, "message" => "Thiếu orderId"]);
            return;
        }

        echo json_encode($this->orderService->cancelOrder($orderId));
    }

    // RETURN
    public function return() {
        header("Content-Type: application/json");

        $orderId = $_POST['orderId'] ?? null;

        if (!$orderId) {
            echo json_encode(["success" => false, "message" => "Thiếu orderId"]);
            return;
        }

        echo json_encode($this->orderService->returnOrder($orderId));
    }

    // STATS
    public function stats() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->getOrderStats());
    }
}