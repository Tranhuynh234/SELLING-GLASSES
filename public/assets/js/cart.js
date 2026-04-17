const CART_BASE = "/SELLING-GLASSES/public";
let cartItems = [];
let selectedItems = new Set();

document.addEventListener("DOMContentLoaded", () => {
    bindCartEvents();
    loadCart();
});

function bindCartEvents() {
    const selectAll = document.getElementById("select-all");
    const removeSelectedButton = document.getElementById("remove-selected");
    const checkoutButton = document.getElementById("go-to-checkout");

    if (selectAll) {
        selectAll.addEventListener("change", () => {
            if (selectAll.checked) {
                selectedItems = new Set(cartItems.map((item) => String(item.cartItemId)));
            } else {
                selectedItems.clear();
            }

            sessionStorage.setItem(
                "selectedCartItems",
                JSON.stringify(Array.from(selectedItems))
            );

            renderCart();
        });
    }

    if (removeSelectedButton) {
        removeSelectedButton.addEventListener("click", removeSelectedItems);
    }

    if (checkoutButton) {
        checkoutButton.addEventListener("click", goToCheckout);
    }
}

async function loadCart() {
    try {
        const response = await fetch(`${CART_BASE}/get-cart`, {
            credentials: "include"
        });
        const data = await response.json();

        if (data && data.error) {
            cartItems = [];
            selectedItems.clear();
            renderCart(data.error === "Not logged in" ? "login" : "empty");
            updateCartCount([]);
            return;
        }

        const saved = JSON.parse(sessionStorage.getItem("selectedCartItems")) || [];

        cartItems = Array.isArray(data) ? data : [];

        selectedItems = new Set(saved);

        renderCart();
        updateCartCount(cartItems);
    } catch (error) {
        console.error("Lỗi loadCart:", error);
        cartItems = [];
        selectedItems.clear();
        renderCart("error");
        updateCartCount([]);
    }
}

async function changeQuantity(cartItemId, nextQuantity) {
    if (nextQuantity <= 0) {
        return;
    }

    const formData = new FormData();
    formData.append("cartItemId", cartItemId);
    formData.append("quantity", nextQuantity);

    await fetch(`${CART_BASE}/update-cart`, {
        method: "POST",
        body: formData,
        credentials: "include"
    });

    await loadCart();
}

async function removeItem(cartItemId) {
    const formData = new FormData();
    formData.append("cartItemId", cartItemId);

    await fetch(`${CART_BASE}/remove-cart-item`, {
        method: "POST",
        body: formData,
        credentials: "include"
    });

    selectedItems.delete(String(cartItemId));
    await loadCart();
}

async function removeSelectedItems() {
    if (!selectedItems.size) {
        alert("Hãy chọn ít nhất một sản phẩm để xóa.");
        return;
    }

    const ids = Array.from(selectedItems);
    for (const cartItemId of ids) {
        await removeItem(cartItemId);
    }
}

window.addToCart = async function (variantId) {
    const formData = new FormData();
    formData.append("variantId", variantId);
    formData.append("quantity", 1);

    try {
        const response = await fetch(`${CART_BASE}/add-to-cart`, {
            method: "POST",
            body: formData,
            credentials: "include"
        });

        // CHECK HTTP STATUS TRƯỚC
        if (!response.ok) {
            const text = await response.text();
            console.error("Server error:", text);
            alert("Lỗi server khi thêm vào giỏ hàng!");
            return;
        }

        const data = await response.json();

        if (data.success) {
            updateCartCount(data.data);
            alert("Đã thêm vào giỏ hàng thành công!");
        } else {
            alert("Lỗi: " + (data.message || "Unknown error"));
        }

    } catch (error) {
        console.error("Lỗi khi thêm vào giỏ:", error);
        alert("Không thể thêm vào giỏ hàng!");
    }
};

