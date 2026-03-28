<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/Shipment.php";

class ShipmentModel extends BaseModel {
    protected $table = "Shipment"; 

    public function findByTrackingCode($trackingCode) {
        $data = $this->findBy("trackingCode", $trackingCode);
        return $data ? new Shipment($data) : null;
    }

    public function updateShipmentStatus($trackingCode, $status) {
        $sql = "UPDATE {$this->table} SET status = :status WHERE trackingCode = :trackingCode";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':status' => $status, ':trackingCode' => $trackingCode]);
    }
}
?>''