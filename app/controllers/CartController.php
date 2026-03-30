<?php

require_once __DIR__ . "/../../config/db_connect.php";
require_once __DIR__ . "/../services/CartService.php";

class CartController {

    private $cartService;
    private $conn;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->conn = Database::connect();
        $this->cartService = new CartService($this->conn);
    }

    private function getCustomerId() {
       $userId = $_SESSION['user']['userId'] ?? null;

if (!$userId) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

        if (!$userId) return null;

        // tìm customer
        $stmt = $this->conn->prepare("
            SELECT customerId FROM customers WHERE userId = ?
        ");
        $stmt->execute([$userId]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        // 🔥 nếu chưa có thì tạo luôn
        if (!$customer) {
            $stmt = $this->conn->prepare("
                INSERT INTO customers (userId) VALUES (?)
            ");
            $stmt->execute([$userId]);

            return $this->conn->lastInsertId();
        }

        return $customer['customerId'];
    }

    public function getCart() {
        header('Content-Type: application/json');

        $customerId = $this->getCustomerId();

        if (!$customerId) {
            echo json_encode(["error" => "Not logged in"]);
            exit();
        }

        $items = $this->cartService->getCart($customerId);

        echo json_encode($items);
        exit();
    }

    public function add() {
        header('Content-Type: application/json');

        $customerId = $this->getCustomerId();

        if (!$customerId) {
            echo json_encode(["error" => "Not logged in"]);
            exit();
        }

        $variantId = $_POST['variantId'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$variantId) {
            echo json_encode(["error" => "Missing variantId"]);
            exit();
        }

        $result = $this->cartService->addToCart($customerId, $variantId, $quantity);

        echo json_encode($result);
        exit();
    }

    public function update() {
    header('Content-Type: application/json');

    $cartItemId = $_POST['cartItemId'] ?? null;
    $quantity = $_POST['quantity'] ?? null;

    if (!$cartItemId || !$quantity) {
        echo json_encode([
            "success" => false,
            "message" => "Missing cartItemId or quantity"
        ]);
        exit();
    }

    $this->cartService->updateItem($cartItemId, $quantity);

    echo json_encode(["success" => true]);
    exit();
}

    public function remove() {
    header('Content-Type: application/json');

    $cartItemId = $_POST['cartItemId'] ?? null;

    if (!$cartItemId) {
        echo json_encode([
            "success" => false,
            "message" => "Missing cartItemId"
        ]);
        exit();
    }

    $this->cartService->removeItem($cartItemId);

    echo json_encode(["success" => true]);
    exit();
}
}
