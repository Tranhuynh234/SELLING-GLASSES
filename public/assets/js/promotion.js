const PROMO_API_URL =
  "http://localhost:8088/SELLING-GLASSES/public/promotion-index";
const PROMO_ITEMS_PER_PAGE = 5;

let currentSearchName = "";
let debounceTimer;

document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchPromo");
  if (document.getElementById("promotion-list-body")) {
    loadPromotions();
  }

  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      currentSearchName = e.target.value.trim();
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        loadPromotions(1);
      }, 300);
    });
  }
});

async function loadPromotions(page = 1) {
  try {
    const response = await fetch(
      `${PROMO_API_URL}?page=${page}&name=${encodeURIComponent(currentSearchName)}`,
    );
    const result = await response.json();
    if (result.success) {
      renderPromotionTable(result);
      renderPromoPagination(result.totalPages, result.page);
    }
  } catch (error) {
    console.error("Lỗi kết nối khuyến mãi:", error);
  }
}

function renderPromotionTable(apiResponse) {
  const tableBody = document.getElementById("promotion-list-body");
  if (!tableBody) return;
  tableBody.innerHTML = "";

  if (apiResponse.data.length === 0) {
    tableBody.innerHTML = `<tr><td colspan="5" class="text-center" style="padding: 20px;">Không tìm thấy chương trình khuyến mãi nào.</td></tr>`;
    return;
  }

  apiResponse.data.forEach((promo, index) => {
    const discountVal = parseFloat(promo.discount);
    const displayDiscount =
      promo.discountType === "percent"
        ? `${discountVal}%`
        : `${new Intl.NumberFormat("vi-VN").format(discountVal)} VNĐ`;

    const statusText = promo.status === 1 ? "Đang chạy" : "Tạm dừng";
    const statusClass = promo.status === 1 ? "badge-active" : "badge-inactive";
    const stt = (apiResponse.page - 1) * PROMO_ITEMS_PER_PAGE + (index + 1);

    const row = `
            <tr>
                <td class="text-center">${stt}</td>
                <td>
                    <div class="user-info">
                        <span class="user-name">${promo.name}</span>
                        <span class="user-email">ID: #PROMO${promo.promotionId}</span>
                    </div>
                </td>
                <td>
                    <div class="contact-info">
                        <span class="promo-value">${displayDiscount}</span>
                        <span class="promo-date">${formatPromoDate(promo.startDate)} - ${formatPromoDate(promo.endDate)}</span>
                    </div>
                </td>
                <td class="text-center">
                    <span class="role-badge ${statusClass}">${statusText}</span>
                </td>
                <td class="text-center">
                    <div class="action-btns">
                        <button class="btn-edit-small" onclick="editPromo(${promo.promotionId})">
                            <i class="fas fa-edit"></i> Sửa
                        </button>
                        <button class="btn-delete-small" onclick="deletePromo(${promo.promotionId})">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                        <button class="btn-apply-small" onclick="openApplyModal('${promo.promotionId}')">
                            <i class="fas fa-check-circle"></i> Áp dụng
                        </button>
                        <button class="btn-cancel-small" onclick="openCancelModal('${promo.promotionId}')" style="background-color: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer;">
                          <i class="fas fa-times-circle"></i> Hủy KM
                        </button>
                    </div>
                </td>
            </tr>
        `;
    tableBody.insertAdjacentHTML("beforeend", row);
  });
}

function formatPromoDate(dateString) {
  if (!dateString) return "";
  const [year, month, day] = dateString.split("-");
  return `${day}/${month}/${year}`;
}

function renderPromoPagination(totalPages, currentPage) {
  const container = document.getElementById("promo-pagination-container");
  if (!container) return;
  if (totalPages <= 0) {
    container.innerHTML = "";
    return;
  }

  let html = "";
  html += `
        <button class="page-nav" ${currentPage === 1 ? "disabled" : ""} 
                onclick="${currentPage > 1 ? `loadPromotions(${currentPage - 1})` : ""}">
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

  for (let i = 1; i <= totalPages; i++) {
    html += `<button class="page-num ${i === currentPage ? "active" : ""}" onclick="loadPromotions(${i})">${i}</button>`;
  }

  html += `
        <button class="page-nav" ${currentPage === totalPages ? "disabled" : ""} 
                onclick="${currentPage < totalPages ? `loadPromotions(${currentPage + 1})` : ""}">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;
  container.innerHTML = html;
}

