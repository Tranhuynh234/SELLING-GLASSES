<?php

require_once __DIR__ . "/../models/promotion/PromotionModel.php";
require_once __DIR__ . "/../models/promotion/PromotionProductModel.php";
require_once __DIR__ . "/../models/promotion/PrescriptionModel.php";
require_once __DIR__ . "/../models/promotion/ReturnRequestModel.php";

class PromotionServices {

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

    public function createPromotion($data) {
        return $this->promotionModel->createPromotion($data);
    }

    public function applyPromotion($promotionId, $productId) {
        return $this->promotionProductModel->applyPromotion($promotionId, $productId);
    }

    public function uploadPrescription($data) {
        return $this->prescriptionModel->uploadPrescription($data);
    }

    public function requestReturn($data) {
        return $this->returnModel->createRequest($data);
    }
}