<?php
require_once __DIR__ . "/../models/ReviewModel.php";
require_once __DIR__ . "/../entities/Review.php";

class ReviewService {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new ReviewModel();
    }

    public function submitReview($customerId, $orderId, $rating, $comment = '') {

        if (!$customerId || !$orderId || !$rating) {
            return ['success' => false, 'message' => 'Thiếu thông tin bắt buộc'];
        }

        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'message' => 'Đánh giá phải từ 1 đến 5 sao'];
        }

        if ($this->reviewModel->checkExistingReview($orderId)) {
            return ['success' => false, 'message' => 'Bạn đã đánh giá đơn hàng này rồi'];
        }

        $result = $this->reviewModel->create($customerId, $orderId, $rating, $comment);
        
        if ($result['success']) {
            return ['success' => true, 'message' => 'Cảm ơn bạn đã đánh giá!', 'reviewId' => $result['reviewId']];
        }

        return ['success' => false, 'message' => 'Lỗi khi lưu đánh giá'];
    }

    public function getAllReviews() {
        return $this->reviewModel->getAll();
    }

    public function getReviewsForHomePage($limit = 5) {
        return $this->reviewModel->getLatestForHomePage($limit);
    }

    public function getReviewByOrderId($orderId) {
        return $this->reviewModel->getByOrderId($orderId);
    }

    public function getReviewsByCustomerId($customerId) {
        return $this->reviewModel->getByCustomerId($customerId);
    }

    public function hasReview($orderId) {
        return $this->reviewModel->checkExistingReview($orderId);
    }
}
?>
