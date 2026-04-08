<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $field = $_POST['field']; // name, email, phone, hoặc address
    $value = $_POST['value'];

    $db = Database::connect();

    try {
        if ($field === 'address') {
            // Cập nhật địa chỉ vào bảng customers
            $sql = "INSERT INTO customers (userId, address) VALUES (:userId, :val) 
                    ON DUPLICATE KEY UPDATE address = :val";
        } else {
            // Cập nhật thông tin vào bảng users
            $allowedFields = ['name', 'email', 'phone'];
            if (!in_array($field, $allowedFields)) die("Field không hợp lệ");
            
            $sql = "UPDATE users SET $field = :val WHERE userId = :userId";
        }

        $stmt = $db->prepare($sql);
        $stmt->execute([':val' => $value, ':userId' => $userId]);
        echo "Thành công";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Lỗi: " . $e->getMessage();
    }
}