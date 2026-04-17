const opsApp = {
  // --- DỮ LIỆU HỆ THỐNG (STATE) ---
  state: {
    user: {
      name: "ThienTru1612",
      role: "Nhân viên Vận hành cấp cao",
      id: "OPS-1612",
      email: "thientru.ops@eyesglass.com",
      joinDate: "16/12/2023",
      processedOrders: 1248,
    },
    inventory: [
      {
        id: "G-TITAN",
        name: "Gọng Titanium EyesGlass",
        stock: 13,
        unit: "Cái",
      },
      { id: "L-BLUE", name: "Tròng Blue Control 1.61", stock: 4, unit: "Cặp" },
      { id: "P-BOX", name: "Hộp kính Da EyesGlass", stock: 0, unit: "Cái" },
    ],
    orders: [
      {
        id: "EG-1100",
        customer: "Nguyễn Văn Toàn",
        status: "Đang xử lý",
        date: "2026-03-19",
        total: "850.000đ",
        tracking: "",
      },
      {
        id: "EG-1101",
        customer: "Trương Mỹ Nhân",
        status: "Đang giao",
        date: "2026-03-18",
        total: "1.250.000đ",
        tracking: "GHN-123456",
      },
      {
        id: "EG-1102",
        customer: "Lê Minh",
        status: "Hoàn tất",
        date: "2026-03-15",
        total: "500.000đ",
        tracking: "VTP-999888",
      },
    ],
    currentFilter: "all", // Lọc cho Kho
    orderFilter: "all", // Lọc cho Đơn hàng
  },

  // --- KHỞI TẠO ---
  init() {
    this.renderTab("dashboard"); // Mặc định hiển thị Tổng quan
    this.bindEvents();
  },

  bindEvents() {
    // Menu Sidebar điều hướng
    document.querySelectorAll(".nav-link").forEach((link) => {
      link.onclick = (e) => {
        e.preventDefault();
        const tab = e.currentTarget.getAttribute("data-tab");
        document
          .querySelectorAll(".nav-link")
          .forEach((l) => l.classList.remove("active"));
        e.currentTarget.classList.add("active");
        this.renderTab(tab);
      };
    });

    // Nút đăng xuất ở sidebar footer
    const logoutBtn = document.querySelector(".btn-logout-sidebar");
    if (logoutBtn) logoutBtn.onclick = () => this.handleLogout();

    // Icon Profile góc trên bên phải
    const headerProfile = document.querySelector(".header-profile");
    if (headerProfile) {
      headerProfile.onclick = () => {
        document
          .querySelectorAll(".nav-link")
          .forEach((l) => l.classList.remove("active"));
        const accLink = document.querySelector('[data-tab="account"]');
        if (accLink) accLink.classList.add("active");
        this.renderTab("account");
      };
    }
  },

  renderTab(tab) {
    const area = document.getElementById("render-area");
    area.innerHTML = "";

    switch (tab) {
      case "dashboard":
        this.renderDashboard(area);
        break;
      case "orders":
        this.renderOrders(area);
        break;
      case "shipping":
        this.renderShipping(area);
        break;
      case "warehouse":
        this.renderWarehouse(area);
        break;
      case "account":
        this.renderAccount(area);
        break;
      default:
        area.innerHTML = `<div class="card"><h3>Tính năng đang phát triển</h3></div>`;
    }
  },

  // --- 1. TỔNG QUAN (DASHBOARD) ---
  renderDashboard(el) {
    const pendingOrders = this.state.orders.filter(
      (o) => o.status === "Đang xử lý",
    ).length;
    const outOfStock = this.state.inventory.filter((i) => i.stock <= 0).length;
    const lowStock = this.state.inventory.filter(
      (i) => i.stock < 5 && i.stock > 0,
    ).length;

    el.innerHTML = `
            <div class="fade-in">
                <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="card" style="border-left: 5px solid #e67e22;">
                        <h4>Đơn chờ xử lý</h4>
                        <h2 style="color: #e67e22; margin-top:10px">${pendingOrders}</h2>
                    </div>
                    <div class="card" style="border-left: 5px solid #c0392b;">
                        <h4>Vật tư đã hết hàng</h4>
                        <h2 style="color: #c0392b; margin-top:10px">${outOfStock}</h2>
                    </div>
                    <div class="card" style="border-left: 5px solid #27ae60;">
                        <h4>Hiệu suất làm việc</h4>
                        <h2 style="color: #27ae60; margin-top:10px">98%</h2>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <div class="card">
                        <h3 style="margin-bottom:20px">Hiệu suất vận hành tuần</h3>
                        <canvas id="dashChart" height="150"></canvas>
                    </div>
                    <div class="card">
                        <h3>Lối tắt nhanh</h3>
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-top:15px">
                            <button class="btn-confirm" onclick="opsApp.renderTab('shipping')" style="width:100%; text-align:left; padding:15px">
                                <i class="fa-solid fa-truck-fast"></i> Đẩy vận đơn ngay (${pendingOrders})
                            </button>
                            <button class="btn-confirm" onclick="opsApp.renderTab('warehouse')" style="width:100%; text-align:left; padding:15px; background:#7f8c8d">
                                <i class="fa-solid fa-boxes-stacked"></i> Kiểm kho hàng sắp hết (${lowStock})
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    this.initDashboardChart();
  },

  initDashboardChart() {
    const ctx = document.getElementById("dashChart");
    if (!ctx) return;
    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["T2", "T3", "T4", "T5", "T6", "T7", "CN"],
        datasets: [
          {
            label: "Đơn hàng",
            data: [12, 19, 15, 25, 22, 30, 45],
            borderColor: "#e67e22",
            tension: 0.4,
            fill: true,
            backgroundColor: "rgba(230, 126, 34, 0.05)",
          },
        ],
      },
      options: { plugins: { legend: { display: false } } },
    });
  },

  // --- 2. QUẢN LÝ ĐƠN HÀNG ---
  renderOrders(el) {
    const filteredOrders = this.state.orders.filter((o) => {
      if (this.orderFilter === "pending") return o.status === "Đang xử lý";
      if (this.orderFilter === "shipping") return o.status === "Đang giao";
      if (this.orderFilter === "completed") return o.status === "Hoàn tất";
      return true;
    });

    el.innerHTML = `
            <div class="warehouse-header">
                <h3 style="font-size: 1.5rem; font-weight: 800;">Danh sách đơn hàng</h3>
                <div class="header-actions">
                    <select class="filter-select" onchange="opsApp.handleOrderFilter(this.value)">
                        <option value="all" ${this.orderFilter === "all" ? "selected" : ""}>Tất cả trạng thái</option>
                        <option value="pending" ${this.orderFilter === "pending" ? "selected" : ""}>Đang xử lý</option>
                        <option value="shipping" ${this.orderFilter === "shipping" ? "selected" : ""}>Đang giao</option>
                        <option value="completed" ${this.orderFilter === "completed" ? "selected" : ""}>Hoàn tất</option>
                    </select>
                </div>
            </div>
            <div class="card fade-in">
                <table class="table-pro">
                    <thead>
                        <tr><th>MÃ ĐƠN</th><th>KHÁCH HÀNG</th><th>NGÀY ĐẶT</th><th>TỔNG TIỀN</th><th>TRẠNG THÁI</th><th style="text-align:right">THAO TÁC</th></tr>
                    </thead>
                    <tbody>
                        ${filteredOrders
                          .map(
                            (o) => `
                            <tr>
                                <td><code>#${o.id}</code></td>
                                <td><b>${o.customer}</b></td>
                                <td>${o.date}</td>
                                <td>${o.total}</td>
                                <td><span class="badge ${this.getOrderBadgeClass(o.status)}">${o.status}</span></td>
                                <td style="text-align:right">
                                    <button class="btn-action" onclick="opsApp.openUpdateOrderModal('${o.id}')"><i class="fa-solid fa-pen-to-square"></i></button>
                                </td>
                            </tr>
                        `,
                          )
                          .join("")}
                    </tbody>
                </table>
            </div>
        `;
  },

  handleOrderFilter(val) {
    this.orderFilter = val;
    this.renderOrders();
  },

  getOrderBadgeClass(status) {
    if (status === "Đang xử lý") return "bg-low";
    if (status === "Đang giao") return "bg-shipping";
    if (status === "Hoàn tất") return "bg-ok";
    return "";
  },

  // --- 3. VẬN CHUYỂN ---
  renderShipping(el) {
    const pending = this.state.orders.filter(
      (o) => o.status === "Đang xử lý",
    );
    el.innerHTML = `
            <div class="warehouse-header">
                <h3 style="font-size: 1.5rem; font-weight: 800;">Tạo vận đơn nhanh</h3>
            </div>
            <div class="card fade-in" style="max-width: 600px; margin: 0 auto;">
                <div class="form-group"><label>Chọn đơn hàng chờ đi</label><select id="ship-order-id" class="input-field">${pending.length > 0 ? pending.map((o) => `<option value="${o.id}">Đơn #${o.id} - ${o.customer} (${o.total})</option>`).join("") : "<option disabled>Hết đơn chờ vận chuyển</option>"}</select></div>
                <div class="form-group"><label>Đối tác vận chuyển</label><select id="ship-partner" class="input-field"><option value="GHN">GHN</option><option value="VTP">Viettel Post</option></select></div>
                <button class="btn-confirm" style="width: 100%; padding: 15px;" onclick="opsApp.createShippingTicket()" ${pending.length === 0 ? 'disabled style="opacity:0.5"' : ""}>XÁC NHẬN & XUẤT MÃ</button>
            </div>
        `;
  },

  createShippingTicket() {
    const orderId = document.getElementById("ship-order-id").value;
    const partner = document.getElementById("ship-partner").value;
    const order = this.state.orders.find((o) => o.id === orderId);
    if (order) {
      order.tracking = `${partner}-${Math.floor(100000 + Math.random() * 900000)}`;
      order.status = "Đang giao";
      alert(`Thành công! Mã vận đơn: ${order.tracking}`);
      this.renderTab("orders");
    }
  },

  // --- 4. QUẢN LÝ KHO ---
  renderWarehouse(el) {
    const target = el || document.getElementById("render-area");
    const filteredData = this.state.inventory.filter((item) => {
      if (this.currentFilter === "instock") return item.stock >= 5;
      if (this.currentFilter === "lowstack")
        return item.stock < 5 && item.stock > 0;
      if (this.currentFilter === "outofstock") return item.stock <= 0;
      return true;
    });

    target.innerHTML = `
            <div class="warehouse-header">
                <h3 style="font-size: 1.5rem; font-weight: 800; margin:0">Quản lý vật tư kho</h3>
                <div class="header-actions">
                    <select class="filter-select" onchange="opsApp.handleFilter(this.value)">
                        <option value="all" ${this.currentFilter === "all" ? "selected" : ""}>Tất cả vật tư</option>
                        <option value="instock" ${this.currentFilter === "instock" ? "selected" : ""}>Còn hàng</option>
                        <option value="lowstack" ${this.currentFilter === "lowstack" ? "selected" : ""}>Sắp hết hàng</option>
                        <option value="outofstock" ${this.currentFilter === "outofstock" ? "selected" : ""}>Đã hết hàng</option>
                    </select>
                    <button class="btn-confirm" onclick="opsApp.openAddProductModal()">+ Nhập hàng</button>
                </div>
            </div>
            <div class="card fade-in"><table class="table-pro">
                <thead><tr><th>MÃ SP</th><th>TÊN VẬT TƯ</th><th>SỐ LƯỢNG</th><th>TÌNH TRẠNG</th><th style="text-align:right">THAO TÁC</th></tr></thead>
                <tbody>${filteredData
                  .map((item) => {
                    let stText =
                      item.stock <= 0
                        ? "Đã hết hàng"
                        : item.stock < 5
                          ? "Sắp hết hàng"
                          : "Còn hàng";
                    let stClass =
                      item.stock <= 0
                        ? "bg-empty"
                        : item.stock < 5
                          ? "bg-low"
                          : "bg-ok";
                    let stColor =
                      item.stock <= 0
                        ? "#c0392b"
                        : item.stock < 5
                          ? "#e67e22"
                          : "#27ae60";
                    return `<tr><td><code>${item.id}</code></td><td><b>${item.name}</b></td><td style="color: ${stColor}; font-weight: 800;">${item.stock} ${item.unit}</td><td><span class="badge ${stClass}">${stText}</span></td><td style="text-align:right"><button class="btn-action" onclick="opsApp.adjustStock('${item.id}', 1)">+</button><button class="btn-action" onclick="opsApp.adjustStock('${item.id}', -1)">-</button></td></tr>`;
                  })
                  .join("")}</tbody>
            </table></div>
        `;
  },

  handleFilter(val) {
    this.currentFilter = val;
    this.renderWarehouse();
  },
  adjustStock(id, val) {
    const item = this.state.inventory.find((i) => i.id === id);
    if (item) {
      item.stock = Math.max(0, item.stock + val);
      this.renderWarehouse();
    }
  },

  // --- 5. TÀI KHOẢN (MỚI BỔ SUNG) ---
  renderAccount(el) {
    el.innerHTML = `
            <div class="fade-in">
                <div class="warehouse-header"><h3 style="font-size: 1.5rem; font-weight: 800;">Hồ sơ cá nhân</h3></div>
                <div class="card" style="max-width: 800px; margin: 0 auto; display: grid; grid-template-columns: 250px 1fr; gap: 30px; padding: 40px;">
                    <div style="text-align: center; border-right: 1px solid #eee; padding-right: 30px;">
                        <img src="https://ui-avatars.com/api/?name=Thien+Tru&background=e67e22&color=fff&size=150" style="width:150px; border-radius: 30px; margin-bottom: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                        <h3 style="margin:0">${this.state.user.name}</h3>
                        <p style="color: #27ae60; font-size: 0.85rem; font-weight: 700; margin-top:5px;"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Đang làm việc</p>
                    </div>
                    <div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                            <div><small style="color:#888">Mã nhân viên</small><p style="font-weight:700;margin:5px 0">${this.state.user.id}</p></div>
                            <div><small style="color:#888">Chức vụ</small><p style="font-weight:700;margin:5px 0">${this.state.user.role}</p></div>
                            <div><small style="color:#888">Email</small><p style="font-weight:700;margin:5px 0">${this.state.user.email}</p></div>
                            <div><small style="color:#888">Ngày gia nhập</small><p style="font-weight:700;margin:5px 0">${this.state.user.joinDate}</p></div>
                        </div>
                        <div style="background:#f8f9fa; padding:20px; border-radius:15px; display:flex; justify-content:space-around; text-align:center; margin-bottom:30px">
                            <div><h2 style="margin:0;color:var(--primary)">${this.state.user.processedOrders}</h2><small>Đơn đã xử lý</small></div>
                            <div><h2 style="margin:0;color:var(--primary)">98%</h2><small>Hiệu suất</small></div>
                        </div>
                        <button class="btn-logout-sidebar" style="width:100%; padding:15px; font-weight:bold" onclick="opsApp.handleLogout()">
                            <i class="fa-solid fa-power-off"></i> ĐĂNG XUẤT TÀI KHOẢN
                        </button>
                    </div>
                </div>
            </div>
        `;
  },

  handleLogout() {
    if (confirm("Xác nhận đăng xuất khỏi phiên làm việc hiện tại?"))
      window.location.reload();
  },

  // --- MODAL HELPERS ---
  setModal(title, content, action) {
    document.getElementById("modal-title-text").innerText = title;
    document.getElementById("modal-content-body").innerHTML = content;
    document.getElementById("modal-action-btn").onclick = action;
    document.getElementById("app-modal").classList.add("active");
  },
  closeModal() {
    document.getElementById("app-modal").classList.remove("active");
  },
  openUpdateOrderModal(id) {
    const order = this.state.orders.find((o) => o.id === id);
    const body = `<div class="form-group"><label>Trạng thái</label><select id="up-status" class="input-field"><option value="Đang xử lý" ${order.status === "Đang xử lý" ? "selected" : ""}>Đang xử lý</option><option value="Đang giao" ${order.status === "Đang giao" ? "selected" : ""}>Đang giao</option><option value="Hoàn tất" ${order.status === "Hoàn tất" ? "selected" : ""}>Hoàn tất</option></select></div><div class="form-group"><label>Mã Tracking</label><input type="text" id="up-tracking" class="input-field" value="${order.tracking}"></div>`;
    this.setModal(`Cập nhật đơn #${id}`, body, () => {
      order.status = document.getElementById("up-status").value;
      order.tracking = document.getElementById("up-tracking").value;
      this.closeModal();
      this.renderOrders();
    });
  },
  openAddProductModal() {
    const body = `<div class="form-group"><label>Mã SP</label><input type="text" id="n-id" class="input-field" placeholder="G-001"></div><div class="form-group"><label>Tên vật tư</label><input type="text" id="n-name" class="input-field" placeholder="..."></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:15px"><div class="form-group"><label>Số lượng</label><input type="number" id="n-stock" class="input-field" value="0"></div><div class="form-group"><label>Đơn vị</label><input type="text" id="n-unit" class="input-field" value="Cái"></div></div>`;
    this.setModal("Thêm vật tư mới", body, () => {
      const id = document.getElementById("n-id").value.toUpperCase();
      const name = document.getElementById("n-name").value;
      if (!id || !name) return alert("Nhập đủ thông tin!");
      this.state.inventory.push({
        id,
        name,
        stock: parseInt(document.getElementById("n-stock").value),
        unit: document.getElementById("n-unit").value,
      });
      this.closeModal();
      this.renderWarehouse();
    });
  },
};

document.addEventListener("DOMContentLoaded", () => opsApp.init());
