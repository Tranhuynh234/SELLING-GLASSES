document.addEventListener("DOMContentLoaded", () => {
  /*========== KHAI BÁO BIẾN TOÀN CỤC & TRUY XUẤT PHẦN TỬ ============*/
  const sidebar = document.querySelector(".sidebar");
  const btnToggle = document.getElementById("sidebar-close");

  // Các nút Menu Sidebar
  const btnDashboard = document.getElementById("menu-dashboard");
  const btnOrders = document.getElementById("menu-orders");
  const btnPreorder = document.getElementById("menu-preorder");
  const btnComplaints = document.getElementById("menu-complaints");

  const allSections = document.querySelectorAll(".dashboard-body");
  const allMenuItems = document.querySelectorAll(".menu-item");

  /*========== DỮ LIỆU MẪU ĐỂ TEST TRANG GIAO DIỆN ============*/
  const recentOrders = [
    {
      id: "ORD-8830",
      customer: "Trần Thị Mai",
      date: "2025-01-16",
      status: "Chờ xử lý",
      color: "#e25656",
    },
    {
      id: "ORD-8828",
      customer: "Lê Văn An",
      date: "2025-02-16",
      status: "Đang giao",
      color: "#f39c12",
    },
    {
      id: "ORD-8826",
      customer: "Nguyễn Văn Nam",
      date: "2025-01-01",
      status: "Hoàn tất",
      color: "#2ecc71",
    },
    {
      id: "ORD-8825",
      customer: "Đinh Hoàng Vũ",
      date: "2024-02-01",
      status: "Đã xác nhận",
      color: "#3498db",
    },
    {
      id: "ORD-8830",
      customer: "Trần Thị Mai",
      date: "2025-01-16",
      status: "Chờ xử lý",
      color: "#e25656",
    },
    {
      id: "ORD-8828",
      customer: "Lê Văn An",
      date: "2025-02-16",
      status: "Đang giao",
      color: "#f39c12",
    },
    {
      id: "ORD-8826",
      customer: "Nguyễn Văn Nam",
      date: "2025-01-01",
      status: "Hoàn tất",
      color: "#2ecc71",
    },
    {
      id: "ORD-8825",
      customer: "Đinh Hoàng Vũ",
      date: "2024-02-01",
      status: "Đã xác nhận",
      color: "#3498db",
    },
  ];

  const allOrders = [
    {
      id: "ORD-8830",
      customer: "Trần Thị Mai",
      date: "2025-01-16",
      status: "Chờ xử lý",
      color: "#e25656",
    },
    {
      id: "ORD-8828",
      customer: "Lê Văn An",
      date: "2025-02-16",
      status: "Đang giao",
      color: "#f39c12",
    },
    {
      id: "ORD-8826",
      customer: "Nguyễn Văn Nam",
      date: "2025-01-01",
      status: "Hoàn tất",
      color: "#2ecc71",
    },
    {
      id: "ORD-8825",
      customer: "Đinh Hoàng Vũ",
      date: "2024-02-01",
      status: "Đã xác nhận",
      color: "#3498db",
    },
    {
      id: "ORD-8830",
      customer: "Trần Thị Mai",
      date: "2025-01-16",
      status: "Chờ xử lý",
      color: "#e25656",
    },
    {
      id: "ORD-8828",
      customer: "Lê Văn An",
      date: "2025-02-16",
      status: "Đang giao",
      color: "#f39c12",
    },
    {
      id: "ORD-8826",
      customer: "Nguyễn Văn Nam",
      date: "2025-01-01",
      status: "Hoàn tất",
      color: "#2ecc71",
    },
    {
      id: "ORD-8825",
      customer: "Đinh Hoàng Vũ",
      date: "2024-02-01",
      status: "Đã xác nhận",
      color: "#3498db",
    },
  ];
  const preorderOrders = [
    {
      id: "ORD-8830",
      customer: "Trần Thị Mai",
      date: "2025-01-16",
      status: "cho-ve",
      hasIcon: true,
    },
    {
      id: "ORD-8835",
      customer: "Lê Văn An",
      date: "2025-02-16",
      status: "sap-co",
      hasIcon: true,
    },
    {
      id: "ORD-8840",
      customer: "Nguyễn Thị Kim",
      date: "2025-02-01",
      status: "da-co",
      hasIcon: true,
    },
    {
      id: "ORD-8842",
      customer: "Đặng Văn B",
      date: "2025-01-28",
      status: "tre-hang",
      hasIcon: false,
    },
  ];

  const complaintOrders = [
    {
      id: "ORD-8830",
      customer: "Trần Thị Mai",
      reason: "PD cao",
      status: "Đổi trả",
    },
    {
      id: "ORD-8835",
      customer: "Lê Văn An",
      reason: "Delay Pre-order",
      status: "Đổi trả",
    },
    {
      id: "ORD-8840",
      customer: "Nguyễn Thị Kim",
      reason: "Bảo hành gọng gãy",
      status: "Bảo hành",
    },
    {
      id: "ORD-8842",
      customer: "Nguyễn Thị La",
      reason: "Hoàn tiền",
      status: "Khiếu nại",
    },
    {
      id: "ORD-8855",
      customer: "Trần Mai Anh",
      reason: "Cắt kính sai",
      status: "Khiếu nại",
    },
  ];

  /*========== HÀM HIỂN THỊ DỮ LIỆU (RENDER) ============*/
  const renderRecentOrders = () => {
    const tableBody = document.getElementById("recent-orders");
    if (!tableBody) return;
    tableBody.innerHTML = recentOrders
      .map(
        (order) => `
      <tr>
        <td><strong>${order.id}</strong></td>
        <td>${order.customer}</td>
        <td>${order.date}</td>
        <td>${order.status}</td>
        <td><button class="btn-view">Xem chi tiết</button></td>
      </tr>
    `,
      )
      .join("");

    const detailButtons = tableBody.querySelectorAll(".btn-go-to-orders");
    detailButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        switchPage("orders-page", btnOrders);
        renderAllOrders();
        setupOrdersFilter();
      });
    });
  };

  const renderAllOrders = (data = allOrders, searchId = "") => {
    const tableBody = document.getElementById("all-orders-list");
    if (!tableBody) return;

    const displayData = searchId
      ? data.filter((item) => item.id === searchId)
      : data;

    tableBody.innerHTML = data
      .map(
        (order) => `
      <tr>
        <td><strong>${order.id}</strong></td>
        <td>${order.customer}</td>
        <td>${order.date}</td>
        <td>${order.status}</td>
        <td>
          <button class="btn-view">Xem chi tiết</button>
        </td>
      </tr>
    `,
      )
      .join("");
  };

  const renderPreorders = (data = preorderOrders) => {
    const tableBody = document.getElementById("preorder-list");
    if (!tableBody) return;

    const statusMap = {
      "cho-ve": { text: "Chờ về hàng" },
      "sap-co": { text: "Sắp có hàng" },
      "da-co": { text: "Đã có hàng" },
      "tre-hang": { text: "Trễ hàng" },
    };

    tableBody.innerHTML = data
      .map((order) => {
        const s = statusMap[order.status];
        return `
    <tr>
      <td><strong>${order.id}</strong></td>
      <td>${order.customer}</td>
      <td>
        <div class="date-status">
          ${order.date} 
        </div>
      </td>
      <td>
        <span class="status-label" >
          ${s.text}
        </span>
      </td>
      <td>
        <div class="action-btn-group">
          <button class="btn-call" onclick="alert('Đang gọi cho: ${order.customer}')">
            <i class="fas fa-phone-alt"></i> Liên hệ
          </button>
          <button class="btn-reconfirm" onclick="alert('Xác nhận đơn ${order.id}')">
            <i class="fas fa-check"></i> Xác nhận
          </button>
        </div>
      </td>
    </tr>`;
      })
      .join("");
  };

  // --- RENDER TRANG KHIẾU NẠI ---
  const renderComplaints = (data = complaintOrders) => {
    const tableBody = document.getElementById("complaint-list");
    if (!tableBody) return;

    tableBody.innerHTML = data
      .map(
        (item) => `
    <tr>
      <td><strong>${item.id}</strong></td>
      <td>${item.customer}</td>
      <td>
        ${item.reason}
      </td>
      <td>
          ${item.status}
        </span>
      </td>
      <td>
        <button class="btn-view" onclick="alert('Xem khiếu nại: ${item.id}')">Xem chi tiết</button>
      </td>
    </tr>
  `,
      )
      .join("");
  };

  // --- LOGIC BỘ LỌC CHO DANH SÁCH ĐƠN ---
  const setupOrdersFilter = () => {
    const orderFilters = document.querySelectorAll(
      "#orders-page .filter-tab, #orders-page .filter-bar",
    );

    orderFilters.forEach((btn) => {
      btn.addEventListener("click", function () {
        orderFilters.forEach((b) => b.classList.remove("active"));
        this.classList.add("active");

        const statusText = this.innerText.trim();

        const filteredData =
          statusText === "Tất cả"
            ? allOrders
            : allOrders.filter((item) => item.status === statusText);

        renderAllOrders(filteredData);
      });
    });
  };

  // --- LOGIC BỘ LỌC (FILTER) ---
  const setupPreorderFilter = () => {
    const filterButtons = document.querySelectorAll(
      "#preorder-page .filter-bar, #preorder-page .filter-tab",
    );

    filterButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        filterButtons.forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
        const status = this.getAttribute("data-status");
        const filteredData =
          status === "all"
            ? preorderOrders
            : preorderOrders.filter((item) => item.status === status);

        renderPreorders(filteredData);
      });
    });
  };

  // --- LOGIC BỘ LỌC KHIẾU NẠI ---
  const setupComplaintFilter = () => {
    const filterTabs = document.querySelectorAll("#complaint-page .filter-tab");
    filterTabs.forEach((tab) => {
      tab.addEventListener("click", function () {
        filterTabs.forEach((t) => t.classList.remove("active"));
        this.classList.add("active");

        const type = this.innerText.trim();
        const filtered =
          type === "Tất cả"
            ? complaintOrders
            : complaintOrders.filter(
                (item) =>
                  item.reason.includes(type) || item.status.includes(type),
              );

        renderComplaints(filtered);
      });
    });
  };

  /*========== XỬ LÝ CHUYỂN ĐỔI MÀN HÌNH (FIX CHỒNG LẤN) ============*/
  const switchPage = (targetPageId, activeBtn) => {
    allSections.forEach((section) => section.classList.add("hidden"));
    allMenuItems.forEach((item) => item.classList.remove("active"));

    const targetSection = document.getElementById(targetPageId);
    if (targetSection) {
      targetSection.classList.remove("hidden");
      activeBtn.classList.add("active");
    }
  };

  if (btnDashboard) {
    btnDashboard.addEventListener("click", () => {
      switchPage("dashboard-page", btnDashboard);
    });
  }

  if (btnOrders) {
    btnOrders.addEventListener("click", () => {
      switchPage("orders-page", btnOrders);
      renderAllOrders();
      setupOrdersFilter();
    });
  }

  if (btnPreorder) {
    btnPreorder.addEventListener("click", () => {
      switchPage("preorder-page", btnPreorder);
      renderPreorders();
      setupPreorderFilter();
    });
  }

  if (btnComplaints) {
    btnComplaints.addEventListener("click", () => {
      switchPage("complaint-page", btnComplaints);
      renderComplaints();
      setupComplaintFilter();
    });
  }

  /*========== CÁC CHỨC NĂNG KHÁC ============*/
  // Sidebar Toggle
  if (btnToggle && sidebar) {
    btnToggle.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
    });
  }

  /*========== HÀM TỰ ĐỘNG TẠO AVATAR TỪ TÊN ============*/
  const autoGenerateAvatar = () => {
    const nameElement = document.getElementById("user-footer-name");
    const initialsElement = document.getElementById("user-initials");

    if (!nameElement || !initialsElement) return;
    const fullName = nameElement.innerText.trim();
    const words = fullName.split(" ");
    let initials = "";

    if (words.length >= 2) {
      initials = words[0].charAt(0) + words[words.length - 1].charAt(0);
    } else if (words.length === 1) {
      initials = words[0].substring(0, 2);
    }

    initialsElement.innerText = initials.toUpperCase();
  };

  autoGenerateAvatar();

  /*========== KHỞI TẠO MẶC ĐỊNH ============*/
  switchPage("dashboard-page", btnDashboard);
  renderRecentOrders();
});
