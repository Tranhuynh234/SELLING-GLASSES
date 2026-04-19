<?php
session_start();
require_once __DIR__ . "/../config/db_connect.php";
require_once "../app/controllers/AuthController.php";
require_once "../app/controllers/ProductController.php";
// Yen them
require_once "../app/controllers/OrderController.php"; // TRAN HUYNH
// THIEN TRU
require_once "../app/controllers/PromotionController.php";
require_once "../app/controllers/StaffController.php";
require_once "../app/controllers/CartController.php";
require_once "../app/controllers/HomeController.php";
require_once __DIR__ . "/../app/controllers/UserController.php";
require_once "../app/controllers/PaymentController.php";
require_once "../app/controllers/PrescriptionController.php";
require_once "../app/controllers/ReviewController.php";
require_once "../app/controllers/ComboController.php";

$conn = Database::connect();

// tạo controller
//Yen them
$authController = new AuthController();
$productController = new ProductController(); //Yen them
$orderController = new OrderController();
$promotionController = new PromotionController();
$staffController = new StaffController();
$cartController = new CartController();
$homeController = new HomeController();
$userController = new UserController();
$paymentController = new PaymentController();
$prescriptionController = new PrescriptionController($conn);
$reviewController = new ReviewController();
$comboController = new ComboController();

$url = $_GET['url'] ?? '';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$variantId = isset($_GET['variantId']) ? $_GET['variantId'] : null;

