<?php
require_once __DIR__ . "/../models/order/OrderModel.php";
require_once __DIR__ . "/../services/OrderServices.php";

class OrderController {
    private $orderService;
    private $orderModel;

    public function __construct() { 
        $this->orderService = new OrderService();
        $this->orderModel = new OrderModel(); 
    }

    // CREATE (POST)
    public function create() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->createOrder($_POST));
        exit();
    }

    // GET BY STATUS (GET)
    public function getByStatus() {
        header("Content-Type: application/json");

        $status = $_GET['status'] ?? 'all';

        echo json_encode($this->orderService->getOrdersByStatus($status));
        exit();
    }

    // UPDATE STATUS (POST)
    public function updateStatus() {
        header("Content-Type: application/json");
        
        $orderId = $_POST['orderId'] ?? null;
        $status = $_POST['status'] ?? null;
        $trackingCode = $_POST['trackingCode'] ?? null;
        $carrier = $_POST['carrier'] ?? 'GHN';

        if (!$orderId || !$status) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu dữ liệu"
            ]);
            exit();
        }

        // 1. Update orders
        $res = $this->orderService->updateStatus($orderId, $status, $trackingCode);

        // 2. Nếu update thành công + shipped thì insert shipment
        if ($res['success'] && $status === 'Shipped' && $trackingCode) {

            $this->orderModel->createShipment([
                'orderId'      => $orderId,
                'trackingCode' => $trackingCode,
                'carrier'      => $carrier,
                'status'       => 'In Transit',
                'staffId'      => 1
            ]);
        }

        echo json_encode($res);
        exit();
    }

    // DASHBOARD STATS
    public function stats() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->getOrderStats());
        exit();
    }
}
