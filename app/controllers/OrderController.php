<?php
require_once __DIR__ . "/../services/OrderServices.php";
require_once __DIR__ . "/../services/PaymentService.php";

class OrderController {
    private $orderService;
    private $paymentService;

    public function __construct() {
        $this->orderService = new OrderService();
        $this->paymentService = new PaymentService();
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

    public function showDetail($orderId) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $db = Database::connect();
        
        // 1. Lấy thông tin chung của đơn hàng
        $stmtOrder = $db->prepare("SELECT * FROM orders WHERE orderId = :id AND userId = :uid");
        $stmtOrder->execute([':id' => $orderId, ':uid' => $_SESSION['user']['userId']]);
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            die("Đơn hàng không tồn tại hoặc bạn không có quyền xem.");
        }

        // 2. Lấy danh sách sản phẩm và đơn kính đi kèm (JOIN bảng prescription)
        $query = "SELECT oi.*, p.name as product_name, p.image as product_image, 
                        pr.leftEye, pr.rightEye, pr.leftPD, pr.rightPD
                FROM order_items oi
                JOIN products p ON oi.productId = p.productId
                LEFT JOIN prescription pr ON oi.orderItemId = pr.orderItemId
                WHERE oi.orderId = :orderId";
        
        $stmtItems = $db->prepare($query);
        $stmtItems->execute([':orderId' => $orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        // 3. Gọi View hiển thị
        include __DIR__ . '/../views/auth/profile.php';
    }

    public function cancelOrder() {
        header('Content-Type: application/json');
        $orderId = $_POST['orderId'] ?? null;
        $userId = $_SESSION['user']['userId'] ?? null;

        if (!$orderId || !$userId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng']);
            return;
        }

        try {
            $db = Database::connect();
            // 1. Kiểm tra trạng thái đơn hàng và đúng khách đặt đơn
            $stmt = $db->prepare("SELECT status FROM orders o 
                                JOIN customers c ON o.customerId = c.customerId 
                                WHERE o.orderId = ? AND c.userId = ?");
            $stmt->execute([$orderId, $userId]);
            $order = $stmt->fetch();

            if (!$order) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
            } elseif ($order['status'] !== 'Pending') {
                echo json_encode(['success' => false, 'message' => 'Chỉ được hủy đơn hàng khi đang chờ xử lý']);
            } else {
                // 2. Cập nhật trạng thái thành Cancelled
                $update = $db->prepare("UPDATE orders SET status = 'Cancelled' WHERE orderId = ?");
                $update->execute([$orderId]);
                echo json_encode(['success' => true]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}