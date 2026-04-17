<?php
class Prescription {
    public $prescriptionId;
    public $orderItemId;
    public $userId;
    public $leftEye;
    public $rightEye;
    public $leftPD;
    public $rightPD;
    public $imagePath;

    public function save($conn) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO prescription 
                (userId, orderItemId, leftEye, rightEye, leftPD, rightPD, imagePath)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $this->userId,
                $this->orderItemId,
                $this->leftEye,
                $this->rightEye,
                $this->leftPD,
                $this->rightPD,
                $this->imagePath
            ]);

        } catch (Exception $e) {
            error_log("Prescription save error: " . $e->getMessage());
            return false;
        }
    }
}