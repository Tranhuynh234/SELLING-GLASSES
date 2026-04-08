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
}

// 2. QUẢN LÝ MODAL (THÊM / SỬA)
function openModal(type) {
  const modal = document.getElementById("modal");
  const title = document.getElementById("modalTitle");
  modal.style.display = "flex"; // Tùy chỉnh tiêu đề dựa trên loại tab đang đứng

  if (type === "promo") {
    title.innerText = "Tạo mã khuyến mãi mới"; // Bạn có thể reset form hoặc thay đổi label ở đây nếu muốn
  } else if (type === "product") {
    title.innerText = "Thêm sản phẩm mới";
  }
}

function closeModal() {
  const modal = document.getElementById("modal");
  modal.style.display = "none"; // Reset các input trong modal
  const inputs = modal.querySelectorAll("input, select");
  inputs.forEach((input) => {
    if (input.type !== "hidden") input.value = "";
  });
}

// Đóng modal khi click ra ngoài vùng xám
window.onclick = function (event) {
  const modal = document.getElementById("modal");
  if (event.target == modal) {
    closeModal();
  }
};

// 3. KẾT NỐI API KHUYẾN MÃI (PROMOTION)
async function loadPromotions() {
  const tbody = document.getElementById("promoTable");
  tbody.innerHTML =
    '<tr><td colspan="5" style="text-align:center;">Đang tải dữ liệu...</td></tr>';

  try {
    const response = await fetch("/SELLING-GLASSES/public/get-all-promotions");
    const result = await response.json();

    if (result.success) {
      tbody.innerHTML = ""; // Xóa dòng "Đang tải"

      if (result.data.length === 0) {
        tbody.innerHTML =
          '<tr><td colspan="5" style="text-align:center;">Chưa có chương trình khuyến mãi nào</td></tr>';
        return;
      }

      result.data.forEach((promo, index) => {
        // Xử lý logic trạng thái
        const today = new Date();
        const start = new Date(promo.startDate);
        const end = new Date(promo.endDate);
        let statusHtml = "";
        if (today < start) {
          statusHtml =
            '<span style="color: #f59e0b; font-weight: bold;">Sắp diễn ra</span>';
        } else if (today >= start && today <= end) {
          statusHtml =
            '<span style="color: #10b981; font-weight: bold;">Đang diễn ra</span>';
        } else {
          statusHtml =
            '<span style="color: #ef4444; font-weight: bold;">Đã hết hạn</span>';
        } // Định dạng hiển thị % hoặc VNĐ

        const discountVal = parseFloat(promo.discount);
        const discountText =
          discountVal <= 100
            ? `${discountVal}%`
            : `${discountVal.toLocaleString("vi-VN")}đ`;

        tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td style="font-weight: 600;">${promo.name}</td>
                        <td style="color: #d97706; font-weight: bold;">${discountText}</td>
                        <td>${statusHtml}</td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <button onclick="editPromo(${promo.promotionId})" style="color: #3b82f6; border:none; background:none; cursor:pointer;"><i class="fas fa-edit"></i></button>
                                <button onclick="deletePromo(${promo.promotionId})" style="color: #ef4444; border:none; background:none; cursor:pointer;"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
      }); // Cập nhật con số tổng ở Dashboard

      const totalPromoEl = document.getElementById("totalPromo");
      if (totalPromoEl) totalPromoEl.innerText = result.data.length;
    } else {
      tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;">Lỗi: ${result.message}</td></tr>`;
    }
  } catch (error) {
    tbody.innerHTML =
      '<tr><td colspan="5" style="text-align:center; color:red;">Không thể kết nối máy chủ</td></tr>';
  }
}

// 1. KHỞI TẠO DỮ LIỆU
let products = JSON.parse(localStorage.getItem("products")) || [];
let promos = JSON.parse(localStorage.getItem("promos")) || [];
let policies = JSON.parse(localStorage.getItem("policies")) || [];
let users = JSON.parse(localStorage.getItem("users")) || [];
let combos = JSON.parse(localStorage.getItem("combos")) || [];
let permissions = JSON.parse(localStorage.getItem("permissions")) || [];
let currentType = "";
let mainChart = null;

