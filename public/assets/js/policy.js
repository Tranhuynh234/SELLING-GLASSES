var policyData = {
    'doi-tra': {
        title: 'CHÍNH SÁCH ĐỔI TRẢ',
        content: `
            <h6>1. Thời gian áp dụng</h6>
            <p>Khách hàng có quyền đổi trả sản phẩm trong vòng <b>07 ngày</b> kể từ ngày nhận hàng.</p>
            <h6>2. Điều kiện đổi trả</h6>
            <p>- Sản phẩm còn đầy đủ hóa đơn, hộp, bao bì và quà tặng (nếu có).<br>
               - Tem niêm phong trên tròng kính/gọng kính còn nguyên vẹn.<br>
               - Sản phẩm không có dấu hiệu đã qua sử dụng hoặc bị tác động ngoại lực gây trầy xước sau khi nhận hàng.</p>
            <h6>3. Quy trình thực hiện</h6>
            <p>Liên hệ Hotline 1900 1234 hoặc chọn "Hỗ trợ khách hàng" để được hướng dẫn gửi hàng về trung tâm bảo hành.</p>`
    },
    'bao-hanh': {
        title: 'CHÍNH SÁCH BẢO HÀNH',
        content: `
            <h6>1. Bảo hành Gọng kính</h6>
            <p>Bảo hành 12 tháng đối với các lỗi kỹ thuật (mối hàn, ốc vít, lò xo) từ nhà sản xuất.</p>
            <h6>2. Bảo hành Tròng kính</h6>
            <p>Bảo hành 06 tháng cho lớp váng phủ (coating) nếu có hiện tượng bong tróc tự nhiên.</p>
            <h6>3. Dịch vụ miễn phí trọn đời</h6>
            <p>Thay đệm mũi, vệ sinh kính bằng sóng siêu âm và nắn chỉnh form kính hoàn toàn miễn phí.</p>`
    },
    'thanh-toan': {
        title: 'PHƯƠNG THỨC THANH TOÁN',
        content: `
            <p>EYESGLASS cung cấp các phương thức thanh toán an toàn sau:</p>
            <ul>
                <li><b>Chuyển khoản:</b> Ngân hàng Vietcombank - STK: 123456789 - Chủ TK: EYESGLASS VN.</li>
                <li>(Lưu ý: Vui lòng ghi rõ Mã đơn hàng và SĐT khi chuyển khoản)</li>
                <li><b>Ví điện tử:</b> Quét mã QR qua MoMo hoặc ZaloPay để nhận thêm ưu đãi hoàn tiền.</li>
            </ul>`
    },
    'giao-hang': {
        title: 'CHÍNH SÁCH GIAO HÀNG',
        content: `
            <h6>1. Phí vận chuyển</h6>
            <p>- Miễn phí giao hàng toàn quốc cho mọi hóa đơn từ <b>500.000đ</b>.<br>
               - Đơn hàng dưới 500k áp dụng phí ship đồng giá 30.000đ.</p>
            <h6>2. Thời gian dự kiến</h6>
            <p>- Khu vực nội thành TP.HCM: 1 - 2 ngày làm việc.<br>
               - Các khu vực khác: 3 - 5 ngày làm việc.</p>`
    }
};

/**
 * Hàm hiển thị Modal chính sách
 * @param {string} type - Key của chính sách cần hiển thị
 */
function showPolicy(type) {
    const data = policyData[type];
    
    // Kiểm tra dữ liệu có tồn tại không
    if (data) {
        const titleElement = document.getElementById('policyTitle');
        const bodyElement = document.getElementById('policyBody');
        const modalElement = document.getElementById('policyModal');

        if (titleElement && bodyElement && modalElement) {
            // 1. Cập nhật nội dung vào Modal
            titleElement.innerHTML = data.title;
            bodyElement.innerHTML = data.content;
            
            // 2. Kiểm tra và gọi Modal của Bootstrap
            try {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                modalInstance.show();
            } catch (error) {
                console.error("Lỗi: Thư viện Bootstrap JS chưa được tải thành công.", error);
                alert("Hệ thống đang bận, vui lòng thử lại sau giây lát!");
            }
        } else {
            console.error("Lỗi: Không tìm thấy các phần tử Modal trong HTML (ID: policyModal, policyTitle hoặc policyBody).");
        }
    }
}