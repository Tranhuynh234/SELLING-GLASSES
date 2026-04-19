/** Combo Manager - Quản lý Combo */

const COMBO_API_BASE = "/SELLING-GLASSES/public";
let comboData = {
  allCombos: [],
  allProducts: [],
  currentEditingComboId: null,
  selectedProducts: [],
};

let comboManagerInitialized = false;

// INITIALIZATION
function initComboManager() {
  const comboTable = document.getElementById("comboTableBody");
  if (!comboTable) return;

  if (comboManagerInitialized) {
    loadAllCombos();
    return;
  }

  comboManagerInitialized = true;

  loadAllCombos();
  loadAllProducts();

  const form = document.getElementById("comboForm");
  if (form) {
    form.onsubmit = null;
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      saveCombo();
    });
  }

  const searchInput = document.getElementById("productSearch");
  if (searchInput) {
    searchInput.addEventListener("input", filterProductList);
  }

  const imageInput = document.getElementById("comboImage");
  if (imageInput) {
    imageInput.addEventListener("change", previewComboImage);
  }

  const btnSearch = document.getElementById("btnSearch");
  if (btnSearch) {
    btnSearch.addEventListener("click", searchCombos);
  }

  const btnReset = document.getElementById("btnReset");
  if (btnReset) {
    btnReset.addEventListener("click", resetSearch);
  }
}

// LOAD COMBOS
function loadAllCombos() {
  fetch(`${COMBO_API_BASE}/index.php?url=get-combos&active=0`)
    .then((res) => res.json())
    .then((data) => {
      if (data.success && data.data) {
        comboData.allCombos = data.data;
        renderComboTable();
      }
    })
    .catch((err) => showComboAlert("Lỗi tải combo: " + err, "error"));
}

function loadAllProducts() {
  fetch(`${COMBO_API_BASE}/index.php?url=get-all-products&format=json`)
    .then((res) => {
      if (!res.ok) {
        throw new Error("Server returned status " + res.status);
      }
      const contentType = res.headers.get("content-type") || "";
      if (contentType.indexOf("application/json") === -1) {
        return res.text().then((text) => {
          throw new Error("Expected JSON but got: " + text);
        });
      }
      return res.json();
    })
    .then((data) => {
      if (data && data.success && data.data) {
        comboData.allProducts = data.data;
        renderProductOptions();
      } else if (data && data.success && !data.data) {
        comboData.allProducts = [];
        renderProductOptions();
      } else if (data && data.message) {
        console.warn("loadAllProducts:", data.message);
      }
    })
    .catch((err) => {
      console.error("Lỗi tải sản phẩm", err);
      showComboAlert("Lỗi tải sản phẩm: " + err.message, "error");
    });
}

// RENDER COMBOS TABLE
function renderComboTable() {
  const tbody = document.getElementById("comboTableBody");
  if (!tbody) return;

  if (comboData.allCombos.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="7" style="text-align:center; padding:20px;">Chưa có combo nào</td></tr>';
    return;
  }

  tbody.innerHTML = comboData.allCombos
    .map((combo) => {
      const dateCreated = new Date(combo.createdAt).toLocaleDateString("vi-VN");
      const itemCount = combo.items ? combo.items.length : 0;
      const status = combo.isActive
        ? '<span style="color:green;">✓ Hoạt động</span>'
        : '<span style="color:red;">✗ Tắt</span>';
      const price = formatCurrency(combo.price);

      return `
            <tr>
                <td>${combo.comboId}</td>
                <td><strong>${combo.name}</strong></td>
                <td>${price}</td>
                <td>${itemCount}</td>
                <td>${status}</td>
                <td>${dateCreated}</td>
                <td>
                    <button class="btn-edit" onclick="editCombo(${combo.comboId})">Sửa</button>
                    <button class="btn-delete" onclick="deleteCombo(${combo.comboId})">Xóa</button>
                </td>
            </tr>
        `;
    })
    .join("");
}

