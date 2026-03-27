<?php
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../entities/User.php";

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // =========================
    // REGISTER
    // =========================
    public function register($data) {
        // kiểm tra data
        if (empty($data['email']) || empty($data['password'])) {
            return $this->response(false, "Email and password are required");
        }

        // check email tồn tại
        $existingUser = $this->userModel->findByEmail($data['email']);
        if ($existingUser) {
            return $this->response(false, "Email already exists");
        }

        // hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // insert DB
       $result = $this->userModel->create([
    "name" => $data['name'],
    "email" => $data['email'],
    "password" => $data['password'],
    "phone" => $data['phone'] ?? null
]);

        if (!$result) {
            return $this->response(false, "Register failed");
        }

        return $this->response(true, "Register success");
    }

    // =========================
    // LOGIN
    // =========================
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return $this->response(false, "Email and password are required");
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return $this->response(false, "User not found");
        }

        if (!password_verify($password, $user->password)) {
            return $this->response(false, "Wrong password");
        }

        return $this->response(true, "Login success", $user);
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
        return $this->userModel->delete($id, "userId");
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