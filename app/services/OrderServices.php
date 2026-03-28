// app/services/OrderServices.php
<?php
require_once __DIR__ . "/../models/order/OrderModel.php";
require_once __DIR__ . "/../models/order/OrderItemModel.php";
require_once __DIR__ . "/../models/order/PaymentModel.php";
require_once __DIR__ . "/../models/order/ShipmentModel.php";

class OrderServices {
    private $orderModel;
    private $orderItemModel;
    private $paymentModel;
    private $shipmentModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentModel = new PaymentModel();
        $this->shipmentModel = new ShipmentModel();
    }

    // Nghiệp vụ 1: Tạo đơn hàng liên kết 4 bảng
    public function createOrder($data) {
        try {
            // 1. Lưu vào bảng Order
            $orderId = $this->orderModel->createAndReturnId([
                'orderDate' => date('Y-m-d H:i:s'),
                'status' => 'Pending',
                'totalPrice' => $data['totalPrice'],
                'customerId' => $data['customerId']
            ]);

            // 2. Lưu vào bảng OrderItem (Mảng các sản phẩm)
            foreach ($data['items'] as $item) {
                $this->orderItemModel->insert([
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'orderId' => $orderId,
                    'variantId' => $item['variantId']
                ]);
            }

            // 3. Khởi tạo Payment
            $this->paymentModel->insert([
                'paymentMethod' => $data['paymentMethod'],
                'paymentStatus' => 'Unpaid',
                'orderId' => $orderId
            ]);

            // 4. Khởi tạo Shipment với mã tracking tự động
            $this->shipmentModel->insert([
                'trackingCode' => uniqid('TRK-'),
                'carrier' => 'Standard',
                'status' => 'Preparing',
                'orderId' => $orderId
            ]);

            return ["status" => "success", "message" => "Tạo đơn hàng thành công", "orderId" => $orderId];

        } catch (Exception $e) {
            return ["status" => "error", "message" => "Lỗi tạo đơn: " . $e->getMessage()];
        }
    }

    // Nghiệp vụ 2: Xử lý thanh toán
    public function processPayment($orderId, $status) {
        // Cập nhật paymentStatus trong bảng Payment dựa vào orderId
    }

    // Nghiệp vụ 3: Tracking vận chuyển
    public function trackShipment($trackingCode) {
    }
}
?>