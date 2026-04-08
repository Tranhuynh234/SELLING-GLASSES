// ==========================
// TAB SYSTEM 
// ==========================
function showTab(tab) {
    // 1. Ẩn toàn bộ tab
    document.querySelectorAll(".tab").forEach(t => {
        t.classList.remove("active");
    });

    // 2. Hiện tab
    const currentTab = document.getElementById(tab);
    if (currentTab) {
        currentTab.classList.add("active");
    }

    // 3. Active sidebar
    document.querySelectorAll(".menu-item").forEach(i => {
        i.classList.remove("active");
    });

    const currentBtn = document.getElementById("btn-" + tab);
    if (currentBtn) {
        currentBtn.classList.add("active");
    }
}

// ==========================
// EDIT PROFILE (SMOOTH & API)
// ==========================
function edit(field) {
    const text = document.getElementById(field + "Text");
    const input = document.getElementById(field + "Input");

    if (!text || !input) return;
    if (!input.classList.contains("hidden")) return;

    // Lọc giá trị rỗng mặc định
    const oldValue = (text.innerText === 'Chưa cập nhật' || text.innerText === 'Chưa có địa chỉ') ? '' : text.innerText;
    input.value = oldValue;

    // Hiệu ứng mờ chữ -> Hiện Input
    text.style.opacity = '0';
    setTimeout(() => {
        text.classList.add("hidden");
        input.classList.remove("hidden");
        input.style.opacity = '1';
        input.focus();
    }, 150);

    // Bấm Enter để lưu
    input.onkeydown = (e) => {
        if (e.key === "Enter") input.blur();
    };

    // Click ra ngoài (Blur) để chạy lệnh lưu
    input.onblur = async () => {
        const newValue = input.value.trim();

        // Hiệu ứng mờ Input -> Hiện lại chữ
        input.style.opacity = '0';
        setTimeout(() => {
            input.classList.add("hidden");
            text.classList.remove("hidden");
            text.style.opacity = '1';
        }, 150);

        // Kiểm tra nếu có thay đổi thì gửi API
        if (newValue !== "" && newValue !== oldValue) {
            
            text.innerText = newValue; // Update giao diện tạm

            // Đóng gói dữ liệu gửi lên PHP
            const formData = new FormData();
            formData.append(field, newValue);

            try {
                const response = await fetch('/SELLING-GLASSES/public/update-profile', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();

                if (result.success) {
                    showToast("Cập nhật " + field + " thành công!");
                    
                    // Update LocalStorage để thanh Header đổi tên theo
                    let user = JSON.parse(localStorage.getItem('user'));
                    if(user && (field === 'name' || field === 'email' || field === 'phone')) {
                        user[field] = newValue;
                        localStorage.setItem('user', JSON.stringify(user));
                    }
                } else {
                    showToast("Lỗi: " + (result.message || "Không thể cập nhật"));
                    text.innerText = oldValue || (field === 'phone' ? 'Chưa cập nhật' : field === 'address' ? 'Chưa có địa chỉ' : ''); // Rollback
                }
            } catch (error) {
                showToast("Lỗi kết nối máy chủ");
                text.innerText = oldValue || (field === 'phone' ? 'Chưa cập nhật' : field === 'address' ? 'Chưa có địa chỉ' : ''); // Rollback
            }
        } else if (newValue === "") {
            // Nếu xóa trắng thì trả về text cũ
            text.innerText = oldValue || (field === 'phone' ? 'Chưa cập nhật' : field === 'address' ? 'Chưa có địa chỉ' : oldValue);
        }
    };
}

// ==========================
// MODAL PASSWORD
// ==========================
function openPassword() {
    const modal = document.getElementById("passwordModal");
    if (modal) modal.style.display = "flex";
}

function closePassword() {
    const modal = document.getElementById("passwordModal");
    if (modal) {
        modal.style.display = "none";
        modal.querySelectorAll("input").forEach(i => i.value = "");
    }
}

window.addEventListener("click", function(e) {
    const modal = document.getElementById("passwordModal");
    if (e.target === modal) closePassword();
});

// ==========================
// SAVE PASSWORD (Fake UI - Sau này ráp API tương tự edit)
// ==========================
function savePassword() {
    const inputs = document.querySelectorAll("#passwordModal input");
    const oldPass = inputs[0].value.trim();
    const newPass = inputs[1].value.trim();
    const confirmPass = inputs[2].value.trim(); // Xác nhận MK

    if (!oldPass || !newPass || !confirmPass) {
        showToast("Vui lòng nhập đầy đủ");
        return;
    }

    if (newPass.length < 6) {
        showToast("Mật khẩu mới phải >= 6 ký tự");
        return;
    }
    
    if (newPass !== confirmPass) {
        showToast("Xác nhận mật khẩu không khớp");
        return;
    }

    showToast("Đổi mật khẩu thành công (Test UI)");
    closePassword();
}

// ==========================
// TOAST THÔNG BÁO
// ==========================
function showToast(msg) {
    const toast = document.createElement("div");
    toast.innerText = msg;
    
    // Đảm bảo không bị đè CSS
    Object.assign(toast.style, {
        position: "fixed",
        bottom: "30px",
        right: "30px",
        background: "#d97706", // Chuyển qua màu cam cho nổi
        color: "#fff",
        padding: "12px 20px",
        borderRadius: "10px",
        opacity: "0",
        transition: "0.3s ease-in-out",
        zIndex: "9999",
        boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
        fontWeight: "bold",
        fontFamily: "'Inter', sans-serif"
    });

    document.body.appendChild(toast);

    setTimeout(() => { toast.style.opacity = "1"; }, 100);

    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}