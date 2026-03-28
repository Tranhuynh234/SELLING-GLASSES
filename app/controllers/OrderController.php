<?php
require_once __DIR__ . "/../services/OrderServices.php";

class OrderController {
    private $orderService;

    public function __construct() {
        $this->orderService = new OrderServices();
    }

    // 1. API: Tạo đơn hàng
    public function createOrder() {
        // Lấy dữ liệu JSON từ body request
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data)) {
            echo json_encode(["status" => "error", "message" => "Dữ liệu đầu vào không hợp lệ hoặc trống"]);
            return;
        }

        $result = $this->orderService->createOrder($data);
        echo json_encode($result);
    }

    // 2. API: Lấy chi tiết đơn hàng
    public function getOrderDetail($id) {
        if (!$id) {
            echo json_encode(["status" => "error", "message" => "Thiếu ID đơn hàng"]);
            return;
        }

        // Gọi hàm getOrderDetail bên OrderServices (bạn có thể bổ sung logic này bên file Service nếu chưa có)
        // $result = $this->orderService->getOrderDetail($id);
        
        // Code tạm thời nếu bạn chưa viết hàm chi tiết bên Service:
        echo json_encode(["status" => "success", "message" => "Đang lấy chi tiết cho đơn hàng ID: " . $id]);
    }

    // 3. API: Xử lý thanh toán
    public function payment($orderId) {
        if (!$orderId) {
            echo json_encode(["status" => "error", "message" => "Thiếu ID đơn hàng để thanh toán"]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data['paymentStatus'] ?? 'Paid'; 

        $result = $this->orderService->processPayment($orderId, $status);
        echo json_encode($result);
    }

    // 4. API: Tracking giao hàng
    public function shipmentTracking($trackingNumber) {
        if (!$trackingNumber) {
            echo json_encode(["status" => "error", "message" => "Thiếu mã Tracking"]);
            return;
        }

        $result = $this->orderService->trackShipment($trackingNumber);
        echo json_encode($result);
    }
}