<?php
class Prescription {
    public $prescriptionId;
    public $orderItemId;
    public $leftEye;   // Sẽ chứa chuỗi JSON
    public $rightEye;  // Sẽ chứa chuỗi JSON
    public $leftPD;
    public $rightPD;
    public $imagePath;

    /**
     * Hàm lưu thông số vào Database
     * Sử dụng PDO Prepare Statement để chống lỗi bảo mật SQL Injection
     */

    public function save($conn) {
        try {
            // Thay INSERT bằng REPLACE để ghi đè dữ liệu nếu orderItemId đã tồn tại
            $query = "REPLACE INTO prescription (orderItemId, leftEye, rightEye, leftPD, rightPD, imagePath) 
                    VALUES (:orderItemId, :leftEye, :rightEye, :leftPD, :rightPD, :imagePath)";
            
            $stmt = $conn->prepare($query);
            
            return $stmt->execute([
                ':orderItemId' => $this->orderItemId,
                ':leftEye'     => $this->leftEye,
                ':rightEye'    => $this->rightEye,
                ':leftPD'      => $this->leftPD,
                ':rightPD'     => $this->rightPD,
                ':imagePath'   => $this->imagePath
            ]);
        } catch (PDOException $e) {
            die("LỖI DATABASE CHI TIẾT: " . $e->getMessage()); 
        }
    }
}
?>