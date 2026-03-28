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
        $name = isset($_GET['name']) ? $_GET['name'] : 'Danh mục mới ' . time();
        $data = ['name' => $name];
        $result = $this->productService->addCategory($data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    // Cập nhật danh mục
    public function updateCategory($id) {
        $name = isset($_GET['name']) ? $_GET['name'] : 'Tên cập nhật ' . time();
        $data = ['name' => $name];
        $result = $this->productService->editCategory($id, $data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
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
        $data = [
        'name'        => isset($_GET['name']) ? $_GET['name'] : 'Sản phẩm mới ' . time(),
        'description' => isset($_GET['desc']) ? $_GET['desc'] : 'Mô tả mặc định',
        'categoryId'  => isset($_GET['catId']) ? $_GET['catId'] : 1,
        'imagePath'   => isset($_GET['img']) ? $_GET['img'] : 'default.jpg',
        'staffId'     => isset($_GET['staffId']) ? $_GET['staffId'] : 1
        ];
        $result = $this->productService->addProduct($data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    // Chỉnh sửa sản phẩm
    public function updateProduct($id) {
        $data = [
            'name'        => isset($_GET['name']) ? $_GET['name'] : 'Tên SP cập nhật',
            'description' => isset($_GET['desc']) ? $_GET['desc'] : 'Mô tả cập nhật',
            'categoryId'  => isset($_GET['catId']) ? $_GET['catId'] : 1,
            'imagePath'   => isset($_GET['img']) ? $_GET['img'] : 'updated.jpg',
            'staffId'     => isset($_GET['staffId']) ? $_GET['staffId'] : 1
        ];
        $result = $this->productService->editProduct($id, $data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
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
        $data = [
            'productId' => isset($_GET['prodId']) ? $_GET['prodId'] : 1,
            'color'     => isset($_GET['color']) ? $_GET['color'] : 'Đen',
            'size'      => isset($_GET['size']) ? $_GET['size'] : 'M',
            'price'     => isset($_GET['price']) ? $_GET['price'] : 0,
            'stock'     => isset($_GET['stock']) ? $_GET['stock'] : 0
        ];
        $result = $this->productService->addVariant($data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    // Cập nhật thông tin biến thể
    public function updateVariant($variantId) {
        $data = [
            'color' => isset($_GET['color']) ? $_GET['color'] : 'Màu mới',
            'size'  => isset($_GET['size']) ? $_GET['size'] : 'L',
            'price' => isset($_GET['price']) ? $_GET['price'] : 0,
            'stock' => isset($_GET['stock']) ? $_GET['stock'] : 0
        ];
        $result = $this->productService->updateVariant($variantId, $data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
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