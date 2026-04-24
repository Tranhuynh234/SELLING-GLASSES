// Đợi DOM load xong rồi gán sự kiện
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector(
    'input[placeholder="Tìm kiếm..."]',
  );

  if (searchInput) {
    let typingTimer;
    const doneTypingInterval = 400; // Đợi 0.4s sau khi ngừng gõ mới gọi API (Debounce)

    searchInput.addEventListener("input", function () {
      clearTimeout(typingTimer);
      const value = this.value.trim();

      typingTimer = setTimeout(() => {
        promoPage = 1; // Reset về trang 1 khi tìm kiếm mới
        loadPromotions(value);
      }, doneTypingInterval);
    });
  }

  // Gọi lần đầu để load toàn bộ dữ liệu
  loadPromotions();
});
let promoPage = 1;
const promoLimit = 6;

function loadPromotions(keyword = "") {
  const tableBody = document.getElementById("promoTable");
  if (!tableBody) return;

  // 1. Tạo URL khớp với Backend (keyword, status, page, limit)
  const url = `/SELLING-GLASSES/public/index.php?url=promotion-search&keyword=${encodeURIComponent(keyword)}&status=active&page=${promoPage}&limit=${promoLimit}&format=json`;

  fetch(url)
    .then((res) => res.json())
    .then((res) => {
      // Kiểm tra cấu trúc res.data.data như trong Thunder Client của bạn
      if (res.success && res.data && res.data.data) {
        const promotions = res.data.data;
        let html = "";

        if (promotions.length === 0) {
          tableBody.innerHTML = `<tr><td colspan="5" class="text-center">Không tìm thấy mã nào với từ khóa "${keyword}"</td></tr>`;
          renderPromoPagination(0);
          return;
        }

        // 2. Render dữ liệu vào bảng
        promotions.forEach((p, index) => {
          const stt = (promoPage - 1) * promoLimit + (index + 1);
          html += `
                        <tr>
                            <td class="text-center">${stt}</td>
                            <td><strong>${p.name}</strong></td>
                            <td class="text-center" style="color: #f39c12; font-weight: bold;">
                                ${parseFloat(p.discount).toFixed(2)}%
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">${p.status}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn-apply" onclick="applyPromotion('${p.promotionId}')">
                                    <i class="fas fa-check-circle"></i> Áp dụng
                                </button>
                            </td>
                        </tr>
                    `;
        });

        tableBody.innerHTML = html;

        // 3. Cập nhật phân trang dựa trên res.data.total từ Backend
        renderPromoPagination(res.data.total);
      }
    })
    .catch((err) => {
      console.error("Lỗi Fetch:", err);
      tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Lỗi kết nối server</td></tr>`;
    });
}

function changePromoPage(page) {
  promoPage = page;
  loadPromotions();
}

function renderPromoPagination(total) {
  const container = document.getElementById("promoPagination");
  if (!container) return;

  const totalPages = Math.ceil((total || 0) / promoLimit);

  if (totalPages <= 1) {
    container.innerHTML = "";
    return;
  }

  let html = "";

  // Nút TRƯỚC (Previous) - Chỉ hiện hoặc active khi không ở trang 1
  html += `
    <button 
      class="promo-page-btn ${promoPage === 1 ? "disabled" : ""}" 
      onclick="${promoPage > 1 ? `changePromoPage(${promoPage - 1})` : ""}"
    >
      <i class="fas fa-chevron-left"></i>
    </button>
  `;

  // Vòng lặp các con số trang
  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button 
        class="promo-page-btn ${i === promoPage ? "active" : ""}" 
        onclick="changePromoPage(${i})"
      >
        ${i}
      </button>
    `;
  }

  // Nút SAU (Next) - Chỉ hiện hoặc active khi chưa tới trang cuối
  html += `
    <button 
      class="promo-page-btn ${promoPage === totalPages ? "disabled" : ""}" 
      onclick="${promoPage < totalPages ? `changePromoPage(${promoPage + 1})` : ""}"
    >
      <i class="fas fa-chevron-right"></i>
    </button>
  `;

  container.innerHTML = html;
}

// ==========================
// Apply Promotion Modal + API
// ==========================
let currentApplyPromotionId = null;
let selectedApplyProducts = new Set();
let allApplyProducts = [];

function openApplyModal(promotionId, promoName) {
  currentApplyPromotionId = promotionId;
  selectedApplyProducts.clear();

  // Build modal content
  const html = `
    <div style="text-align: left;">
      <div style="margin-bottom:12px; font-weight:700; font-size:16px;">🔎 Tìm sản phẩm:</div>
      <input id="promoApplySearch" placeholder="Nhập tên sản phẩm..." style="width:100%; padding:8px; margin-bottom:12px; border:1px solid #ddd; border-radius:6px;">
      <div id="promoApplyList" style="max-height:320px; overflow:auto; border:1px solid #f0f0f0; border-radius:6px; padding:8px;">
        <div style="text-align:center; color:#888;">Đang tải danh sách...</div>
      </div>
    </div>
  `;

  Swal.fire({
    title: `Áp dụng: ${promoName}`,
    html,
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonText: "✓ Áp dụng",
    cancelButtonText: "Hủy",
    width: 600,
    didOpen: () => {
      // Load products
      loadProductsForApply();

      const input = document.getElementById("promoApplySearch");
      input.addEventListener("input", (e) =>
        filterApplyList(e.target.value.trim()),
      );
    },
  }).then((res) => {
    if (res.isConfirmed) {
      const productIds = Array.from(selectedApplyProducts);
      if (productIds.length === 0) {
        Swal.fire("Lỗi", "Vui lòng chọn ít nhất một sản phẩm", "error");
        return;
      }

      applyPromotionToProducts(currentApplyPromotionId, productIds);
    }
  });
}

function loadProductsForApply() {
  const listEl = document.getElementById("promoApplyList");
  if (!listEl) return;

  fetch("/SELLING-GLASSES/public/index.php?url=get-all-products&format=json")
    .then((r) => r.json())
    .then((res) => {
      if (!res.success || !Array.isArray(res.data)) {
        listEl.innerHTML =
          '<div style="color:red; text-align:center;">Không thể tải danh sách sản phẩm</div>';
        return;
      }

      allApplyProducts = res.data;
      renderApplyList(allApplyProducts);
    })
    .catch((err) => {
      console.error("Load products error", err);
      listEl.innerHTML =
        '<div style="color:red; text-align:center;">Lỗi khi tải sản phẩm</div>';
    });
}

function renderApplyList(products) {
  const listEl = document.getElementById("promoApplyList");
  if (!listEl) return;

  if (!products.length) {
    listEl.innerHTML =
      '<div style="text-align:center; color:#666;">Không có sản phẩm</div>';
    return;
  }

  const html = products
    .map((p) => {
      const price =
        p.price !== undefined ? Number(p.price).toLocaleString() + "đ" : "0đ";
      return `
      <label style="display:flex; align-items:center; gap:10px; padding:8px; border-bottom:1px solid #f5f5f5;">
        <input type="checkbox" data-pid="${p.productId}" onchange="toggleApplyProduct(this)">
        <div style="flex:1;">
          <div style="font-weight:600">${escapeHtml(p.name)}</div>
          <div style="color:#888; font-size:12px">Giá hiện tại: ${price}</div>
        </div>
      </label>
    `;
    })
    .join("\n");

  listEl.innerHTML = html;
}

function toggleApplyProduct(checkbox) {
  const pid = Number(checkbox.dataset.pid);
  if (checkbox.checked) selectedApplyProducts.add(pid);
  else selectedApplyProducts.delete(pid);
}

function filterApplyList(keyword) {
  const filtered = allApplyProducts.filter((p) =>
    p.name.toLowerCase().includes(keyword.toLowerCase()),
  );
  renderApplyList(filtered);
}

function applyPromotionToProducts(promotionId, productIds) {
  fetch("/SELLING-GLASSES/public/index.php?url=promotion-apply", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ promotionId, productIds }),
  })
    .then((r) => r.json())
    .then((res) => {
      if (res.success) {
        Swal.fire("Thành công", "Áp dụng khuyến mãi thành công", "success");
        loadPromotions();
      } else {
        Swal.fire("Lỗi", res.message || "Áp dụng thất bại", "error");
      }
    })
    .catch((err) => {
      console.error("Apply error", err);
      Swal.fire("Lỗi", "Không thể kết nối server", "error");
    });
}

function escapeHtml(text) {
  return String(text)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
