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
    loadPromotions();
  } else if (tabId === "product") {
    loadProducts(1); // Gọi trang 1 khi chuyển tab
  }
}

/* =========================================
   1. FETCH & RENDER (LẤY DỮ LIỆU)
   ========================================= */
let productPage = 1;
const productPageSize = 10; // Mỗi trang 10 sản phẩm
let allProducts = []; // Lưu toàn bộ dữ liệu sản phẩm từ server
function loadProducts() {
  // Lấy toàn bộ sản phẩm, không cần truyền page lên server nữa vì mình phân trang bằng JS
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
  const container = document.getElementById("productPagination"); // Nhớ ID này trong HTML
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

/* =========================================
   2. XEM CHI TIẾT (DETAIL MODAL)
   ========================================= */
function viewDetail(id) {
  // Thêm Header Accept để Backend biết bạn muốn nhận JSON
  fetch(`/SELLING-GLASSES/public/index.php?url=detail&id=${id}`, {
    headers: {
      Accept: "application/json",
    },
  })
    .then((response) => response.json())
    .then((res) => {
      // Vì Backend trả về {success: true, data: {...}}
      // nên chúng ta dùng res.success để kiểm tra
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

/* =========================================
   3. LƯU SẢN PHẨM (XỬ LÝ DỮ LIỆU TỪ FORM)
   ========================================= */
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

  // QUAN TRỌNG: Đẩy từng dòng biến thể vào mảng variants[]
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

/* =========================================
   4. XÓA SẢN PHẨM (TRANSACTION)
   ========================================= */
function confirmDelete(id) {
  Swal.fire({
    title: "Xác nhận xóa?",
    text: "Bạn chắc chắn muốn xóa sản phẩm này? Hệ thống sẽ xóa cả các màu và size liên quan!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#e67e22", // Màu cam giống nút Thêm mới của bạn
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

/* =========================================
   5. Reset form về trạng thái trống
   ========================================= */
function openModal() {
  // 1. Đổi tiêu đề modal về "Thêm mới"
  document.getElementById("modalTitle").innerText = "Thêm mới sản phẩm";

  // 2. Xóa sạch dữ liệu trong form
  document.getElementById("productForm").reset();

  // 3. Xóa ID ẩn (vì đang thêm mới nên không có ID)
  document.getElementById("editProductId").value = "";

  // 4. Xóa tên ảnh cũ đang hiển thị (nếu có)
  document.getElementById("currentImageName").innerText = "";

  // 5. Hiển thị Modal
  document.getElementById("modal").style.display = "flex";
}

function closeModal() {
  document.activeElement.blur();
  document.getElementById("modal").style.display = "none";
}

/* =========================================
   6. Hàm để đóng Modal Chi tiết sản phẩm
   ========================================= */
function closeDetailModal() {
  const modal = document.getElementById("detailModal");
  if (modal) {
    modal.style.display = "none";
  }
}

/* =========================================
   7. MỞ MODAL ĐỂ CHỈNH SỬA
   ========================================= */
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

      // ✅ Load danh mục rồi mới set value
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
              // ✅ So sánh == thay vì === để tránh lỗi kiểu dữ liệu số vs chuỗi
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
      // Vì Server trả về mảng trực tiếp [ {...}, {...} ]
      // Ta kiểm tra xem res có phải là mảng không
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
function loadPromotions() {
  console.log("Tính năng khuyến mãi đang phát triển...");
  const tableBody = document.getElementById("promoTable");
  if (tableBody)
    tableBody.innerHTML = "<tr><td colspan='5'>Đang cập nhật...</td></tr>";
}
document.addEventListener("DOMContentLoaded", () => {
  // Kiểm tra xem hàm có tồn tại không trước khi gọi để tránh lỗi dừng script
  if (typeof loadProducts === "function") loadProducts();
  if (typeof loadCategories === "function") loadCategories();
});
