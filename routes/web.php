<?php
require_once "../app/controllers/AuthController.php";

$authController = new AuthController();

$url = $_GET['url'] ?? '';

switch ($url) {

    case "login":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            require_once "../app/views/auth/login.php";
        }
        break;

    case "register":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            require_once "../app/views/auth/register.php";
        }
        break;

    case "logout":
        $authController->logout();
        break;

    case "profile":
        $authController->profile();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}