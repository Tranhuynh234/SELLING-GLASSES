// 1. QUẢN LÝ TAB (CHUYỂN ĐỔI GIỮA CÁC MENU)
function showTab(tabId) {
    // Ẩn tất cả các tab
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });

    // Hiện tab được chọn
    const activePane = document.getElementById(tabId);
    if (activePane) {
        activePane.classList.add('active');
    }

    // Cập nhật trạng thái menu sidebar
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const activeBtn = document.getElementById('btn-' + tabId);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }

    // Nếu bấm vào tab Khuyến mãi thì tự động load dữ liệu
    if (tabId === 'promo') {
        loadPromotions();
    }
}

// 2. QUẢN LÝ MODAL (THÊM / SỬA)
function openModal(type) {
    const modal = document.getElementById('modal');
    const title = document.getElementById('modalTitle');
    modal.style.display = 'flex';

    // Tùy chỉnh tiêu đề dựa trên loại tab đang đứng
    if (type === 'promo') {
        title.innerText = "Tạo mã khuyến mãi mới";
        // Bạn có thể reset form hoặc thay đổi label ở đây nếu muốn
    } else if (type === 'product') {
        title.innerText = "Thêm sản phẩm mới";
    }
}

function closeModal() {
    const modal = document.getElementById('modal');
    modal.style.display = 'none';
    // Reset các input trong modal
    const inputs = modal.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.type !== 'hidden') input.value = '';
    });
}

// Đóng modal khi click ra ngoài vùng xám
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target == modal) {
        closeModal();
    }
}

// 3. KẾT NỐI API KHUYẾN MÃI (PROMOTION)
async function loadPromotions() {
    const tbody = document.getElementById('promoTable');
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Đang tải dữ liệu...</td></tr>';

    try {
        const response = await fetch('/SELLING-GLASSES/public/get-all-promotions');
        const result = await response.json();

        if (result.success) {
            tbody.innerHTML = ''; // Xóa dòng "Đang tải"

            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Chưa có chương trình khuyến mãi nào</td></tr>';
                return;
            }

            result.data.forEach((promo, index) => {
                // Xử lý logic trạng thái
                const today = new Date();
                const start = new Date(promo.startDate);
                const end = new Date(promo.endDate);
                
                let statusHtml = '';
                if (today < start) {
                    statusHtml = '<span style="color: #f59e0b; font-weight: bold;">Sắp diễn ra</span>';
                } else if (today >= start && today <= end) {
                    statusHtml = '<span style="color: #10b981; font-weight: bold;">Đang diễn ra</span>';
                } else {
                    statusHtml = '<span style="color: #ef4444; font-weight: bold;">Đã hết hạn</span>';
                }

                // Định dạng hiển thị % hoặc VNĐ
                const discountVal = parseFloat(promo.discount);
                const discountText = discountVal <= 100 ? `${discountVal}%` : `${discountVal.toLocaleString('vi-VN')}đ`;

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
            });

            // Cập nhật con số tổng ở Dashboard
            const totalPromoEl = document.getElementById('totalPromo');
            if (totalPromoEl) totalPromoEl.innerText = result.data.length;

        } else {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;">Lỗi: ${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:red;">Không thể kết nối máy chủ</td></tr>';
    }
}

// 4. CÁC HÀM XỬ LÝ DỮ LIỆU (SAVE / DELETE)
function saveData() {
    // Tạm thời hiển thị thông báo, sau này bạn sẽ dùng fetch POST để gửi dữ liệu lên createPromotion
    alert("Hệ thống đang xử lý lưu dữ liệu...");
    closeModal();
}

function deletePromo(id) {
    if (confirm("Bạn có chắc chắn muốn xóa khuyến mãi này không?")) {
        console.log("Xóa ID:", id);
        // Gửi fetch DELETE tới API ở đây
    }
}

// 5. KHỞI TẠO KHI TRANG LOAD XONG
document.addEventListener("DOMContentLoaded", () => {
    console.log("Admin System Ready!");
    
  
});