function renderCart(state = "ready") {
    const itemsContainer = document.getElementById("cart-items");
    const selectAll = document.getElementById("select-all");
    const removeSelectedButton = document.getElementById("remove-selected");

    if (!itemsContainer) {
        return;
    }

    if (state !== "ready") {
        itemsContainer.innerHTML = renderState(state);
        syncSummary([]);
        if (selectAll) {
            selectAll.checked = false;
            selectAll.disabled = true;
        }
        if (removeSelectedButton) {
            removeSelectedButton.disabled = true;
        }
        return;
    }

    if (!cartItems.length) {
        itemsContainer.innerHTML = renderState("empty");
        syncSummary([]);
        if (selectAll) {
            selectAll.checked = false;
            selectAll.disabled = true;
        }
        if (removeSelectedButton) {
            removeSelectedButton.disabled = true;
        }
        return;
    }

    if (selectAll) {
        selectAll.disabled = false;
        selectAll.checked = cartItems.every((item) => selectedItems.has(String(item.cartItemId)));
    }
    if (removeSelectedButton) {
        removeSelectedButton.disabled = !selectedItems.size;
    }

    itemsContainer.innerHTML = cartItems.map(renderRow).join("");
    bindRowEvents();
    syncSummary(cartItems.filter((item) => selectedItems.has(String(item.cartItemId))));
}

function renderRow(item) {
    const cartItemId = String(item.cartItemId);
    const imageUrl = resolveImageUrl(item.imagePath);
    const lineTotal = Number(item.price || 0) * Number(item.quantity || 0);
    const detailParts = [];

    if (item.color) {
        detailParts.push(`<strong>Màu sắc:</strong> ${escapeHtml(item.color)}`);
    }
    if (item.size) {
        detailParts.push(`<strong>Size:</strong> ${escapeHtml(item.size)}`);
    }

    return `
        <article class="cart-row" data-cart-item-id="${cartItemId}">
            <label class="check-wrap">
                <input type="checkbox" class="row-check" data-cart-item-id="${cartItemId}" ${selectedItems.has(cartItemId) ? "checked" : ""}>
                <span></span>
            </label>

            <div class="cart-product">
                <img class="cart-product-image" src="${imageUrl}" alt="${escapeHtml(item.productName || "Sản phẩm")}" onerror="this.src='/SELLING-GLASSES/public/assets/images/thumbnail1.jpg'">
                <div class="cart-product-copy">
                    <h3>${escapeHtml(item.productName || "Sản phẩm đang cập nhật")}</h3>
                    <p class="cart-product-code">Mã biến thể: ${escapeHtml(item.variantId || "")}</p>
                    <p class="cart-product-detail">${detailParts.join(" • ") || "Phiên bản tiêu chuẩn"}</p>
                    ${item.description ? `<p class="cart-product-detail">${escapeHtml(item.description)}</p>` : ""}
                </div>
            </div>

            <div class="cart-price">${formatCurrency(item.price)}</div>

            <div class="qty-control">
                <button type="button" class="qty-btn" data-action="decrease" data-cart-item-id="${cartItemId}" ${Number(item.quantity) <= 1 ? "disabled" : ""}>-</button>
                <span class="qty-value">${Number(item.quantity || 0)}</span>
                <button type="button" class="qty-btn" data-action="increase" data-cart-item-id="${cartItemId}" ${Number(item.quantity) >= Number(item.stock) ? "disabled" : ""}>+</button>
            </div>

            <div class="cart-stock">${Math.max(Number(item.stock || 0) - Number(item.quantity || 0), 0)} sản phẩm</div>

            <div class="cart-line-total">${formatCurrency(lineTotal)}</div>

            <button type="button" class="remove-item" data-cart-item-id="${cartItemId}" aria-label="Xóa sản phẩm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2m-7 4v7m6-7v7M6 6l1 14h10l1-14"></path>
                </svg>
            </button>
        </article>
    `;
}

