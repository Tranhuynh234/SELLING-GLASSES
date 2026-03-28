<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/Payment.php";

class PaymentModel extends BaseModel {
    protected $table = "Payment"; 

    public function getPaymentByOrderId($orderId) {
        $data = $this->findBy("orderId", $orderId);
        return $data ? new Payment($data) : null;
    }

    public function updatePaymentStatus($orderId, $status) {
        $sql = "UPDATE {$this->table} SET paymentStatus = :status WHERE orderId = :orderId";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':status' => $status, ':orderId' => $orderId]);
    }
}
?>