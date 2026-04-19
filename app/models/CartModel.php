<?php

require_once __DIR__ . "/../entities/CartItem.php";

class CartModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function getCartByCustomer($customerId) {

        $sql = "SELECT ci.cartItemId, ci.quantity, pv.price, p.name
                FROM cart_item ci
                JOIN product_variant pv ON ci.variantId = pv.variantId
                JOIN product p ON pv.productId = p.productId
                JOIN cart c ON ci.cartId = c.cartId
                WHERE c.customerId = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$customerId]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = new CartItem(
                $row['cartItemId'],
                $row['name'],
                $row['price'],
                $row['quantity']
            );
        }

        return $items;
    }

    public function getCartDetailsByCustomer($customerId) {
        error_log("CartModel::getCartDetailsByCustomer - START with customerId=" . $customerId);
        
        // Note: combo table may not have 'stock' column, so use a default value
        $sql = "SELECT
                    ci.cartItemId,
                    ci.variantId,
                    ci.comboId,
                    ci.quantity,
                    CASE 
                        WHEN ci.comboId IS NOT NULL THEN c_combo.price
                        ELSE pv.price
                    END as price,
                    CASE 
                        WHEN ci.comboId IS NOT NULL THEN 999
                        ELSE pv.stock
                    END as stock,
                    pv.color,
                    pv.size,
                    CASE 
                        WHEN ci.comboId IS NOT NULL THEN c_combo.comboId
                        ELSE p.productId
                    END as productId,
                    CASE 
                        WHEN ci.comboId IS NOT NULL THEN c_combo.name
                        ELSE p.name
                    END AS productName,
                    CASE 
                        WHEN ci.comboId IS NOT NULL THEN c_combo.description
                        ELSE p.description
                    END as description,
                    CASE 
                        WHEN ci.comboId IS NOT NULL THEN c_combo.imagePath
                        ELSE p.imagePath
                    END as imagePath,
                    CASE 
                        WHEN ci.comboId IS NOT NULL THEN 'combo'
                        ELSE 'product'
                    END as type
                FROM cart_item ci
                JOIN cart c ON ci.cartId = c.cartId
                LEFT JOIN product_variant pv ON ci.variantId = pv.variantId
                LEFT JOIN product p ON pv.productId = p.productId
                LEFT JOIN combo c_combo ON ci.comboId = c_combo.comboId
                WHERE c.customerId = ?
                ORDER BY ci.cartItemId DESC";

        try {
            $stmt = $this->conn->prepare($sql);
            error_log("CartModel::getCartDetailsByCustomer - prepared sql");
            
            $stmt->execute([$customerId]);
            error_log("CartModel::getCartDetailsByCustomer - executed");
            
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("CartModel::getCartDetailsByCustomer - fetched rows count=" . count($rows) . ", rows=" . json_encode($rows));

            return $rows ?: [];
        } catch (\Exception $e) {
            error_log("CartModel::getCartDetailsByCustomer - EXCEPTION: " . $e->getMessage());
            return [];
        }
    }

    public function getCartDetailsByCustomerAndIds($customerId, $cartItemIds) {
        $cartItemIds = array_values(array_filter(array_map('intval', $cartItemIds)));

        if (!$cartItemIds) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($cartItemIds), '?'));

        $sql = "SELECT
                    ci.cartItemId,
                    ci.variantId,
                    ci.quantity,
                    pv.price,
                    pv.stock,
                    pv.color,
                    pv.size,
                    p.productId,
                    p.name AS productName,
                    p.description,
                    p.imagePath
                FROM cart_item ci
                JOIN cart c ON ci.cartId = c.cartId
                JOIN product_variant pv ON ci.variantId = pv.variantId
                JOIN product p ON pv.productId = p.productId
                WHERE c.customerId = ?
                  AND ci.cartItemId IN ($placeholders)
                ORDER BY ci.cartItemId DESC";

        $params = array_merge([(int) $customerId], $cartItemIds);
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows ?: [];
    }

    public function removeItemsByIds($customerId, $cartItemIds) {
        $cartItemIds = array_values(array_filter(array_map('intval', $cartItemIds)));

        if (!$cartItemIds) {
            return true;
        }

        $placeholders = implode(',', array_fill(0, count($cartItemIds), '?'));

        $sql = "DELETE ci
                FROM cart_item ci
                JOIN cart c ON ci.cartId = c.cartId
                WHERE c.customerId = ?
                  AND ci.cartItemId IN ($placeholders)";

        $params = array_merge([(int) $customerId], $cartItemIds);
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($params);
    }

    public function addItem($cartId, $variantId, $quantity) {

        $stmt = $this->conn->prepare("
            SELECT * FROM cart_item WHERE cartId = ? AND variantId = ?
        ");
        $stmt->execute([$cartId, $variantId]);

        if ($stmt->fetch()) {
            $this->conn->prepare("
                UPDATE cart_item SET quantity = quantity + ?
                WHERE cartId = ? AND variantId = ?
            ")->execute([$quantity, $cartId, $variantId]);
        } else {
            $this->conn->prepare("
                INSERT INTO cart_item (cartId, variantId, quantity)
                VALUES (?, ?, ?)
            ")->execute([$cartId, $variantId, $quantity]);
        }
    }
}