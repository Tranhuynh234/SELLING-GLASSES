<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Số Đơn Kính | EYESGLASS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/prescription.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased">

    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="mb-10">
            <div class="relative flex items-center justify-center mb-3">
                <button type="button" onclick="window.location.href='/SELLING-GLASSES/public/checkout';"
                        class="absolute left-0 group flex items-center justify-center w-11 h-11 rounded-full bg-white border border-slate-200 shadow-sm hover:border-amber-500 hover:text-amber-500 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-6 w-6 transform group-hover:-translate-x-1 transition-transform" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 uppercase">Thông Số Đơn Kính</h1>               
            </div>
            <p class="text-slate-500 font-medium text-center">Cung cấp thông số chính xác để chúng tôi chế tác cặp kính hoàn hảo cho bạn.</p>
        </div>
        
        <form id="prescriptionForm" action="/SELLING-GLASSES/public/index.php?url=prescription-store" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                            <h2 class="text-lg font-bold flex items-center gap-2 text-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Thông số khúc xạ
                            </h2>
                            <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-1 rounded">Đơn vị: Diopter</span>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center text-xs font-bold shadow-md shadow-amber-200">OD</div>
                                    <h3 class="font-bold text-slate-800 uppercase tracking-wider text-sm">Mắt Phải</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="input-group">
                                        <label>SPH (Cầu)</label>
                                        <input type="text" name="right_sph" placeholder="0.00" class="form-input">
                                    </div>
                                    <div class="input-group">
                                        <label>CYL (Trụ)</label>
                                        <input type="text" name="right_cyl" placeholder="0.00" class="form-input">
                                    </div>
                                    <div class="input-group">
                                        <label>AXIS (Trục)</label>
                                        <input type="text" name="right_axis" placeholder="0" class="form-input">
                                    </div>
                                    <div class="input-group">
                                        <label>ADD</label>
                                        <input type="text" name="right_add" placeholder="0.00" class="form-input">
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <label class="text-xs font-bold text-slate-700 mb-1 block uppercase">PD Phải (mm) <span class="text-red-500">*</span></label>
                                    <input type="text" name="right_pd" required placeholder="VD: 32" class="form-input focus:ring-amber-500 border-amber-200 bg-amber-50/30">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold shadow-md shadow-slate-200">OS</div>
                                    <h3 class="font-bold text-slate-800 uppercase tracking-wider text-sm">Mắt Trái</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="input-group">
                                        <label>SPH (Cầu)</label>
                                        <input type="text" name="left_sph" placeholder="0.00" class="form-input">
                                    </div>
                                    <div class="input-group">
                                        <label>CYL (Trụ)</label>
                                        <input type="text" name="left_cyl" placeholder="0.00" class="form-input">
                                    </div>
                                    <div class="input-group">
                                        <label>AXIS (Trục)</label>
                                        <input type="text" name="left_axis" placeholder="0" class="form-input">
                                    </div>
                                    <div class="input-group">
                                        <label>ADD</label>
                                        <input type="text" name="left_add" placeholder="0.00" class="form-input">
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <label class="text-xs font-bold text-slate-700 mb-1 block uppercase">PD Trái (mm) <span class="text-red-500">*</span></label>
                                    <input type="text" name="left_pd" required placeholder="VD: 32" class="form-input focus:ring-amber-500 border-amber-200 bg-amber-50/30">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                            Hoặc tải lên hình chụp đơn thuốc
                        </h3>
                        <div class="upload-zone border-2 border-dashed border-slate-200 rounded-xl p-8 text-center transition-all hover:border-amber-400 hover:bg-amber-50/50 group relative">
                            <input type="file" name="prescription_image" id="file-upload" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="pointer-events-none">
                                <div class="text-slate-400 group-hover:text-amber-600 mb-2 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Nhấn hoặc kéo thả để tải ảnh đơn thuốc</p>
                                <p class="text-xs text-slate-400 mt-1" id="file-name-display">Hỗ trợ JPG, PNG (Tối đa 5MB)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 sticky top-10">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                        <div class="p-6 bg-slate-900 text-white">
                            <h2 class="text-xl font-bold uppercase tracking-tight">Chi phí tròng kính</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wide">Loại tròng kính</label>
                                <select id="lensType" name="lens_price" class="lens-select">
                                    <option value="0">Chưa chọn tròng (0đ)</option>
                                    <option value="250000">Tròng cơ bản (250.000đ)</option>
                                    <option value="300000">Chống ánh sáng xanh (300.000 đ)</option>
                                    <option value="500000">Tròng Đổi màu (500.000 đ)</option>
                                    <option value="700000">Tròng Siêu mỏng 1.67 (700.000 đ)</option>
                                </select>
                            </div>

                            <div class="space-y-3 pt-4 border-t border-slate-100">
                                <div class="flex justify-between text-slate-500 font-medium text-sm">
                                    <span>Giá tròng:</span>
                                    <span id="lensPriceDisplay" class="text-slate-900">300.000 đ</span>
                                </div>
                                <div class="flex justify-between text-slate-500 font-medium text-sm">
                                    <span>Phí gia công:</span>
                                    <span class="text-slate-900">50.000 đ</span>
                                </div>
                                <div class="pt-4 flex justify-between items-end">
                                    <span class="text-slate-900 font-extrabold text-base">TỔNG CỘNG:</span>
                                    <span id="totalPriceDisplay" class="text-2xl font-black text-amber-600">350.000 đ</span>
                                </div>
                            </div>

                            <input type="hidden" name="orderItemId" value="<?php echo $_GET['orderItemId'] ?? 1; ?>">
                            <input type="hidden" name="total_amount_input" id="total_amount_input" value="0">
                            
                            <button type="submit" class="btn-submit-pro w-full flex items-center justify-center gap-3">
                                <span>Xác nhận & Lưu đơn</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                            
                            <p class="text-[10px] text-slate-400 text-center uppercase font-bold tracking-widest">Gia công chính xác theo yêu cầu</p>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const lensSelect = document.getElementById("lensType");
            const lensPriceDisplay = document.getElementById("lensPriceDisplay");
            const totalPriceDisplay = document.getElementById("totalPriceDisplay");
            const fileUpload = document.getElementById("file-upload");
            const fileNameDisplay = document.getElementById("file-name-display");

            function formatVND(amount) {
                return new Intl.NumberFormat('vi-VN').format(amount) + " đ";
            }

            function updatePrice() {
                const lensPrice = parseInt(lensSelect.value) || 0;
                const processingFee = 50000;
                const total = lensPrice + processingFee;

                lensPriceDisplay.innerText = formatVND(lensPrice);
                totalPriceDisplay.innerText = formatVND(total);
            }

            // Cập nhật tên file khi chọn ảnh
            fileUpload.addEventListener("change", function() {
                if (this.files && this.files[0]) {
                    fileNameDisplay.innerHTML = `<span class="text-amber-600 font-bold">Đã chọn: ${this.files[0].name}</span>`;
                }
            });

            lensSelect.addEventListener("change", updatePrice);
            
            // Khởi tạo giá mặc định khi load
            updatePrice();
        });

        (function() {
            const form = document.getElementById('prescriptionForm');
            if (!form) return;
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = form.querySelector('.btn-submit-pro');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-70');
                }

                const fd = new FormData(form);

                try {
                    const res = await fetch('/SELLING-GLASSES/public/index.php?url=prescription-store', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });

                    const data = await res.json();

                    if (data && data.success) {
                        alert('Đã lưu thông số vào hồ sơ.');
                        window.location.href = '/SELLING-GLASSES/public/index.php?url=checkout&status=saved';
                    } else {
                        alert('Lưu không thành công: ' + (data.message || 'Lỗi không xác định'));
                    }
                } catch (err) {
                    console.error('Lỗi khi lưu đơn kính:', err);
                    alert('Lỗi kết nối hoặc phản hồi không hợp lệ. Vui lòng thử lại.');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-70');
                    }
                }
            });
        })();
    </script>
</body>
</html>