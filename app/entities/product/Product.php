<?php
class Product {
    public $productId;
    public $name;
    public $price; // THÊM DÒNG NÀY
    public $description;
    public $categoryId;
    public $imagePath;
    public $staffId;

    public function __construct($data = []) {
        $this->productId = $data['productId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->price = $data['price'] ?? null; // THÊM DÒNG NÀY
        $this->description = $data['description'] ?? null;
        $this->categoryId = $data['categoryId'] ?? null;
        $this->imagePath = $data['imagePath'] ?? null; // Cột trong DB phải tên là imagePath mới ăn nha
        $this->staffId = $data['staffId'] ?? null;
    }
}
