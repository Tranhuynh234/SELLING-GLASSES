<?php
require_once __DIR__ . "/../core/BaseModel.php";
require_once __DIR__ . "/../entities/Staff.php";

class StaffModel extends BaseModel {

    protected $table = "staff";

    // ===== LẤY 1 STAFF =====
    public function findStaff($id) {
        $data = $this->find($id, "staffId");
        return $data ? new Staff($data) : null;
    }

    // ===== LẤY TẤT CẢ =====
    public function getAllStaff() {
        $rows = $this->all();
        $staffs = [];

        foreach ($rows as $row) {
            $staffs[] = new Staff($row);
        }

        return $staffs;
    }

    // ===== TẠO STAFF =====
    public function createStaff($data) {
        return $this->create($data);
    }

    // ===== UPDATE STAFF =====
    public function updateStaff($id, $data) {
        return $this->update($id, $data, "staffId");
    }

    // ===== TÌM THEO userId =====
    public function findByUserId($userId) {
        $data = $this->findBy("userId", $userId);
        return $data ? new Staff($data) : null;
    }

    // ===== XÓA THEO staffId =====
    public function deleteStaff($id) {
        return $this->delete($id, "staffId");
    }

    // ===== XÓA THEO userId =====
    public function deleteByUserId($userId) {
        return $this->delete($userId, "userId");
    }

    // ===== KIỂM TRA QUYỀN MANAGER THEO userId =====
    public function isManagerByUserId($userId) {
        $sql = "SELECT COUNT(*) as total 
                FROM staff 
                WHERE userId = :userId AND role = 'manager'";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($row['total'] > 0);
    }
}
?>