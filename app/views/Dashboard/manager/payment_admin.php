<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payment Control</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/payment_admin.css">
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="sidebar-brand">EYESGLASS<span>.</span></div>
            <p class="sidebar-copy">Bảng quản lý thanh toán chuyển khoản. Admin duyệt đơn từ `Pending` sang `Success` ngay trên cùng một màn hình.</p>
            <nav class="sidebar-nav">
                <button class="sidebar-link active" data-tab="pending">Pending</button>
                <button class="sidebar-link" data-tab="success">Success</button>
            </nav>
            <div class="sidebar-actions">
                <a href="/SELLING-GLASSES/public/home">Về trang chủ</a>
                <button type="button" id="admin-logout">Đăng xuất</button>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <p class="admin-kicker">Admin payment</p>
                    <h1>Quản lý xác nhận chuyển khoản</h1>
                </div>
                <div class="admin-stats">
                    <div class="stat-card">
                        <span>Pending</span>
                        <strong id="pending-count">0</strong>
                    </div>
                    <div class="stat-card success">
                        <span>Success</span>
                        <strong id="success-count">0</strong>
                    </div>
                </div>
            </header>

            <section class="admin-panel">
                <div class="panel-head">
                    <h2 id="panel-title">Danh sách chờ duyệt</h2>
                    <button id="refresh-payments" class="refresh-btn" type="button">Làm mới</button>
                </div>

                <div class="table-wrapper">
                    <table class="payment-table">
                        <thead>
                            <tr>
                                <th>Payment</th>
                                <th>Đơn hàng</th>
                                <th>Khách hàng</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Địa chỉ</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="payment-table-body"></tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="/SELLING-GLASSES/public/assets/js/payment_admin.js"></script>
</body>
</html>
