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
            $sql = "SELECT o.*, u.name AS cust_name 
                FROM {$this->table} o
                JOIN customers c ON o.customerId = c.customerId
                JOIN users u ON c.userId = u.userId";

            if (strcasecmp($status, 'All') == 0) {
                $sql .= " ORDER BY o.orderDate DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
            } else {
                $sql .= " WHERE o.status = :status ORDER BY o.orderDate DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':status' => $status]);
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi findByStatus: " . $e->getMessage());
            return [];
        }
    }

    public function findLatestOrderIdByUserId($userId) {
        if (!$userId) {
            return null;
        }

        try {
            $sql = "SELECT o.orderId
                    FROM orders o
                    JOIN customers c ON o.customerId = c.customerId
                    WHERE c.userId = :userId
                    ORDER BY o.orderDate DESC
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':userId' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['orderId'] ?? null;
        } catch (Exception $e) {
            error_log("Lỗi findLatestOrderIdByUserId: " . $e->getMessage());
            return null;
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

    // ================================
    //  LƯU TIN NHẮN
    // ================================
    private function ensureMessageReadColumns() {
        try {
            $columns = [
                'is_read_by_staff' => "ALTER TABLE messages ADD COLUMN is_read_by_staff TINYINT(1) NOT NULL DEFAULT 0 AFTER created_at",
                'is_read_by_customer' => "ALTER TABLE messages ADD COLUMN is_read_by_customer TINYINT(1) NOT NULL DEFAULT 0 AFTER is_read_by_staff",
            ];

            foreach ($columns as $column => $alterSql) {
                $stmt = $this->conn->prepare("SHOW COLUMNS FROM messages LIKE :column");
                $stmt->execute([':column' => $column]);
                if (!$stmt->fetch()) {
                    $this->conn->exec($alterSql);
                }
            }
        } catch (Exception $e) {
            error_log("Lỗi ensureMessageReadColumns: " . $e->getMessage());
        }
    }

    public function saveMessage($orderId, $senderType, $content) {
        try {
            // Dùng Transaction để đảm bảo cả 2 lệnh đều chạy thành công
            $this->conn->beginTransaction();

            // 1. Lưu tin nhắn vào bảng messages
            $this->ensureMessageReadColumns();
            $isReadByStaff = $senderType === 'Staff' ? 1 : 0;
            $isReadByCustomer = $senderType === 'Customer' ? 1 : 0;
            $sqlMsg = "INSERT INTO messages (order_id, sender_type, message_content, is_read_by_staff, is_read_by_customer) VALUES (?, ?, ?, ?, ?)";
            $stmtMsg = $this->conn->prepare($sqlMsg);
            $stmtMsg->execute([$orderId, $senderType, $content, $isReadByStaff, $isReadByCustomer]);

            // 2. Cập nhật is_contacted = 1 cho đơn hàng
            $sqlOrder = "UPDATE {$this->table} SET is_contacted = 1 WHERE orderId = ?";
            $stmtOrder = $this->conn->prepare($sqlOrder);
            $stmtOrder->execute([$orderId]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi saveMessage: " . $e->getMessage());
            return false;
        }
    }

    public function markCustomerMessagesReadForOrder($orderId) {
        try {
            $this->ensureMessageReadColumns();
            $sql = "UPDATE messages SET is_read_by_staff = 1 WHERE order_id = :orderId AND sender_type = 'Customer' AND is_read_by_staff = 0";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':orderId' => $orderId]);
        } catch (Exception $e) {
            error_log("Lỗi markCustomerMessagesReadForOrder: " . $e->getMessage());
            return false;
        }
    }

    public function markStaffMessagesReadForUser($userId) {
        try {
            $this->ensureMessageReadColumns();
            $sql = "UPDATE messages m
                JOIN orders o ON m.order_id = o.orderId
                JOIN customers c ON o.customerId = c.customerId
                SET m.is_read_by_customer = 1
                WHERE c.userId = :userId
                AND m.sender_type = 'Staff'
                AND m.is_read_by_customer = 0";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':userId' => $userId]);
        } catch (Exception $e) {
            error_log("Lỗi markStaffMessagesReadForUser: " . $e->getMessage());
            return false;
        }
    }

    public function getSupportUnreadCountForUser($userId) {
        try {
            $this->ensureMessageReadColumns();
            $sql = "SELECT COUNT(*) AS unread
                FROM messages m
                JOIN orders o ON m.order_id = o.orderId
                JOIN customers c ON o.customerId = c.customerId
                WHERE c.userId = :userId
                AND m.sender_type = 'Staff'
                AND m.is_read_by_customer = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':userId' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($row['unread'] ?? 0);
        } catch (Exception $e) {
            error_log("Lỗi getSupportUnreadCountForUser: " . $e->getMessage());
            return 0;
        }
    }

    // ================================
    //  LẤY LỊCH SỬ TIN NHẮN CỦA ĐƠN HÀNG
    // ================================
    public function getMessagesByOrder($orderId) {
        try {
            // SQL NÂNG CẤP:
            // 1. Tìm customerId từ đơn hàng truyền vào
            // 2. Lấy tất cả tin nhắn của mọi đơn hàng thuộc về customerId đó
            $sql = "SELECT m.sender_type, m.message_content, m.created_at
                FROM messages m
                JOIN orders o ON m.order_id = o.orderId
                WHERE o.customerId = (SELECT customerId FROM orders WHERE orderId = :orderId)
                ORDER BY m.created_at ASC"; // Sắp xếp theo thời gian để tin nhắn liền mạch

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':orderId' => $orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi getMessagesByOrder: " . $e->getMessage());
            return [];
        }
    }

    public function getAllCustomerFromOrders() {
        try {
            $this->ensureMessageReadColumns();
            $sql = "SELECT
                u.name AS cust_name,
                o.orderId,
                m.message_content AS last_msg,
                m.sender_type AS last_sender,
                m.created_at AS last_time,
                (SELECT COUNT(*) FROM messages m2 JOIN orders o2 ON m2.order_id = o2.orderId WHERE o2.customerId = o.customerId AND m2.sender_type = 'Customer' AND m2.is_read_by_staff = 0) AS unread_count,
                o.orderDate -- Lấy thêm ngày đặt đơn để dự phòng
                FROM orders o
                JOIN customers c ON o.customerId = c.customerId
                JOIN users u ON c.userId = u.userId
                LEFT JOIN messages m ON m.message_id = (
                SELECT m2.message_id
                FROM messages m2
                JOIN orders o2 ON m2.order_id = o2.orderId
                WHERE o2.customerId = o.customerId
                ORDER BY m2.created_at DESC
                LIMIT 1
                )

                WHERE o.orderId IN (
                SELECT MAX(orderId)
                FROM orders
                GROUP BY customerId
                )
                
                -- Sắp xếp: Ưu tiên thời gian tin nhắn, nếu không có thì dùng thời gian đơn hàng
                ORDER BY COALESCE(m.created_at, o.orderDate) DESC";       

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi: " . $e->getMessage());
            return [];
        }
    }
}