// 2. ĐIỀU HƯỚNG TAB
function showTab(tabId) {
  // 1. Xóa tất cả trạng thái cũ
  document
    .querySelectorAll(".tab-pane")
    .forEach((t) => t.classList.remove("active"));
  document
    .querySelectorAll(".nav-item")
    .forEach((n) => n.classList.remove("active")); // 2. Hiển thị Tab được chọn

  const targetTab = document.getElementById(tabId);
  const targetBtn = document.getElementById("btn-" + tabId);

  if (targetTab) targetTab.classList.add("active");
  if (targetBtn) targetBtn.classList.add("active"); // 3. Vẽ lại biểu đồ nếu là Dashboard

  if (tabId === "dashboard" && typeof initChart === "function") {
    setTimeout(initChart, 150);
  }
}

// 3. QUẢN LÝ MODAL LUXURY
function openModal(t) {
  currentType = t;
  const title = document.getElementById("modalTitle");
  const lbl1 = document.getElementById("lbl1");
  const lbl2 = document.getElementById("lbl2");

  const configs = {
    product: {
      title: "THÊM MẮT KÍNH MỚI",
      l1: "Tên mắt kính",
      l2: "Mô tả sản phẩm",
    },
    promo: {
      title: "TẠO MÃ KHUYẾN MÃI",
      l1: "Tên mã / Sự kiện",
      l2: "Mức giảm (%)",
    },
    policy: {
      title: "THÊM CHÍNH SÁCH",
      l1: "Tiêu đề quy định",
      l2: "Nội dung chi tiết",
    },
    user: { title: "THÊM NGƯỜI DÙNG", l1: "Họ tên khách", l2: "SĐT / Email" },
    combo: { title: "THÊM COMBO", l1: "Tên combo", l2: "Giá combo" },
    permission: {
      title: "CẤP QUYỀN",
      l1: "Tên nhân viên",
      l2: "Quyền hạn (all/tab_name)",
    },
  };

  title.innerText = configs[t].title;
  lbl1.innerText = configs[t].l1;
  lbl2.innerText = configs[t].l2;

  const c = configs[t];
  document.getElementById("modalTitle").innerText = c.title;
  document.getElementById("lbl1").innerText = c.l1;
  document.getElementById("lbl2").innerText = c.l2;
  document.getElementById("modal").style.display = "flex";
}

function closeModal() {
  document.getElementById("modal").style.display = "none";
}

// 4. LƯU DỮ LIỆU & FORMAT TIỀN TỆ
function saveData() {
  // 1. Kiểm tra xem đang ở Tab nào
  // Nếu KHÔNG PHẢI sản phẩm, ta dùng logic localStorage cũ
  if (currentType !== "product") {
    const v1 = document.getElementById("input1").value.trim();
    const v2 = document.getElementById("input2").value.trim();

    if (!v1 || !v2) {
      showToast("Đừng để trống thông tin nhé!", "error");
      return;
    }

    const entry = { name: v1, detail: v2 }; // Đẩy vào mảng tương ứng dựa trên currentType

    if (currentType === "promo") promos.push(entry);
    else if (currentType === "policy") policies.push(entry);
    else if (currentType === "user") users.push(entry);
    else if (currentType === "combo") combos.push(entry);
    else if (currentType === "permission") permissions.push(entry);

    syncData(); // Lưu vào localStorage cho các tab này
    closeModal();
    showToast(`Đã thêm ${v1} thành công!`);
    return; // THOÁT HÀM, không chạy xuống đoạn fetch phía dưới
  } // 2. Logic dành riêng cho SẢN PHẨM (Kết nối Database thật)

  const name = document.getElementById("input1").value.trim();
  const description = document.getElementById("input2").value.trim();
  const catId = document.getElementById("inputCatId").value;
  const variantStr = document.getElementById("inputVariant").value.trim();
  const imageFile = document.getElementById("inputImage").files[0];

  if (!name || !variantStr) {
    showToast("Vui lòng nhập tên và biến thể (Màu|Size|Giá|Kho)!", "error");
    return;
  }

  const formData = new FormData();
  formData.append("name", name);
  formData.append("description", description);
  formData.append("categoryId", catId);
  formData.append("variants[]", variantStr);
  formData.append("staffId", 1);

  if (imageFile) {
    formData.append("image", imageFile);
  }

  fetch("http://localhost/SELLING-GLASSES/public/index.php?url=add-product", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Thêm sản phẩm thành công!");
        closeModal();
        loadProducts(); // Load lại bảng từ DB
        // Reset form
        document.getElementById("input1").value = "";
        document.getElementById("input2").value = "";
        document.getElementById("inputVariant").value = "";
        if (document.getElementById("inputImage"))
          document.getElementById("inputImage").value = "";
      } else {
        showToast("Lỗi: " + data.message, "error");
      }
    })
    .catch((err) => {
      console.error("Lỗi kết nối:", err);
      showToast("Không thể kết nối đến Server!", "error");
    });
}