switch ($url) {
    case "home":
        $homeController->index();
        exit();
    case "auth":
        $authController->showLogin();
    exit();

    case "login":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
      }
        exit();
    case "register":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        }
        exit();

    case "logout":
        $authController->logout();
        exit();

    case "profile":
        $authController->profile();
        exit();
    case "update-profile":
        $userController->updateProfile();
        exit();
    case "staff-save":
        $staffController->save();
        exit();
    case "manager":
        AuthMiddleware::handle(['staff'], ['manager']);
        require_once "../app/views/Dashboard/manager/mana.php";
        exit();

    case "sales":
        AuthMiddleware::handle(['staff'], ['sales']);
        require_once "../app/views/Dashboard/sale/sale.php";
        exit();

    case "operation":
        AuthMiddleware::handle(['staff'], ['operation']);
        require_once "../app/views/ops/ops.php";
        exit();

    case "register":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        }
        exit();

    case "get-users":
        AuthMiddleware::handle(['staff'], ['manager']);
        $userController->getAllUsers();
        exit();

    case "search-users":
        AuthMiddleware::handle(['staff'], ['manager']);
        $userController->searchUsers();
        exit();
        
    case "update-user":
        AuthMiddleware::handle(['staff'], ['manager']);
       $userController->updateUser();
        exit();

    case "update-prescription":
        // Chỉ cho phép khách hàng đã đăng nhập cập nhật đơn kính của họ
        $userController->updatePrescription();
        exit();

    case "create-user":
        AuthMiddleware::handle(['staff'], ['manager']);
        $userController->createUser();
        exit();

    case "delete-user":
        AuthMiddleware::handle(['staff'], ['manager']);
        $userController->deleteUser();
        exit();

    case "change-password":
        $userController->changePassword();
        exit();
    // --- PRESCRIPTION ---
    case "prescription":
        // Hiển thị giao diện nhập đơn kính
        $prescriptionController->create(); 
        exit();

    case "prescription-store":
        // Xử lý lưu dữ liệu đơn kính (POST)
        $prescriptionController->store();
        exit();

    case "get-prescription-session":
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Nếu tồn tại thì trả về giá, không thì trả về 0
        $price = isset($_SESSION['prescription_total']) ? $_SESSION['prescription_total'] : 0;
        
        echo json_encode(['price' => $price]);
        exit();

    // --- PHẦN QUẢN LÝ CỦA YẾN ---
    // --- CATEGORY ---
    case 'get-all-categories':
        $productController->getAllCategories();
        break;
    case 'add-category':
        $productController->addCategory();
        break;
    case 'update-category':
        $productController->updateCategory($id);
        break;
    case 'delete-category':
        $productController->deleteCategory($id);
        break;

    // --- PRODUCT ---
    case 'get-all-products':
        $productController->index();
        break;
    case 'add-product':
        $productController->addProduct();
        break;
    case 'update-product':
        $productController->updateProduct($id);
        break;
    case 'delete-product':
        $productController->deleteProduct($id);
        break;
    case 'detail':
        $productController->detail($id);
        break;

    // --- VARIANT ---
    case 'add-variant':
        $productController->addVariant();
        break;
    case 'update-variant':
        $productController->updateVariant($variantId);
        break;
    // ==== trân =========
    case "create-order":
        $orderController->create();
        break;

    case "get-orders-by-status":
        $orderController->getByStatus();
        exit();

    case "update-order-status":
        $orderController->updateStatus();
        exit();

    case "cancel-order":
        $orderController->cancelOrder();
        exit();

    case "get-order-detail":
        $orderController->getOrderDetail();
        exit();

    case "get-complaints":
        $promotionController->getComplaints();
        exit();

    case "process-complaint-request":
        $promotionController->processRequest();
        exit();

    case "contact-customer":
        $orderController->contactCustomer();
        exit();

    case "get-messages":
        $orderController->getMessages();
        exit();

    case "get-customer-messages":
        $orderController->getCustomerMessages();
        exit();

    case "get-support-unread-count":
        $orderController->getSupportUnreadCount();
        exit();

    case "send-customer-message":
        $orderController->sendCustomerMessage();
        exit();

    case "get-conversation-list":
        $orderController->getConversationList();
        exit();

    // case "return-order":
    // $orderController->return();
    // exit();

    case "order-stats":
        $orderController->stats();
        exit();

    case "dashboard-stats":
        $homeController->getDashboardStats();
        exit();

    case "order-revenue-stats":
        $homeController->getOrderRevenueStats();
        exit();

    case 'order-detail':
        $controller = new OrderController($conn);
        $controller->showDetail($_GET['id']);
        break;    

    // --- Promotion Module ---
    case 'create-promotion':
        $promotionController->createPromotion();
        break;

    case 'apply-promotion':
        $promotionController->applyPromotion();
        break;

    case 'upload-prescription':
        $promotionController->uploadPrescription();
        break;

    case 'request-return':
        $promotionController->requestReturn();
        break;

    // --- Combo Module ---
    case 'get-combos':
        $comboController->getCombos();
        exit();

    case 'get-combo':
        $comboController->getCombo();
        exit();

    case 'search-combos':
        // Search combos by name (used by combo-manager.js)
        $comboController->searchCombos();
        exit();

    case 'create-combo':
        $comboController->createCombo();
        exit();

    case 'update-combo':
        $comboController->updateCombo();
        exit();

    case 'delete-combo':
        $comboController->deleteCombo();
        exit();

    // --- Cart---//
    case 'get-cart':
        $cartController->getCart();
        exit();

    case 'add-to-cart':
        $cartController->add();
        exit();

    case 'update-cart':
        $cartController->update();
        exit();

    case 'remove-cart-item':
        $cartController->remove();
        exit();
    
    case "cart":
        require_once "../app/views/cart/cart.php";
        exit();

    case "checkout":
        if (session_status() === PHP_SESSION_NONE) session_start();

        // KIỂM TRA: Nếu khách vào checkout mà KHÔNG phải vừa từ trang nhập độ về thì xóa số tiền 300k cũ đi.
        if (!isset($_GET['status']) || $_GET['status'] !== 'saved') {
            unset($_SESSION['prescription_total']);
        }
        // Sau đó mới cho hiển thị trang checkout
        require_once "../app/views/order/checkout.php";
        exit();

    case "get-checkout-summary":
        $cartController->getCheckoutSummary();
        exit();

    case "create-pending-payment": 
        $paymentController->createPendingPayment();
        exit();  
        
    case "clear-prescription-session":
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['prescription_total']); 
        echo json_encode(['success' => true]);
        exit();
    
    // --- REVIEW ---
    case "submit-review":
        $reviewController->submitReview();
        exit();
    
    case "get-reviews":
        $reviewController->getReviews();
        exit();

    case "get-review-by-order":
        $reviewController->getReviewByOrder();
        exit();
   
    default:
        http_response_code(404);
        echo "404 Not Found";
        exit();    
}
?>
