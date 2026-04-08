// 1. QUẢN LÝ TAB (CHUYỂN ĐỔI GIỮA CÁC MENU)
function showTab(tabId) {
  // Ẩn tất cả các tab
  document.querySelectorAll(".tab-pane").forEach((pane) => {
    pane.classList.remove("active");
  }); // Hiện tab được chọn

  const activePane = document.getElementById(tabId);
  if (activePane) {
    activePane.classList.add("active");
  } // Cập nhật trạng thái menu sidebar

  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.remove("active");
  });
  const activeBtn = document.getElementById("btn-" + tabId);
  if (activeBtn) {
    activeBtn.classList.add("active");
  } // Nếu bấm vào tab Khuyến mãi thì tự động load dữ liệu

  if (tabId === "promo") {
    loadPromotions();
  }
}
