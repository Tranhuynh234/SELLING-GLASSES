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

// DRAG MOUSE 
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

// CHAT UI
function toggleChatbox() {
    isChatOpen = !isChatOpen;

    chatPanel.classList.toggle('opacity-0');
    chatPanel.classList.toggle('pointer-events-none');
    chatPanel.classList.toggle('translate-y-10');

    if (isChatOpen) scrollToBottom();
}

closeChatBtn.addEventListener('click', toggleChatbox);
minimizeChatBtn.addEventListener('click', toggleChatbox);

function scrollToBottom() {
    chatBody.scrollTop = chatBody.scrollHeight;
}

function getTime() {
    const now = new Date();
    return `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;
}

// MESSAGE 
function appendUserMessage(text) {
    const msg = `
    <div class="flex justify-end mt-2">
        <div class="bg-amber-600 text-white p-3 rounded-2xl">${text}</div>
    </div>`;
    chatBody.insertAdjacentHTML('beforeend', msg);
    scrollToBottom();
}

function showTypingIndicator() {
    const html = `
    <div id="typing">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center"><i class="fa-solid fa-robot text-white text-sm"></i></div>
            <div class="bg-white px-3 py-2 rounded-xl">...</div>
        </div>
    </div>`;
    chatBody.insertAdjacentHTML('beforeend', html);
}

function removeTypingIndicator() {
    document.getElementById('typing')?.remove();
}

function appendAIMessage(text, options = []) {
    let optionsHtml = '';

    if (options.length > 0) {
        optionsHtml = `<div class="flex gap-2 mt-2 flex-wrap">`;
        options.forEach(opt => {
            optionsHtml += `
            <button onclick="handleQuickAction('${opt.action}')"
            class="px-3 py-1 border border-amber-600 text-amber-700 rounded-full hover:bg-amber-600 hover:text-white transition">
                ${opt.label}
            </button>`;
        });
        optionsHtml += `</div>`;
    }

    const msg = `
    <div class="mt-2 flex gap-2">
        <div class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-robot text-white text-sm"></i>
        </div>
        <div class="bg-white p-3 rounded-xl border">
            ${text}
            ${optionsHtml}
        </div>
    </div>`;

    chatBody.insertAdjacentHTML('beforeend', msg);
    scrollToBottom();
}

// QUICK ACTION 
function handleQuickAction(actionType) {
    appendUserMessage(actionType);
    showTypingIndicator();

    setTimeout(() => {
        removeTypingIndicator();

        if (actionType === 'find_glasses') {
            appendAIMessage("Bạn chọn giới tính để mình tư vấn chính xác hơn:", [
                { label: `${maleIcon} Nam`, action: 'male' },
                { label: `${femaleIcon} Nữ`, action: 'female' }
            ]);
        }
        else if (actionType === 'human') {
            appendAIMessage("Đang kết nối với nhân viên tư vấn...");
        }
        else {
            appendAIMessage("Tính năng đang phát triển thêm!");
        }

    }, 1000);
}

// INPUT 
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
        appendAIMessage(`Mình đã nhận câu hỏi: "<b>${text}</b>" <br>
        Bạn muốn được hỗ trợ theo cách nào?`, [
            { label: '<i class="fa-solid fa-phone-volume text-amber-500"></i> Gặp tư vấn viên', action: 'human' },
            { label: '<i class="fa-solid fa-robot text-amber-500"></i> Tiếp tục AI', action: 'ai' }
        ]);
    }, 1200);
}