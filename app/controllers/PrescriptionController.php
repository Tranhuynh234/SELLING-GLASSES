<?php
// Gọi file Model vào
require_once __DIR__ . '/../models/Prescription.php';

class PrescriptionController {
    
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function create() {
        require_once __DIR__ . '/../views/order/prescription.php';
    }

    public function store() {
        // ĐẢM BẢO SESSION ĐƯỢC BẬT ĐẦU TIÊN
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            // 1. Gom thông số MẮT PHẢI
            $rightEyeJson = json_encode([
                'sph'  => $_POST['right_sph'] ?? '',
                'cyl'  => $_POST['right_cyl'] ?? '',
                'axis' => $_POST['right_axis'] ?? '',
                'add'  => $_POST['right_add'] ?? ''
            ]);

            // 2. Gom thông số MẮT TRÁI
            $leftEyeJson = json_encode([
                'sph'  => $_POST['left_sph'] ?? '',
                'cyl'  => $_POST['left_cyl'] ?? '',
                'axis' => $_POST['left_axis'] ?? '',
                'add'  => $_POST['left_add'] ?? ''
            ]);

            // 3. Xử lý Upload Ảnh
            $imagePath = null;
            if (isset($_FILES['prescription_image']) && $_FILES['prescription_image']['error'] == 0) {
                $uploadDir = __DIR__ . '/../../public/uploads/prescriptions/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['prescription_image']['name']);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['prescription_image']['tmp_name'], $targetFile)) {
                    $imagePath = 'uploads/prescriptions/' . $fileName;
                }
            }

            // 4. Khởi tạo Object Model
            $prescription = new Prescription();
            $prescription->orderItemId = $_POST['order_item_id'] ?? 1; 
            $prescription->leftEye = $leftEyeJson;
            $prescription->rightEye = $rightEyeJson;
            $prescription->leftPD = $_POST['left_pd'] ?? '';
            $prescription->rightPD = $_POST['right_pd'] ?? '';
            $prescription->imagePath = $imagePath;

            // 5. Lưu vào Database
            // Tìm hàm store() và sửa đoạn lưu Session
           // Trong PrescriptionController.php, hàm store()
            if ($prescription->save($this->conn)) {
                
                // 1. Lấy giá tròng kính từ form (Ví dụ: 250000)
                $lensPrice = isset($_POST['lens_price']) ? (int)$_POST['lens_price'] : 0;

                // 2. Định nghĩa phí gia công cố định (Giống như hiển thị trên giao diện của bạn)
                $processingFee = 50000;

                // 3. TỔNG CỘNG thực tế sẽ bao gồm cả hai
                $totalPrescriptionCost = $lensPrice + $processingFee;

                // 4. Lưu TỔNG CỘNG này vào Session
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['prescription_total'] = $totalPrescriptionCost; 

                // Điều hướng về Checkout
                header("Location: /SELLING-GLASSES/public/index.php?url=checkout&status=saved");
                exit();
            }
        }
    }
}