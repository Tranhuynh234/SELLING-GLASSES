const opsApp = {
  state: {
    orders: [],
    currentTab: "dashboard",
    searchQuery: "",
  },

  apiUrl: "index.php",

  getStatusLabel(status) {
    switch (status) {
      case "Processing":
        return "Đang xử lý";
      case "Shipped":
        return "Đang giao";
      case "Delivered":
        return "Hoàn thành";
      default:
        return status;
    }
  },

  //  1. KHỞI TẠO HỆ THỐNG
  async init() {
    await this.fetchData("all");
    this.renderTab(this.state.currentTab);
    this.bindEvents();
  },

  // 2. LOGIC LẤY DỮ LIỆU (FIXED ASYNC & ALL STATUS)
  async fetchData(status = "all") {
    try {
      const response = await fetch(
        `${this.apiUrl}?url=get-orders-by-status&status=${status}`,
      );
      const res = await response.json();

      if (res.success) {
        this.state.orders = res.data;
        return res.data; // Trả về để hàm onchange có thể await
      }
      this.state.orders = [];
      return [];
    } catch (e) {
      console.error("Lỗi fetch dữ liệu:", e);
      this.state.orders = [];
      return [];
    }
  },

  // 3. XỬ LÝ SỰ KIỆN SIDEBAR
  bindEvents() {
    document.querySelectorAll(".nav-link").forEach((link) => {
      link.onclick = async (e) => {
        e.preventDefault();
        const tab = e.currentTarget.getAttribute("data-tab");
        if (!tab || tab === "account") return;

        this.state.currentTab = tab;
        document
          .querySelectorAll(".nav-link")
          .forEach((l) => l.classList.remove("active"));
        link.classList.add("active");

        // Tải dữ liệu tương ứng tab trước khi vẽ
        if (tab === "shipping") {
          await this.fetchData("Processing");
        } else {
          await this.fetchData("all");
        }
        this.renderTab(tab);
      };
    });
  },

  // 4. ĐIỀU PHỐI GIAO DIỆN
  renderTab(tab) {
    const area = document.getElementById("render-area");
    if (!area) return;
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
      default:
        this.renderDashboard(area);
    }
  },

  // 5. GIAO DIỆN TỔNG QUAN (DASHBOARD)
  renderDashboard(el) {
    const pendingCount = this.state.orders.filter(
      (o) => o.status === "Processing",
    ).length;
    const shippedCount = this.state.orders.filter(
      (o) => o.status === "Shipped",
    ).length;
    const total = this.state.orders.length;
    const efficiency = total > 0 ? Math.round((shippedCount / total) * 100) : 0;

    el.innerHTML = `
        <div class="dashboard-container fade-in">
            <div class="stats-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:20px; margin-bottom:25px;">
                <div class="card" style="border-left:5px solid #e67e22; padding:20px; border-radius:14px; box-shadow:0 4px 14px rgba(0,0,0,0.08);">
                    <h4 style="margin:0; color:#777;">ĐƠN ĐANG XỬ LÝ</h4>
                    <h2 style="margin-top:12px; color:#e67e22; font-size:34px;">${pendingCount}</h2>
                </div>
                <div class="card" style="border-left:5px solid #3498db; padding:20px; border-radius:14px; box-shadow:0 4px 14px rgba(0,0,0,0.08);">
                    <h4 style="margin:0; color:#777;">ĐƠN ĐANG GIAO</h4>
                    <h2 style="margin-top:12px; color:#3498db; font-size:34px;">${shippedCount}</h2>
                </div>
                <div class="card" style="border-left:5px solid #27ae60; padding:20px; border-radius:14px; box-shadow:0 4px 14px rgba(0,0,0,0.08);">
                    <h4 style="margin:0; color:#777;">HIỆU SUẤT LÀM VIỆC</h4>
                    <h2 style="margin-top:12px; color:#27ae60; font-size:34px;">${efficiency}%</h2>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; align-items:start;">
                <div class="card" style="padding:20px; border-radius:14px;">
                    <h3 style="margin-bottom:20px;">Hiệu suất vận hành tuần</h3>
                    <canvas id="dashChart" height="120"></canvas>
                </div>

                <div class="card" style="padding:20px; border-radius:14px;">
                    <h3 style="margin-bottom:20px;">Lối tắt nhanh</h3>
                    <button class="btn-confirm" style="width:100%; margin-bottom:12px; background:#e67e22; color:white;"
                        onclick="opsApp.goShippingFromDashboard()">
                        Đẩy vận đơn ngay (${pendingCount})
                    </button>
                    <button style="width:100%; background:#7f8c8d; color:white; border:none; padding:14px; border-radius:10px; cursor:pointer;"
                        onclick="opsApp.fetchData('all').then(() => opsApp.renderTab('orders'))">
                        Danh sách đơn hàng
                    </button>
                </div>
            </div>
        </div>`;
    this.initChart();
  },

  async goShippingFromDashboard() {
    this.state.currentTab = "shipping";
    await this.fetchData("Processing");
    document
      .querySelectorAll(".nav-link")
      .forEach((l) => l.classList.remove("active"));
    const shippingTab = document.querySelector(
      '.nav-link[data-tab="shipping"]',
    );
    if (shippingTab) shippingTab.classList.add("active");
    this.renderTab("shipping");
  },

  // 6. DANH SÁCH ĐƠN HÀNG (FIXED FILTER & DISPLAY)
  renderOrders(el) {
    el.innerHTML = `
            <div class="warehouse-header fade-in" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2>Danh sách đơn hàng</h2>
                <div class="header-actions">
                    <select class="filter-select" onchange="(async () => { await opsApp.fetchData(this.value); opsApp.renderTab('orders'); })()" 
                        style="padding:10px; border-radius:8px; border:1px solid #ddd; font-weight:600; cursor:pointer;">
                        <option value="all">Tất cả đơn hàng</option>
                        <option value="Processing">Đang xử lý</option>
                        <option value="Shipped">Đang giao</option>
                        <option value="Delivered">Hoàn thành</option>
                    </select>
                </div>
            </div>

            <div class="card fade-in">
                <table class="table-pro">
                    <thead>
                        <tr>
                            <th>MÃ ĐƠN</th>
                            <th>PHÂN LOẠI</th>
                            <th>KHÁCH HÀNG</th>
                            <th>TỔNG TIỀN</th>
                            <th>TRẠNG THÁI</th>
                            <th style="text-align:right">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${
                          this.state.orders.length
                            ? this.state.orders
                                .map((o) => {
                                  const isCustom = o.leftEye || o.rightEye;
                                  const typeBadge = isCustom
                                    ? '<span style="color:#3498db; font-weight:700;">Gia công</span>'
                                    : '<span style="color:#9b59b6; font-weight:700;">Mua sẵn</span>';

                                  return `
                                <tr>
                                    <td><code>#${o.orderId}</code></td>
                                    <td>${typeBadge}</td>
                                    <td>${o.customerName || "Khách lẻ"}</td>
                                    <td>${Number(o.totalPrice || 0).toLocaleString("vi-VN")}đ</td>
                                    <td>
                                        <span class="badge ${o.status === "Processing" ? "bg-low" : o.status === "Shipped" ? "bg-primary" : "bg-ok"}">
                                            ${this.getStatusLabel(o.status)}
                                        </span>
                                    </td>
                                    <td style="text-align:right">
                                        <button class="btn-action" title="Chỉnh sửa" onclick="opsApp.openEditStatusModal('${o.orderId}', '${o.status}')">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>`;
                                })
                                .join("")
                            : '<tr><td colspan="6" style="text-align:center; padding:30px;">Không tìm thấy dữ liệu</td></tr>'
                        }
                    </tbody>
                </table>
            </div>`;
  },

  // 7. VẬN CHUYỂN
