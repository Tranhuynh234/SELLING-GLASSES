<?php

class AuthMiddleware {
public static function handle($roles = [], $positions = []) {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        self::jsonResponse(401, "Unauthorized", [
            "redirect" => "/SELLING-GLASSES/public/auth"
        ]);
    }

    $user = $_SESSION['user'];

    // ✔ chỉ cần login
    if (empty($roles) && empty($positions)) {
        return $user;
    }

    // check role
    if (!empty($roles) && !in_array($user['role'], $roles)) {
        self::jsonResponse(403, "Không có quyền (role)");
    }

    // check position
    if (!empty($positions)) {
        $userPosition = $user['position'] ?? null;
        if (!in_array($userPosition, $positions)) {
            self::jsonResponse(403, "Không có quyền - position (yêu cầu: " . implode(", ", $positions) . ", user: " . $userPosition . ")");
        }
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