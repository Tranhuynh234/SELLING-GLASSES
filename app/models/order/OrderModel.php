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
        try {
            if (strcasecmp($status, 'All') == 0) {
                // Lấy toàn bộ dữ liệu thật từ bảng orders
                $sql = "SELECT * FROM {$this->table} ORDER BY orderDate DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
            } else {
                // Lấy theo status thực tế
                $sql = "SELECT * FROM {$this->table} WHERE status = :status ORDER BY orderDate DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':status' => $status]);
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            // Nếu có lỗi SQL, trả về mảng rỗng thay vì làm sập trang
            return [];
        }
        
    }

    public function getOrderDetailWithCustomer($orderId) {
        try {
            $sql = "SELECT 
                o.orderId, o.orderDate, o.status, o.totalPrice, o.is_contacted,
                u.name AS cust_name, 
                u.phone AS cust_phone, 
                c.address AS cust_address,
                oi.quantity, oi.price,
                pv.color, pv.size,
                p.name AS product_name, p.imagePath AS product_image
            FROM orders o
            JOIN customers c ON o.customerId = c.customerId
            JOIN users u ON c.userId = u.userId
            JOIN order_item oi ON o.orderId = oi.orderId
            JOIN product_variant pv ON oi.variantId = pv.variantId
            JOIN product p ON pv.productId = p.productId
            WHERE o.orderId = :orderId";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':orderId' => $orderId]); // Đã khớp với :orderId ở trên
            
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

    // ================================
    //  CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
    // ================================
    public function update($orderId, $data, $primaryKey = 'orderId') {
        try {
            $sets = [];
            $params = [':orderId' => $orderId];

            foreach ($data as $column => $value) {
                $sets[] = "{$column} = :{$column}";
                $params[":{$column}"] = $value;
            }

            $setSql = implode(", ", $sets);
            $sql = "UPDATE {$this->table} SET {$setSql} WHERE {$primaryKey} = :orderId";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            return false;
        }
    }
}
