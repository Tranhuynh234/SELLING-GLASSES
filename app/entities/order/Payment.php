<?php
class Payment {
    public $paymentId;
    public $paymentMethod;
    public $paymentStatus;
    public $orderId;

    public function __construct($data = []) {
        $this->paymentId = $data['paymentId'] ?? null;
        $this->paymentMethod = $data['paymentMethod'] ?? 'Cash';
        $this->paymentStatus = $data['paymentStatus'] ?? 'Unpaid';
        $this->orderId = $data['orderId'] ?? null;
    }
}
?>