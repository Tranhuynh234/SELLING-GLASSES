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
        // Query 1: Lấy các item sản phẩm (có variantId)
        $sqlProducts = "SELECT
                    ci.cartItemId,
                    ci.variantId,
                    ci.comboId,
                    ci.quantity,
                    pv.price,
                    pv.stock,
                    pv.color,
                    pv.size,
                    p.productId,
                    p.name AS productName,
                    p.description,
                    p.imagePath,
                    'product' AS itemType,
                    NULL AS comboName,
                    NULL AS comboDescription,
                    NULL AS comboImagePath
                FROM cart_item ci
                JOIN cart c ON ci.cartId = c.cartId
                JOIN product_variant pv ON ci.variantId = pv.variantId
                JOIN product p ON pv.productId = p.productId
                WHERE c.customerId = ? AND ci.variantId IS NOT NULL";

        // Query 2: Lấy các item combo (có comboId, không có variantId)
        $sqlCombos = "SELECT
                    ci.cartItemId,
                    ci.variantId,
                    ci.comboId,
                    ci.quantity,
                    cb.price,
                    999 AS stock,
                    NULL AS color,
                    NULL AS size,
                    NULL AS productId,
                    cb.name AS productName,
                    cb.description,
                    cb.imagePath,
                    'combo' AS itemType,
                    cb.name AS comboName,
                    cb.description AS comboDescription,
                    cb.imagePath AS comboImagePath
                FROM cart_item ci
                JOIN cart c ON ci.cartId = c.cartId
                JOIN combo cb ON ci.comboId = cb.comboId
                WHERE c.customerId = ? AND ci.comboId IS NOT NULL AND ci.variantId IS NULL";

        $sql = "($sqlProducts) UNION ALL ($sqlCombos) ORDER BY cartItemId DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$customerId, $customerId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows ?: [];
    }

    public function getCartDetailsByCustomerAndIds($customerId, $cartItemIds) {
        $cartItemIds = array_values(array_filter(array_map('intval', $cartItemIds)));

        if (!$cartItemIds) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($cartItemIds), '?'));

        // Query 1: Sản phẩm (có variantId)
        $sqlProducts = "SELECT
                    ci.cartItemId,
                    ci.variantId,
                    ci.comboId,
                    ci.quantity,
                    pv.price,
                    pv.stock,
                    pv.color,
                    pv.size,
                    p.productId,
                    p.name AS productName,
                    p.description,
                    p.imagePath,
                    'product' AS itemType
                FROM cart_item ci
                JOIN cart c ON ci.cartId = c.cartId
                JOIN product_variant pv ON ci.variantId = pv.variantId
                JOIN product p ON pv.productId = p.productId
                WHERE c.customerId = ?
                  AND ci.cartItemId IN ($placeholders)
                  AND ci.variantId IS NOT NULL";

        // Query 2: Combo (có comboId, không có variantId)
        $sqlCombos = "SELECT
                    ci.cartItemId,
                    ci.variantId,
                    ci.comboId,
                    ci.quantity,
                    cb.price,
                    999 AS stock,
                    NULL AS color,
                    NULL AS size,
                    NULL AS productId,
                    cb.name AS productName,
                    cb.description,
                    cb.imagePath,
                    'combo' AS itemType
                FROM cart_item ci
                JOIN cart c ON ci.cartId = c.cartId
                JOIN combo cb ON ci.comboId = cb.comboId
                WHERE c.customerId = ?
                  AND ci.cartItemId IN ($placeholders)
                  AND ci.comboId IS NOT NULL AND ci.variantId IS NULL";

        $sql = "($sqlProducts) UNION ALL ($sqlCombos) ORDER BY cartItemId DESC";

        $params = array_merge(
            [(int) $customerId], $cartItemIds,
            [(int) $customerId], $cartItemIds
        );
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
