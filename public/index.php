<?php

require_once "../config/db_connect.php";
require_once "../app/controllers/AuthController.php";
require_once "../app/controllers/ProductController.php";  // Yen them

// tạo controller
$conn = Database::connect();    //Yen themd
$authController = new AuthController($conn);
$productController = new ProductController();   //Yen them

// lấy action từ URL
$action = $_GET['action'] ?? 'login';

// router (điều hướng)
switch ($action) {

    case "login":
        $authController->login();
        break;

    case "register":
        $authController->register();
        break;

    case "logout":
        $authController->logout();
        break;

    case "profile":
        $authController->profile();
        break;

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

    default:
        echo "Invalid action";
        break;
}