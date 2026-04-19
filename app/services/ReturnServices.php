<?php

require_once __DIR__ . "/../models/promotion/PromotionModel.php";
require_once __DIR__ . "/../models/promotion/PromotionProductModel.php";
require_once __DIR__ . "/../models/promotion/PrescriptionModel.php";
require_once __DIR__ . "/../models/promotion/ReturnRequestModel.php";

class ReturnServices {

    private $promotionModel;
    private $promotionProductModel;
    private $prescriptionModel;
    private $returnModel;

    public function __construct($conn) {
        $this->promotionModel = new PromotionModel($conn);
        $this->promotionProductModel = new PromotionProductModel($conn);
        $this->prescriptionModel = new PrescriptionModel($conn);
        $this->returnModel = new ReturnRequestModel($conn);
    }
    public function requestReturn($data) {
        $orderId = $data['orderId'] ?? null;
        if ($orderId && $this->returnModel->getRequestByOrderId($orderId)) {
            return [
                'success' => false,
                'message' => 'Bạn đã gửi yêu cầu đổi/trả cho đơn hàng này. Vui lòng chờ xử lý.'
            ];
        }

        $created = $this->returnModel->createRequest($data);
        return ['success' => $created];
    }

    public function getComplaints($type = 'all') {
        return $this->returnModel->fetchRequests($type);
    }

    public function processRequest($returnId, $action) {
        $request = $this->returnModel->getRequestById($returnId);
        if (!$request) {
            return ["success" => false, "message" => "Yêu cầu không tồn tại."];
        }

        $requestType = $request['request_type'];
        $newStatus = 'Completed';
        $orderStatus = null;

        if ($action === 'resolve' && $requestType === 'complaint') {
            $orderStatus = 'Cancelled';
        } elseif ($action === 'approve_return' && $requestType === 'return') {
            $orderStatus = 'Returned';
        } else {
            return ["success" => false, "message" => "Hành động không hợp lệ cho loại yêu cầu này."];
        }

        $okRequest = $this->returnModel->updateRequestStatus($returnId, $newStatus);
        $okOrder = $this->returnModel->updateOrderStatus($request['orderId'], $orderStatus);

        if ($okRequest && $okOrder) {
            return ["success" => true, "message" => "Cập nhật thành công."];
        }

        return ["success" => false, "message" => "Không thể cập nhật yêu cầu hoặc đơn hàng."];
    }
}