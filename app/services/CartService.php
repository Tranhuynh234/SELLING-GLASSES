<?php
require_once __DIR__ . "/../models/CartItem.php";
class CartService {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // 🔹 GET CART
    public function getCart($customerId) {

    $sql = "SELECT ci.cartItemId, ci.quantity, pv.price, p.name
            FROM cart_item ci
            JOIN product_variant pv ON ci.variantId = pv.variantId
            JOIN product p ON pv.productId = p.productId
            JOIN cart c ON ci.cartId = c.cartId
            WHERE c.customerId = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$customerId]);

    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $item = new CartItem(
            $row['cartItemId'],
            $row['name'],
            $row['price'],
            $row['quantity']
        );

        $data[] = $item;
    }

    return $data;
}
    // 🔹 ADD
    public function addToCart($customerId, $variantId, $quantity) {

        // check cart
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE customerId = ?");
        $stmt->execute([$customerId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            $stmt = $this->conn->prepare("INSERT INTO cart (customerId, createdDate) VALUES (?, NOW())");
            $stmt->execute([$customerId]);
            $cartId = $this->conn->lastInsertId();
        } else {
            $cartId = $cart['cartId'];
        }

        // check item
        $stmt = $this->conn->prepare("SELECT * FROM cart_item WHERE cartId = ? AND variantId = ?");
        $stmt->execute([$cartId, $variantId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $stmt = $this->conn->prepare("UPDATE cart_item SET quantity = quantity + ? WHERE cartId = ? AND variantId = ?");
            $stmt->execute([$quantity, $cartId, $variantId]);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO cart_item (cartId, variantId, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$cartId, $variantId, $quantity]);
        }
    }

    // 🔹 UPDATE
    public function updateItem($cartItemId, $quantity) {
        $stmt = $this->conn->prepare("UPDATE cart_item SET quantity = ? WHERE cartItemId = ?");
        $stmt->execute([$quantity, $cartItemId]);
    }

    // 🔹 DELETE
    public function removeItem($cartItemId) {
        $stmt = $this->conn->prepare("DELETE FROM cart_item WHERE cartItemId = ?");
        $stmt->execute([$cartItemId]);
    }
}
