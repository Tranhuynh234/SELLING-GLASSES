<?php
require_once __DIR__ . "/../models/order/OrderModel.php";

class OrderService {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function createOrder($data) {
        if (empty($data['customerId'])) {
            return ["success" => false, "message" => "Thiếu customerId"];
        }

        $data['orderDate'] = date('Y-m-d H:i:s');
        $data['status'] = 'Pending';
        $data['totalPrice'] = $data['totalPrice'] ?? 0;

        $orderId = $this->orderModel->create($data);

        return [
            "success" => !!$orderId,
            "orderId" => $orderId
        ];
    }

    public function getOrdersByStatus($status) {
        $data = $this->orderModel->getOrdersForOps($status); 
        return ["success" => true, "data" => $data];
    }

    public function updateStatus($orderId, $status, $trackingCode = null) {
    if (!$orderId || !$status) {
        return [
            "success" => false,
            "message" => "Thiếu dữ liệu"
        ];
    }

    // CHỈ update status để tránh lỗi cột không tồn tại
    $updateData = [
        'status' => $status
    ];

    $res = $this->orderModel->updateOrder($orderId, $updateData);

    return [
        "success" => $res,
        "message" => $res
            ? "Cập nhật đơn hàng thành công"
            : "Lỗi database khi update đơn"
    ];
}

    public function cancelOrder($orderId) {
        return $this->updateStatus($orderId, 'Cancelled');
    }

    public function returnOrder($orderId) {
        return $this->updateStatus($orderId, 'Returned');
    }

    // Thống kê cho Dashboard
    public function getOrderStats() {
        $data = $this->orderModel->countByStatus();
        return ["success" => true, "data" => $data];
    }
}
