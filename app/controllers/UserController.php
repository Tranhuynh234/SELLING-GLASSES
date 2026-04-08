<?php
require_once dirname(__DIR__) . "/services/UserServices.php";
class UserController  {
    private $userService;
      public function __construct() {
        $this->userService = new UserService();
    }

   public function setUserService($userService) {
        $this->userService = $userService;
    }
    public function getUserService() {
        return $this->userService;
    }
     public function getAllUsers() {
        
        header("Content-Type: application/json");

        $result = $this->userService->getAllUsers();

        echo json_encode($result);
    }
    // tìm kiếm user 
  public function searchUsers() {
    header("Content-Type: application/json");

    $keyword = $_GET['keyword'] ?? '';

    echo json_encode(
        $this->userService->searchUsers($keyword)
    );
}
    // update users
   public function updateUser() {
    header("Content-Type: application/json; charset=utf-8");

    $id = $_POST['userId'] ?? null;

    if (!$id) {
        echo json_encode([
            "success" => false,
            "message" => "Thiếu userId"
        ]);
        return;
    }

    $data = $_POST;
    unset($data['userId']); //

    $result = $this->userService->updateUser($id, $data);

    echo json_encode($result);
}
    public function deleteUser() {
    header("Content-Type: application/json");

    $id = $_POST['userId'] ?? null;

    if (!$id) {
        echo json_encode([
            "success" => false,
            "message" => "Thiếu userId"
        ]);
        return;
    }

    $result = $this->userService->deleteUser($id);

    echo json_encode($result);
}
public function createUser() {
    header("Content-Type: application/json");

    $data = $_POST;

    $result = $this->userService->createUserByAdmin($data);

    echo json_encode($result);
}
}
?>