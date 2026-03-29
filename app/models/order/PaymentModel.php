<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/order/Payment.php";

class PaymentModel extends BaseModel {
    protected $table = "payments"; 

    // 🔥 đồng bộ với service
    public function findByOrderId($orderId) {
        $data = $this->findBy("orderId", $orderId);
        return $data ? new Payment($data) : null;
    }

    // cập nhật trạng thái thanh toán
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
}
?>