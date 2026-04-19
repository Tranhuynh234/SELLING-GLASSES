<?php include __DIR__ . '/../layout/header.php'; ?>

<main class="w-full bg-stone-50 pt-[120px] pb-24">
    <div class="max-w-7xl mx-auto px-6 md:px-10">

        <div class="grid grid-cols-1 md:grid-cols-[1fr_1.5fr] gap-12 lg:gap-16 items-start">

            <div class="w-full flex items-start justify-center md:sticky md:top-[150px]">
                <div
                    class="bg-white rounded-[32px] p-6 shadow-sm border border-stone-100 w-full flex items-center justify-center">
                    <img id="detail-image" src="" alt="Sản phẩm"
                        class="w-full max-w-[400px] h-auto object-contain transition-all duration-500 mx-auto rounded-xl">
                </div>
            </div>

            <div class="space-y-8 pt-4">

                <div class="space-y-2">
                    <div id="detail-category" class="text-amber-700 font-bold text-xs uppercase tracking-[0.2em]"></div>
                    <h1 id="detail-name" class="text-5xl font-extrabold text-stone-900 leading-tight tracking-tighter">
                        Đang tải...</h1>
                </div>

                <div
                    class="bg-white p-6 rounded-3xl shadow-sm border border-stone-100 flex items-center justify-between">
                    <div class="flex items-baseline gap-2">
                        <span id="detail-price" class="text-4xl font-bold text-amber-700">0đ</span>
                        <span class="text-xs text-stone-400">/ chiếc</span>
                    </div>
                    <span id="detail-stock"
                        class="px-4 py-1.5 bg-green-50 text-green-700 rounded-full text-xs font-bold"></span>
                </div>

                <div class="space-y-3 border-t border-stone-100 pt-8">
                    <h3 class="font-bold text-stone-800 text-sm uppercase tracking-wider">Mô tả sản phẩm</h3>
                    <p id="detail-description" class="text-stone-600 text-base leading-relaxed"></p>
                </div>

                <div class="space-y-4 border-t border-stone-100 pt-8">
                    <label class="block font-bold text-stone-800 text-sm uppercase tracking-wider">Màu sắc & Kích
                        thước:</label>
                    <div id="variants-container" class="grid grid-cols-2 gap-4">
                    </div>
                </div>

                <div class="pt-10">
                    <button id="add-to-cart-btn"
                        class="w-full py-5 bg-[#1C1917] text-white rounded-[20px] font-bold text-lg hover:bg-black transition-all shadow-xl flex items-center justify-center gap-3 active:scale-[0.98]">
                        <i class="fas fa-shopping-cart text-base"></i>
                        Thêm vào giỏ hàng
                    </button>
                    <p class="text-center text-xs text-stone-400 mt-4">Miễn phí giao hàng toàn quốc</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<!-- Essential Scripts -->
<script src="/SELLING-GLASSES/public/assets/js/main.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/cart.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/home.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/chatbox.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/auth.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/product-detail.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof renderAuth === 'function') {
            renderAuth();
        }
        
        if (typeof updateCartCount === 'function') {
            fetch('/SELLING-GLASSES/public/get-cart', {
                credentials: 'include'
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.data && Array.isArray(data.data)) {
                    const totalQty = data.data.reduce((sum, item) => sum + (Number(item.quantity) || 0), 0);
                    updateCartCount(totalQty);
                }
            })
            .catch(err => console.log('Cart load error:', err));
        }
    });
</script>