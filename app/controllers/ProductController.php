<?php
require_once __DIR__ . "/../services/ProductServices.php";

class ProductController {
    private $productService;

    public function __construct() {
        $this->productService = new ProductServices();
    }

    // ================================
    // QUẢN LÝ DANH MỤC (CATEGORY)
    // ================================

    // Lấy toàn bộ danh mục
    public function getAllCategories() {
        $result = $this->productService->getAllCategories();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function addCategory() {
        $data = ['name' => $_POST['name'] ?? '']; 
        $result = $this->productService->addCategory($data);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function updateCategory($id) {
        $data = ['name' => $_POST['name'] ?? ''];
        $result = $this->productService->editCategory($id, $data);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function deleteCategory($id) {
        $result = $this->productService->deleteCategory($id);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ================================
    // QUẢN LÝ SẢN PHẨM (PRODUCT)
    // ================================

    public function addProduct() {
        // Thu thập dữ liệu từ FormData (bao gồm cả mảng variants)
        $data = [
            'name'        => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '', 
            'categoryId'  => $_POST['categoryId'] ?? 1,
            'staffId'     => $_POST['staffId'] ?? 1,
            'variants'    => $_POST['variants'] ?? '',
        ];

        // Truyền $_FILES để Service xử lý upload ảnh
        $result = $this->productService->addFullProductAndVariants($data, $_FILES);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function updateProduct($id) {
        $data = [
            'name'        => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'categoryId'  => $_POST['categoryId'] ?? 1,
            'staffId'     => $_POST['staffId'] ?? 1,
            'variants'    => $_POST['variants'] ?? '' // Nhận chuỗi Màu|Size|Giá|Kho
        ];

        // Truyền thêm $_FILES để xử lý cập nhật ảnh
        $result = $this->productService->updateFullProductAndVariants($id, $data, $_FILES);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function deleteProduct($id) {
        $result = $this->productService->deleteProduct($id);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ================================
    // QUẢN LÝ BIẾN THỂ (VARIANT)
    // ================================

    public function addVariant() {
        $data = [
            'productId' => $_POST['prodId'] ?? 0,
            'color'     => $_POST['color'] ?? '',
            'size'      => $_POST['size'] ?? '',
            'price'     => $_POST['price'] ?? 0,
            'stock'     => $_POST['stock'] ?? 0
        ];
        $result = $this->productService->addVariant($data);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function updateVariant($variantId) {
        $data = [
            'color' => $_POST['color'] ?? '',
            'size'  => $_POST['size'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0
        ];
        $result = $this->productService->updateVariant($variantId, $data);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ================================
    // HIỂN THỊ DANH SÁCH SẢN PHẨM
    // ================================

    // Hiển thị danh sách sản phẩm đầy đủ
    public function index() {
        $products = $this->productService->getAllProducts(); 
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($products, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // public function index() {
    //     // 1. Lấy dữ liệu từ Service
    //     $result = $this->productService->getAllProducts();

    //     // 2. Kiểm tra nếu là yêu cầu JSON (từ bạn của ông hoặc từ AJAX)
    //     // Cách 1: Kiểm tra Header Accept (như hàm detail của ông)
    //     // Cách 2: Kiểm tra tham số ?type=json trên URL (dễ test hơn)
    //     $isJson = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
    //               || (isset($_GET['type']) && $_GET['type'] === 'json');

    //     if ($isJson) {
    //         $this->sendResponse($result);
    //     }

    //     // 3. Nếu không phải JSON, nạp dữ liệu vào biến và gọi View
    //     // Dựa vào cấu trúc API của ông, sản phẩm nằm trong $result['data']
    //     $products = $result['data'] ?? [];
        
    //     // Giả lập phân trang cơ bản nếu cần
    //     $currentPage = $_GET['page'] ?? 1;
    //     $totalPages = 1; // Service hiện tại của ông chưa trả về pagination nên tạm để 1

    //     // 4. Gọi file giao diện (đảm bảo đường dẫn đúng)
    //     include dirname(__DIR__) . '/views/products/all_products.php';
    // }

    // Hiển thị chi tiết (Dùng cho nút Xem chi tiết)
    public function detail($id) {
    if (!$id || !is_numeric($id)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            exit();
        }

        $result = $this->productService->getProductDetail($id);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

//     // 1. Kiểm tra ID hợp lệ
//     if (!$id || !is_numeric($id)) {
//         if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
//             header('Content-Type: application/json');
//             echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
//             exit;
//         }
//         die("ID không hợp lệ");
//     }

//     // 2. Lấy dữ liệu từ Service
//     $result = $this->productService->getProductDetail($id);

//     // 3. KIỂM TRA: Nếu là yêu cầu từ file JS (có header Accept: application/json)
//     if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
//         header('Content-Type: application/json; charset=utf-8');
//         echo json_encode($result, JSON_UNESCAPED_UNICODE);
//         exit;
//     }

//     // 4. KIỂM TRA: Nếu là người dùng gõ URL trực tiếp (trình duyệt đòi HTML)
//     // Trả về file giao diện .php của bạn
//     include dirname(__DIR__) . '/views/product-detail.php';
// }
}