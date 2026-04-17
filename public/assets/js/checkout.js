const CHECKOUT_BASE = "/SELLING-GLASSES/public";
let checkoutSummary = null;

document.addEventListener("DOMContentLoaded", () => {
    loadCheckoutSummary();

    const form = document.getElementById("checkout-form");
    if (form) {
        form.addEventListener("submit", submitPendingPayment);
    }

    const citySelect = document.getElementById("city");

    if (citySelect) {
        citySelect.addEventListener("change", () => {
            if (!checkoutSummary) return;

            const subtotal = checkoutSummary.summary.subtotal;
            const lensPrice = checkoutSummary.summary.lensPrice || 0;
            const shippingFee = calculateShippingFee(subtotal);

            checkoutSummary.summary.shippingFee = shippingFee;
            checkoutSummary.summary.total = subtotal + shippingFee + lensPrice;

            renderSummary(checkoutSummary.summary);
            renderBankInfo(
                checkoutSummary.bankInfo,
                checkoutSummary.summary,
                checkoutSummary.transferNote
            );
        });
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
    console.log("selectedCartItems:", selectedCartItems);
    if (!selectedCartItems.length) {
        renderEmptyState("Bạn chưa chọn sản phẩm nào để thanh toán!");
        return;
    }

    const query = `?selected=${selectedCartItems.join(",")}`;

    try {
        const response = await fetch(`${CHECKOUT_BASE}/get-checkout-summary${query}`, {
            credentials: "include"
        });
        const data = await response.json();

        const sessionRes = await fetch(`${CHECKOUT_BASE}/index.php?url=get-prescription-session`, {
            credentials: "include"
        });
        const sessionData = await sessionRes.json();
  
        let lensPrice = 0;
        if (sessionData && sessionData.price !== undefined && sessionData.price !== null) {
            lensPrice = Number(sessionData.price);
        } else {
            lensPrice = 0;
        }

        if (!data || data.length === 0) {
            renderEmptyState("Không có dữ liệu thanh toán");
            return;
        }

        const filteredData = data.filter(item =>
            selectedCartItems.includes(String(item.cartItemId))
        );

        const subtotal = filteredData.reduce((sum, i) => sum + i.price * i.quantity, 0);
        const shippingFee = calculateShippingFee(subtotal);

        checkoutSummary = {
            items: filteredData,
            summary: {
                subtotal: subtotal,
                discount: 0,
                lensPrice: lensPrice,
                shippingFee: shippingFee,
                total: subtotal + shippingFee + lensPrice 
            },
            bankInfo: {
                bankName: "Vietcombank",
                bankCode: "VCB",
                accountNumber: "9346484951",
                accountName: "EYESGLASS VN"
            },
        transferNote: "EYESGLASS"
    };

        // gọi render
    renderItems(checkoutSummary.items);
    renderSummary(checkoutSummary.summary);
    

        //checkoutSummary = result.data;
        fillCustomerInfo(checkoutSummary.customer || {});
        document.getElementById("transferNote").value = checkoutSummary.transferNote || "";
        renderItems(checkoutSummary.items || []);
        renderSummary(checkoutSummary.summary || {});
        renderBankInfo(checkoutSummary.bankInfo || {}, checkoutSummary.summary || {}, checkoutSummary.transferNote || "");
    } catch (error) {
        console.log("DATA:", data);
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
        const imageUrl = item.imagePath 
            ? `/SELLING-GLASSES/public/assets/images/products/${item.imagePath}`
            : "/SELLING-GLASSES/public/assets/images/thumbnail1.jpg";

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

    let lensPrice = Number(summary.lensPrice || 0);
    if (!lensPrice) {
        const serverLens = Number(document.getElementById('server-lens-cost')?.value || 0);
        if (serverLens) lensPrice = serverLens;
    }
    setText("lens-cost", formatCurrency(lensPrice || 0));

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
    qr.classList.add("vietqr"); // Chỉ áp dụng khi là VietQR
}

async function submitPendingPayment(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const button = document.getElementById("submit-payment");

    if (!checkoutSummary || !checkoutSummary.items || !checkoutSummary.items.length) {
        alert("Không có sản phẩm để thanh toán.");
        return;
    }

    const selectedCartItems = getSelectedCartItems();

    if (!selectedCartItems.length) {
        alert("Vui lòng chọn sản phẩm để thanh toán!");
        return;
    }

    const formData = new FormData(form);
    formData.append("selectedCartItems", selectedCartItems.join(","));

    const finalTotal = checkoutSummary.summary.total; 
    formData.append("totalPrice", checkoutSummary.summary.total);
    formData.append("shippingFee", checkoutSummary.summary.shippingFee);

    button.disabled = true;
    button.textContent = "Đang gửi...";

    try {
        const response = await fetch(`${CHECKOUT_BASE}/create-pending-payment`, {
            method: "POST",
            body: formData,
            credentials: "include"
        });

        const data = await response.json();
        console.log("RESPONSE:", data);

        if (!data || !data.success || !data.data?.orderId) {
            alert("Tạo đơn thất bại!");
            return;
        }

        sessionStorage.removeItem("selectedCartItems");

    alert(`Đã tạo đơn #${data.data.orderId}. Đơn đang chờ duyệt.`);

    // Chuyển sang trang profile -> tab orders, đồng thời hiển thị chi tiết đơn mới tạo
    const orderId = encodeURIComponent(data.data.orderId);
    window.location.href = `${CHECKOUT_BASE}/index.php?url=profile&tab=orders&order_id=${orderId}`;

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

function calculateShippingFee(subtotal) {
    const city = document.getElementById("city")?.value;

    if (city === "Ho Chi Minh") {
        return 0;
    }

    if (subtotal >= 500000) {
        return 0;
    }

    return 30000;
}

function useSavedPrescription() {
    // 1. Gửi yêu cầu lên server để báo rằng khách dùng số đo hồ sơ
    // Ở đây mình tận dụng route 'prescription-store' bạn đã có
    fetch('/SELLING-GLASSES/public/index.php?url=prescription-store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'use_saved=true' // Gửi một flag báo là dùng đồ có sẵn
    })
    .then(res => res.json())
    .then(data => {
        // Load lại trang để cập nhật tổng tiền
        window.location.reload();
    })
    .then(response => {
        // 2. Sau khi server lưu session phí cắt tròng, ta load lại trang
        // để PHP tính lại tổng tiền mới nhất
        window.location.href = '/SELLING-GLASSES/public/index.php?url=checkout&status=saved';
    })
    .catch(error => console.error('Lỗi:', error));
    
}