// 5. ĐỒNG BỘ & RENDER
function syncData() {
  localStorage.setItem("products", JSON.stringify(products));
  localStorage.setItem("promos", JSON.stringify(promos));
  localStorage.setItem("policies", JSON.stringify(policies));
  localStorage.setItem("users", JSON.stringify(users));
  localStorage.setItem("combos", JSON.stringify(combos));
  localStorage.setItem("permissions", JSON.stringify(permissions)); // renderTable("productTable", products);

  renderTable("promoTable", promos);
  renderTable("policyTable", policies);
  renderTable("userTable", users);
  renderTable("comboTable", combos);
  renderTable("permissionTable", permissions);
  updateStats();
}

function renderTable(tableId, data) {
  const tbody = document.getElementById(tableId);
  if (!tbody) return;

  tbody.innerHTML = data
    .map(
      (item, i) => `
        <tr class="fade-in">
            <td>${i + 1}</td>
            <td style="font-weight:600; color: #1c1917">${item.name}</td>
            <td><span class="badge-vip">${item.detail}</span></td>
            <td>
                <button class="btn-del" onclick="deleteItem('${tableId}', ${i})" style="color:#ef4444; border:none; background:none; cursor:pointer; font-size: 1.1rem;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `,
    )
    .join("");
}

function deleteItem(tableId, index) {
  if (!confirm("Xác nhận xóa?")) return;

  if (tableId === "productTable") products.splice(index, 1);
  else if (tableId === "promoTable") promos.splice(index, 1);
  else if (tableId === "policyTable") policies.splice(index, 1);
  else if (tableId === "userTable") users.splice(index, 1);
  else if (tableId === "comboTable") combos.splice(index, 1);
  else if (tableId === "permissionTable") permissions.splice(index, 1);

  syncData();
  logActivity(`Hệ thống vừa xóa dữ liệu từ ${tableId.replace("Table", "")}`);
  showToast("Đã xóa mục thành công.", "info");
}

function updateStats() {
  const updateVal = (id, val) => {
    const el = document.getElementById(id);
    if (el) el.innerText = val;
  };
  updateVal("totalPromo", promos.length);
  updateVal("totalPolicy", policies.length);
}

// 6. NHẬT KÝ & THÔNG BÁO AMBER
function logActivity(msg) {
  const list = document.getElementById("logList");
  if (!list) return;
  const item = document.createElement("div");
  item.className = "log-item";
  item.innerHTML = `<span class="time">${new Date().toLocaleTimeString()}</span> ${msg}`;
  list.prepend(item);
}

