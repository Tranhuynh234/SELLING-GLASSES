<?php
require_once __DIR__ . "/../../core/BaseModel.php"; 

class OrderModel extends BaseModel {
    protected $table = "orders";
    protected $primaryKey = "orderId";

    // ================================
    // LẤY ORDER THEO CUSTOMER
    // ================================
    public function findByCustomer($customerId) {
        if (!$customerId) return [];

        try {
            $sql = "SELECT * FROM {$this->table} WHERE customerId = :customerId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':customerId' => $customerId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return [];
        }
    }

    // ================================
    //  LẤY ORDER THEO STATUS
    // ================================
    public function findByStatus($status) {
        if (!$status) return [];

        try {
            $sql = "SELECT * FROM {$this->table} WHERE status = :status";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':status' => $status]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return [];
        }
    }

    // ================================
    //  THỐNG KÊ STATUS
    // ================================
    public function countByStatus() {
        try {
            $sql = "SELECT status, COUNT(*) as total FROM {$this->table} GROUP BY status";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return [];
        }
    }
}
