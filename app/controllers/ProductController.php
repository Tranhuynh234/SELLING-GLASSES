<?php
require_once __DIR__ . "/../services/ProductServices.php";

class ProductController {
    private $productService;

    public function __construct() {
        $this->productService = new ProductServices();
    }

    // Hàm hỗ trợ trả về JSON chuẩn cho Frontend
    private function sendResponse($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ================================
    // QUẢN LÝ DANH MỤC (CATEGORY)
    // ================================

    // Lấy toàn bộ danh mục
    public function getAllCategories() {
        $result = $this->productService->getAllCategories();
        $this->sendResponse($result);
    }

    public function addCategory() {
        $data = ['name' => $_POST['name'] ?? '']; 
        $result = $this->productService->addCategory($data);
        $this->sendResponse($result);
    }

    public function updateCategory($id) {
        $data = ['name' => $_POST['name'] ?? ''];
        $result = $this->productService->editCategory($id, $data);
        $this->sendResponse($result);
    }

    public function deleteCategory($id) {
        $result = $this->productService->deleteCategory($id);
        $this->sendResponse($result);
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
            'variants'    => $_POST['variants'] ?? [] 
        ];

        // Truyền $_FILES để Service xử lý upload ảnh
        $result = $this->productService->addFullProductAndVariants($data, $_FILES);
        $this->sendResponse($result);
    }

    public function updateProduct($id) {
        $data = [
            'name'        => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'categoryId'  => $_POST['categoryId'] ?? 1,
            'imagePath'   => $_POST['imagePath'] ?? null
        ];
        $result = $this->productService->editProduct($id, $data);
        $this->sendResponse($result);
    }

    public function deleteProduct($id) {
        $result = $this->productService->deleteProduct($id);
        $this->sendResponse($result);
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
        $this->sendResponse($result);
    }

    public function updateVariant($variantId) {
        $data = [
            'color' => $_POST['color'] ?? '',
            'size'  => $_POST['size'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0
        ];
        $result = $this->productService->updateVariant($variantId, $data);
        $this->sendResponse($result);
    }

    // ================================
    // HIỂN THỊ DANH SÁCH SẢN PHẨM
    // ================================

    // Hiển thị danh sách sản phẩm đầy đủ
    public function index() {
        $result = $this->productService->getAllProductsWithDetails();
        $this->sendResponse($result);
    }

    // Hiển thị chi tiết (Dùng cho nút Xem chi tiết)
    public function detail($id) {
    // 1. Xóa bỏ mọi ký tự lạ hoặc khoảng trắng thừa ở đầu file PHP nếu có
    
    // 2. Kiểm tra ID
    if (!$id || !is_numeric($id)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
        exit;
    }

    // 3. Gọi Service (Hãy chắc chắn tên hàm trong Service là getProductDetail)
    // Nếu em đổi tên hàm ở Service thì ở đây cũng phải đổi theo nhé
    $result = $this->productService->getProductDetail($id);
    
    // 4. Trả về JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}
}
