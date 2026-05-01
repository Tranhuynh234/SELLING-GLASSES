<?php include_once __DIR__ . "/../layout/header.php"; ?>

<main class="bg-stone-50 min-h-screen pt-[120px] pb-20">
    <div class="max-w-7xl mx-auto px-6">

        <nav class="flex text-sm text-stone-500 mb-8">
            <a href="/SELLING-GLASSES/public/home" class="hover:text-amber-700">Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-stone-900 font-medium">
                <?php 
                $categoryNames = [
                    1 => 'Gọng Nam',
                    2 => 'Gọng Nữ',
                    3 => 'Gọng Trẻ Em',
                    4 => 'Chống Ánh Sáng Xanh',
                    5 => 'Kính Đổi Màu',
                    6 => 'Kính Siêu Mỏng'
                ];
                
                // Lấy category từ $_GET trực tiếp
                $selectedCat = isset($_GET['category']) ? (int)$_GET['category'] : null;
                
                if ($selectedCat && isset($categoryNames[$selectedCat])) {
                    echo $categoryNames[$selectedCat];
                } else {
                    echo 'Tất cả sản phẩm';
                }
                ?>
            </span>
        </nav>

        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-4xl font-bold text-stone-900 mb-2">
                    <?php 
                    $categoryNames = [
                        1 => 'Gọng Nam',
                        2 => 'Gọng Nữ',
                        3 => 'Gọng Trẻ Em',
                        4 => 'Chống Ánh Sáng Xanh',
                        5 => 'Kính Đổi Màu',
                        6 => 'Kính Siêu Mỏng'
                    ];
                    
                    // Lấy category từ $_GET trực tiếp
                    $selectedCat = isset($_GET['category']) ? (int)$_GET['category'] : null;
                    
                    if ($selectedCat && isset($categoryNames[$selectedCat])) {
                        echo 'Bộ Sưu Tập ' . $categoryNames[$selectedCat];
                    } else {
                        echo 'Bộ Sưu Tập Mắt Kính';
                    }
                    ?>
                </h1>
                <p class="text-stone-500">Khám phá hàng trăm mẫu gọng kính và tròng kính hiện đại nhất 2026.</p>
            </div>
            <span class="text-sm font-medium text-stone-400 uppercase tracking-widest">

            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">

            <?php if (!empty($products)): ?>
            <?php foreach ($products as $item): ?>
            <div class="group flex flex-col">
                <div class="aspect-square rounded-[32px] overflow-hidden bg-stone-200 mb-6 relative shadow-sm">
                    <img src="/SELLING-GLASSES/public/assets/images/products/<?php echo $item['imagePath']; ?>"
                        alt="<?php echo $item['name']; ?>"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                    <div
                        class="absolute top-4 left-4 bg-white/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-stone-800">
                        <?php echo $item['categoryName']; ?>
                    </div>
                </div>

                <div class="space-y-2 px-2 flex-grow">
                    <h3 class="font-bold text-xl text-stone-900 group-hover:text-amber-700 transition">
                        <?php echo $item['name']; ?>
                    </h3>
                    <p class="text-stone-500 text-sm line-clamp-1">
                        <?php echo $item['description']; ?>
                    </p>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-amber-700 font-black text-lg">
                            <?php echo number_format($item['minPrice'], 0, ',', '.'); ?>đ
                        </span>
                        <button
                            class="add-to-cart-quick w-10 h-10 rounded-full bg-stone-900 text-white flex items-center justify-center hover:bg-amber-700 transition shadow-lg"
                            data-product-id="<?php echo $item['productId']; ?>"
                            data-variant-id="<?php echo $item['variantId'] ?? 0; ?>">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <a href="/SELLING-GLASSES/public/detail?id=<?php echo $item['productId']; ?>"
                    class="block mt-4 text-center py-3 border border-stone-200 rounded-2xl text-sm font-bold hover:bg-stone-900 hover:text-white transition uppercase tracking-widest">
                    Chi tiết sản phẩm
                </a>
            </div> <?php endforeach; ?>
            <?php else: ?>
            <div class="col-span-full py-20 text-center">
                <i class="fa-solid fa-box-open text-4xl text-stone-300 mb-4"></i>
                <p class="text-stone-500 italic">Chưa có sản phẩm nào trong danh mục này.</p>
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
<script src="/SELLING-GLASSES/public/assets/js/product-detail.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý click vào nút dấu cộng để thêm vào giỏ hàng
    const addToCartButtons = document.querySelectorAll('.add-to-cart-quick');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const variantId = this.getAttribute('data-variant-id');

            if (!variantId || variantId === '0') {
                alert('Vui lòng chọn màu sắc & kích thước trước khi thêm vào giỏ hàng');
                return;
            }

            // Gọi hàm addToCart từ cart.js
            if (typeof addToCart === 'function') {
                addToCart(variantId, 1);
            } else {
                alert('Hệ thống giỏ hàng chưa sẵn sàng. Vui lòng thử lại!');
            }
        });
    });
});
</script>