renderShipping(el) {
  // Lấy cả đơn hàng status 'Pending' và 'Processing'
  const pending = this.state.orders.filter(
    (o) => o.status === "Pending" || o.status === "Processing"
  );
  el.innerHTML = `
    <h2 class="fade-in" style="margin-bottom:20px;">Tạo vận đơn nhanh</h2>
    <div class="card fade-in" style="max-width:600px; margin:0 auto; padding:30px;">
      ${
        pending.length
          ? `
            <div class="form-group">
              <label><i class="fa-solid fa-receipt"></i> Chọn đơn hàng</label>
              <select id="ship-order-id" class="input-field">
                ${pending.map((o) => `<option value="${o.orderId}">Đơn #${o.orderId} - ${o.customerName || "Khách"}</option>`).join("")}
              </select>
            </div>
            <div class="form-group">
              <label><i class="fa-solid fa-truck"></i> Đối tác vận chuyển</label>
              <select id="ship-partner" class="input-field">
                <option value="GHN">Giao Hàng Nhanh (GHN)</option>
                <option value="GHTK">Giao Hàng Tiết Kiệm (GHTK)</option>
              </select>
            </div>
            <button class="btn-confirm" style="width:100%;" onclick="opsApp.handleShip()">XÁC NHẬN & XUẤT MÃ</button>
          `
          : `<div style="text-align:center; padding:30px;"><h3>Không còn đơn chờ xử lý</h3></div>`
      }
    </div>`;
},

  // 8. LOGIC XỬ LÝ (SHIP & CẬP NHẬT TRẠNG THÁI)
   async handleShip() {
  const id = document.getElementById("ship-order-id")?.value;
  const partner = document.getElementById("ship-partner")?.value;
  if (!id) return alert("Vui lòng chọn đơn hàng.");

  const track =
    partner + "-" + Math.random().toString(36).substring(2, 8).toUpperCase();
  const formData = new FormData();
  formData.append("orderId", id);
  formData.append("status", "Shipped");
  formData.append("trackingCode", track);
  formData.append("carrier", partner);

  try {
    const response = await fetch(`${this.apiUrl}?url=update-order-status`, {
      method: "POST",
      body: formData,
    });
    const res = await response.json();
    if (res.success) {
      this.setModal(
        "Xuất mã vận đơn thành công",
        `<div style="text-align:center">
          <div style="font-size:18px; margin-bottom:10px;">Mã vận đơn:</div>
          <div style="font-size:28px; font-weight:bold; color:#e67e22; margin-bottom:16px;">${track}</div>
          <div>Đơn hàng #${id} đã được chuyển sang trạng thái <b>Đang giao</b>.</div>
        </div>`,
        async () => {
          this.closeModal();
          await this.init();
        }
      );
    } else {
      alert(res.message || "Tạo vận đơn thất bại.");
    }
  } catch (e) {
    alert("Lỗi kết nối server.");
  }
},

  async handleUpdateOrder(orderId, newStatus) {
    const formData = new FormData();
    formData.append("orderId", orderId);
    formData.append("status", newStatus);
    formData.append("carrier", "GHTK");

    try {
      const response = await fetch(`${this.apiUrl}?url=update-order-status`, {
        method: "POST",
        body: formData,
      });
      const res = await response.json();
      if (res.success) {
        alert("Cập nhật thành công đơn #" + orderId);
        this.closeModal();
        await this.init(); // Refresh lại dữ liệu Dashboard & Bảng
      } else {
        alert(res.message || "Cập nhật thất bại.");
      }
    } catch (e) {
      alert("Lỗi kết nối server!");
    }
  },

  openEditStatusModal(orderId, currentStatus) {
    const body = `
            <div class="form-group" style="padding: 10px 0;">
                <label style="display:block; margin-bottom:10px; font-weight:700;">Chỉnh sửa đơn: #${orderId}</label>
                <select id="update-status-value" class="input-field" style="width:100%; padding:12px; border-radius:8px;">
                    <option value="Processing" ${currentStatus === "Processing" ? "selected" : ""}>Đang xử lý</option>
                    <option value="Shipped" ${currentStatus === "Shipped" ? "selected" : ""}>Đang giao</option>
                    <option value="Delivered" ${currentStatus === "Delivered" ? "selected" : ""}>Hoàn thành</option>
                </select>
            </div>`;
    this.setModal("Cập nhật trạng thái", body, () => {
      const newStatus = document.getElementById("update-status-value").value;
      this.handleUpdateOrder(orderId, newStatus);
    });
  },

 
  // 9. CÁC HÀM TIỆN ÍCH (SEARCH, MODAL, CHART)
  handleGlobalSearch(val) {
    this.state.searchQuery = val.toLowerCase();
  },

  handleLogout() {
    if (confirm("Bạn muốn đăng xuất?"))
      window.location.href = "index.php?url=logout";
  },

  initChart() {
    const ctx = document.getElementById("dashChart");
    if (!ctx || typeof Chart === "undefined") return;
    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["T2", "T3", "T4", "T5", "T6", "T7", "CN"],
        datasets: [
          {
            label: "Số đơn",
            data: [18, 25, 20, 35, 28, 45, 60],
            borderColor: "#e67e22",
            backgroundColor: "rgba(230,126,34,0.1)",
            fill: true,
            tension: 0.4,
          },
        ],
      },
      options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } },
      },
    });
  },

  setModal(t, b, a) {
    const title = document.getElementById("modal-title-text");
    const body = document.getElementById("modal-content-body");
    const btn = document.getElementById("modal-action-btn");
    if (title) title.innerText = t;
    if (body) body.innerHTML = b;
    if (btn) btn.onclick = a;
    document.getElementById("app-modal")?.classList.add("active");
  },

  closeModal() {
    document.getElementById("app-modal")?.classList.remove("active");
  },
};

document.addEventListener("DOMContentLoaded", () => opsApp.init());