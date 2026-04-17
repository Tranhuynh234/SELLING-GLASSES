<?php
class Prescription {
    public $prescriptionId;
    public $userId;
    public $orderItemId;
    public $leftEye;   // Sẽ chứa chuỗi JSON
    public $rightEye;  // Sẽ chứa chuỗi JSON
    public $leftPD;
    public $rightPD;
    public $imagePath;

    /** Sử dụng PDO Prepare Statement để chống lỗi bảo mật SQL Injection */

    public function save($conn) {
        try {

            $query = "INSERT INTO prescription 
            (userId, orderItemId, leftEye, rightEye, leftPD, rightPD, imagePath) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                leftEye = ?, 
                rightEye = ?, 
                leftPD = ?, 
                rightPD = ?, 
                imagePath = ?";

            $stmt = $conn->prepare($query);

            return $stmt->execute([
                $this->userId,
                $this->orderItemId,
                $this->leftEye,
                $this->rightEye,
                $this->leftPD,
                $this->rightPD,
                $this->imagePath,

                // update
                $this->leftEye,
                $this->rightEye,
                $this->leftPD,
                $this->rightPD,
                $this->imagePath
            ]);

        } catch (PDOException $e) {
            die("LỖI DATABASE CHI TIẾT: " . $e->getMessage());
        }
    }
}
?>