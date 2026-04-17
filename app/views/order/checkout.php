<?php
// Kiểm tra xem session đã được start chưa 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lensCost = isset($_SESSION['prescription_total']) ? $_SESSION['prescription_total'] : 0;
// Kết nối database
$db = Database::connect();
$userId = $_SESSION['user']['userId'] ?? null;

$user = null;
$userPres = null;

if ($userId) {
    // 1. Lấy thông tin cá nhân để tự điền (Auto-fill) vào form giao hàng
    $queryUser = "SELECT u.name, u.email, u.phone, c.address 
                  FROM users u 
                  LEFT JOIN customers c ON u.userId = c.userId 
                  WHERE u.userId = :userId";
    $stmtUser = $db->prepare($queryUser);
    $stmtUser->execute([':userId' => $userId]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // 2. Lấy thông số đơn kính mẫu đã lưu trong hồ sơ
    $queryPres = "SELECT * FROM prescription WHERE userId = :userId LIMIT 1";
    $stmtPres = $db->prepare($queryPres);
    $stmtPres->execute([':userId' => $userId]);
    $userPres = $stmtPres->fetch(PDO::FETCH_ASSOC);
}
?>

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
                            <option value="Ha Noi">Ben Tre</option>
                            <option value="Da Nang">Long An</option>
                            <option value="Can Tho">Tay Ninh</option>
                            <option value="Hai Phong">Kien Giang</option>
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
                        
                        <?php if ($userPres): ?>
                            <div style="background: #fffbeb; border: 1px dashed #f59e0b; padding: 12px; border-radius: 12px; margin-top: 10px; margin-bottom: 10px;">
                                <p style="font-size: 0.8rem; color: #92400e; margin-bottom: 8px; font-weight: 500;">
                                    <i class="fas fa-eye"></i> Đã tìm thấy số đo mắt trong hồ sơ!
                                </p>
                                <button type="button" onclick="useSavedPrescription()" style="background: #d97706; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; cursor: pointer; width: 100%;">
                                    Sử dụng số đo đã lưu
                                </button>
                            </div>
                        <?php endif; ?>

                        <a href="/SELLING-GLASSES/public/index.php?url=prescription" class="btn-prescription">
                            <i class="icon-plus-custom">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </i>
                            <span><?php echo ($userPres) ? 'Nhập thông số mắt mới' : 'Nhập thông số mắt ngay'; ?></span>
                        </a>
                    </div>


                    <input type="hidden" id="server-lens-cost" value="<?= intval($lensCost) ?>">

                    <div class="total-row">
                        <span>Tạm tính</span>
                        <strong id="subtotal">0đ</strong>
                    </div>

                    <div class="total-row" id="lens-cost-row">
                        <span>Chi phí tròng kính</span>
                        <strong id="lens-cost"><?= number_format($lensCost, 0, ',', '.') ?>đ</strong>
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
