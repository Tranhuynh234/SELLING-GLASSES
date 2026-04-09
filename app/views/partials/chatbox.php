<!-- CHATBOX -->
    <div id="ai-chat-panel" 
        class="fixed bottom-24 right-8 w-[90vw] sm:w-[400px] max-w-[400px] h-[80vh] max-h-[600px] bg-white rounded-2xl shadow-2xl flex flex-col z-[100] transition-all duration-300 opacity-0 pointer-events-none translate-y-10 overflow-hidden border border-stone-200">
        <div class="bg-stone-900 text-white p-4 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-robot text-white"></i>
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
                    <i class="fa-solid fa-minus"></i>
                </button>
                <button id="close-chat" class="text-stone-400 hover:text-red-500 transition text-2xl leading-none">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        </div>

        <div id="chat-body" class="flex-1 bg-stone-50 p-4 overflow-y-auto chat-scroll flex flex-col gap-4 relative">
            <div class="flex gap-2 w-full">
                <div
                    class="w-8 h-8 bg-amber-600 rounded-full flex-shrink-0 flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-robot text-white"></i>
                </div>

                <div class="flex flex-col gap-1 max-w-[85%]">
                    <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm text-[14px] text-stone-800 border border-stone-100">
                        Ngày mới tốt lành 
                        <i class="fa-solid fa-hand text-amber-500"></i> 
                        Tôi là trợ lý Kính sành điệu của EYESGLASS.
                        Tôi có thể giúp gì cho baby nò?

                        <!-- QUICK OPTIONS-->
                         <div class="flex flex-wrap gap-2 mt-3">
                            <button onclick="handleQuickAction('Tư vấn chọn kính', 'find_glasses')"
                                 class="flex items-center gap-2 text-[13px] border border-amber-600 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full hover:bg-amber-600 hover:text-white transition shadow-sm">
                                <i class="fa-solid fa-glasses"></i>
                                Tư vấn chọn kính
                            </button>

                            <button onclick="handleQuickAction('Xem sản phẩm hot', 'hot_products')"
                                class="flex items-center gap-2 text-[13px] border border-amber-600 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full hover:bg-amber-600 hover:text-white transition shadow-sm">
                                <i class="fa-solid fa-fire"></i>
                                Xem sản phẩm hot
                            </button>

                            <button onclick="handleQuickAction('Liên hệ nhân viên', 'human')"
                                class="flex items-center gap-2 text-[13px] border border-amber-600 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full hover:bg-amber-600 hover:text-white transition shadow-sm">
                                <i class="fa-solid fa-headset"></i>
                                Liên hệ nhân viên
                            </button>

                        </div>
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
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <div id="ai-icon"
        class="fixed bottom-8 right-8 w-16 h-16 bg-amber-600 text-white rounded-full flex items-center justify-center shadow-xl cursor-grab z-[101] text-3xl glow-effect">
        <i class="fa-solid fa-robot text-white"></i>
    </div>