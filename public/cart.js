function loadCart(){

let cart = JSON.parse(localStorage.getItem("cart")) || [];

let cartTable = document.getElementById("cart-items");

let totalPrice = 0;

cartTable.innerHTML = "";

cart.forEach((item,index)=>{

let row = `
<tr>

<td>${item.name}</td>

<td>${item.price}đ</td>

<td>

<button onclick="decreaseQuantity(${index})">-</button>

${item.quantity}

<button onclick="increaseQuantity(${index})">+</button>

</td>

<td>${item.price * item.quantity}đ</td>

<td>
<button onclick="removeItem(${index})">Xóa</button>
</td>

</tr>
`;

cartTable.innerHTML += row;

totalPrice += item.price * item.quantity;

});

document.getElementById("total-price").innerText = totalPrice + "đ";

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