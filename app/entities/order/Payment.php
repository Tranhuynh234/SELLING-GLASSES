<?php
class Payment {
    public $paymentId;
    public $paymentMethod;
    public $paymentStatus;
    public $orderId;
    public $transferNote;
    public $approvedByStaffId;
    public $approvedAt;

    public function __construct($data = []) {
        $this->paymentId = $data['paymentId'] ?? null;
        $this->paymentMethod = $data['paymentMethod'] ?? 'Bank Transfer';
        $this->paymentStatus = $data['paymentStatus'] ?? 'Pending';
        $this->orderId = $data['orderId'] ?? null;
        $this->transferNote = $data['transferNote'] ?? null;
        $this->approvedByStaffId = $data['approvedByStaffId'] ?? null;
        $this->approvedAt = $data['approvedAt'] ?? null;
    }
}