function showToast(message, type = "success") {
  const toast = document.createElement("div"); // Chỉnh màu Toast theo tông Stone & Amber
  const bgColor =
    type === "success" ? "#d97706" : type === "error" ? "#7f1d1d" : "#44403c";

  toast.style = `position:fixed; bottom:30px; right:30px; background:${bgColor}; color:white; padding:16px 28px; border-radius:12px; z-index:10000; box-shadow:0 15px 35px rgba(0,0,0,0.2); animation: slideUp 0.4s ease; font-weight:600;`;
  toast.innerText = message;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateY(20px)";
    setTimeout(() => toast.remove(), 500);
  }, 3000);
}

// 7. BIỂU ĐỒ TÔNG AMBER LUXURY
function initChart() {
  const chartElement = document.getElementById("revenueChart");
  if (!chartElement) return;

  if (mainChart) {
    mainChart.destroy();
  }

  const ctx = chartElement.getContext("2d"); // Gradient vàng hổ phách (Amber)
  let gradient = ctx.createLinearGradient(0, 0, 0, 400);
  gradient.addColorStop(0, "rgba(217, 119, 6, 0.3)"); // amber-600
  gradient.addColorStop(1, "rgba(217, 119, 6, 0)");

  mainChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: ["T2", "T3", "T4", "T5", "T6", "T7", "CN"],
      datasets: [
        {
          label: "Doanh thu dự kiến",
          data: [45, 52, 38, 65, 48, 80, 80],
          borderColor: "#d97706",
          borderWidth: 3,
          pointBackgroundColor: "#fff",
          pointBorderColor: "#d97706",
          pointRadius: 5,
          backgroundColor: gradient,
          fill: true,
          tension: 0.4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, grid: { color: "rgba(0,0,0,0.03)" } },
        x: { grid: { display: false } },
      },
    },
  });
}

window.onload = function () {
  loadProducts(); // Thay vì syncData() cũ
  initChart();
  logActivity("Hệ thống quản trị đã kết nối Database.");
};

let promotions = [
  { id: 1, title: "Sale Tết", discount: 20, active: true },
  { id: 2, title: "Black Friday", discount: 50, active: false },
];

function renderPromotions() {
  let html = "";

  promotions.forEach((p, index) => {
    html += `
        <tr>
            <td>${index + 1}</td>
            <td>${p.title}</td>
            <td>${p.discount}%</td>
            <td>
                <span class="badge ${p.active ? "badge-active" : "badge-off"}">
                    ${p.active ? "Hoạt động" : "Tắt"}
                </span>
            </td>
            <td>
                <button class="btn-action btn-edit">Sửa</button>
                <button class="btn-action btn-delete">Xóa</button>
            </td>
        </tr>
        `;
  });

  document.getElementById("promoTable").innerHTML = html;
}

