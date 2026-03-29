<?php
require_once __DIR__ . "/../services/OrderServices.php";

class OrderController {
    private $orderService;

    public function __construct() {
        $this->orderService = new OrderService();
    }

    public function create() {
        header("Content-Type: application/json");

        $result = $this->orderService->createOrder($_POST);

        echo json_encode($result);
    }
}