<?php
class CartItem {
    public $cartItemId;
    public $name;
    public $price;
    public $quantity;

    public function __construct($id, $name, $price, $quantity) {
        $this->cartItemId = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}
?>
