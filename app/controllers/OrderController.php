<?php
require_once __DIR__ . "/../services/OrderServices.php";

class OrderController {
    private $orderService;
    public function __construct() {
        $this->orderService = new OrderService();
    }

    // ================================
    //  CREATE (POST)
    // ================================
    public function create() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->createOrder($_POST));
    }

    // ================================
    //  GET BY STATUS (GET)
    // ================================
    public function getByStatus() {
        ob_clean();
        header('Content-Type: application/json');
        $status = $_GET['status'] ?? 'All';
        // Gọi qua Service để lấy dữ liệu đồng bộ
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
        $isContacted = $_POST['is_contacted'] ?? null;
        if (!$orderId) {
            echo json_encode(["success" => false, "message" => "Thiếu orderId"]);
            return;
        }

        // Truyền thêm $isContacted vào Service
        $result = $this->orderService->updateStatus($orderId, $status, $isContacted);
        echo json_encode($result);
        exit();
    }

    // ================================
    //  CANCEL
    // ================================
    public function cancel() {
        header("Content-Type: application/json");
        $orderId = $_POST['orderId'] ?? null;

        if (!$orderId) {
            echo json_encode(["success" => false, "message" => "Thiếu orderId"]);
            return;
        }
        echo json_encode($this->orderService->cancelOrder($orderId));
    }

    // ================================
    // RETURN
    // ================================
    public function return() {
        header("Content-Type: application/json");
        $orderId = $_POST['orderId'] ?? null;
        if (!$orderId) {
            echo json_encode(["success" => false, "message" => "Thiếu orderId"]);
            return;
        }
        echo json_encode($this->orderService->returnOrder($orderId));
    }

    // ================================
    // STATS
    // ================================
    public function stats() {
        header("Content-Type: application/json");
        echo json_encode($this->orderService->getOrderStats());
    }

    // ================================
    // GET ORDER DETAIL (Lấy chi tiết đơn hàng)
    // ================================
    public function getOrderDetail() {
        ob_clean();
        header('Content-Type: application/json');
        $orderId = $_GET['orderId'] ?? null;
        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu Order ID']);
            exit();
        }

        // Gọi hàm getOrderDetail trong Service (hàm này gọi getOrderDetailWithCustomer của Model)
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
}