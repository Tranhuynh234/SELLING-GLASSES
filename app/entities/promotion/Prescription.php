<?php
class Prescription {
    public $prescriptionId;
    public $orderId;
    public $orderItemId;
    public $userId;
    public $leftEye;
    public $rightEye;
    public $leftPD;
    public $rightPD;
    public $imagePath;
    public $status;

    public function save($conn) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO prescription 
                (orderId, userId, orderItemId, leftEye, rightEye, leftPD, rightPD, imagePath, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $this->orderId,
                $this->userId,
                $this->orderItemId,
                $this->leftEye,
                $this->rightEye,
                $this->leftPD,
                $this->rightPD,
                $this->imagePath,
                $this->status ?? 'Pending'
            ]);

        } catch (Exception $e) {
            error_log("Prescription save error: " . $e->getMessage());
            return false;
        }
    }
}