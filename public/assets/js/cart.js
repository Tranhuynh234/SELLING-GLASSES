document.addEventListener("DOMContentLoaded", () => {

    let buttons = document.querySelectorAll("button");

    buttons.forEach((btn, index) => {

        if (btn.innerText.includes("Thêm vào giỏ")) {

            // gán tạm variantId theo index
            btn.onclick = () => addToCart(index + 1);
        }
    });

});
document.addEventListener("DOMContentLoaded", function () {
    loadCart();
});


async function loadCart() {

    try {
        let res = await fetch("/SELLING-GLASSES/app/controllers/cartController.php?action=getCart", {
            credentials: "include" 
        });

        let data = await res.json();

        console.log("DATA:", data); 

        let cartTable = document.getElementById("cart-items");
        if (!cartTable) return;

        let subtotal = 0;
        cartTable.innerHTML = "";

        data.forEach(item => {

            let total = item.price * item.quantity;
            subtotal += total;

            let row = `
            <tr>
                <td>${item.name}</td>
                <td>Kính</td>
                <td>${Number(item.price).toLocaleString()}đ</td>

                <td>
                    <button onclick="decreaseQuantity(${item.cartItemId}, ${item.quantity})">-</button>
                    ${item.quantity}
                    <button onclick="increaseQuantity(${item.cartItemId}, ${item.quantity})">+</button>
                </td>

                <td>${total.toLocaleString()}đ</td>

                <td>
                    <button onclick="removeItem(${item.cartItemId})">Xóa</button>
                </td>
            </tr>
            `;

            cartTable.innerHTML += row;
        });

        let subEl = document.getElementById("subtotal");
        let totalEl = document.getElementById("total");

        if (subEl) subEl.innerText = subtotal.toLocaleString() + "đ";

        let tax = subtotal * 0.1;
        let totalPrice = subtotal + tax + 30000;

        if (totalEl) totalEl.innerText = totalPrice.toLocaleString() + "đ";

    } catch (err) {
        console.error("Lỗi loadCart:", err);
    }
}


async function increaseQuantity(id, currentQty) {

    let formData = new FormData();
    formData.append("cartItemId", id);
    formData.append("quantity", currentQty + 1);

    await fetch("/SELLING-GLASSES/app/controllers/cartController.php?action=update", {
        method: "POST",
        body: formData,
        credentials: "include"
    });

    loadCart();
}

async function decreaseQuantity(id, currentQty) {

    if (currentQty <= 1) return;

    let formData = new FormData();
    formData.append("cartItemId", id);
    formData.append("quantity", currentQty - 1);

    await fetch("/SELLING-GLASSES/app/controllers/cartController.php?action=update", {
        method: "POST",
        body: formData,
        credentials: "include"
    });

    loadCart();
}



async function removeItem(id) {

    let formData = new FormData();
    formData.append("cartItemId", id);

    await fetch("/SELLING-GLASSES/app/controllers/cartController.php?action=remove", {
        method: "POST",
        body: formData,
        credentials: "include"
    });

    loadCart();
}



window.addToCart = async function(variantId) {

    let formData = new FormData();
    formData.append("variantId", variantId);
    formData.append("quantity", 1);

    await fetch("/SELLING-GLASSES/app/controllers/cartController.php?action=add", {
        method: "POST",
        body: formData,
        credentials: "include"
    });

    alert("Đã thêm vào giỏ hàng");

  
    loadCart();
};
