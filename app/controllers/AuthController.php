<?php

require_once "../config/database.php";

class AuthController {
    private $conn;
    public function __construct($conn) {
        $this->conn  = $conn;
    }

    // ĐĂNG KÝ
    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $phone = $data['phone'] ?? '';
        $address = $data['address'] ?? '';

        if (!$name || !$email || !$password) {
            echo json_encode([
                "status" => "error",
                "message" => "Vui lòng nhập đủ tên, email, mật khẩu!"
            ]);
            return;
        }

        // Kiểm tra email tồn tại
        $stmt = $this->conn->prepare(
            "SELECT userId FROM users WHERE email=?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Email này đã tồn tại!"
            ]);
            return;
        }

        // Mã hóa mật khẩu và mặc định role là customer
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role = "customer";

        // Chèn vào bảng users
        $stmt = $this->conn->prepare(
            "INSERT INTO users(name, email, password, role, phone)
            VALUES(?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "sssss",
            $name,
            $email,
            $hashedPassword,
            $role,
            $phone
        );
        if(!$stmt->execute()) {
            echo json_encode([
                "status" => "error",
                "message" => "Không thể tạo tài khoản!"
            ]);
            return;
        }
        $userId = $stmt->insert_id;

        // Chèn vào bảng customers
        $stmt2 = $this->conn->prepare(
            "INSERT INTO customers(userId, address)
            VALUES(?, ?)"
        );
        $stmt2->bind_param(
            "is",
            $userId,
            $address
        );
        $stmt2->execute();
        echo json_encode([
            "status" => "success",
            "message" => "Đăng ký thành công!",
            "userId" => $userId
        ]);
    }

    // ĐĂNG NHẬP
    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            echo json_encode ([
                "status" => "error",
                "message" => "Thiếu email hoặc mật khẩu!"
            ]);
            return;
        }

        // Kiểm tra tài khoản tồn tại
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email=?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            echo json_encode ([
                "status" => "error",
                "message" => "Tài khoản không tồn tại!"
            ]);
            return;
        }

        // Xác thực mật khẩu
        $user = $result->fetch_assoc();
        if (!password_verify($password, $user['password'])) {
            echo json_encode ([
                "status" => "error",
                "message" => "Mật khẩu không chính xác!"
            ]);
            return;
        }

        // Phản hồi khi thành công
        echo json_encode ([
            "status" => "success",
            "message" => "Đăng nhập thành công",
            "user" => [
                "userId" => $user['userId'],
                "name" => $user['name'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ]);
    }
}