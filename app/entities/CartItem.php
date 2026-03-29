<?php

class CartItem {
    private $cartItemId;
    private $name;
    private $price;
    private $quantity;

    public function __construct($cartItemId, $name, $price, $quantity) {
        $this->cartItemId = $cartItemId;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    // getter
    public function getCartItemId() { return $this->cartItemId; }
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getQuantity() { return $this->quantity; }
}
