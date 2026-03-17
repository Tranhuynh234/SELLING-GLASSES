<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkRole($roles = []) {

    // chưa login thì chặn luôn
    if (!isset($_SESSION['role'])) {
        header("Location: /SELLING-GLASSES/public/login.html");
        exit();
    }

    // không thuộc role cho phép
    if (!in_array($_SESSION['role'], $roles)) {
        echo "Access denied";
        exit();
    }
}