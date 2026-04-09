const CHECKOUT_BASE = "/SELLING-GLASSES/public";
let checkoutSummary = null;

document.addEventListener("DOMContentLoaded", () => {
    loadCheckoutSummary();

    const form = document.getElementById("checkout-form");
    if (form) {
        form.addEventListener("submit", submitPendingPayment);
    }
});

function getSelectedCartItems() {
    try {
        const raw = sessionStorage.getItem("selectedCartItems");
        const parsed = raw ? JSON.parse(raw) : [];
        return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
        return [];
    }
}

async function loadCheckoutSummary() {
    const selectedCartItems = getSelectedCartItems();
    const query = selectedCartItems.length ? `?selected=${selectedCartItems.join(",")}` : "";

    try {
        const response = await fetch(`${CHECKOUT_BASE}/get-checkout-summary${query}`, {
            credentials: "include"
        });
        const result = await response.json();

        if (!result.success) {
            renderEmptyState(result.message || "Không có dữ liệu thanh toán");
            return;
        }

        checkoutSummary = result.data;
        fillCustomerInfo(checkoutSummary.customer || {});
        document.getElementById("transferNote").value = checkoutSummary.transferNote || "";
        renderItems(checkoutSummary.items || []);
        renderSummary(checkoutSummary.summary || {});
        renderBankInfo(checkoutSummary.bankInfo || {}, checkoutSummary.summary || {}, checkoutSummary.transferNote || "");
    } catch (error) {
        renderEmptyState("Không tải được dữ liệu thanh toán. Vui lòng thử lại.");
    }
}

function fillCustomerInfo(customer) {
    setValue("recipientName", customer.name || "");
    setValue("recipientEmail", customer.email || "");
    setValue("recipientPhone", customer.phone || "");
}

function renderItems(items) {
    const container = document.getElementById("checkout-items");
    const countLabel = document.getElementById("cart-count-label");

    if (!container || !countLabel) {
        return;
    }

    const totalQuantity = items.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
    countLabel.textContent = `(${totalQuantity})`;

    container.innerHTML = items.map((item) => {
        const lineTotal = Number(item.price || 0) * Number(item.quantity || 0);
        const imageUrl = resolveImageUrl(item.imagePath);

        return `
            <article class="checkout-item">
                <img src="${imageUrl}" alt="${escapeHtml(item.productName || "Sản phẩm")}" onerror="this.src='/SELLING-GLASSES/public/assets/images/thumbnail1.jpg'">
                <div>
                    <h4>${escapeHtml(item.productName || "Sản phẩm")}</h4>
                    <p>Màu: ${escapeHtml(item.color || "-")} • Size: ${escapeHtml(item.size || "-")}</p>
                    <p>Số lượng: ${Number(item.quantity || 0)}</p>
                </div>
                <div class="item-price">${formatCurrency(lineTotal)}</div>
            </article>
        `;
    }).join("");
}

function renderSummary(summary) {
    setText("subtotal", formatCurrency(summary.subtotal || 0));
    setText("discount", formatCurrency(summary.discount || 0));
    setText("shipping-fee", formatCurrency(summary.shippingFee || 0));
    setText("grand-total", formatCurrency(summary.total || 0));
}

function renderBankInfo(bankInfo, summary, transferNote) {
    setText("bank-name", `${bankInfo.bankName || "-"} (${bankInfo.bankCode || "-"})`);
    setText("bank-account", bankInfo.accountNumber || "-");
    setText("bank-owner", bankInfo.accountName || "-");
    setText("bank-note", transferNote || "-");

    const qr = document.getElementById("bank-qr");
    if (!qr) {
        return;
    }

    const amount = Number(summary.total || 0);
    const account = encodeURIComponent(bankInfo.accountNumber || "");
    const bankCode = encodeURIComponent(bankInfo.bankCode || "");
    const accountName = encodeURIComponent(bankInfo.accountName || "");
    const addInfo = encodeURIComponent(transferNote || "EYESGLASS");
    qr.src = `https://img.vietqr.io/image/${bankCode}-${account}-compact2.png?amount=${amount}&addInfo=${addInfo}&accountName=${accountName}`;
}

async function submitPendingPayment(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const button = document.getElementById("submit-payment");

    if (!checkoutSummary || !checkoutSummary.items || !checkoutSummary.items.length) {
        alert("Không có sản phẩm để thanh toán.");
        return;
    }

    const selectedCartItems = getSelectedCartItems().length
        ? getSelectedCartItems()
        : (checkoutSummary.items || []).map((item) => item.cartItemId);
    const formData = new FormData(form);
    formData.append("selectedCartItems", selectedCartItems.join(","));

    button.disabled = true;
    button.textContent = "Đang gửi yêu cầu thanh toán...";

    try {
        const response = await fetch(`${CHECKOUT_BASE}/create-pending-payment`, {
            method: "POST",
            body: formData,
            credentials: "include"
        });
        const result = await response.json();

        if (!result.success) {
            alert(result.message || "Không thể tạo yêu cầu thanh toán.");
            return;
        }

        sessionStorage.removeItem("selectedCartItems");
        alert(`Đã tạo đơn #${result.data.orderId}. Đơn hiện ở trạng thái chờ admin duyệt.`);
        window.location.href = `${CHECKOUT_BASE}/profile`;
    } catch (error) {
        alert("Không gửi được yêu cầu thanh toán. Vui lòng thử lại.");
    } finally {
        button.disabled = false;
        button.textContent = "Tôi đã chuyển khoản, gửi đơn chờ duyệt";
    }
}

function renderEmptyState(message) {
    const container = document.getElementById("checkout-items");
    if (container) {
        container.innerHTML = `<div class="empty-state">${escapeHtml(message)}</div>`;
    }
}

function resolveImageUrl(imagePath) {
    if (!imagePath) {
        return `${CHECKOUT_BASE}/assets/images/thumbnail1.jpg`;
    }

    if (imagePath.startsWith("http://") || imagePath.startsWith("https://") || imagePath.startsWith("/")) {
        return imagePath;
    }

    return `${CHECKOUT_BASE}/assets/images/products/${imagePath}`;
}

function setText(id, value) {
    const node = document.getElementById(id);
    if (node) {
        node.textContent = value;
    }
}

function setValue(id, value) {
    const node = document.getElementById(id);
    if (node) {
        node.value = value;
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
