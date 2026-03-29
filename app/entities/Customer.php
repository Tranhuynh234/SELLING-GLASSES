<?php
class  Customer{
    private $customerId;
    private $userId;
    private $address;
   public function __construct($data = []) {
     $this->customerId = $data['customerId'] ?? null;
        $this->userId = $data['userId'] ?? null; 
        $this->address = $data['address'] ?? null;
    }
    // === getter==== 
    public function getCustomerId(){
        return $this->customerId;
    }
     public function getUserId(){
        return $this->userId;
    }
     public function getAddress(){
        return $this->address;
    }
    //=====setter============
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }
     public function setUserId($userId)
    {
        $this->userId = $userId;
    }
      public function setAddress($address)
    {
        $this->address = $address;
    }
}
?>