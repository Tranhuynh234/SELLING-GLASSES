<?php
class Category {
    public $categoryId;
    public $name;

    public function __construct($data = []) {
        $this->categoryId = $data['categoryId'] ?? null;
        $this->name = $data['name'] ?? null;
    }
}