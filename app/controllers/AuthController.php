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

            
                 echo json_encode($result);
          exit;

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

        header("Content-Type: application/json");

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->userService->login($email, $password);

        if ($result['success']) {
            $_SESSION['user'] = $result['data'];
            $result['redirect'] = "/home";
        }

        echo json_encode($result);
        exit; 
    }

    // 👉 CHỈ GET mới load view
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
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), // PHPSESSID
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    echo json_encode([
        "success" => true,
        "message" => "Logout success",
        "redirect" => "/login"
    ]);
    exit;
}
//  updateProfile
public function updateProfile() {

    require_once __DIR__ . "/../middleware/AuthMiddleware.php";

    $user = AuthMiddleware::handle(); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header("Content-Type: application/json");
        $userId = $user['userId'];
        $result = $this->userService->updateProfile($userId, $_POST);
        echo json_encode($result);
        exit;
    }

    require_once __DIR__ . "/../views/auth/profile.php";
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

        echo "Xin chào: " . $user['name'];
    }
}
?>