document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");

  if (!productId) {
    console.error("Không tìm thấy ID");
    return;
  }

  fetch(`/SELLING-GLASSES/public/detail?id=${productId}`, {
    headers: {
      Accept: "application/json",
    },
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        const product = result.data;

        // 1. Đổ thông tin cơ bản
        document.getElementById("detail-name").innerText = product.name;
        document.getElementById("detail-description").innerText =
          product.description;
        document.getElementById("detail-category").innerText =
          product.categoryName;

        // 2. Xử lý ảnh (Kiểm tra lại đường dẫn nếu vẫn lỗi ảnh)
        const imgElement = document.getElementById("detail-image");
        imgElement.src = `/SELLING-GLASSES/public/assets/images/${product.imagePath}`;
        imgElement.onerror = function () {
          this.src = "https://via.placeholder.com/500x300?text=No+Image"; // Ảnh thay thế nếu sai đường dẫn
        };

        // 3. Đổ Variants và xử lý chọn giá
        const container = document.getElementById("variants-container");
        container.innerHTML = "";

        if (product.variants && product.variants.length > 0) {
          product.variants.forEach((v, index) => {
            const btn = document.createElement("button");

            // Style cơ bản
            const baseClass =
              "p-4 border-2 rounded-2xl text-left transition-all duration-300 ";
            const inactiveClass = "border-stone-100 hover:border-amber-300";
            const activeClass = "border-amber-600 bg-amber-50 shadow-sm";

            btn.className =
              baseClass + (index === 0 ? activeClass : inactiveClass);

            btn.innerHTML = `
              <div class="font-bold text-stone-800">${v.color} - ${v.size}</div>
              <div class="text-sm text-amber-700">${Number(v.price).toLocaleString()}đ</div>
            `;

            // Sự kiện khi click chọn variant
            btn.onclick = () => {
              // Reset tất cả nút về trạng thái bình thường
              Array.from(container.children).forEach((b) => {
                b.className = baseClass + inactiveClass;
              });
              // Active nút hiện tại
              btn.className = baseClass + activeClass;

              // CẬP NHẬT GIÁ VÀ KHO TẠI ĐÂY
              updatePriceAndStock(v.price, v.stock);
            };

            container.appendChild(btn);
          });

          // 4. QUAN TRỌNG: Cập nhật giá của variant đầu tiên ngay khi load trang
          updatePriceAndStock(
            product.variants[0].price,
            product.variants[0].stock,
          );
        }
      }
    })
    .catch((error) => console.error("Lỗi:", error));
});

// Hàm bổ trợ để cập nhật giá tiền lên giao diện
function updatePriceAndStock(price, stock) {
  const priceEl = document.getElementById("detail-price");
  const stockEl = document.getElementById("detail-stock");

  if (priceEl) {
    priceEl.innerText = Number(price).toLocaleString() + "đ";
  }

  if (stockEl) {
    stockEl.innerText = stock > 0 ? `Còn lại: ${stock}` : "Hết hàng";
    stockEl.className = `text-sm ${stock > 0 ? "text-green-600" : "text-red-500"} font-medium`;
  }
}