function bindRowEvents() {
    document.querySelectorAll(".row-check").forEach((input) => {
        input.addEventListener("change", () => {
            const cartItemId = String(input.dataset.cartItemId);

            if (input.checked) {
                selectedItems.add(cartItemId);
            } else {
                selectedItems.delete(cartItemId);
            }

            sessionStorage.setItem(
                "selectedCartItems",
                JSON.stringify(Array.from(selectedItems))
            );

            renderCart();
        });
    });

    document.querySelectorAll(".qty-btn").forEach((button) => {
        button.addEventListener("click", async () => {
            const cartItemId = String(button.dataset.cartItemId);
            const item = cartItems.find((cartRow) => String(cartRow.cartItemId) === cartItemId);

            if (!item) {
                return;
            }

            const currentQuantity = Number(item.quantity || 0);
            const nextQuantity = button.dataset.action === "increase"
                ? currentQuantity + 1
                : currentQuantity - 1;

            await changeQuantity(cartItemId, nextQuantity);
        });
    });

    document.querySelectorAll(".remove-item").forEach((button) => {
        button.addEventListener("click", async () => {
            await removeItem(button.dataset.cartItemId);
        });
    });
}

function syncSummary(selectedCartItems) {
    const subtotal = selectedCartItems.reduce((sum, item) => {
        return sum + Number(item.price || 0) * Number(item.quantity || 0);
    }, 0);
    const selectedCount = selectedCartItems.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
    const shipping = subtotal > 0 ? 30000 : 0;
    const total = subtotal + shipping;

    setText("selected-count", `${selectedCount}`);
    setText("subtotal", formatCurrency(subtotal));
    setText("shipping", formatCurrency(shipping));
    setText("total", formatCurrency(total));
}

function goToCheckout() {
    if (!selectedItems.size) {
        alert("Hãy chọn ít nhất một sản phẩm trước khi thanh toán.");
        return;
    }

    sessionStorage.setItem("selectedCartItems", JSON.stringify(Array.from(selectedItems)));
    window.location.href = `${CART_BASE}/checkout`;
}

function renderState(type) {
    if (type === "login") {
        return `
            <div class="cart-state">
                <h3>Bạn cần đăng nhập để xem giỏ hàng</h3>
                <p>Giỏ hàng đang gắn với tài khoản khách hàng trong hệ thống này. Hãy đăng nhập trước rồi quay lại để tiếp tục mua sắm.</p>
                <a class="btn btn-primary" href="${CART_BASE}/auth">Đăng nhập ngay</a>
            </div>
        `;
    }

    if (type === "error") {
        return `
            <div class="cart-state">
                <h3>Chưa tải được giỏ hàng</h3>
                <p>Đã có lỗi khi lấy dữ liệu sản phẩm. Bạn thử tải lại trang hoặc kiểm tra kết nối cơ sở dữ liệu của dự án.</p>
                <a class="btn btn-primary" href="${CART_BASE}/cart">Tải lại</a>
            </div>
        `;
    }

    return `
        <div class="cart-state">
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p>Hãy chọn thêm một vài mẫu kính để phần tóm tắt đơn hàng và thao tác thanh toán hiển thị đầy đủ.</p>
            <a class="btn btn-primary" href="${CART_BASE}/home">Tiếp tục mua hàng</a>
        </div>
    `;
}

function resolveImageUrl(imagePath) {
    if (!imagePath) {
        return `${CART_BASE}/assets/images/thumbnail1.jpg`;
    }

    if (imagePath.startsWith("http://") || imagePath.startsWith("https://") || imagePath.startsWith("/")) {
        return imagePath;
    }

    return `${CART_BASE}/assets/images/products/${imagePath}`;
}

function setText(id, value) {
    const node = document.getElementById(id);
    if (node) {
        node.textContent = value;
    }
}

function formatCurrency(value) {
    return `${Number(value || 0).toLocaleString("vi-VN")}đ`;
}

function escapeHtml(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function updateCartCount(data) {
    const badge = document.getElementById("cart-count");
    if (!badge) return;

    if (!Array.isArray(data)) {
        badge.innerText = 0;
        return;
    }

    const totalQty = data.reduce((total, item) => {
        return total + Number(item.quantity || 0);
    }, 0);

    badge.innerText = totalQty;
}
