<?php
require_once __DIR__ . "/../services/ProductServices.php";

class ProductController {
    private $productService;

    public function __construct() {
        $this->productService = new ProductServices();
    }

    // QUẢN LÝ DANH MỤC (CATEGORY)

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

    // QUẢN LÝ SẢN PHẨM (PRODUCT)

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

    // QUẢN LÝ BIẾN THỂ (VARIANT)

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

    // HIỂN THỊ DANH SÁCH SẢN PHẨM
 
    // Hiển thị danh sách sản phẩm đầy đủ
    public function index() {
        // 1. Lấy toàn bộ dữ liệu từ Service
        $result = $this->productService->getAllProducts();

        // 2. Nhận diện loại yêu cầu (Request Detection)
        // Nếu có header Accept: application/json hoặc có tham số ?format=json trên URL
        $isJsonRequest = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
                      || (isset($_GET['format']) && $_GET['format'] === 'json');

        if ($isJsonRequest) {
            header('Content-Type: application/json; charset=utf-8');
            $response = [
                'success' => true,
                'data' => $result 
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            //  PHẦN HIỂN THỊ CHO KHÁCH HÀNG 
            // Service trả về mảng trực tiếp nên gán thẳng $result cho $products 
            $products = $result; 

            $viewPath = __DIR__ . '/../views/products/all_products.php';
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo "Lỗi: Không tìm thấy file giao diện tại " . $viewPath;
            }
            exit();
        }
    }

    // Lấy sản phẩm theo danh mục (Category Filter)
    public function getProductsByCategory() {
        $categoryName = isset($_GET['category']) ? trim($_GET['category']) : '';
        
        if (empty($categoryName)) {
            $this->handleError("Danh mục không hợp lệ");
        }

        // Lấy sản phẩm theo danh mục từ Service
        $result = $this->productService->getProductsByCategory($categoryName);

        // Kiểm tra loại yêu cầu
        $isJsonRequest = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
                      || (isset($_GET['format']) && $_GET['format'] === 'json');

        if ($isJsonRequest) {
            header('Content-Type: application/json; charset=utf-8');
            $response = [
                'success' => true,
                'data' => $result,
                'category' => $categoryName
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            // Hiển thị view với sản phẩm lọc
            $products = $result;
            $categoryTitle = $categoryName;
            $viewPath = __DIR__ . '/../views/products/all_products.php';
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo "Lỗi: Không tìm thấy file giao diện tại " . $viewPath;
            }
            exit();
        }
    }

    // Lấy sản phẩm theo ID danh mục (Category Filter by ID)
    public function getProductsByCategoryId() {
        $categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;
        
        if ($categoryId <= 0) {
            $this->handleError("Danh mục không hợp lệ");
        }

        // Lấy sản phẩm theo categoryId từ Service
        $result = $this->productService->getProductsByCategoryId($categoryId);

        // Kiểm tra loại yêu cầu
        $isJsonRequest = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
                      || (isset($_GET['format']) && $_GET['format'] === 'json');

        if ($isJsonRequest) {
            header('Content-Type: application/json; charset=utf-8');
            $response = [
                'success' => true,
                'data' => $result,
                'categoryId' => $categoryId
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            // Hiển thị view với sản phẩm lọc
            $products = $result;
            
            // Lấy tên danh mục để hiển thị tiêu đề
            $categoryName = '';
            $categoryMap = [
                1 => 'Gọng Nam',
                2 => 'Gọng Nữ',
                3 => 'Gọng Trẻ Em',
                4 => 'Chống Ánh Sáng Xanh',
                5 => 'Kính Đổi Màu',
                6 => 'Kính Siêu Mỏng'
            ];
            
            if (isset($categoryMap[$categoryId])) {
                $categoryName = $categoryMap[$categoryId];
            }
            
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
            // Trả JSON cho các modal / AJAX
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            // Trả về view chi tiết
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

    // TÌM KIẾM SẢN PHẨM
    public function searchProducts() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($keyword)) {
            $this->handleError("Từ khóa tìm kiếm không hợp lệ");
        }

        // Lấy kết quả từ Service
        $result = $this->productService->searchProducts($keyword);

        // Kiểm tra loại yêu cầu
        $isJsonRequest = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
                      || (isset($_GET['format']) && $_GET['format'] === 'json');

        if ($isJsonRequest) {
            header('Content-Type: application/json; charset=utf-8');
            $response = [
                'success' => true,
                'data' => $result,
                'keyword' => $keyword,
                'count' => count($result)
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            // Hiển thị view với kết quả tìm kiếm
            $products = $result;
            $categoryName = 'Kết quả tìm kiếm: "' . htmlspecialchars($keyword) . '"';
            $viewPath = __DIR__ . '/../views/products/all_products.php';
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo "Lỗi: Không tìm thấy file giao diện tại " . $viewPath;
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

    /**
     * Lấy tất cả sản phẩm (API - dùng cho Combo Manager)
     */
    public function getAllProducts() {
        // Force JSON response
        header('Content-Type: application/json; charset=utf-8');
        header('Accept: application/json');

        try {
            $products = $this->productService->getAllProducts();
            
            // Transform to simpler format for combo selection
            $data = array_map(function($product) {
                return [
                    'productId' => (int)$product['productId'],
                    'name' => $product['name'] ?? '',
                    'price' => (int)($product['minPrice'] ?? 0),
                    'description' => $product['description'] ?? ''
                ];
            }, $products);
            
            echo json_encode([
                'success' => true,
                'data' => $data
            ], JSON_UNESCAPED_UNICODE);
            exit();
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
}
