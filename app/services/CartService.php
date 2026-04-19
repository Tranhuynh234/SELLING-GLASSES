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
        error_log("CartService::findOrCreateCartId - customerId=" . $customerId);
        
        // First, ensure the customer exists in customers table
        try {
            $stmt = $this->conn->prepare("SELECT customerId FROM customers WHERE customerId = ? LIMIT 1");
            $stmt->execute([$customerId]);
            $existingCustomer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$existingCustomer) {
                error_log("CartService::findOrCreateCartId - Customer does not exist, creating row for guest customerId=" . $customerId);
                $insertStmt = $this->conn->prepare("INSERT INTO customers (customerId) VALUES (?)");
                $insertStmt->execute([$customerId]);
                error_log("CartService::findOrCreateCartId - Created customer row for guest customerId=" . $customerId);
            }
        } catch (\Exception $e) {
            error_log("CartService::findOrCreateCartId - Exception when ensuring customer: " . $e->getMessage());
            // Continue anyway - the insert may fail later with FK error but at least we tried
        }
        
        // Now find or create the cart
        $stmt = $this->conn->prepare("SELECT cartId FROM cart WHERE customerId = ? LIMIT 1");
        $stmt->execute([$customerId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            error_log("CartService::findOrCreateCartId - Found existing cartId: " . $cart['cartId']);
            return (int) $cart['cartId'];
        }

        error_log("CartService::findOrCreateCartId - Cart does not exist, creating new one for customerId=" . $customerId);
        $stmt = $this->conn->prepare("
            INSERT INTO cart (customerId, createdDate)
            VALUES (?, NOW())
        ");
        $stmt->execute([$customerId]);

        $newCartId = (int) $this->conn->lastInsertId();
        error_log("CartService::findOrCreateCartId - Created new cartId: " . $newCartId);
        return $newCartId;
    }

    // ================= UPDATE =================
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

    // ================= REMOVE =================
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

    // ================= GET CART =================
    public function getCart($customerId) {
        if (!$customerId) return [];

        try {
            return $this->model->getCartDetailsByCustomer($customerId);

        } catch (Exception $e) {
            return []; // Có lỗi thì trả về rỗng luôn cho an toàn
        }
    }

    // ================= ADD TO CART =================
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

    // ================= ADD COMBO TO CART =================
    public function addComboToCart($customerId, $comboId, $quantity) {
        try {
            $cId = (int)$comboId;
            $qty = (int)$quantity;

            $stmt = $this->conn->prepare("SELECT comboId FROM combo WHERE comboId = ?");
            $stmt->execute([$cId]);

            if (!$stmt->fetch()) {
                error_log("Combo not found: " . $cId);
                return false;
            }

            if (!$customerId || $cId <= 0 || $qty <= 0) {
                return [];
            }

            $cartId = $this->findOrCreateCartId($customerId);

            if (!$cartId) throw new Exception("Cannot create cart");

            $stmt = $this->conn->prepare("SELECT cartItemId, quantity FROM cart_item WHERE cartId = ? AND comboId = ?");
            $stmt->execute([$cartId, $cId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                $newQty = $item['quantity'] + $qty;
                $update = $this->conn->prepare("UPDATE cart_item SET quantity = ? WHERE cartItemId = ?");
                $update->execute([$newQty, $item['cartItemId']]);
            } else {
                $insert = $this->conn->prepare("INSERT INTO cart_item (cartId, comboId, quantity) VALUES (?, ?, ?)");
                $insert->execute([$cartId, $cId, $qty]);
            }

            return $this->getCart($customerId);

        } catch (Exception $e) {
            error_log("addComboToCart error: " . $e->getMessage());
            return false;
        }
    }
}