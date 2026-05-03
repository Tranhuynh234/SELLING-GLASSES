<?php
class ProductVariant {
    public $variantId;
    public $color;
    public $size;
    public $price;
    public $stock;
    public $productId;
    public $original_price;

    public function __construct($data = []) {
        $this->variantId = $data['variantId'] ?? null;
        $this->color = $data['color'] ?? null;
        $this->size = $data['size'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->stock = $data['stock'] ?? null;
        $this->productId = $data['productId'] ?? null;
        $this->original_price = $data['original_price'] ?? null;
    }
}