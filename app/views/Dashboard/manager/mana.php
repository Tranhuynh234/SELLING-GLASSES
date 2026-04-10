<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manager System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/mana.css" />
</head>

<body>
    <div id="modalUpdate" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Cập nhật thông tin</h2>
                <button class="close-btn" onclick="dongModal()">&times;</button>
            </div>

            <form id="formUpdateUser" class="modal-body">
                <input type="hidden" id="edit_userId">

                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" id="edit_name" placeholder="Nhập họ tên...">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="edit_email" placeholder="Nhập email...">
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" id="edit_phone" placeholder="Nhập số điện thoại...">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="dongModal()">Hủy</button>
                    <button type="submit" class="btn-save">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
    <div id="userModal" class="modal-overlay" style="display: none;">
        <div class="modal-card">
            <div class="modal-glow"></div>
            <div class="modal-head">
                <div class="head-title">
                    <i class="fas fa-user-plus"></i>
                    <h3>TẠO TÀI KHOẢN MỚI</h3>
                </div>
                <span class="close-modal" onclick="closeUserModal()">&times;</span>
            </div>

            <div class="modal-body">
                <div class="input-field">
                    <label>Họ và tên</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="u_name" placeholder="Nhập họ tên..." />
                    </div>
                </div>

                <div class="input-field">
                    <label>Email / Tài khoản</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="u_email" placeholder="example@gmail.com" />
                    </div>
                </div>

                <div class="input-field">
                    <label>Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="u_password" placeholder="Nhập mật khẩu..." />
                    </div>
                </div>


            </div>

            <div class="modal-foot">
                <button class="btn-ghost" onclick="closeUserModal()">
                    <i class="fas fa-times"></i> Hủy bỏ
                </button>
                <button class="btn-confirm" onclick="saveUser()">
                    <i class="fas fa-user-plus"></i> Thêm người dùng
                </button>
            </div>
        </div>
    </div>
    <div id="modal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-glow"></div>
            <div class="modal-head">
                <div class="head-title">
                    <i class="fas fa-plus-circle"></i>
                    <h3 id="modalTitle">Thêm mới</h3>
                </div>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>

            <div class="modal-body">
                <input type="hidden" id="editProductId" value="" />
                <div class="input-field">
                    <label id="lbl1">Tên mắt kính</label>
                    <div class="input-wrapper">
                        <i class="fas fa-pen-nib"></i>
                        <input type="text" id="input1" placeholder="Ví dụ: Kính Mắt..." />
                    </div>
                </div>

                <div class="input-field">
                    <label id="lbl2">Mô tả sản phẩm</label>
                    <div class="input-wrapper">
                        <i class="fas fa-align-left"></i>
                        <input type="text" id="input2" placeholder="Nhập mô tả chi tiết sản phẩm..." />
                    </div>
                </div>

                <div class="input-field">
                    <label>Giá sản phẩm (VNĐ)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-tag"></i>
                        <input type="text" id="inputProductPrice" placeholder="Ví dụ: 500000" />
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
                </div>

                <div class="input-field">
                    <label>Biến thể (Màu|Size|Giá|Kho)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-tags"></i>
                        <input type="text" id="inputVariant" placeholder="Đen|M|500000|10" />
                    </div>
                    <small class="helper-text-amber">* Ngăn cách bằng dấu gạch đứng |
                    </small>
                </div>
            </div>

            <div class="modal-foot">
                <button class="btn-ghost" onclick="closeModal()">
                    <i class="fas fa-times"></i> Hủy bỏ
                </button>
                <button class="btn-confirm" onclick="saveData()">
                    <i class="fas fa-check"></i> Xác nhận lưu
                </button>
            </div>
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
                    <!-- LOGOUT -->
                    <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-right-from-bracket"></i> Logout
                    </button>
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
                        <button class="btn-confirm" onclick="openModal('product')">
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
                        <div class="header-tools" style="display: flex; gap: 10px;">
                            <div class="search-box">
                                <input type="text" id="searchUser" placeholder="Tìm tên hoặc email..."
                                    onkeyup="handleSearchUser(event)"
                                    style="padding: 8px 15px; border-radius: 8px; border: 1px solid #ddd; outline: none;">
                            </div>
                            <button class="btn-confirm" onclick="openUserModal()">
                                <i class="fas fa-user-plus"></i> Thêm người dùng
                            </button>
                        </div>
                    </div>
                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Liên hệ</th>
                                    <th>Vai Trò</th>
                                    <th>Hành động</th>

                                </tr>
                            </thead>
                            <tbody id="userTable"></tbody>
                        </table>
                    </div>

                    <div id="pagination" class="pagination-container"></div>
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
                    </div>

                    <div class="table-wrapper glass">
                        <table class="vip-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên người dùng</th>
                                    <th>Email</th>
                                    <th>Quyền hạn</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>

                            <tbody id="permissionTable">
                                <!-- JS render -->
                            </tbody>
                        </table>
                    </div>

                    <!--  PHÂN TRANG -->
                    <div id="permissionPagination" class="pagination" style="margin-top: 16px;"></div>
                </section>
            </div>
        </main>
    </div>
    <script src="/SELLING-GLASSES/public/assets/js/mana.js"></script>
    <script src="/SELLING-GLASSES/public/assets/js/user.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>