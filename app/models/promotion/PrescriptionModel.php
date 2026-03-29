<?php
class PrescriptionModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function uploadPrescription($data) {
        $sql = "INSERT INTO Prescription(leftEye, rightEye, leftPD, rightPD, imagePath, orderItemId)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['leftEye'],
            $data['rightEye'],
            $data['leftPD'],
            $data['rightPD'],
            $data['imagePath'],
            $data['orderItemId']
        ]);
    }
}