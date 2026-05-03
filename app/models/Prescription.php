<?php
class Prescription {
    public $prescriptionId;
    public $orderId;
    public $userId;
    public $orderItemId;
    public $leftEye;
    public $rightEye;
    public $leftPD;
    public $rightPD;
    public $imagePath;
    public $status;

    public function save($conn) {
        try {

            $query = "INSERT INTO prescription 
            (orderId, userId, orderItemId, leftEye, rightEye, leftPD, rightPD, imagePath, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                orderId = ?,
                userId = ?,
                orderItemId = ?,
                leftEye = ?, 
                rightEye = ?, 
                leftPD = ?, 
                rightPD = ?, 
                imagePath = ?,
                status = ?";

            $stmt = $conn->prepare($query);

            return $stmt->execute([
                $this->orderId,
                $this->userId,
                $this->orderItemId,
                $this->leftEye,
                $this->rightEye,
                $this->leftPD,
                $this->rightPD,
                $this->imagePath,
                $this->status,

                // update
                $this->orderId,
                $this->userId,
                $this->orderItemId,
                $this->leftEye,
                $this->rightEye,
                $this->leftPD,
                $this->rightPD,
                $this->imagePath,
                $this->status
            ]);

        } catch (PDOException $e) {
            die("LỖI DATABASE CHI TIẾT: " . $e->getMessage());
        }
    }
}
?>