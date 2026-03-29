<?php
class Database {
    private static $host = "localhost";
    private static $port = "3306";
    private static $dbname = "selling_glasses";
    private static $username = "root";
    private static $password = "";

    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$username,
                    self::$password
                );

                // bật lỗi exception
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>