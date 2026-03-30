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
        return [
            "success" => true,
            "data" => $this->orderModel->findByStatus($status)
        ];
    }

    public function updateStatus($orderId, $status) {
        return [
            "success" => $this->orderModel->update($orderId, ['status' => $status])
        ];
    }

    public function cancelOrder($orderId) {
        return $this->updateStatus($orderId, 'Cancelled');
    }

    public function returnOrder($orderId) {
        return $this->updateStatus($orderId, 'Returned');
    }

    public function getOrderStats() {
        return [
            "success" => true,
            "data" => $this->orderModel->countByStatus()
        ];
    }
}