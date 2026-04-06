<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LENS - Hệ thống mắt kính AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/home.css" />
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/chatbox.css" />
</head>

<body class="bg-stone-50 text-stone-900 pt-[104px]">
    <div class="fixed top-0 left-0 w-full z-50 shadow-sm flex flex-col">
        <div class="bg-[#111] text-white text-[11px] py-2 px-6 flex justify-between items-center font-medium">
            <div class="flex space-x-6">
                <span>📞 Hotline: 1900 xxxx</span>
                <span>🚚 Giao hàng toàn quốc - Miễn phí đơn từ 500k</span>
            </div>
            <div id="auth-box" class="flex space-x-4">
                <a href="/SELLING-GLASSES/public/auth" class="hover:text-amber-500">Đăng nhập</a>
                <span>|</span>
                <a href="/SELLING-GLASSES/public/auth" class="hover:text-amber-500">Đăng ký</a>
            </div>
        </div>
        <header class="bg-white py-4 px-6 md:px-12 flex justify-between items-center border-b border-stone-100">
            <div class="font-bold text-3xl tracking-tighter">
                EYESGLASS<span class="text-amber-600">.</span>
            </div>
            <nav class="hidden lg:flex space-x-8 font-medium text-stone-600">
                <a href="#" class="hover:text-amber-700">Gọng kính</a>
                <a href="#" class="hover:text-amber-700">Tròng kính</a>
                <a href="#" class="hover:text-amber-700">Combo</a>
                <a href="#" class="text-red-600 font-bold">Khuyến mãi</a>
                <a href="#" class="hover:text-amber-700">Try On</a>
                <a href="#" class="hover:text-amber-700">Tư vấn</a>
            </nav>
            <div class="flex space-x-5 text-xl text-stone-700 items-center">
                <button>🔍</button>
                <button>👤</button>
                <button class="relative">
                    🛒<span
                        class="absolute -top-2 -right-2 bg-amber-700 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">3</span>
                </button>
            </div>
        </header>
    </div>

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
                <button class="px-10 py-4 bg-[#1C1917] text-white rounded-full font-bold hover:bg-black transition">
                    Mua ngay
                </button>
                <button
                    class="px-10 py-4 border-2 border-stone-800 rounded-full font-bold hover:bg-stone-800 hover:text-white transition">
                    Thử kính online
                </button>
            </div>
        </div>
        <div class="md:w-1/2 h-[400px] md:h-auto overflow-hidden">
            <img src="/SELLING-GLASSES/public/assets/images/bannerhome.jpg" alt="Banner"
                class="w-full h-full object-cover object-center" />
        </div>
    </section>

    <section class="py-20 px-6 max-w-7xl mx-auto text-center">
        <h2 class="text-4xl font-bold mb-12">Khám Phá Danh Mục</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="group cursor-pointer">
                <div
                    class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                    <span class="text-stone-300">[Gọng Nam]</span>
                </div>
                <p class="font-bold text-stone-600">Gọng Nam</p>
            </div>
            <div class="group cursor-pointer">
                <div
                    class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                    <span class="text-stone-300">[Gọng Nữ]</span>
                </div>
                <p class="font-bold text-amber-700">Gọng Nữ</p>
            </div>
            <div class="group cursor-pointer">
                <div
                    class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                    <span class="text-stone-300">[Trẻ Em]</span>
                </div>
                <p class="font-bold text-stone-600">Gọng Trẻ Em</p>
            </div>
            <div class="group cursor-pointer">
                <div
                    class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                    <span class="text-stone-300">[Tròng AS Xanh]</span>
                </div>
                <p class="font-bold text-stone-600">Chống Ánh Sáng Xanh</p>
            </div>
            <div class="group cursor-pointer">
                <div
                    class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                    <span class="text-stone-300">[Đổi Màu]</span>
                </div>
                <p class="font-bold text-stone-600">Kính Đổi Màu</p>
            </div>
            <div class="group cursor-pointer">
                <div
                    class="bg-white rounded-[32px] aspect-square border border-stone-100 shadow-sm flex items-center justify-center mb-4 group-hover:shadow-lg transition">
                    <span class="text-stone-300">[Siêu Mỏng]</span>
                </div>
                <p class="font-bold text-stone-600">Kính Siêu Mỏng</p>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-end mb-10">
            <h2 class="text-4xl font-bold">Sản Phẩm Bán Chạy</h2>
            <a href="#" class="text-amber-700 font-bold hover:underline">Xem tất cả →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="bg-white rounded-[32px] overflow-hidden border border-stone-100 group shadow-sm">
                <div class="h-72 bg-stone-100 relative flex items-center justify-center">
                    <span
                        class="absolute top-4 left-4 bg-black text-white text-[10px] px-2 py-1 rounded font-bold uppercase">Best
                        Seller</span>
                    <span class="text-stone-300">[Ảnh Sản Phẩm]</span>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-2 text-stone-800">
                        Gọng Titan Classic
                    </h3>
                    <p class="text-amber-700 font-bold text-xl mb-4">750.000đ</p>
                    <button
                        class="w-full py-3 border border-stone-800 rounded-xl font-bold group-hover:bg-stone-800 group-hover:text-white transition">
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-[32px] overflow-hidden border border-stone-100 group shadow-sm">
                <div class="h-72 bg-stone-100 relative flex items-center justify-center">
                    <span
                        class="absolute top-4 left-4 bg-red-600 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">-20%</span>
                    <span class="text-stone-300">[Ảnh Sản Phẩm]</span>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-2 text-stone-800">
                        Gọng Nhựa Dẻo Tròn
                    </h3>
                    <div class="flex space-x-3 items-center mb-4">
                        <p class="text-amber-700 font-bold text-xl">450.000đ</p>
                        <p class="text-stone-400 line-through text-sm">560.000đ</p>
                    </div>
                    <button
                        class="w-full py-3 border border-stone-800 rounded-xl font-bold group-hover:bg-stone-800 group-hover:text-white transition">
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-[32px] overflow-hidden border border-stone-100 group shadow-sm">
                <div class="h-72 bg-stone-100 relative flex items-center justify-center">
                    <span class="text-stone-300">[Ảnh Sản Phẩm]</span>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-2 text-stone-800">
                        Gọng Mắt Mèo Nữ
                    </h3>
                    <p class="text-amber-700 font-bold text-xl mb-4">520.000đ</p>
                    <button
                        class="w-full py-3 border border-stone-800 rounded-xl font-bold group-hover:bg-stone-800 group-hover:text-white transition">
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-[32px] overflow-hidden border border-stone-100 group shadow-sm">
                <div class="h-72 bg-stone-100 relative flex items-center justify-center">
                    <span
                        class="absolute top-4 left-4 bg-blue-600 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">New</span>
                    <span class="text-stone-300">[Ảnh Sản Phẩm]</span>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-2 text-stone-800">
                        Gọng Kim Loại Mảnh
                    </h3>
                    <p class="text-amber-700 font-bold text-xl mb-4">680.000đ</p>
                    <button
                        class="w-full py-3 border border-stone-800 rounded-xl font-bold group-hover:bg-stone-800 group-hover:text-white transition">
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 max-w-7xl mx-auto">
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
                    [Ảnh Combo 1]
                </div>
                <h3 class="font-bold text-xl mb-1">Combo Sinh Viên</h3>
                <p class="text-stone-400 text-sm mb-4">
                    Gọng Basic + Tròng chống xước
                </p>
                <p class="text-amber-700 font-bold text-2xl mb-6">499.000đ</p>
                <button
                    class="w-full py-3 bg-stone-100 rounded-xl font-bold text-stone-600 hover:bg-stone-200 transition">
                    Chọn combo này
                </button>
            </div>
            <div class="bg-white rounded-[32px] p-8 border-2 border-amber-500 text-center shadow-xl relative scale-105">
                <span
                    class="absolute -top-4 left-1/2 -translate-x-1/2 bg-amber-500 text-white px-4 py-1 rounded-full text-[10px] font-bold uppercase">Hot
                    nhất</span>
                <div class="h-48 bg-stone-100 rounded-2xl mb-6 flex items-center justify-center">
                    [Ảnh Combo 2]
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
                    [Ảnh Combo 3]
                </div>
                <h3 class="font-bold text-xl mb-1">Combo Siêu Nét</h3>
                <p class="text-stone-400 text-sm mb-4">
                    Gọng Thời Trang + Tròng Siêu Mỏng
                </p>
                <p class="text-amber-700 font-bold text-2xl mb-6">1.290.000đ</p>
                <button
                    class="w-full py-3 bg-stone-100 rounded-xl font-bold text-stone-600 hover:bg-stone-200 transition">
                    Chọn combo này
                </button>
            </div>
        </div>
    </section>

    <section class="bg-[#1C1917] text-white py-24 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
            <div class="md:w-1/2 flex justify-center">
                <div
                    class="relative w-72 h-[500px] bg-stone-800 rounded-[45px] border-8 border-stone-700 overflow-hidden shadow-2xl flex items-center justify-center">
                    <div class="z-10 text-stone-500 text-center text-sm px-8 pointer-events-none">
                        [Hình Demo Camera Điện Thoại] <br />
                        Quét khuôn mặt AI
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-b from-transparent via-amber-500/25 to-transparent w-full h-1/2 animate-scan top-0 pointer-events-none">
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
                        ✨ <span>Nhận diện khuôn mặt chính xác</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        📏 <span>Gợi ý size kính vừa vặn</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        🔄 <span>So sánh hàng trăm mẫu cùng lúc</span>
                    </li>
                </ul>
                <button
                    class="mt-8 px-10 py-4 bg-amber-600 rounded-full font-bold shadow-[0_0_20px_rgba(217,119,6,0.3)] hover:bg-amber-500 transition">
                    Bắt đầu thử kính ngay
                </button>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white text-center">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-16">Vì sao chọn chúng tôi?</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-24">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                        🛡️
                    </div>
                    <p class="font-bold text-sm">Bảo hành 12 tháng</p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                        🔄
                    </div>
                    <p class="font-bold text-sm">Đổi trả 7 ngày</p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                        🚚
                    </div>
                    <p class="font-bold text-sm">Giao hàng toàn quốc</p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                        ⚙️
                    </div>
                    <p class="font-bold text-sm">Gia công chuẩn xác</p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                        💬
                    </div>
                    <p class="font-bold text-sm">Tư vấn miễn phí</p>
                </div>
            </div>

            <h2 class="text-3xl font-bold mb-16">
                Khách Hàng Nói Gì Về EYESGLASS?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <div class="bg-stone-50 p-8 rounded-[32px] border border-stone-100">
                    <div class="text-amber-500 mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-stone-600 italic mb-8">
                        "Tính năng thử kính AI quá xịn! Mình đã chọn được gọng Titan rất
                        vừa vặn mà không cần đến cửa hàng."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-stone-200 rounded-full"></div>
                        <div>
                            <p class="font-bold">Minh Tú</p>
                            <p class="text-xs text-stone-400">Đã mua: Gọng Titan Classic</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-[#F5F1E7]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-4xl font-bold">Góc Tư Vấn Mắt</h2>
                <a href="#" class="text-amber-800 font-bold hover:underline">Xem thêm bài viết →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-[32px] overflow-hidden group shadow-sm">
                    <div class="h-64 bg-stone-200 flex items-center justify-center text-stone-400">
                        [Ảnh Thumbnail 1]
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

    <footer class="bg-[#0A0A0A] text-stone-400 py-10 px-6 border-t border-stone-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-16">
            <div class="space-y-6">
                <div class="font-bold text-3xl text-white">
                    EYESGLASS<span class="text-amber-600">.</span>
                </div>
                <p class="text-sm leading-relaxed">
                    Thương hiệu mắt kính hiện đại, mang đến giải pháp thị lực toàn diện
                    kết hợp công nghệ AI. Mua sắm dễ dàng, tận tâm và chuyên nghiệp.
                </p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-8 uppercase text-xs tracking-[3px]">
                    Liên kết nhanh
                </h4>
                <ul class="space-y-4 text-sm">
                    <li>
                        <a href="#" class="hover:text-white transition">Tất cả sản phẩm</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Chương trình khuyến mãi</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Combo Gọng + Tròng</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Thử kính AI</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-8 uppercase text-xs tracking-[3px]">
                    Chính sách
                </h4>
                <ul class="space-y-4 text-sm">
                    <li>
                        <a href="#" class="hover:text-white transition">Chính sách đổi trả</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Chính sách bảo hành</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Phương thức thanh toán</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Chính sách giao hàng</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-8 uppercase text-xs tracking-[3px]">
                    Liên hệ
                </h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-center gap-3">
                        📞 <span>Hotline: 1900 xxxx (8h-22h)</span>
                    </li>
                    <li class="flex items-center gap-3">
                        ✉️ <span>Email: hello@lens.vn</span>
                    </li>
                    <li class="flex items-center gap-3">
                        📍 <span>Cửa hàng: 123 Đường ABC, Quận X, TP.HCM</span>
                    </li>
                </ul>
                <div class="flex space-x-4 mt-8">
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-stone-900 flex items-center justify-center hover:bg-white hover:text-black transition">FB</a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-stone-900 flex items-center justify-center hover:bg-white hover:text-black transition">IG</a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-stone-900 flex items-center justify-center hover:bg-white hover:text-black transition">TT</a>
                </div>
            </div>
        </div>
        <div
            class="max-w-7xl mx-auto mt-24 pt-8 border-t border-stone-900 text-center text-xs tracking-widest uppercase">
            © 2026 EYESGLASS. All rights reserved.
        </div>
    </footer>

    <div id="ai-chat-panel"
        class="fixed bottom-24 right-8 w-[90vw] sm:w-[400px] max-w-[400px] h-[80vh] max-h-[600px] bg-white rounded-2xl shadow-2xl flex flex-col z-[100] transition-all duration-300 opacity-0 pointer-events-none translate-y-10 overflow-hidden border border-stone-200">
        <div class="bg-stone-900 text-white p-4 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center text-xl">
                    🤖
                </div>
                <div>
                    <h4 class="font-bold text-[15px] leading-tight">
                        Trợ lý Kính sành điệu
                    </h4>
                    <p class="text-xs text-green-400 flex items-center gap-1 mt-0.5">
                        <span class="w-2 h-2 bg-green-400 rounded-full inline-block animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>
            <div class="flex gap-4 items-center">
                <button id="minimize-chat"
                    class="text-stone-400 hover:text-white transition text-2xl leading-none -mt-3">
                    _
                </button>
                <button id="close-chat" class="text-stone-400 hover:text-red-500 transition text-2xl leading-none">
                    ×
                </button>
            </div>
        </div>

        <div id="chat-body" class="flex-1 bg-stone-50 p-4 overflow-y-auto chat-scroll flex flex-col gap-4 relative">
            <div class="flex gap-2 w-full">
                <div
                    class="w-8 h-8 bg-amber-600 rounded-full flex-shrink-0 flex items-center justify-center text-sm shadow-sm">
                    🤖
                </div>
                <div class="flex flex-col gap-1 max-w-[85%]">
                    <div
                        class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm text-[14px] text-stone-800 border border-stone-100">
                        Ngày mới tốt lành 👋 Tôi là trợ lý Kính sành điệu của EYESGLASS.
                        Tôi có thể giúp gì cho baby nò?
                    </div>
                </div>
            </div>
            <div id="chat-anchor"></div>
        </div>

        <div id="chat-footer" class="p-3 bg-white border-t border-stone-200 flex gap-2 items-center">
            <input type="text" id="chat-input" placeholder="Nhập câu hỏi của bạn..."
                class="flex-1 bg-stone-100 rounded-full px-4 py-2 text-[14px] focus:outline-none focus:ring-1 focus:ring-amber-500 border border-transparent transition"
                onkeypress="handleEnter(event)" />
            <button onclick="sendManualMessage()" id="send-btn"
                class="w-9 h-9 bg-amber-600 text-white rounded-full flex items-center justify-center hover:bg-amber-700 transition flex-shrink-0 shadow-md">
                ➤
            </button>
        </div>
    </div>

    <div id="ai-icon"
        class="fixed bottom-8 right-8 w-16 h-16 bg-amber-600 text-white rounded-full flex items-center justify-center shadow-xl cursor-grab z-[101] text-3xl glow-effect">
        🤖
    </div>

    <script src="/SELLING-GLASSES/public/assets/js/chatbox.js"></script>
    <script src="/SELLING-GLASSES/public/assets/js/auth.js"></script>
</body>

</html>