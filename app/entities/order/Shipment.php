<?php
class Shipment {
    public $shipmentId;
    public $trackingCode;
    public $carrier;
    public $status;
    public $staffId;
    public $orderId;

    public function __construct($data = []) {
        $this->shipmentId = $data['shipmentId'] ?? null;
        $this->trackingCode = $data['trackingCode'] ?? uniqid('SHIP_');
        $this->carrier = $data['carrier'] ?? 'GHTK';
        $this->status = $data['status'] ?? 'Preparing';
        $this->staffId = $data['staffId'] ?? null;
        $this->orderId = $data['orderId'] ?? null;
    }
}
?>