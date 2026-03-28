<?php
require_once __DIR__ . "/../models/product/productModel.php";
require_once __DIR__ . "/../models/product/product_variantModel.php";
require_once __DIR__ . "/../models/product/categoryModel.php";

class ProductServices {
    private $productModel;
    private $variantModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->variantModel = new ProductVariantModel();
        $this->categoryModel = new CategoryModel();
    }

    // ================================
    // CRUD DANH MỤC (Category)
    // ================================
    public function getAllCategories() {
        $data = $this->categoryModel->getAllCategories();
        return ['success' => true, 'data' => $data];
    }

    public function addCategory($data) {
        $categoryId = $this->categoryModel->create(['name' => $data['name']]);
        return $categoryId ? ['success' => true, 'message' => 'Thêm danh mục thành công'] : ['success' => false];
    }

    public function editCategory($id, $data) {
        $res = $this->categoryModel->update($id, ['name' => $data['name']], "categoryId");
        return $res ? ['success' => true] : ['success' => false];
    }

    public function deleteCategory($id) {
        $res = $this->categoryModel->delete($id, "categoryId");
        return $res ? ['success' => true] : ['success' => false];
    }

    // ===============================
    // CRUD SẢN PHẨM (Product)
    // ===============================
    public function addProduct($data) {
        $productId = $this->productModel->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'categoryId' => $data['categoryId'],
            'imagePath' => $data['imagePath'] ?? null,
            'staffId' => $data['staffId']
        ]);
        return $productId ? ['success' => true, 'productId' => $productId] : ['success' => false];
    }

    public function editProduct($id, $data) {
        $res = $this->productModel->update($id, [
            'name' => $data['name'],
            'description' => $data['description'],
            'categoryId' => $data['categoryId'],
            'imagePath' => $data['imagePath'] ?? null
        ], "productId");
        return $res ? ['success' => true] : ['success' => false];
    }

    public function deleteProduct($id) {
        $this->variantModel->delete($id, "productId"); 
        $res = $this->productModel->delete($id, "productId");
        return $res ? ['success' => true] : ['success' => false];
    }

    // ===============================
    // QUẢN LÝ BIẾN THỂ SẢN PHẨM
    // ===============================
    public function addVariant($data) {
        $res = $this->variantModel->create([
            'productId' => $data['productId'],
            'color' => $data['color'],
            'size' => $data['size'],
            'price' => $data['price'],
            'stock' => $data['stock']
        ]);
        return $res ? ['success' => true] : ['success' => false];
    }

    public function updateVariant($variantId, $data) {
        $res = $this->variantModel->update($variantId, [
            'color' => $data['color'],
            'size' => $data['size'],
            'price' => $data['price'],
            'stock' => $data['stock']
        ], "variantId");
        return $res ? ['success' => true] : ['success' => false];
    }

    // ================================
    // HIỂN THỊ DANH SÁCH SẢN PHẨM
    // ================================
    
    // API: get product list
    public function getAllProductsWithDetails() {
        $products = $this->productModel->getAllProducts();
        $result = [];
        foreach ($products as $product) {
            $variants = $this->variantModel->getVariantsByProductId($product->productId);
            $category = $this->categoryModel->findCategory($product->categoryId);
            $result[] = [
                'product' => $product,
                'category_name' => $category ? $category->name : 'N/A',
                'variants' => $variants
            ];
        }
        return ['success' => true, 'data' => $result];
    }

    // API: get product detail
    public function getProductDetail($id) {
        $product = $this->productModel->findProduct($id);
        if (!$product) return ['success' => false, 'message' => 'Sản phẩm không tồn tại'];

        $variants = $this->variantModel->getVariantsByProductId($id);
        $category = $this->categoryModel->findCategory($product->categoryId);

        return [
            'success' => true,
            'data' => [
                'product' => $product,
                'category' => $category,
                'variants' => $variants
            ]
        ];
    }
}