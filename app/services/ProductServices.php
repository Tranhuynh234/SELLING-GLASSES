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
        return $categoryId ? ['success' => true, 'message' => 'Them danh muc thanh cong'] : ['success' => false];
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
        // Đồng bộ: Gọi trực tiếp hàm addProduct của Model
        $productId = $this->productModel->addProduct($data);
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
        // Đồng bộ: Gọi hàm xóa Transaction trong Model
        $res = $this->productModel->deleteProductComplete($id);
        return $res ? ['success' => true] : ['success' => false];
    }

    // ===============================
    // QUẢN LÝ BIẾN THỂ SẢN PHẨM
    // ===============================
    public function addVariant($data) {
        // Đồng bộ: Gọi hàm createVariant của Model
        $res = $this->productModel->createVariant($data);
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
    public function getAllProductsWithDetails() {
        $data = $this->productModel->getAllProductsWithVariants(); 
        return ['success' => true, 'data' => $data];
    }

    // ProductServices.php
    public function getProductDetail($id) {
        $product = $this->productModel->findProduct($id);
        if (!$product) return ['success' => false, 'message' => 'Sản phẩm không tồn tại'];

        $variants = $this->variantModel->getVariantsByProductId($id);
        // Đảm bảo variants luôn là mảng để Frontend không bị crash
        if (!$variants) $variants = []; 

        $category = $this->categoryModel->findCategory($product->categoryId);

        return [
            'success' => true,
            'data' => [
                'productId'   => $product->productId,
                'name'        => $product->name,
                'description' => $product->description,
                'imagePath'   => $product->imagePath,
                'categoryName'=> $category ? $category->name : 'Mắt kính',
                'variants'    => $variants
            ]
        ];
    }

    // Hàm "Tổng lực": Xử lý Upload Ảnh + Lưu Sản phẩm + Lưu Biến thể
    public function addFullProductAndVariants($postData, $fileData) {
        // 1. Xử lý Upload ảnh
        $imageName = null; 
        if (isset($fileData['image']) && $fileData['image']['error'] === 0) {
            $ext = strtolower(pathinfo($fileData['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $allowed)) {
                $imageName = "glass_" . time() . "_" . uniqid() . "." . $ext;
                $targetDir = __DIR__ . "/../../public/assets/images/products/";
                
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $targetFile = $targetDir . $imageName;
                if (!move_uploaded_file($fileData['image']['tmp_name'], $targetFile)) {
                    $imageName = null; 
                }
            }
        }

        // 2. Lưu sản phẩm chính
        $postData['imagePath'] = $imageName;
        $postData['staffId'] = $postData['staffId'] ?? 1;
        
        $resProduct = $this->addProduct($postData);

        if ($resProduct['success']) {
            $newProductId = $resProduct['productId'];

            // 3. Xử lý danh sách biến thể
            if (!empty($postData['variants']) && is_array($postData['variants'])) {
                foreach ($postData['variants'] as $variantString) {
                    $parts = explode('|', $variantString);
                    if (count($parts) === 4) {
                        $this->addVariant([
                            'productId' => $newProductId,
                            'color'     => trim($parts[0]),
                            'size'      => trim($parts[1]),
                            'price'     => (float)preg_replace('/[^0-9.]/', '', $parts[2]), 
                            'stock'     => (int)$parts[3]
                        ]);
                    }
                }
            }
            return ['success' => true, 'message' => 'Thêm sản phẩm thành công!'];
        }

        return ['success' => false, 'message' => 'Lỗi: Không thể tạo sản phẩm.'];
    } 
} 
