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
            'price'       => $_POST['price'] ?? 0,
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
    // 1. Lấy toàn bộ dữ liệu từ Service
    $result = $this->productService->getAllProducts();

    // 2. Nhận diện loại yêu cầu (Request Detection)
    // Nếu có header Accept: application/json hoặc có tham số ?type=json trên URL
    $isJsonRequest = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
                  || (isset($_GET['format']) && $_GET['format'] === 'json');

   if ($isJsonRequest) {
    header('Content-Type: application/json; charset=utf-8');
    $response = [
        'success' => true,
        'data' => $result // $result này chính là mảng bạn thấy trong hình
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}
    else {
        // --- ĐÂY LÀ PHẦN HIỂN THỊ CHO KHÁCH HÀNG ---
        
        // Vì Service của bạn trả về mảng trực tiếp (như hình Thunder Client)
        // Nên bạn gán thẳng $result cho $products luôn
        $products = $result; 

        // Nếu bạn muốn an toàn hơn, có thể viết:
        // $products = (isset($result['data'])) ? $result['data'] : $result;

        $viewPath = __DIR__ . '/../views/products/all_products.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "Lỗi: Không tìm thấy file giao diện tại " . $viewPath;
        }
        exit();
    }
}

    // Hiển thị chi tiết (Dùng cho nút Xem chi tiết)
   public function detail($id) {
    // 1. Kiểm tra ID hợp lệ (Dùng chung cho cả 2 bên)
    if (!$id || !is_numeric($id)) {
        $this->handleError("ID không hợp lệ");
    }

    // 2. Lấy dữ liệu từ Service
    $result = $this->productService->getProductDetail($id);

    // 3. Kiểm tra sản phẩm có tồn tại không
    if (!$result || (isset($result['success']) && $result['success'] === false)) {
        $this->handleError("Sản phẩm không tồn tại");
    }

    // 4. Nhận diện loại yêu cầu (Request Detection)
    $isJsonRequest = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
                  || (isset($_GET['format']) && $_GET['format'] === 'json');

    if ($isJsonRequest) {
        // --- PHẦN CỦA BẠN (TRẢ VỀ JSON CHO MODAL) ---
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    } else {
        // --- PHẦN CỦA BẠN BẠN (TRẢ VỀ GIAO DIỆN CHI TIẾT) ---
        // Gán dữ liệu vào biến để file .php sử dụng
        // Tùy Service trả về mảng phẳng hay mảng có key 'data'
        $product = $result['data'] ?? $result; 

        $viewPath = __DIR__ . '/../views/product-detail.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "Lỗi: Không tìm thấy file giao diện chi tiết.";
        }
        exit();
    }
}

// Hàm bổ trợ để xử lý lỗi nhanh cho cả 2 loại yêu cầu
private function handleError($message) {
    $isJson = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    if ($isJson) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => $message]);
    } else {
        die($message);
    }
    exit();
}
}