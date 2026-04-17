 <?php include_once __DIR__ . '/../layout/header.php'; ?>

 <section class="flex flex-col md:flex-row bg-[#F2EFE6]">
     <div class="md:w-1/2 p-12 md:p-24 flex flex-col justify-center space-y-6">
         <span class="text-amber-800 font-bold text-sm tracking-widest uppercase">Bộ sưu tập mới 2026</span>
         <h1 class="text-5xl md:text-6xl font-bold leading-tight">
             Chọn Kính Phù Hợp <br />
             Ngay Tại Nhà
         </h1>
         <p class="text-stone-600 text-lg max-w-md">
             Trải nghiệm thử kính online thông minh, đặt làm tròng chuẩn xác theo
             đơn bác sĩ và nhận hàng tận tay.
         </p>
         <div class="flex space-x-4 pt-4">
             <a href="#danh-muc"
                 class="inline-block px-10 py-4 bg-[#1C1917] text-white rounded-full font-bold hover:bg-black transition text-center">
                 Mua ngay
             </a>
             <a href="#try-on-section"
                 class="inline-block px-10 py-4 border-2 border-stone-800 rounded-full font-bold hover:bg-stone-800 hover:text-white transition text-center">
                 Thử kính online
             </a>
         </div>
     </div>
     <div class="md:w-1/2 h-[400px] md:h-auto overflow-hidden">
         <img src="/SELLING-GLASSES/public/assets/images/bannerhome.jpg" alt="Banner"
             class="w-full h-full object-cover object-center" />
     </div>
 </section>

 <section id="danh-muc" class="py-20 px-6 max-w-7xl mx-auto text-center scroll-mt-[104px]">
     <h2 class="text-4xl font-bold mb-12">Khám Phá Danh Mục</h2>
     <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
         <div class="group cursor-pointer">
             <div
                 class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                 <img src="/SELLING-GLASSES/public/assets/images/products/gongnam.jpg" alt="Gọng Nam"
                     class="w-full h-full object-cover rounded-[32px]">
             </div>
             <p class="font-bold text-stone-600">Gọng Nam</p>
         </div>
         <div class="group cursor-pointer">
             <div
                 class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                 <img src="/SELLING-GLASSES/public/assets/images/products/gongnu.jpg" alt="Gọng Nữ"
                     class="w-full h-full object-cover rounded-[32px]">
             </div>
             <p class="font-bold text-stone-600">Gọng Nữ</p>
         </div>
         <div class="group cursor-pointer">
             <div
                 class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                 <img src="/SELLING-GLASSES/public/assets/images/products/gongtrem.jpg" alt="Gọng Trẻ Em"
                     class="w-full h-full object-cover rounded-[32px]">
             </div>
             <p class="font-bold text-stone-600">Gọng Trẻ Em</p>
         </div>
         <div class="group cursor-pointer">
             <div
                 class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                 <img src="/SELLING-GLASSES/public/assets/images/products/anhsangxanh.jpg" alt="Tròng AS Xanh"
                     class="w-full h-full object-cover rounded-[32px]">
             </div>
             <p class="font-bold text-stone-600">Chống Ánh Sáng Xanh</p>
         </div>
         <div class="group cursor-pointer">
             <div
                 class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                 <img src="/SELLING-GLASSES/public/assets/images/products/doimau.jpg" alt="Kính Đổi Màu"
                     class="w-full h-full object-cover rounded-[32px]">
             </div>
             <p class="font-bold text-stone-600">Kính Đổi Màu</p>
         </div>
         <div class="group cursor-pointer">
             <div
                 class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                 <img src="/SELLING-GLASSES/public/assets/images/products/sieumong.jpg" alt="Kính Siêu Mỏng"
                     class="w-full h-full object-cover rounded-[32px]">
             </div>
             <p class="font-bold text-stone-600">Kính Siêu Mỏng</p>
         </div>
     </div>
 </section>

 <section class="py-20 px-6 max-w-7xl mx-auto">
     <div class="flex justify-between items-end mb-10">
         <h2 class="text-4xl font-bold">Sản Phẩm Bán Chạy</h2>
         <a href="/SELLING-GLASSES/public/get-all-products"
             class="text-amber-700 font-bold flex items-center gap-2 group">
             Xem tất cả
             <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
         </a>
     </div>
     <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
         <?php if (!empty($products)): ?>
         <?php foreach ($products as $product): ?>
         <div
             class="bg-white rounded-[32px] overflow-hidden border border-stone-100 group shadow-sm hover:shadow-md transition-shadow">

             <a href="/SELLING-GLASSES/public/detail?id=<?php echo $product->productId; ?>" class="block">
                 <div class="h-72 bg-stone-100 relative flex items-center justify-center overflow-hidden">
                     <?php if (isset($product->is_best_seller) && $product->is_best_seller): ?>
                     <span
                         class="absolute top-4 left-4 z-10 bg-black text-white text-[10px] px-2 py-1 rounded font-bold uppercase">Best
                         Seller</span>
                     <?php endif; ?>

                     <img src="/SELLING-GLASSES/public/assets/images/products/<?php echo $product->imagePath; ?>"
                         alt="<?php echo $product->name; ?>"
                         class="w-full h-full object-cover rounded-[32px] transition-transform duration-500 group-hover:scale-110">
                 </div>
             </a>

             <div class="p-6">
                 <a href="/SELLING-GLASSES/public/detail?id=<?php echo $product->productId; ?>" class="block">
                     <h3 class="font-bold text-lg mb-2 text-stone-800 hover:text-amber-700 transition-colors">
                         <?php echo $product->name; ?>
                     </h3>
                 </a>

                 <p class="text-amber-700 font-bold text-xl mb-4">
                     <?php echo number_format($product->price ?? 0, 0, ',', '.'); ?>đ
                 </p>

                 <button onclick="addToCart(<?php echo $product->variantId; ?>)"
                     class="w-full py-3 border border-stone-800 rounded-xl font-bold group-hover:bg-stone-800 group-hover:text-white transition">
                     Thêm vào giỏ
                 </button>
             </div>
         </div>
         <?php endforeach; ?>
         <?php else: ?>
         <p class="col-span-4 text-center text-stone-400">Đang cập nhật sản phẩm...</p>
         <?php endif; ?>
     </div>
 </section>

 <section id="combo-section" class="py-20 px-6 max-w-7xl mx-auto scroll-mt-[104px]">
     <div
         class="bg-[#8B4513] rounded-[40px] p-12 text-white flex flex-col md:flex-row items-center justify-between mb-16 relative overflow-hidden">
         <div class="md:w-2/3 space-y-4 relative z-10">
             <h2 class="text-5xl font-bold leading-tight">
                 Combo Gọng + Tròng <br />
                 Tiết Kiệm Đến 30%
             </h2>
             <p class="text-stone-200 text-lg">
                 Giải pháp hoàn hảo cho đôi mắt. Chọn gọng yêu thích, ghép tròng cao
                 cấp với mức giá ưu đãi nhất.
             </p>
         </div>
         <button
             class="bg-white text-stone-900 px-10 py-4 rounded-full font-bold shadow-lg hover:bg-stone-100 transition relative z-10">
             Khám phá Combo
         </button>
     </div>
     <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
         <div class="bg-white rounded-[32px] p-8 border border-stone-100 text-center shadow-sm">
             <div class="h-48 bg-stone-100 rounded-2xl mb-6 flex items-center justify-center">
                 <img src="/SELLING-GLASSES/public/assets/images/products/combo_sinhvien.jpg" alt="Combo Sinh Viên"
                     class="w-full h-full object-cover">
             </div>
             <h3 class="font-bold text-xl mb-1">Combo Sinh Viên</h3>
             <p class="text-stone-400 text-sm mb-4">
                 Gọng Basic + Tròng chống xước
             </p>
             <p class="text-amber-700 font-bold text-2xl mb-6">499.000đ</p>
             <button class="w-full py-3 bg-stone-100 rounded-xl font-bold text-stone-600 hover:bg-stone-200 transition">
                 Chọn combo này
             </button>
         </div>
         <div class="bg-white rounded-[32px] p-8 border-2 border-amber-500 text-center shadow-xl relative scale-105">
             <span
                 class="absolute -top-4 left-1/2 -translate-x-1/2 bg-amber-500 text-white px-4 py-1 rounded-full text-[10px] font-bold uppercase">Hot
                 nhất</span>
             <div class="h-48 bg-stone-100 rounded-2xl mb-6 flex items-center justify-center">
                 <img src="/SELLING-GLASSES/public/assets/images/products/combo_vanphong.jpg" alt="Combo Văn Phòng"
                     class="w-full h-full object-cover">
             </div>
             <h3 class="font-bold text-xl mb-1">Combo Văn Phòng</h3>
             <p class="text-stone-400 text-sm mb-4">
                 Gọng Titan + Tròng chống AS Xanh
             </p>
             <p class="text-amber-700 font-bold text-2xl mb-6">899.000đ</p>
             <button class="w-full py-3 bg-[#1C1917] rounded-xl font-bold text-white hover:bg-black transition">
                 Chọn combo này
             </button>
         </div>
         <div class="bg-white rounded-[32px] p-8 border border-stone-100 text-center shadow-sm">
             <div class="h-48 bg-stone-100 rounded-2xl mb-6 flex items-center justify-center">
                 <img src="/SELLING-GLASSES/public/assets/images/products/combo_sieunet.jpg" alt="Combo Siêu Nét"
                     class="w-full h-full object-cover">
             </div>
             <h3 class="font-bold text-xl mb-1">Combo Siêu Nét</h3>
             <p class="text-stone-400 text-sm mb-4">
                 Gọng Thời Trang + Tròng Siêu Mỏng
             </p>
             <p class="text-amber-700 font-bold text-2xl mb-6">1.290.000đ</p>
             <button class="w-full py-3 bg-stone-100 rounded-xl font-bold text-stone-600 hover:bg-stone-200 transition">
                 Chọn combo này
             </button>
         </div>
     </div>
 </section>

 <section id="try-on-section" class="bg-[#1C1917] text-white py-24 px-6 scroll-mt-[104px]">
     <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
         <div class="md:w-1/2 flex justify-center">
             <div
                 class="relative w-72 h-[500px] bg-stone-800 rounded-[45px] border-8 border-stone-700 overflow-hidden shadow-2xl flex items-center justify-center">
                 <video id="video" autoplay playsinline
                     class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500"></video>
                 <canvas id="canvas"
                     class="absolute inset-0 w-full h-full object-cover z-10 opacity-0 transition-opacity duration-500"></canvas>

                 <div id="tryon-placeholder"
                     class="z-20 text-stone-500 text-center text-sm px-8 pointer-events-none transition-opacity duration-300">
                     [Hình Demo Camera Điện Thoại] <br />
                     Quét khuôn mặt AI
                 </div>

                 <div id="scan-line"
                     class="absolute inset-0 bg-gradient-to-b from-transparent via-amber-500/25 to-transparent w-full h-1/2 animate-scan top-0 pointer-events-none transition-opacity duration-300">
                 </div>

             </div>
         </div>

         <div class="md:w-1/2 space-y-6">
             <h2 class="text-5xl font-bold">Thử Kính Ảo Bằng AI</h2>
             <p class="text-stone-400 text-lg leading-relaxed">
                 Chưa biết gọng nào hợp với khuôn mặt? Công nghệ AI của chúng tôi sẽ
                 quét 3D khuôn mặt bạn, gợi ý kích thước và kiểu dáng hoàn hảo nhất.
             </p>
             <ul class="space-y-4 font-medium">
                 <li class="flex items-center space-x-3">
                     <i class="fa-solid fa-face-smile text-amber-500"></i> <span>Nhận diện khuôn mặt chính xác</span>
                 </li>
                 <li class="flex items-center space-x-3">
                     <i class="fa-solid fa-ruler text-amber-500"></i> <span id="ai-size-result">Gợi ý size kính vừa
                         vặn</span>
                 </li>
                 <li class="flex items-center space-x-3">
                     <i class="fa-solid fa-sync-alt text-amber-500"></i> <span>Thử nhiều kiểu gọng kính theo khuôn
                         mặt</span>
                 </li>
             </ul>
             <div class="relative inline-block mt-8">
                    <button id="btn-start-tryon"
                        class="px-10 py-4 bg-amber-600 rounded-full font-bold shadow-[0_0_20px_rgba(217,119,6,0.3)] hover:bg-amber-500 transition">
                        Bắt đầu thử kính ngay
                    </button>

                    <div id="tryon-controls" class="hidden absolute left-full top-1/2 -translate-y-1/2 ml-4">
                            <button onclick="toggleBunny()" 
                                class="px-4 py-2 bg-pink-500 rounded-lg text-white font-bold flex items-center gap-2">
                                <i class="fa-solid fa-paw"></i>
                                Bunny
                            </button>
                        </div>
                </div>
             <!--  DANH SÁCH CHỌN KÍNH (ẨN BAN ĐẦU) -->
             <div id="glasses-selector" class="mt-6 flex flex-wrap gap-3 hidden">
                 <button onclick="changeGlasses('Square')"
                     class="px-4 py-2 bg-white text-black rounded-lg font-bold hover:bg-gray-200 transition">
                     Vuông
                 </button>

                 <button onclick="changeGlasses('Round')"
                     class="px-4 py-2 bg-white text-black rounded-lg font-bold hover:bg-gray-200 transition">
                     Tròn
                 </button>

                 <button onclick="changeGlasses('Oval')"
                     class="px-4 py-2 bg-white text-black rounded-lg font-bold hover:bg-gray-200 transition">
                     Oval
                 </button>

                 <button onclick="changeGlasses('Rectangle')"
                     class="px-4 py-2 bg-white text-black rounded-lg font-bold hover:bg-gray-200 transition">
                     Chữ nhật
                 </button>

                 <button onclick="changeGlasses('Cat-eye')"
                     class="px-4 py-2 bg-white text-black rounded-lg font-bold hover:bg-gray-200 transition">
                     Cat-eye
                 </button>
                
             </div>
         </div>
     </div>
 </section>

 <section class="py-24 bg-white text-center">
     <div class="max-w-7xl mx-auto px-6">
         <h2 class="text-3xl font-bold mb-16">Vì sao chọn chúng tôi?</h2>
         <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-24">
             <div class="flex flex-col items-center gap-3">
                 <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                     <i class="fa-solid fa-shield-halved text-blue-500"></i>
                 </div>
                 <p class="font-bold text-sm">Bảo hành 12 tháng</p>
             </div>
             <div class="flex flex-col items-center gap-3">
                 <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                     <i class="fa-solid fa-rotate-left text-blue-500"></i>
                 </div>
                 <p class="font-bold text-sm">Đổi trả 7 ngày</p>
             </div>
             <div class="flex flex-col items-center gap-3">
                 <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                     <i class="fa-solid fa-truck text-blue-500"></i>
                 </div>
                 <p class="font-bold text-sm">Giao hàng toàn quốc</p>
             </div>
             <div class="flex flex-col items-center gap-3">
                 <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                     <i class="fa-solid fa-gear text-blue-500"></i>
                 </div>
                 <p class="font-bold text-sm">Gia công chuẩn xác</p>
             </div>
             <div class="flex flex-col items-center gap-3">
                 <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                     <i class="fa-solid fa-comment text-blue-500"></i>
                 </div>
                 <p class="font-bold text-sm">Tư vấn miễn phí</p>
             </div>
         </div>

         <h2 class="text-3xl font-bold mb-16">
             Khách Hàng Nói Gì Về EYESGLASS?
         </h2>
         <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
             <?php if (!empty($reviews) && is_array($reviews)): ?>
                 <?php foreach ($reviews as $review): ?>
                     <div class="bg-stone-50 p-8 rounded-[32px] border border-stone-100">
                         <div class="text-amber-500 mb-4">
                             <?php 
                             $rating = isset($review['rating']) ? intval($review['rating']) : 5;
                             for ($i = 0; $i < 5; $i++): 
                             ?>
                                 <i class="fa-solid fa-star" style="<?= $i < $rating ? '' : 'opacity: 0.3;' ?>"></i>
                             <?php endfor; ?>
                         </div>
                         <p class="text-stone-600 italic mb-8">
                             "<?php echo htmlspecialchars($review['comment'] ?? 'Sản phẩm rất tốt!'); ?>"
                         </p>
                         <div class="flex items-center gap-4">
                             <div class="w-12 h-12 bg-stone-200 rounded-full flex items-center justify-center" style="color: white; background: linear-gradient(135deg, #d97706 0%, #ea580c 100%); font-weight: bold;">
                                 <?php echo strtoupper(substr($review['customerName'] ?? 'User', 0, 1)); ?>
                             </div>
                             <div>
                                 <p class="font-bold"><?php echo htmlspecialchars($review['customerName'] ?? 'Khách hàng'); ?></p>
                                 <p class="text-xs text-stone-400"><?php echo date('d/m/Y', strtotime($review['createdDate'] ?? date('Y-m-d'))); ?></p>
                             </div>
                         </div>
                     </div>
                 <?php endforeach; ?>
             <?php else: ?>
                 <div class="bg-stone-50 p-8 rounded-[32px] border border-stone-100 md:col-span-3 text-center">
                     <p class="text-stone-600">Chưa có đánh giá nào. Hãy mua sản phẩm và chia sẻ trải nghiệm của bạn!</p>
                 </div>
             <?php endif; ?>
         </div>
     </div>
 </section>

 <section class="py-24 bg-[#F5F1E7]">
     <div class="max-w-7xl mx-auto px-6">
         <div class="flex justify-between items-end mb-12">
             <h2 class="text-4xl font-bold">Góc Tư Vấn Mắt</h2>
             <a href="#" class="text-amber-800 font-bold hover:underline">Xem thêm bài viết
                 <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i></a>
         </div>
         <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
             <div class="bg-white rounded-[32px] overflow-hidden group shadow-sm">
                 <div class="h-64 bg-stone-200 flex items-center justify-center text-stone-400">
                     <img src="/SELLING-GLASSES/public/assets/images/thumbnail1.jpg" alt="Cách chọn gọng kính"
                         class="w-full h-full object-cover transition duration-300 hover:scale-105">
                 </div>
                 <div class="p-8">
                     <span class="text-amber-700 font-bold text-xs uppercase tracking-widest">Mẹo hay</span>
                     <h3 class="font-bold text-xl mt-3 mb-6 leading-snug">
                         Cách chọn gọng kính phù hợp với từng khuôn mặt
                     </h3>
                     <a href="#"
                         class="text-stone-900 font-bold border-b-2 border-amber-300 pb-1 hover:border-amber-700 transition">Đọc
                         thêm</a>
                 </div>
             </div>
         </div>
     </div>
 </section>

 <?php include_once __DIR__ . '/../layout/footer.php'; ?>
 <?php include_once __DIR__ . '/../partials/chatbox.php'; ?>
 <script src="/SELLING-GLASSES/public/assets/js/chatbox.js"></script>
 <script src="/SELLING-GLASSES/public/assets/js/auth.js"></script>
 <script src="/SELLING-GLASSES/public/assets/js/cart.js"></script>
 <script src="/SELLING-GLASSES/public/assets/js/home.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <?php include_once __DIR__ . '/../partials/policies.php'; ?>
 <script src="/SELLING-GLASSES/public/assets/js/policy.js"></script>


 <script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
 <script src="/SELLING-GLASSES/public/assets/js/tryon.js" defer></script>

 </body>

 </html>