async function editPromo(id) {
  try {
    const editUrl = PROMO_API_URL.replace("promotion-index", "promotion-edit");
    const response = await fetch(`${editUrl}?id=${id}`);
    const result = await response.json();

    if (result.success) {
      const promo = result.data;
      document.getElementById("edit_promotionId").value = promo.promotionId;
      document.getElementById("edit_name_promotion").value = promo.name;
      document.getElementById("edit_discount").value = promo.discount;
      document.getElementById("edit_discountType").value = promo.discountType;
      document.getElementById("edit_startDate").value = promo.startDate;
      document.getElementById("edit_endDate").value = promo.endDate;
      document.getElementById("edit_status").value = promo.status;

      document.getElementById("modalEditPromotion").style.display = "flex";
    } else {
      alert("Không tìm thấy dữ liệu!");
    }
  } catch (error) {
    console.error("Lỗi Fetch edit:", error);
  }
}

async function saveUpdatePromotion() {
  const id = document.getElementById("edit_promotionId").value;
  const name = document.getElementById("edit_name_promotion").value;
  const discount = document.getElementById("edit_discount").value;
  const discountType = document.getElementById("edit_discountType").value;
  const startDate = document.getElementById("edit_startDate").value;
  const endDate = document.getElementById("edit_endDate").value;
  const status = document.getElementById("edit_status").value;

  if (!name || !discount || !startDate || !endDate) {
    alert("Vui lòng điền đầy đủ thông tin!");
    return;
  }

  if (new Date(startDate) > new Date(endDate)) {
    alert("Ngày bắt đầu không được sau ngày kết thúc!");
    return;
  }

  const data = {
    promotionId: id,
    name: name,
    discount: discount,
    discountType: discountType,
    startDate: startDate,
    endDate: endDate,
    status: status,
  };

  try {
    const updateUrl = PROMO_API_URL.replace(
      "promotion-index",
      "promotion-update",
    );

    const response = await fetch(updateUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await response.json();

    if (result.success) {
      alert("Cập nhật khuyến mãi thành công!");
      closeModalPromotion();
      loadPromotions();
    } else {
      alert("Lỗi: " + result.message);
    }
  } catch (error) {
    console.error("Lỗi kết nối API:", error);
    alert("Không thể kết nối đến máy chủ!");
  }
}

function closeModalPromotion() {
  document.getElementById("modalEditPromotion").style.display = "none";
}

async function deletePromo(id) {
  if (
    !confirm(`Bạn có chắc chắn muốn xóa mã khuyến mãi #PROMO${id} này không?`)
  )
    return;

  try {
    const deleteUrl = PROMO_API_URL.replace(
      "promotion-index",
      "promotion-delete",
    );
    const response = await fetch(`${deleteUrl}?id=${id}`);
    const result = await response.json();

    if (result.success) {
      alert("Xóa thành công!");
      loadPromotions();
    } else {
      alert("Không thể xóa: " + (result.message || "Lỗi hệ thống"));
    }
  } catch (error) {
    console.error("Lỗi xóa:", error);
  }
}

async function addPromotion() {
  const name = document.getElementById("add_name").value;
  const discount = document.getElementById("add_discount").value;
  const discountType = document.getElementById("add_discountType").value;
  const startDate = document.getElementById("add_startDate").value;
  const endDate = document.getElementById("add_endDate").value;
  const status = document.getElementById("add_status").value;

  if (!name || !discount || !startDate || !endDate) {
    alert("Vui lòng điền đầy đủ thông tin!");
    return;
  }

  const data = {
    name: name,
    discount: discount,
    discountType: discountType,
    startDate: startDate,
    endDate: endDate,
    status: status,
  };

  try {
    const response = await fetch(
      "http://localhost:8088/SELLING-GLASSES/public/promotion-create",
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      },
    );

    const result = await response.json();

    if (result.success) {
      alert("Tạo khuyến mãi mới thành công!");
      closeAddModal();
      loadPromotions(); // Đã sửa loadModal() thành loadPromotions()
      const form = document.getElementById("formAddPromotion");
      if (form) form.reset();
    } else {
      alert("Lỗi: " + result.message);
    }
  } catch (error) {
    console.error("Lỗi:", error);
    alert("Không thể kết nối đến máy chủ!");
  }
}

