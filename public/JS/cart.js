function loadCart(){

let cart = JSON.parse(localStorage.getItem("cart")) || [];

let cartTable = document.getElementById("cart-items");

let subtotal = 0;

cartTable.innerHTML = "";

cart.forEach((item,index)=>{

let total = item.price * item.quantity;

let row = `
<tr>

<td>${item.name}</td>

<td>Kính</td>

<td>${item.power ? item.power : "Chưa chọn"}</td>

<td>${item.price}đ</td>

<td>

<button onclick="decreaseQuantity(${index})">-</button>

${item.quantity}

<button onclick="increaseQuantity(${index})">+</button>

</td>

<td>${total}đ</td>

<td>
<button onclick="removeItem(${index})">Xóa</button>
</td>

</tr>
`;

cartTable.innerHTML += row;

subtotal += total;

});

document.getElementById("subtotal").innerText = subtotal + "đ";

let tax = subtotal * 0.1;

let totalPrice = subtotal + tax + 30000;

document.getElementById("total").innerText = totalPrice + "đ";

}

function increaseQuantity(index){

let cart = JSON.parse(localStorage.getItem("cart"));

cart[index].quantity++;

localStorage.setItem("cart",JSON.stringify(cart));

loadCart();

}

function decreaseQuantity(index){

let cart = JSON.parse(localStorage.getItem("cart"));

if(cart[index].quantity > 1){
cart[index].quantity--;
}

localStorage.setItem("cart",JSON.stringify(cart));

loadCart();

}

function removeItem(index){

let cart = JSON.parse(localStorage.getItem("cart"));

cart.splice(index,1);

localStorage.setItem("cart",JSON.stringify(cart));

loadCart();

}

loadCart();
function addToCart(name, price, powerId){

let power = document.getElementById(powerId).value;

let cart = JSON.parse(localStorage.getItem("cart")) || [];

let product = {
name: name,
price: price,
power: power,
quantity: 1
};

cart.push(product);

localStorage.setItem("cart", JSON.stringify(cart));

}
