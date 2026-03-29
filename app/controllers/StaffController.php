<?php
require_once __DIR__ . "/../services/StaffServices.php";
require_once __DIR__ . "/../services/UserServices.php";
require_once __DIR__ . "/../middleware/AuthMiddleware.php";

class StaffController {

    private $staffService;
    private $userService;

    public function __construct() {
        $this->staffService = new StaffService();
        $this->userService = new UserService();
    }

    // =========================
    // SAVE STAFF (email → userId)
    // =========================
    public function save() {

        $currentUser = AuthMiddleware::handle(['staff']);

        header("Content-Type: application/json");

        $email = $_POST['email'] ?? null;
        $position = $_POST['position'] ?? null;

        if (!$email || !$position) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu email hoặc position"
            ]);
            exit;
        }

        $user = $this->userService->getUserByEmail($email);

        if (!$user) {
            echo json_encode([
                "success" => false,
                "message" => "Không tìm thấy user"
            ]);
            exit;
        }

        $userId = $user->getUserId();

        try {
            $this->staffService->createOrUpdateStaff($userId, $position);

            echo json_encode([
                "success" => true,
                "message" => "Save staff thành công"
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }

        exit;
    }

    // =========================
    // DELETE STAFF
    // =========================
    public function delete() {

        AuthMiddleware::handle(['staff']);

        header("Content-Type: application/json");

        $email = $_POST['email'] ?? null;

        if (!$email) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu email"
            ]);
            exit;
        }

        $user = $this->userService->getUserByEmail($email);

        if (!$user) {
            echo json_encode([
                "success" => false,
                "message" => "Không tìm thấy user"
            ]);
            exit;
        }

        $this->staffService->deleteUser($user->getUserId());

        echo json_encode([
            "success" => true,
            "message" => "Xóa user thành công"
        ]);

        exit;
    }
}