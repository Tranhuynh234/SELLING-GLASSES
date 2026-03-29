<?php
require_once "../app/controllers/AuthController.php";
require_once "../app/controllers/ProductController.php";
// Yen them
require_once "../app/controllers/OrderController.php"; // TRAN HUYNH

// tạo controller
$productController = new ProductController();   //Yen them

//$orderController = new OrderController(); // TRAN HUYNH
require_once "../app/controllers/StaffController.php";

$staffController = new StaffController();
//$orderController = new OrderController(); // TRAN HUYNH  lỗi vì khong triển khai model. pull code về r sửa lạii
$authController = new AuthController();

$url = $_GET['url'] ?? '';

switch ($url) {

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
    case "delete-user":
        $staffController->delete();
        exit();

    // --- PHẦN QUẢN LÝ CỦA YẾN ---
    case "get-all-products":
        $productController->index();
        break;

    case "get-product-detail":
        $id = $_GET['id'] ?? null;
        $productController->detail($id);
        break;

    case "get-categories":
        $productController->getAllCategories();
        break;

    case "add-product":
        $productController->addProduct();
        break;

    case "delete-product":
        $id = $_GET['id'] ?? null;
        $productController->deleteProduct($id);
        break;

    //  case "create-order":
    //     $orderController->createOrder();
    //     break;

    // case "get-order-detail":
    //     $id = $_GET['id'] ?? null;
    //     $orderController->getOrderDetail($id);
    //     break;

    // case "payment":
    //     $orderId = $_GET['orderId'] ?? null;
    //     $orderController->payment($orderId);
    //     break;

    // case "shipment-tracking":
    //     $trackingNumber = $_GET['trackingNumber'] ?? null;
    //     $orderController->shipmentTracking($trackingNumber);
    //     break;

  
    default:
        http_response_code(404);
        echo "404 Not Found";
        exit();
}