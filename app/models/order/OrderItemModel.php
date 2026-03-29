<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/OrderItem.php";

class OrderItemModel extends BaseModel {
    protected $table = "order_items"; // 

    public function findByOrderId($orderId) {
        $sql = "SELECT * FROM {$this->table} WHERE orderId = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':orderId' => $orderId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $items = [];
        foreach ($rows as $row) {
            $items[] = new OrderItem($row);
        }
        return $items;
    }
}
?>