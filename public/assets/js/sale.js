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
    const filters = document.querySelectorAll(`#${containerId} .filter-tab`);
    filters.forEach((btn) => {
      btn.onclick = function () {
        filters.forEach((b) => b.classList.remove("active"));
        this.classList.add("active");

        const statusData = this.getAttribute("data-status") || "All";
        callback(statusData);
      };
    });
  };

  /*========== 3. LOGIC GỌI API (KẾT NỐI DATABASE) ============*/

  const fetchOrders = async (status = "All") => {
    const tableBody = document.getElementById("orders-table-body");
    if (!tableBody) return;
    tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Đang tải dữ liệu...</td></tr>`;

    try {
      const response = await fetch(
        `/SELLING-GLASSES/public/index.php?url=get-orders-by-status&status=${status}`,
      );
      const result = await response.json();

      if (result.success && result.data && result.data.length > 0) {
        const config = {
          Pending: { vn: "Chờ xử lý", cls: "pending" },
          Confirmed: { vn: "Đã xác nhận", cls: "completed" },
          Processing: { vn: "Đang xử lý", cls: "pending" },
          Shipped: { vn: "Đang giao", cls: "shipping" },
          Delivered: { vn: "Hoàn tất", cls: "completed" },
          Cancelled: { vn: "Đã hủy", cls: "cancelled" },
          Returned: { vn: "Trả hàng", cls: "cancelled" },
        };

        let html = "";
        result.data.forEach((order) => {
          const statusInfo = config[order.status] || {
            vn: order.status,
            cls: "pending",
          };

          html += `
                    <tr>
                        <td>#${order.orderId}</td>
                        <td>${order.cust_name || "Khách vãng lai"}</td>
                        <td>${order.orderDate}</td>
                        <td>
                            <span class="status-badge status-${statusInfo.cls}">
                                ${statusInfo.vn}
                            </span>
                        </td>
                        <td>
                              <button class="btn-view" onclick="viewOrderDetail('${order.orderId}')">
                                  <i class="fas fa-eye"></i> Xem
                              </button>
                        </td>
                    </tr>`;
        });
        tableBody.innerHTML = html;
      } else {
        tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Không có đơn hàng nào ở trạng thái này.</td></tr>`;
      }
    } catch (error) {
      console.error("Lỗi Fetch:", error);
      tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;">Lỗi hệ thống hoặc JSON không hợp lệ!</td></tr>`;
    }
  };

  // THÊM MỚI Ở MỤC 3
  const fetchComplaints = async (type = "Tất cả") => {
    const tableBody = document.getElementById("complaint-list");
    if (!tableBody) return;

    tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Đang tải danh sách khiếu nại...</td></tr>`;

    try {
      const response = await fetch(
        `/SELLING-GLASSES/public/index.php?url=get-complaints&type=${type}`,
      );
      const result = await response.json();

      if (result.success && result.data && result.data.length > 0) {
        tableBody.innerHTML = result.data
          .map(
            (item) => `
                <tr>
                    <td>#${item.orderId}</td>
                    <td>${item.cust_name || "N/A"}</td>
                    <td>${item.reason}</td>
                    <td><span class="status-badge status-pending">${item.status}</span></td>
                    <td><button class="btn-view" onclick="viewOrderDetail('${item.orderId}')">Xem</button></td>
                </tr>
            `,
          )
          .join("");
      } else {
        tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Không có khiếu nại nào.</td></tr>`;
      }
    } catch (error) {
      tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Chưa kết nối được API khiếu nại.</td></tr>`;
    }
  };

  /*========== 4. GÁN SỰ KIỆN MENU SIDEBAR ============*/
  // Gán sự kiện cho các Menu Item
  if (btnDashboard) {
    btnDashboard.onclick = () => switchPage("dashboard-page", btnDashboard);
  }

  if (btnOrders) {
    btnOrders.onclick = () => {
      switchPage("orders-page", btnOrders);
      fetchOrders("All");
    };
  }

  if (btnPreorder) {
    btnPreorder.onclick = () => {
      switchPage("preorder-page", btnPreorder);
      // fetchPreorders("all"); // Bạn có thể viết thêm hàm này tương tự fetchOrders
    };
  }

  // Chạy các hàm setup filter MỘT LẦN DUY NHẤT khi trang load
  setupFilterEvents("orders-page", (status) => fetchOrders(status));
  setupFilterEvents("preorder-page", (status) =>
    console.log("Lọc Preorder:", status),
  );

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

  /*========== XỬ LÝ ĐƠN ============*/
  // Biến lưu trữ ID và trạng thái hiện tại của đơn hàng đang xem
  window.currentOrderId = null;
  window.currentOrderStatus = null;

  // Hàm ẩn/hiện nút dựa trên trạng thái
  const renderActionButtons = (status, isContacted) => {
    const btnConfirm = document.querySelector(".btn-confirm-order");
    const btnLogistic = document.querySelector(".btn-logistic-order");
    const btnContact = document.querySelector(".btn-contact-customer"); // Nút Liên hệ

    if (!btnConfirm || !btnLogistic || !btnContact) return;

    // Ẩn tất cả trước khi xét điều kiện
    btnConfirm.classList.add("hidden");
    btnLogistic.classList.add("hidden");
    btnContact.classList.add("hidden");

    if (status === "Pending") {
      // Đang chờ xử lý: Luôn cho phép Liên hệ
      btnContact.classList.remove("hidden");

      // CHỈ KHI đã liên hệ (isContacted == 1) mới hiện nút Xác nhận
      if (isContacted == 1) {
        btnConfirm.classList.remove("hidden");
      }
    } else if (status === "Confirmed") {
      // Đã xác nhận: Hiện nút Chuyển sang Operation (để đi giao hàng)
      btnLogistic.classList.remove("hidden");
    }
  };

  /*========== HÀM XEM CHI TIẾT ĐƠN HÀNG ============*/
  window.viewOrderDetail = async (orderId) => {
    // 1. Truy xuất các phần tử UI
    const custName = document.getElementById("custName");
    const custPhone = document.getElementById("custPhone");
    const custAddress = document.getElementById("custAddress");
    const modalBody = document.getElementById("orderDetailBody");
    const modalTitle = document.getElementById("orderDetailTitle");

    const orderModal = new bootstrap.Modal(
      document.getElementById("orderDetailModal"),
    );

    // 2. Trạng thái chờ
    if (modalTitle) modalTitle.innerText = `Chi tiết đơn hàng #${orderId}`;
    if (custName) custName.innerText = "Đang tải...";
    if (custPhone) custPhone.innerText = "Đang tải...";
    if (custAddress) custAddress.innerText = "Đang tải...";
    if (modalBody)
      modalBody.innerHTML = `<tr><td colspan="5" class="text-center">Đang tải dữ liệu...</td></tr>`;

    orderModal.show();

    try {
      const response = await fetch(
        `/SELLING-GLASSES/public/index.php?url=get-order-detail&orderId=${orderId}`,
      );
      const result = await response.json();

      if (result.success && result.data && result.data.length > 0) {
        const firstItem = result.data[0];
        const status = firstItem.status;
        const isContacted = firstItem.is_contacted; // Lấy thêm biến này

        window.currentOrderId = orderId;
        window.currentOrderStatus = status;

        // Gửi cả 2 thông tin để nút hiện đúng logic
        renderActionButtons(status, isContacted);

        // --- PHẦN TIMELINE CŨNG CẦN CHỈNH LẠI MỘT CHÚT ---
        const steps = document.querySelectorAll(".timeline-steps .step");
        steps.forEach((step) => step.classList.remove("active", "is-complete"));

        // Bước 1: Mặc định hoàn tất
        steps[0].classList.add("is-complete");

        // Bước 2: Liên hệ
        if (isContacted == 1) {
          steps[1].classList.add("is-complete");
        } else if (status === "Pending") {
          steps[1].classList.add("active");
        }

        // Bước 3: Đã xác nhận
        if (
          status === "Confirmed" ||
          status === "Processing" ||
          ["Shipped", "Delivered"].includes(status)
        ) {
          steps[1].classList.add("is-complete"); // Nếu đã xác nhận thì chắc chắn phải liên hệ rồi
          steps[2].classList.add("is-complete");
        } else if (status === "Pending" && isContacted == 1) {
          steps[2].classList.add("active"); // Đã liên hệ xong, đang chờ bấm Xác nhận
        }

        // Bước 4: Chuyển Ops (Đang xử lý)
        if (
          status === "Processing" ||
          ["Shipped", "Delivered"].includes(status)
        ) {
          steps[3].classList.add("is-complete");
        } else if (status === "Confirmed") {
          steps[3].classList.add("active"); // Đã xác nhận xong, đang chờ Chuyển Ops
        }

        if (custName) custName.innerText = firstItem.cust_name || "N/A";
        if (custPhone)
          custPhone.innerText = firstItem.cust_phone || "Chưa có SĐT";
        if (custAddress)
          custAddress.innerText = firstItem.cust_address || "Chưa có địa chỉ";

        // 3. RENDER DANH SÁCH SẢN PHẨM
        modalBody.innerHTML = result.data
          .map(
            (item) => `
                <tr style="vertical-align: middle;">
                    <td class="text-center" style="width: 80px;">
                        <img src="/SELLING-GLASSES/public/assets/images/products/${item.product_image}" 
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                    </td>
                    <td>
                        <div class="fw-bold" style="color: #333;">${item.product_name}</div>
                        <div class="text-muted" style="font-size: 0.85rem;">Màu: ${item.color} | Size: ${item.size}</div>
                    </td>
                    <td class="text-center" style="width: 60px;">
                        ${item.quantity}
                    </td>
                    <td class="text-center fw-bold" style="width: 120px; color: #2d3436;">
                        ${new Intl.NumberFormat("vi-VN").format(item.price)}đ
                    </td>
                </tr>
            `,
          )
          .join("");
      } else {
        modalBody.innerHTML = `<tr><td colspan="5" class="text-center py-4">Không tìm thấy dữ liệu sản phẩm.</td></tr>`;
      }
    } catch (error) {
      console.error("Lỗi fetch chi tiết:", error);
      modalBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">Lỗi kết nối máy chủ!</td></tr>`;
    }
  };

  /*========== HÀM GỬI CẬP NHẬT TRẠNG THÁI XUỐNG DATABASE ============*/
  window.handleUpdateStatus = async (newStatus) => {
    const orderId = window.currentOrderId;
    if (!orderId) return;

    const confirmMsg =
      newStatus === "Confirmed"
        ? "Xác nhận đơn hàng này?"
        : "Chuyển đơn hàng này sang bộ phận Operation xử lý?";

    if (!confirm(confirmMsg)) return;

    try {
      const formData = new FormData();
      formData.append("orderId", orderId);
      formData.append("status", newStatus);

      const response = await fetch(
        "/SELLING-GLASSES/public/index.php?url=update-order-status",
        {
          method: "POST",
          body: formData,
        },
      );
      const result = await response.json();

      if (result.success) {
        alert("Cập nhật thành công!");
        // Đóng modal chi tiết
        const modalEl = document.getElementById("orderDetailModal");
        bootstrap.Modal.getInstance(modalEl).hide();
        // Load lại danh sách bên ngoài để cập nhật badge màu
        fetchOrders("All");
      } else {
        alert("Lỗi: " + result.message);
      }
    } catch (error) {
      console.error("Lỗi cập nhật:", error);
      alert("Không thể kết nối máy chủ.");
    }
  };

  /*========== LIÊN HỆ KHÁCH HÀNG ============*/
  window.handleContactCustomer = function () {
    const name = document.getElementById("custName")?.innerText || "Khách hàng";
    const phone =
      document.getElementById("custPhone")?.innerText || "chưa có số";

    // 1. Lấy mã đơn hàng từ tiêu đề Modal (Ví dụ: "Chi tiết đơn hàng #8")
    const modalTitle =
      document.getElementById("orderDetailTitle")?.innerText || "";
    const orderIdDisplay =
      modalTitle.match(/#\d+/)?.[0] || `#${window.currentOrderId}`;

    // 2. Lấy tên sản phẩm đầu tiên từ danh sách sản phẩm trong Modal
    const firstProduct =
      document.querySelector("#orderDetailBody tr td div.fw-bold")?.innerText ||
      "sản phẩm";

    // 3. Tạo tin nhắn đầy đủ thông tin mã đơn và tên sản phẩm
    const message = `Chào ${name}, mình nhắn từ cửa hàng mắt kính để xác nhận đơn hàng ${orderIdDisplay} (${firstProduct}) của bạn.`;

    if (confirm(`Gửi tin nhắn đến số ${phone}:\n"${message}"?`)) {
      const formData = new FormData();
      formData.append("orderId", window.currentOrderId);
      formData.append("is_contacted", 1);

      fetch("/SELLING-GLASSES/public/index.php?url=update-order-status", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            alert("Đã cập nhật trạng thái liên hệ!");

            // --- DÒNG QUAN TRỌNG ĐỂ HẾT BỊ ĐEN MÀN HÌNH ---
            // 1. Ép đóng Modal thủ công
            const modalEl = document.getElementById("orderDetailModal");
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            // 2. Xóa sạch cái lớp phủ đen (backdrop) nếu nó còn sót lại
            document
              .querySelectorAll(".modal-backdrop")
              .forEach((el) => el.remove());
            document.body.classList.remove("modal-open");
            document.body.style.paddingRight = "";
            // ----------------------------------------------

            // Load lại danh sách đơn hàng
            window.viewOrderDetail(window.currentOrderId);
          } else {
            alert("Lỗi cập nhật: " + data.message);
          }
        })
        .catch((err) => console.error("Lỗi:", err));
    }
  };

  /*========== 6. KHỞI TẠO MẶC ĐỊNH ============*/
  switchPage("dashboard-page", btnDashboard);
});
