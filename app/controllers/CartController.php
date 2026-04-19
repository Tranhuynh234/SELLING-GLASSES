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

    /** Lấy Customer ID từ Session User hoặc Session ID cho khách vô đăng nhập */
    private function getCustomerId() {
        // Debug: log session status
        error_log("=== CartController::getCustomerId DEBUG ===");
        error_log("Session user isset: " . (isset($_SESSION['user']) ? 'YES' : 'NO'));
        
        // Kiểm tra session user trước
        if (isset($_SESSION['user']) && isset($_SESSION['user']['userId'])) {
            $userId = $_SESSION['user']['userId'];
            error_log("Found logged-in userId: " . $userId);

            if ($userId) {
                // Tìm customerId từ bảng customers
                try {
                    $stmt = $this->conn->prepare("SELECT customerId FROM customers WHERE userId = ?");
                    $stmt->execute([$userId]);
                    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($customer) {
                        error_log("Found existing customerId: " . $customer['customerId']);
                        return (int)$customer['customerId'];
                    }

                    // Nếu User đã đăng nhập nhưng chưa có trong bảng customers thì tạo mới
                    error_log("customerId not found for userId=$userId, creating new...");
                    $stmt = $this->conn->prepare("INSERT INTO customers (userId) VALUES (?)");
                    $stmt->execute([$userId]);
                    $newCustomerId = (int)$this->conn->lastInsertId();
                    error_log("Created new customerId: " . $newCustomerId);
                    return $newCustomerId;
                } catch (\Exception $e) {
                    error_log("getCustomerId exception: " . $e->getMessage());
                    return null;
                }
            }
        }
        
        // Nếu chưa đăng nhập, dùng SESSION ID làm guest ID
        // Guest users sẽ có customerId với NULL userId
        $sessionId = session_id();
        $guestCustomerId = 1000000 + crc32($sessionId) % 900000;
        error_log("No logged-in user, using guest customerId: " . $guestCustomerId . " (from sessionId: " . $sessionId . ")");
        return $guestCustomerId;
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

        error_log("=== CartController::getCart CALLED ===");
        $customerId = $this->getCustomerId();

        error_log("CartController::getCart - resolved customerId=" . json_encode($customerId));

        if (!$customerId) {
            error_log("CartController::getCart - customerId is null/falsy, returning empty");
            echo json_encode([]);
            exit();
        }

        try {
            $items = $this->cartService->getCart($customerId);
            $count = count($items);
            error_log("CartController::getCart - items count=" . $count . ", items=" . json_encode($items));

            // Expose debug info in response headers to help local debugging
            header('X-Cart-Customer: ' . $customerId);
            header('X-Cart-Count: ' . $count);

            // Return items
            echo json_encode($items);
        } catch (\Exception $e) {
            error_log("CartController::getCart - Exception: " . $e->getMessage());
            echo json_encode([]);
        }
        exit();
    }

    /** Thêm sản phẩm vào giỏ */
    public function add() {
        header('Content-Type: application/json');
        
        try {
            $customerId = $this->getCustomerId();

            if (!$customerId) {
                error_log("Cart::add - Cannot get customer ID");
                echo json_encode(["error" => "Cannot get customer ID", "success" => false]);
                exit();
            }

            // Nhận data từ JSON body (cho combo)
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Support cả POST form data và JSON
            $variantId = $input['variantId'] ?? $_POST['variantId'] ?? null;
            $comboId = $input['comboId'] ?? $_POST['comboId'] ?? null;
            $quantity = $input['quantity'] ?? $_POST['quantity'] ?? 1;
            $isCombo = $input['isCombo'] ?? $_POST['isCombo'] ?? false;

            error_log("Cart::add - variantId=$variantId, comboId=$comboId, quantity=$quantity, isCombo=$isCombo");

            // Validate: Phải có variantId hoặc comboId
            if (!$variantId && !$comboId) {
                echo json_encode(["error" => "Missing variantId or comboId", "success" => false]);
                exit();
            }

            // Xử lý thêm combo
            if ($isCombo && $comboId) {
                $this->cartService->addComboToCart($customerId, $comboId, $quantity);
            } 
            // Xử lý thêm sản phẩm bình thường
            elseif ($variantId) {
                $this->cartService->addToCart($customerId, $variantId, $quantity);
            }

            // Lấy giỏ hàng mới nhất
            $items = $this->cartService->getCart($customerId);

            echo json_encode([
                "success" => true,
                "data" => $items
            ]);

            exit();
        } catch (Exception $e) {
            error_log("Cart::add - Exception: " . $e->getMessage() . " - " . $e->getTraceAsString());
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
            exit();
        }
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
