<?php
class User {
    private $userId;
    private $name;
    private $email;
    private $password;
    private $phone;
    private $role;

    public function __construct($data = []) {
        $this->userId = $data['userId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->role = $data['role']??null;
    }

    // Getter 
    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPhone() {
        return $this->phone;
    }
    public function getRole(){
        return $this->role;
    }
    // Setter 
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }
    public function setRole($role) {
        $this->role = $role;
    }
      public function toArray() {
    return [
        "userId" => $this->userId,
        "name" => $this->name,
        "email" => $this->email,
        "phone" => $this->phone,
        "role" => $this->role
    ];
}
}
?>