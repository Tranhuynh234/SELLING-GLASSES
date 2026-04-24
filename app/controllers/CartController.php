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

    /** Lấy Customer ID từ Session User */
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

    public function showCartPage() {
        require_once __DIR__ . "/../views/cart/cart.php";
        exit();
    }

    public function showCheckoutPage() {
        require_once __DIR__ . "/../views/order/checkout.php";
        exit();
    }

    /** Lấy toàn bộ sản phẩm trong giỏ hàng (Trả về JSON) */
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

    /** Thêm sản phẩm vào giỏ */
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

        // Vừa thêm vừa nhận lại giỏ hàng mới nhất
        $items = $this->cartService->addToCart($customerId, $variantId, $quantity);

        if ($items === false) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Add to cart failed"
            ]);
            exit();
        }

        echo json_encode([
            "success" => true,
            "data" => $items
        ]);

        // Trả kết quả về cho JS nhảy số
        exit();
    }

    /** Thêm combo vào giỏ */
    public function addCombo() {
        header('Content-Type: application/json');
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            echo json_encode(["error" => "Not logged in"]);
            exit();
        }

        $comboId = $_POST['comboId'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$comboId) {
            echo json_encode(["success" => false, "message" => "Missing comboId"]);
            exit();
        }

        $items = $this->cartService->addComboToCart($customerId, $comboId, $quantity);

        if ($items === false) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Combo không tồn tại hoặc đã ngưng hoạt động"
            ]);
            exit();
        }

        echo json_encode([
            "success" => true,
            "data" => $items
        ]);
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

    public function getCheckoutSummary() {
        header('Content-Type: application/json');

        $customerId = $this->getCustomerId();

        $cart = $this->cartService->getCart($customerId);

        echo json_encode($cart);
        exit();
    }

    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Xóa tiền tròng kính ngay khi rời khỏi giỏ hàng để sang checkout
        unset($_SESSION['prescription_total']);
        
        // Sau đó mới gọi View/ Redirect sang trang Checkout
        header("Location: index.php?url=checkout");
        exit();
    }
}
