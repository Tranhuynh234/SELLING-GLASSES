<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/Payment.php";

class PaymentModel extends BaseModel {
    protected $table = "payment";

    public function findPayment($paymentId) {
        $data = $this->find($paymentId, "paymentId");
        return $data ? new Payment($data) : null;
    }

    public function findByOrderId($orderId) {
        $data = $this->findBy("orderId", $orderId);
        return $data ? new Payment($data) : null;
    }

    public function createPayment($data) {
        return $this->create($data);
    }

    public function updateStatusByOrderId($orderId, $status) {
        $sql = "UPDATE {$this->table} 
                SET paymentStatus = :status 
                WHERE orderId = :orderId";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':orderId' => $orderId
        ]);
    }

    public function updatePayment($paymentId, $data) {
        return $this->update($paymentId, $data, "paymentId");
    }
}
