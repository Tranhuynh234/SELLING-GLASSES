<?php

require_once "../config/db_connect.php";
require_once "../app/controllers/AuthController.php";

// tạo controller
$authController = new AuthController($conn);

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

    default:
        echo "Invalid action";
        break;
}