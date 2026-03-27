<?php
require_once dirname(__DIR__) . "/services/UserServices.php";
class AuthController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    // =========================
    // REGISTER
    // =========================
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // thông báo trả về json
            header("Content-Type: application/json");
            $result = $this->userService->register($_POST);

            if ($result['success']) {
                 echo json_encode([
                "success" => true,
                "message" => $result['message']
            ]);
            } else {
               echo json_encode([
                "success" => false,
                "message" => $result['message']
            ]);
            }

        } else {
            // load view
            require_once __DIR__ . "/../views/auth/register.php";
        }
    }

    // =========================
    // LOGIN
    // =========================
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // thông báo trả về json 
             header("Content-Type: application/json");
            $result = $this->userService->login(
                $_POST['email'],    
                $_POST['password']
            );

            if ($result['success']) {
                // lưu session
                $_SESSION['user'] = $result['data'];

                 echo json_encode([
                    "success" => true,
                    "message" => "Login success",
                    "redirect" => "/home"
                    ]);
            } else {
                  echo json_encode([
                    "success" => false,
                    "message" => $result['message']
                ]);
             
            }
            exit;
        } 
            require_once __DIR__ . "/../views/auth/login.php";
        
    }

    // =========================
    // LOGOUT
    // =========================
  public function logout() {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();

    header("Content-Type: application/json");

    echo json_encode([
        "success" => true,
        "message" => "Logout success",
        "redirect" => "/login"
    ]);
    exit;
}

    // =========================
    // PROFILE (test session)
    // =========================
    public function profile() {
          if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

        if (!isset($_SESSION['user'])) {
            echo "Bạn chưa đăng nhập";
            return;
        }

        $user = $_SESSION['user'];

        echo "Xin chào: " . $user->name;
    }
}
?>