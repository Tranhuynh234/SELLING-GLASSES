<?php

class UserModel {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getUserByEmail($email){

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("s",$email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
 public function createUser($name,$email,$password,$phone,$role){

    $sql = "INSERT INTO users (name,email,password,role,phone) 
            VALUES (?,?,?,?,?)";

    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param("sssss",$name,$email,$password,$role,$phone);

    // execute và kiểm tra lỗi
    if(!$stmt->execute()){
        echo "SQL Error: " . $stmt->error;
        return false;
    }

    $stmt->close();

    return true;
}
}
?>