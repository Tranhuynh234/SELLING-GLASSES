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
        echo json_encode($result);
    }

    // Thêm danh mục mới
    public function addCategory() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->productService->addCategory($data);
        echo json_encode($result);
    }

    // Cập nhật danh mục
    public function updateCategory($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->productService->editCategory($id, $data);
        echo json_encode($result);
    }

    // Xóa danh mục
    public function deleteCategory($id) {
        $result = $this->productService->deleteCategory($id);
        echo json_encode($result);
    }

    // ================================
    // QUẢN LÝ SẢN PHẨM (PRODUCT)
    // ================================

    // Thêm sản phẩm mới
    public function addProduct() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->productService->addProduct($data);
        echo json_encode($result);
    }

    // Chỉnh sửa sản phẩm
    public function updateProduct($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->productService->editProduct($id, $data);
        echo json_encode($result);
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        $result = $this->productService->deleteProduct($id);
        echo json_encode($result);
    }

    // ================================
    // QUẢN LÝ BIẾN THỂ SẢN PHẨM 
    // ================================

    // Thêm màu sắc, size, giá cho sản phẩm
    public function addVariant() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->productService->addVariant($data);
        echo json_encode($result);
    }

    // Cập nhật thông tin biến thể
    public function updateVariant($variantId) {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->productService->updateVariant($variantId, $data);
        echo json_encode($result);
    }

    // ================================
    // HIỂN THỊ DANH SÁCH SẢN PHẨM
    // ================================

    // Hiển thị danh sách sản phẩm đầy đủ
    public function index() {
        $result = $this->productService->getAllProductsWithDetails();
        echo json_encode($result);
    }

    // Hiển thị chi tiết 1 sản phẩm cụ thể
    public function detail($id) {
        $result = $this->productService->getProductDetail($id);
        echo json_encode($result);
    }
}