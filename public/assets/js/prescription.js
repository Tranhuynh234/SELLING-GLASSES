document.addEventListener("DOMContentLoaded", function() {
    const lensSelect = document.getElementById("lensType");
    const lensPriceDisplay = document.getElementById("lensPriceDisplay");
    const totalPriceDisplay = document.getElementById("totalPriceDisplay");
    const form = document.getElementById("prescriptionForm");
    const fileInput = document.getElementById("file-upload");
    const fileNameDisplay = document.getElementById("file-name-display");

    // Hàm định dạng tiền tệ chuyên nghiệp 
    const formatVND = (amount) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
        }).format(amount).replace('₫', 'đ');
    };

    // Hàm cập nhật giá
    function updatePrice() {
    const price = parseInt(lensSelect.value) || 0;
        const processingFee = 50000;
        const total = price + processingFee;

        // Hiệu ứng thay đổi số mượt hơn
        lensPriceDisplay.innerText = formatVND(price);
        totalPriceDisplay.innerText = formatVND(total);

        document.getElementById("total_amount_input").value = total;
    }

    // Hiển thị tên file khi chọn ảnh
    if (fileInput) {
        fileInput.addEventListener("change", function() {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.innerHTML = `<span class="text-amber-600 font-bold">Đã chọn: ${this.files[0].name}</span>`;
            }
        });
    }

    // Lắng nghe vc thay đổi loại tròng
    lensSelect.addEventListener("change", updatePrice);

    // Kiểm tra trước khi gửi form (Validate)
    form.addEventListener("submit", function(e) {
        const pdLeft = document.querySelector('input[name="left_pd"]').value;
        const pdRight = document.querySelector('input[name="right_pd"]').value;

        if (!pdLeft || !pdRight) {
            e.preventDefault();
            alert("⚠️ Vui lòng cung cấp chỉ số PD (Khoảng cách đồng tử) để chúng tôi lắp tròng chính xác.");
            return;
        }

        // Có thể thêm loading state ở đây
        const btn = form.querySelector('.btn-submit-pro');
        btn.innerHTML = '<span>Đang xử lý...</span>';
        btn.classList.add('opacity-70', 'pointer-events-none');
    });

    // Chạy lần đầu để khởi tạo giá
    updatePrice();
});