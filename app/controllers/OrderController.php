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

    // QUẢN LÝ ĐƠN HÀNG 
    // TẠO ĐƠN HÀNG MỚI
    public function create() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->createOrder($_POST));
        exit();
    }

    // LẤY DANH SÁCH ĐƠN HÀNG THEO TRẠNG THÁI 
    public function getByStatus() {
        ob_clean();
        header('Content-Type: application/json');
        $status = $_GET['status'] ?? 'All';
        $result = $this->orderService->getOrdersByStatus($status);
        echo json_encode($result);
        exit();
    }

    // CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
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

    // THỐNG KÊ SỐ LƯỢNG ĐƠN HÀNG THEO TRẠNG THÁI
    public function stats() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->getOrderStats());
        exit();
    }

    // LẤY CHI TIẾT ĐƠN HÀNG
    public function getOrderDetail() {
        ob_clean();
        header('Content-Type: application/json');
        $orderId = $_GET['orderId'] ?? $_GET['order_id'] ?? null;
        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu Order ID']);
            exit();
        }

        $result = $this->orderService->getOrderDetail($orderId);
        echo json_encode($result);
        exit();
    }

    // TỰ ĐỘNG GỬI TIN NHẮN LIÊN HỆ KHI ĐẶT HÀNG
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
        $result = $this->orderService->handleContactAndMessage($orderId, $message); 
        echo json_encode($result);
        exit();
    }

    // HỆ THỐNG CHAT & TƯ VẤN KHÁCH HÀNG
    public function contactCustomer() {
        ob_clean();
        header("Content-Type: application/json");
        $orderId = $_POST['orderId'] ?? null;
        $message = $_POST['message'] ?? '';
        if (!$orderId || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
            exit();
        }

        $cleanMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        $result = $this->orderService->contactCustomer($orderId, $cleanMessage);
     
        echo json_encode($result);
        exit();
    }

    // LẤY TIN NHẮN CỦA KHÁCH HÀNG THEO ĐƠN HÀNG
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

    // LẤY SỐ TIN NHẮN CHƯA ĐỌC CỦA KHÁCH HÀNG
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

    // GỬI YÊU CẦU HỦY ĐƠN TỪ KHÁCH HÀNG
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

    // LẤY TIN NHẮN HỖ TRỢ THEO ĐƠN HÀNG
    public function getMessages() {
        ob_clean();
        header("Content-Type: application/json");

        $orderId = $_GET['orderId'] ?? null;

        $result = $this->orderService->getMessages($orderId);
        echo json_encode($result);
        exit();
    }

    // LẤY DANH SÁCH CUỘC TRÒ CHUYỆN GIỮA KHÁCH HÀNG VÀ NHÂN VIÊN
    public function getConversationList() {
        ob_clean();
        header('Content-Type: application/json');
        $result = $this->orderService->getConversationList();
        echo json_encode($result);
        exit();
    }

    // HIỂN THỊ CHI TIẾT ĐƠN HÀNG TRÊN TRANG CÁ NHÂN
    public function showDetail($orderId) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $db = Database::connect();
        
        $stmtOrder = $db->prepare("SELECT * FROM orders WHERE orderId = :id AND userId = :uid");
        $stmtOrder->execute([':id' => $orderId, ':uid' => $_SESSION['user']['userId']]);
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            die("Đơn hàng không tồn tại hoặc bạn không có quyền xem.");
        }

        $query = "SELECT oi.*, p.name as product_name, p.image as product_image, 
                        pr.leftEye, pr.rightEye, pr.leftPD, pr.rightPD
                FROM order_items oi
                JOIN products p ON oi.productId = p.productId
                LEFT JOIN prescription pr ON oi.orderItemId = pr.orderItemId
                WHERE oi.orderId = :orderId";
        
        $stmtItems = $db->prepare($query);
        $stmtItems->execute([':orderId' => $orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/auth/profile.php';
    }

    // HỦY ĐƠN HÀNG
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
