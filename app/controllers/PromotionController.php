<?php
require_once __DIR__ . "/../services/PromotionServices.php";

class PromotionController {
    private $service;

    public function __construct() {
        $this->service = new PromotionService();
    }

    public function getAll() {
        header('Content-Type: application/json; charset=utf-8');

        // Lấy từ khóa 'name' từ query string (do JS gửi lên)
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

        // Service sẽ trả về dữ liệu đã lọc theo tên và phân trang
        $data = $this->service->getPromotions($page, $limit, $name);
        $totalPages = $this->service->getTotalPages($limit, $name);

        $result = array_map(function($p) {
            return [
                'promotionId' => $p->getPromotionId(),
                'name' => $p->getName(),
                'discount' => $p->getDiscount(),
                'discountType' => $p->getDiscountType(),
                'startDate' => $p->getStartDate(),
                'endDate' => $p->getEndDate(),
                'status' => $p->getStatus(),
                'staffId' => $p->getStaffId(),
            ];
        }, $data);

        echo json_encode([
            'success' => true,
            'page' => $page,
            'totalPages' => $totalPages,
            'data' => $result
        ]);
    }
    public function delete() {
    header('Content-Type: application/json; charset=utf-8');
    
    // Lấy ID từ URL (ví dụ: ?id=5)
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id > 0) {
        $result = $this->service->deletePromotion($id);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa dữ liệu']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    }
}
public function edit() {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    
    // Gọi Service để lấy dữ liệu cũ
    $data = $this->service->getPromotionById($id);
    
    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu']);
    }
}

public function update() {
    header('Content-Type: application/json');
    
    // Nhận dữ liệu JSON từ request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $id = $data['promotionId'] ?? 0;
    
    if ($id > 0) {
        // Tách ID ra khỏi mảng dữ liệu để hàm update của BaseModel hoạt động đúng
        $promoId = $data['promotionId'];
        unset($data['promotionId']); 
        
        // Gọi Service thực hiện cập nhật
        $result = $this->service->updatePromotion($promoId, $data);
        
        echo json_encode(['success' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    }
}
public function create() {
    // 1. Khởi tạo session
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    // Đảm bảo trả về header JSON để trình duyệt hiểu đúng
    header('Content-Type: application/json');

    // 2. Lấy dữ liệu từ Frontend
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ"]);
        exit();
    }

    // 3. Gán staffId AN TOÀN (Sửa lỗi tại đây)
    // Kiểm tra nếu có session thì lấy, không có thì gán mặc định để tránh ném lỗi Warning ra màn hình
    if (isset($_SESSION['user']['staffId'])) {
        $data['staffId'] = $_SESSION['user']['staffId'];
    } else {
        $data['staffId'] = 1; // fallback
    }

    try {
        $result = $this->service->createPromotion($data);

        if ($result) {
            echo json_encode([
                "success" => true, 
                "message" => "Tạo khuyến mãi thành công",
                "staffId" => $data['staffId']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Không thể lưu dữ liệu"]);
        }
    } catch (Exception $e) {
        // Trả về lỗi dưới dạng JSON thay vì để PHP tự in lỗi ra
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit();
}
public function applyPromotion() {
    $promotionId = $_POST['promotionId'] ?? null;
    $productIds = $_POST['productIds'] ?? []; // Mảng ID sản phẩm từ checkbox

    $service = new PromotionService();
    $result = $service->applyToProducts($promotionId, $productIds);

    // Trả về kết quả JSON cho giao diện
    echo json_encode($result);
}
public function cancelPromotion() {
    // Nhận dữ liệu từ FormData gửi lên
    $productIds = $_POST['productIds'] ?? [];

    $service = new PromotionService();
    $result = $service->cancelForProducts($productIds);

    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}
}