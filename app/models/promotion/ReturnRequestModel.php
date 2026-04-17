<?php
class ReturnRequestModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createRequest($data) {
        try {
            $orderItemId = null;

            if (!empty($data['orderItemId'])) {
                $orderItemId = $data['orderItemId'];
            } elseif (!empty($data['orderId'])) {
                $stmtFind = $this->conn->prepare("SELECT orderItemId FROM order_item WHERE orderId = ? LIMIT 1");
                $stmtFind->execute([$data['orderId']]);
                $row = $stmtFind->fetch(PDO::FETCH_ASSOC);
                if ($row && isset($row['orderItemId'])) {
                    $orderItemId = $row['orderItemId'];
                } else {
                    throw new PDOException('No order_item found for orderId ' . intval($data['orderId']));
                }
            } else {
                throw new PDOException('Missing orderId/orderItemId');
            }

            $cols = ['orderItemId', 'reason', 'status', 'requestDate'];
            $placeholders = ['?', '?', '?', 'NOW()'];
            $params = [$orderItemId, $data['reason'] ?? '', 'Pending'];

            if (!empty($data['staffId'])) {
                $cols[] = 'staffId';
                $placeholders[] = '?';
                $params[] = $data['staffId'];
            }

            if (!empty($data['note'])) {
                $cols[] = 'note';
                $placeholders[] = '?';
                $params[] = $data['note'];
            }

            if (!empty($data['imagePath'])) {
                $cols[] = 'imagePath';
                $placeholders[] = '?';
                $params[] = $data['imagePath'];
            }

            $sql = "INSERT INTO return_request (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log('ReturnRequestModel::createRequest error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function fetchRequests($type = 'all') {
        try {
            $sql = "SELECT rr.returnId, o.orderId, u.name AS cust_name, rr.reason, rr.status, rr.requestDate,
                           rr.staffId, rr.note, rr.imagePath, su.name AS staff_name
                    FROM return_request rr
                    JOIN order_item oi ON rr.orderItemId = oi.orderItemId
                    JOIN orders o ON oi.orderId = o.orderId
                    JOIN customers c ON o.customerId = c.customerId
                    JOIN users u ON c.userId = u.userId
                    LEFT JOIN staff s ON rr.staffId = s.staffId
                    LEFT JOIN users su ON s.userId = su.userId
                    ORDER BY rr.requestDate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_values(array_filter(array_map(function ($row) use ($type) {
                $row['request_type'] = $this->determineRequestType($row['reason']);
                $row['label_status'] = $this->buildStatusLabel($row['status'], $row['request_type']);
                $filter = strtolower(trim($type));
                if ($filter === 'all') {
                    return $row;
                }
                return $row['request_type'] === $filter ? $row : null;
            }, $requests)));
        } catch (Exception $e) {
            error_log("Lỗi fetchRequests: " . $e->getMessage());
            return [];
        }
    }

    public function getRequestById($returnId) {
        try {
            $sql = "SELECT rr.returnId, o.orderId, u.name AS cust_name, rr.reason, rr.status,
                           rr.staffId, rr.note, rr.imagePath, su.name AS staff_name
                    FROM return_request rr
                    JOIN order_item oi ON rr.orderItemId = oi.orderItemId
                    JOIN orders o ON oi.orderId = o.orderId
                    JOIN customers c ON o.customerId = c.customerId
                    JOIN users u ON c.userId = u.userId
                    LEFT JOIN staff s ON rr.staffId = s.staffId
                    LEFT JOIN users su ON s.userId = su.userId
                    WHERE rr.returnId = :returnId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':returnId' => $returnId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $row['request_type'] = $this->determineRequestType($row['reason']);
            $row['label_status'] = $this->buildStatusLabel($row['status'], $row['request_type']);
            return $row;
        } catch (Exception $e) {
            error_log("Lỗi getRequestById: " . $e->getMessage());
            return null;
        }
    }

    public function updateRequestStatus($returnId, $newStatus) {
        try {
            $sql = "UPDATE return_request SET status = :status WHERE returnId = :returnId";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':status' => $newStatus, ':returnId' => $returnId]);
        } catch (Exception $e) {
            error_log("Lỗi updateRequestStatus: " . $e->getMessage());
            return false;
        }
    }

    public function updateOrderStatus($orderId, $status) {
        try {
            $sql = "UPDATE orders SET status = :status WHERE orderId = :orderId";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':status' => $status, ':orderId' => $orderId]);
        } catch (Exception $e) {
            error_log("Lỗi updateOrderStatus: " . $e->getMessage());
            return false;
        }
    }

    private function buildStatusLabel($status, $type) {
        if (strtolower($status) === 'pending') {
            return 'Chờ xử lý';
        }

        if (strtolower($status) === 'completed') {
            return $type === 'return' ? 'Thành công' : 'Đã giải quyết';
        }

        return $status;
    }

    private function determineRequestType($reason) {
        $text = mb_strtolower(trim($reason), 'UTF-8');

        $returnKeywords = [
            'đổi',
            'trả',
            'return',
            'exchange',
            'giao sai',
            'mẫu kính',
            'màu sắc',
            'đeo không vừa',
            'quá rộng',
            'quá chật',
            'size',
            'kích thước',
            'sai thông số',
            'độ cận',
            'viễn',
        ];

        foreach ($returnKeywords as $keyword) {
            if (mb_strpos($text, $keyword) !== false) {
                return 'return';
            }
        }

        return 'complaint';
    }
}
