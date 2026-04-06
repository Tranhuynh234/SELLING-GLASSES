<?php
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Xác Thực - EYESGLASS. Exclusive</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    // Cấu hình font cho Tailwind
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ["Inter", "sans-serif"],
                    serif: ["Playfair Display", "serif"],
                },
            },
        },
    };
    </script>
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/auth.css" />
</head>

<body class="bg-white text-stone-800 selection:bg-amber-200 selection:text-amber-900 w-screen h-screen">
    <div class="relative w-full h-full overflow-hidden">
        <div id="image-panel"
            class="hidden lg:flex absolute top-0 left-0 w-1/2 h-full bg-stone-950 z-20 panel-transition shadow-2xl overflow-hidden group">
            <div class="absolute inset-0 transform group-hover:scale-105 transition-transform duration-[10s] ease-out">
                <img src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?q=80&w=1200&auto=format&fit=crop"
                    alt="EYESGLASS Exclusive"
                    class="w-full h-full object-cover object-center opacity-80 mix-blend-luminosity" />
            </div>
            <div class="absolute inset-0 bg-gradient-to-tr from-stone-950 via-stone-900/60 to-transparent"></div>
            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
            </div>

            <div id="img-text-login"
                class="relative z-10 w-full h-full p-16 flex flex-col justify-between transition-opacity duration-500 opacity-100">
                <div class="flex items-center gap-2">
                    <span class="font-serif italic text-4xl text-white">EYESGLASS<span
                            class="text-amber-500 text-5xl leading-none">.</span></span>
                </div>
                <div class="max-w-md pb-10">
                    <h2 class="font-serif text-5xl leading-[1.2] text-white mb-6">
                        Khám phá góc nhìn <br />
                        <span class="italic text-amber-400">đậm chất riêng.</span>
                    </h2>
                    <p class="text-stone-400 font-light leading-relaxed tracking-wide text-sm">
                        Truy cập không gian mua sắm cá nhân hóa. Nơi công nghệ AI thấu
                        hiểu khuôn mặt bạn, và nghệ thuật chế tác kính mắt được tôn vinh.
                    </p>
                </div>
            </div>

            <div id="img-text-register"
                class="absolute inset-0 z-10 w-full h-full p-16 flex flex-col justify-between transition-opacity duration-500 opacity-0 pointer-events-none">
                <div class="flex items-center justify-end gap-2">
                    <span class="font-serif italic text-4xl text-white">EYESGLASS<span
                            class="text-amber-500 text-5xl leading-none">.</span></span>
                </div>
                <div class="max-w-md pb-10 ml-auto text-right">
                    <h2 class="font-serif text-5xl leading-[1.2] text-white mb-6">
                        Gia nhập <br />
                        <span class="italic text-amber-400">cộng đồng EYESGLASS.</span>
                    </h2>
                    <p class="text-stone-400 font-light leading-relaxed tracking-wide text-sm ml-auto pl-10">
                        Đăng ký ngay để nhận đặc quyền thành viên VIP, ưu đãi lên đến 30%
                        cho đơn hàng đầu tiên và lưu trữ hồ sơ đo mắt trọn đời.
                    </p>
                </div>
            </div>
        </div>

        <div id="form-panel"
            class="absolute top-0 right-0 w-full lg:w-1/2 h-full bg-white z-10 panel-transition flex flex-col overflow-y-auto">
            <div class="w-full flex justify-between items-center p-6 lg:p-8 absolute top-0 left-0 right-0 z-10">
                <div class="lg:hidden font-serif italic text-3xl text-stone-900">
                    EYESGLASS<span class="text-amber-600">.</span>
                </div>
                <div class="hidden lg:block"></div>
                <a href="/SELLING-GLASSES/public/home"
                    class="group text-[11px] font-bold uppercase tracking-[0.2em] text-stone-400 hover:text-stone-900 transition-colors flex items-center gap-2">
                    <span
                        class="w-6 h-[1px] bg-stone-300 group-hover:bg-stone-900 group-hover:w-8 transition-all"></span>
                    Trang chủ
                </a>
            </div>

            <div class="flex-1 flex items-center justify-center p-6 sm:p-12 lg:p-20 mt-16 lg:mt-0">
                <div class="w-full max-w-[400px] relative">
                    <div id="login-view" class="auth-view active">
                        <div class="mb-10 text-center">
                            <h1 class="font-serif text-4xl text-stone-900 mb-3">
                                Đăng Nhập
                            </h1>
                            <p class="text-stone-500 text-sm">
                                Chào mừng bạn trở lại với EYESGLASS.
                            </p>
                        </div>

                        <form class="space-y-5">
                            <div class="input-group">
                                <input type="text" id="login-email" class="floating-input" placeholder=" " required />
                                <label for="login-email" class="floating-label">Email hoặc Số điện thoại</label>
                            </div>

                            <div class="input-group">
                                <input type="password" id="login-password" class="floating-input pr-12" placeholder=" "
                                    required />
                                <label for="login-password" class="floating-label">Mật khẩu</label>
                                <div class="toggle-pwd" onclick="togglePassword('login-password')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-2 pb-4">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center justify-center w-5 h-5">
                                        <input type="checkbox"
                                            class="peer appearance-none w-5 h-5 border-2 border-stone-300 rounded-md bg-transparent checked:bg-stone-900 checked:border-stone-900 transition-all cursor-pointer" />
                                        <svg class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transform scale-50 peer-checked:scale-100 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium text-stone-500 group-hover:text-stone-900 transition-colors">Ghi
                                        nhớ tôi</span>
                                </label>
                                <button type="button" onclick="switchView('forgot-view')"
                                    class="text-sm font-semibold text-stone-900 hover:text-amber-600 transition-colors">
                                    Quên mật khẩu?
                                </button>
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-stone-900 text-white rounded-full text-sm font-bold tracking-[0.15em] uppercase hover:bg-stone-800 hover:shadow-[0_10px_20px_-10px_rgba(28,25,23,0.5)] transform hover:-translate-y-0.5 transition-all duration-300">
                                Đăng Nhập
                            </button>
                        </form>

                        <div class="my-8 flex items-center gap-4">
                            <div class="flex-grow border-t border-stone-200"></div>
                            <span class="text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">Hoặc</span>
                            <div class="flex-grow border-t border-stone-200"></div>
                        </div>

                        <div class="flex gap-4">
                            <button type="button"
                                class="flex-1 flex items-center justify-center gap-3 py-3.5 border border-stone-200 rounded-full hover:border-stone-900 hover:bg-stone-50 transition-all font-semibold text-sm text-stone-700">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                        fill="#4285F4" />
                                    <path
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                        fill="#34A853" />
                                    <path
                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                        fill="#FBBC05" />
                                    <path
                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                        fill="#EA4335" />
                                </svg>
                                Google
                            </button>
                            <button type="button"
                                class="flex-1 flex items-center justify-center gap-3 py-3.5 border border-stone-200 rounded-full hover:border-stone-900 hover:bg-stone-50 transition-all font-semibold text-sm text-stone-700">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Facebook
                            </button>
                        </div>

                        <p class="mt-10 text-center text-stone-500 text-sm">
                            Chưa có tài khoản?
                            <button type="button" onclick="switchView('register-view')"
                                class="font-bold text-stone-900 border-b border-stone-900 hover:text-amber-600 hover:border-amber-600 transition-colors pb-0.5 ml-1">
                                Đăng ký ngay
                            </button>
                        </p>
                    </div>

                    <div id="register-view" class="auth-view">
                        <div class="mb-8 text-center">
                            <h1 class="font-serif text-4xl text-stone-900 mb-3">
                                Tạo Tài Khoản
                            </h1>
                            <p class="text-stone-500 text-sm">
                                Trở thành khách hàng VIP của EYESGLASS.
                            </p>
                        </div>

                        <form class="space-y-4" id="register-form">
                            <div class="input-group">
                                <input type="text" id="reg-name" class="floating-input" placeholder=" " required />
                                <label for="reg-name" class="floating-label">Họ và Tên</label>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="input-group">
                                    <input type="tel" id="reg-phone" class="floating-input" placeholder=" " required />
                                    <label for="reg-phone" class="floating-label">Số điện thoại</label>
                                </div>
                                <div class="input-group">
                                    <input type="email" id="reg-email" class="floating-input" placeholder=" "
                                        required />
                                    <label for="reg-email" class="floating-label">Email</label>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="password" id="reg-password" class="floating-input pr-12" placeholder=" "
                                    required minlength="8" />
                                <label for="reg-password" class="floating-label">Mật khẩu</label>
                                <div class="toggle-pwd" onclick="togglePassword('reg-password')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="password" id="reg-confirm" class="floating-input pr-12" placeholder=" "
                                    required minlength="8" />
                                <label for="reg-confirm" class="floating-label">Xác nhận mật khẩu</label>
                            </div>

                            <div class="pt-1 pb-3">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <div class="relative flex items-center justify-center w-5 h-5 mt-0.5">
                                        <input type="checkbox" required
                                            class="peer appearance-none w-5 h-5 border-2 border-stone-300 rounded-md bg-transparent checked:bg-stone-900 checked:border-stone-900 transition-all cursor-pointer" />
                                        <svg class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transform scale-50 peer-checked:scale-100 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-stone-500 leading-relaxed">Đồng ý với
                                        <a href="#" class="font-semibold text-stone-900 border-b border-stone-900">Điều
                                            khoản</a>
                                        &
                                        <a href="#" class="font-semibold text-stone-900 border-b border-stone-900">Bảo
                                            mật</a>.</span>
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-stone-900 text-white rounded-full text-sm font-bold tracking-[0.15em] uppercase hover:bg-stone-800 hover:shadow-[0_10px_20px_-10px_rgba(28,25,23,0.5)] transform hover:-translate-y-0.5 transition-all duration-300">
                                Đăng Ký
                            </button>
                        </form>

                        <p class="mt-8 text-center text-stone-500 text-sm">
                            Đã có tài khoản?
                            <button type="button" onclick="switchView('login-view')"
                                class="font-bold text-stone-900 border-b border-stone-900 hover:text-amber-600 hover:border-amber-600 transition-colors pb-0.5 ml-1">
                                Đăng nhập
                            </button>
                        </p>
                    </div>
                    <div id="forgot-view" class="auth-view">
                        <div class="text-center mb-10">
                            <h1 class="font-serif text-4xl text-stone-900 mb-3">
                                Quên Mật Khẩu
                            </h1>
                            <p class="text-stone-500 text-sm leading-relaxed max-w-xs mx-auto">
                                Nhập email liên kết với tài khoản. Chúng tôi sẽ gửi hướng dẫn
                                khôi phục.
                            </p>
                        </div>

                        <form class="space-y-6" onsubmit="
                  event.preventDefault();
                  switchView('reset-view');
                ">
                            <div class="input-group">
                                <input type="email" id="forgot-email" class="floating-input" placeholder=" " required />
                                <label for="forgot-email" class="floating-label">Email của bạn</label>
                            </div>
                            <button type="submit"
                                class="w-full py-4 bg-stone-900 text-white rounded-full text-sm font-bold tracking-[0.15em] uppercase hover:bg-stone-800 hover:shadow-[0_10px_20px_-10px_rgba(28,25,23,0.5)] transform hover:-translate-y-0.5 transition-all duration-300">
                                Gửi Yêu Cầu
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <button type="button" onclick="switchView('login-view')"
                                class="text-xs font-bold uppercase tracking-widest text-stone-400 hover:text-stone-900 transition-colors pb-1 border-b border-transparent hover:border-stone-900 inline-flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Hủy bỏ
                            </button>
                        </div>
                    </div>

                    <div id="reset-view" class="auth-view">
                        <div class="text-center mb-10">
                            <h1 class="font-serif text-4xl text-stone-900 mb-3">
                                Mật Khẩu Mới
                            </h1>
                            <p class="text-stone-500 text-sm">
                                Vui lòng thiết lập mật khẩu mới an toàn.
                            </p>
                        </div>

                        <form class="space-y-5" onsubmit="
                  event.preventDefault();
                  alert('Thành công! Vui lòng đăng nhập lại.');
                  switchView('login-view');
                ">
                            <div class="input-group">
                                <input type="password" id="new-password" class="floating-input pr-12" placeholder=" "
                                    required minlength="8" />
                                <label for="new-password" class="floating-label">Mật khẩu mới</label>
                                <div class="toggle-pwd" onclick="togglePassword('new-password')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="input-group">
                                <input type="password" id="confirm-new-password" class="floating-input pr-12"
                                    placeholder=" " required minlength="8" />
                                <label for="confirm-new-password" class="floating-label">Xác nhận mật khẩu</label>
                            </div>

                            <button type="submit"
                                class="w-full py-4 mt-2 bg-amber-700 text-white rounded-full text-sm font-bold tracking-[0.15em] uppercase hover:bg-amber-800 hover:shadow-[0_10px_20px_-10px_rgba(217,119,6,0.5)] transform hover:-translate-y-0.5 transition-all duration-300">
                                Cập nhật & Đăng nhập
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/SELLING-GLASSES/public/assets/js/auth.js"></script>
</body>

</html>