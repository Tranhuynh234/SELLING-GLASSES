<?php
class Staff {
    private $staffId;
    private $userId;
    private $position;

    public function __construct($data = []) {
        $this->staffId = $data['staffId'] ?? null;
        $this->userId = $data['userId'] ?? null;
        $this->position = $data['position'] ?? null;
    }

    // Getter 
    public function getStaffId() {
        return $this->staffId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getPosition() {
        return $this->position;
    }

    // Setter 
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setPosition($position) {
        $this->position = $position;
    }
}
?>