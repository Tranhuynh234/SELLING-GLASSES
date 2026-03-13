<?php

require_once "../config/database.php";
require_once "../controllers/AuthController.php";

header("Content-Type: application/json");
$authController = new AuthController($conn);

// Kiểm tra hành động của người dùng
$action = $_GET['action'] ?? '';
switch($action) {
    case "register":
        $authController->register();
        break;
    case "login":
        $authController->login();
        break;
    default:
        echo json_encode ([
            "status" => "error",
            "message" => "Chức năng không tồn tại"
        ]);
        break;
}