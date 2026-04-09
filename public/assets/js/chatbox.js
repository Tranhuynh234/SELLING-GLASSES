const aiIcon = document.getElementById('ai-icon');
const chatPanel = document.getElementById('ai-chat-panel');
const closeChatBtn = document.getElementById('close-chat');
const minimizeChatBtn = document.getElementById('minimize-chat');
const chatBody = document.getElementById('chat-body');
const chatInput = document.getElementById('chat-input');
const quickActionsArea = document.getElementById('quick-actions');

let isChatOpen = false;
let isDragging = false;
let startX = null, startY = null;
let offsetX = 0, offsetY = 0;

// ICON SVG 
const maleIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 3h5v5M21 3l-7 7"/><circle cx="10" cy="14" r="5"/></svg>`;
const femaleIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="10" r="5"/><path d="M12 15v6M9 18h6"/></svg>`;


// 1. XỬ LÝ KÉO THẢ (DRAG & DROP)
aiIcon.addEventListener('mousedown', (e) => {
    isDragging = false;
    startX = e.clientX;
    startY = e.clientY;
    const rect = aiIcon.getBoundingClientRect();
    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;
    aiIcon.style.transition = 'none';
});

document.addEventListener('mousemove', (e) => {
    if (startX === null || startY === null) return;
    if (Math.abs(e.clientX - startX) > 5 || Math.abs(e.clientY - startY) > 5) {
        isDragging = true;
    }
    if (!isDragging) return;
    e.preventDefault();
    aiIcon.style.left = `${e.clientX - offsetX}px`;
    aiIcon.style.top = `${e.clientY - offsetY}px`;
    aiIcon.style.right = 'auto';
    aiIcon.style.bottom = 'auto';
});

document.addEventListener('mouseup', () => {
    if (!isDragging && startX !== null) toggleChatbox();
    startX = null;
    startY = null;
    isDragging = false;
    aiIcon.style.transition = '';
});

// TOUCH MOBILE 
aiIcon.addEventListener('touchstart', (e) => {
    const touch = e.touches[0];
    startX = touch.clientX;
    startY = touch.clientY;
    const rect = aiIcon.getBoundingClientRect();
    offsetX = touch.clientX - rect.left;
    offsetY = touch.clientY - rect.top;
}, { passive: false });

document.addEventListener('touchmove', (e) => {
    if (startX === null || startY === null) return;
    const touch = e.touches[0];
    if (Math.abs(touch.clientX - startX) > 5 || Math.abs(touch.clientY - startY) > 5) {
        isDragging = true;
    }
    if (!isDragging) return;
    e.preventDefault();
    aiIcon.style.left = `${touch.clientX - offsetX}px`;
    aiIcon.style.top = `${touch.clientY - offsetY}px`;
}, { passive: false });

document.addEventListener('touchend', () => {
    if (!isDragging && startX !== null) toggleChatbox();
    startX = null;
    startY = null;
    isDragging = false;
});


// 2. GIAO DIỆN CHATBOX (UI)
function toggleChatbox() {
    isChatOpen = !isChatOpen;
    chatPanel.classList.toggle('opacity-0');
    chatPanel.classList.toggle('pointer-events-none');
    chatPanel.classList.toggle('translate-y-10');
    
    if (isChatOpen) {
        scrollToBottom();
        // Tự động focus vào ô nhập liệu khi mở chat
        setTimeout(() => chatInput.focus(), 300);
    }
}

// Bấm phím ESC để đóng chatbox cho chuyên nghiệp
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isChatOpen) {
        toggleChatbox();
    }
});

function openChatboxAndConsult() {
    if (!isChatOpen) toggleChatbox();
    // Tự động gọi tính năng tư vấn
    handleQuickAction('Tư vấn chọn kính', 'find_glasses');
}

closeChatBtn.addEventListener('click', toggleChatbox);
minimizeChatBtn.addEventListener('click', toggleChatbox);

function scrollToBottom() {
    chatBody.scrollTop = chatBody.scrollHeight;
}


// 3. XỬ LÝ TIN NHẮN (MESSAGING SYSTEM)
function appendUserMessage(text) {
    const msg = `
    <div class="flex justify-end mt-2">
        <div class="bg-amber-600 text-white px-4 py-2 rounded-2xl rounded-tr-sm shadow-sm max-w-[85%] text-[14px]">${text}</div>
    </div>`;
    chatBody.insertAdjacentHTML('beforeend', msg);
    scrollToBottom();
}

function showTypingIndicator() {
    const html = `
    <div id="typing" class="mt-2 flex gap-2 w-full">
        <div class="w-8 h-8 bg-amber-600 rounded-full flex-shrink-0 flex items-center justify-center shadow-sm">
            <i class="fa-solid fa-robot text-white text-sm"></i>
        </div>
        <div class="bg-white px-4 py-3 rounded-2xl rounded-tl-sm border border-stone-100 shadow-sm flex items-center gap-1">
            <div class="w-1.5 h-1.5 bg-stone-400 rounded-full animate-bounce"></div>
            <div class="w-1.5 h-1.5 bg-stone-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
            <div class="w-1.5 h-1.5 bg-stone-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        </div>
    </div>`;
    chatBody.insertAdjacentHTML('beforeend', html);
    scrollToBottom();
}

function removeTypingIndicator() {
    document.getElementById('typing')?.remove();
}

function appendAIMessage(text, options = []) {
    let optionsHtml = '';
    if (options.length > 0) {
        optionsHtml = `<div class="flex gap-2 mt-3 flex-wrap">`;
        options.forEach(opt => {
            optionsHtml += `
            <button onclick="handleQuickAction(this.innerText.trim(), '${opt.action}')"
            class="flex items-center gap-2 text-[13px] border border-amber-600 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full hover:bg-amber-600 hover:text-white transition shadow-sm">
                ${opt.label}
            </button>`;
        });
        optionsHtml += `</div>`;
    }

    const msg = `
    <div class="mt-2 flex gap-2 w-full">
        <div class="w-8 h-8 bg-amber-600 rounded-full flex-shrink-0 flex items-center justify-center shadow-sm">
            <i class="fa-solid fa-robot text-white text-sm"></i>
        </div>
        <div class="bg-white p-3 rounded-2xl rounded-tl-sm border border-stone-100 shadow-sm text-[14px] text-stone-800 max-w-[85%]">
            ${text}
            ${optionsHtml}
        </div>
    </div>`;
    chatBody.insertAdjacentHTML('beforeend', msg);
    scrollToBottom();
}


// 4. TIN NHẮN TỰ ĐỘNG (LOGIC ENGINE)
function handleQuickAction(label, actionCode) {
    if (!actionCode) {
        actionCode = label;
        if (actionCode === 'find_glasses') label = 'Tư vấn chọn kính';
        else if (actionCode === 'hot_products') label = 'Xem sản phẩm hot';
        else if (actionCode === 'human') label = 'Gặp tư vấn viên';
    }

    const cleanLabel = label.replace(/<[^>]*>?/gm, ''); 
    appendUserMessage(cleanLabel);
    
    showTypingIndicator();

    setTimeout(() => {
        removeTypingIndicator();

        if (actionCode === 'find_glasses') {
            appendAIMessage("Dạ vâng! Để EYESGLASS gợi ý chuẩn xác nhất, bạn chọn giới tính giúp mình nhé:", [
                { label: `${maleIcon} Mình là Nam`, action: 'male' },
                { label: `${femaleIcon} Mình là Nữ`, action: 'female' }
            ]);
        }

        else if (actionCode === 'male' || actionCode === 'female') {
            const gender = actionCode === 'male' ? 'bạn nam' : 'bạn nữ';
            appendAIMessage(`Tuyệt vời! Đối với ${gender}, bạn đang tìm kính để sử dụng cho mục đích nào chủ yếu?`, [
                { label: '<i class="fa-solid fa-laptop"></i> Ngồi máy tính/Văn phòng', action: 'office' },
                { label: '<i class="fa-solid fa-sun"></i> Đi chơi/Thời trang', action: 'fashion' }
            ]);
        }

        else if (actionCode === 'office') {
            appendAIMessage("Với nhu cầu làm việc máy tính, bạn nên ưu tiên <b>Tròng chống ánh sáng xanh</b>. <br><br>Gợi ý số 1 của EYESGLASS là <b>Combo Văn Phòng (899k)</b> giúp bảo vệ mắt tối đa. Bạn muốn xem chi tiết Combo này chứ?", [
                { label: 'Xem Combo Văn Phòng', action: 'view_combo_office' },
                { label: 'Xem các mẫu khác', action: 'hot_products' }
            ]);
        }
        else if (actionCode === 'fashion') {
            appendAIMessage("Đi chơi thì gọng kính phải thật có gu! EYESGLASS đề xuất các dòng <b>Kính Đổi Màu</b> hoặc <b>Gọng Titan Siêu Mỏng</b> để bạn luôn nổi bật.<br><br>Bạn muốn thử kính ảo AI các mẫu này ngay bây giờ không?", [
                { label: 'Thử kính ảo ngay', action: 'try_on' }
            ]);
        }

        else if (actionCode === 'hot_products') {
            appendAIMessage("EYESGLASS đang có rất nhiều mẫu Best Seller. Mình sẽ tự động chuyển bạn đến danh mục sản phẩm hot nhé!");
            setTimeout(() => document.getElementById('danh-muc')?.scrollIntoView({behavior: 'smooth'}), 1500);
        }
        else if (actionCode === 'try_on') {
            appendAIMessage("Chuẩn bị quét khuôn mặt 3D! Vui lòng cấp quyền camera khi được yêu cầu nhé.");
            setTimeout(() => document.getElementById('try-on-section')?.scrollIntoView({behavior: 'smooth'}), 1500);
        }
        else if (actionCode === 'view_combo_office') {
            appendAIMessage("Đang cuộn đến Combo Văn Phòng cho bạn...");
            setTimeout(() => document.getElementById('combo-section')?.scrollIntoView({behavior: 'smooth'}), 1500);
        }
        else if (actionCode === 'human') {
            appendAIMessage("Đang kết nối với nhân viên CSKH... (Thời gian chờ dự kiến: 1 phút)");
        }
        else {
            appendAIMessage("Dạ EYESGLASS đã ghi nhận thông tin. Bạn cần mình hỗ trợ gì thêm không ạ?");
        }

    }, 1000);
}


// 5. XỬ LÝ NHẬP VĂN BẢN (TEXT INPUT)
function handleEnter(e) {
    if (e.key === 'Enter') sendManualMessage();
}

function sendManualMessage() {
    const text = chatInput.value.trim();
    if (!text) return;

    appendUserMessage(text);
    chatInput.value = '';
    showTypingIndicator();

    setTimeout(() => {
        removeTypingIndicator();
        // Xử lý các từ khóa cơ bản do khách nhập
        const lowerText = text.toLowerCase();
        
        if (lowerText.includes('giá') || lowerText.includes('bao nhiêu')) {
            appendAIMessage(`Dạ các sản phẩm kính bên mình có giá dao động từ <b>499.000đ</b> đến <b>1.290.000đ</b> tùy dòng. Bạn muốn xem loại nào ạ?`, [
                { label: 'Xem sản phẩm bán chạy', action: 'hot_products' }
            ]);
        } else if (lowerText.includes('bảo hành') || lowerText.includes('đổi trả')) {
            appendAIMessage(`EYESGLASS cam kết bảo hành 12 tháng và hỗ trợ đổi trả trong vòng 7 ngày ạ. Mình kết nối bạn với nhân viên nhé?`, [
                { label: 'Gặp nhân viên', action: 'human' }
            ]);
        } else {
            appendAIMessage(`Mình đã nhận được câu hỏi: "<i>${text}</i>" <br>Trợ lý AI đang học hỏi thêm, bạn có muốn kết nối với Tư vấn viên người thật không ạ?`, [
                { label: '<i class="fa-solid fa-phone-volume text-amber-500"></i> Gặp tư vấn viên', action: 'human' },
                { label: '<i class="fa-solid fa-glasses text-amber-500"></i> Tư vấn chọn kính', action: 'find_glasses' }
            ]);
        }
    }, 1200);
}