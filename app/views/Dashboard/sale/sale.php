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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                            <span>Khiếu nại / Đổi trả</span>
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
                <!-- <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search" />
                </div> -->

                <!-- Nút đăng xuất + Chat -->
                <div class="topbar-right">
                    <button id="btn-global-chat" class="chat-nav-btn" onclick="openGlobalChat()" style="margin-right: 10px;">
                        <i class="fas fa-comment-dots"></i>
                        <!-- <span>Chat</span> -->
                    </button>
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
                        <label>Tìm theo mã đơn/khách</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-customer" placeholder="Nhập mã hoặc tên khách..." />
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Trạng thái</label>
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-status="All">
                                Tất cả
                            </button>
                            <button class="filter-tab" data-status="Pending">
                                Chờ xử lý
                            </button>
                            <button class="filter-tab" data-status="Confirmed">
                                Đã xác nhận
                            </button>
                            <button class="filter-tab" data-status="Processing">
                                Đang xử lý
                            </button>
                            <button class="filter-tab" data-status="Shipped">
                                Đang giao
                            </button>
                            <button class="filter-tab" data-status="Delivered">
                                Hoàn tất
                            </button>
                            <button class="filter-tab" data-status="Cancelled">
                                Đã hủy
                            </button>
                            <button class="filter-tab" data-status="Returned">
                                Trả hàng
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
                            <tbody id="orders-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ================= PRE-ORDER ================= -->
            <section id="preorder-page" class="dashboard-body hidden">
                <h1 class="page-title">DANH SÁCH ĐƠN HÀNG PRE-ORDER</h1>

                <div class="toolbar-container">
                    <div class="search-group">
                        <label>Tìm theo mã đơn/khách</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-preorder-customer" placeholder="Nhập mã hoặc tên khách..." />
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
                            <button class="filter-tab active" data-status="all">Tất cả</button>
                            <button class="filter-tab" data-status="complaint">Khiếu nại</button>
                            <button class="filter-tab" data-status="return">Đổi trả</button>
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

    <!-- ================= CHI TIẾT ĐƠN HÀNG ================= -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content order-modal-content">
                <div class="modal-header order-modal-header">
                    <h5 class="modal-title" id="orderDetailTitle">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- LAYOUT BÊN TRONG CHI TIẾT ĐƠN -->
                <div class="modal-body order-details-container">
                    <div class="custom-modal-content"> <div class="row m-0">
                            <div class="col-md-5 side-panel">
                                <h6 class="section-title">BẢNG ĐIỀU KHIỂN SALES</h6>
                                
                                <div class="action-box">
                                    <p class="group-label">XỬ LÝ ĐƠN</p>
                                    <button class="btn-confirm-order hidden" onclick="handleUpdateStatus('Confirmed')">
                                        <span><i class="fas fa-check-circle"></i> Xác nhận đơn hàng</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </button>

                                    <button class="btn btn-request-action hidden" id="btn-request-action" type="button">
                                        <i class="fas fa-check"></i> Xác nhận yêu cầu
                                    </button>

                                    <button class="btn-logistic-order hidden" onclick="handleUpdateStatus('Processing')">
                                        <span><i class="fas fa-truck"></i> Chuyển giao cho đơn vị vận chuyển</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </button>

                                </div>

                                <hr style="border-top: 1px solid #000000; margin: 20px 0;">

                                <div class="action-box">
                                    <p class="group-label">HỖ TRỢ</p>
                                    <button class="btn btn-support-contact" onclick="handleContactCustomer()">
                                        <i class="fas fa-phone"></i> Liên hệ khách hàng
                                    </button>
                                </div>

                                <hr style="border-top: 1px solid #000000; margin: 20px 0;">

                                <div class="action-box hidden" id="requestContextBox">
                                    <p class="group-label">YÊU CẦU KHIẾU NẠI / ĐỔI TRẢ</p>
                                    <p><strong>Loại:</strong> <span id="requestTypeLabel">-</span></p>
                                    <p><strong>Lý do:</strong> <span id="requestReason">-</span></p>
                                    <p><strong>Ghi chú:</strong> <span id="requestNote">-</span></p>
                                    <p><strong>Nhân viên xử lý:</strong> <span id="requestStaffLabel">-</span></p>
                                    <p><strong>Trạng thái:</strong> <span id="requestStatusLabel">-</span></p>
                                    <div id="requestImageContainer" class="request-image hidden" style="margin-top:10px;">
                                        <strong>Ảnh:</strong>
                                        <div class="request-image-preview" style="margin-top:8px;">
                                            <img id="requestImagePreview" src="" alt="Ảnh yêu cầu" style="max-width:100%; border-radius:6px; border:1px solid #ddd;" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 main-panel">
                                <h6 class="section-title">THÔNG TIN CHI TIẾT</h6>

                                <div class="info-group">
                                    <p class="group-label">THÔNG TIN KHÁCH HÀNG</p>
                                    <div id="customerInfoDisplay" class="data-display-box">
                                        <p><strong>Họ tên:</strong> <span class="info-value" id="custName">---</span></p>
                                        <p><strong>Số điện thoại:</strong> <span class="info-value" id="custPhone">---</span></p>
                                        <p><strong>Địa chỉ:</strong> <span class="info-value" id="custAddress">---</span></p>
                                    </div>
                                </div>

                                <hr style="border-top: 1px solid #000000; margin: 20px 0;">

                                <div class="info-group">
                                    <p class="group-label">THÔNG TIN ĐƠN HÀNG</p>
                                    <div class="table-container">
                                        <table class="order-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Ảnh</th>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-center">Số Lượng</th>
                                                    <th class="text-center">Giá</th>
                                                </tr>
                                            </thead>
                                            <tbody id="orderDetailBody">
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CHAT LIÊN HỆ VỚI KHÁCH HÀNG -->
    <div id="chat-wrapper" class="chat-main-wrapper chat-hidden">
        <div class="chat-header-container">
            <div class="header-left">
                <i class="fas fa-comment-dots"></i>
                <span>Trung tâm tin nhắn</span>
            </div>
            <button type="button" class="close-inbox-btn" onclick="closeChat()">&times;</button>
        </div>
        
        <div class="chat-inbox-layout">
            <aside class="inbox-sidebar">
                <div class="inbox-search">
                    <input type="text" placeholder="Tìm kiếm hội thoại..." id="search-contact">
                </div>
                <ul id="conversation-list">
                    </ul>
            </aside>

            <section class="inbox-content">
                <div id="chat-active-header" class="active-user-header">
                    <span id="chat-title">Chọn khách hàng để hỗ trợ</span>
                </div>

                <div id="chat-body" class="inbox-chat-body">
                    <div class="chat-placeholder">
                        <i class="fas fa-comments"></i>
                        <p>Chọn một khách hàng từ danh sách bên trái để bắt đầu tư vấn!</p>
                    </div>
                </div>
                
                <div class="inbox-footer">
                    <div class="input-group-wrapper">
                        <input type="text" id="chat-input" placeholder="Nhập tin nhắn phản hồi...">
                        <button type="button" class="btn-send-inbox" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FILE JS -->
    <script src="/SELLING-GLASSES/public/assets/js/sale.js"></script>

    <!-- THƯ VIỆN CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>