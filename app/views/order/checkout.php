<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán chuyển khoản - EYESGLASS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/checkout.css">
</head>
<body>
    <header class="checkout-header">
        <a href="/SELLING-GLASSES/public/cart" class="checkout-back">Quay lại giỏ hàng</a>
        <a href="/SELLING-GLASSES/public/home" class="checkout-brand">
            EYESGLASS<span>.</span>
        </a>
    </header>

    <main class="checkout-shell">
        <section class="checkout-form-panel">
            <div class="section-head">
                <p class="section-kicker">Thông tin giao hàng</p>
                <h1>Hoàn tất đơn chuyển khoản</h1>
                <p>Điền thông tin nhận hàng, quét QR ngân hàng của admin và gửi yêu cầu để hệ thống chuyển sang trạng thái chờ duyệt.</p>
            </div>

            <form id="checkout-form" class="checkout-form">
                <div class="field-grid two">
                    <label class="field">
                        <span>Họ và tên</span>
                        <input type="text" name="recipientName" id="recipientName" placeholder="Nguyễn Văn A" required>
                    </label>
                    <label class="field">
                        <span>Số điện thoại</span>
                        <input type="text" name="recipientPhone" id="recipientPhone" placeholder="09xxxxxxxx" required>
                    </label>
                </div>

                <div class="field-grid two">
                    <label class="field">
                        <span>Email</span>
                        <input type="email" name="recipientEmail" id="recipientEmail" placeholder="email@example.com" required>
                    </label>
                    <label class="field">
                        <span>Tỉnh / Thành phố</span>
                        <select name="city" id="city" required>
                            <option value="">Chọn tỉnh / thành phố</option>
                            <option value="Ho Chi Minh">TP. Ho Chi Minh</option>
                            <option value="Ha Noi">Ha Noi</option>
                            <option value="Da Nang">Da Nang</option>
                            <option value="Can Tho">Can Tho</option>
                            <option value="Hai Phong">Hai Phong</option>
                        </select>
                    </label>
                </div>

                <label class="field">
                    <span>Địa chỉ chi tiết</span>
                    <input type="text" name="detailAddress" id="detailAddress" placeholder="Số nhà, tên đường..." required>
                </label>

                <div class="field-grid two">
                    <label class="field">
                        <span>Quận / Huyện</span>
                        <input type="text" name="district" id="district" placeholder="Ví dụ: Bình Thạnh" required>
                    </label>
                    <label class="field">
                        <span>Phường / Xã</span>
                        <input type="text" name="ward" id="ward" placeholder="Ví dụ: Phường 25" required>
                    </label>
                </div>

                <label class="field">
                    <span>Ghi chú</span>
                    <textarea name="note" id="note" rows="4" placeholder="Lời nhắn cho admin hoặc shipper..."></textarea>
                </label>

                <input type="hidden" name="transferNote" id="transferNote">

                <div class="checkout-notice">
                    <strong>Cách hoạt động:</strong>
                    <span>Người dùng chuyển khoản theo QR bên phải, sau đó bấm gửi yêu cầu. Admin sẽ kiểm tra giao dịch và duyệt đơn sang trạng thái thành công.</span>
                </div>

                <button type="submit" id="submit-payment" class="submit-btn">Tôi đã chuyển khoản, gửi đơn chờ duyệt</button>
            </form>
        </section>

        <aside class="checkout-summary-panel">
            <div class="summary-sticky">
                <div class="summary-block">
                    <div class="summary-head">
                        <h2>Giỏ hàng <span id="cart-count-label">(0)</span></h2>
                    </div>
                    <div id="checkout-items" class="checkout-items"></div>
                </div>

                <div class="summary-block qr-block">
                    <div class="qr-copy">
                        <h3>QR ngân hàng của admin</h3>
                        <p>Quét mã để chuyển khoản đúng số tiền. Nội dung chuyển khoản nên giữ nguyên để admin dễ đối soát.</p>
                    </div>
                    <div class="qr-wrap">
                        <img id="bank-qr" src="/SELLING-GLASSES/public/assets/images/qr.png" alt="QR thanh toán ngân hàng">
                    </div>
                    <div class="bank-meta">
                        <div><span>Ngân hàng</span><strong id="bank-name">Vietcombank</strong></div>
                        <div><span>Số tài khoản</span><strong id="bank-account">123456789</strong></div>
                        <div><span>Chủ tài khoản</span><strong id="bank-owner">EYESGLASS VN</strong></div>
                        <div><span>Nội dung CK</span><strong id="bank-note"> Vui lòng ghi rõ Mã đơn hàng và SĐT khi chuyển khoản</strong></div>
                    </div>
                </div>

                <div class="summary-block totals-block">
                    <div class="prescription-promo">
                        <p>Bạn có đơn kính thuốc?</p>
                        <a href="/SELLING-GLASSES/public/index.php?url=prescription" class="btn-prescription">
                            <i class="icon-plus-custom">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </i>
                            <span>Nhập thông số mắt ngay</span>
                        </a>
                    </div>

                    <div class="total-row">
                        <span>Tạm tính</span>
                        <strong id="subtotal">0đ</strong>
                    </div>

                    <div class="total-row" id="lens-cost-row" style="color: #6b7280">
                        <span>Chi phí tròng kính</span>
                        <strong id="lens-cost">0đ</strong>
                    </div>
                    <div class="total-row">
                        <span>Giảm giá</span>
                        <strong id="discount">0đ</strong>
                    </div>

                    <div class="total-row">
                        <span>Phí giao hàng</span>
                        <strong id="shipping-fee">0đ</strong>
                    </div>

                    <div class="grand-total">
                        <span>Tổng</span>
                        <strong id="grand-total">0đ</strong>
                    </div>
                </div>
            </div>
        </aside>
    </main>

    <script src="/SELLING-GLASSES/public/assets/js/checkout.js"></script>
</body>
</html>
