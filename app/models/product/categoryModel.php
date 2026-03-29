<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/product/Category.php";

class CategoryModel extends BaseModel {
    protected $table = "category";

    // Tìm theo tên
    public function findByName($name) {
        $data = $this->findBy("name", $name); 
        return $data ? new Category($data) : null;
    }

    // Tìm theo ID
    public function findCategory($id) {
        $data = $this->find($id, "categoryId");
        return $data ? new Category($data) : null;
    }

    public function getAllCategories() {
        $rows = $this->all();
        $categories = [];
        foreach ($rows as $row) {
            $categories[] = new Category($row);
        }
        return $categories;
    }
}