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

    /**
     * Lấy Customer ID từ Session User
     */
    private function getCustomerId() {
        $userId = $_SESSION['user']['userId'] ?? null;

        if (!$userId) {
            return null;
        }

        // Tìm customerId từ bảng customers
        $stmt = $this->conn->prepare("SELECT customerId FROM customers WHERE userId = ?");
        $stmt->execute([$userId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu User đã đăng nhập nhưng chưa có trong bảng customers thì tạo mới
        if (!$customer) {
            $stmt = $this->conn->prepare("INSERT INTO customers (userId) VALUES (?)");
            $stmt->execute([$userId]);
            return $this->conn->lastInsertId();
        }

        return $customer['customerId'];
    }

    /**
     * Lấy toàn bộ sản phẩm trong giỏ hàng (Trả về JSON)
     */
    public function getCart() {
        header('Content-Type: application/json');

        $customerId = $this->getCustomerId();

        if (!$customerId) {
            echo json_encode(["error" => "Not logged in", "count" => 0]);
            exit();
        }

        $items = $this->cartService->getCart($customerId);
        echo json_encode($items);
        exit();
    }

    /**
     * Thêm sản phẩm vào giỏ (Sửa để trả về giỏ hàng mới nhất)
     */
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

        // GỌI DUY NHẤT 1 LẦN Ở ĐÂY (Vừa thêm vừa nhận lại giỏ hàng mới nhất)
        $items = $this->cartService->addToCart($customerId, $variantId, $quantity);

        // Trả kết quả về cho JS nhảy số
        echo json_encode($items);
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