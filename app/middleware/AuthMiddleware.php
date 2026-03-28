<?php

class AuthMiddleware {

    public static function handle($roles = []) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // chưa login
        if (!isset($_SESSION['user'])) {
            self::jsonResponse(401, "Unauthorized", [
                "redirect" => "/SELLING-GLASSES/public/login"
            ]);
        }

        $user = $_SESSION['user'];

        // ✔ chỉ cần login
        if (empty($roles)) {
            return $user;
        }

        // không có quyền
        if (!in_array($user['role'], $roles)) {
            self::jsonResponse(403, "Access denied");
        }

        return $user;
    }

    //  trả JSON
    private static function jsonResponse($status, $message, $extra = []) {
        http_response_code($status);
        header("Content-Type: application/json");

        echo json_encode(array_merge([
            "success" => false,
            "message" => $message
        ], $extra));

        exit();
    }
}