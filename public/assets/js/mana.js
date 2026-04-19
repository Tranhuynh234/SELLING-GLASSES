// 1. QUẢN LÝ TAB (CHUYỂN ĐỔI GIỮA CÁC MENU)
function showTab(tabId) {
  document.querySelectorAll(".tab-pane").forEach((pane) => {
    pane.classList.remove("active");
  });

  const activePane = document.getElementById(tabId);
  if (activePane) activePane.classList.add("active");

  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.remove("active");
  });

  const activeBtn = document.getElementById("btn-" + tabId);
  if (activeBtn) activeBtn.classList.add("active");

  if (tabId === "promo") {
    if (typeof loadPromotions === "function") {
      loadPromotions();
    }
  } else if (tabId === "product") {
    loadProducts(1); // Gọi trang 1 khi chuyển tab
  } else if (tabId === "combo") {
    if (typeof initComboManager === "function") {
      initComboManager();
    }
  }
}

/* 1. FETCH & RENDER (LẤY DỮ LIỆU) */
let productPage = 1;
const productPageSize = 10; // Mỗi trang 10 sản phẩm
let allProducts = []; // Lưu toàn bộ dữ liệu sản phẩm từ server
function loadProducts() {
  // Lấy toàn bộ sản phẩm, không cần truyền page lên server vì phân trang bằng JS
  const url = `/SELLING-GLASSES/public/index.php?url=get-all-products&format=json`;

  fetch(url, {
    headers: { Accept: "application/json" },
  })
    .then((response) => response.json())
    .then((res) => {
      if (res.success && Array.isArray(res.data)) {
        allProducts = res.data; // Lưu toàn bộ vào biến global
        productPage = 1; // Reset về trang đầu

        renderProductTable(); // Vẽ bảng
        renderProductPagination(); // Vẽ nút phân trang
      } else {
        console.error("Lỗi dữ liệu:", res.message);
        document.getElementById("productTable").innerHTML =
          "<tr><td colspan='5'>Không có sản phẩm nào.</td></tr>";
      }
    })
    .catch((error) => console.error("Lỗi lấy dữ liệu:", error));
}
function renderProductTable() {
  const tableBody = document.getElementById("productTable");
  if (!tableBody) return;

  // Tính toán vị trí bắt đầu và kết thúc
  const start = (productPage - 1) * productPageSize;
  const end = start + productPageSize;
  const displayData = allProducts.slice(start, end);

  tableBody.innerHTML = displayData
    .map((item, index) => {
      const stt = start + index + 1; // STT cộng dồn theo trang
      return `
      <tr style="font-family: inherit; font-size: 0.85rem;">
        <td>${stt}</td>
        <td><div style="text-align: left;"><span>${item.name}</span></div></td>
        <td>${new Intl.NumberFormat("vi-VN").format(item.minPrice || 0)}đ</td>
        <td>
          <button onclick="viewDetail(${item.productId})" style="background:none; border:none; cursor:pointer; color: var(--primary); font-size: 1.1rem;">
            <i class="fas fa-eye"></i>
          </button>
        </td>
        <td>
          <div class="action-group" style="display: flex; gap: 20px; justify-content: center;">
            <button onclick="openEditModal(${item.productId})" style="color: #f39c12; background:none; border:none; cursor:pointer; font-size: 1.1rem;">
              <i class="fas fa-edit"></i>
            </button>
            <button onclick="confirmDelete(${item.productId})" style="color: #ff4d4d; background:none; border:none; cursor:pointer; font-size: 1.1rem;">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </td>
      </tr>`;
    })
    .join("");
}
function renderProductPagination() {
  const container = document.getElementById("productPagination");
  if (!container) return;

  const totalPages = Math.ceil(allProducts.length / productPageSize);
  if (totalPages <= 1) {
    container.innerHTML = "";
    return;
  }

  let html = `
    <button onclick="changeProductPage(${productPage - 1})" ${productPage === 1 ? "disabled" : ""}>
      <i class="fas fa-chevron-left"></i>
    </button>
  `;

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button onclick="changeProductPage(${i})" class="${i === productPage ? "active" : ""}">
        ${i}
      </button>
    `;
  }

  html += `
    <button onclick="changeProductPage(${productPage + 1})" ${productPage === totalPages ? "disabled" : ""}>
      <i class="fas fa-chevron-right"></i>
    </button>
  `;

  container.innerHTML = html;
}

function changeProductPage(page) {
  productPage = page;
  renderProductTable();
  renderProductPagination();
}

/* 2. XEM CHI TIẾT (DETAIL MODAL) */
function viewDetail(id) {
  // Thêm Header Accept để Backend biết bạn muốn nhận JSON
  fetch(`/SELLING-GLASSES/public/index.php?url=detail&id=${id}`, {
    headers: {
      Accept: "application/json",
    },
  })
    .then((response) => response.json())
    .then((res) => {
      if (!res.success) return alert(res.message);

      const product = res.data;

      let variantHtml = product.variants
        .map(
          (v) =>
            `<li>${v.color} - Size ${v.size}: <b>${new Intl.NumberFormat("vi-VN").format(v.price)}đ</b> (Kho: ${v.stock})</li>`,
        )
        .join("");

      document.getElementById("detailContent").innerHTML = `
                <div class="detail-img-wrapper">
                    <img src="/SELLING-GLASSES/public/assets/images/products/${product.imagePath || "default.jpg"}" alt="Kính">
                </div>
                <div class="detail-info">
                    <div class="info-group">
                        <label>Tên sản phẩm</label>
                        <span>${product.name}</span>
                    </div>
                    <div class="info-group">
                        <label>Danh mục</label>
                        <span>${product.categoryName}</span>
                    </div>
                    <div class="info-group">
                        <label>Danh sách biến thể</label>
                        <ul style="font-size: 0.85rem; padding-left: 15px;">${variantHtml || "Không có"}</ul>
                    </div>
                </div>`;
      document.getElementById("detailModal").style.display = "flex";
    })
    .catch((error) => {
      console.error("Lỗi Detail:", error);
      alert("Lỗi khi kết nối Database hoặc dữ liệu không đúng định dạng!");
    });
}

/* 3. LƯU SẢN PHẨM (XỬ LÝ DỮ LIỆU TỪ FORM) */
// Khai báo cấu hình Toast ở ngoài để dùng chung cho cả Add và Update
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});

function saveData() {
  const productId = document.getElementById("editProductId").value;
  const name = document.getElementById("input1").value.trim();
  const description = document.getElementById("input2").value.trim();
  const categoryId = document.getElementById("inputCatId").value;
  const variantInput = document.getElementById("inputVariant").value.trim();
  const staffIdFromSession = document.getElementById("sessionStaffId").value;
  const price = document.getElementById("inputProductPrice").value.trim();

  // 1. Kiểm tra các thông tin bắt buộc
  if (!staffIdFromSession) {
    Swal.fire({
      icon: "error",
      title: "Lỗi",
      text: "Phiên đăng nhập hết hạn!",
    });
    return;
  }
  if (!name || !categoryId || !price || !variantInput) {
    Swal.fire({
      icon: "warning",
      title: "Thiếu thông tin",
      text: "Vui lòng điền đầy đủ các trường bắt buộc!",
    });
    return;
  }

  // 2. Kiểm tra định dạng biến thể (Màu|Size|Giá|Kho)
  const lines = variantInput.split("\n").filter((l) => l.trim());
  const isValidFormat = lines.every((line) => line.split("|").length === 4);
  if (!isValidFormat) {
    Swal.fire({
      icon: "error",
      title: "Sai định dạng",
      text: "Biến thể phải là: Màu|Size|Giá|SốLượng",
    });
    return;
  }

  // 3. Khởi tạo FormData
  const formData = new FormData();
  formData.append("name", name);
  formData.append("description", description);
  formData.append("categoryId", categoryId);
  formData.append("staffId", staffIdFromSession);
  formData.append("price", price);

  // Gửi ảnh nếu có
  const imageInput = document.getElementById("inputImage");
  if (imageInput.files.length > 0) {
    formData.append("image", imageInput.files[0]);
  }

  // Đẩy từng dòng biến thể vào mảng variants[]
  lines.forEach((line) => {
    formData.append("variants[]", line);
  });

  // 4. Xác định URL (Add hoặc Update)
  const actionUrl = productId
    ? `update-product&id=${productId}`
    : `add-product`;

  fetch(`/SELLING-GLASSES/public/index.php?url=${actionUrl}`, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((result) => {
      if (result.success) {
        Swal.fire("Thành công", result.message, "success");
        closeModal(); // Đóng modal sau khi lưu
        loadProducts(); // Load lại danh sách sản phẩm
      } else {
        Swal.fire("Lỗi", result.message, "error");
      }
    })
    .catch((err) => console.error("Lỗi kết nối:", err));
}

/* 4. XÓA SẢN PHẨM (TRANSACTION) */
function confirmDelete(id) {
  Swal.fire({
    title: "Xác nhận xóa?",
    text: "Bạn chắc chắn muốn xóa sản phẩm này? Hệ thống sẽ xóa cả các màu và size liên quan!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#e67e22",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Đồng ý xóa",
    cancelButtonText: "Hủy bỏ",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      // Nếu đồng ý, tiến hành gọi API xóa
      fetch(`/SELLING-GLASSES/public/index.php?url=delete-product&id=${id}`, {
        method: "POST",
      })
        .then((response) => response.json())
        .then((result) => {
          if (result.success) {
            Swal.fire({
              title: "Đã xóa!",
              text: "Sản phẩm đã được gỡ khỏi hệ thống.",
              icon: "success",
              timer: 1500,
              showConfirmButton: false,
            });
            loadProducts();
          } else {
            Swal.fire("Lỗi!", "Không thể xóa sản phẩm này.", "error");
          }
        })
        .catch((error) => {
          Swal.fire("Thất bại!", "Lỗi kết nối đến máy chủ.", "error");
        });
    }
  });
}

/* 5. Reset form về trạng thái trống */
function openModal() {
  document.getElementById("modalTitle").innerText = "Thêm mới sản phẩm";

  // Xóa sạch dữ liệu trong form
  document.getElementById("productForm").reset();

  // Xóa ID ẩn
  document.getElementById("editProductId").value = "";

  // Xóa tên ảnh cũ đang hiển thị (nếu có)
  document.getElementById("currentImageName").innerText = "";

  //  Hiển thị Modal
  document.getElementById("modal").style.display = "flex";
}

function closeModal() {
  document.activeElement.blur();
  document.getElementById("modal").style.display = "none";
}

/* Policy Modal Functions */
function openPolicyModal() {
  // Reset form
  document.getElementById("policyForm").reset();
  document.getElementById("editPolicyId").value = "";
  document.getElementById("policyModalTitle").innerText = "Thêm chính sách mới";

  // Show modal
  document.getElementById("policyModal").style.display = "flex";
}

function closePolicyModal() {
  document.activeElement.blur();
  document.getElementById("policyModal").style.display = "none";
}

function savePolicyData() {
  const title = document.getElementById("policyTitle").value;
  const content = document.getElementById("policyContent").value;
  const type = document.getElementById("policyType").value;
  const status = document.getElementById("policyStatus").value;

  if (!title || !content || !type) {
    alert("Vui lòng điền đầy đủ thông tin!");
    return;
  }

  // Hiển thị thông báo thành công
  Swal.fire({
    icon: "success",
    title: "Thành công!",
    text: "Chính sách đã được thêm thành công.",
    confirmButtonColor: "#d97706",
  });

  // Reset form
  document.getElementById("policyForm").reset();
  closePolicyModal();
}

/* 6. Hàm để đóng Modal Chi tiết sản phẩm */
function closeDetailModal() {
  const modal = document.getElementById("detailModal");
  if (modal) {
    modal.style.display = "none";
  }
}

/* 7. MỞ MODAL ĐỂ CHỈNH SỬA */
function openEditModal(id) {
  document.getElementById("modalTitle").innerText = "Chỉnh sửa sản phẩm";

  fetch(`/SELLING-GLASSES/public/index.php?url=detail&id=${id}`, {
    headers: { Accept: "application/json" },
  })
    .then((response) => response.json())
    .then((res) => {
      if (!res.success) return alert(res.message);

      const product = res.data;

      document.getElementById("editProductId").value = product.productId;
      document.getElementById("input1").value = product.name;
      document.getElementById("input2").value = product.description;
      document.getElementById("inputProductPrice").value = product.price ?? "";

      // Load danh mục rồi mới set value
      fetch("/SELLING-GLASSES/public/index.php?url=get-all-categories", {
        headers: { Accept: "application/json" },
      })
        .then((r) => r.json())
        .then((categories) => {
          const selectCat = document.getElementById("inputCatId");
          selectCat.innerHTML = '<option value="">-- Chọn danh mục --</option>';

          if (Array.isArray(categories)) {
            categories.forEach((cat) => {
              const option = document.createElement("option");
              option.value = cat.categoryId;
              option.textContent = cat.name;
              if (cat.categoryId == product.categoryId) {
                option.selected = true;
              }
              selectCat.appendChild(option);
            });
          }
        });

      const variantStr = product.variants
        .map((v) => `${v.color}|${v.size}|${v.price}|${v.stock}`)
        .join("\n");
      document.getElementById("inputVariant").value = variantStr;

      document.getElementById("currentImageName").innerText =
        "Ảnh hiện tại: " + (product.imagePath || "Không có");

      document.getElementById("modal").style.display = "flex";
    })
    .catch((error) => {
      console.error("Lỗi Edit Modal:", error);
      alert("Lỗi khi lấy thông tin sản phẩm!");
    });
}
function loadCategories() {
  fetch("/SELLING-GLASSES/public/index.php?url=get-all-categories", {
    headers: { Accept: "application/json" },
  })
    .then((response) => response.json())
    .then((res) => {
      if (Array.isArray(res)) {
        const selectCat = document.getElementById("inputCatId");
        if (!selectCat) return;

        res.forEach((cat) => {
          // Sử dụng đúng tên thuộc tính categoryId từ JSON bạn vừa gửi
          const option = `<option value="${cat.categoryId}">${cat.name}</option>`;
          selectCat.insertAdjacentHTML("beforeend", option);
        });

        console.log("Đã tải danh mục thành công!");
      } else {
        console.error("Dữ liệu trả về không phải là mảng:", res);
      }
    })
    .catch((err) => console.error("Lỗi load danh mục:", err));
}

/** Lấy và cập nhật thống kê dashboard */
function loadDashboardStats() {
  const url = `/SELLING-GLASSES/public/index.php?url=dashboard-stats`;

  fetch(url, {
    headers: { Accept: "application/json" },
  })
    .then((response) => response.json())
    .then((res) => {
      if (res.success && res.data) {
        // Cập nhật số sản phẩm
        const totalProductEl = document.getElementById("totalProduct");
        if (totalProductEl) {
          totalProductEl.textContent = res.data.productCount || 0;
        }

        // Cập nhật số mã giảm giá
        const totalPromoEl = document.getElementById("totalPromo");
        if (totalPromoEl) {
          totalPromoEl.textContent = res.data.promoCount || 0;
        }

        // Cập nhật số chính sách
        const totalPolicyEl = document.getElementById("totalPolicy");
        if (totalPolicyEl) {
          totalPolicyEl.textContent = res.data.policyCount || 0;
        }

        // Cập nhật số khách hàng
        const totalCustomerEl = document.getElementById("totalCustomer");
        if (totalCustomerEl) {
          totalCustomerEl.textContent = res.data.customerCount || 0;
        }
      } else {
        console.error("Lỗi dữ liệu:", res.message);
      }
    })
    .catch((error) => console.error("Lỗi lấy dữ liệu thống kê:", error));
}

/** Tạo và cập nhật biểu đồ doanh thu */
let revenueChartInstance = null;

function loadRevenueChart(period = "daily") {
  const url = `/SELLING-GLASSES/public/index.php?url=order-revenue-stats&period=${period}`;

  fetch(url, {
    headers: { Accept: "application/json" },
  })
    .then((response) => response.json())
    .then((res) => {
      if (res.success && res.data) {
        renderRevenueChart(res.data);
      } else {
        console.error("Lỗi dữ liệu biểu đồ:", res.message);
      }
    })
    .catch((error) => console.error("Lỗi lấy dữ liệu biểu đồ:", error));
}

function renderRevenueChart(data) {
  const chartCanvas = document.getElementById("revenueChart");
  if (!chartCanvas) return;

  const ctx = chartCanvas.getContext("2d");

  // Hủy biểu đồ cũ nếu tồn tại
  if (revenueChartInstance) {
    revenueChartInstance.destroy();
  }

  // Tạo biểu đồ mới
  revenueChartInstance = new Chart(ctx, {
    type: "line",
    data: {
      labels: data.labels,
      datasets: [
        {
          label: "Doanh thu (VNĐ)",
          data: data.revenues,
          borderColor: "#ff6b6b",
          backgroundColor: "rgba(255, 107, 107, 0.1)",
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#ff6b6b",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          yAxisID: "y",
        },
        {
          label: "Số đơn hàng",
          data: data.orderCounts,
          borderColor: "#4ecdc4",
          backgroundColor: "rgba(78, 205, 196, 0.1)",
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#4ecdc4",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          yAxisID: "y1",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      interaction: {
        mode: "index",
        intersect: false,
      },
      plugins: {
        legend: {
          display: true,
          position: "top",
          labels: {
            usePointStyle: true,
            padding: 15,
            font: {
              size: 12,
              weight: "500",
            },
          },
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          padding: 12,
          titleFont: {
            size: 13,
            weight: "bold",
          },
          bodyFont: {
            size: 12,
          },
          borderColor: "rgba(255, 255, 255, 0.2)",
          borderWidth: 1,
          callbacks: {
            label: function (context) {
              let label = context.dataset.label || "";
              if (label) label += ": ";
              if (context.dataset.yAxisID === "y") {
                // Định dạng tiền tệ cho doanh thu
                label += new Intl.NumberFormat("vi-VN", {
                  style: "currency",
                  currency: "VND",
                }).format(context.parsed.y);
              } else {
                // Số nguyên cho đơn hàng
                label += context.parsed.y + " đơn";
              }
              return label;
            },
          },
        },
      },
      scales: {
        y: {
          type: "linear",
          display: true,
          position: "left",
          title: {
            display: true,
            text: "Doanh thu (VNĐ)",
            font: {
              size: 12,
              weight: "bold",
            },
          },
          ticks: {
            callback: function (value) {
              return new Intl.NumberFormat("vi-VN", {
                notation: "compact",
                compactDisplay: "short",
              }).format(value);
            },
          },
        },
        y1: {
          type: "linear",
          display: true,
          position: "right",
          title: {
            display: true,
            text: "Số đơn hàng",
            font: {
              size: 12,
              weight: "bold",
            },
          },
          grid: {
            drawOnChartArea: false,
          },
        },
        x: {
          ticks: {
            font: {
              size: 11,
            },
          },
        },
      },
    },
  });
}
document.addEventListener("DOMContentLoaded", () => {
  // Kiểm tra xem hàm có tồn tại không trước khi gọi để tránh lỗi dừng script
  if (typeof loadProducts === "function") loadProducts();
  if (typeof loadCategories === "function") loadCategories();
  if (typeof loadDashboardStats === "function") loadDashboardStats();
  if (typeof loadRevenueChart === "function") loadRevenueChart("daily");
});
