<?php
require_once dirname(__DIR__) . "/services/UserServices.php";
class UserController  {
    private $userService;
      public function __construct() {
        $this->userService = new UserService();
    }

   public function setUserService($userService) {
        $this->userService = $userService;
    }

    public function getUserService() {
        return $this->userService;
    }

    public function getAllUsers() {
        
        header("Content-Type: application/json");

        $result = $this->userService->getAllUsers();

        echo json_encode($result);
    }

    // Tìm kiếm user 
    public function searchUsers() {
        header("Content-Type: application/json");

        $keyword = $_GET['keyword'] ?? '';

        echo json_encode(
            $this->userService->searchUsers($keyword)
        );
    }

    // Update users
    public function updateUser() {
        header("Content-Type: application/json; charset=utf-8");

        $id = $_POST['userId'] ?? null;

        if (!$id) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu userId"
            ]);
            return;
        }

        $data = $_POST;
        unset($data['userId']); 

        $result = $this->userService->updateUser($id, $data);

        echo json_encode($result);
    }

    public function deleteUser() {
        header("Content-Type: application/json");

        $id = $_POST['userId'] ?? null;

        if (!$id) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu userId"
            ]);
            return;
        }

        $result = $this->userService->deleteUser($id);

        echo json_encode($result);
    }
    public function createUser() {
        header("Content-Type: application/json");

        $data = $_POST;

        $result = $this->userService->createUserByAdmin($data);

        echo json_encode($result);
    }

    public function updatePrescription() {
        ob_clean();
        header("Content-Type: application/json; charset=utf-8");
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user']['userId'])) {
            echo json_encode(["success" => false, "message" => "Vui lòng đăng nhập để lưu đơn kính"]);
            return;
        }

        $db = Database::connect();
        $userId = $_SESSION['user']['userId'];

        // Lấy dữ liệu từ Form
        $rightEye = json_encode([
            'sph' => $_POST['rightEye_sph'] ?? '0.00',
            'cyl' => $_POST['rightEye_cyl'] ?? '0.00',
            'axis' => $_POST['rightEye_axis'] ?? '0'
        ]);
        $leftEye = json_encode([
            'sph' => $_POST['leftEye_sph'] ?? '0.00',
            'cyl' => $_POST['leftEye_cyl'] ?? '0.00',
            'axis' => $_POST['leftEye_axis'] ?? '0'
        ]);
        $pd = $_POST['pd'] ?? '0';

        try {
            $sql = "INSERT INTO prescription (userId, orderItemId, leftEye, rightEye, leftPD, rightPD) 
                    VALUES (:userId, NULL, :leftEye, :rightEye, :pd, :pd)
                    ON DUPLICATE KEY UPDATE 
                        leftEye = :leftEye, 
                        rightEye = :rightEye, 
                        leftPD = :pd, 
                        rightPD = :pd";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':userId' => $userId,
                ':leftEye' => $leftEye,
                ':rightEye' => $rightEye,
                ':pd' => $pd
            ]);

            echo json_encode(["success" => true, "message" => "Đã lưu đơn kính vào hồ sơ!"]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Lỗi DB: " . $e->getMessage()]);
        }
    }

    public function updateProfile() {
        // Xóa sạch mọi thứ đã xuất ra trước đó để tránh lỗi JSON
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        // Lấy dữ liệu 
        $field = isset($_POST['field']) ? $_POST['field'] : null;
        $value = isset($_POST['value']) ? $_POST['value'] : null;
        $userId = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : null;

        // KIỂM TRA DỮ LIỆU ĐẦU VÀO 
        if (!$field || $value === null || !$userId) {
            echo json_encode([
                'success' => false, 
                'message' => 'Dữ liệu đầu vào bị rỗng',
                'debug' => ['post' => $_POST, 'session_id' => $userId]
            ]);
            return;
        }

        //  Xác định bảng cần update
        $table = '';
        if (in_array($field, ['name', 'email', 'phone'])) {
            $table = 'users';
        } else if ($field === 'address') {
            $table = 'customers';
        } else {
            echo json_encode(['success' => false, 'message' => 'Trường không hợp lệ: ' . $field]);
            return;
        }

        try {
            $db = Database::connect();
            // SQL: Tên cột ($field) phải nối chuỗi, giá trị (:val) thì bind param
            $sql = "UPDATE " . $table . " SET " . $field . " = :val WHERE userId = :uid";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([':val' => $value, ':uid' => $userId]);

            if ($result) {
                // CẬP NHẬT SESSION
                if (!isset($_SESSION['user'])) $_SESSION['user'] = [];
                $_SESSION['user'][$field] = $value;

                echo json_encode([
                    'success' => true,
                    'message' => 'Cập nhật thành công',
                    'data' => $_SESSION['user'] // Trả về session mới để JS kiểm tra
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi thực thi SQL']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
        exit; 
    }

    public function changePassword() {
        header('Content-Type: application/json');
        $oldPass = $_POST['oldPass'] ?? '';
        $newPass = $_POST['newPass'] ?? '';
        $userId = $_SESSION['user']['userId'] ?? null;

        if (!$oldPass || !$newPass || !$userId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
            return;
        }

        try {
            $db = Database::connect();
            //  Lấy mật khẩu cũ trong DB ra để so sánh
            $stmt = $db->prepare("SELECT password FROM users WHERE userId = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            // Kiểm tra mật khẩu cũ 
            if ($user && password_verify($oldPass, $user['password'])) {
                // Mã hóa mật khẩu mới và cập nhật
                $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
                $update = $db->prepare("UPDATE users SET password = ? WHERE userId = ?");
                $update->execute([$hashedPass, $userId]);

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu cũ không chính xác']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
?>