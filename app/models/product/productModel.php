<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/product/Product.php";

class ProductModel extends BaseModel {
    protected $table = "product";

    // Tìm theo tên sản phẩm
    public function findByName($name) {
        $data = $this->findBy("name", $name);
        return $data ? new Product($data) : null;
    }

    public function findProduct($id) {
        $data = $this->find($id, "productId");
        return $data ? new Product($data) : null;
    }

    public function getAllProducts() {
        $rows = $this->all();
        $products = [];
        foreach ($rows as $row) {
            $products[] = new Product($row);
        }
        return $products;
    }
}
