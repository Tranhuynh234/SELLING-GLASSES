<?php
require_once __DIR__ . "/../../core/BaseModel.php"; 

class OrderModel extends BaseModel {
    protected $table = "orders";
    protected $primaryKey = "orderId";

    // --- 1. CẬP NHẬT ĐƠN HÀNG ---
    public function updateOrder($orderId, $data) {
        return $this->update($orderId, $data, "orderId");
    }

    // --- 2. LẤY ĐƠN HÀNG CHO OPS ---
    public function getOrdersForOps($status = null) {
        $sql = "SELECT o.*, u.name as customerName 
                FROM orders o 
                JOIN customers c ON o.customerId = c.customerId
                JOIN users u ON c.userId = u.userId";
        
        $params = [];
        if ($status && $status !== 'all') {
            $sql .= " WHERE o.status = :status";
            $params[':status'] = $status;
            // Nếu có status thì trả về kết quả luôn
            return $this->queryAll($sql, $params);
        }
        
        // Nếu không có status hoặc là 'all' thì nối thêm ORDER BY
        $sql .= " ORDER BY o.orderDate DESC";
        return $this->queryAll($sql);
    }

    // --- 3. THỐNG KÊ ---
    public function countByStatus() {
        $sql = "SELECT status, COUNT(*) as total FROM {$this->table} GROUP BY status";
        return $this->queryAll($sql);
    }

    // --- 4. TẠO VẬN ĐƠN (LƯU VÀO BẢNG SHIPMENT) ---
    public function createShipment($data) {
        try {
            $sql = "INSERT INTO shipment (orderId, trackingCode, carrier, status, staffId) 
                    VALUES (:orderId, :trackingCode, :carrier, :status, :staffId)";
            return $this->queryAll($sql, [
                ':orderId'      => $data['orderId'],
                ':trackingCode' => $data['trackingCode'],
                ':carrier'      => $data['carrier'],
                ':status'       => $data['status'] ?? 'In Transit',
                ':staffId'      => $data['staffId'] ?? 1
            ]); 
        } catch (Exception $e) {
            error_log("Lỗi INSERT bảng shipment: " . $e->getMessage());
            return false;
        }
    }
}