// RENDER PRODUCTS OPTIONS
function renderProductOptions() {
  const list = document.getElementById("productList");
  if (!list) return;

  if (comboData.allProducts.length === 0) {
    list.innerHTML = '<div style="padding:10px;">Không có sản phẩm</div>';
    return;
  }

  list.innerHTML = comboData.allProducts
    .map((product) => {
      const isSelected = comboData.selectedProducts.some(
        (p) => p.productId === product.productId,
      );
      return `
            <label style="display:flex; align-items:center; padding:10px; border-bottom:1px solid #eee; cursor:pointer;">
                <input type="checkbox" value="${product.productId}" 
                       ${isSelected ? "checked" : ""}
                       onchange="toggleComboProduct(${product.productId}, this.checked)"
                       style="margin-right:10px;">
                <span>${product.name}</span>
            </label>
        `;
    })
    .join("");
}

function filterProductList() {
  const searchTerm = document
    .getElementById("productSearch")
    .value.toLowerCase();
  const list = document.getElementById("productList");
  if (!list) return;

  const filtered = comboData.allProducts.filter((p) =>
    p.name.toLowerCase().includes(searchTerm),
  );

  list.innerHTML = filtered
    .map((product) => {
      const isSelected = comboData.selectedProducts.some(
        (p) => p.productId === product.productId,
      );
      return `
            <label style="display:flex; align-items:center; padding:10px; border-bottom:1px solid #eee; cursor:pointer;">
                <input type="checkbox" value="${product.productId}" 
                       ${isSelected ? "checked" : ""}
                       onchange="toggleComboProduct(${product.productId}, this.checked)"
                       style="margin-right:10px;">
                <span>${product.name}</span>
            </label>
        `;
    })
    .join("");
}

// MODAL ACTIONS
function openCreateModal() {
  comboData.currentEditingComboId = null;
  comboData.selectedProducts = [];

  document.getElementById("comboId").value = "";
  document.getElementById("comboForm").reset();
  document.getElementById("modalTitle").textContent = "Tạo Combo Mới";
  document.getElementById("imagePreview").style.display = "none";

  renderProductOptions();

  const modal = document.getElementById("comboModal");
  modal.style.display = "block";
  document.body.style.overflow = "hidden";
}

function closeModalCombo() {
  document.getElementById("comboModal").style.display = "none";
  document.body.style.overflow = "auto";
  comboData.currentEditingComboId = null;
  comboData.selectedProducts = [];
}

function editCombo(comboId) {
  const combo = comboData.allCombos.find((c) => c.comboId === comboId);
  if (!combo) return;

  comboData.currentEditingComboId = comboId;
  // selectedProducts: chỉ lấy productId và quantity
  comboData.selectedProducts = (combo.items || []).map((item) => ({
    productId: item.productId,
    quantity: item.quantity || 1,
  }));

  console.log(
    "DEBUG editCombo - selectedProducts:",
    comboData.selectedProducts,
  );

  document.getElementById("comboId").value = combo.comboId;
  document.getElementById("comboName").value = combo.name;
  document.getElementById("comboPrice").value = combo.price;
  document.getElementById("comboDescription").value = combo.description || "";
  document.getElementById("isActive").checked = combo.isActive;

  if (combo.imagePath) {
    document.getElementById("imagePreview").src =
      `/SELLING-GLASSES/public/assets/images/products/${combo.imagePath}`;
    document.getElementById("imagePreview").style.display = "block";
  }

  document.getElementById("modalTitle").textContent = "Sửa Combo";
  renderProductOptions();

  const modal = document.getElementById("comboModal");
  modal.style.display = "block";
  document.body.style.overflow = "hidden";
}

// PRODUCT SELECTION
function toggleComboProduct(productId, isChecked) {
  if (isChecked) {
    if (!comboData.selectedProducts.find((p) => p.productId === productId)) {
      comboData.selectedProducts.push({ productId: productId, quantity: 1 });
    }
  } else {
    comboData.selectedProducts = comboData.selectedProducts.filter(
      (p) => p.productId !== productId,
    );
  }
}

// IMAGE PREVIEW
function previewComboImage(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      const preview = document.getElementById("imagePreview");
      preview.src = e.target.result;
      preview.style.display = "block";
    };
    reader.readAsDataURL(file);
  }
}