function loadProducts() {
  fetch(
    "http://localhost/SELLING-GLASSES/public/index.php?url=get-all-products",
  )
    .then((res) => res.json())
    .then((result) => {
      const products = result.success ? result.data : result;
      if (Array.isArray(products)) {
        const tbody = document.getElementById("productTable");
        if (!tbody) return; // Vẽ lại bảng bằng dữ liệu đã xử lý

        tbody.innerHTML = products
          .map((item, i) => {
            // Định dạng giá tiền (Dùng item.price từ database)
            const priceVal = item.price
              ? new Intl.NumberFormat("vi-VN").format(item.price) + "đ"
              : "0đ";

            return `
            <tr class="fade-in">
              <td style="text-align: center;">${i + 1}</td>
              <td style="font-weight:600; color: #1c1917">${item.name}</td>
              <td style="text-align: center;"><span class="badge-vip">${priceVal}</span></td>
              
              <td style="text-align: center;">
                <button onclick="showProductDetail(${item.productId})" 
                        style="color: #d97706; border: none; background: none; cursor: pointer; font-size: 1.2rem; transition: transform 0.2s;" 
                        class="btn-hover-scale"
                        title="Xem chi tiết">
                  <i class="fas fa-eye"></i>
                </button>
              </td>

              <td style="text-align: center;">
                <div style="display: flex; gap: 15px; justify-content: center; align-items: center;">
                  <button onclick="editProduct(${item.productId})" 
                          style="color: #3b82f6; border: none; background: none; cursor: pointer; font-size: 1.1rem;" 
                          title="Chỉnh sửa">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button onclick="deleteProduct(${item.productId}, '${item.name}')" 
                          style="color: #ef4444; border: none; background: none; cursor:pointer; font-size: 1.1rem;" 
                          title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          `;
          })
          .join(""); // Cập nhật con số tổng trên Dashboard

        const totalEl = document.getElementById("totalProduct");
        if (totalEl) totalEl.innerText = products.length;
      } else {
        console.error("Dữ liệu trả về không phải mảng:", products);
      }
    })
    .catch((err) => {
      console.error("Lỗi kết nối hoặc lỗi JSON:", err); // Nếu lỗi, nên xóa trắng bảng để người dùng không nhìn thấy dữ liệu cũ sai lệch
      const tbody = document.getElementById("productTable");
      if (tbody)
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Lỗi tải dữ liệu. Vui lòng kiểm tra Console.</td></tr>`;
    });
}
// function loadProducts() {
//   fetch(
//     "http://localhost/SELLING-GLASSES/public/index.php?url=get-all-products",
//   )
//     .then((res) => res.json())
//     .then((result) => {
//       if (result.success) {
//         const tbody = document.getElementById("productTable");
//         if (!tbody) return;

//         tbody.innerHTML = result.data
//           .map((item, i) => {
//             // Định dạng giá tiền
//             const priceVal = item.price
//               ? new Intl.NumberFormat("vi-VN").format(item.price) + "đ"
//               : "0đ";

//             return `
//             <tr class="fade-in">
//               <td style="text-align: center;">${i + 1}</td>
//               <td style="font-weight:600; color: #1c1917">${item.name}</td>
//               <td style="text-align: center;"><span class="badge-vip">${priceVal}</span></td>

//               <td style="text-align: center;">
//                 <button onclick="showProductDetail(${item.productId})"
//                         style="color: #d97706; border: none; background: none; cursor: pointer; font-size: 1.2rem; transition: transform 0.2s;"
//                         class="btn-hover-scale"
//                         title="Xem chi tiết">
//                   <i class="fas fa-eye"></i>
//                 </button>
//               </td>

//               <td style="text-align: center;">
//                 <div style="display: flex; gap: 15px; justify-content: center; align-items: center;">
//                   <button onclick="editProduct(${item.productId})"
//                           style="color: #3b82f6; border: none; background: none; cursor: pointer; font-size: 1.1rem;"
//                           title="Chỉnh sửa">
//                     <i class="fas fa-edit"></i>
//                   </button>
//                   <button onclick="deleteProduct(${item.productId}, '${item.name}')"
//                           style="color: #ef4444; border: none; background: none; cursor:pointer; font-size: 1.1rem;"
//                           title="Xóa">
//                     <i class="fas fa-trash-alt"></i>
//                   </button>
//                 </div>
//               </td>
//             </tr>
//           `;
//           })
//           .join("");

//         // Cập nhật tổng số lượng trên dashboard
//         const totalEl = document.getElementById("totalProduct");
//         if (totalEl) totalEl.innerText = result.data.length;
//       }
//     })
//     .catch((err) => console.error("Lỗi load bảng:", err));
// }

function deleteProduct(id, name) {
  if (!confirm(`Bạn có chắc chắn muốn xóa sản phẩm [${name}]?`)) return;

  fetch(
    `http://localhost/SELLING-GLASSES/public/index.php?url=delete-product&id=${id}`,
  )
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast(`Đã xóa ${name} thành công!`);
        loadProducts(); // Load lại bảng
      } else {
        showToast("Xóa thất bại!", "error");
      }
    });
}

