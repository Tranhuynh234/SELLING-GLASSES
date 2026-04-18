<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EYESGLASS - Operations System Pro</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/ops.css">
</head>
<body>
    <div id="main-app">
        <div class="app-container">
            <aside class="sidebar">
                <div class="sidebar-logo">
                    EYESGLASS <span>OPS</span>
                </div>

                <nav class="sidebar-menu">
                    <div class="menu-label">QUẢN TRỊ</div>

                    <a href="#" class="nav-link active" data-tab="dashboard">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Tổng quan</span>
                    </a>

                    <a href="#" class="nav-link" data-tab="orders">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Đơn hàng</span>
                    </a>

                    <a href="#" class="nav-link" data-tab="shipping">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Vận chuyển</span>
                    </a>

                   

                <div class="sidebar-footer">
                    <button type="button" class="btn-logout-sidebar" onclick="opsApp.handleLogout?.()">
                        <i class="fa-solid fa-power-off"></i>
                        Đăng xuất
                    </button>
                </div>
            </aside>

            <main class="main-viewport">
                <header class="top-header">
                    <div class="header-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input
                            type="text"
                            id="global-search"
                            placeholder="Tìm kiếm nhanh mã đơn..."
                            oninput="opsApp.handleGlobalSearch?.(this.value)"
                        >
                    </div>
                </header>

                <section id="render-area" class="fade-in"></section>
            </main>
        </div>
    </div>

    <div id="app-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="modal-title-text">Tiêu đề</h3>
                <button type="button" class="modal-close" onclick="opsApp.closeModal()">&times;</button>
            </div>

            <div id="modal-content-body" class="modal-body"></div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="opsApp.closeModal()">Hủy</button>
                <button type="button" class="btn-confirm" id="modal-action-btn">Xác nhận</button>
            </div>
        </div>
    </div>

    <script src="/SELLING-GLASSES/public/assets/js/ops.js"></script>
</body>
</html>
