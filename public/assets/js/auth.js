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
    const response = await fetch(
      "/SELLING-GLASSES/routes/web.php?action=login",
      {
        method: "POST",
        body: formData,
      },
    );

    const result = await response.text();

    if (result.includes("Invalid") || result.includes("exists")) {
      alert(result);
    } else {
      window.location.href = result;
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

  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("phone", phone);
  formData.append("password", password);

  const response = await fetch(
    "/SELLING-GLASSES/routes/web.php?action=register",
    {
      method: "POST",
      body: formData,
    },
  );

  const result = await response.text();

  if (result.startsWith("/")) {
    window.location.href = result;
  } else {
    alert(result);
  }
}
function logout() {
  fetch("/SELLING-GLASSES/routes/web.php?action=logout", {
    credentials: "include",
  })
    .then((res) => res.text())
    .then((url) => {
      window.location.href = url;
    });
}
function checkLoginStatus() {
  fetch("/SELLING-GLASSES/routes/web.php?action=check", {
    credentials: "include",
  })
    .then((res) => res.text())
    .then((status) => {
      const loginBtn = document.getElementById("loginBtn");
      const logoutBtn = document.getElementById("logoutBtn");

      if (status.trim() === "logged_in") {
        loginBtn.style.display = "none";
        logoutBtn.style.display = "inline-block";
      } else {
        loginBtn.style.display = "inline-block";
        logoutBtn.style.display = "none";
      }
    });
}

// chạy khi load trang
document.addEventListener("DOMContentLoaded", function () {
  // check login
  checkLoginStatus();

  // login form
  const loginForm = document.querySelector("#login-view form");
  if (loginForm) {
    loginForm.addEventListener("submit", handleLogin);
  }

  // register form
  const registerForm = document.querySelector("#register-view form");
  if (registerForm) {
    registerForm.addEventListener("submit", handleRegister);
  }
});
