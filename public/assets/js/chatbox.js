const aiIcon = document.getElementById('ai-icon');
const chatPanel = document.getElementById('ai-chat-panel');
const closeChatBtn = document.getElementById('close-chat');
const minimizeChatBtn = document.getElementById('minimize-chat');
const chatBody = document.getElementById('chat-body');
const chatInput = document.getElementById('chat-input');
const quickActionsArea = document.getElementById('quick-actions');

let isChatOpen = false;
let isDragging = false;
let startX, startY;
let offsetX, offsetY;

// --- Logic Kéo Thả (Mouse) ---
aiIcon.addEventListener('mousedown', (e) => {
    isDragging = false;
    startX = e.clientX;
    startY = e.clientY;
    aiIcon.classList.remove('cursor-grab');
    aiIcon.classList.add('cursor-grabbing');
    const rect = aiIcon.getBoundingClientRect();
    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;
    aiIcon.style.transition = 'none'; 
});

document.addEventListener('mousemove', (e) => {
    if (startX && startY) {
        if (Math.abs(e.clientX - startX) > 5 || Math.abs(e.clientY - startY) > 5) {
            isDragging = true;
        }
    }
    if (!isDragging) return;
    e.preventDefault();
    aiIcon.style.bottom = 'auto';
    aiIcon.style.right = 'auto';
    aiIcon.style.left = `${e.clientX - offsetX}px`;
    aiIcon.style.top = `${e.clientY - offsetY}px`;
    aiIcon.classList.remove('glow-effect');
});

document.addEventListener('mouseup', (e) => {
    aiIcon.classList.remove('cursor-grabbing');
    aiIcon.classList.add('cursor-grab');
    aiIcon.style.transition = 'background-color 0.3s, transform 0.3s';
    aiIcon.classList.add('glow-effect');
    if (!isDragging && startX !== undefined) toggleChatbox();
    startX = undefined; startY = undefined; isDragging = false;
});

// --- Logic Mobile Touch ---
aiIcon.addEventListener('touchstart', (e) => {
    const touch = e.touches[0];
    startX = touch.clientX; startY = touch.clientY;
    const rect = aiIcon.getBoundingClientRect();
    offsetX = touch.clientX - rect.left;
    offsetY = touch.clientY - rect.top;
}, {passive: false});

document.addEventListener('touchmove', (e) => {
    if (!startX || !startY) return;
    const touch = e.touches[0];
    if (Math.abs(touch.clientX - startX) > 5 || Math.abs(touch.clientY - startY) > 5) isDragging = true;
    if (!isDragging) return;
    e.preventDefault();
    aiIcon.style.bottom = 'auto';
    aiIcon.style.right = 'auto';
    aiIcon.style.left = `${touch.clientX - offsetX}px`;
    aiIcon.style.top = `${touch.clientY - offsetY}px`;
}, {passive: false});

document.addEventListener('touchend', (e) => {
    if (!isDragging && startX !== undefined) toggleChatbox();
    startX = undefined; startY = undefined; isDragging = false;
});

// --- Logic Chat UI ---
function toggleChatbox() {
    isChatOpen = !isChatOpen;
    if (isChatOpen) {
        chatPanel.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-10');
        scrollToBottom();
    } else {
        chatPanel.classList.add('opacity-0', 'pointer-events-none', 'translate-y-10');
    }
}

closeChatBtn.addEventListener('click', toggleChatbox);
minimizeChatBtn.addEventListener('click', toggleChatbox);

function scrollToBottom() { chatBody.scrollTop = chatBody.scrollHeight; }

function getTime() {
    const now = new Date();
    return `${now.getHours()}:${now.getMinutes() < 10 ? '0' : ''}${now.getMinutes()}`;
}

function appendUserMessage(text) {
    const msgHtml = `
        <div class="flex gap-2 w-full justify-end mt-2 mb-2">
            <div class="flex flex-col gap-1 max-w-[85%] items-end">
                <div class="bg-amber-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-[14px]">
                    ${text}
                </div>
                <span class="text-[10px] text-stone-400 mr-1">${getTime()}</span>
            </div>
        </div>`;
    const wrapper = document.createElement('div');
    wrapper.innerHTML = msgHtml;
    chatBody.insertBefore(wrapper.firstElementChild, document.getElementById('chat-anchor'));
    scrollToBottom();
}

