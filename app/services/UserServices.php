<?php
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../entities/User.php";
require_once __DIR__ . "/../models/CustomerModel.php";
require_once __DIR__ . "/../models/StaffModel.php";
class UserService {
    private $userModel;
    private $customerModel;
    private $staffModel;
    public function __construct() {
        $this->userModel = new UserModel();
        $this->customerModel = new CustomerModel();
        $this->staffModel = new StaffModel();
    }

    // =========================
    // REGISTER
    // =========================
public function register($data) {
    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($data['name'])) {
    return $this->response(false, "Vui lòng nhập tên");
}
    if (empty($email) || empty($password)) {
        return $this->response(false, "Email and password are required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $this->response(false, "Email không hợp lệ");
    }
    if (strlen($password) < 6) {
    return $this->response(false, "Mật khẩu phải >= 6 ký tự");
}

    // check email tồn tại
    $existingUser = $this->userModel->findByEmail($email);
    if ($existingUser) {
        return $this->response(false, "Email already exists");
    }

    try {

        // BẮT ĐẦU TRANSACTION
        $this->userModel->beginTransaction();

        // 1. INSERT USER
        $userId = $this->userModel->create([
            "name" => $data['name'] ?? null,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_BCRYPT),
            "phone" => $data['phone'] ?? null,
            "role" => "customer"
        ]);
           if ($userId === false) {
            throw new Exception("Insert user failed");
        }

        // 2. INSERT CUSTOMER
        $customer = $this->customerModel->createCustomer([
            "userId" => $userId,
            "address" => $data['address'] ?? null
        ]);
             if (!$customer) {
            throw new Exception("Insert customer failed");
        }


        //  COMMIT nếu tất cả OK
        $this->userModel->commit();

        return $this->response(true, "Register success", [
            "userId" => $userId
        ]);

    } catch (Exception $e) {

        //
        $this->userModel->rollBack();

        return $this->response(false, "Register failed: " . $e->getMessage());
    }
}

    // =========================
    // LOGIN
    // =========================
    public function login($email, $password) {
          $email = trim($email ?? '');
    $password = trim($password ?? '');


   if (!$email) {
    return $this->response(false, "Vui lòng nhập email");
}

if (!$password) {
    return $this->response(false, "Vui lòng nhập mật khẩu");
} 
        if (strpos($email, ' ') !== false) {
     return $this->response(false, "Email không được chứa khoảng trắng");
}
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $this->response(false, "Email không hợp lệ");
    }
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return $this->response(false, "User not found");
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->response(false, "Wrong password");
        }

        return $this->response(true, "Login success", [
    "userId" => $user->getUserId(),
    "name" => $user->getName(),
    "email" => $user->getEmail(),
    "role" => $user->getRole(),
]);
    }

    // =========================
    // GET USER
    // =========================
    public function getUserById($id) {
        return $this->userModel->findUser($id);
    }

    // =========================
    // UPDATE USER 
    // =========================
    public function updateUser($id, $data) {
        // nếu có password thì hash lại
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->userModel->update($id, $data, "userId");
    }

    // =========================
    // DELETE USER
    // ========================
   public function deleteUser($id) {

    try {
        $this->userModel->beginTransaction();

        // 1. xóa bảng con trước
        $this->customerModel->deleteByUserId($id);
        $this->staffModel->deleteByUserId($id);

        // 2. xóa user
        $result = $this->userModel->delete($id, "userId");

        $this->userModel->commit();
return [
    "success" => $result > 0,
    "message" => $result > 0 ? "Xóa user thành công" : "Không tìm thấy user để xóa"
];

    } catch (Exception $e) {

        $this->userModel->rollBack();

        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}
    public function updateProfile($userId, $data) {
        try {
            // BẮT ĐẦU TRANSACTION
            $this->userModel->beginTransaction();

            // 1. Update bảng USER
            $updateUserData = [];
            if (isset($data['name'])) {
                $updateUserData['name'] = $data['name'];
            }
            if (isset($data['email'])) {
                $updateUserData['email'] = $data['email'];
            }
            if (isset($data['phone'])) {
                $updateUserData['phone'] = $data['phone'];
            }

            if (!empty($updateUserData)) {
                $this->userModel->update($userId, $updateUserData, "userId");
            }

            // 2. Update bảng CUSTOMER (Riêng cho address)
            if (isset($data['address'])) {
                $customer = $this->customerModel->findByUserId($userId);

                if ($customer) {
                    $this->customerModel->updateCustomer(
                        $customer->getCustomerId(),
                        ["address" => $data['address']]
                    );
                } else {
                    // Chưa có data ở bảng customer -> tạo mới
                    $this->customerModel->createCustomer([
                        "userId" => $userId,
                        "address" => $data['address']
                    ]);
                }
            }

            $this->userModel->commit();

            return $this->response(true, "Cập nhật hồ sơ thành công", [
                "userId" => $userId,
                "name" => $data['name'] ?? null,
                "email" => $data['email'] ?? null,
                "phone" => $data['phone'] ?? null,
                "address" => $data['address'] ?? null
            ]);

        } catch (Exception $e) {
            // CÓ LỖI THÌ ROLLBACK
            $this->userModel->rollBack();
            return $this->response(false, "Cập nhật thất bại: " . $e->getMessage());
        }
    }
public function getUserByEmail($email) {
    return $this->userModel->findByEmail($email);
}

    // =========================
    // RESPONSE CHUẨN return $this->response(true, "Register success", [
   // "id" => 1,
   // "name" => "An"
    //  ]);
    // =========================
    private function response($success, $message, $data = null) {
        return [
            "success" => $success,
            "message" => $message,
            "data" => $data
        ];
    }
}
?>
