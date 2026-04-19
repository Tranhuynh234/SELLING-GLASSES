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

function viewPromotion(id) {
  fetch(
    `/SELLING-GLASSES/public/index.php?url=promotion-detail&promotionId=${id}`,
  )
    .then((r) => r.json())
    .then((res) => {
      alert(JSON.stringify(res.data, null, 2));
    });
}
