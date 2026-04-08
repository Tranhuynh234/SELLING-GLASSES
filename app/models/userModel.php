<?php
require_once __DIR__ . "/../core/BaseModel.php";
require_once __DIR__ . "/../entities/User.php";

class UserModel extends BaseModel {
    protected $table = "users";

    // override để trả về entity
    public function findByEmail($email) {
        $data = $this->findBy("email", $email);
        return $data ? new User($data) : null;
    }

    public function findUser($id) {
        $data = $this->find($id, "userId");
        return $data ? new User($data) : null;
    }

    public function getAllUsers() {
        $rows = $this->all();
        $users = [];

        foreach ($rows as $row) {
            $users[] = new User($row);
        }

        return $users;
    }
     public function searchUsers($keyword) {
    $sql = "SELECT * FROM users 
            WHERE name LIKE :kw 
            OR email LIKE :kw 
            OR phone LIKE :kw";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        ":kw" => "%" . $keyword . "%"
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>