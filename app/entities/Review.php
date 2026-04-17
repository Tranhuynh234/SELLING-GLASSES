<?php
class Review {
    public $reviewId;
    public $customerId;
    public $orderId;
    public $rating;
    public $comment;
    public $createdDate;

    public function __construct($customerId = null, $orderId = null, $rating = null, $comment = null) {
        $this->customerId = $customerId;
        $this->orderId = $orderId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->createdDate = date('Y-m-d H:i:s');
    }
}
?>
