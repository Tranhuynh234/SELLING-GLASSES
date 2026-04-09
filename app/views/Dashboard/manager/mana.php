<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manager System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/mana.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div id="modal" class="modal-overlay">
        <div class="modal-card">
            <form id="productForm" onsubmit="event.preventDefault(); saveData();">
                <div class="modal-glow"></div>
                <div class="modal-head">
                    <div class="head-title">
                        <i class="fas fa-plus-circle"></i>
                        <h3 id="modalTitle">Thêm mới sản phẩm</h3>
                    </div>
                    <span class="close-modal" onclick="closeModal()">&times;</span>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editProductId" value="" />
                    
                    <div class="input-field">
                        <label>Tên mắt kính</label>
                        <div class="input-wrapper">
                            <i class="fas fa-pen-nib"></i>
                            <input type="text" id="input1" placeholder="Ví dụ: Kính Mắt Phi Công..." required />
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Mô tả sản phẩm</label>
                        <div class="input-wrapper">
                            <i class="fas fa-align-left"></i>
                            <input type="text" id="input2" placeholder="Chất liệu, đặc điểm nổi bật..." />
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Giá hiển thị (VNĐ)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-tag"></i>
                            <input type="text" id="inputProductPrice" placeholder="Ví dụ: 500000" required />
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Danh mục (Loại kính)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-list"></i>
                            <select id="inputCatId">
                                <option value="1">Gọng Nam</option>
                                <option value="2">Gọng Nữ</option>
                                <option value="3">Gọng Trẻ Em</option>
                                <option value="4">Chống Ánh Sáng Xanh</option>
                                <option value="5">Kính Đổi Màu</option>
                                <option value="6">Kính Siêu Mỏng</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Hình ảnh sản phẩm</label>
                        <div class="input-wrapper">
                            <i class="fas fa-image"></i>
                            <input type="file" id="inputImage" accept="image/*" />
                        </div>
                        <small id="currentImageName" style="color: #aaa; margin-top: 5px; display: block;"></small>
                    </div>

                    <div class="input-field">
                        <label>Biến thể (Màu|Size|Giá|Kho)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-tags"></i>
                            <input type="text" id="inputVariant" placeholder="Đen|M|500000|10" />
                        </div>
                        <small class="helper-text-amber">* Ngăn cách bằng dấu gạch đứng |</small>
                    </div>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn-ghost" onclick="closeModal()">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </button>
                    <button type="submit" class="btn-confirm">
                        <i class="fas fa-check"></i> Xác nhận lưu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="detailModal" class="modal-overlay" style="display: none">
        <div class="modal-card modal-detail-custom">
            <div class="modal-glow"></div>
            <div class="modal-head">
                <div class="head-title">
                    <i class="fas fa-eye"></i>
                    <h3>Chi tiết sản phẩm</h3>
                </div>
                <span class="close-modal" onclick="closeDetailModal()">&times;</span>
            </div>
            <div class="modal-body" id="detailContent"></div>
            <div class="modal-foot">
                <button class="btn-ghost" onclick="closeDetailModal()">
                    <i class="fas fa-times"></i> Đóng lại
                </button>
            </div>
        </div>
    </div>

    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="logo-box"><i class="fas fa-glasses"></i></div>
                <h2>GLASS<span>PRO</span></h2>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li onclick="showTab('dashboard')" class="nav-item active" id="btn-dashboard">
                        <i class="fas fa-th-large"></i> <span>Tổng quan</span>
                    </li>
                    <li onclick="showTab('product')" class="nav-item" id="btn-product">
                        <i class="fas fa-box-archive"></i> <span>Kho sản phẩm</span>
                    </li>
                    <li onclick="showTab('promo')" class="nav-item" id="btn-promo">
                        <i class="fas fa-tags"></i> <span>Khuyến mãi</span>
                    </li>
                    <li onclick="showTab('policy')" class="nav-item" id="btn-policy">
                        <i class="fas fa-file-shield"></i> <span>Chính sách</span>
                    </li>
                    <li class="nav-item" id="btn-user" onclick="showTab('user')">
                        <i class="fas fa-users"></i> <span>Quản lý người dùng</span>
                    </li>
                    <li class="nav-item" id="btn-combo" onclick="showTab('combo')">
                        <i class="fas fa-cubes"></i> <span>Quản lý combo</span>
                    </li>
                    <li class="nav-item" id="btn-permission" onclick="showTab('permission')">
                        <i class="fas fa-user-shield"></i> <span>Phân quyền hạn</span>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <div class="welcome-text">
                    <h1>Hệ thống quản trị</h1>
                    <p>Chào mừng trở lại!</p>
                </div>
                <div class="header-actions">
                    <div class="notif-box">
                        <i class="far fa-bell"></i>
                        <span class="dot"></span>
                    </div>
                </div>
            </header>

            <div class="content-body">
                <section id="dashboard" class="tab-pane active">
                    <div class="stats-grid">
                        <div class="stat-card glass blue">
                            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
                            <div class="stat-val">
                                <small>Sản phẩm</small>
                                <h2 id="totalProduct">0</h2>
                            </div>
                        </div>
                        <div class="stat-card glass purple">
                            <div class="stat-icon">
                                <i class="fas fa-ticket-simple"></i>
                            </div>
                            <div class="stat-val">
                                <small>Mã giảm giá</small>
                                <h2 id="totalPromo">0</h2>
                            </div>
                        </div>
                        <div class="stat-card glass green">
                            <div class="stat-icon"><i class="fas fa-shield-check"></i></div>
                            <div class="stat-val">
                                <small>Chính sách</small>
                                <h2 id="totalPolicy">0</h2>
                            </div>
                        </div>
                        <div class="stat-card glass gold">
                            <div class="stat-icon"><i class="fas fa-users-gear"></i></div>
                            <div class="stat-val">
                                <small>Khách hàng</small>
                                <h2>1,250</h2>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-visuals">
                        <div class="chart-container glass">
                            <h3><i class="fas fa-chart-line"></i> Hiệu suất doanh thu</h3>
                            <div style="position: relative; height: 300px; width: 100%">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                        <div class="recent-activities glass">
                            <h3><i class="fas fa-bolt"></i> Hoạt động mới</h3>
                            <div class="activity-list" id="logList">
                                <div class="log-item">Hệ thống đã sẵn sàng trực tuyến.</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="product" class="tab-pane">
                    <div class="pane-header">
                        <h2>Quản lý kho hàng</h2>
                        <button class="btn-confirm" onclick="openModal()">
                            <i class="fas fa-plus"></i> Thêm mới
                        </button>
                    </div>
                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên mắt kính</th>
                                    <th>Đơn giá</th>
                                    <th>Chi tiết</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="productTable"></tbody>
                        </table>
                    </div>
                </section>

                <section id="promo" class="tab-pane">
                    <div class="pane-header">
                        <h2>Chương trình khuyến mãi</h2>
                        <div style="display: flex; gap: 10px">
                            <input type="text" id="searchPromo" placeholder=" Tìm kiếm..." />
                            <button class="btn-confirm" onclick="openModal('promo')">
                                <i class="fas fa-percent"></i> Tạo mã
                            </button>
                        </div>
                    </div>
                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên chương trình</th>
                                    <th>Mức giảm</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="promoTable"></tbody>
                        </table>
                    </div>
                </section>

                <section id="policy" class="tab-pane">
                    <div class="pane-header">
                        <h2>Quy chuẩn & Chính sách</h2>
                        <button class="btn-confirm" onclick="openModal('policy')">
                            <i class="fas fa-shield"></i> Thêm chính sách
                        </button>
                    </div>
                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu đề chính sách</th>
                                    <th>Nội dung</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="policyTable"></tbody>
                        </table>
                    </div>
                </section>

                <section id="user" class="tab-pane">
                    <div class="pane-header">
                        <h2>Quản lý khách hàng</h2>
                        <button class="btn-confirm" onclick="openModal('user')">
                            <i class="fas fa-user-plus"></i> Thêm người dùng
                        </button>
                    </div>
                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Liên hệ</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="userTable"></tbody>
                        </table>
                    </div>
                </section>

                <section id="combo" class="tab-pane">
                    <div class="pane-header">
                        <h2>Quản lý Combo</h2>
                        <button class="btn-confirm" onclick="openModal('combo')">
                            <i class="fas fa-plus"></i> Thêm Combo
                        </button>
                    </div>
                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Combo</th>
                                    <th>Giá tiền</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="comboTable"></tbody>
                        </table>
                    </div>
                </section>

                <section id="permission" class="tab-pane">
                    <div class="pane-header">
                        <h2>Phân quyền nhân viên</h2>
                        <button class="btn-confirm" onclick="openModal('permission')">
                            <i class="fas fa-shield-alt"></i> Cấp quyền
                        </button>
                    </div>
                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên nhân viên</th>
                                    <th>Quyền hạn</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="permissionTable"></tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>
    <script src="/SELLING-GLASSES/public/assets/js/mana.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>