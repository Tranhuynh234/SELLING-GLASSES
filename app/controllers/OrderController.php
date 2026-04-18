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

    // ================================
    //  CREATE (POST)
    // ================================
    public function create() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->createOrder($_POST));
        exit();
    }

    // ================================
    //  GET BY STATUS (GET)
    // ================================
    public function getByStatus() {
        ob_clean();
        header('Content-Type: application/json');
        $status = $_GET['status'] ?? 'All';
        $result = $this->orderService->getOrdersByStatus($status);
        echo json_encode($result);
        exit();
    }

    // ================================
    //  UPDATE STATUS (POST)
    // ================================
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

        $res = $this->orderService->updateStatus($orderId, $status, $trackingCode);

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

    public function stats() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->getOrderStats());
        exit();
    }

    public function getOrderDetail() {
        ob_clean();
        header('Content-Type: application/json');
        $orderId = $_GET['orderId'] ?? null;
        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu Order ID']);
            exit();
        }

        $result = $this->orderService->getOrderDetail($orderId);
        echo json_encode($result);
        exit();
    }

    // ================================
    //  XỬ LÝ LIÊN HỆ TỰ ĐỘNG
    // ================================
    public function handleAutoContact() {
        ob_clean();
        header("Content-Type: application/json");
        $orderId = $_POST['orderId'] ?? null;
        $message = $_POST['message'] ?? '';
        if (!$orderId) {
            echo json_encode(["success" => false, "message" => "Thiếu orderId"]);
            exit();
        }
        $cleanMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        // Gọi qua Service để xử lý cả việc update is_contacted và lưu tin nhắn
        $result = $this->orderService->handleContactAndMessage($orderId, $message); 
        echo json_encode($result);
        exit();
    }

    // --- THÊM MỚI ---
    // 1. Hàm xử lý gửi tin nhắn
    public function contactCustomer() {
        ob_clean();
        header("Content-Type: application/json");
        $orderId = $_POST['orderId'] ?? null;
        $message = $_POST['message'] ?? '';
        if (!$orderId || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
            exit();
        }

        // --- BƯỚC NÂNG CẤP Ở ĐÂY ---
        // Loại bỏ các thẻ script, html nguy hiểm
        $cleanMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        // Truyền $cleanMessage thay vì $message gốc
        $result = $this->orderService->contactCustomer($orderId, $cleanMessage);
     
        echo json_encode($result);
        exit();
    }

    public function getCustomerMessages() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        ob_clean();
        header('Content-Type: application/json');

        $userId = $_SESSION['user']['userId'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để chat với nhân viên.']);
            exit();
        }

        $result = $this->orderService->getCustomerMessagesForUser($userId);
        echo json_encode([
            'success' => true,
            'orderId' => $result['orderId'],
            'data' => $result['messages']
        ]);
        exit();
    }

    public function getSupportUnreadCount() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        ob_clean();
        header('Content-Type: application/json');

        $userId = $_SESSION['user']['userId'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để xem số tin nhắn chưa đọc.']);
            exit();
        }

        $count = $this->orderService->getSupportUnreadCountForUser($userId);
        echo json_encode(['success' => true, 'unread' => $count]);
        exit();
    }

    public function sendCustomerMessage() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        ob_clean();
        header('Content-Type: application/json');

        $message = $_POST['message'] ?? '';
        $userId = $_SESSION['user']['userId'] ?? null;

        if (!$userId || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập và nhập tin nhắn.']);
            exit();
        }

        $cleanMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        $sent = $this->orderService->sendCustomerMessageForUser($userId, $cleanMessage);

        if ($sent) {
            echo json_encode(['success' => true, 'message' => 'Tin nhắn đã gửi đến nhân viên.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể gửi tin nhắn. Vui lòng kiểm tra lại đơn hàng hoặc đăng nhập.']);
        }
        exit();
    }

    // 2. Hàm lấy lịch sử tin nhắn
    public function getMessages() {
        ob_clean();
        header("Content-Type: application/json");

        $orderId = $_GET['orderId'] ?? null;
        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn hàng']);
            exit();
        }

        $result = $this->orderService->getMessages($orderId);
        echo json_encode($result);
        exit();
    }

    // ================================
    // LẤY DANH SÁCH HỘI THOẠI (Dựa trên tất cả đơn hàng)
    // ================================
    public function getConversationList() {
        ob_clean();
        header('Content-Type: application/json');
        $result = $this->orderService->getConversationList();
        echo json_encode($result);
        exit();
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
