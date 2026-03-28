<?php
session_start();

require_once __DIR__ . "/../../config/db_connect.php";
require_once __DIR__ . "/../services/cartService.php";

$conn = Database::connect();

$cartService = new CartService($conn);

$userId = $_SESSION['userId'] ?? null;

if (!$userId) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("SELECT customerId FROM customers WHERE userId = ?");
$stmt->execute([$userId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo json_encode([]);
    exit();
}

$customerId = $customer['customerId'];

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'getCart':
        echo json_encode($cartService->getCart($customerId));
        break;

    case 'add':
        $cartService->addToCart($customerId, $_POST['variantId'], $_POST['quantity']);
        echo "success";
        break;

    case 'update':
        $cartService->updateItem($_POST['cartItemId'], $_POST['quantity']);
        echo "updated";
        break;

    case 'remove':
        $cartService->removeItem($_POST['cartItemId']);
        echo "deleted";
        break;
}
