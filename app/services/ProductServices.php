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

    // ==========================================
    // 1. QUẢN LÝ DANH MỤC (CATEGORY)
    // ==========================================
    public function getAllCategories() {
        // Trả về mảng trực tiếp giúp Frontend dễ xử lý map/foreach
        return $this->categoryModel->getAllCategories() ?: [];
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

    // ==========================================
    // 2. QUẢN LÝ SẢN PHẨM (PRODUCT)
    // ==========================================
    public function getAllProducts() {
        // Đảm bảo khớp với Controller->index()
        return $this->productModel->getAllProductsWithVariants() ?: [];
    }
    
    public function getProductDetail($id) {
        $product = $this->productModel->findProduct($id);
        if (!$product) return ['success' => false, 'message' => 'Sản phẩm không tồn tại'];

        $variants = $this->variantModel->getVariantsByProductId($id);
        $variants = $variants ?: []; 

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
    
    public function addProduct($data) {
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
        $res = $this->productModel->deleteProductComplete($id);
        return $res ? ['success' => true] : ['success' => false];
    }

    // ==========================================
    // 3. QUẢN LÝ BIẾN THỂ (VARIANT)
    // ==========================================
    public function addVariant($data) {
        $res = $this->productModel->addVariant($data);
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

    // HÀM dành cho nút Thêm mới: Xử lý Upload Ảnh + Lưu Sản phẩm + Lưu Biến thể
    public function addFullProductAndVariants($postData, $fileData) {
    // 1. Bắt đầu Transaction từ Model
    $this->productModel->beginTransaction();

    try {
        // --- BƯỚC 1: Xử lý file ảnh (giữ nguyên logic của bạn) ---
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

        $postData['imagePath'] = $imageName;
        // staffId nên lấy từ Session ở Controller truyền vào, ở đây tạm giữ logic của bạn
        $postData['staffId'] = $postData['staffId'] ?? 1;
        
        // --- BƯỚC 2: Thêm sản phẩm chính ---
        $resProduct = $this->addProduct($postData);

        if (!$resProduct['success']) {
            // Nếu không tạo được sản phẩm, ném ra lỗi để nhảy vào catch
            throw new Exception("Lỗi: Không thể tạo thông tin sản phẩm chính.");
        }

        $newProductId = $resProduct['productId'];

        // --- BƯỚC 3: Thêm các biến thể ---
        // 1. Kiểm tra nếu nó là chuỗi thì biến nó thành mảng để xử lý
        $variantsArray = $postData['variants'];
        if (!is_array($variantsArray)) {
            $variantsArray = [$variantsArray]; // Ép chuỗi "Bạc|M|..." vào trong 1 mảng []
        }

        if (!empty($variantsArray)) {
            foreach ($variantsArray as $variantString) {
                $parts = explode('|', $variantString);
                if (count($parts) === 4) {
                    $resVariant = $this->addVariant([
                        'productId' => $newProductId,
                        'color'     => trim($parts[0]),
                        'size'      => trim($parts[1]),
                        'price'     => (float)preg_replace('/[^0-9.]/', '', $parts[2]), 
                        'stock'     => (int)$parts[3]
                    ]);

                    if (!$resVariant['success']) {
                        throw new Exception("Lỗi: Không thể thêm biến thể sản phẩm.");
                    }
                }
            }
        } else {
            throw new Exception("Lỗi: Sản phẩm phải có ít nhất một biến thể.");
        }

        // --- BƯỚC 4: Nếu chạy đến đây không lỗi, lưu vĩnh viễn vào DB ---
        $this->productModel->commit();
        return ['success' => true, 'message' => 'Thêm sản phẩm và biến thể thành công!'];

        } catch (Exception $e) {
            // --- BƯỚC 5: Nếu có bất kỳ lỗi nào ở trên, hủy bỏ mọi thay đổi ---
            $this->productModel->rollBack();

            // Xóa file ảnh vừa upload nếu đã lỡ upload thành công để tránh rác server
            if ($imageName) {
                $pathToDelete = __DIR__ . "/../../public/assets/images/products/" . $imageName;
                if (file_exists($pathToDelete)) unlink($pathToDelete);
            }

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // HÀM dành cho nút Chỉnh sửa: Xử lý Upload Ảnh + Lưu Sản phẩm + Lưu Biến thể (KHI CẬP NHẬT XONG)
    public function updateFullProductAndVariants($id, $data, $files) {
        try {
            // 1. Xử lý ảnh (Chỉ upload nếu người dùng chọn file mới)
            // 1. Xử lý ảnh
        $imageName = null;
        if (isset($files['image']) && $files['image']['error'] === 0) {
            $ext = strtolower(pathinfo($files['image']['name'], PATHINFO_EXTENSION));
            $imageName = "glass_upd_" . time() . "." . $ext;
            $targetDir = __DIR__ . "/../../public/assets/images/products/";
            
            if (move_uploaded_file($files['image']['tmp_name'], $targetDir . $imageName)) {
                // Chỉ lưu tên file hoặc đường dẫn tương đối khớp với lúc hiển thị
                $data['imagePath'] = $imageName; 
            }
        }
            // 2. Cập nhật bảng products (Model sẽ lo việc giữ ảnh cũ nếu không có imagePath mới)
            $this->productModel->updateProduct($id, $data);

            // 3. Cập nhật Biến thể
            if (!empty($data['variants'])) {
                $this->productModel->deleteVariantsByProductId($id);
                
                // Kiểm tra nếu là chuỗi (từ Frontend gửi về thường là chuỗi "Màu|Size|Giá|Kho")
                $variantGroups = is_array($data['variants']) ? $data['variants'] : [$data['variants']];
                
                foreach ($variantGroups as $group) {
                    $parts = explode('|', $group);
                    if (count($parts) === 4) {
                        $this->productModel->addVariant([
                            'productId' => $id,
                            'color' => trim($parts[0]),
                            'size'  => trim($parts[1]),
                            'price' => (float)preg_replace('/[^0-9.]/', '', $parts[2]),
                            'stock' => (int)$parts[3]
                        ]);
                    }
                }
            }

            return ['success' => true, 'message' => 'Cập nhật sản phẩm thành công!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
