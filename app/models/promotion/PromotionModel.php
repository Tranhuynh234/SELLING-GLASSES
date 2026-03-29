<?php
class PromotionModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createPromotion($data) {
        $sql = "INSERT INTO Promotion(name, discount, startDate, endDate, staffId)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['discount'],
            $data['startDate'],
            $data['endDate'],
            $data['staffId']
        ]);
    }
}
