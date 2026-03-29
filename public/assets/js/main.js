function addToCart(name, price, type) {

let cart = JSON.parse(localStorage.getItem("cart")) || [];

let product = cart.find(p => p.name === name);

if(product){
product.quantity += 1;
}else{
cart.push({
name:name,
price:price,
type:type,
quantity:1
});
}

localStorage.setItem("cart", JSON.stringify(cart));

updateCartCount();

alert("Đã thêm vào giỏ hàng!");

}

function updateCartCount(){

let cart = JSON.parse(localStorage.getItem("cart")) || [];

let count = 0;

cart.forEach(item=>{
count += item.quantity;
});

let badge = document.getElementById("cart-count");

if(badge){
badge.innerText = count;
}

}

updateCartCount();
