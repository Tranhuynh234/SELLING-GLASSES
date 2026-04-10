const ADMIN_PAYMENT_BASE = "/SELLING-GLASSES/public";
let activeTab = "pending";

document.addEventListener("DOMContentLoaded", () => {
    bindAdminEvents();
    loadCounts();
    loadPayments(activeTab);
});

function bindAdminEvents() {
    document.querySelectorAll(".sidebar-link").forEach((button) => {
        button.addEventListener("click", () => {
            document.querySelectorAll(".sidebar-link").forEach((item) => item.classList.remove("active"));
            button.classList.add("active");
            activeTab = button.dataset.tab;
            document.getElementById("panel-title").textContent = activeTab === "pending"
                ? "Danh sách chờ duyệt"
                : "Danh sách thanh toán thành công";
            loadPayments(activeTab);
        });
    });

    const refreshButton = document.getElementById("refresh-payments");
    if (refreshButton) {
        refreshButton.addEventListener("click", () => {
            loadCounts();
            loadPayments(activeTab);
        });
    }

    const logoutButton = document.getElementById("admin-logout");
    if (logoutButton) {
        logoutButton.addEventListener("click", logoutAdmin);
    }
}

async function loadCounts() {
    const [pending, success] = await Promise.all([
        fetchPayments("pending"),
        fetchPayments("success")
    ]);

    setText("pending-count", Array.isArray(pending) ? pending.length : 0);
    setText("success-count", Array.isArray(success) ? success.length : 0);
}

async function loadPayments(status) {
    const rows = await fetchPayments(status);
    const body = document.getElementById("payment-table-body");

    if (!body) {
        return;
    }

    if (!Array.isArray(rows) || !rows.length) {
        body.innerHTML = `<tr><td colspan="8" class="table-empty">Không có giao dịch ở mục ${status}.</td></tr>`;
        return;
    }

    body.innerHTML = rows.map((row) => `
        <tr>
            <td>
                <strong>#PAY-${row.paymentId}</strong>
                <p>${escapeHtml(row.paymentMethod || "Bank Transfer")}</p>
            </td>
            <td>
                <strong>#${row.orderId}</strong>
                <p>${escapeHtml(row.orderDate || "")}</p>
            </td>
            <td>
                <strong>${escapeHtml(row.customerName || "")}</strong>
                <p>${escapeHtml(row.customerPhone || "")}</p>
                <p>${escapeHtml(row.customerEmail || "")}</p>
            </td>
            <td>
                <p>${escapeHtml(row.items || "Chưa có chi tiết sản phẩm")}</p>
            </td>
            <td>
                <strong>${formatCurrency(row.totalPrice || 0)}</strong>
            </td>
            <td>
                <p>${escapeHtml(row.customerAddress || "Chưa có địa chỉ")}</p>
            </td>
            <td>
                <span class="status-pill ${status === "pending" ? "pending" : "success"}">${escapeHtml(row.uiStatus || row.paymentStatus || "")}</span>
            </td>
            <td>
                ${status === "pending"
                    ? `<button type="button" class="approve-btn" data-payment-id="${row.paymentId}">Duyệt thành công</button>`
                    : `<p>Đã duyệt</p>`}
            </td>
        </tr>
    `).join("");

    if (status === "pending") {
        document.querySelectorAll(".approve-btn").forEach((button) => {
            button.addEventListener("click", () => approvePayment(button));
        });
    }
}

async function fetchPayments(status) {
    try {
        const response = await fetch(`${ADMIN_PAYMENT_BASE}/get-payment-requests?status=${status}`, {
            credentials: "include"
        });
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        return [];
    }
}

async function approvePayment(button) {
    const paymentId = button.dataset.paymentId;
    button.disabled = true;
    button.textContent = "Đang duyệt...";

    try {
        const formData = new FormData();
        formData.append("paymentId", paymentId);

        const response = await fetch(`${ADMIN_PAYMENT_BASE}/approve-payment`, {
            method: "POST",
            body: formData,
            credentials: "include"
        });
        const result = await response.json();

        if (!result.success) {
            alert(result.message || "Không thể duyệt thanh toán.");
            return;
        }

        await loadCounts();
        await loadPayments(activeTab);
    } catch (error) {
        alert("Không thể duyệt thanh toán. Vui lòng thử lại.");
    } finally {
        button.disabled = false;
        button.textContent = "Duyệt thành công";
    }
}

async function logoutAdmin() {
    try {
        await fetch(`${ADMIN_PAYMENT_BASE}/logout`, {
            method: "POST",
            credentials: "include"
        });
    } catch (error) {
    }

    window.location.href = `${ADMIN_PAYMENT_BASE}/index.php?url=home`;
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
