// =============================
// USER MODULE (API ONLY)
// =============================
let currentPage = 1;
const pageSize = 5;
let allUsers = [];

const USER_API = "/SELLING-GLASSES/public/get-users";

// =============================
// LOAD USERS
// =============================
async function loadUsers() {
  try {
    const res = await fetch(USER_API);
    const result = await res.json();

    if (!result.success) {
      showToast(result.message || "Lỗi load user", "error");
      return;
    }

    allUsers = result.data;
    currentPage = 1;

    renderUsers();
    renderPagination();
  } catch (err) {
    console.error(err);
    showToast("Không thể kết nối server", "error");
  }
}

// =============================
// RENDER TABLE
// =============================
function renderUsers() {
  const tbody = document.getElementById("userTable");
  if (!tbody) return;

  const start = (currentPage - 1) * pageSize;
  const end = start + pageSize;

  const users = allUsers.slice(start, end);

  tbody.innerHTML = users
    .map(
      (u, i) => `
      <tr>
        <td>${start + i + 1}</td>

        <td>
          <div style="font-weight:600">${u.name}</div>
        </td>

        <td>
          <div>${u.email}</div>
          <div style="color:#777">${u.phone}</div>
        </td>

        <!-- ROLE COLUMN -->
        <td>
          <span style="
            padding:6px 12px;
            border-radius:10px;
            font-size:12px;
            color:white;
            background:${u.role === "staff" ? "#d97706" : "#6b7280"};
          ">
            ${u.role === "staff" ? "Nhân Viên" : "Khách Hàng"}
          </span>
        </td>

        <!-- ACTION COLUMN -->
        <td>
          <div class="action-btns" style="display:flex; gap:8px;">

            <button class="btn-edit"
              onclick="showEditUser('${u.userId}', '${u.name}', '${u.email}', '${u.phone}')"
              style="background:#f39c12; color:white; border:none; padding:6px 12px; border-radius:8px; cursor:pointer;">
              <i class="fas fa-edit"></i> Sửa
            </button>

            <button class="btn-delete"
              onclick="deleteUser('${u.userId}')"
              style="background:#e74c3c; color:white; border:none; padding:6px 12px; border-radius:8px; cursor:pointer;">
              <i class="fas fa-trash"></i> Xóa
            </button>

          </div>
        </td>

      </tr>
    `,
    )
    .join("");
}
function renderPagination() {
  const container = document.getElementById("pagination");
  if (!container) return;

  const totalPages = Math.ceil(allUsers.length / pageSize);
  if (totalPages <= 1) {
    container.innerHTML = ""; // Ẩn nếu chỉ có 1 trang
    return;
  }

  let html = "";

  // Nút TRƯỚC
  html += `
    <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}>
      <i class="fas fa-chevron-left"></i>
    </button>
  `;

  // Các nút SỐ TRANG
  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button onclick="changePage(${i})" class="${i === currentPage ? "active" : ""}">
        ${i}
      </button>
    `;
  }

  // Nút SAU
  html += `
    <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? "disabled" : ""}>
      <i class="fas fa-chevron-right"></i>
    </button>
  `;

  container.innerHTML = html;
}
// Hàm chuyển trang
function changePage(page) {
  const totalPages = Math.ceil(allUsers.length / pageSize);

  // Kiểm tra điều kiện chặn trang hợp lệ
  if (page < 1 || page > totalPages) return;

  // Cập nhật trang hiện tại
  currentPage = page;

  // Render lại dữ liệu và thanh phân trang mới
  renderUsers();
  renderPagination();
}
// ========== thêm user ===========
// user.js
// =============================
// Hàm mở/đóng modal
function openUserModal() {
  document.getElementById("userModal").style.display = "flex";
}

function closeUserModal() {
  document.getElementById("userModal").style.display = "none";
  // Xóa sạch form sau khi đóng
  document.getElementById("u_name").value = "";
  document.getElementById("u_email").value = "";
  document.getElementById("u_password").value = "";
}

// Hàm lưu User
async function saveUser() {
  const name = document.getElementById("u_name").value;
  const email = document.getElementById("u_email").value;
  const password = document.getElementById("u_password").value;

  if (!name || !email || !password) {
    alert("Vui lòng điền đầy đủ thông tin!");
    return;
  }

  // Tạo FormData khớp với ảnh Thunder Client của bạn
  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("password", password);

  // Gán giá trị mặc định cho các trường API yêu cầu nhưng không có trong HTML
  formData.append("phone", "0000000000"); // Hoặc để trống nếu API cho phép
  formData.append("role", "customer"); // Mặc định là khách hàng
  formData.append("address", "N/A"); // Mặc định chưa xác định

  try {
    const response = await fetch("/SELLING-GLASSES/public/create-user", {
      method: "POST",
      body: formData, // Gửi dưới dạng Form
    });

    const result = await response.json();

    if (result.success || response.ok) {
      alert("Thêm thành công!");
      closeUserModal();
      loadUsers(); // Cập nhật lại danh sách bảng
    } else {
      alert("Lỗi: " + (result.message || "Không thể thêm user"));
    }
  } catch (error) {
    console.error("Lỗi kết nối:", error);
    alert("Lỗi kết nối server!");
  }
}
// =============== tìm kiếm user
// Biến lưu trữ timeout để xử lý debounce (tránh gọi API quá nhiều lần khi đang gõ)
let searchTimer;

/**
 * Hàm xử lý sự kiện khi người dùng gõ vào ô tìm kiếm
 */
function handleSearchUser(event) {
  const keyword = event.target.value.trim();

  // Xóa timer cũ nếu người dùng vẫn đang gõ
  clearTimeout(searchTimer);

  // Thiết lập timer mới: Đợi 500ms sau khi ngừng gõ mới thực thi tìm kiếm
  searchTimer = setTimeout(() => {
    executeSearch(keyword);
  }, 500);
}

/**
 * Hàm thực hiện gọi API tìm kiếm
 */
async function executeSearch(keyword) {
  if (!keyword) {
    loadUsers(); // Nếu trống thì quay về danh sách gốc
    return;
  }

  try {
    const response = await fetch(
      `/SELLING-GLASSES/public/search-users?keyword=${encodeURIComponent(keyword)}`,
    );
    const result = await response.json();

    if (result.success) {
      // CẬP NHẬT BIẾN TỔNG để dùng chung với hệ thống phân trang hiện có
      allUsers = result.data;
      currentPage = 1; // Reset về trang 1 cho kết quả tìm kiếm

      renderUsers(); // Dùng hàm render gốc của bạn
      renderPagination();

      // Đảm bảo thanh phân trang hiện lại nếu có nhiều kết quả
      const pagination = document.getElementById("pagination");
      if (pagination) pagination.style.display = "flex";
    } else {
      const tbody = document.getElementById("userTable");
      tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 20px;">Không tìm thấy kết quả.</td></tr>`;
      document.getElementById("pagination").innerHTML = "";
    }
  } catch (error) {
    console.error("Lỗi tìm kiếm:", error);
  }
}

