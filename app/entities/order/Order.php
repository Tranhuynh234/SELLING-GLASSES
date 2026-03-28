<?php
class Order {
    public $orderId;
    public $orderDate;
    public $status;
    public $totalPrice;
    public $customerId;
    public $staffId;

    public function __construct($data = []) {
        $this->orderId = $data['orderId'] ?? null;
        $this->orderDate = $data['orderDate'] ?? date('Y-m-d H:i:s');
        $this->status = $data['status'] ?? 'Pending';
        $this->totalPrice = $data['totalPrice'] ?? 0;
        $this->customerId = $data['customerId'] ?? null;
        $this->staffId = $data['staffId'] ?? null;
    }
}
?>