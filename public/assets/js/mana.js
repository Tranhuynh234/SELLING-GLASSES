// 1. KHỞI TẠO DỮ LIỆU
let products = JSON.parse(localStorage.getItem('products')) || [];
let promos = JSON.parse(localStorage.getItem('promos')) || [];
let policies = JSON.parse(localStorage.getItem('policies')) || [];
let currentType = ""; 
let mainChart = null; 

// 2. ĐIỀU HƯỚNG TAB
function showTab(tabId) {
    document.querySelectorAll(".tab-pane").forEach(t => t.classList.remove("active"));
    document.querySelectorAll(".nav-item").forEach(n => n.classList.remove("active"));
    
    const targetTab = document.getElementById(tabId);
    const targetBtn = document.getElementById("btn-" + tabId);
    
    if(targetTab) targetTab.classList.add("active");
    if(targetBtn) targetBtn.classList.add("active");

    // Nếu quay lại Dashboard, vẽ lại biểu đồ với hiệu ứng mượt
    if(tabId === 'dashboard') {
        setTimeout(initChart, 150); 
    }
}

// 3. QUẢN LÝ MODAL LUXURY
function openModal(t) {
    currentType = t;
    const title = document.getElementById("modalTitle");
    const lbl1 = document.getElementById("lbl1");
    const lbl2 = document.getElementById("lbl2");

    const configs = {
        product: { title: "THÊM MẮT KÍNH MỚI", l1: "Tên mắt kính", l2: "Giá bán niêm yết" },
        promo:   { title: "TẠO MÃ KHUYẾN MÃI", l1: "Tên mã / Sự kiện", l2: "Mức giảm (%)" },
        policy:  { title: "THÊM CHÍNH SÁCH",   l1: "Tiêu đề quy định", l2: "Nội dung chi tiết" }
    };

    title.innerText = configs[t].title;
    lbl1.innerText = configs[t].l1;
    lbl2.innerText = configs[t].l2;

    document.getElementById("modal").style.display = "flex";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

// 4. LƯU DỮ LIỆU & FORMAT TIỀN TỆ
function saveData() {
    const v1 = document.getElementById("input1").value.trim();
    const v2 = document.getElementById("input2").value.trim();

    if (!v1 || !v2) {
        showToast("Đừng để trống thông tin nhé! ", "error");
        return;
    }

    let finalValue = v2;
    // Format giá tiền tự động cho chuyên nghiệp
    if(currentType === 'product' && !isNaN(v2.replace(/\./g, ''))) {
        finalValue = new Number(v2.replace(/\./g, '')).toLocaleString('vi-VN') + "đ";
    }

    const entry = { name: v1, detail: finalValue };

    if (currentType === "product") products.push(entry);
    else if (currentType === "promo") promos.push(entry);
    else if (currentType === "policy") policies.push(entry);

    syncData();
    closeModal();
    logActivity(`Đã thêm thành công: ${v1}`);
    showToast(`Đã thêm ${v1} vào hệ thống!`);

    document.getElementById("input1").value = "";
    document.getElementById("input2").value = "";
}

// 5. ĐỒNG BỘ & RENDER
function syncData() {
    localStorage.setItem('products', JSON.stringify(products));
    localStorage.setItem('promos', JSON.stringify(promos));
    localStorage.setItem('policies', JSON.stringify(policies));

    renderTable("productTable", products);
    renderTable("promoTable", promos);
    renderTable("policyTable", policies);
    updateStats();
}

function renderTable(tableId, data) {
    const tbody = document.getElementById(tableId);
    if (!tbody) return;
    
    tbody.innerHTML = data.map((item, i) => `
        <tr class="fade-in">
            <td>${i + 1}</td>
            <td style="font-weight:600; color: #1c1917">${item.name}</td>
            <td><span class="badge-vip">${item.detail}</span></td>
            <td>
                <button class="btn-del" onclick="deleteItem('${tableId}', ${i})" style="color:#ef4444; border:none; background:none; cursor:pointer; font-size: 1.1rem;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function deleteItem(tableId, index) {
    if (!confirm("Hệ thống: Manager xác nhận xóa mục này?")) return;

    if (tableId === "productTable") products.splice(index, 1);
    else if (tableId === "promoTable") promos.splice(index, 1);
    else if (tableId === "policyTable") policies.splice(index, 1);
    
    syncData();
    logActivity(`Hệ thống vừa xóa dữ liệu từ ${tableId.replace('Table', '')}`);
    showToast("Đã xóa mục thành công.", "info");
}

function updateStats() {
    const updateVal = (id, val) => {
        const el = document.getElementById(id);
        if(el) el.innerText = val;
    };
    updateVal("totalProduct", products.length);
    updateVal("totalPromo", promos.length);
    updateVal("totalPolicy", policies.length);
}

// 6. NHẬT KÝ & THÔNG BÁO AMBER
function logActivity(msg) {
    const list = document.getElementById("logList");
    if(!list) return;
    const item = document.createElement("div");
    item.className = "log-item";
    item.innerHTML = `<span class="time">${new Date().toLocaleTimeString()}</span> ${msg}`;
    list.prepend(item);
}

function showToast(message, type = "success") {
    const toast = document.createElement("div");
    // Chỉnh màu Toast theo tông Stone & Amber
    const bgColor = type === "success" ? "#d97706" : (type === "error" ? "#7f1d1d" : "#44403c");
    
    toast.style = `position:fixed; bottom:30px; right:30px; background:${bgColor}; color:white; padding:16px 28px; border-radius:12px; z-index:10000; box-shadow:0 15px 35px rgba(0,0,0,0.2); animation: slideUp 0.4s ease; font-weight:600;`;
    toast.innerText = message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = "0"; toast.style.transform = "translateY(20px)"; setTimeout(() => toast.remove(), 500); }, 3000);
}

// 7. BIỂU ĐỒ TÔNG AMBER LUXURY
function initChart() {
    const chartElement = document.getElementById('revenueChart');
    if (!chartElement) return;

    if (mainChart) mainChart.destroy(); 

    const ctx = chartElement.getContext('2d');
    // Gradient vàng hổ phách (Amber)
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(217, 119, 6, 0.3)'); // amber-600
    gradient.addColorStop(1, 'rgba(217, 119, 6, 0)');

    mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
            datasets: [{
                label: 'Doanh thu dự kiến',
                data: [45, 52, 38, 65, 48, 80, 80],
                borderColor: '#d97706',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#d97706',
                pointRadius: 5,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)' } },
                x: { grid: { display: false } }
            }
        }
    });
}

window.onload = function() {
    syncData();
    initChart();
    logActivity("Hệ thống quản trị trực tuyến.");
};
