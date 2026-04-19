<!-- Combo Management Section -->
<div class="pane-header">
    <h2>Quản lý Combo Sản phẩm</h2>
</div>

<!-- Alert Message -->
<div id="alertBox"></div>

<!-- Search/Filter -->
<div class="combo-toolbar">
    <input type="text" id="searchInput" placeholder="Tìm kiếm combo theo tên...">
    <button id="btnSearch" type="button">Tìm kiếm</button>
    <button id="btnReset" type="button">Làm mới</button>
    <button type="button" onclick="openCreateModal()" style="background: #ff9800;">+ Tạo Combo Mới</button>
</div>

<!-- Table -->
<div class="table-wrapper glass">
    <table class="vip-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Combo</th>
                <th>Giá</th>
                <th>Số SP</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="comboTableBody">
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #999;">Đang tải dữ liệu...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Tạo/Sửa Combo -->
<div id="comboModal" class="modal-overlay" style="display: none;">
    <div class="modal-card">
        <div class="modal-glow"></div>
            <div class="modal-head">
                <div class="head-title">
                    <i class="fas fa-box"></i>
                    <h3 id="modalTitle">Tạo Combo Mới</h3>
                </div>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>

        <form id="comboForm">
            <input type="hidden" id="comboId">

            <div class="modal-body">
                <div class="input-field">
                    <label>Tên Combo *</label>
                    <div class="input-wrapper">
                        <i class="fas fa-tag"></i>
                        <input type="text" id="comboName" required placeholder="Ví dụ: Combo Gọng + Tròng Tiết Kiệm">
                    </div>
                </div>

                <div class="input-field">
                    <label>Giá Combo (VND) *</label>
                    <div class="input-wrapper">
                        <i class="fas fa-dollar-sign"></i>
                        <input type="number" id="comboPrice" required min="0" step="1000" placeholder="500000">
                    </div>
                </div>

                <div class="input-field">
                    <label>Mô tả</label>
                    <textarea id="comboDescription" placeholder="Mô tả chi tiết về combo..." style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; min-height: 80px;"></textarea>
                </div>

                <div class="input-field">
                    <label>Ảnh Combo</label>
                    <input type="file" id="comboImage" accept="image/*">
                    <img id="imagePreview" style="max-width: 200px; max-height: 200px; margin-top: 10px; border-radius: 4px; display: none;">
                </div>

                <div class="input-field">
                    <label>Chọn Sản phẩm *</label>
                    <input type="text" id="productSearch" placeholder="Tìm sản phẩm...">
                    <div id="productList" style="border: 1px solid #ddd; border-radius: 4px; max-height: 300px; overflow-y: auto; margin-top: 10px;"></div>
                </div>

                <div class="input-field">
                    <label>
                        <input type="checkbox" id="isActive" checked> Kích hoạt combo
                    </label>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-ghost" onclick="closeModal()">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="submit" class="btn-confirm">
                    <i class="fas fa-save"></i> Lưu Combo
                </button>
            </div>
        </form>
    </div>
</div>