function openAddModal() {
  document.getElementById("addPromotionModal").style.display = "flex";
}

function openAddPromotionModal() {
  const modal = document.getElementById("addPromotionModal");
  if (modal) {
    modal.style.display = "flex";
    const form = document.getElementById("formAddPromotion");
    if (form) form.reset();
  }
}

function closeAddModal() {
  const modal = document.getElementById("addPromotionModal");
  if (modal) {
    modal.style.display = "none";
  }
}
// ap dụng mã khuyến mãi cho sản phẩm
let currentPromoIdForApply = null; // Biến lưu ID khuyến mãi đang thao tác

async function openApplyModal(promoId) {
  currentPromoIdForApply = promoId;
  selectedToApply = []; // Reset mảng chọn mỗi lần mở mới (hoặc giữ lại tùy ý bạn)
  const productContainer = document.getElementById("product_list_container");
  productContainer.innerHTML = `<div class="text-center">Đang tải danh sách sản phẩm...</div>`;

  try {
    const response = await fetch(
      "http://localhost:8088/SELLING-GLASSES/public/get-all-products?format=json",
    );
    const result = await response.json();

    if (result.success) {
      // Thay vì dùng innerHTML += ..., hãy gọi hàm render chung để đồng bộ mảng selected
      renderProductList(result.data, "product_list_container");
      document.getElementById("applyPromotionModal").style.display = "flex";
    }
  } catch (error) {
    console.error("Lỗi Fetch:", error);
    productContainer.innerHTML = `<div style="color: red; padding: 10px;">${error.message}</div>`;
  }
}

function closeApplyModal() {
  document.getElementById("applyPromotionModal").style.display = "none";
}
async function submitApplyPromotion() {
  // Thay vì quét checkbox trên giao diện, hãy dùng trực tiếp mảng đã lưu
  const productIds = selectedToApply;

  if (productIds.length === 0) {
    alert("Vui lòng chọn ít nhất một sản phẩm!");
    return;
  }

  const formData = new FormData();
  formData.append("promotionId", currentPromoIdForApply);
  // Gửi toàn bộ ID trong mảng lưu trữ
  productIds.forEach((id) => formData.append("productIds[]", id));

  try {
    const response = await fetch(
      "http://localhost:8088/SELLING-GLASSES/public/apply-promotion",
      {
        method: "POST",
        body: formData,
      },
    );

    const result = await response.json();
    if (result.success) {
      alert(`Áp dụng thành công cho ${productIds.length} sản phẩm!`);
      selectedToApply = []; // Xóa bộ nhớ đệm sau khi thành công
      closeApplyModal();
      loadPromotions();
    } else {
      alert("Lỗi: " + result.message);
    }
  } catch (error) {
    console.error("Lỗi:", error);
  }
}
// hủy áp dụng mã khuyến mãi cho sản phẩm
// Hàm mở Modal Hủy
async function openCancelModal(promoId) {
  currentPromoIdForApply = promoId;
  selectedToCancel = []; // Reset mảng chọn khi mở modal mới
  const container = document.getElementById("product_list_cancel_container");
  container.innerHTML = `<div class="text-center">Đang tải danh sách sản phẩm...</div>`;

  try {
    const response = await fetch(
      "http://localhost:8088/SELLING-GLASSES/public/get-all-products?format=json",
    );
    const result = await response.json();

    if (result.success) {
      // Dùng hàm render chung để đồng bộ với mảng selectedToCancel
      renderProductList(result.data, "product_list_cancel_container");
      document.getElementById("cancelPromotionModal").style.display = "flex";
    }
  } catch (error) {
    console.error("Lỗi:", error);
    container.innerHTML = `<div style="color: red; padding: 10px;">Lỗi tải dữ liệu</div>`;
  }
}

