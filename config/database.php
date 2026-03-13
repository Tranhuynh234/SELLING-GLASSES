<?php

$host = "localhost";
$dbname = "selling_glasses";
$username = "root";
$password = "";
$conn = new mysqli($host, $username, $password, $dbname , 3307);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// set charset
$conn->set_charset("utf8");