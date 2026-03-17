<?php

$host = "localhost";
$port = 3307;
$dbname = "selling_glasses";
$username = "root";
$password = "";

// tạo kết nối
$conn = new mysqli($host, $username, $password, $dbname, $port);

// kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// set charset
$conn->set_charset("utf8mb4");