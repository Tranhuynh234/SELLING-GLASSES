<?php include_once __DIR__ . "/../layout/header.php"; ?>

<main class="bg-stone-50 min-h-screen pt-[120px] pb-20">
    <div class="max-w-7xl mx-auto px-6">

        <nav class="flex text-sm text-stone-500 mb-8">
            <a href="/SELLING-GLASSES/public/home" class="hover:text-amber-700">Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-stone-900 font-medium">Tất cả sản phẩm</span>
        </nav>

        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-4xl font-bold text-stone-900 mb-2">Bộ Sưu Tập Mắt Kính</h1>
                <p class="text-stone-500">Khám phá hàng trăm mẫu gọng kính và tròng kính hiện đại nhất 2026.</p>
            </div>
            <span class="text-sm font-medium text-stone-400 uppercase tracking-widest">
                <?php echo count($products); ?> Sản phẩm
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
                            <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                        </span>
                        <button
                            class="w-10 h-10 rounded-full bg-stone-900 text-white flex items-center justify-center hover:bg-amber-700 transition shadow-lg">
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

        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="flex justify-center items-center mt-20 gap-3">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/SELLING-GLASSES/public/get-all-products?page=<?php echo $i; ?>"
                class="w-12 h-12 flex items-center justify-center rounded-2xl font-bold transition-all
                <?php echo ($i == $currentPage) ? 'bg-stone-900 text-white shadow-xl scale-110' : 'bg-white border border-stone-200 text-stone-600 hover:border-stone-900'; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

    </div>
</main>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>