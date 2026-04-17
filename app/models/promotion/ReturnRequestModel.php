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
            $placeholders = ['?', '?', "'Pending'", 'CURDATE()'];
            $params = [$orderItemId, $data['reason'] ?? ''];

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
}
