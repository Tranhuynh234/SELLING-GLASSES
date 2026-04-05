// ==========================
// TAB SYSTEM (FIX)
// ==========================
function showTab(tab) {

    // 1. Ẩn toàn bộ tab
    document.querySelectorAll(".tab").forEach(t => {
        t.classList.remove("active");
    });

    // 2. Hiện tab (có check tránh lỗi)
    const currentTab = document.getElementById(tab);
    if (currentTab) {
        currentTab.classList.add("active");
    } else {
        console.warn("Không tìm thấy tab:", tab);
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
// EDIT PROFILE (FIX VIP)
// ==========================
function edit(field) {

    const text = document.getElementById(field + "Text");
    const input = document.getElementById(field + "Input");

    if (!text || !input) {
        console.warn("Thiếu field:", field);
        return;
    }

    // Nếu đang mở rồi thì không làm lại
    if (!input.classList.contains("hidden")) return;

    input.value = text.innerText;

    text.classList.add("hidden");
    input.classList.remove("hidden");

    input.focus();

    // ENTER để lưu
    input.onkeydown = (e) => {
        if (e.key === "Enter") {
            input.blur();
        }
    };

    // BLUR để lưu
    input.onblur = () => {
        text.innerText = input.value.trim() || text.innerText;

        text.classList.remove("hidden");
        input.classList.add("hidden");

        showToast("Đã cập nhật " + field);
    };
}

// ==========================
// MODAL (FIX AN TOÀN)
// ==========================
// ==========================
// MODAL PASSWORD (FULL FIX)
// ==========================
function openPassword() {
    const modal = document.getElementById("passwordModal");
    if (modal) {
        modal.style.display = "flex";
    }
}

function closePassword() {
    const modal = document.getElementById("passwordModal");
    if (modal) {
        modal.style.display = "none";

        // reset input
        modal.querySelectorAll("input").forEach(i => i.value = "");
    }
}

// CLICK NGOÀI ĐỂ ĐÓNG
window.addEventListener("click", function(e) {
    const modal = document.getElementById("passwordModal");
    if (e.target === modal) {
        closePassword();
    }
});

// ==========================
// SAVE PASSWORD (NEW)
// ==========================
function savePassword() {

    const inputs = document.querySelectorAll("#passwordModal input");

    const oldPass = inputs[0].value.trim();
    const newPass = inputs[1].value.trim();

    // validate
    if (!oldPass || !newPass) {
        showToast("Vui lòng nhập đầy đủ");
        return;
    }

    if (newPass.length < 6) {
        showToast("Mật khẩu phải >= 6 ký tự");
        return;
    }

    // fake success (sau này call API PHP)
    showToast("Đổi mật khẩu thành công 🔥");

    closePassword();
}

// ==========================
// CLICK NGOÀI MODAL ĐỂ TẮT
// ==========================
window.onclick = function(e) {
    const modal = document.getElementById("passwordModal");
    if (e.target === modal) {
        modal.style.display = "none";
    }
};

// ==========================
// TOAST VIP (NHẸ)
// ==========================
function showToast(msg) {

    const toast = document.createElement("div");
    toast.innerText = msg;

    toast.style.position = "fixed";
    toast.style.bottom = "30px";
    toast.style.right = "30px";
    toast.style.background = "#1c1917";
    toast.style.color = "#fff";
    toast.style.padding = "10px 18px";
    toast.style.borderRadius = "10px";
    toast.style.opacity = "0";
    toast.style.transition = "0.3s";
    toast.style.zIndex = "9999";

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = "1";
    }, 100);

    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}