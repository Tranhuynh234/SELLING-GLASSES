<?php
require_once __DIR__ . "/../models/order/OrderModel.php";

class OrderService {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function createOrder($data) {
  
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (session_status() === PHP_SESSION_NONE) session_start();


        if (empty($data['customerId'])) {
            return ["success" => false, "message" => "Thiếu customerId"];
        }

        $db = Database::connect();

        try {
            $db->beginTransaction();

            // Tạo order
            $data['orderDate'] = date('Y-m-d H:i:s');
            $data['status'] = 'Pending';
            $data['totalPrice'] = $data['totalPrice'] ?? 0;
            $data['subtotal'] = $data['subtotal'] ?? 0;
            $data['lensCost'] = $data['lensCost'] ?? 0;
            $data['shippingFee'] = $data['shippingFee'] ?? 0;
            $data['discount'] = $data['discount'] ?? 0;

            $orderId = $this->orderModel->create($data);

            if (!$orderId) throw new Exception("Không tạo được order");

            // Lấy giỏ hàng từ session
            $cart = $_SESSION['cart'] ?? [];

            require_once __DIR__ . '/../models/Prescription.php';

            foreach ($cart as $item) {

                // INSERT order_item
                $stmt = $db->prepare("
                    INSERT INTO order_items (orderId, productId, quantity, price)
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->execute([
                    $orderId,
                    $item['productId'],
                    $item['quantity'],
                    $item['price']
                ]);

                $orderItemId = $db->lastInsertId();

                // GẮN PRESCRIPTION
                if (!empty($_SESSION['prescription_data'])) {

                    $p = $_SESSION['prescription_data'];

                    $pres = new Prescription();
                    $pres->userId = $p['userId'] ?? ($_SESSION['user']['userId'] ?? null);
                    $pres->orderItemId = $orderItemId;
                    $pres->leftEye = $p['leftEye'];
                    $pres->rightEye = $p['rightEye'];
                    $pres->leftPD = $p['leftPD'];
                    $pres->rightPD = $p['rightPD'];
                    $pres->imagePath = $p['imagePath'];

                    $pres->save($db);

                    unset($_SESSION['prescription_data']);
                }
            }

            $db->commit();

            return [
                "success" => true,
                "orderId" => $orderId
            ];

        } catch (Exception $e) {
            $db->rollBack();
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
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