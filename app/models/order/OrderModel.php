<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/Order.php";

class OrderModel extends BaseModel {
    protected $table = "Order";

    public function createAndReturnId($data) {
        $sql = "INSERT INTO {$this->table} (orderDate, status, totalPrice, customerId) 
                VALUES (:orderDate, :status, :totalPrice, :customerId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':orderDate' => $data['orderDate'],
            ':status' => $data['status'],
            ':totalPrice' => $data['totalPrice'],
            ':customerId' => $data['customerId']
        ]);
        return $this->conn->lastInsertId(); 
    }
}
?>