// Hàm mở Modal xem chi tiết
// Hàm mở Modal xem chi tiết
function showProductDetail(id) {
  fetch(`http://localhost/SELLING-GLASSES/public/index.php?url=detail&id=${id}`)
    .then((res) => res.json())
    .then((result) => {
      if (result.success) {
        const p = result.data;
        const content = document.getElementById("detailContent");

        // 1. Xử lý đường dẫn ảnh (Yến kiểm tra lại số dấu ../ cho chuẩn nhé)
        // Nếu file mana.js nằm trong public/assets/js thì dùng đường dẫn này:
        // Đường dẫn mới khi đã bỏ thư mục products:
        // Thêm thư mục /products/ vào đường dẫn vì code PHP đang lưu vào đó
        let imgUrl = p.imagePath
          ? `../../../../public/assets/images/products/${p.imagePath}`
          : `../../../../public/assets/images/default.jpg`;

        // 2. Tạo HTML cho biến thể
        let variantsHtml = "";
        if (p.variants && p.variants.length > 0) {
          variantsHtml = p.variants
            .map(
              (v) => `
                            <div style="background: #fff7ed; padding: 10px; border-radius: 8px; margin-bottom: 8px; border-left: 4px solid #f59e0b; font-size: 0.9rem;">
                                <span style="font-weight: 600;">Màu: ${v.color} | Size: ${v.size}</span><br>
                                <span style="color: #b45309;">Giá: ${new Intl.NumberFormat("vi-VN").format(v.price)}đ</span> 
                                <span style="margin-left: 10px; color: #6b7280;">- Kho: ${v.stock}</span>
                            </div>
                        `,
            )
            .join("");
        } else {
          variantsHtml =
            "<p style='color: #9ca3af;'>Chưa có thông tin biến thể.</p>";
        }

        // 3. Đổ dữ liệu vào Modal với giao diện đã thu gọn
        content.innerHTML = `
                    <div class="detail-img-wrapper" style="text-align: center; margin-bottom: 15px;">
                        <img src="${imgUrl}" 
                             onerror="this.src='https://via.placeholder.com/250x200?text=Khong+Tim+Thay+Anh'"
                             style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    </div>
                    
                    <div class="detail-info" style="width: 100%; text-align: left;">
                        <div style="margin-bottom: 8px;">
                            <span style="color: #a8a29e; font-size: 0.75rem; text-transform: uppercase; display: block;">Tên sản phẩm</span>
                            <strong style="font-size: 1.1rem; color: #1c1917;">${p.name}</strong>
                        </div>

                        <div style="margin-bottom: 8px;">
                            <span style="color: #a8a29e; font-size: 0.75rem; text-transform: uppercase; display: block;">Danh mục</span>
                            <span style="font-weight: 600; color: #444;">${p.categoryName}</span>
                        </div>

                        <div style="margin-bottom: 12px;">
                            <span style="color: #a8a29e; font-size: 0.75rem; text-transform: uppercase; display: block;">Mô tả chi tiết</span>
                            <p style="font-size: 0.9rem; line-height: 1.4; color: #57534e; margin: 4px 0;">${p.description || "Sản phẩm chất lượng cao."}</p>
                        </div>

                        <div style="font-weight: bold; margin-bottom: 8px; color: #d97706; font-size: 0.85rem; border-top: 1px solid #f5f5f4; pt: 10px;">
                            PHIÊN BẢN CÓ SẴN
                        </div>
                        <div style="max-height: 150px; overflow-y: auto;">
                            ${variantsHtml}
                        </div>
                    </div>
                `;

        document.getElementById("detailModal").style.display = "flex";
      }
    })
    .catch((err) => console.error("Lỗi:", err));
}

// Hàm đóng Modal chi tiết
function closeDetailModal() {
  document.getElementById("detailModal").style.display = "none";
}

// Hàm mở Modal chỉnh sửa
function editProduct(id) {
  alert("Chức năng chỉnh sửa sản phẩm ID " + id + " đang được xây dựng!");
}

function deletePromo(id) {
  if (confirm("Bạn có chắc chắn muốn xóa khuyến mãi này không?")) {
    console.log("Xóa ID:", id); // Gửi fetch DELETE tới API ở đây
  }
}

// 5. KHỞI TẠO KHI TRANG LOAD XONG
document.addEventListener("DOMContentLoaded", () => {
  console.log("Admin System Ready!");
});
