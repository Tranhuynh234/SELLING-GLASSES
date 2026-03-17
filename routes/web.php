<?php

require_once "../config/db_connect.php";
require_once "../app/controllers/AuthController.php";

$authController = new AuthController($conn);

$action = $_GET['action'] ?? '';

switch ($action) {

    case "login":
        $authController->login();
        exit();

    case "register":
        $authController->register();
        exit();

    case "logout":
        $authController->logout();
        exit();
    case "check":
        $authController->check();
        exit();
// case "delete-product":
// case "update-profile":
// case "create-order":
    // case "add-product":
    default:
         echo "Invalid action";
        exit();
        

}