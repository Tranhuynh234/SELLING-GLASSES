<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EYESGLASS - Hệ thống bán kính mắt</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    </style>
</head>

<body class="bg-stone-50 text-stone-900">
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md z-50 border-b border-stone-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/SELLING-GLASSES/public/home" class="text-2xl font-extrabold tracking-tighter text-stone-900">
                EYES<span class="text-amber-700">GLASS</span>
            </a>
            <div class="flex gap-8 font-bold text-sm uppercase tracking-widest">
                <a href="#" class="hover:text-amber-700 transition-colors">Cửa hàng</a>
                <a href="#" class="hover:text-amber-700 transition-colors">Bộ sưu tập</a>
                <a href="#" class="hover:text-amber-700 transition-colors text-amber-700">Chi tiết</a>
            </div>
            <div class="flex items-center gap-6">
                <i class="fas fa-search cursor-pointer hover:text-amber-700"></i>
                <div class="relative cursor-pointer hover:text-amber-700">
                    <i class="fas fa-shopping-bag text-xl"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-amber-700 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
                </div>
            </div>
        </div>
    </nav>