<?php include __DIR__ . '/../layout/header.php'; ?>

<?php
// Tính tổng giá sản phẩm lẻ để so sánh
$totalItemPrice = 0;
if (!empty($combo->items)) {
    foreach ($combo->items as $item) {
        $totalItemPrice += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1);
    }
}
$saved = $totalItemPrice - (float)$combo->price;
$savedPercent = ($totalItemPrice > 0 && $saved > 0) ? round(($saved / $totalItemPrice) * 100) : 0;
?>

<main class="w-full bg-stone-50 pt-[120px] pb-24">
    <div class="max-w-7xl mx-auto px-6 md:px-10">

        <!-- Breadcrumb -->
        <nav class="flex text-sm text-stone-500 mb-8">
            <a href="/SELLING-GLASSES/public/home" class="hover:text-amber-700">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="/SELLING-GLASSES/public/all-combos" class="hover:text-amber-700">Combo</a>
            <span class="mx-2">/</span>
            <span class="text-stone-900 font-medium"><?php echo htmlspecialchars($combo->name); ?></span>
        </nav>

        <!-- Main Layout: Ảnh combo + Thông tin -->
        <div class="grid grid-cols-1 md:grid-cols-[1fr_1.5fr] gap-12 lg:gap-16 items-start">

            <!-- Ảnh combo -->
            <div class="w-full flex items-start justify-center md:sticky md:top-[150px]">
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-stone-100 w-full flex items-center justify-center relative overflow-hidden">
                    <?php if ($combo->imagePath): ?>
                    <img src="/SELLING-GLASSES/public/assets/images/products/<?php echo $combo->imagePath; ?>"
                        alt="<?php echo htmlspecialchars($combo->name); ?>"
                        class="w-full max-w-[400px] h-auto object-contain transition-all duration-500 mx-auto rounded-xl">
                    <?php else: ?>
                    <div class="w-full max-w-[400px] aspect-square flex items-center justify-center bg-gradient-to-br from-amber-50 to-stone-100 rounded-xl">
                        <i class="fa-solid fa-box-open text-6xl text-stone-300"></i>
                    </div>
                    <?php endif; ?>

                    <?php if ($savedPercent > 0): ?>
                    <div class="absolute top-8 right-8 bg-red-500 text-white px-4 py-2 rounded-2xl text-sm font-bold shadow-lg">
                        Tiết kiệm <?php echo $savedPercent; ?>%
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thông tin combo -->
            <div class="space-y-8 pt-4">

                <!-- Tên & Badge -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="bg-amber-600 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-[0.2em]">COMBO</span>
                        <?php if (!empty($combo->items)): ?>
                        <span class="text-stone-400 text-xs"><?php echo count($combo->items); ?> sản phẩm</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-5xl font-extrabold text-stone-900 leading-tight tracking-tighter">
                        <?php echo htmlspecialchars($combo->name); ?>
                    </h1>
                </div>

                <!-- Giá -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-stone-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-baseline gap-3">
                            <span class="text-4xl font-bold text-amber-700">
                                <?php echo number_format($combo->price, 0, ',', '.'); ?>đ
                            </span>
                            <span class="text-xs text-stone-400">/ combo</span>
                        </div>
                        <?php if ($savedPercent > 0): ?>
                        <div class="text-right">
                            <span class="text-stone-400 text-sm line-through block">
                                <?php echo number_format($totalItemPrice, 0, ',', '.'); ?>đ
                            </span>
                            <span class="text-red-500 text-xs font-bold">
                                Tiết kiệm <?php echo number_format($saved, 0, ',', '.'); ?>đ
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mô tả -->
                <?php if ($combo->description): ?>
                <div class="space-y-3 border-t border-stone-100 pt-8">
                    <h3 class="font-bold text-stone-800 text-sm uppercase tracking-wider">Mô tả combo</h3>
                    <p class="text-stone-600 text-base leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($combo->description)); ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Danh sách sản phẩm trong combo -->
                <?php if (!empty($combo->items)): ?>
                <div class="space-y-5 border-t border-stone-100 pt-8">
                    <h3 class="font-bold text-stone-800 text-sm uppercase tracking-wider">Sản phẩm trong combo</h3>
                    
                    <div class="space-y-4">
                        <?php foreach ($combo->items as $item): ?>
                        <a href="/SELLING-GLASSES/public/detail?id=<?php echo $item['productId']; ?>"
                           class="group flex items-center gap-5 bg-white p-4 rounded-2xl border border-stone-100 hover:border-amber-300 hover:shadow-md transition-all duration-300">
                            
                            <!-- Ảnh sản phẩm -->
                            <div class="w-20 h-20 rounded-xl overflow-hidden bg-stone-100 flex-shrink-0">
                                <?php if (!empty($item['imagePath'])): ?>
                                <img src="/SELLING-GLASSES/public/assets/images/products/<?php echo $item['imagePath']; ?>"
                                    alt="<?php echo htmlspecialchars($item['productName']); ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-glasses text-stone-300 text-xl"></i>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Thông tin sản phẩm -->
                            <div class="flex-grow min-w-0">
                                <h4 class="font-bold text-stone-900 group-hover:text-amber-700 transition truncate">
                                    <?php echo htmlspecialchars($item['productName'] ?? 'Sản phẩm'); ?>
                                </h4>
                                <?php if (!empty($item['productDescription'])): ?>
                                <p class="text-stone-500 text-sm line-clamp-1 mt-0.5">
                                    <?php echo htmlspecialchars($item['productDescription']); ?>
                                </p>
                                <?php endif; ?>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <?php if (isset($item['price']) && $item['price'] > 0): ?>
                                    <span class="text-amber-700 font-bold text-sm">
                                        <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                                    </span>
                                    <?php endif; ?>
                                    <span class="text-stone-400 text-xs">
                                        × <?php echo (int)($item['quantity'] ?? 1); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Mũi tên -->
                            <div class="flex-shrink-0 text-stone-300 group-hover:text-amber-600 transition">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Nút thêm vào giỏ hàng -->
                <div class="pt-10">
                    <button id="add-combo-to-cart-btn"
                        data-combo-id="<?php echo $combo->comboId; ?>"
                        class="w-full py-5 bg-[#1C1917] text-white rounded-[20px] font-bold text-lg hover:bg-black transition-all shadow-xl flex items-center justify-center gap-3 active:scale-[0.98]">
                        <i class="fas fa-shopping-cart text-base"></i>
                        Thêm combo vào giỏ hàng
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auth render
        if (typeof renderAuth === 'function') {
            renderAuth();
        }

        // Cart count
        if (typeof updateCartCount === 'function') {
            fetch('/SELLING-GLASSES/public/get-cart', { credentials: 'include' })
            .then(res => res.json())
            .then(data => {
                if (data && data.data && Array.isArray(data.data)) {
                    const totalQty = data.data.reduce((sum, item) => sum + (Number(item.quantity) || 0), 0);
                    updateCartCount(totalQty);
                }
            })
            .catch(err => console.log('Cart load error:', err));
        }

        // Nút thêm combo vào giỏ hàng
        const addBtn = document.getElementById('add-combo-to-cart-btn');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                const comboId = this.getAttribute('data-combo-id');

                if (typeof addComboToCart === 'function') {
                    addComboToCart(comboId, 1);
                } else {
                    // Fallback: gọi API trực tiếp
                    fetch('/SELLING-GLASSES/public/add-combo-to-cart', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        credentials: 'include',
                        body: JSON.stringify({ comboId: comboId, quantity: 1 })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Đã thêm combo vào giỏ hàng!');
                            if (typeof updateCartCount === 'function') {
                                fetch('/SELLING-GLASSES/public/get-cart', { credentials: 'include' })
                                .then(r => r.json())
                                .then(d => {
                                    if (d && d.data && Array.isArray(d.data)) {
                                        const totalQty = d.data.reduce((s, i) => s + (Number(i.quantity) || 0), 0);
                                        updateCartCount(totalQty);
                                    }
                                });
                            }
                        } else {
                            alert(data.error || 'Không thể thêm vào giỏ hàng');
                        }
                    })
                    .catch(() => alert('Vui lòng đăng nhập để thêm vào giỏ hàng'));
                }
            });
        }
    });
</script>
