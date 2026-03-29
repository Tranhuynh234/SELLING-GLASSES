<?php

require_once __DIR__ . "/../models/CartModel.php";

class CartService {

    private $model;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->model = new CartModel($conn);
    }


    public function getCart($customerId) {

        if (!$customerId) {
            throw new Exception("Customer ID is required");
        }

        return $this->model->getCartByCustomer($customerId);
    }

    public function addToCart($customerId, $variantId, $quantity) {

        try {

         
            if (!$customerId || !$variantId || !$quantity) {
                throw new Exception("Invalid input data");
            }

            if ($quantity <= 0) {
                throw new Exception("Quantity must be greater than 0");
            }

            // 🔍 check cart tồn tại chưa
            $stmt = $this->conn->prepare("
                SELECT cartId FROM cart WHERE customerId = ?
            ");
            $stmt->execute([$customerId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

          
            if (!$cart) {

                $stmt = $this->conn->prepare("
                    INSERT INTO cart (customerId, createdDate)
                    VALUES (?, NOW())
                ");
                $stmt->execute([$customerId]);

                $cartId = $this->conn->lastInsertId();

            } else {
                $cartId = $cart['cartId'];
            }

            // ➕ thêm item
            $this->model->addItem($cartId, $variantId, $quantity);

            return [
                "success" => true,
                "message" => "Added to cart"
            ];

        } catch (Exception $e) {

            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
