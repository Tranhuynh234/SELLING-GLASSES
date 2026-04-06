function switchView(viewId) {
  document.querySelectorAll(".auth-view").forEach((el) => {
    el.classList.remove("active");
  });

  document.getElementById(viewId).classList.add("active");

  const imgPanel = document.getElementById("image-panel");
  const formPanel = document.getElementById("form-panel");
  const imgTextLogin = document.getElementById("img-text-login");
  const imgTextRegister = document.getElementById("img-text-register");

  if (viewId === "register-view") {
    imgPanel.classList.add("lg:translate-x-full");
    formPanel.classList.add("lg:-translate-x-full");

    imgTextLogin.classList.replace("opacity-100", "opacity-0");
    imgTextRegister.classList.replace("opacity-0", "opacity-100");
  } else {
    imgPanel.classList.remove("lg:translate-x-full");
    formPanel.classList.remove("lg:-translate-x-full");

    imgTextLogin.classList.replace("opacity-0", "opacity-100");
    imgTextRegister.classList.replace("opacity-100", "opacity-0");
  }
}

function togglePassword(inputId) {
  const input = document.getElementById(inputId);

  if (input.type === "password") {
    input.type = "text";
  } else {
    input.type = "password";
  }
}

async function handleLogin(event) {
  event.preventDefault();

  const email = document.getElementById("login-email").value;
  const password = document.getElementById("login-password").value;

  const formData = new FormData();
  formData.append("email", email);
  formData.append("password", password);
  try {
    const response = await fetch("/SELLING-GLASSES/public/login", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      localStorage.setItem("user", JSON.stringify(result.data));

      renderAuth(); // thêm dòng này

      setTimeout(() => {
        window.location.href = result.redirect;
      }, 300);
    } else {
      alert(result.message);
    }
  } catch (error) {
    alert("Error connecting to server");
  }
}

async function handleRegister(event) {
  event.preventDefault();

  const name = document.getElementById("reg-name").value;
  const email = document.getElementById("reg-email").value;
  const phone = document.getElementById("reg-phone").value;
  const password = document.getElementById("reg-password").value;
  const confirmPassword = document.getElementById("reg-confirm").value;

  // Kiểm tra xác nhận mật khẩu trước khi gửi lên server
  if (password !== confirmPassword) {
    alert("Mật khẩu xác nhận không khớp!");
    return;
  }

  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("password", password);
  formData.append("phone", phone);

  try {
    const response = await fetch("/SELLING-GLASSES/public/register", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      alert("Đăng ký thành công!");
      // Chuyển hướng về trang đăng nhập hoặc trang cá nhân
      window.location.href = "/SELLING-GLASSES/public/auth";
    } else {
      alert(result.message || "Đăng ký thất bại, vui lòng thử lại.");
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Có lỗi xảy ra trong quá trình kết nối với máy chủ.");
  }
}
//=========================
function renderAuth() {
  const user = JSON.parse(localStorage.getItem("user"));
  const authBox = document.getElementById("auth-box");

  if (!authBox) return;

  if (user) {
    authBox.innerHTML = `
      <span>Xin chào, ${user.name}</span>
      <a href="#" onclick="logout()" class="ml-3 hover:text-amber-500">
        Đăng xuất
      </a>
    `;
  } else {
    authBox.innerHTML = `
      <a href="/SELLING-GLASSES/public/auth">Đăng nhập</a>
      <span>|</span>
      <a href="/SELLING-GLASSES/public/auth">Đăng ký</a>
    `;
  }
}

function logout() {
  fetch("/SELLING-GLASSES/public/logout", {
    credentials: "include",
  })
    .then((res) => res.text())
    .then(() => {
      localStorage.removeItem("user");
      window.location.href = "/SELLING-GLASSES/public/home";
    });
}

// chạy khi load trang
document.addEventListener("DOMContentLoaded", function () {
  renderAuth();
  // LOGIN FORM
  const loginForm = document.querySelector("#login-view form");
  if (loginForm) {
    loginForm.addEventListener("submit", handleLogin);
  }

  // REGISTER FORM
  const registerForm = document.querySelector("#register-view form");
  if (registerForm) {
    registerForm.addEventListener("submit", handleRegister);
  }
});
