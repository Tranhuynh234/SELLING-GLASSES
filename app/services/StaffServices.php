<?php
require_once __DIR__ . "/../models/StaffModel.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/CustomerModel.php";
class StaffService {

    private $staffModel;
    private $userModel;
    private $customerModel;

    public function __construct() {
        $this->staffModel = new StaffModel();
        $this->userModel = new UserModel();
        $this->customerModel = new CustomerModel;
    }

    // =========================
    // CREATE OR UPDATE STAFF
    // =========================
    public function createOrUpdateStaff($userId, $position) {

        $validPositions = ['sales', 'operation', 'leader', 'manager'];

        if (!in_array($position, $validPositions)) {
            throw new Exception("Position không hợp lệ");
        }
     
        $this->userModel->update($userId, [
             "role" => "staff"
            ]);
        $existing = $this->staffModel->findByUserId($userId);
        

        if (!$existing) {
            $this->staffModel->createStaff([
                "userId" => $userId,
                "position" => $position
            ]);
            $this->customerModel->deleteByUserId($userId);
        } else {
            $this->staffModel->updateStaff(
                $existing->getStaffId(),
                ["position" => $position]
            );
           
        }
    }

    // =========================
    // DELETE STAFF
    // =========================
 public function deleteUser($userId)
{
    // 1. lấy user
    $user = $this->userModel->findUser($userId);

    if (!$user) {
        return false;
    }

    // 2. nếu là staff thì xóa staff trước
    if ($user->getRole() === "staff") {
        $this->staffModel->deleteByUserId($userId);
    }else {
        $this->customerModel->deleteByUserId($userId);
    }

    // 3. xóa user
    return $this->userModel->delete($userId, "userId");
}
}