function showTypingIndicator() {
    const typingHtml = `
        <div id="typing-indicator" class="flex gap-2 w-full mb-2">
            <div class="w-8 h-8 bg-amber-600 rounded-full flex-shrink-0 flex items-center justify-center text-sm shadow-sm">🤖</div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-stone-100 flex gap-1 items-center h-10">
                <div class="w-2 h-2 bg-stone-400 rounded-full typing-dot"></div>
                <div class="w-2 h-2 bg-stone-400 rounded-full typing-dot"></div>
                <div class="w-2 h-2 bg-stone-400 rounded-full typing-dot"></div>
            </div>
        </div>`;
    const wrapper = document.createElement('div');
    wrapper.innerHTML = typingHtml;
    chatBody.insertBefore(wrapper.firstElementChild, document.getElementById('chat-anchor'));
    scrollToBottom();
}

function removeTypingIndicator() {
    const indicator = document.getElementById('typing-indicator');
    if (indicator) indicator.remove();
}

function appendAIMessage(textHtml, options = []) {
    let optionsHtml = '';
    if (options.length > 0) {
        optionsHtml = `<div class="flex flex-wrap gap-2 mt-3">`;
        options.forEach(opt => {
            optionsHtml += `<button onclick="handleQuickAction('${opt.label}', '${opt.action}')" class="text-[13px] border border-amber-600 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full hover:bg-amber-600 hover:text-white transition shadow-sm">${opt.label}</button>`;
        });
        optionsHtml += `</div>`;
    }

    const msgHtml = `
        <div class="flex gap-2 w-full mb-2">
            <div class="w-8 h-8 bg-amber-600 rounded-full flex-shrink-0 flex items-center justify-center text-sm shadow-sm">🤖</div>
            <div class="flex flex-col gap-1 max-w-[85%]">
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm text-[14px] text-stone-800 border border-stone-100">
                    ${textHtml}
                    ${optionsHtml}
                </div>
                <span class="text-[10px] text-stone-400 ml-1">${getTime()}</span>
            </div>
        </div>`;
    const wrapper = document.createElement('div');
    wrapper.innerHTML = msgHtml;
    chatBody.insertBefore(wrapper.firstElementChild, document.getElementById('chat-anchor'));
    scrollToBottom();
}

function handleQuickAction(text, actionType) {
    if (quickActionsArea) quickActionsArea.style.display = 'none';
    appendUserMessage(text);
    showTypingIndicator();
    setTimeout(() => {
        removeTypingIndicator();
        if (actionType === 'find_glasses') {
            appendAIMessage("Dạ, để gợi ý chính xác nhất, bạn vui lòng cho AI biết bạn là <b>Nam</b> hay <b>Nữ</b> ạ?", [
                { label: '👨 Nam', action: 'gender_male' },
                { label: '👩 Nữ', action: 'gender_female' }
            ]);
        } else if (actionType === 'human') {
            appendAIMessage("Tôi đang kết nối bạn với chuyên viên tư vấn. Vui lòng giữ máy trong giây lát... 👨‍💻");
        } else {
            appendAIMessage("Dạ, AI đã nhận thông tin. Tính năng này hiện đang được kết nối với hệ thống.");
        }
    }, 1200); 
}

function handleEnter(e) {
    if (e.key === 'Enter' && chatInput.value.trim() !== '') sendManualMessage();
}

function sendManualMessage() {
    const text = chatInput.value.trim();
    if (!text) return;
    if (quickActionsArea) quickActionsArea.style.display = 'none';
    appendUserMessage(text);
    chatInput.value = '';
    showTypingIndicator();
    setTimeout(() => {
        removeTypingIndicator();
        appendAIMessage("Xin lỗi, tôi là phiên bản demo AI. Đối với câu hỏi phức tạp: <i>'" + text + "'</i>, bạn có muốn tôi chuyển cho nhân viên thật hỗ trợ không?", [
            { label: 'Gặp nhân viên', action: 'human' },
            { label: 'Quay lại menu', action: 'menu' }
        ]);
    }, 1500);
}