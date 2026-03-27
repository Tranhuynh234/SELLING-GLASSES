<?php
class User {
    public $userId;
    public $name;
    public $email;
    public $password;
    public $phone;

    public function __construct($data = []) {
        $this->userId = $data['userId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->phone = $data['phone'] ?? null;
    }
}
?>