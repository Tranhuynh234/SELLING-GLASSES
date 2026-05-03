<?php
class Promotion {
    private $promotionId;
    private $name;
    private $discount;
    private $discountType; // percent | fixed
    private $startDate;
    private $endDate;
    private $status; // 0 | 1
    private $staffId;

    // =========================
    // Getter
    // =========================
    public function getPromotionId() {
        return $this->promotionId;
    }

    public function getName() {
        return $this->name;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getDiscountType() {
        return $this->discountType;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getStaffId() {
        return $this->staffId;
    }

    // =========================
    // Setter
    // =========================
    public function setPromotionId($promotionId) {
        $this->promotionId = $promotionId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    public function setDiscountType($discountType) {
        $this->discountType = $discountType;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setStaffId($staffId) {
        $this->staffId = $staffId;
    }
}