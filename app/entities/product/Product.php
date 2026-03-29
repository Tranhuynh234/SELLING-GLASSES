<?php
class Product {
    public $productId;
    public $name;
    public $description;
    public $categoryId;
    public $imagePath;
    public $staffId;

    public function __construct($data = []) {
        $this->productId = $data['productId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->categoryId = $data['categoryId'] ?? null;
        $this->imagePath = $data['imagePath'] ?? null;
        $this->staffId = $data['staffId'] ?? null;
    }
}
