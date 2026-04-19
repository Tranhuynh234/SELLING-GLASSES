function updateCartCount(data){
    console.log("[main.js] updateCartCount called with data:", data);
    
    const badge = document.getElementById("cart-count");
    if (!badge) {
        console.log("[main.js] Badge element #cart-count not found in DOM!");
        return;
    }

    let count = 0;
    
    // Nếu nhận được data từ cart.js
    if (data) {
        if (Array.isArray(data)) {
            count = data.reduce((total, item) => total + Number(item.quantity || 0), 0);
        } else if (typeof data === 'number') {
            count = data;
        }
        console.log("[main.js] Count from parameter:", count);
        badge.innerText = count;
        console.log("[main.js] Badge updated to:", count);
        return;
    }
    
    // Nếu không có data, lấy từ server
    console.log("[main.js] No data parameter, fetching from /get-cart");
    fetch("/SELLING-GLASSES/public/get-cart", {
        credentials: "include"
    })
    .then(response => {
        console.log("[main.js] /get-cart response status:", response.status);
        return response.json();
    })
    .then(data => {
        console.log("[main.js] /get-cart data received:", data);
        let count = 0;
        
        if (Array.isArray(data)) {
            count = data.reduce((total, item) => total + Number(item.quantity || 0), 0);
            console.log("[main.js] Data is array, calculated count:", count);
        } else if (data && data.data && Array.isArray(data.data)) {
            count = data.data.reduce((total, item) => total + Number(item.quantity || 0), 0);
            console.log("[main.js] Data wrapped in .data property, calculated count:", count);
        } else {
            console.log("[main.js] Data format unexpected, setting count to 0");
        }
        
        console.log("[main.js] Final count:", count);
        badge.innerText = count;
    })
    .catch(error => {
        console.error("[main.js] Error fetching cart:", error);
        badge.innerText = "0";
    });
}

// Gọi updateCartCount khi trang vừa load
console.log("[main.js] Script loaded, document.readyState:", document.readyState);

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", () => {
        console.log("[main.js] DOMContentLoaded event fired");
        const badgeElement = document.getElementById("cart-count");
        if (badgeElement) {
            console.log("[main.js] cart-count element found, calling updateCartCount()");
            updateCartCount();
        } else {
            console.log("[main.js] cart-count element NOT found after DOMContentLoaded");
        }
    });
} else {
    // DOM đã load rồi, gọi ngay
    console.log("[main.js] DOM already loaded (readyState !== loading), calling updateCartCount immediately");
    const badgeElement = document.getElementById("cart-count");
    if (badgeElement) {
        console.log("[main.js] cart-count element found, calling updateCartCount()");
        updateCartCount();
    } else {
        console.log("[main.js] cart-count element NOT found");
    }
}

