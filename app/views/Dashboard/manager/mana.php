<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manager System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/mana.css" />
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/promotion.css" />

    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/combo.css" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <input type="hidden" id="sessionStaffId" value="<?php echo $_SESSION['user']['staffId'] ?? ''; ?>">
    <div id="modalUpdate" class="modal-overlay" style="display: none;">
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
    <!-- hủy áp dụng promotion  -->
    <div id="cancelPromotionModal" class="modal-overlay" style="display: none;">
        <div class="modal-content modern-ui">
            <div class="modal-header">
                <h3><span class="header-icon" style="background: #e8f5e9; color: #27ae60;"><i
                            class="fas fa-magic"></i></span> Chọn sản phẩm hủy áp dụng</h3>
                <button class="close-x" onclick="closeCancelModal()">&times;</button>
            </div>
            <input type="text" id="search_cancel" class="form-control mb-3 product-search-input"
                placeholder="Tìm sản phẩm đang áp dụng..."
                oninput="handleSearch(this.value, 'product_list_cancel_container')">
            <div class="modal-body">
                <div id="product_list_cancel_container"
                    style="max-height: 350px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px; padding: 5px;">
                    <!-- Sản phẩm load vào đây -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modern-cancel" onclick="closeCancelModal()">Hủy</button>
                <!-- Nên dùng hàm đóng riêng -->
                <button class="btn-modern-confirm" onclick="submitCancelPromotion()" style="background: #e74c3c;">
                    <!-- Đổi màu đỏ cho đúng tính chất hủy -->
                    Xác nhận hủy áp dụng
                </button>
            </div>
        </div>
    </div>
    <!-- áp dụng promotion  -->
    <div id="applyPromotionModal" class="modal-overlay" style="display: none;">
        <div class="modal-content modern-ui">
            <div class="modal-header">
                <h3><span class="header-icon" style="background: #e8f5e9; color: #27ae60;"><i
                            class="fas fa-magic"></i></span> Chọn sản phẩm áp dụng</h3>
                <button class="close-x" onclick="closeApplyModal()">&times;</button>
            </div>
            <input type="text" id="search_apply" class="form-control mb-3 product-search-input"
                placeholder="Tìm tên hoặc mã sản phẩm..." oninput="handleSearch(this.value, 'product_list_container')">
            <div class="modal-body">
                <div id="product_list_container"
                    style="max-height: 350px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px; padding: 5px;">
                    <!-- Sản phẩm load vào đây -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modern-cancel" onclick="closeApplyModal()">Hủy</button>
                <button class="btn-modern-confirm" onclick="submitApplyPromotion()" style="background: #27ae60;">Xác
                    nhận áp dụng</button>
            </div>
        </div>
    </div>
    <!-- Modal Thêm Khuyến Mãi (Đã sửa để giống Modal Chỉnh sửa) -->
    <div id="addPromotionModal" class="modal-overlay" style="display: none;">
        <div class="modal-content modern-ui">
            <div class="modal-header">
                <h3><span class="header-icon"><i class="fas fa-plus-circle"></i></span> Thêm chương trình mới</h3>
                <button class="close-x" onclick="closeAddModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formAddPromotion">
                    <div class="input-group full-width">
                        <label>Tên chương trình</label>
                        <div class="input-wrapper">
                            <i class="fas fa-tag icon-input"></i>
                            <input type="text" id="add_name" placeholder="Tên khuyến mãi">
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <label>Mức giảm</label>
                            <div class="input-wrapper">
                                <i class="fas fa-percent icon-input"></i>
                                <input type="number" id="add_discount">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Loại giảm giá</label>
                            <div class="input-wrapper">
                                <i class="fas fa-list-ul icon-input"></i>
                                <select id="add_discountType">
                                    <option value="percent">Phần trăm</option>
                                    <option value="fixed">Số tiền</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <label>Ngày bắt đầu</label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-alt icon-input"></i>
                                <input type="date" id="add_startDate">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Ngày kết thúc</label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-check icon-input"></i>
                                <input type="date" id="add_endDate">
                            </div>
                        </div>
                    </div>

                    <div class="input-group full-width">
                        <label>Trạng thái</label>
                        <div class="input-wrapper status-wrapper">
                            <i class="fas fa-toggle-on icon-input"></i>
                            <select id="add_status">
                                <option value="1">Đang chạy</option>
                                <option value="0">Tạm dừng</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modern-cancel" onclick="closeAddModal()"><i class="fas fa-times"></i> Hủy bỏ</button>
                <button class="btn-modern-confirm" onclick="addPromotion()"><i class="fas fa-check"></i> Xác nhận
                    lưu</button>
            </div>
        </div>
    </div>
    <!-- Modal Chỉnh sửa Promotion -->
    <div id="modalEditPromotion" class="modal-overlay">
        <div class="modal-content modern-ui">
            <div class="modal-header">
                <h3><span class="header-icon"><i class="fas fa-plus-circle"></i></span> Chỉnh sửa chương trình</h3>
                <button class="close-x" onclick="closeModalPromotion()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formEditPromotion">
                    <input type="hidden" id="edit_promotionId">

                    <div class="input-group full-width">
                        <label>Tên chương trình</label>
                        <div class="input-wrapper">
                            <i class="fas fa-tag icon-input"></i>
                            <input type="text" id="edit_name_promotion" placeholder="Tên khuyến mãi">
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <label>Mức giảm</label>
                            <div class="input-wrapper">
                                <i class="fas fa-percent icon-input"></i>
                                <input type="number" id="edit_discount">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Loại giảm giá</label>
                            <div class="input-wrapper">
                                <i class="fas fa-list-ul icon-input"></i>
                                <select id="edit_discountType">
                                    <option value="percent">Phần trăm</option>
                                    <option value="fixed">Số tiền</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <label>Ngày bắt đầu</label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-alt icon-input"></i>
                                <input type="date" id="edit_startDate">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Ngày kết thúc</label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-check icon-input"></i>
                                <input type="date" id="edit_endDate">
                            </div>
                        </div>
                    </div>

                    <div class="input-group full-width">
                        <label>Trạng thái</label>
                        <div class="input-wrapper status-wrapper">
                            <i class="fas fa-toggle-on icon-input"></i>
                            <select id="edit_status">
                                <option value="1">Đang chạy</option>
                                <option value="0">Tạm dừng</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modern-cancel" onclick="closeModalPromotion()"><i class="fas fa-times"></i> Hủy
                    bỏ</button>
                <button class="btn-modern-confirm" onclick="saveUpdatePromotion()"><i class="fas fa-check"></i> Xác nhận
                    lưu</button>
            </div>
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
    <div id="modal" class="modal-overlay" style="display: none;">
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
                        <label>Danh mục</label>
                        <div class="input-wrapper">
                            <i class="fas fa-list"></i>
                            <select id="inputCatId">
                                <option value="">-- Chọn danh mục --</option>
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
                        <div class="input-wrapper" style="align-items: flex-start;">
                            <i class="fas fa-tags" style="margin-top: 10px;"></i>
                            <textarea id="inputVariant" placeholder="Đen|M|500000|10" rows="4"
                                style="width: 100%; border: none; outline: none; padding: 10px; font-family: inherit;"></textarea>
                        </div>
                        <small class="helper-text-amber">* Mỗi dòng là một biến thể. Ngăn cách bằng |</small>
                    </div>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn-ghost" onclick="closeModal()">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </button>
                    <button type="button" class="btn-modern-confirm" onclick="saveData()">
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

    <!-- Policy Modal -->
    <div id="policyModal" class="modal-overlay" style="display: none;">
        <div class="modal-card">
            <form id="policyForm" onsubmit="event.preventDefault(); savePolicyData();">
                <div class="modal-glow"></div>
                <div class="modal-head">
                    <div class="head-title">
                        <i class="fas fa-file-shield"></i>
                        <h3 id="policyModalTitle">Thêm chính sách mới</h3>
                    </div>
                    <span class="close-modal" onclick="closePolicyModal()">&times;</span>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editPolicyId" value="" />

                    <div class="input-field">
                        <label>Tiêu đề chính sách</label>
                        <div class="input-wrapper">
                            <i class="fas fa-heading"></i>
                            <input type="text" id="policyTitle" placeholder="Ví dụ: Chính sách giao hàng..." required />
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Nội dung chính sách</label>
                        <div class="input-wrapper" style="align-items: flex-start;">
                            <i class="fas fa-align-left" style="margin-top: 10px;"></i>
                            <textarea id="policyContent" placeholder="Nhập nội dung chính sách chi tiết..." rows="6"
                                style="width: 100%; border: none; outline: none; padding: 10px; font-family: inherit; resize: vertical;"></textarea>
                        </div>
                        <small class="helper-text-amber">* Bạn có thể sử dụng nhiều dòng để định dạng nội dung</small>
                    </div>

                    <div class="input-field">
                        <label>Loại chính sách</label>
                        <div class="input-wrapper">
                            <i class="fas fa-list"></i>
                            <select id="policyType" required>
                                <option value="">-- Chọn loại chính sách --</option>
                                <option value="shipping">Chính sách giao hàng</option>
                                <option value="warranty">Chính sách bảo hành</option>
                                <option value="return">Chính sách đổi trả</option>
                                <option value="other">Chính sách khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Trạng thái</label>
                        <div class="input-wrapper">
                            <i class="fas fa-toggle-on"></i>
                            <select id="policyStatus" required>
                                <option value="active">Kích hoạt</option>
                                <option value="inactive">Vô hiệu hóa</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn-ghost" onclick="closePolicyModal()">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </button>
                    <button type="submit" class="btn-confirm">
                        <i class="fas fa-check"></i> Xác nhận lưu
                    </button>
                </div>
            </form>
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
                                <h2 id="totalCustomer">0</h2>
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
                    <div id="productPagination" class="pagination-container"></div>
                </section>

                <section id="promo" class="tab-pane">
                    <div class="pane-header">
                        <h2>Chương trình khuyến mãi</h2>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <!-- Container cho ô tìm kiếm -->
                            <div class="search-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="searchPromo" placeholder="Tìm tên hoặc email..." />
                            </div>

                            <!-- Nút tạo mã giữ nguyên class của bạn -->
                            <button class="btn-confirm" onclick="openAddPromotionModal()">
                                <i class="fas fa-percent"></i> Tạo mã khuyến mãi
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
                            <tbody id="promotion-list-body"></tbody>
                        </table>
                    </div>
                    <div class="pagination-wrapper">
                        <div id="promo-pagination-container">

                        </div>
                    </div>
                </section>

                <section id="policy" class="tab-pane">
                    <div class="pane-header">
                        <h2>Quy chuẩn & Chính sách</h2>
                        <button class="btn-confirm" onclick="openPolicyModal()">
                            <i class="fas fa-shield"></i> Thêm chính sách
                        </button>
                    </div>
                    <div class="policies-grid">
                        <!-- Chính sách 1: Giao hàng -->
                        <div class="policy-card glass">
                            <div class="policy-header">
                                <i class="fas fa-truck policy-icon"></i>
                                <h3>Chính sách giao hàng</h3>
                            </div>
                            <div class="policy-content">
                                <ul>
                                    <li><strong>1. Phí vận chuyển</strong>
                                        <ul>
                                            <li>Miễn phí giao hàng toàn quốc cho mỗi hóa đơn từ 500.000đ.</li>
                                            <li>Đơn hàng dưới 500k áp dụng phí ship đồng giá 30.000đ.</li>
                                        </ul>
                                    </li>
                                    <li><strong>2. Thời gian dự kiến</strong>
                                        <ul>
                                            <li>Khu vực nội thành TP.HCM: 1 - 2 ngày làm việc.</li>
                                            <li>Các khu vực khác: 3 - 5 ngày làm việc.</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Chính sách 2: Bảo hành -->
                        <div class="policy-card glass">
                            <div class="policy-header">
                                <i class="fas fa-shield-alt policy-icon"></i>
                                <h3>Chính sách bảo hành</h3>
                            </div>
                            <div class="policy-content">
                                <ul>
                                    <li><strong>1. Bảo hành Gọng kính</strong>
                                        <ul>
                                            <li>Bảo hành 12 tháng đối với các lỗi kỹ thuật (mới hàn, ốc vít, lỏ xo) từ
                                                nhà sản xuất.</li>
                                        </ul>
                                    </li>
                                    <li><strong>2. Bảo hành Tròng kính</strong>
                                        <ul>
                                            <li>Bảo hành 06 tháng cho lớp vàng phủ (coating) nếu có hiện tượng bong trắc
                                                từ nhiên.</li>
                                        </ul>
                                    </li>
                                    <li><strong>3. Dịch vụ miễn phí trọn đời</strong>
                                        <ul>
                                            <li>Thay đệm mũi, vệ sinh kính bằng sóng siêu âm và năn chính form kính hoàn
                                                toàn miễn phí.</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Chính sách 3: Đổi trả -->
                        <div class="policy-card glass">
                            <div class="policy-header">
                                <i class="fas fa-undo policy-icon"></i>
                                <h3>Chính sách đổi trả</h3>
                            </div>
                            <div class="policy-content">
                                <ul>
                                    <li><strong>1. Thời gian áp dụng</strong>
                                        <ul>
                                            <li>Khách hàng có quyền đổi trả sản phẩm trong vòng 07 ngày kể từ ngày nhận
                                                hàng.</li>
                                        </ul>
                                    </li>
                                    <li><strong>2. Điều kiện đổi trả</strong>
                                        <ul>
                                            <li>Sản phẩm còn đầy đủ hộp, bao bì và quà tặng (nếu có).</li>
                                            <li>Tem niêm phong trên trong kính/gọng kính còn nguyên vẹn.</li>
                                            <li>Sản phẩm không có dấu hiệu đã qua sử dụng hoặc bị tác động ngoại lực gây
                                                trầy xước sau khi nhận hàng.</li>
                                        </ul>
                                    </li>
                                    <li><strong>3. Quy trình thực hiện</strong>
                                        <ul>
                                            <li>Liên hệ Hotline 1900 1234 hoặc chọn "Hỗ trợ khách hàng" để được hướng
                                                dẫn gửi hàng về trung tâm bảo hành.</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
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
                    <?php include __DIR__ . '/combo.php'; ?>
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

    <script src="/SELLING-GLASSES/public/assets/js/promotion.js"></script>

    <script src="/SELLING-GLASSES/public/assets/js/combo-manager.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>