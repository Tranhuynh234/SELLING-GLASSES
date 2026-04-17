function showTab(tabId) {
    console.log("Mở tab: " + tabId);
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    const target = document.getElementById(tabId);
    if (target) target.classList.add('active');

    document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
    const btn = document.getElementById('btn-' + tabId);
    if (btn) btn.classList.add('active');
}

async function edit(field) {
    const text = document.getElementById(field + "Text");
    const input = document.getElementById(field + "Input");
    if (!text || !input) return;
    const oldValue = (text.innerText.includes('Chưa')) ? '' : text.innerText;
    input.value = oldValue;
    text.classList.add("hidden");
    input.classList.remove("hidden");
    input.focus();
    input.onblur = async () => {
        const newValue = input.value.trim();
        input.classList.add("hidden");
        text.classList.remove("hidden");
        if (newValue !== "" && newValue !== oldValue) {
            text.innerText = newValue;
            const params = new URLSearchParams();
            params.append('field', field);
            params.append('value', newValue);
            try {
                const response = await fetch('/SELLING-GLASSES/public/index.php?url=update-profile', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params.toString()
                });

                const result = await response.json();
                console.log("Kết quả:", result);

                if (result.success) {
                    showToast("Cập nhật thành công!");
                } else {
                    alert("Lỗi: " + result.message);
                }
            } catch (error) {
                console.error("Lỗi kết nối:", error);
            }
        }
    };
}

function openPassword() { document.getElementById("passwordModal").style.display = "flex"; }
function closePassword() { document.getElementById("passwordModal").style.display = "none"; }
function openReturnModal(id) { 
    document.getElementById("returnOrderId").innerText = "#" + id;
    document.getElementById("returnModal").style.display = "flex"; 
}
function closeReturnModal() { document.getElementById("returnModal").style.display = "none"; }

function showToast(msg) {
    const toast = document.createElement("div");
    toast.innerText = msg;
    Object.assign(toast.style, { position: "fixed", bottom: "30px", right: "30px", background: "#d97706", color: "#fff", padding: "12px 20px", borderRadius: "10px", zIndex: "9999" });
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2500);
}

// CÁC LOGIC TỰ ĐỘNG THÌ ĐỂ XUỐNG DƯỚI CÙNG
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('order_id') || urlParams.get('tab') === 'orders') {
        showTab('orders');
    }

    const presForm = document.getElementById('prescriptionForm');
    if (presForm) {
        presForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/SELLING-GLASSES/public/index.php?url=update-prescription', {
                method: 'POST', body: formData
            })
            .then(res => res.json())
            .then(data => alert(data.success ? "Đã lưu!" : "Lỗi: " + data.message))
            .catch(err => alert("Lỗi kết nối"));
        });
    }
});

// TAB SYSTEM 
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

// EDIT PROFILE (SMOOTH & API)
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
            text.innerText = newValue; 

            try {
                // 1. Sửa lại biến response và URL cho đúng mục đích
                const response = await fetch('/SELLING-GLASSES/public/index.php?url=update-profile', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    // 2. Gửi dạng field=value để PHP dễ nhận bằng $_POST
                    body: `field=${field}&value=${encodeURIComponent(newValue)}`
                });

                if (!response.ok) throw new Error("Network response was not ok");

                const result = await response.json();

                if (result.success) {
                    showToast("Cập nhật thành công!");
                    // Cập nhật LocalStorage nếu cần thiết
                    let user = JSON.parse(localStorage.getItem('user'));
                    if(user && (field === 'name' || field === 'email')) {
                        user[field] = newValue;
                        localStorage.setItem('user', JSON.stringify(user));
                    }
                } else {
                    throw new Error(result.message || "Lỗi từ server");
                }
            } catch (error) {
                console.error("Lỗi:", error);
                showToast("Lỗi kết nối máy chủ!");
                // Rollback lại giá trị cũ nếu lỗi
                text.innerText = oldValue || (field === 'phone' ? 'Chưa cập nhật' : 'Chưa có địa chỉ');
            }
        }
    };
}

// MODAL PASSWORD
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

// SAVE PASSWORD 
async function savePassword() {
    const inputs = document.querySelectorAll("#passwordModal input");
    const oldPass = inputs[0].value.trim();
    const newPass = inputs[1].value.trim();
    const confirmPass = inputs[2].value.trim();

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

    // GỬI API THẬT 
    const params = new URLSearchParams();
    params.append('oldPass', oldPass);
    params.append('newPass', newPass);

    try {
        const response = await fetch('/SELLING-GLASSES/public/index.php?url=change-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: params.toString()
        });

        const result = await response.json();
        if (result.success) {
            showToast("Đổi mật khẩu thành công!");
            closePassword();
        } else {
            showToast("Lỗi: " + result.message);
        }
    } catch (error) {
        showToast("Lỗi kết nối máy chủ");
    }
}

// TOAST THÔNG BÁO
function showToast(msg) {
    const toast = document.createElement("div");
    toast.innerText = msg;
    
    // Đảm bảo không bị đè CSS
    Object.assign(toast.style, {
        position: "fixed",
        bottom: "30px",
        right: "30px",
        background: "#d97706", 
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

// Mở Modal đổi trả
function openReturnModal(orderId) {
    const modal = document.getElementById("returnModal");
    const orderIdSpan = document.getElementById("returnOrderId");
    if (modal) {
        orderIdSpan.innerText = "#" + orderId;
        modal.style.display = "flex";
    }
}

// Đóng Modal đổi trả
function closeReturnModal() {
    const modal = document.getElementById("returnModal");
    if (modal) modal.style.display = "none";
}

// Xử lý nút bấm xác nhận 
function confirmReturn() {
    const reason = document.getElementById("returnReason").value;
    if (!reason) {
        showToast("Vui lòng chọn lý do hoàn trả");
        return;
    }
    showToast("Đã gửi yêu cầu đổi trả thành công!");
    closeReturnModal();
}

document.addEventListener('DOMContentLoaded', function() {
    const presForm = document.getElementById('prescriptionForm');

    if (presForm) {
        presForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Ngăn trang web bị load lại

            // Thu thập dữ liệu từ các ô input
            const formData = new FormData(this);

            // Gửi dữ liệu sang Controller
            fetch('/SELLING-GLASSES/public/index.php?url=update-prescription', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Đã lưu thông số mắt thành công!");
                } else {
                    alert("Lỗi: " + data.message);
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert("Không thể kết nối đến máy chủ");
            });
        });
    }
});

// HỦY ĐƠN HÀNG
async function cancelOrder(orderId) {
    // Hiện thông báo xác nhận cho chắc ăn
    if (!confirm("Bạn có chắc chắn muốn hủy đơn hàng #" + orderId + " không?")) {
        return;
    }

    const params = new URLSearchParams();
    params.append('orderId', orderId);

    try {
        const response = await fetch('/SELLING-GLASSES/public/index.php?url=cancel-order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: params.toString()
        });

        const result = await response.json();
        
        if (result.success) {
            alert("Hủy đơn hàng thành công!");
            location.reload(); // Load lại trang để nút Hủy tự biến mất và hiện status Cancelled
        } else {
            alert("Lỗi: " + result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Không thể kết nối máy chủ để hủy đơn.");
    }
}