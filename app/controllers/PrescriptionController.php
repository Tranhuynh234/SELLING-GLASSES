<?php
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
        if (session_status() === PHP_SESSION_NONE) session_start();

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $userId = $_SESSION['user']['userId'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }

        // CASE: dùng lại từ DB (profile)
        if (isset($_POST['use_saved']) && $_POST['use_saved'] === 'true') {

            $stmt = $this->conn->prepare("
                SELECT * FROM prescription 
                WHERE userId = ? 
                ORDER BY prescriptionId DESC LIMIT 1
            ");
            $stmt->execute([$userId]);
            $pres = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pres) {
                $_SESSION['prescription_data'] = [
                    'userId' => $userId,
                    'leftEye' => $pres['leftEye'],
                    'rightEye' => $pres['rightEye'],
                    'leftPD' => $pres['leftPD'],
                    'rightPD' => $pres['rightPD'],
                    'imagePath' => $pres['imagePath']
                ];
            }

            $_SESSION['prescription_total'] = 300000;

            echo json_encode(['success' => true]);
            exit;
        }

        // CASE: nhập mới từ form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // JSON mắt
            $rightEyeJson = json_encode([
                'sph'  => $_POST['right_sph'] ?? '',
                'cyl'  => $_POST['right_cyl'] ?? '',
                'axis' => $_POST['right_axis'] ?? '',
                'add'  => $_POST['right_add'] ?? ''
            ]);

            $leftEyeJson = json_encode([
                'sph'  => $_POST['left_sph'] ?? '',
                'cyl'  => $_POST['left_cyl'] ?? '',
                'axis' => $_POST['left_axis'] ?? '',
                'add'  => $_POST['left_add'] ?? ''
            ]);

            $imagePath = null;

            // LƯU SESSION 
            $_SESSION['prescription_data'] = [
                'userId' => $userId,
                'leftEye' => $leftEyeJson,
                'rightEye' => $rightEyeJson,
                'leftPD' => $_POST['left_pd'] ?? '',
                'rightPD' => $_POST['right_pd'] ?? '',
                'imagePath' => $imagePath
            ];

            // TÍNH TIỀN
            $lensPrice = (int)($_POST['lens_price'] ?? 0);
            $_SESSION['prescription_total'] = $lensPrice + 50000;

            try {
                if ($userId) {

                    $check = $this->conn->prepare("SELECT prescriptionId FROM prescription WHERE userId = ? LIMIT 1");
                    $check->execute([$userId]);
                    $exists = $check->fetch(PDO::FETCH_ASSOC);

                    if ($exists) {
                        $upd = $this->conn->prepare("UPDATE prescription SET leftEye = :leftEye, rightEye = :rightEye, leftPD = :leftPD, rightPD = :rightPD, imagePath = :imagePath WHERE userId = :userId");
                        $upd->execute([
                            ':leftEye' => $leftEyeJson,
                            ':rightEye' => $rightEyeJson,
                            ':leftPD' => $_POST['left_pd'] ?? '',
                            ':rightPD' => $_POST['right_pd'] ?? '',
                            ':imagePath' => $imagePath,
                            ':userId' => $userId
                        ]);
                    } else {

                        $ins = $this->conn->prepare("INSERT INTO prescription (userId, orderItemId, leftEye, rightEye, leftPD, rightPD, imagePath) VALUES (:userId, 0, :leftEye, :rightEye, :leftPD, :rightPD, :imagePath)");
                        $ins->execute([
                            ':userId' => $userId,
                            ':leftEye' => $leftEyeJson,
                            ':rightEye' => $rightEyeJson,
                            ':leftPD' => $_POST['left_pd'] ?? '',
                            ':rightPD' => $_POST['right_pd'] ?? '',
                            ':imagePath' => $imagePath
                        ]);
                    }
                }
            } catch (Exception $e) {

                error_log("Prescription save error: " . $e->getMessage());
            }

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } else {
                header("Location: /SELLING-GLASSES/public/index.php?url=checkout&status=saved");
            }
            exit;
        }
    }
}