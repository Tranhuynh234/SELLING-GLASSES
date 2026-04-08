<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Staff</title>
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/sale.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <div class="app-container">
        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar">
            <!-- LOGO / BRAND -->
            <div class="sidebar-header-wrapper">
                <div class="sidebar-logo">
                    <a href="#" class="logo-wrapper">
                        <!-- Tên thương hiệu -->
                        <span class="logo-main">
                            <span class="full-name">EYEGLASS</span>
                            <span class="short-name">E</span>
                            <span class="dot">.</span>
                        </span>
                        <!-- Logo phụ -->
                        <span class="logo-sub">Sale</span>
                    </a>
                </div>
                <!--Nút Toggle-->
                <button id="sidebar-close" class="sidebar-toggle-inner">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- MENU ĐIỀU HƯỚNG -->
            <div class="sidebar-content-wrapper hide-on-collapse">
                <div class="sidebar-menu-content">
                    <div class="sidebar-title">ĐIỀU HƯỚNG CHÍNH</div>
                    <!-- Danh sách menu -->
                    <nav class="menu">
                        <div class="menu-item active" id="menu-dashboard">
                            <i class="fas fa-chart-line"></i> <span>Dashboard</span>
                        </div>
                        <!-- Danh sách đơn -->
                        <div class="menu-item" id="menu-orders">
                            <i class="fas fa-box"></i> <span>Danh sách đơn</span>
                        </div>
                        <!-- Pre-order -->
                        <div class="menu-item" id="menu-preorder">
                            <i class="fas fa-hourglass-half"></i> <span>Pre-order</span>
                        </div>
                        <!-- Khiếu nại -->
                        <div class="menu-item" id="menu-complaints">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Khiếu nại</span>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- THÔNG TIN USER -->
            <div class="sidebar-footer">
                <!-- Avatar -->
                <div id="user-avatar-auto" class="avatar-circle">
                    <span id="user-initials">--</span>
                </div>

                <!-- Thông tin -->
                <div class="user-details">
                    <div class="user-name" id="user-footer-name">Sale Staff</div>
                    <div class="user-id" id="user-footer-id">Số TK: #01</div>
                </div>

                <!-- Icon menu user -->
                <i class="fas fa-ellipsis-h"></i>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT ================= -->
        <main class="content">
            <header class="topbar">
                <!-- Ô tìm kiếm -->
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search" />
                </div>

                <!-- Nút đăng xuất -->
                <div class="topbar-right">
                    <a href="/SELLING-GLASSES/public/auth" style="text-decoration: none">
                        <button class="login-btn">
                            <i class="fas fa-user-circle"></i>
                            <span>Đăng Xuất</span>
                        </button>
                    </a>
                </div>
            </header>

            <!-- ================= DASHBOARD ================= -->
            <section id="dashboard-page" class="dashboard-body">
                <!-- TIÊU ĐỀ TRANG -->
                <h1 class="page-title">DASHBOARD TỔNG QUAN HIỆU SUẤT</h1>

                <!-- KHỐI THỐNG KÊ (4 CARD CHÍNH) -->
                <div class="stats-grid">
                    <!-- Card: Doanh thu -->
                    <div class="card card-dashboard">
                        <div class="card-label">
                            Doanh thu hôm nay<br />(Today's Revenue)
                        </div>
                        <div class="card-content">
                            <div class="card-value">
                                <i class="fas fa-sack-dollar"></i> 0 VND
                            </div>
                            <div class="card-trend trend-up">
                                <i class="fas fa-caret-up"></i> +0%
                            </div>
                        </div>
                    </div>

                    <!-- Card: Đơn mới -->
                    <div class="card card-order">
                        <div class="card-label">Số đơn mới<br />(New Orders)</div>
                        <div class="card-content">
                            <div class="card-value"><i class="fas fa-box"></i> 0 đơn</div>
                            <div class="card-trend trend-down">
                                <i class="fas fa-caret-down"></i> -0%
                            </div>
                        </div>
                    </div>

                    <!-- Card: Pre-order -->
                    <div class="card card-preorder">
                        <div class="card-label">
                            Pre-order CHỜ<br />(Pending Pre-orders)
                        </div>
                        <div class="card-content">
                            <div class="card-value">
                                <i class="fas fa-hourglass"></i> 0 đơn
                            </div>
                        </div>
                    </div>

                    <!-- Card: Khiếu nại -->
                    <div class="card card-complain">
                        <div class="card-label">
                            Khiếu nại ĐANG XỬ LÝ<br />(Processing Complaints)
                        </div>
                        <div class="card-content">
                            <div class="card-value">
                                <i class="fas fa-exclamation-triangle"></i> 0 đơn
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BẢNG ĐƠN HÀNG GẦN NHẤT -->
                <div class="table-card">
                    <!-- Tiêu đề -->
                    <div class="card-header">DANH SÁCH ĐƠN HÀNG MỚI NHẤT</div>

                    <!-- Bảng -->
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Tên khách</th>
                                    <th>Ngày nhận</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="recent-orders"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ================= DANH SÁCH ĐƠN ================= -->
            <section id="orders-page" class="dashboard-body hidden">
                <h1 class="page-title">DANH SÁCH ĐƠN HÀNG</h1>

                <div class="toolbar-container">
                    <div class="search-group">
                        <label>Tìm theo tên khách</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-customer" placeholder="Nhập tên khách hàng..." />
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Trạng thái</label>
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-status="all">
                                Tất cả
                            </button>
                            <button class="filter-tab" data-status="pending">
                                Chờ xử lý
                            </button>
                            <button class="filter-tab" data-status="confirmed">
                                Đã xác nhận
                            </button>
                            <button class="filter-tab" data-status="shipping">
                                Đang giao
                            </button>
                            <button class="filter-tab" data-status="completed">
                                Hoàn tất
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-wrapper">
                        <table class="main-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Tên khách</th>
                                    <th>Ngày nhận</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="all-orders-list"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ================= PRE-ORDER ================= -->
            <section id="preorder-page" class="dashboard-body hidden">
                <h1 class="page-title">DANH SÁCH ĐƠN HÀNG PRE-ORDER</h1>

                <div class="toolbar-container">
                    <div class="search-group">
                        <label>Tìm theo tên khách</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-preorder-customer" placeholder="Nhập tên khách hàng..." />
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Trạng thái</label>
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-status="all">
                                Tất cả
                            </button>
                            <button class="filter-tab" data-status="cho-ve">
                                Chờ về hàng
                            </button>

                            <button class="filter-tab" data-status="da-co">
                                Đã có hàng
                            </button>
                            <button class="filter-tab" data-status="tre-hang">
                                Trễ hàng
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-wrapper">
                        <table class="main-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách</th>
                                    <th>Ngày dự kiến</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 20%; text-align: center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="preorder-list"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ================= KHIẾU NẠI ================= -->
            <section id="complaint-page" class="dashboard-body hidden">
                <h1 class="page-title">DANH SÁCH KHIẾU NẠI / ĐỔI TRẢ</h1>

                <div class="toolbar-container">
                    <div class="search-group">
                        <label>Tìm theo mã đơn/khách</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-complaint" placeholder="Nhập mã hoặc tên khách..." />
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Trạng thái</label>
                        <div class="filter-tabs">
                            <button class="filter-tab active">Tất cả</button>
                            <button class="filter-tab">Khiếu nại</button>
                            <button class="filter-tab">Đổi trả</button>
                            <button class="filter-tab">Bảo hành</button>
                        </div>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-wrapper">
                        <table class="main-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách</th>
                                    <th>Lý do</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="complaint-list"></tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- FILE JS -->
    <script src="/SELLING-GLASSES/public/assets/js/sale.js"></script>

    <!-- THƯ VIỆN CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>