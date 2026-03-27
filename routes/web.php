<?php

require_once "../config/db_connect.php";
require_once "../app/controllers/AuthController.php";

$authController = new AuthController();

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

    case "profile":
        $authController->profile();
        exit();

    default:
        echo "Invalid action";
        exit();
}