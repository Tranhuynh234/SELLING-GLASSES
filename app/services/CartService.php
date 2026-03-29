<?php

require_once __DIR__ . "/../models/CartModel.php";

class CartService {

    private $model;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->model = new CartModel($conn);
    }

    // ================= UPDATE =================
    public function updateItem($cartItemId, $quantity) {

        if (!$cartItemId || $quantity <= 0) {
            return ["success" => false, "message" => "Invalid data"];
        }

        $stmt = $this->conn->prepare("
            UPDATE cart_items 
            SET quantity = ?
            WHERE cartItemId = ?
        ");

        $stmt->execute([$quantity, $cartItemId]);

        return ["success" => true];
    }

    // ================= REMOVE =================
    public function removeItem($cartItemId) {

        if (!$cartItemId) {
            return ["success" => false, "message" => "Invalid cartItemId"];
        }

        $stmt = $this->conn->prepare("
            DELETE FROM cart_items 
            WHERE cartItemId = ?
        ");

        $stmt->execute([$cartItemId]);

        return ["success" => true];
    }

    // ================= GET CART =================
    public function getCart($customerId) {

    if (!$customerId) {
        return [];
    }

    $stmt = $this->conn->prepare("
        SELECT 
            ci.cartItemId,
            ci.quantity,
            ci.variantId
        FROM cart c
        INNER JOIN cart_items ci ON c.cartId = ci.cartId
        WHERE c.customerId = ?
    ");

    $stmt->execute([$customerId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    // ================= ADD =================
    public function addToCart($customerId, $variantId, $quantity) {

        try {

            if (!$customerId || !$variantId || !$quantity) {
                throw new Exception("Invalid input data");
            }

            if ($quantity <= 0) {
                throw new Exception("Quantity must be greater than 0");
            }

            
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

           
            $stmt = $this->conn->prepare("
                SELECT cartItemId, quantity 
                FROM cart_items 
                WHERE cartId = ? AND variantId = ?
            ");
            $stmt->execute([$cartId, $variantId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                
                $stmt = $this->conn->prepare("
                    UPDATE cart_items 
                    SET quantity = quantity + ?
                    WHERE cartItemId = ?
                ");
                $stmt->execute([$quantity, $item['cartItemId']]);
            } else {
                
                $stmt = $this->conn->prepare("
                    INSERT INTO cart_items (cartId, variantId, quantity)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$cartId, $variantId, $quantity]);
            }

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
