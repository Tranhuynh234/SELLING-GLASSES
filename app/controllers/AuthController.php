<?php
require_once __DIR__ . "/../../config/db_connect.php";
require_once __DIR__ . "/../models/UserModel.php";

class AuthController {

    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    // LOGIN
    public function login() {

        session_start();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['userId'] = $user['userId'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // trả về URL để JS redirect
            switch ($user['role']) {

                case 'manager':
                    echo "/SELLING-GLASSES/app/views/admin/dashboard.php";
                    break;

                case 'staff':
                    echo "/SELLING-GLASSES/app/views/staff/dashboard.php";
                    break;

                case 'sales':
                    echo "/SELLING-GLASSES/app/views/sales/dashboard.php";
                    break;

                case 'customer':
                    echo "/SELLING-GLASSES/public/index.php";
                    break;

                default:
                    echo "Role not recognized";
            }

            exit();

        } else {
            echo "Invalid email or password";
            exit();
        }
    }

    // REGISTER
    public function register(){

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if(empty($name) || empty($email) || empty($phone) || empty($password)){
        echo "Please fill all fields";
        exit();
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "Invalid email";
    exit();
    }

    $existingUser = $this->userModel->getUserByEmail($email);

    if($existingUser){
        echo "Email already exists";
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $role = "customer";

    $created = $this->userModel->createUser($name,$email,$passwordHash,$phone,$role);

    if($created){
        echo "/SELLING-GLASSES/app/views/auth/auth.html";
    }else{
        echo "Register failed";
    }
}

    // LOGOUT
    public function logout() {

        session_start();
          session_unset();
        session_destroy();
        // Xóa cookie session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }


        echo "/SELLING-GLASSES/public/index.php";
        exit();
    }
    public function check() {
    session_start();

    if (isset($_SESSION['userId'])) {
        echo "logged_in";
    } else {
        echo "not_logged_in";
    }
}
}