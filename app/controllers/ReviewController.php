<?php
require_once __DIR__ . "/../services/ReviewService.php";

class ReviewController {
    private $reviewService;

    public function __construct() {
        $this->reviewService = new ReviewService();
    }

    public function submitReview() {
        header("Content-Type: application/json");

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['userId'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để đánh giá']);
            return;
        }

        $db = Database::connect();
        $userId = $_SESSION['user']['userId'];
        
        $stmtCustomer = $db->prepare("SELECT customerId FROM customers WHERE userId = :userId");
        $stmtCustomer->execute([':userId' => $userId]);
        $customer = $stmtCustomer->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin khách hàng']);
            return;
        }

        $customerId = $customer['customerId'];
        $orderId = $_POST['orderId'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? '';

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã đơn hàng']);
            return;
        }

        $stmtOrder = $db->prepare("SELECT * FROM orders WHERE orderId = :orderId AND customerId = :customerId");
        $stmtOrder->execute([':orderId' => $orderId, ':customerId' => $customerId]);
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ']);
            return;
        }

        if (strtolower($order['status']) !== 'delivered') {
            echo json_encode(['success' => false, 'message' => 'Chỉ có thể đánh giá những đơn hàng đã giao']);
            return;
        }

        $result = $this->reviewService->submitReview($customerId, $orderId, $rating, $comment);
        echo json_encode($result);
    }

    public function getReviews() {
        header("Content-Type: application/json");

        $reviews = $this->reviewService->getReviewsForHomePage(5);
        
        echo json_encode([
            'success' => true,
            'data' => $reviews
        ]);
    }

    public function getReviewByOrder() {
        header("Content-Type: application/json");

        $orderId = $_GET['orderId'] ?? null;

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu orderId']);
            return;
        }

        $review = $this->reviewService->getReviewByOrderId($orderId);
        
        if ($review) {
            echo json_encode(['success' => true, 'data' => $review]);
        } else {
            echo json_encode(['success' => true, 'data' => null]);
        }
    }
}
?>