// SAVE COMBO
function saveCombo() {
  if (comboData.selectedProducts.length === 0) {
    showComboAlert("Vui lòng chọn ít nhất một sản phẩm", "error");
    return;
  }

  const btn = document.querySelector('#comboForm button[type="submit"]');
  btn.disabled = true;

  const isEdit = !!comboData.currentEditingComboId;
  const endpoint = isEdit ? "update-combo" : "create-combo";

  // Sử dụng FormData để upload file image
  const formData = new FormData();
  formData.append("name", document.getElementById("comboName").value);
  formData.append(
    "price",
    parseFloat(document.getElementById("comboPrice").value),
  );
  formData.append(
    "description",
    document.getElementById("comboDescription").value,
  );
  formData.append(
    "isActive",
    document.getElementById("isActive").checked ? 1 : 0,
  );
  formData.append("products", JSON.stringify(comboData.selectedProducts));

  console.log("DEBUG saveCombo:", {
    name: document.getElementById("comboName").value,
    price: parseFloat(document.getElementById("comboPrice").value),
    isActive: document.getElementById("isActive").checked,
    selectedProducts: comboData.selectedProducts,
    comboId: comboData.currentEditingComboId,
    isEdit: isEdit,
  });

  // Thêm file image nếu có
  const imageInput = document.getElementById("comboImage");
  if (imageInput && imageInput.files.length > 0) {
    formData.append("comboImage", imageInput.files[0]);
  }

  if (isEdit) {
    formData.append("comboId", comboData.currentEditingComboId);
  }

  fetch(`${COMBO_API_BASE}/index.php?url=${endpoint}`, {
    method: "POST",
    credentials: "include",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("DEBUG response:", data);
      if (data.success) {
        showComboAlert(
          data.message || (isEdit ? "Cập nhật thành công" : "Tạo thành công"),
          "success",
        );
        closeModal();
        loadAllCombos();
      } else {
        showComboAlert(data.error || "Lỗi không xác định", "error");
      }
    })
    .catch((err) => {
      console.error("DEBUG error:", err);
      showComboAlert("Lỗi: " + err, "error");
    })
    .finally(() => {
      btn.disabled = false;
    });
}

//  DELETE COMBO
function deleteCombo(comboId) {
  if (!confirm("Bạn chắc chắn muốn xóa combo này?")) return;

  fetch(`${COMBO_API_BASE}/index.php?url=delete-combo&id=${comboId}`, {
    method: "DELETE",
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showComboAlert("Xóa thành công", "success");
        loadAllCombos();
      } else {
        showComboAlert(data.error || "Lỗi xóa", "error");
      }
    })
    .catch((err) => showComboAlert("Lỗi: " + err, "error"));
}

// SEARCH COMBOS
function searchCombos() {
  const searchTerm = document.getElementById("searchInput").value;
  if (!searchTerm) {
    loadAllCombos();
    return;
  }

  fetch(
    `${COMBO_API_BASE}/index.php?url=search-combos&name=${encodeURIComponent(searchTerm)}`,
  )
    .then((res) => {
      if (!res.ok) {
        throw new Error("Server returned status " + res.status);
      }
      const contentType = res.headers.get("content-type") || "";
      if (contentType.indexOf("application/json") === -1) {
        return res.text().then((text) => {
          throw new Error("Expected JSON response but received: " + text);
        });
      }
      return res.json();
    })
    .then((data) => {
      if (data && data.success) {
        comboData.allCombos = data.data || [];
        renderComboTable();
      } else if (data && data.error) {
        showComboAlert(data.error, "error");
      }
    })
    .catch((err) => showComboAlert("Lỗi tìm kiếm: " + err.message, "error"));
}

//  RESET SEARCH
function resetSearch() {
  document.getElementById("searchInput").value = "";
  loadAllCombos();
}

// UTILITIES
function formatCurrency(value) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
}

function showComboAlert(message, type = "info") {
  const alertBox = document.getElementById("alertBox");
  if (!alertBox) return;

  const alertClass = type === "success" ? "alert-success" : "alert-error";
  alertBox.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;

  setTimeout(() => {
    alertBox.innerHTML = "";
  }, 4000);
}

window.addEventListener("click", (event) => {
  const modal = document.getElementById("comboModal");
  if (event.target === modal) {
    closeModal();
  }
});

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initComboManager);
} else {
  initComboManager();
}
