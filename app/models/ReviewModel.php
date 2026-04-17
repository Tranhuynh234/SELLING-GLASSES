<?php
class ReviewModel {

    public function create($customerId, $orderId, $rating, $comment = '') {
        $db = Database::connect();
        $sql = "INSERT INTO review (customerId, orderId, rating, comment, createdDate) 
                VALUES (:customerId, :orderId, :rating, :comment, NOW())";
        
        $stmt = $db->prepare($sql);
        try {
            $stmt->execute([
                ':customerId' => $customerId,
                ':orderId' => $orderId,
                ':rating' => $rating,
                ':comment' => $comment
            ]);
            return ['success' => true, 'reviewId' => $db->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAll() {
        $db = Database::connect();
        $sql = "SELECT r.*, u.name as customerName 
                FROM review r
                JOIN customers c ON r.customerId = c.customerId
                JOIN users u ON c.userId = u.userId
                ORDER BY r.createdDate DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function getByOrderId($orderId) {
        $db = Database::connect();
        $sql = "SELECT r.*, u.name as customerName 
                FROM review r
                JOIN customers c ON r.customerId = c.customerId
                JOIN users u ON c.userId = u.userId
                WHERE r.orderId = :orderId
                ORDER BY r.createdDate DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':orderId' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCustomerId($customerId) {
        $db = Database::connect();
        $sql = "SELECT r.*, u.name as customerName 
                FROM review r
                JOIN customers c ON r.customerId = c.customerId
                JOIN users u ON c.userId = u.userId
                WHERE r.customerId = :customerId
                ORDER BY r.createdDate DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':customerId' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestForHomePage($limit = 5) {
        $db = Database::connect();
        $sql = "SELECT r.*, u.name as customerName 
                FROM review r
                JOIN customers c ON r.customerId = c.customerId
                JOIN users u ON c.userId = u.userId
                ORDER BY r.createdDate DESC
                LIMIT :limit";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkExistingReview($orderId) {
        $db = Database::connect();
        $sql = "SELECT COUNT(*) as count FROM review WHERE orderId = :orderId";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':orderId' => $orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function deleteReview($reviewId) {
        $db = Database::connect();
        $sql = "DELETE FROM review WHERE reviewId = :reviewId";
        
        $stmt = $db->prepare($sql);
        try {
            $stmt->execute([':reviewId' => $reviewId]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
