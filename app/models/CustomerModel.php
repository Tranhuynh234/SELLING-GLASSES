<?php
require_once __DIR__ . "/../core/BaseModel.php";
require_once __DIR__ . "/../entities/Customer.php";

class CustomerModel extends BaseModel {
    protected $table = "customers";
    public function findCustomer($id) {
        $data = $this->find($id, "customerId");
        return $data ? new Customer($data) : null;
    }

    public function getAllCustomers() {
        $rows = $this->all();
        $customers = [];

        foreach ($rows as $row) {
            $customers[] = new Customer($row);
        }

        return $customers;
    }
    public function createCustomer($data) {
        return $this->create($data);
    }
    public function updateCustomer($id, $data) {
        return $this->update($id, $data, "customerId");
    }
    public function findByUserId($userId) {
        $data = $this->findBy("userId", $userId);
        return $data ? new Customer($data) : null;
    }
    public function deleteCustomer($id) {
        return $this->delete($id, "customerId");
    }
}
?>