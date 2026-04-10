<?php
class Product {
    public $productId;
    public $variantId;
    public $name;
    public $price;
    public $stock;
    public $color;
    public $size;
    public $description;
    public $categoryId;
    public $imagePath;
    public $staffId;

    public function __construct($data = []) {
        $this->productId = $data['productId'] ?? null;
        $this->variantId = $data['variantId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->stock = $data['stock'] ?? null;
        $this->color = $data['color'] ?? null;
        $this->size = $data['size'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->categoryId = $data['categoryId'] ?? null;
        $this->imagePath = $data['imagePath'] ?? null;
        $this->staffId = $data['staffId'] ?? null;
    }
}
