<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EYESGLASS - Hệ thống mắt kính AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/home.css" />
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/chatbox.css" />

</head>

<body class="bg-stone-50 text-stone-900 pt-[104px]">
    <div class="fixed top-0 left-0 w-full z-50 shadow-sm flex flex-col">
        <div class="bg-[#111] text-white text-[11px] py-2 px-6 flex justify-between items-center font-medium">
            <div class="flex space-x-6">
                <span> <i class="fa-solid fa-phone text-amber-500"></i> Hotline: 1900 1234</span>
                <span> <i class="fa-solid fa-truck text-amber-500"></i> Giao hàng toàn quốc - Miễn phí đơn từ
                    500k</span>
            </div>
            <div id="auth-box" class="flex space-x-4">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="flex items-center gap-2 cursor-default">
                        <i class="fa-solid fa-user"></i> Xin chào, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                    </span>
                    <span>|</span>
                    <a href="/SELLING-GLASSES/public/logout" class="hover:text-amber-500">Đăng xuất</a>
                <?php else: ?>
                    <a href="/SELLING-GLASSES/public/auth" class="hover:text-amber-500">Đăng nhập</a>
                    <span>|</span>
                    <a href="/SELLING-GLASSES/public/auth" class="hover:text-amber-500">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
        <header class="bg-white py-4 px-6 md:px-12 flex justify-between items-center border-b border-stone-100">
            <a href="/SELLING-GLASSES/public/home" class="font-bold text-3xl tracking-tighter hover:opacity-80 transition">
                EYESGLASS<span class="text-amber-600">.</span>
            </a>

            <nav class="hidden lg:flex space-x-8 font-medium text-stone-600">
                <a href="/SELLING-GLASSES/public/home#danh-muc" onclick="highlightCards('gong')"
                    class="hover:text-amber-700">Gọng kính</a>
                <a href="/SELLING-GLASSES/public/home#danh-muc" onclick="highlightCards('trong')"
                    class="hover:text-amber-700">Tròng kính</a>
                <a href="/SELLING-GLASSES/public/get-all-products" class="hover:text-amber-700">Sản phẩm</a>
                <a href="/SELLING-GLASSES/public/home#combo-section" class="hover:text-amber-700">Combo</a>
                <a href="/SELLING-GLASSES/public/home#" class="hover:text-amber-700">Khuyến mãi</a>
                <a href="/SELLING-GLASSES/public/home#try-on-section" class="hover:text-amber-700">Try On</a>
                <a href="javascript:void(0)" onclick="openChatboxAndConsult()" class="hover:text-amber-700">Tư vấn</a>
            </nav>

            <div class="flex space-x-5 text-xl text-stone-700 items-center relative">

                <!-- Thanh tìm kiếm ẩn -->
                <div id="search-bar" class="absolute top-1/2 -translate-y-1/2 right-[70px] flex items-center
                            bg-stone-100 rounded-full overflow-hidden w-0 opacity-0
                            transition-all duration-300 shadow-inner z-50">
                    <input type="text" id="search-input" placeholder="Bạn tìm kính gì..."
                        class="bg-transparent pl-4 pr-10 py-2 text-sm outline-none w-48 md:w-64 text-stone-700">
                </div>

                <!-- Icon tìm kiếm -->
                <button id="search-toggle-btn" class="relative z-50 hover:text-amber-700 transition-colors">
                    <i class="fas fa-search" id="search-icon"></i>
                </button>

                <!-- Icon tài khoản -->
                <a href="<?php echo isset($_SESSION['user']) ? '/SELLING-GLASSES/public/profile' : '/SELLING-GLASSES/public/auth'; ?>"
                    class="hover:text-amber-600 transition">
                    <i class="fas fa-user"></i>
                </a>

                <!-- Icon giỏ hàng -->
                <a href="/SELLING-GLASSES/public/cart" class="relative hover:text-amber-700 transition">
                    <i class="fa fa-shopping-cart"></i>
                    <span id="cart-count"
                        class="absolute -top-2 -right-2 bg-amber-700 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">0</span>
                </a>

                <!-- Icon chat support -->
                <button id="btn-home-chat" onclick="openSupportChat()" class="relative text-stone-700 hover:text-amber-700 transition">
                    <i class="fas fa-comment-dots"></i>
                    <span id="home-chat-badge" class="absolute -top-2 -right-2 bg-amber-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center" style="display:none;">0</span>
                </button>
            </div>
        </header>
    </div>