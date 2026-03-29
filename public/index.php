    <?php
    require_once "../app/controllers/AuthController.php";
    require_once "../app/controllers/ProductController.php";
    // Yen them
    require_once "../app/controllers/OrderController.php"; // TRAN HUYNH
    // THIEN TRU
    require_once "../app/controllers/PromotionController.php";
    require_once "../app/controllers/StaffController.php";
    // tạo controller
        //Yen them
    $authController = new AuthController();
    $productController = new ProductController();   //Yen them
    $orderController = new OrderController(); 

    $staffController = new StaffController(); 

    $url = $_GET['url'] ?? '';
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $variantId = isset($_GET['variantId']) ? $_GET['variantId'] : null;

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

        case "create-order":
            $orderController->create();
            break;

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