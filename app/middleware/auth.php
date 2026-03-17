<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth() {
    if (!isset($_SESSION['userId'])) {
        header("Location: /SELLING-GLASSES/public/login.html");
        exit();
    }
}