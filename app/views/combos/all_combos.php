<?php include_once __DIR__ . "/../layout/header.php"; ?>

<main class="bg-stone-50 min-h-screen pt-[120px] pb-20">
    <div class="max-w-7xl mx-auto px-6">

        <nav class="flex text-sm text-stone-500 mb-8">
            <a href="/SELLING-GLASSES/public/home" class="hover:text-amber-700">Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-stone-900 font-medium">Combo</span>
        </nav>

        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-4xl font-bold text-stone-900 mb-2">Combo Ưu Đãi</h1>
                <p class="text-stone-500">Tiết kiệm hơn khi mua combo gọng kính & tròng kính chính hãng.</p>
            </div>
            <span class="text-sm font-medium text-stone-400 uppercase tracking-widest">

            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">

            <?php if (!empty($combos)): ?>
            <?php foreach ($combos as $combo): ?>
            <div class="group flex flex-col">
                <div class="aspect-square rounded-[32px] overflow-hidden bg-stone-200 mb-6 relative shadow-sm">
                    <?php if ($combo->imagePath): ?>
                    <img src="/SELLING-GLASSES/public/assets/images/products/<?php echo $combo->imagePath; ?>"
                        alt="<?php echo htmlspecialchars($combo->name); ?>"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <?php else: ?>
                    <div
                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-100 to-stone-200">
                        <i class="fa-solid fa-box-open text-5xl text-stone-400"></i>
                    </div>
                    <?php endif; ?>

                    <div
                        class="absolute top-4 left-4 bg-amber-600/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-white">
                        COMBO
                    </div>

                    <?php 
                    // Tính tổng giá sản phẩm lẻ để hiện % tiết kiệm
                    $totalItemPrice = 0;
                    if (!empty($combo->items)) {
                        foreach ($combo->items as $item) {
                            $totalItemPrice += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1);
                        }
                    }
                    $saved = $totalItemPrice - (float)$combo->price;
                    if ($totalItemPrice > 0 && $saved > 0):
                        $savedPercent = round(($saved / $totalItemPrice) * 100);
                    ?>
                    <div
                        class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                        -<?php echo $savedPercent; ?>%
                    </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-2 px-2 flex-grow">
                    <h3 class="font-bold text-xl text-stone-900 group-hover:text-amber-700 transition">
                        <?php echo htmlspecialchars($combo->name); ?>
                    </h3>
                    <p class="text-stone-500 text-sm line-clamp-2">
                        <?php echo htmlspecialchars($combo->description ?? 'Combo tiết kiệm'); ?>
                    </p>

                    <!-- Danh sách sản phẩm trong combo -->
                    <?php if (!empty($combo->items)): ?>
                    <div class="flex flex-wrap gap-1 pt-1">
                        <?php foreach (array_slice($combo->items, 0, 3) as $item): ?>
                        <span class="text-[10px] bg-stone-100 text-stone-600 px-2 py-0.5 rounded-full">
                            <?php echo htmlspecialchars($item['productName'] ?? 'Sản phẩm'); ?>
                        </span>
                        <?php endforeach; ?>
                        <?php if (count($combo->items) > 3): ?>
                        <span class="text-[10px] bg-stone-100 text-stone-500 px-2 py-0.5 rounded-full">
                            +<?php echo count($combo->items) - 3; ?> khác
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="flex justify-between items-center pt-2">
                        <div class="flex flex-col">
                            <span class="text-amber-700 font-black text-lg">
                                <?php echo number_format($combo->price, 0, ',', '.'); ?>đ
                            </span>
                            <?php if ($totalItemPrice > 0 && $saved > 0): ?>
                            <span class="text-stone-400 text-xs line-through">
                                <?php echo number_format($totalItemPrice, 0, ',', '.'); ?>đ
                            </span>
                            <?php endif; ?>
                        </div>
                        <button
                            class="add-combo-to-cart-quick w-10 h-10 rounded-full bg-stone-900 text-white flex items-center justify-center hover:bg-amber-700 transition shadow-lg"
                            data-combo-id="<?php echo $combo->comboId; ?>">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <a href="/SELLING-GLASSES/public/combo-detail?id=<?php echo $combo->comboId; ?>"
                    class="block mt-4 text-center py-3 border border-stone-200 rounded-2xl text-sm font-bold hover:bg-stone-900 hover:text-white transition uppercase tracking-widest">
                    Chi tiết combo
                </a>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="col-span-full py-20 text-center">
                <i class="fa-solid fa-box-open text-4xl text-stone-300 mb-4"></i>
                <p class="text-stone-500 italic">Chưa có combo nào.</p>
            </div>
            <?php endif; ?>

        </div>

    </div>
</main>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>

<!-- Essential Scripts -->
<script src="/SELLING-GLASSES/public/assets/js/main.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/cart.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/home.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/chatbox.js"></script>
<script src="/SELLING-GLASSES/public/assets/js/auth.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý click nút thêm combo vào giỏ hàng
    const addComboButtons = document.querySelectorAll('.add-combo-to-cart-quick');

    addComboButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const comboId = this.getAttribute('data-combo-id');

            if (typeof addComboToCart === 'function') {
                addComboToCart(comboId, 1);
            } else {
                // Fallback: gọi API trực tiếp
                fetch('/SELLING-GLASSES/public/add-combo-to-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            comboId: comboId,
                            quantity: 1
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Đã thêm combo vào giỏ hàng!');
                            if (typeof updateCartCount === 'function') updateCartCount();
                        } else {
                            alert(data.error || 'Không thể thêm vào giỏ hàng');
                        }
                    })
                    .catch(() => alert('Vui lòng đăng nhập để thêm vào giỏ hàng'));
            }
        });
    });
});
</script>