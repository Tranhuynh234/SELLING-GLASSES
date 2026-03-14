/**
 * LENS ERP - PREMIER CORE JS v3.0
 */

// 1. DATABASE QUYỀN HẠN (Yêu cầu 1, 4)
const users = {
    "admin@lens.com": { 
        role: "ADMIN", name: "Huỳnh Võ Huyền Trân", 
        permissions: ["ALL"], url: "admindashboard.html" 
    },
    "manager@lens.com": { 
        role: "MANAGER", name: "Lê Thiên Trứ", 
        permissions: ["dashboard-manager", "hr-management"], 
        url: "admindashboard.html" 
    },
    "sales@lens.com": { 
        role: "SALES", name: "Vũ Hoàng Yến", 
        permissions: ["dashboard-sales"], 
        url: "admindashboard.html" 
    },
    "ops@lens.com": { 
        role: "OPERATIONS", name: "Lê Đức Huy", 
        permissions: ["dashboard-ops"], 
        url: "admindashboard.html" 
    }
};

const loggedInEmail = localStorage.getItem('currentUserEmail') || "admin@lens.com";
let currentUser = users[loggedInEmail];

document.addEventListener('DOMContentLoaded', () => {
    updateUserInfo();
    applySecurityFilters(); // Ẩn các menu không thuộc quyền
    checkCurrentPagePermission(); // Khóa chỉnh sửa nếu sai bộ phận
    renderKPITable(); // Render KPI cho Admin quản lý
    renderRevenueDetail(); // Timeline vận hành
});

// YÊU CẦU 1: PHÂN QUYỀN CHỈNH SỬA (CHỈ XEM NẾU SAI BỘ PHẬN)
function checkCurrentPagePermission() {
    if (currentUser.role === "ADMIN") return;

    const activeArea = document.querySelector('.tab-content.active') || document.body;
    const currentId = activeArea.id;

    // Kiểm tra quyền sửa của User đang đăng nhập
    const canEdit = currentUser.permissions.includes(currentId) || currentUser.permissions.includes("ALL");

    if (!canEdit && currentId !== "") {
        const elementsToLock = activeArea.querySelectorAll('button:not(.sidebar-toggle), input, select, textarea');
        elementsToLock.forEach(el => {
            el.disabled = true;
            el.style.opacity = "0.5";
            el.style.cursor = "not-allowed";
        });

        if (!activeArea.querySelector('.lock-notice')) {
            const notice = document.createElement('div');
            notice.className = "lock-notice bg-amber-50 text-amber-700 p-4 rounded-xl mb-6 text-sm border border-amber-200 flex items-center gap-3 font-semibold";
            notice.innerHTML = `<span>🔒</span> <div>Chế độ xem hạn chế: Bạn không có quyền thay đổi dữ liệu tại đây.</div>`;
            activeArea.prepend(notice);
        }
    }
}

// YÊU CẦU 5: QUẢN LÝ KPI CHO ADMIN
function renderKPITable() {
    const kpiData = [
        { name: "Vũ Hoàng Yến", role: "Sales", salary: "12M", perf: "84%" },
        { name: "Lê Đức Huy", role: "Ops", salary: "10M", perf: "92%" },
        { name: "Lê Thiên Trứ", role: "Manager", salary: "25M", perf: "96%" }
    ];

    const container = document.getElementById('kpi-body');
    if(container) {
        container.innerHTML = kpiData.map(item => `
            <tr class="border-b border-stone-50 hover:bg-stone-50 transition-all">
                <td class="py-5 font-bold text-stone-900">${item.name} <br> <span class="text-[10px] text-stone-400 uppercase">${item.role}</span></td>
                <td><input type="text" value="${item.salary}" class="bg-transparent border-none w-20 focus:ring-1 focus:ring-amber-500 rounded p-1 font-medium"></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-32 bg-stone-100 h-1 rounded-full overflow-hidden">
                            <div class="bg-stone-900 h-full" style="width: ${item.perf}"></div>
                        </div>
                        <span class="text-xs font-bold text-amber-600">${item.perf}</span>
                    </div>
                </td>
                <td class="text-right"><button onclick="saveChanges(this)" class="text-[10px] font-bold uppercase text-stone-900 hover:text-amber-600 transition">Cập nhật</button></td>
            </tr>
        `).join('');
    }
}

// YÊU CẦU 6: TIMELINE & DEADLINE GẤP
function renderRevenueDetail() {
    const tasks = [
        { time: "08:30", task: "Kiểm tồn kho tròng Rx 1.67", status: "Xong", urgent: false },
        { time: "10:00", task: "Xác nhận đơn hàng #ORD-8812", status: "Đang làm", urgent: true },
        { time: "14:00", task: "Giao vận chuyển GHN", status: "Chờ", urgent: false }
    ];
    const container = document.getElementById('ops-timeline');
    if(container) {
        container.innerHTML = tasks.map(t => `
            <div class="flex gap-4 border-l-2 border-stone-800 pl-6 pb-8 relative">
                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-4 ${t.urgent ? 'border-red-500 animate-pulse' : 'border-amber-500'}"></div>
                <span class="text-[10px] font-bold text-stone-400 w-10 uppercase">${t.time}</span>
                <div>
                    <p class="text-sm font-semibold ${t.urgent ? 'text-white' : 'text-stone-300'}">${t.task}</p>
                    <p class="text-[10px] font-bold uppercase ${t.urgent ? 'text-red-500' : 'text-stone-500'}">${t.status}</p>
                </div>
            </div>
        `).join('');
    }
}

function switchAdminTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    const target = document.getElementById(tabId);
    if(target) {
        target.classList.add('active');
        checkCurrentPagePermission(); // Kiểm tra quyền mỗi khi đổi tab
    }
    
    document.querySelectorAll('.admin-sidebar-item').forEach(btn => {
        btn.classList.remove('text-amber-500', 'bg-stone-900/50', 'border-stone-800');
        if(btn.getAttribute('data-target') === tabId) btn.classList.add('text-amber-500', 'bg-stone-900/50', 'border-stone-800');
    });
}

function saveChanges(btn) {
    btn.innerText = "⏳ Đang lưu...";
    setTimeout(() => {
        btn.innerText = "Cập nhật";
        showToast("Dữ liệu đã được cập nhật thành công!");
    }, 800);
}

function showToast(msg) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = "bg-stone-900 text-white px-6 py-4 rounded-2xl shadow-2xl text-xs font-bold uppercase tracking-widest";
    toast.innerText = msg;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function updateUserInfo() {
    document.querySelectorAll('.user-name-display').forEach(el => el.innerText = currentUser.name);
    document.querySelector('.user-role-display').innerText = currentUser.role;
}

function applySecurityFilters() {
    if (currentUser.role === "ADMIN") return;
    document.querySelectorAll('.admin-sidebar-item').forEach(item => {
        const target = item.getAttribute('data-target');
        if (!currentUser.permissions.includes(target)) {
            item.style.opacity = "0.2";
            item.style.pointerEvents = "none";
        }
    });
}

function handleLogout() {
    localStorage.removeItem('currentUserEmail');
    window.location.href = "login_out.html";
}
let staffData = [
    { name: "Vũ Hoàng Yến", role: "SALES", email: "sales@lens.com" },
    { name: "Nguyễn Minh Anh", role: "SALES", email: "anh.nguyen@lens.com" },
    { name: "Lê Đức Huy", role: "OPERATIONS", email: "ops@lens.com" },
    { name: "Lê Thiên Trứ", role: "MANAGER", email: "manager@lens.com" }
];

function renderHRTable() {
    const container = document.getElementById('hr-table-body');
    if(!container) return;

    container.innerHTML = staffData.map((staff, index) => `
        <tr class="border-b border-stone-50 hover:bg-stone-50 transition-all">
            <td class="p-6">
                <input type="text" value="${staff.name}" onchange="updateStaff(${index}, 'name', this.value)" 
                       class="bg-transparent border-none focus:ring-1 focus:ring-amber-500 rounded p-1 font-bold text-stone-900 w-full">
            </td>
            <td class="p-6">
                <select onchange="updateStaff(${index}, 'role', this.value)" 
                        class="bg-stone-100 border-none rounded-lg text-[10px] font-bold py-1.5 px-3 outline-none uppercase tracking-wider cursor-pointer">
                    <option value="SALES" ${staff.role === 'SALES' ? 'selected' : ''}>Sales/Support</option>
                    <option value="OPERATIONS" ${staff.role === 'OPERATIONS' ? 'selected' : ''}>Operations</option>
                    <option value="MANAGER" ${staff.role === 'MANAGER' ? 'selected' : ''}>Manager</option>
                </select>
            </td>
            <td class="p-6 text-sm text-stone-500">${staff.email}</td>
            <td class="p-6 text-right">
                <button onclick="deleteStaff(${index})" class="text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
        </tr>
    `).join('');
}

// Hàm thêm dòng nhân sự mới trống
function addNewStaffRow() {
    staffData.push({ name: "Nhân viên mới", role: "SALES", email: "new@lens.com" });
    renderHRTable();
    showToast("Đã thêm dòng nhân sự mới!");
}

// Cập nhật dữ liệu khi gõ/chọn
function updateStaff(index, field, value) {
    staffData[index][field] = value;
    showToast("Đã cập nhật thông tin!");
}

// Xóa nhân sự
function deleteStaff(index) {
    if(confirm("Bạn có chắc muốn xóa nhân sự này?")) {
        staffData.splice(index, 1);
        renderHRTable();
        showToast("Đã xóa nhân sự!");
    }
}

function renderHRTable() {
    const container = document.getElementById('hr-table-body');
    if(!container) return;

    container.innerHTML = staffData.map((staff, index) => `
        <tr class="border-b border-stone-50 hover:bg-stone-50 transition-all">
            <td class="p-6">
                <input type="text" value="${staff.name}" onchange="updateStaff(${index}, 'name', this.value)" 
                       class="bg-transparent border-none focus:ring-1 focus:ring-amber-500 rounded p-1 font-bold text-stone-900 w-full">
            </td>
            <td class="p-6">
                <select onchange="updateStaff(${index}, 'role', this.value)" 
                        class="bg-stone-100 border-none rounded-lg text-[10px] font-bold py-1.5 px-3 outline-none uppercase tracking-wider cursor-pointer">
                    <option value="SALES" ${staff.role === 'SALES' ? 'selected' : ''}>Sales/Support</option>
                    <option value="OPERATIONS" ${staff.role === 'OPERATIONS' ? 'selected' : ''}>Operations</option>
                    <option value="MANAGER" ${staff.role === 'MANAGER' ? 'selected' : ''}>Manager</option>
                </select>
            </td>
            <td class="p-6">
                <input type="email" value="${staff.email}" onchange="updateStaff(${index}, 'email', this.value)" 
                       class="bg-transparent border-none focus:ring-1 focus:ring-amber-500 rounded p-1 text-sm text-stone-500 w-full"
                       placeholder="nhanvien@lens.com">
            </td>
            <td class="p-6 text-right">
                <button onclick="deleteStaff(${index})" class="text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');
}