/**
 * Hàm bổ trợ để vẽ dữ liệu lên bảng (Render Table)
 */
function displayUsers(users) {
  const tableBody = document.getElementById("userTable");
  let html = "";

  users.forEach((user, index) => {
    html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <div class="user-info">
                        <strong>${user.fullname || user.name}</strong>
                    </div>
                </td>
                <td>
                    <div><i class="fas fa-envelope" style="font-size: 12px;"></i> ${user.email}</div>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="editUser(${user.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn-delete" onclick="deleteUser(${user.id})"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `;
  });

  tableBody.innerHTML = html;
}
// update user
// Hàm mở Modal Update và đổ dữ liệu cũ vào các ô input
function showEditUser(id, name, email, phone) {
  // Điền dữ liệu vào các input trong modal dựa trên ID đã đặt ở HTML
  document.getElementById("edit_userId").value = id;
  document.getElementById("edit_name").value = name;
  document.getElementById("edit_email").value = email;
  document.getElementById("edit_phone").value = phone;

  // Hiển thị modal lên
  document.getElementById("modalUpdate").style.display = "flex";
}

// Hàm đóng Modal Update
function dongModal() {
  document.getElementById("modalUpdate").style.display = "none";
}
// ======= delete user
// =============================
// DELETE USER
// =============================
async function deleteUser(userId) {
  if (!confirm("Bạn có chắc muốn xóa user này không?")) return;

  try {
    const response = await fetch(`/SELLING-GLASSES/public/delete-user`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        userId: userId,
      }),
    });

    const result = await response.json();

    if (result.success) {
      alert("Xóa thành công!");
      loadUsers(); // reload lại bảng
    } else {
      alert("Xóa thất bại: " + (result.message || "Unknown error"));
    }
  } catch (error) {
    console.error("Lỗi delete user:", error);
    alert("Không thể kết nối server!");
  }
}
// =========== logout =========
async function logout() {
  if (!confirm("Bạn có chắc muốn đăng xuất không?")) return;

  try {
    const res = await fetch("/SELLING-GLASSES/public/logout", {
      method: "POST",
      credentials: "include", // nếu dùng session/cookie
    });

    const data = await res.json();

    if (data.success) {
      // xoá local nếu có
      localStorage.clear();

      // chuyển trang login
      window.location.href = "/SELLING-GLASSES/public/auth";
    } else {
      alert(data.message || "Logout thất bại");
    }
  } catch (err) {
    console.error("Logout error:", err);
    alert("Không thể logout!");
  }
}
// ======== phân quyền =============
// =============================
// PERMISSION MODULE
// =============================
let permissionPage = 1;
const permissionPageSize = 5;
let allPermissions = [];
async function loadPermissions() {
  try {
    const res = await fetch("/SELLING-GLASSES/public/get-users");
    const result = await res.json();

    if (!result.success) {
      alert("Lỗi load permission");
      return;
    }

    allPermissions = result.data;
    permissionPage = 1;

    renderPermissions();
    renderPermissionPagination();
  } catch (err) {
    console.error(err);
  }
}
function renderPermissions() {
  const tbody = document.getElementById("permissionTable");
  if (!tbody) return;

  const start = (permissionPage - 1) * permissionPageSize;
  const end = start + permissionPageSize;
  const users = allPermissions.slice(start, end);

  tbody.innerHTML = users
    .map((u, i) => {
      // Lấy giá trị position hiện tại, nếu là null (khách hàng) thì để trống hoặc mặc định
      const currentPos = u.position ? u.position.toLowerCase() : "";

      return `
    <tr>
      <td>${start + i + 1}</td>
      <td style="font-weight: 600;">${u.name}</td>
      <td style="text-align: left; font-size: 0.85rem; color: #78716c;">${u.email}</td>
      <td>
        <select class="role-select" onchange="changeRole('${u.userId}', this.value)">
          <option value="customer" ${currentPos === "customer" ? "selected" : ""}>customer</option>
          <option value="sales" ${currentPos === "sales" ? "selected" : ""}>Sales</option>
          <option value="operation" ${currentPos === "operation" ? "selected" : ""}>Operation</option>
          <option value="manager" ${currentPos === "manager" ? "selected" : ""}>Manager</option>
        </select>
      </td>
      <td>
        <button class="btn-save-permission" onclick="updatePermission('${u.userId}')">Lưu</button>
      </td>
    </tr>
  `;
    })
    .join("");
}
function renderPermissionPagination() {
  const container = document.getElementById("permissionPagination");
  if (!container) return;

  const totalPages = Math.ceil(allPermissions.length / permissionPageSize);

  let html = "";

  html += `
    <button onclick="changePermissionPage(${permissionPage - 1})" ${permissionPage === 1 ? "disabled" : ""}>
      ←
    </button>
  `;

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button onclick="changePermissionPage(${i})" class="${i === permissionPage ? "active" : ""}">
        ${i}
      </button>
    `;
  }

  html += `
    <button onclick="changePermissionPage(${permissionPage + 1})" ${permissionPage === totalPages ? "disabled" : ""}>
      →
    </button>
  `;

  container.innerHTML = html;
}

function changePermissionPage(page) {
  const totalPages = Math.ceil(allPermissions.length / permissionPageSize);
  if (page < 1 || page > totalPages) return;

  permissionPage = page;
  renderPermissions();
  renderPermissionPagination();
}
function changeRole(userId, newRole) {
  const user = allPermissions.find((u) => u.userId == userId);
  if (user) {
    // Cập nhật lại role của user trong mảng local để hàm updatePermission lấy ra gửi API
    user.role = newRole;
    console.log(`Đã chọn quyền mới cho ${user.name}: ${newRole}`);
  }
}
async function updatePermission(userId) {
  // 1. Tìm user để lấy email
  const user = allPermissions.find((u) => u.userId == userId);

  if (!user || !user.email) {
    alert("Không tìm thấy email của người dùng!");
    return;
  }

  try {
    // 2. Gọi ĐÚNG đường dẫn API và truyền ĐÚNG tham số như ảnh Thunder Client
    const res = await fetch("/SELLING-GLASSES/public/staff-save", {
      // Thay đổi route ở đây
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        position: user.role, // Tương ứng với 'position' trong ảnh
        email: user.email, // Tương ứng với 'email' trong ảnh
      }),
    });

    const result = await res.json();

    if (result.success) {
      alert("Cập nhật quyền thành công!");
    } else {
      alert("Lỗi từ server: " + (result.message || "Thất bại"));
    }
  } catch (err) {
    console.error("Lỗi kết nối:", err);
    alert("Không thể kết nối đến máy chủ.");
  }
}
// ======================
// AUTO LOAD WHEN CLICK TAB
// =============================
document.addEventListener("DOMContentLoaded", () => {
  const btnPermission = document.getElementById("btn-permission");

  if (btnPermission) {
    btnPermission.addEventListener("click", () => {
      setTimeout(() => {
        loadPermissions();
      }, 100);
    });
  }
});
document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("btn-user");

  if (btn) {
    btn.addEventListener("click", () => {
      setTimeout(() => {
        loadUsers();
      }, 100);
    });
  }
});
// Xử lý gửi dữ liệu khi người dùng nhấn "Lưu thay đổi" trong Modal Update
const formUpdate = document.getElementById("formUpdateUser");
if (formUpdate) {
  formUpdate.addEventListener("submit", async function (e) {
    e.preventDefault(); // Ngăn trang web load lại

    // Lấy dữ liệu từ các input
    const userId = document.getElementById("edit_userId").value;
    const name = document.getElementById("edit_name").value;
    const email = document.getElementById("edit_email").value;
    const phone = document.getElementById("edit_phone").value;

    // Đóng gói dữ liệu theo định dạng x-www-form-urlencoded (giống Thunder)
    const params = new URLSearchParams();
    params.append("userId", userId);
    params.append("name", name);
    params.append("email", email);
    params.append("phone", phone);

    try {
      const response = await fetch("/SELLING-GLASSES/public/update-user", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: params,
      });

      const result = await response.json();

      if (result.success) {
        alert("Cập nhật thành công!");
        dongModal(); // Đóng modal
        loadUsers(); // Load lại bảng để thấy dữ liệu mới
      } else {
        alert("Lỗi: " + (result.message || "Cập nhật thất bại"));
      }
    } catch (error) {
      console.error("Lỗi Fetch Update:", error);
      alert("Không thể kết nối đến máy chủ khi cập nhật!");
    }
  });
}
