<?php
class Prescription {
    public $prescriptionId;
    public $userId;
    public $orderItemId;
    public $leftEye;   
    public $rightEye;  
    public $leftPD;
    public $rightPD;
    public $imagePath;

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