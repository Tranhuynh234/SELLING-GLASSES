document.addEventListener("DOMContentLoaded", () => {
  const $id = (id) => document.getElementById(id);
  const $q = (selector) => document.querySelector(selector);
  const $qa = (selector) => document.querySelectorAll(selector);
  const ajaxJson = async (url, options) => {
    const response = await fetch(url, options);
    const text = await response.text();
    return text.trim().startsWith("{")
      ? JSON.parse(text)
      : { success: false, message: "Invalid JSON", raw: text };
  };

  const sidebar = $q(".sidebar");
  const btnToggle = $id("sidebar-close");
  const btnDashboard = $id("menu-dashboard");
  const btnOrders = $id("menu-orders");
  const btnPreorder = $id("menu-preorder");
  const btnComplaints = $id("menu-complaints");
  const allSections = $qa(".dashboard-body");
  const allMenuItems = $qa(".menu-item");

  /* CÁC HÀM HỖ TRỢ  */
  const switchPage = (pageId, activeBtn) => {
    allSections.forEach((section) => section.classList.add("hidden"));
    allMenuItems.forEach((item) => item.classList.remove("active"));
    const page = $id(pageId);
    if (!page || !activeBtn) return;
    page.classList.remove("hidden");
    activeBtn.classList.add("active");
  };

  /* XỬ LÝ FILTER */
  const setupFilterEvents = (containerId, callback) => {
    const filters = document.querySelectorAll(`#${containerId} .filter-tab`);
    filters.forEach((btn) => {
      btn.onclick = function () {
        filters.forEach((item) => item.classList.remove("active"));
        this.classList.add("active");
        callback(this.dataset.status || "All");
      };
    });
  };

  const setOrderFilter = (status) => {
    const filters = document.querySelectorAll("#orders-page .filter-tab");
    filters.forEach((btn) => {
      if (
        btn.dataset.status?.toString().toLowerCase() ===
        status.toString().toLowerCase()
      ) {
        btn.classList.add("active");
      } else {
        btn.classList.remove("active");
      }
    });
  };

  const syncOrderUI = (status, isContacted) => {
    const btnConfirm = $q(".btn-confirm-order");
    const btnLogistic = $q(".btn-logistic-order");
    if (!btnConfirm || !btnLogistic) return;
    btnConfirm.classList.add("hidden");
    btnLogistic.classList.add("hidden");
    if (status === "Pending" && Number(isContacted) === 1)
      btnConfirm.classList.remove("hidden");
    else if (status === "Confirmed") btnLogistic.classList.remove("hidden");
  };

  const orderStatusLabels = {
    Pending: { vn: "Chờ xử lý", cls: "pending" },
    Confirmed: { vn: "Đã xác nhận", cls: "completed" },
    Processing: { vn: "Đang xử lý", cls: "pending" },
    Shipped: { vn: "Đang giao", cls: "shipping" },
    Delivered: { vn: "Hoàn tất", cls: "completed" },
    Cancelled: { vn: "Đã hủy", cls: "cancelled" },
    Returned: { vn: "Trả hàng", cls: "cancelled" },
  };

  const complaintStatusLabels = {
    Pending: { vn: "Chờ xử lý", cls: "warning" },
    Approved: { vn: "Đã duyệt", cls: "success" },
    Rejected: { vn: "Bị từ chối", cls: "danger" },
    Completed: { vn: "Hoàn tất", cls: "success" },
  };

  const normalizeComplaintType = (type) => {
    const key = (type || "all").toString().trim().toLowerCase();
    if (["khiếu nại", "khieu nai", "complaint", "complaint"]?.includes(key))
      return "complaint";
    if (["đổi trả", "doi tra", "doi trả", "return", "return"]?.includes(key))
      return "return";
    return "all";
  };

  const formatPrice = (value) =>
    new Intl.NumberFormat("vi-VN").format(value) + "đ";
  const renderRows = (data, rowFn) => data.map(rowFn).join("");

  /* API: ORDERS / COMPLAINTS */
  const fetchOrders = async (status = "All") => {
    const body = $id("orders-table-body");
    if (!body) return;
    body.innerHTML = `<tr><td colspan="5" style="text-align:center;">Đang tải dữ liệu...</td></tr>`;
    try {
      const result = await ajaxJson(
        `/SELLING-GLASSES/public/index.php?url=get-orders-by-status&status=${status}`,
      );
      if (result.success && result.data?.length) {
        body.innerHTML = renderRows(result.data, (order) => {
          const statusInfo = orderStatusLabels[order.status] || {
            vn: order.status,
            cls: "pending",
          };
          const customer =
            order.cust_name ||
            order.customerName ||
            order.customer_name ||
            "Khách vãng lai";
          return `
            <tr>
              <td>#${order.orderId}</td>
              <td>${customer}</td>
              <td>${order.orderDate}</td>
              <td><span class="status-badge status-${statusInfo.cls}">${statusInfo.vn}</span></td>
              <td><button class="btn-view" onclick="viewOrderDetail('${order.orderId}')"><i class="fas fa-eye"></i> Xem</button></td>
            </tr>`;
        });
        setupSearchTable("search-customer", "orders-table-body");
      } else {
        body.innerHTML = `<tr><td colspan="5" style="text-align:center;">Không có đơn hàng nào ở trạng thái này.</td></tr>`;
      }
    } catch (error) {
      console.error("Lỗi Fetch:", error);
      body.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;">Lỗi hệ thống hoặc JSON không hợp lệ!</td></tr>`;
    }
  };

  const renderRecentOrders = (orders) => {
    const body = $id("recent-orders");
    if (!body) return;
    const rows = orders
      .sort((a, b) => new Date(b.orderDate) - new Date(a.orderDate))
      .slice(0, 5)
      .map((order) => {
        const customer =
          order.cust_name ||
          order.customerName ||
          order.customer_name ||
          "Khách vãng lai";
        const statusInfo = orderStatusLabels[order.status] || {
          vn: order.status,
          cls: "pending",
        };
        return `
          <tr>
            <td>#${order.orderId}</td>
            <td>${customer}</td>
            <td>${order.orderDate}</td>
            <td><span class="status-badge status-${statusInfo.cls}">${statusInfo.vn}</span></td>
            <td><button class="btn-view" onclick="viewOrderDetail('${order.orderId}')"><i class="fas fa-eye"></i> Xem</button></td>
          </tr>`;
      })
      .join("");

    body.innerHTML =
      rows ||
      `<tr><td colspan="5" style="text-align:center;">Chưa có đơn hàng mới.</td></tr>`;
  };

  const fetchDashboard = async () => {
    const revenueEl = $id("dashboard-revenue-value");
    const revenueTrend = $id("dashboard-revenue-trend");
    const newOrdersEl = $id("dashboard-new-orders-value");
    const newOrdersTrend = $id("dashboard-new-orders-trend");
    const preorderEl = $id("dashboard-preorder-value");
    const complaintsEl = $id("dashboard-complaints-value");

    const today = new Date().toISOString().slice(0, 10);
    let orders = [];
    let complaints = [];

    try {
      const ordersResult = await ajaxJson(
        "/SELLING-GLASSES/public/index.php?url=get-orders-by-status&status=All",
      );
      if (ordersResult.success && ordersResult.data) {
        orders = ordersResult.data;
      }
    } catch (err) {
      console.error("Lỗi tải đơn hàng dashboard:", err);
    }

    try {
      const complaintsResult = await ajaxJson(
        "/SELLING-GLASSES/public/index.php?url=get-complaints&type=all",
      );
      if (complaintsResult.success && complaintsResult.data) {
        complaints = complaintsResult.data;
      }
    } catch (err) {
      console.error("Lỗi tải khiếu nại dashboard:", err);
    }

    const todayOrders = orders.filter((order) =>
      order.orderDate?.startsWith(today),
    );
    const revenueToday = todayOrders.reduce(
      (sum, order) => sum + Number(order.totalPrice || 0),
      0,
    );
    const newOrdersCount = orders.filter(
      (order) => order.status === "Pending",
    ).length;
    const pendingComplaints = complaints.filter(
      (item) => item.status !== "Completed",
    ).length;

    if (revenueEl)
      revenueEl.innerHTML = `
        <span><i class="fas fa-sack-dollar"></i></span>
        ${new Intl.NumberFormat("vi-VN").format(revenueToday)} đ`;
    if (revenueTrend)
      revenueTrend.innerHTML = `
        <i class="fas fa-caret-up"></i> +${
          todayOrders.length > 0
            ? Math.round((todayOrders.length / (orders.length || 1)) * 100)
            : 0
        }%`;
    if (newOrdersEl)
      newOrdersEl.innerHTML = `
        <span><i class="fas fa-box"></i></span>
        ${newOrdersCount} đơn`;
    if (newOrdersTrend)
      newOrdersTrend.innerHTML = `
        <i class="fas fa-caret-${newOrdersCount > 0 ? "up" : "down"}"></i> ${
          newOrdersCount > 0 ? "+" : "-"
        }${newOrdersCount}%`;
    if (preorderEl)
      preorderEl.innerHTML = `
        <span><i class="fas fa-hourglass"></i></span>
        0 đơn`;
    if (complaintsEl)
      complaintsEl.innerHTML = `
        <span><i class="fas fa-exclamation-triangle"></i></span>
        ${pendingComplaints} đơn`;

    renderRecentOrders(orders);
  };

  const fetchComplaints = async (type = "all") => {
    const body = $id("complaint-list");
    if (!body) return;
    const normalizedType = normalizeComplaintType(type);
    body.innerHTML = `<tr><td colspan="5" style="text-align:center;">Đang tải danh sách khiếu nại...</td></tr>`;
    try {
      const result = await ajaxJson(
        `/SELLING-GLASSES/public/index.php?url=get-complaints&type=${normalizedType}`,
      );
      if (result.success && result.data?.length) {
        body.innerHTML = renderRows(result.data, (item) => {
          const statusInfo = complaintStatusLabels[item.status] || {
            vn: item.label_status || item.status,
            cls: item.status === "Completed" ? "success" : "pending",
          };
          const customer = item.cust_name || "Khách vãng lai";
          const safeReason = item.reason.replace(/'/g, "\\'");
          const safeNote = (item.note || "").replace(/'/g, "\\'");
          const safeImage = (item.imagePath || "").replace(/'/g, "\\'");
          return `
            <tr>
              <td>#${item.orderId}</td>
              <td>${customer}</td>
              <td>${item.reason}</td>
              <td><span class="status-badge status-${statusInfo.cls}">${statusInfo.vn}</span></td>
              <td>
                <button class="btn-view" onclick="viewComplaintOrderDetail('${item.orderId}','${item.returnId}','${item.request_type}','${safeReason}','${item.status}','${item.label_status}','${safeNote}','${safeImage}')"><i class="fas fa-eye"></i> Xem</button>
              </td>
            </tr>`;
        });
        setupSearchTable("search-complaint", "complaint-list");
      } else {
        body.innerHTML = `<tr><td colspan="5" style="text-align:center;">Không có khiếu nại nào.</td></tr>`;
      }
    } catch (error) {
      console.error("Lỗi khi tải khiếu nại:", error);
      body.innerHTML = `<tr><td colspan="5" style="text-align:center;">Chưa kết nối được API khiếu nại.</td></tr>`;
    }
  };

  /* SỰ KIỆN UI */
  btnDashboard?.addEventListener("click", async () => {
    switchPage("dashboard-page", btnDashboard);
    await fetchDashboard();
  });
  btnOrders?.addEventListener("click", () => {
    switchPage("orders-page", btnOrders);
    fetchOrders("All");
  });
  btnPreorder?.addEventListener("click", () => {
    switchPage("preorder-page", btnPreorder);
    fetchPreorders("All");
  });
  btnComplaints?.addEventListener("click", () => {
    switchPage("complaint-page", btnComplaints);
    fetchComplaints("all");
  });

  setupFilterEvents("orders-page", fetchOrders);
  setupFilterEvents("preorder-page", (status) =>
    console.log("Lọc Preorder:", status),
  );
  setupFilterEvents("complaint-page", fetchComplaints);

  btnToggle?.addEventListener("click", () =>
    sidebar?.classList.toggle("collapsed"),
  );

  /*  CÁC HÀM HỖ TRỢ  */
  const autoGenerateAvatar = () => {
    const nameEl = $id("user-footer-name");
    const initialsEl = $id("user-initials");
    if (!nameEl || !initialsEl) return;
    const words = nameEl.innerText.trim().split(" ");
    const initials =
      words.length >= 2
        ? words[0][0] + words[words.length - 1][0]
        : words[0].slice(0, 2);
    initialsEl.innerText = initials.toUpperCase();
  };

  autoGenerateAvatar();
  fetchDashboard();

  window.currentOrderId = null;
  window.currentOrderStatus = null;
  window.currentRequestContext = null;
  window.chattingOrderId = null;

  /*  CHI TIẾT ĐƠN HÀNG */
  window.viewOrderDetail = async (orderId, requestContext = null) => {
    window.currentOrderId = orderId;
    window.currentRequestContext = requestContext;
    const custName = $id("custName");
    const custPhone = $id("custPhone");
    const custAddress = $id("custAddress");
    const modalBody = $id("orderDetailBody");
    const modalTitle = $id("orderDetailTitle");
    const orderModalEl = $id("orderDetailModal");
    if (!orderModalEl) return;
    bootstrap.Modal.getOrCreateInstance(orderModalEl).show();
    modalTitle &&
      (modalTitle.innerText = `Chi tiết đơn hàng #${orderId}${requestContext ? ` - ${requestContext.request_type === "complaint" ? "Khiếu nại" : "Đổi trả"}` : ""}`);
    custName && (custName.innerText = "Đang tải...");
    custPhone && (custPhone.innerText = "Đang tải...");
    custAddress && (custAddress.innerText = "Đang tải...");
    modalBody &&
      (modalBody.innerHTML = `<tr><td colspan="5" class="text-center">Đang tải dữ liệu...</td></tr>`);
    try {
      const result = await ajaxJson(
        `/SELLING-GLASSES/public/index.php?url=get-order-detail&orderId=${orderId}`,
      );
      if (result.success && result.data?.length) {
        const detail = result.data[0];
        custName && (custName.innerText = detail.cust_name || "N/A");
        custPhone && (custPhone.innerText = detail.cust_phone || "N/A");
        custAddress && (custAddress.innerText = detail.cust_address || "N/A");
        syncOrderUI(detail.status, detail.is_contacted);
        modalBody.innerHTML = renderRows(
          result.data,
          (item) => {
            const isCombo = item.itemType === 'combo' || item.comboId;
            const badgeHtml = isCombo ? '<span style="display:inline-block;background:#b45309;color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;margin-right:4px;">COMBO</span>' : '';
            const detailHtml = isCombo ? '' : `<div class="text-muted" style="font-size: 0.85rem;">Màu: ${item.color || '-'} | Size: ${item.size || '-'}</div>`;
            return `
            <tr style="vertical-align: middle;">
              <td class="text-center" style="width: 80px;"><img src="/SELLING-GLASSES/public/assets/images/products/${item.product_image}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;"></td>
              <td><div class="fw-bold" style="color: #333;">${badgeHtml}${item.product_name}</div>${detailHtml}</td>
              <td class="text-center">${item.quantity}</td>
              <td class="text-center fw-bold" style="color: #2d3436;">${formatPrice(item.price)}</td>
            </tr>`;
          },
        );
        renderComplaintRequestPanel(requestContext);
      } else {
        modalBody &&
          (modalBody.innerHTML = `<tr><td colspan="5" class="text-center py-4">Không tìm thấy dữ liệu.</td></tr>`);
        renderComplaintRequestPanel(null);
      }
    } catch (error) {
      console.error("Lỗi fetch chi tiết:", error);
      modalBody &&
        (modalBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">Lỗi kết nối máy chủ!</td></tr>`);
      renderComplaintRequestPanel(null);
    }
  };

  const renderComplaintRequestPanel = (requestContext) => {
    const requestBox = $id("requestContextBox");
    const requestTypeLabel = $id("requestTypeLabel");
    const requestReason = $id("requestReason");
    const requestNote = $id("requestNote");
    const requestStaffLabel = $id("requestStaffLabel");
    const requestStatusLabel = $id("requestStatusLabel");
    const requestImageContainer = $id("requestImageContainer");
    const requestImagePreview = $id("requestImagePreview");
    const requestButton = $id("btn-request-action");

    if (
      !requestBox ||
      !requestTypeLabel ||
      !requestReason ||
      !requestNote ||
      !requestStaffLabel ||
      !requestStatusLabel ||
      !requestImageContainer ||
      !requestImagePreview ||
      !requestButton
    )
      return;

    if (!requestContext) {
      requestBox.classList.add("hidden");
      requestButton.classList.add("hidden");
      requestTypeLabel.innerText = "-";
      requestReason.innerText = "-";
      requestNote.innerText = "-";
      requestStaffLabel.innerText = "-";
      requestStatusLabel.innerText = "-";
      requestImageContainer.classList.add("hidden");
      requestImagePreview.src = "";
      return;
    }

    requestBox.classList.remove("hidden");
    requestTypeLabel.innerText =
      requestContext.request_type === "complaint" ? "Khiếu nại" : "Đổi trả";
    requestReason.innerText = requestContext.reason || "-";
    requestNote.innerText = requestContext.note || "-";
    requestStaffLabel.innerText =
      requestContext.staff_name || requestContext.staffId || "-";
    requestStatusLabel.innerText =
      requestContext.label_status || requestContext.status || "Pending";

    if (requestContext.imagePath) {
      requestImageContainer.classList.remove("hidden");
      requestImagePreview.src = requestContext.imagePath.startsWith("http")
        ? requestContext.imagePath
        : `/SELLING-GLASSES/public/${requestContext.imagePath}`;
    } else {
      requestImageContainer.classList.add("hidden");
      requestImagePreview.src = "";
    }

    if (requestContext.status === "Pending") {
      requestButton.classList.remove("hidden");
      requestButton.innerHTML =
        requestContext.request_type === "complaint"
          ? '<i class="fas fa-check"></i> Chấp nhận Khiếu nại'
          : '<i class="fas fa-undo"></i> Xác nhận Trả hàng';
      requestButton.onclick = () =>
        processComplaintRequest(
          requestContext.returnId,
          requestContext.orderId,
          requestContext.request_type,
        );
    } else {
      requestButton.classList.add("hidden");
    }
  };

  window.viewComplaintOrderDetail = (
    orderId,
    returnId,
    requestType,
    reason,
    status,
    labelStatus,
    note,
    imagePath,
  ) => {
    viewOrderDetail(orderId, {
      returnId,
      orderId,
      request_type: requestType,
      reason,
      status,
      label_status: labelStatus,
      note,
      imagePath,
    });
  };

  /* CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG  */
  window.handleUpdateStatus = async (newStatus) => {
    const orderId = window.currentOrderId;
    if (!orderId) return;
    if (
      !confirm(
        newStatus === "Confirmed"
          ? "Xác nhận đơn hàng này?"
          : "Chuyển đơn hàng này sang bộ phận Operation xử lý?",
      )
    )
      return;
    try {
      const formData = new FormData();
      formData.append("orderId", orderId);
      formData.append("status", newStatus);
      const result = await ajaxJson(
        "/SELLING-GLASSES/public/index.php?url=update-order-status",
        { method: "POST", body: formData },
      );
      if (result.success) {
        syncOrderUI(newStatus, 1);
        alert("Cập nhật thành công!");
        setOrderFilter("All");
        await window.viewOrderDetail(orderId);
        typeof fetchOrders === "function" && fetchOrders("All");
      } else alert("Lỗi: " + result.message);
    } catch (error) {
      console.error("Lỗi cập nhật:", error);
      alert("Không thể kết nối máy chủ.");
    }
  };

  /* CHAT */
  window.loadConversationList = async function () {
    const container = $id("conversation-list");
    if (!container) return;
    try {
      const result = await ajaxJson(
        "/SELLING-GLASSES/public/index.php?url=get-conversation-list",
      );
      const conversationHtml =
        result.success && result.data?.length
          ? renderRows(result.data, (item) => {
              const isActive =
                window.chattingOrderId == item.orderId ? "active" : "";
              const lastMsg = item.last_msg || `Đơn hàng #${item.orderId}`;
              const displayTime = item.last_time
                ? new Date(item.last_time).toLocaleTimeString("vi-VN", {
                    hour: "2-digit",
                    minute: "2-digit",
                  })
                : "";
              return `
              <li class="conv-item ${isActive}" onclick="openChatBox('${item.orderId}', '${item.cust_name}')">
                <div class="conv-avatar">${item.cust_name.charAt(0).toUpperCase()}</div>
                <div class="conv-info" style="flex: 1; min-width: 0;"><div style="display: flex; justify-content: space-between; align-items: baseline;"><span class="conv-name" style="font-weight: bold;">${item.cust_name}</span><span class="conv-time" style="font-size: 10px; color: #999;">${displayTime}</span></div><span class="conv-order" style="display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #666; font-size: 12px;">${lastMsg}</span></div>
              </li>`;
            })
          : '<p class="text-center mt-3" style="font-size:12px; color:#999;">Chưa có hội thoại nào</p>';

      container.innerHTML = conversationHtml;

      const badge = document.getElementById("sale-chat-badge");
      if (badge) {
        const unreadCount =
          result.success && Array.isArray(result.data)
            ? result.data.reduce(
                (sum, item) => sum + (Number(item.unread_count) || 0),
                0,
              )
            : 0;
        if (unreadCount > 0) {
          badge.textContent = unreadCount;
          badge.style.display = "flex";
        } else {
          badge.style.display = "none";
        }
      }
    } catch (error) {
      console.error("Lỗi tải danh sách chat:", error);
    }
  };

  /* LIÊN HỆ KHÁCH HÀNG  */
  window.handleContactCustomer = async function (orderIdParam, nameParam) {
    const orderId = orderIdParam || window.currentOrderId;
    const name = nameParam || $id("custName")?.innerText || "Khách hàng";
    if (!orderId) return;
    try {
      const formData = new FormData();
      formData.append("orderId", orderId);
      formData.append(
        "message",
        `Chào ${name}, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #${orderId} sẽ được gửi đến bạn trong thời gian sớm nhất.`,
      );
      const result = await ajaxJson(
        "/SELLING-GLASSES/public/index.php?url=contact-customer",
        { method: "POST", body: formData },
      );
      if (result.success) {
        syncOrderUI("Pending", 1);
        window.chattingOrderId = orderId;
        $id("chat-wrapper")?.classList.remove("chat-hidden");
        await loadChatHistory(orderId);
        typeof window.loadConversationList === "function" &&
          window.loadConversationList();
        typeof fetchOrders === "function" && fetchOrders("All");
        console.log("Đã gửi tin nhắn tự động và mở khóa nút Xác nhận.");
      }
    } catch (error) {
      console.error("Lỗi liên hệ khách:", error);
    }
  };

  /* MỞ HỘP CHAT */
  window.openChatBox = async function (orderId, customerName) {
    window.chattingOrderId = orderId;
    const chatWrapper = $id("chat-wrapper");
    if (chatWrapper) chatWrapper.classList.remove("chat-hidden");
    const chatTitle = $id("chat-title");
    if (chatTitle) chatTitle.innerText = "Hỗ trợ khách: " + customerName;
    document.querySelectorAll(".conv-item").forEach((el) => {
      el.classList.toggle(
        "active",
        el.getAttribute("onclick")?.includes(`'${orderId}'`),
      );
    });
    await loadChatHistory(orderId);
    if (typeof window.loadConversationList === "function")
      await window.loadConversationList();
  };

  window.processComplaintRequest = async function (
    returnId,
    orderId,
    requestType,
  ) {
    if (
      !confirm(
        requestType === "complaint"
          ? "Xác nhận chấp nhận khiếu nại và hủy đơn này?"
          : "Xác nhận trả hàng và cập nhật đơn này về trạng thái Trả hàng?",
      )
    ) {
      return;
    }

    try {
      const formData = new FormData();
      formData.append("returnId", returnId);
      formData.append(
        "action",
        requestType === "complaint" ? "resolve" : "approve_return",
      );
      const result = await ajaxJson(
        "/SELLING-GLASSES/public/index.php?url=process-complaint-request",
        { method: "POST", body: formData },
      );

      if (result.success) {
        alert("Cập nhật thành công.");
        fetchComplaints(
          document.querySelector("#complaint-page .filter-tab.active")?.dataset
            .status || "all",
        );
        if (
          typeof window.currentRequestContext !== "undefined" &&
          window.currentRequestContext?.returnId === returnId
        ) {
          window.currentRequestContext = {
            ...window.currentRequestContext,
            status: "Completed",
            label_status:
              requestType === "complaint" ? "Đã giải quyết" : "Thành công",
          };
          renderComplaintRequestPanel(window.currentRequestContext);
          await viewOrderDetail(orderId, window.currentRequestContext);
        }
        if (typeof fetchOrders === "function") fetchOrders("All");
      } else {
        alert("Lỗi: " + result.message);
      }
    } catch (error) {
      console.error("Lỗi xử lý yêu cầu:", error);
      alert("Không thể kết nối máy chủ.");
    }
  };

  /* BẢNG TRÒ CHUYỆN TỔNG  */
  window.openGlobalChat = function () {
    const chatWrapper = $id("chat-wrapper");
    if (!chatWrapper) return;
    chatWrapper.classList.remove("chat-hidden");
    const chatTitle = $id("chat-title");
    if (chatTitle) chatTitle.innerText = "Trung tâm tin nhắn";
    typeof window.loadConversationList === "function" &&
      window.loadConversationList();
  };

  /*  GỬI TIN NHẮN */
  window.sendMessage = async function () {
    const input = $id("chat-input");
    const message = input?.value.trim();
    const orderId = window.chattingOrderId;
    if (!orderId)
      return alert("Vui lòng chọn một khách hàng bên trái để bắt đầu chat!");
    if (!message) return;
    try {
      const formData = new FormData();
      formData.append("orderId", orderId);
      formData.append("message", message);
      const result = await ajaxJson(
        "/SELLING-GLASSES/public/index.php?url=contact-customer",
        { method: "POST", body: formData },
      );
      if (result.success) {
        if (input) input.value = "";
        await loadChatHistory(orderId);
        typeof window.loadConversationList === "function" &&
          (await window.loadConversationList());
        typeof fetchOrders === "function" && fetchOrders("All");
      } else alert("Lỗi: " + result.message);
    } catch (error) {
      console.error("Lỗi gửi tin:", error);
    }
  };

  /* ĐÓNG CHAT */
  window.closeChat = () => $id("chat-wrapper")?.classList.add("chat-hidden");

  /* TẢI LỊCH SỬ CHAT  */
  async function loadChatHistory(orderId) {
    if (!orderId) return;
    const chatBody = $id("chat-body");
    if (!chatBody) return;
    try {
      const result = await ajaxJson(
        `/SELLING-GLASSES/public/index.php?url=get-messages&orderId=${orderId}`,
      );
      if (!result.success || !Array.isArray(result.data)) return;
      chatBody.innerHTML =
        result.data.length === 0
          ? '<div class="chat-placeholder"><p>Bắt đầu cuộc trò chuyện ngay!</p></div>'
          : renderRows(result.data, (msg) => {
              const senderClass =
                msg.sender_type?.trim().toLowerCase() === "staff"
                  ? "msg-staff"
                  : "msg-customer";
              const msgTime = msg.created_at
                ? new Date(msg.created_at).toLocaleTimeString("vi-VN", {
                    hour: "2-digit",
                    minute: "2-digit",
                  })
                : "";
              return `
              <div class="msg-bubble ${senderClass}">
                <div class="msg-text">${msg.message_content}</div>
                <span class="msg-time">${msgTime}</span>
              </div>`;
            });
      chatBody.scrollTop = chatBody.scrollHeight;
    } catch (error) {
      console.error("Lỗi tải lịch sử chat:", error);
    }
  }

  /* XỬ LÝ NHẤP CHUỘT BÊN NGOÀI */
  document.addEventListener("mousedown", (e) => {
    const chatWrapper = $id("chat-wrapper");
    const btnGlobalChat = $id("btn-global-chat");
    const modalDetail = $id("orderDetailModal");
    if (!chatWrapper || chatWrapper.classList.contains("chat-hidden")) return;
    if (
      !chatWrapper.contains(e.target) &&
      !btnGlobalChat?.contains(e.target) &&
      !modalDetail?.classList.contains("show")
    ) {
      window.closeChat();
    }
  });

  /*  TÌM KIẾM TRONG BẢNG  */
  const setupSearchTable = (inputId, tableBodyId) => {
    const searchInput = $id(inputId);
    const tableBody = $id(tableBodyId);
    if (!searchInput || !tableBody) return;
    let statusLabel = $id(inputId + "-status");
    if (!statusLabel) {
      statusLabel = document.createElement("small");
      statusLabel.id = inputId + "-status";
      statusLabel.style.cssText =
        "margin-left: 10px; color: #666; font-style: italic;";
      searchInput.parentNode.appendChild(statusLabel);
    }
    searchInput.oninput = function () {
      const filter = this.value.toLowerCase().trim();
      const rows = tableBody.getElementsByTagName("tr");
      let count = 0;
      tableBody.querySelector(".no-result-row")?.remove();
      for (const row of rows) {
        if (row.classList.contains("no-result-row")) continue;
        const visible = row.textContent.toLowerCase().includes(filter);
        row.style.display = visible ? "" : "none";
        count += visible ? 1 : 0;
      }
      statusLabel.innerText = filter ? `Tìm thấy ${count} kết quả` : "";
      statusLabel.style.color = count > 0 ? "#2ecc71" : "#e74c3c";
      if (!count && filter) {
        const colCount = tableBody
          .closest("table")
          .querySelectorAll("thead th").length;
        tableBody.insertAdjacentHTML(
          "beforeend",
          `<tr class=\"no-result-row\"><td colspan=\"${colCount}\" style=\"text-align:center; padding: 40px; background: #fffaf0;\"><div style=\"font-size: 16px; color: #555;\"><i class=\"fas fa-search-minus\" style=\"font-size: 24px; color: #ccc; margin-bottom: 10px; display: block;\"></i>Rất tiếc, không tìm thấy <strong>\"${this.value}\"</strong> trong danh sách.<br><small style=\"color: #999;\">Vui lòng kiểm tra lại mã đơn hoặc tên khách hàng.</small></div></td></tr>`,
        );
      }
    };
  };

  /* KHỞI TẠO  */
  switchPage("dashboard-page", btnDashboard);
  window.loadConversationList();

  let isFetchingChat = false;
  setInterval(async () => {
    const chatWrapper = $id("chat-wrapper");
    if (
      !window.chattingOrderId ||
      !chatWrapper ||
      chatWrapper.classList.contains("chat-hidden") ||
      isFetchingChat
    )
      return;
    isFetchingChat = true;
    await loadChatHistory(window.chattingOrderId);
    if (window.currentOrderId == window.chattingOrderId) {
      const result = await ajaxJson(
        `/SELLING-GLASSES/public/index.php?url=get-order-detail&orderId=${window.currentOrderId}`,
      );
      if (result.success && result.data?.length)
        syncOrderUI(result.data[0].status, result.data[0].is_contacted);
    }
    isFetchingChat = false;
  }, 3000);

  $id("orderDetailModal")?.addEventListener("hidden.bs.modal", () => {
    window.currentOrderId = null;
    window.currentRequestContext = null;
  });
  $id("chat-input")?.addEventListener("keypress", (e) => {
    if (e.key === "Enter") window.sendMessage();
  });
});
