//document.addEventListener("DOMContentLoaded", () => {
//   let buttons = document.querySelectorAll("button");
//    buttons.forEach((btn, index) => {
 //       if (btn.innerText.includes("Thêm vào giỏ")) {
   //         // gán tạm variantId theo index
     //       btn.onclick = () => addToCart(index + 1);
       // }
    //});

//});
document.addEventListener("DOMContentLoaded", function () {
    loadCart();
});


async function loadCart() {
    try {
        // Sửa đường dẫn từ /cart/getCart thành index.php?url=get-cart
        const response = await fetch('index.php?url=get-cart'); 
        const data = await response.json();
        
        if (data && !data.error) {
            updateCartCount(data);
        }
    } catch (error) {
        console.error("Lỗi loadCart:", error);
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



window.addToCart = async function(productId) {
    let formData = new FormData();
    formData.append("variantId", productId);
    formData.append("quantity", 1);

    try {
        // Sửa đường dẫn thành index.php?url=add-to-cart
        const response = await fetch('index.php?url=add-to-cart', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        console.log("Dữ liệu sau khi thêm:", data);

        if (data.error) {
            alert("Vui lòng đăng nhập để thêm vào giỏ hàng!");
            return;
        }

        alert("Đã thêm vào giỏ hàng thành công!");
        
        // Sau khi thêm xong, gọi hàm cập nhật lại con số trên Header
        updateCartCount(data); 

    } catch (error) {
        console.error("Lỗi khi thêm vào giỏ:", error);
    }
};

// --- CẬP NHẬT SÓ LƯỢNG SẢN PHẨM TRONG GIỎ HÀNG ---
function updateCartCount(data) {
    const badge = document.getElementById("cart-count");
    if (!badge) return;

    if (Array.isArray(data) && data.length > 0) {
        // Chỉ cần dùng reduce để tính tổng quantity của tất cả các dòng
        const totalQty = data.reduce((total, item) => total + parseInt(item.quantity), 0);
        badge.innerText = totalQty;
    } else {
        badge.innerText = 0;
    }
}