<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/Shipment.php";

class ShipmentModel extends BaseModel {
    protected $table = "shipments"; 

    // 🔥 tìm theo orderId (service cần)
    public function findByOrderId($orderId) {
        $data = $this->findBy("orderId", $orderId);
        return $data ? new Shipment($data) : null;
    }

    // tìm theo tracking code
    public function findByTrackingCode($trackingCode) {
        $data = $this->findBy("trackingCode", $trackingCode);
        return $data ? new Shipment($data) : null;
    }

    // cập nhật trạng thái theo tracking
    public function updateStatusByTrackingCode($trackingCode, $status) {
        $sql = "UPDATE {$this->table} 
                SET status = :status 
                WHERE trackingCode = :trackingCode";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':trackingCode' => $trackingCode
        ]);
    }
}
?>