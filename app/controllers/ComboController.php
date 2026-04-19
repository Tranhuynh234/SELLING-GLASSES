<?php

require_once __DIR__ . '/../models/product/comboModel.php';
require_once __DIR__ . '/../entities/product/Combo.php';

/**
 * ComboController
 * Xử lý tất cả request liên quan đến combo
 */
class ComboController
{
    protected $comboModel;
    protected $staffId;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->comboModel = new ComboModel();
        $this->staffId = $_SESSION['user']['staffId'] ?? $_SESSION['user']['userId'] ?? null;
    }

    /**
     * Kiểm tra quyền staff/manager
     */
    private function checkAuth() {
        if (!isset($_SESSION['user'])) {
            error_log("ComboController::checkAuth - No session user");
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit();
        }

        $position = $_SESSION['user']['position'] ?? null;
        $role = $_SESSION['user']['role'] ?? null;

        error_log("ComboController::checkAuth - role: $role, position: $position");

        // Chỉ staff/manager mới được tạo/sửa combo
        if ($role !== 'staff' || !in_array($position, ['manager', 'sales'])) {
            error_log("ComboController::checkAuth - Access denied for role=$role, position=$position");
            echo json_encode(['success' => false, 'error' => "Forbidden - chỉ staff được phép (role=$role, position=$position)"]);
            exit();
        }
    }

    /**
     * API: Lấy danh sách combo
     * GET /index.php?url=get-combos
     */
    public function getCombos()
    {
        try {
            $onlyActive = isset($_GET['active']) ? $_GET['active'] !== '0' : true;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

            $combos = $this->comboModel->getAll($onlyActive, $limit, $offset);

            // Format response
            $data = array_map(function ($combo) {
                return [
                    'comboId' => $combo->comboId,
                    'name' => $combo->name,
                    'description' => $combo->description,
                    'imagePath' => $combo->imagePath,
                    'price' => $combo->price,
                    'isActive' => (bool)$combo->isActive,
                    'createdAt' => $combo->createdAt,
                    'items' => $combo->items
                ];
            }, $combos);

            echo json_encode(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * API: Lấy chi tiết combo
     * GET /index.php?url=get-combo&id=1
     */
    public function getCombo()
    {
        try {
            $comboId = isset($_GET['id']) ? (int)$_GET['id'] : null;

            if (!$comboId) {
                echo json_encode(['success' => false, 'error' => 'Thiếu ID combo']);
                return;
            }

            $combo = $this->comboModel->getById($comboId);

            if (!$combo) {
                echo json_encode(['success' => false, 'error' => 'Combo không tồn tại']);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'comboId' => $combo->comboId,
                    'name' => $combo->name,
                    'description' => $combo->description,
                    'imagePath' => $combo->imagePath,
                    'price' => $combo->price,
                    'isActive' => (bool)$combo->isActive,
                    'items' => $combo->items
                ]
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * API: Tạo combo
     * POST /index.php?url=create-combo
     * Body: FormData {
     *   "name": "Combo...",
     *   "price": 500000,
     *   "description": "...",
     *   "isActive": true,
     *   "products": "[{\"productId\": 1, \"quantity\": 1}, ...]",
     *   "comboImage": <file>
     * }
     */
    public function createCombo()
    {
        try {
            // Kiểm tra quyền
            $this->checkAuth();

            // Hỗ trợ cả FormData và JSON
            $input = [];
            if (!empty($_POST)) {
                $input = $_POST;
                // Parse JSON string từ products
                if (!empty($_POST['products']) && is_string($_POST['products'])) {
                    $input['products'] = json_decode($_POST['products'], true);
                }
            } else {
                $input = json_decode(file_get_contents('php://input'), true);
            }

            // Validate input
            if (!$input || empty($input['name'])) {
                echo json_encode(['success' => false, 'error' => 'Tên combo bắt buộc']);
                return;
            }

            if (!isset($input['price']) || $input['price'] < 0) {
                echo json_encode(['success' => false, 'error' => 'Giá combo không hợp lệ']);
                return;
            }

            if (empty($input['products']) || !is_array($input['products'])) {
                echo json_encode(['success' => false, 'error' => 'Combo phải có ít nhất một sản phẩm']);
                return;
            }

            // Xử lý upload hình ảnh
            $imagePath = null;
            if (!empty($_FILES['comboImage'])) {
                $file = $_FILES['comboImage'];
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($ext, $allowed)) {
                    echo json_encode(['success' => false, 'error' => 'Chỉ hỗ trợ ảnh JPG, PNG, GIF, WEBP']);
                    return;
                }

                if ($file['size'] > 5 * 1024 * 1024) { // 5MB
                    echo json_encode(['success' => false, 'error' => 'Ảnh không được vượt quá 5MB']);
                    return;
                }

                // Tạo thư mục nếu chưa có
                $uploadDir = __DIR__ . '/../../public/assets/images/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Tạo tên file duy nhất
                $imageName = 'combo_' . time() . '_' . uniqid() . '.' . $ext;
                $imagePath = $imageName;
                $uploadPath = $uploadDir . $imageName;

                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    echo json_encode(['success' => false, 'error' => 'Lỗi tải ảnh lên']);
                    return;
                }
            }

            // Tạo entity
            $combo = new Combo(
                $input['name'],
                (float)$input['price'],
                $input['description'] ?? null,
                $imagePath,
                (bool)($input['isActive'] ?? true),
                $this->staffId
            );

            // Tạo combo
            $comboId = $this->comboModel->create($combo, $input['products']);

            echo json_encode([
                'success' => true,
                'message' => 'Tạo combo thành công',
                'comboId' => $comboId
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * API: Cập nhật combo
     * POST /index.php?url=update-combo
     * Body: FormData {
     *   "comboId": 1,
     *   "name": "...",
     *   "price": 500000,
     *   "isActive": true,
     *   "description": "...",
     *   "products": "[{\"productId\": 1, \"quantity\": 1}, ...]",
     *   "comboImage": <file (optional)>
     * }
     */
    public function updateCombo()
    {
        try {
            // Kiểm tra quyền
            $this->checkAuth();

            // Hỗ trợ cả FormData và JSON
            $input = [];
            if (!empty($_POST)) {
                $input = $_POST;
                // Parse JSON string từ products
                if (!empty($_POST['products']) && is_string($_POST['products'])) {
                    $input['products'] = json_decode($_POST['products'], true);
                }
            } else {
                $input = json_decode(file_get_contents('php://input'), true);
            }

            error_log("updateCombo input: " . json_encode($input));
            error_log("updateCombo _POST: " . json_encode($_POST));
            error_log("updateCombo _FILES: " . json_encode($_FILES ? array_keys($_FILES) : []));

            if (!$input || empty($input['comboId'])) {
                echo json_encode(['success' => false, 'error' => 'Thiếu ID combo']);
                return;
            }

            $comboId = (int)$input['comboId'];

            // Kiểm tra combo tồn tại
            if (!$this->comboModel->exists($comboId)) {
                echo json_encode(['success' => false, 'error' => 'Combo không tồn tại']);
                return;
            }

            // Chuẩn bị data cập nhật
            $data = [];
            if (isset($input['name'])) {
                $data['name'] = $input['name'];
            }
            if (isset($input['price'])) {
                $data['price'] = (float)$input['price'];
            }
            if (isset($input['description'])) {
                $data['description'] = $input['description'];
            }
            if (isset($input['isActive'])) {
                // Handle isActive - can be '0', '1', 0, 1, false, true
                $isActive = $input['isActive'];
                if (is_string($isActive)) {
                    $data['isActive'] = ($isActive === '1' || strtolower($isActive) === 'true') ? 1 : 0;
                } else {
                    $data['isActive'] = $isActive ? 1 : 0;
                }
                error_log("updateCombo isActive conversion: " . var_export($isActive, true) . " -> " . $data['isActive']);
            }

            // Xử lý upload hình ảnh mới (nếu có)
            if (!empty($_FILES['comboImage'])) {
                $file = $_FILES['comboImage'];
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($ext, $allowed)) {
                    echo json_encode(['success' => false, 'error' => 'Chỉ hỗ trợ ảnh JPG, PNG, GIF, WEBP']);
                    return;
                }

                if ($file['size'] > 5 * 1024 * 1024) { // 5MB
                    echo json_encode(['success' => false, 'error' => 'Ảnh không được vượt quá 5MB']);
                    return;
                }

                // Tạo thư mục nếu chưa có
                $uploadDir = __DIR__ . '/../../public/assets/images/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Xóa ảnh cũ nếu tồn tại
                $oldCombo = $this->comboModel->getById($comboId);
                if ($oldCombo && $oldCombo->imagePath) {
                    $oldImagePath = $uploadDir . $oldCombo->imagePath;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Tạo tên file duy nhất
                $imageName = 'combo_' . time() . '_' . uniqid() . '.' . $ext;
                $uploadPath = $uploadDir . $imageName;

                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    echo json_encode(['success' => false, 'error' => 'Lỗi tải ảnh lên']);
                    return;
                }

                $data['imagePath'] = $imageName;
            }

            $products = $input['products'] ?? [];

            error_log("updateCombo products: " . json_encode($products));
            error_log("updateCombo products is_array: " . (is_array($products) ? 'true' : 'false'));
            error_log("updateCombo products count: " . count($products));
            error_log("updateCombo data to update: " . json_encode($data));

            // Kiểm tra combo phải có ít nhất một sản phẩm
            if (empty($products) || !is_array($products)) {
                error_log("updateCombo FAILED - products invalid");
                echo json_encode(['success' => false, 'error' => 'Combo phải có ít nhất một sản phẩm']);
                return;
            }

            // Cập nhật
            error_log("updateCombo - calling model->update with comboId=$comboId");
            $this->comboModel->update($comboId, $data, $products);
            
            error_log("updateCombo SUCCESS");

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật combo thành công'
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * API: Xóa combo (soft delete - mềm)
     * DELETE /index.php?url=delete-combo&id=1
     */
    public function deleteCombo()
    {
        try {
            $comboId = isset($_GET['id']) ? (int)$_GET['id'] : null;

            if (!$comboId) {
                echo json_encode(['success' => false, 'error' => 'Thiếu ID combo']);
                return;
            }

            if (!$this->comboModel->exists($comboId)) {
                echo json_encode(['success' => false, 'error' => 'Combo không tồn tại']);
                return;
            }

            // Soft delete (đánh dấu deletedAt)
            $this->comboModel->softDelete($comboId);

            echo json_encode([
                'success' => true,
                'message' => 'Xóa combo thành công'
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * API: Khôi phục combo bị xóa
     * POST /index.php?url=restore-combo&id=1
     */
    public function restoreCombo()
    {
        try {
            $comboId = isset($_GET['id']) ? (int)$_GET['id'] : null;

            if (!$comboId) {
                echo json_encode(['success' => false, 'error' => 'Thiếu ID combo']);
                return;
            }

            $this->comboModel->restore($comboId);

            echo json_encode([
                'success' => true,
                'message' => 'Khôi phục combo thành công'
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * API: Tìm combo theo tên
     * GET /index.php?url=search-combos&name=combo
     */
    public function searchCombos()
    {
        // Ensure JSON Content-Type for clients
        header('Content-Type: application/json; charset=utf-8');

        try {
            $name = isset($_GET['name']) ? $_GET['name'] : '';

            if (empty($name)) {
                echo json_encode(['success' => false, 'error' => 'Nhập tên combo để tìm']);
                return;
            }

            $combos = $this->comboModel->searchByName($name);

            $data = array_map(function ($combo) {
                return [
                    'comboId' => $combo->comboId,
                    'name' => $combo->name,
                    'description' => $combo->description,
                    'price' => $combo->price,
                    'imagePath' => $combo->imagePath,
                    'isActive' => $combo->isActive,
                    'createdAt' => $combo->createdAt,
                    'items' => $combo->items
                ];
            }, $combos);

            echo json_encode(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
