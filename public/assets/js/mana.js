// 1. QUẢN LÝ TAB (CHUYỂN ĐỔI GIỮA CÁC MENU)
function showTab(tabId) {
  // Ẩn tất cả các tab
  document.querySelectorAll(".tab-pane").forEach((pane) => {
    pane.classList.remove("active");
  }); // Hiện tab được chọn

  const activePane = document.getElementById(tabId);
  if (activePane) {
    activePane.classList.add("active");
  } // Cập nhật trạng thái menu sidebar

  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.remove("active");
  });
  const activeBtn = document.getElementById("btn-" + tabId);
  if (activeBtn) {
    activeBtn.classList.add("active");
  } // Nếu bấm vào tab Khuyến mãi thì tự động load dữ liệu

  if (tabId === "promo") {
    loadPromotions();
  }

  // BỔ SUNG THÊM ĐOẠN NÀY:
  else if (tabId === "product") {
    loadProducts();
  }
}

/* =========================================
   1. FETCH & RENDER (LẤY DỮ LIỆU)
   ========================================= */
function loadProducts() {
  fetch("/SELLING-GLASSES/public/index.php?url=get-all-products")
    .then((response) => response.json())
    .then((products) => {
      const tableBody = document.getElementById("productTable");
      tableBody.innerHTML = "";

      products.forEach((item, index) => {
        const row = `
            <tr style="font-family: inherit; font-size: 0.85rem;">
              <td>${index + 1}</td>

               <td>
                <div style="text-align: left;">
                  <span>${item.name}</span>
                </div>
              </td>

              <td>${new Intl.NumberFormat("vi-VN", { maximumFractionDigits: 0 }).format(item.minPrice || 0)}đ</td>

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
        tableBody.insertAdjacentHTML("beforeend", row);
      });
    })
    .catch((error) => console.error("Lỗi lấy dữ liệu:", error));
}

/* =========================================
   2. XEM CHI TIẾT (DETAIL MODAL)
   ========================================= */
function viewDetail(id) {
  fetch(`/SELLING-GLASSES/public/index.php?url=detail&id=${id}`)
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
    .catch((error) => alert("Lỗi khi kết nối Database!"));
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
  const name = document.getElementById("input1").value;
  const description = document.getElementById("input2").value;
  const categoryId = document.getElementById("inputCatId").value;
  const variantInput = document.getElementById("inputVariant").value;
  const imageInput = document.getElementById("inputImage");

  const formData = new FormData();
  formData.append("name", name);
  formData.append("description", description);
  formData.append("categoryId", categoryId);
  formData.append("variants", variantInput);
  // BỔ SUNG
  formData.append("staffId", 1); // Thêm dòng này vào trước khi fetch

  if (imageInput.files.length > 0) {
    formData.append("image", imageInput.files[0]);
  }

  // Kiểm tra URL (Nếu có ID thì là update, không thì là add)
  const actionUrl = productId
    ? `update-product&id=${productId}`
    : `add-product`;

  if (productId) {
    formData.append("productId", productId);
  }

  fetch(`/SELLING-GLASSES/public/index.php?url=${actionUrl}`, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        Toast.fire({
          icon: "success",
          title: productId ? "Cập nhật thành công!" : "Thêm mới thành công!",
        });
        closeModal();
        loadProducts();
      } else {
        Swal.fire({
          icon: "error",
          title: "Lỗi...",
          text: result.message,
        });
      }
    })
    .catch((error) => {
      console.error("Lỗi kết nối:", error);
      alert("Không thể kết nối API!");
    });
}

/* =========================================
   4. XÓA SẢN PHẨM (TRANSACTION)
   ========================================= */
// function confirmDelete(id) {
//   if (
//     confirm(
//       "Bạn chắc chắn muốn xóa sản phẩm này? Hệ thống sẽ xóa cả các màu và size liên quan!",
//     )
//   ) {
//     fetch(`/SELLING-GLASSES/public/index.php?url=delete-product&id=${id}`, {
//       method: "POST",
//     })
//       .then((response) => response.json())
//       .then((result) => {
//         if (result.success) {
//           loadProducts();
//           //updateDashboardStats();
//         } else {
//           alert("Không thể xóa sản phẩm này!");
//         }
//       })
//       .catch((error) => alert("Lỗi khi yêu cầu xóa!"));
//   }
// }
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
  // 1. Đổi tiêu đề Modal
  document.getElementById("modalTitle").innerText = "Chỉnh sửa sản phẩm";

  // 2. Gọi API lấy chi tiết sản phẩm để đổ vào form
  fetch(`/SELLING-GLASSES/public/index.php?url=detail&id=${id}`)
    .then((response) => response.json())
    .then((res) => {
      if (!res.success) return alert(res.message);
      const product = res.data;

      // 3. Điền thông tin vào các ô Input
      document.getElementById("editProductId").value = product.productId; // Lưu ID vào ô ẩn
      document.getElementById("input1").value = product.name;
      document.getElementById("input2").value = product.description;
      document.getElementById("inputCatId").value = product.categoryId;

      // Gom danh sách biến thể thành chuỗi: Màu|Size|Giá|Kho
      const variantStr = product.variants
        .map((v) => `${v.color}|${v.size}|${v.price}|${v.stock}`)
        .join("\n");
      document.getElementById("inputVariant").value = variantStr;

      // Hiển thị tên ảnh hiện tại để người dùng biết
      document.getElementById("currentImageName").innerText =
        "Ảnh hiện tại: " + (product.imagePath || "Không có");

      // 4. Mở Modal
      document.getElementById("modal").style.display = "flex";
    })
    .catch((error) => alert("Lỗi khi lấy thông tin sản phẩm!"));
}
