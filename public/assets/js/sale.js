document.addEventListener("DOMContentLoaded", () => {
  /*========== 1. KHAI BÁO BIẾN TOÀN CỤC & TRUY XUẤT PHẦN TỬ ============*/
  const sidebar = document.querySelector(".sidebar");
  const btnToggle = document.getElementById("sidebar-close");

  const btnDashboard = document.getElementById("menu-dashboard");
  const btnOrders = document.getElementById("menu-orders");
  const btnPreorder = document.getElementById("menu-preorder");
  const btnComplaints = document.getElementById("menu-complaints");

  const allSections = document.querySelectorAll(".dashboard-body");
  const allMenuItems = document.querySelectorAll(".menu-item");

  /*========== 2. HÀM BỔ TRỢ (HELPER FUNCTIONS) ============*/

  const switchPage = (targetPageId, activeBtn) => {
    allSections.forEach((section) => section.classList.add("hidden"));
    allMenuItems.forEach((item) => item.classList.remove("active"));

    const targetSection = document.getElementById(targetPageId);
    if (targetSection && activeBtn) {
      targetSection.classList.remove("hidden");
      activeBtn.classList.add("active");
    }
  };

  const renderTable = (elementId, data, templateRowFn) => {
    const tableBody = document.getElementById(elementId);
    if (!tableBody) return;

    if (!data || data.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="10" style="text-align:center;">Chưa có dữ liệu hiển thị</td></tr>`;
      return;
    }
    tableBody.innerHTML = data.map(templateRowFn).join("");
  };

  const setupFilterEvents = (containerId, callback) => {
    const filters = document.querySelectorAll(
      `#${containerId} .filter-tab, #${containerId} .filter-bar`,
    );
    filters.forEach((btn) => {
      btn.onclick = function () {
        filters.forEach((b) => b.classList.remove("active"));
        this.classList.add("active");

        const status = this.innerText.trim();
        callback(status);
      };
    });
  };

  /*========== 3. LOGIC GỌI API (KẾT NỐI DATABASE) ============*/

  // Hàm fetch cho Đơn hàng
  const fetchOrders = async (status = "Tất cả") => {
    console.log(`Đang gọi API lấy ĐƠN HÀNG trạng thái: ${status}`);
    // Code fetch thật sẽ nằm ở đây
  };

  // Hàm fetch cho Khiếu nại/Bảo hành
  const fetchComplaints = async (type = "Tất cả") => {
    console.log(`Đang gọi API lấy KHIẾU NẠI loại: ${type}`);
    // Sau này bạn fetch từ bảng 'complaints' hoặc 'warranty' trong DB
  };

  /*========== 4. GÁN SỰ KIỆN MENU SIDEBAR ============*/

  if (btnDashboard) {
    btnDashboard.addEventListener("click", () => {
      switchPage("dashboard-page", btnDashboard);
    });
  }

  if (btnOrders) {
    btnOrders.addEventListener("click", () => {
      switchPage("orders-page", btnOrders);
      setupFilterEvents("orders-page", fetchOrders);
      fetchOrders();
    });
  }

  if (btnPreorder) {
    btnPreorder.addEventListener("click", () => {
      switchPage("preorder-page", btnPreorder);
      setupFilterEvents("preorder-page", (status) =>
        console.log("Lọc Pre-order:", status),
      );
    });
  }

  if (btnComplaints) {
    btnComplaints.addEventListener("click", () => {
      switchPage("complaint-page", btnComplaints);
      // Thiết lập bộ lọc cho trang Khiếu nại (Đổi trả, Bảo hành, Khiếu nại...)
      setupFilterEvents("complaint-page", fetchComplaints);
      fetchComplaints(); // Load mặc định
    });
  }

  /*========== 5. CÁC TIỆN ÍCH HỆ THỐNG ============*/

  if (btnToggle && sidebar) {
    btnToggle.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
    });
  }

  const autoGenerateAvatar = () => {
    const nameElement = document.getElementById("user-footer-name");
    const initialsElement = document.getElementById("user-initials");
    if (!nameElement || !initialsElement) return;

    const fullName = nameElement.innerText.trim();
    const words = fullName.split(" ");
    let initials =
      words.length >= 2
        ? words[0].charAt(0) + words[words.length - 1].charAt(0)
        : words[0].substring(0, 2);

    initialsElement.innerText = initials.toUpperCase();
  };

  autoGenerateAvatar();

  /*========== 6. KHỞI TẠO MẶC ĐỊNH ============*/
  switchPage("dashboard-page", btnDashboard);
});