// Hàm gửi yêu cầu Hủy lên Server
async function submitCancelPromotion() {
  // Lấy trực tiếp từ mảng lưu trữ thay vì quét DOM
  const productIds = selectedToCancel;

  if (productIds.length === 0) {
    alert("Vui lòng chọn ít nhất một sản phẩm để hủy!");
    return;
  }

  if (
    !confirm(
      `Bạn có chắc chắn muốn hủy khuyến mãi cho ${productIds.length} sản phẩm đã chọn?`,
    )
  ) {
    return;
  }

  const formData = new FormData();
  // Gửi ID khuyến mãi hiện tại (nếu backend cần)
  formData.append("promotionId", currentPromoIdForApply);
  productIds.forEach((id) => formData.append("productIds[]", id));

  try {
    const response = await fetch(
      "http://localhost:8088/SELLING-GLASSES/public/cancel-promotion",
      {
        method: "POST",
        body: formData,
      },
    );

    const result = await response.json();
    if (result.success) {
      alert(result.message);
      selectedToCancel = []; // Xóa bộ nhớ đệm sau khi thành công
      closeCancelModal();
      loadPromotions();
    } else {
      alert("Lỗi: " + result.message);
    }
  } catch (error) {
    console.error("Lỗi:", error);
    alert("Không thể kết nối đến máy chủ!");
  }
}

// hàm đóng modal hủy
function closeCancelModal() {
  document.getElementById("cancelPromotionModal").style.display = "none";
}
// === tìm kiếm sản phẩm ====
// Lưu trữ ID đã chọn cho 2 mục riêng biệt
//let searchTimer;
let selectedToApply = [];
let selectedToCancel = [];

function handleSearch(keyword, containerId) {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(async () => {
    const container = document.getElementById(containerId);
    // Không xóa innerHTML ngay để tránh giật lag, chỉ hiện thông báo nhỏ nếu cần

    try {
      const response = await fetch(
        `http://localhost:8088/SELLING-GLASSES/public/search-products?query=${encodeURIComponent(keyword)}`,
      );
      const result = await response.json();

      if (result.success) {
        renderProductList(result.data, containerId);
      }
    } catch (error) {
      console.error("Lỗi:", error);
    }
  }, 300);
}

function renderProductList(products, containerId) {
  const container = document.getElementById(containerId);
  container.innerHTML = "";

  // Xác định đang dùng mảng nào và class nào
  const isCancel = containerId === "product_list_cancel_container";
  const currentArray = isCancel ? selectedToCancel : selectedToApply;
  const checkboxClass = isCancel
    ? "cancel-product-checkbox"
    : "apply-product-checkbox";

  products.forEach((product) => {
    const isChecked = currentArray.includes(product.productId.toString())
      ? "checked"
      : "";

    const html = `
            <div class="product-item d-flex align-items-center p-2 border-bottom">
                <input type="checkbox" class="${checkboxClass} me-3" 
                       value="${product.productId}" ${isChecked}
                       onchange="toggleSelection(this, '${containerId}')">
                <div>
                    <div class="fw-bold">${product.name}</div>
                    <small class="text-muted">Mã: #${product.productId} - ${new Intl.NumberFormat("vi-VN").format(product.price)}đ</small>
                </div>
            </div>
        `;
    container.insertAdjacentHTML("beforeend", html);
  });
}

function toggleSelection(checkbox, containerId) {
  const isCancel = containerId === "product_list_cancel_container";
  let targetArray = isCancel ? selectedToCancel : selectedToApply;

  if (checkbox.checked) {
    if (!targetArray.includes(checkbox.value)) targetArray.push(checkbox.value);
  } else {
    const index = targetArray.indexOf(checkbox.value);
    if (index > -1) targetArray.splice(index, 1);
  }
}
