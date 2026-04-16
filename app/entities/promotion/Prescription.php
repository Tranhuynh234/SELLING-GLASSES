<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn kính - EYESGLASS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/prescription.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eg-orange': '#d97706', // Màu cam/nâu của brand
                        'eg-orange-hover': '#b45309',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <div class="bg-black text-white text-xs py-2 px-6 flex justify-between items-center">
        <div class="flex gap-4">
            <span>📞 Hotline: 1900 1234</span>
            <span>🚚 Giao hàng toàn quốc - Miễn phí đơn từ 500k</span>
        </div>
        <div class="flex gap-4">
            <span>👤 Xin chào, Thien Tru</span>
            <a href="#" class="hover:text-eg-orange">Đăng xuất</a>
        </div>
    </div>

    <header class="bg-white shadow-sm flex justify-between items-center py-4 px-6 sticky top-0 z-50">
        <div class="text-3xl font-black tracking-tight">
            EYESGLASS<span class="text-eg-orange">.</span>
        </div>
        <nav class="hidden md:flex gap-8 font-semibold text-sm">
            <a href="#" class="hover:text-eg-orange">Gọng kính</a>
            <a href="#" class="hover:text-eg-orange">Tròng kính</a>
            <a href="#" class="hover:text-eg-orange">Sản phẩm</a>
            <a href="#" class="hover:text-eg-orange">Combo</a>
            <a href="#" class="hover:text-eg-orange">Khuyến mãi</a>
            <a href="#" class="hover:text-eg-orange">Try On</a>
            <a href="#" class="hover:text-eg-orange">Tư vấn</a>
        </nav>
        <div class="flex gap-4 text-xl">
            <span>🔍</span>
            <span>👤</span>
            <span>🛒</span>
        </div>
    </header>

    <div class="max-w-6xl mx-auto p-6 md:p-10">
        <h1 class="text-3xl font-bold mb-8 text-center uppercase tracking-wide">Nhập thông số đơn kính</h1>

        <form id="prescriptionForm" action="process_prescription.php" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">Thông số mắt</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <h3 class="font-semibold text-eg-orange">MẮT PHẢI (O.D)</h3>
                            <div><label class="text-xs text-gray-500">SPH (Cầu)</label><input type="text" name="right_sph" class="form-input"></div>
                            <div><label class="text-xs text-gray-500">CYL (Trụ)</label><input type="text" name="right_cyl" class="form-input"></div>
                            <div><label class="text-xs text-gray-500">AXIS (Trục)</label><input type="text" name="right_axis" class="form-input"></div>
                            <div><label class="text-xs text-gray-500">ADD (Cộng thêm)</label><input type="text" name="right_add" class="form-input"></div>
                            <div><label class="text-xs text-gray-500 font-bold">PD Phải</label><input type="text" name="right_pd" required class="form-input border-eg-orange"></div>
                        </div>

                        <div class="space-y-3">
                            <h3 class="font-semibold text-eg-orange">MẮT TRÁI (O.S)</h3>
                            <div><label class="text-xs text-gray-500">SPH (Cầu)</label><input type="text" name="left_sph" class="form-input"></div>
                            <div><label class="text-xs text-gray-500">CYL (Trụ)</label><input type="text" name="left_cyl" class="form-input"></div>
                            <div><label class="text-xs text-gray-500">AXIS (Trục)</label><input type="text" name="left_axis" class="form-input"></div>
                            <div><label class="text-xs text-gray-500">ADD (Cộng thêm)</label><input type="text" name="left_add" class="form-input"></div>
                            <div><label class="text-xs text-gray-500 font-bold">PD Trái</label><input type="text" name="left_pd" required class="form-input border-eg-orange"></div>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <label class="font-semibold block mb-2">Hoặc tải lên hình ảnh đơn thuốc (Nên dùng)</label>
                        <input type="file" name="prescription_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-eg-orange hover:file:bg-orange-100 cursor-pointer">
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit sticky top-24">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">Tùy chọn & Thanh toán</h2>
                    
                    <div class="mb-6">
                        <label class="font-semibold block mb-2 text-sm">Loại tròng kính</label>
                        <select id="lensType" name="lens_price" onchange="calculateTotal()" class="w-full p-3 rounded-lg border border-gray-300 focus:border-eg-orange focus:ring-1 focus:ring-eg-orange outline-none bg-white">
                            <option value="300000">Chống ánh sáng xanh (300.000đ)</option>
                            <option value="500000">Đổi màu (500.000đ)</option>
                            <option value="700000">Siêu mỏng 1.67 (700.000đ)</option>
                        </select>
                    </div>

                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between"><span>Giá tròng kính:</span> <span id="lensPriceDisplay" class="font-medium">300.000 đ</span></div>
                        <div class="flex justify-between"><span>Phí gia công lắp ráp:</span> <span class="font-medium">50.000 đ</span></div>
                        <div class="flex justify-between"><span>Giá gọng kính:</span> <span class="text-gray-400 italic">(Đã tính ở giỏ hàng)</span></div>
                    </div>

                    <div class="border-t pt-4 flex justify-between font-bold text-xl mb-6">
                        <span>Tạm tính:</span>
                        <span id="totalPriceDisplay" class="text-eg-orange">350.000 đ</span>
                    </div>

                    <input type="hidden" name="order_item_id" value="1"> <button type="submit" class="w-full py-4 bg-eg-orange text-white rounded-full font-bold uppercase hover:bg-eg-orange-hover transition-colors shadow-lg">
                        Lưu Đơn Kính & Tiếp Tục
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script src="assets/js/prescription.js"></script>
</body>
</html>