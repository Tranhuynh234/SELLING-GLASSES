<?php
class OrderItem {
    public $orderItemId;
    public $quantity;
    public $price;
    public $orderId;
    public $variantId;

    public function __construct($data = []) {
        $this->orderItemId = $data['orderItemId'] ?? null;
        $this->quantity = $data['quantity'] ?? 0;
        $this->price = $data['price'] ?? 0;
        $this->orderId = $data['orderId'] ?? null;
        $this->variantId = $data['variantId'] ?? null;
    }
}
?>