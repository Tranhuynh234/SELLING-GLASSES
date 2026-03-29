<?php
class ReturnRequestModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createRequest($data) {
        $sql = "INSERT INTO ReturnRequest(reason, status, orderItemId)
                VALUES (?, 'pending', ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['reason'],
            $data['orderItemId'],
            //$data['staffId']
        ]);
    }
}
