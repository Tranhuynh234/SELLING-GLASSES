<?php
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
        $authController->updateProfile();
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
     case "create-user":
        AuthMiddleware::handle(['staff'], ['manager']);
        $userController->createUser();
        exit();

    case "delete-user":
        AuthMiddleware::handle(['staff'], ['manager']);
        $userController->deleteUser();
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
        $orderController->cancel();
        exit();

    // case "return-order":
    // $orderController->return();
    // exit();

    case "order-stats":
        $orderController->stats();
        exit();

    // case "shipment-tracking":
    // $trackingNumber = $_GET['trackingNumber'] ?? null;
    // $orderController->shipmentTracking($trackingNumber);
    // break;

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
    default:
        http_response_code(404);
        echo "404 Not Found";
    exit();
}
?>
