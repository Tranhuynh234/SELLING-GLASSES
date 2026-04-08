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
   public function getProducts($limit, $offset) {
    $sql = "SELECT * FROM product LIMIT $limit OFFSET $offset";
    $rows = $this->queryAll($sql); // Lấy mảng dữ liệu thô
    $products = [];
    foreach ($rows as $row) {
        $products[] = new Product($row); // Chuyển từng mảng dữ liệu thành đối tượng Product
    }
    return $products;   
}

   public function countProducts() {
    $sql = "SELECT COUNT(*) AS total FROM product";
    $result = $this->queryOne($sql);
    return $result['total'];
}
}