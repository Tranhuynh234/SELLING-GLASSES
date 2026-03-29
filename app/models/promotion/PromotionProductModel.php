<?php
class PromotionProductModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function applyPromotion($promotionId, $productId) {
        $sql = "INSERT INTO PromotionProduct(promotionId, productId)
                VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$promotionId, $productId]);
    }
}