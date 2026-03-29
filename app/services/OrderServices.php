<?php
require_once __DIR__ . "/../models/order/OrderModel.php";

class OrderService {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    // 🔥 Tạo order
    public function createOrder($data) {
        try {
            // ✅ validate
            if (empty($data['customerId'])) {
                return [
                    "success" => false,
                    "message" => "customerId không được để trống"
                ];
            }

            // ✅ gán mặc định
            $data['orderDate'] = date('Y-m-d H:i:s');
            $data['status'] = $data['status'] ?? 'Pending';
            $data['totalPrice'] = $data['totalPrice'] ?? 0;

            // 🔥 insert DB
            $orderId = $this->orderModel->create($data);

            return [
                "success" => $orderId ? true : false,
                "message" => $orderId ? "Tạo order thành công" : "Tạo thất bại",
                "orderId" => $orderId
            ];

        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    // 🔥 lấy tất cả order
    public function getAllOrders() {
        return $this->orderModel->all();
    }

    // 🔥 lấy order theo customer
    public function getOrdersByCustomer($customerId) {
        return $this->orderModel->findByCustomer($customerId);
    }

    // 🔥 xóa order
    public function deleteOrder($id) {
        $result = $this->orderModel->delete($id);

        return [
            "success" => $result,
            "message" => $result ? "Xóa thành công" : "Xóa thất bại"
        ];
    }
}