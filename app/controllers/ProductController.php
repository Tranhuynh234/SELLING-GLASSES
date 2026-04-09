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
}
