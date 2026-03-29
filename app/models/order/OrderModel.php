<?php
require_once __DIR__ . "/../../core/BaseModel.php"; 

class OrderModel extends BaseModel {
    protected $table = "orders";
    protected $primaryKey = "orderId";

    // có thể custom thêm nếu cần
    public function findByCustomer($customerId) {
        $sql = "SELECT * FROM {$this->table} WHERE customerId = :customerId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':customerId' => $customerId]);
        return $stmt->fetchAll();
    }
}