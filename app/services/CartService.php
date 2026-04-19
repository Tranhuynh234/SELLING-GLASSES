<?php

require_once __DIR__ . "/../models/CartModel.php";

class CartService {

    private $model;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->model = new CartModel($conn);
    }

    private function findOrCreateCartId($customerId) {
        $stmt = $this->conn->prepare("SELECT cartId FROM cart WHERE customerId = ? LIMIT 1");
        $stmt->execute([$customerId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            return (int) $cart['cartId'];
        }

        $stmt = $this->conn->prepare("
            INSERT INTO cart (customerId, createdDate)
            VALUES (?, NOW())
        ");
        $stmt->execute([$customerId]);

        return (int) $this->conn->lastInsertId();
    }

    // UPDATE 
    public function updateItem($cartItemId, $quantity) {
        if (!$cartItemId || $quantity <= 0) {
            return ["success" => false, "message" => "Invalid data"];
        }

        $stmt = $this->conn->prepare("
            UPDATE cart_item 
            SET quantity = ?
            WHERE cartItemId = ?
        ");

        $stmt->execute([$quantity, $cartItemId]);
        return ["success" => true];
    }

    //  REMOVE 
    public function removeItem($cartItemId) {
        if (!$cartItemId) {
            return ["success" => false, "message" => "Invalid cartItemId"];
        }

        $stmt = $this->conn->prepare("
            DELETE FROM cart_item
            WHERE cartItemId = ?
        ");

        $stmt->execute([$cartItemId]);
        return ["success" => true];
    }

    //  GET CART 
    public function getCart($customerId) {
        if (!$customerId) return [];

        try {
            return $this->model->getCartDetailsByCustomer($customerId);

        } catch (Exception $e) {
            return []; // Có lỗi thì trả về rỗng luôn cho an toàn
        }
    }

    // ADD TO CART 
    public function addToCart($customerId, $variantId, $quantity) {
        try {
            $vId = (int)$variantId;
            $qty = (int)$quantity;

            $stmt = $this->conn->prepare("
                SELECT variantId 
                FROM product_variant 
                WHERE variantId = ?
            ");
            $stmt->execute([$vId]);

            if (!$stmt->fetch()) {
                error_log("Variant not found: " . $vId);
                return false; // KHÔNG THROW
            }

            if (!$customerId || $vId <= 0 || $qty <= 0) {
                return [];
            }

            $cartId = $this->findOrCreateCartId($customerId);

            if (!$cartId) {
                throw new Exception("Cannot create cart");
            }

            $stmt = $this->conn->prepare("
                SELECT cartItemId, quantity 
                FROM cart_item 
                WHERE cartId = ? AND variantId = ?
            ");
            $stmt->execute([$cartId, $vId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                $newQty = $item['quantity'] + $qty;

                $update = $this->conn->prepare("
                    UPDATE cart_item 
                    SET quantity = ? 
                    WHERE cartItemId = ?
                ");
                $update->execute([$newQty, $item['cartItemId']]);
            } else {
                $insert = $this->conn->prepare("
                    INSERT INTO cart_item (cartId, variantId, quantity)
                    VALUES (?, ?, ?)
                ");
                $insert->execute([$cartId, $vId, $qty]);
            }

            return $this->getCart($customerId);

        } catch (Exception $e) {
            error_log("addToCart error: " . $e->getMessage());
            return false;
        }
    }
}
