<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/OrderItem.php";

class OrderItemModel extends BaseModel {
    protected $table = "OrderItem"; 

    public function getItemsByOrderId($orderId) {
        $sql = "SELECT * FROM {$this->table} WHERE orderId = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':orderId' => $orderId]);
        $rows = $stmt->fetchAll();

        $items = [];
        foreach ($rows as $row) {
            $items[] = new OrderItem($row);
        }
        return $items;
    }
}
?>