let cameraInstance = null;
let streamRef = null;
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const btnStart = document.getElementById('btn-start-tryon');
const sizeResult = document.getElementById('ai-size-result');

const glassesConfig = {
    Square: 1.6,
    Rectangle: 0.17,
    Round: 1.5,
    Oval: 1.5,
    "Cat-eye": 1.6,
    default: 1.6
};

// load kính
const glassImg = new Image();
glassImg.src = '/SELLING-GLASSES/public/assets/images/glasses_model_AI/Square.png';

// loại kính đang chọn
let currentGlass = "default";

function changeGlasses(type) {
    currentGlass = type;

    glassImg.src = `/SELLING-GLASSES/public/assets/images/glasses_model_AI/${type}.png`;
}

let isRunning = false;

// MediaPipe FaceMesh
const faceMesh = new FaceMesh({
    locateFile: (file) => {
        return `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`;
    }
});

faceMesh.setOptions({
    maxNumFaces: 1,
    refineLandmarks: true,
    minDetectionConfidence: 0.5,
    minTrackingConfidence: 0.5
});

// khi detect xong
faceMesh.onResults(results => {
    if (!isRunning) return;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (results.multiFaceLandmarks.length > 0) {
        const landmarks = results.multiFaceLandmarks[0];

        // mắt trái & phải (MediaPipe index)
        const leftEye = landmarks[33];
        const rightEye = landmarks[263];

        // chuyển về pixel
        const x1 = leftEye.x * canvas.width;
        const y1 = leftEye.y * canvas.height;
        const x2 = rightEye.x * canvas.width;
        const y2 = rightEye.y * canvas.height;

        // khoảng cách mắt
        const eyeDist = Math.hypot(x2 - x1, y2 - y1);

        // scale kính
        const scale = glassesConfig[currentGlass] || 1.6;
        const glassWidth = eyeDist * 2.0;
        const glassHeight = glassWidth * 0.4; 

        // tâm
        const midX = (x1 + x2) / 2;
        const midY = (y1 + y2) / 2;

        // góc xoay
        const angle = Math.atan2(y2 - y1, x2 - x1);

        // vẽ kính
        ctx.save();
        ctx.translate(midX, midY);
        ctx.rotate(angle);
        ctx.drawImage(glassImg, -glassWidth / 2, -glassHeight / 2, glassWidth, glassHeight);
        ctx.restore();

        // gợi ý size
        let size = "";

        if (eyeDist > 130) {
            size = "L";
        } else if (eyeDist < 90) {
            size = "S";
        } else {
            size = "M";
        }
        
        // Lấy các điểm khuôn mặt
        const leftFace = landmarks[234];
        const rightFace = landmarks[454];
        const topFace = landmarks[10];
        const bottomFace = landmarks[152];

        // Tính kích thước mặt
        const faceWidth = Math.abs(rightFace.x - leftFace.x) * canvas.width;
        const faceHeight = Math.abs(bottomFace.y - topFace.y) * canvas.height;

        // Xác định kiểu mặt
        const ratio = faceWidth / faceHeight;

        let faceShape = "";

        if (ratio > 0.95) {
            faceShape = "Mặt tròn";
        } else if (ratio < 0.75) {
            faceShape = "Mặt dài";
        } else {
            faceShape = "Mặt oval";
        }

        // Gợi ý kiểu kính
        let suggest = "";

        if (faceShape === "Mặt tròn") {
            suggest = "Kính vuông / chữ nhật";
        } else if (faceShape === "Mặt dài") {
            suggest = "Kính tròn / oval";
        } else {
            suggest = "Phù hợp nhiều kiểu kính";
        }

        sizeResult.innerHTML = `
             Gợi ý: <b class='text-amber-500'>Size ${size}</b><br>
             Khuôn mặt: <b>${faceShape}</b><br>
             Nên dùng: <b>${suggest}</b>
        `;
    }
});

// start camera
async function startCamera() {

    // Nếu đang chạy -> TẮT
    if (isRunning) {
        isRunning = false;

        // stop camera mediapipe
        if (cameraInstance) {
            cameraInstance.stop();
        }

        // stop webcam thật
        if (streamRef) {
            streamRef.getTracks().forEach(track => track.stop());
        }

        // ẩn danh sách kính
        const selector = document.getElementById('glasses-selector');
        if (selector) {
            selector.classList.add('hidden');
        }

        // reset UI
        video.style.opacity = 0;
        canvas.style.opacity = 0;
        document.getElementById('tryon-placeholder').classList.remove('opacity-0');

        btnStart.innerText = "Bắt đầu thử kính ngay";
        btnStart.disabled = false;

        console.log("Đã tắt AI");

        return;
    }

    // Nếu chưa chạy -> BẬT
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        streamRef = stream;
        video.srcObject = stream;

        video.onloadedmetadata = () => {
            video.play();
            isRunning = true;

            // ẩn placeholder
            document.getElementById('tryon-placeholder').classList.add('opacity-0');

            // hiện danh sách kính
            document.getElementById('glasses-selector').classList.remove('hidden');

            video.style.opacity = 1;
            canvas.style.opacity = 1;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            cameraInstance = new Camera(video, {
                onFrame: async () => {
                    await faceMesh.send({ image: video });
                },
                width: 640,
                height: 480
            });

            cameraInstance.start();
        };

        btnStart.innerText = "AI đang hoạt động";
        btnStart.disabled = false;

    } catch (err) {
        alert("Không mở được camera!");
        console.error(err);
    }
}

btnStart.addEventListener('click', startCamera);