<?php
require_once __DIR__ . "/../models/order/OrderModel.php";
require_once __DIR__ . "/../models/StaffModel.php";
require_once __DIR__ . "/../models/Prescription.php";

date_default_timezone_set('Asia/Ho_Chi_Minh');

class OrderService {
    private $orderModel;
    private $staffModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->staffModel = new StaffModel();
    }

    // TẠO ĐƠN HÀNG MỚI
    public function createOrder($data) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($data['customerId'])) {
            return ["success" => false, "message" => "Thiếu customerId"];
        }

        $currentStaffId = $_SESSION['user']['staffId'] ?? null;
        $currentPosition = strtolower($_SESSION['user']['position'] ?? '');
        if ($currentPosition === 'sales' && $currentStaffId) {
            $data['staffId'] = $currentStaffId;
        }

        $data['orderDate'] = date('Y-m-d H:i:s');
        $data['status'] = 'Pending';
        $data['totalPrice'] = $data['totalPrice'] ?? 0;

        // PHÂN LOẠI ĐƠN HÀNG DỰA TRÊN STOCK VÀ LENS COST
        $db = Database::connect();
        $hasOutOfStock = false;
        $hasPrescription = ($data['lensCost'] ?? 0) > 0;

        // Kiểm tra stock của từng item trong cart
        $cart = $_SESSION['cart'] ?? [];
        foreach ($cart as $item) {
            if (isset($item['variantId']) && $item['variantId']) {
                // Kiểm tra stock của product variant
                $stmt = $db->prepare("SELECT stock FROM product_variant WHERE variantId = ?");
                $stmt->execute([$item['variantId']]);
                $variant = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$variant || $variant['stock'] <= 0) {
                    $hasOutOfStock = true;
                    break;
                }
            }
        }

        if ($hasOutOfStock) {
            $data['order_type'] = 'pre_order';
        } elseif ($hasPrescription) {
            $data['order_type'] = 'prescription';
        } else {
            $data['order_type'] = 'ready_stock';
        }

        try {
            $db->beginTransaction();

            $orderId = $this->orderModel->create($data);
            if (!$orderId) {
                throw new Exception("Không tạo được order");
            }

            $cart = $_SESSION['cart'] ?? [];
            foreach ($cart as $item) {
                $variantId = $item['variantId'] ?? $item['productId'] ?? null;
                $comboId = $item['comboId'] ?? null;

                $stmt = $db->prepare(
                    "INSERT INTO order_item (orderId, variantId, comboId, quantity, price) VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->execute([
                    $orderId,
                    $variantId,
                    $comboId,
                    $item['quantity'],
                    $item['price']
                ]);

                $orderItemId = $db->lastInsertId();

                if (!empty($_SESSION['prescription_data'])) {
                    $p = $_SESSION['prescription_data'];

                    $pres = new Prescription();
                    $pres->orderId = $orderId;
                    $pres->userId = null;
                    $pres->orderItemId = $orderItemId;
                    $pres->leftEye = $p['leftEye'];
                    $pres->rightEye = $p['rightEye'];
                    $pres->leftPD = $p['leftPD'];
                    $pres->rightPD = $p['rightPD'];
                    $pres->imagePath = $p['imagePath'];
                    $pres->status = 'Pending';

                    $pres->save($db);
                } else {
                    // No prescription data in session
                }
            }

            // Xóa session độ kính sau khi hoàn tất lưu toàn bộ items
            if (isset($_SESSION['prescription_data'])) {
                unset($_SESSION['prescription_data']);
            }

            $db->commit();
            return [
                "success" => true,
                "orderId" => $orderId
            ];
        } catch (Exception $e) {
            $db->rollBack();
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    // LẤY DANH SÁCH ĐƠN HÀNG THEO TRẠNG THÁI
    public function getOrdersByStatus($status) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $position = strtolower($_SESSION['user']['position'] ?? '');
        $data = $this->orderModel->getOrdersForSales($status);
        if ($position === 'operation') {
            // Filter chỉ 3 trạng thái cho Operation
            $allowedStatuses = ['Processing', 'Shipped', 'Delivered'];
            $data = array_filter($data, function($order) use ($allowedStatuses) {
                return in_array($order['status'], $allowedStatuses);
            });
        }
        return ["success" => true, "data" => array_values($data)];
    }

    // CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG VÀ GÁN NHÂN VIÊN PHÙ HỢP
    public function updateStatus($orderId, $status, $trackingCode = null) {
        if (!$orderId || !$status) {
            return [
                "success" => false,
                "message" => "Thiếu dữ liệu"
            ];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $updateData = [
            'status' => $status
        ];

        $currentStaffId = $_SESSION['user']['staffId'] ?? null;
        $currentPosition = strtolower($_SESSION['user']['position'] ?? '');

        $salesStatuses = ['Pending', 'Confirmed', 'Cancelled', 'Returned'];
        $operationStatuses = ['Processing', 'Shipped', 'Delivered'];

        if (in_array($status, $salesStatuses, true)) {
            if ($currentPosition === 'sales' && $currentStaffId) {
                $updateData['staffId'] = $currentStaffId;
            } else {
                $defaultSales = $this->staffModel->findByPosition('sales');
                if ($defaultSales) {
                    $updateData['staffId'] = $defaultSales->getStaffId();
                }
            }
        } elseif (in_array($status, $operationStatuses, true)) {
            // Gán staffId cho sales để khách hàng vẫn thấy đơn hàng
            $defaultSales = $this->staffModel->findByPosition('sales');
            if ($defaultSales) {
                $updateData['staffId'] = $defaultSales->getStaffId();
            }
        }

        $res = $this->orderModel->update($orderId, $updateData);
        return [
            "success" => $res,
            "message" => $res ? "Cập nhật thành công" : "Cập nhật thất bại"
        ];
    }

    // LẤY CHI TIẾT ĐƠN HÀNG THEO ID
    public function getOrderDetail($orderId) {
        $data = $this->orderModel->getOrderDetailWithCustomer($orderId);
        return [
            "success" => !empty($data),
            "data" => $data
        ];
    }

    // LẤY TIN NHẮN CỦA KHÁCH HÀNG VÀ ĐÁNH DẤU NHÂN VIÊN ĐÃ ĐỌC
    public function getCustomerMessagesForUser($userId) {
        if (!$userId) {
            return [
                'orderId' => null,
                'messages' => []
            ];
        }

        $orderId = $this->orderModel->findLatestOrderIdByUserId($userId);
        if (!$orderId) {
            return [
                'orderId' => null,
                'messages' => []
            ];
        }

        $this->orderModel->markStaffMessagesReadForUser($userId);
        $messages = $this->orderModel->getMessagesByOrder($orderId);
        return [
            'orderId' => $orderId,
            'messages' => $messages
        ];
    }

    // LẤY SỐ TIN NHẮN CHƯA ĐỌC CỦA KHÁCH HÀNG
    public function getSupportUnreadCountForUser($userId) {
        if (!$userId) {
            return 0;
        }

        return $this->orderModel->getSupportUnreadCountForUser($userId);
    }

    // KHÁCH HÀNG GỬI TIN NHẮN HỖ TRỢ
    public function sendCustomerMessageForUser($userId, $message) {
        if (!$userId || empty($message)) {
            return false;
        }

        $orderId = $this->orderModel->findLatestOrderIdByUserId($userId);
        if (!$orderId) {
            return false;
        }

        return $this->orderModel->saveMessage($orderId, 'Customer', $message);
    }

    // ĐỔI TRẢ ĐƠN HÀNG
    public function returnOrder($orderId) {
        return $this->updateStatus($orderId, 'Returned');
    }

    // THỐNG KÊ SỐ LƯỢNG ĐƠN HÀNG THEO TRẠNG THÁI
    public function getOrderStats() {
        $data = $this->orderModel->countByStatus();
        return ["success" => true, "data" => $data];
    }

    // ĐÁNH DẤU ĐÃ LIÊN HỆ VÀ GỬI TIN NHẮN XÁC NHẬN
    public function handleContactAndMessage($orderId, $message) {
        $updateStatus = $this->orderModel->update($orderId, ['is_contacted' => 1]);

        if ($updateStatus) {
            $saveMsg = $this->orderModel->saveMessage($orderId, 'Staff', $message);
            if ($saveMsg) {
                return ["success" => true, "message" => "Đã gửi tin nhắn và cập nhật trạng thái!"];
            }
        }

        return ["success" => false, "message" => "Có lỗi xảy ra khi lưu dữ liệu."];
    }

    // LIÊN HỆ VỚI KHÁCH HÀNG
    public function contactCustomer($orderId, $message) {
        $result = $this->orderModel->saveMessage($orderId, 'Staff', $message);
        if ($result) {
            return ["success" => true, "message" => "Gửi tin nhắn thành công"];
        }
        return ["success" => false, "message" => "Không thể lưu tin nhắn"];
    }

    // NHÂN VIÊN LẤY LỊCH SỬ CHAT VÀ ĐÁNH DẤU ĐÃ ĐỌC
    public function getMessages($orderId) {
        $this->orderModel->markCustomerMessagesReadForOrder($orderId);
        $messages = $this->orderModel->getMessagesByOrder($orderId);
        return [
            "success" => true,
            "data" => $messages
        ];
    }

    // LẤY DANH SÁCH CUỘC TRÒ CHUYỆN GIỮA KHÁCH HÀNG VÀ NHÂN VIÊN
    public function getConversationList() {
        $data = $this->orderModel->getAllCustomerFromOrders();
        return ["success" => true, "data" => $data];
    }

    // LẤY DANH SÁCH ĐƠN HÀNG CÓ ĐƠN KÍNH (PRESCRIPTION)
    public function getPrescriptionOrders($status = 'all') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        try {
            $db = Database::connect();

            $sql = "SELECT 
                        o.orderId,
                        o.orderDate,
                        o.status,
                        o.totalPrice,
                        u.name AS cust_name,
                        u.email AS cust_email,
                        u.phone AS cust_phone,
                        pr.prescriptionId,
                        pr.leftEye,
                        pr.rightEye,
                        pr.leftPD,
                        pr.rightPD,
                        pr.imagePath AS prescriptionImagePath,
                        COALESCE(pr.status, 'Pending') AS prescriptionStatus
                    FROM orders o
                    JOIN customers c ON o.customerId = c.customerId
                    JOIN users u ON c.userId = u.userId
                    JOIN prescription pr ON o.orderId = pr.orderId 
                        AND pr.prescriptionId = (
                            SELECT MAX(p.prescriptionId) FROM prescription p 
                            WHERE p.orderId = o.orderId
                        )
                    WHERE o.order_type = 'prescription'";

            if ($status !== 'all') {
                $prescriptionStatus = '';
                switch (strtolower($status)) {
                    case 'pending':
                        $prescriptionStatus = 'Pending';
                        break;
                    case 'processing':
                        $prescriptionStatus = 'Confirmed';
                        break;
                    case 'completed':
                        $prescriptionStatus = 'Completed';
                        break;
                }
                if ($prescriptionStatus) {
                    $sql .= " AND COALESCE(pr.status, 'Pending') = :status";
                }
            }
            
            $sql .= " ORDER BY o.orderDate DESC";
            
            $stmt = $db->prepare($sql);
            if ($status !== 'all' && isset($prescriptionStatus)) {
                $stmt->bindParam(':status', $prescriptionStatus);
            }
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return ["success" => true, "data" => $data];
        } catch (Exception $e) {
            error_log("Lỗi getPrescriptionOrders: " . $e->getMessage());
            return ["success" => false, "message" => $e->getMessage(), "data" => []];
        }
    }

    // LẤY CHI TIẾT PRESCRIPTION THEO ORDER ID
    public function getPrescriptionDetail($orderId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $db = Database::connect();

            $sql = "SELECT 
                        o.orderId,
                        o.orderDate,
                        o.status,
                        o.totalPrice,
                        u.name AS cust_name,
                        u.email AS cust_email,
                        u.phone AS cust_phone,
                        pr.prescriptionId,
                        pr.leftEye,
                        pr.rightEye,
                        pr.leftPD,
                        pr.rightPD,
                        pr.imagePath AS prescriptionImagePath,
                        COALESCE(pr.status, 'Pending') AS prescriptionStatus
                    FROM orders o
                    JOIN customers c ON o.customerId = c.customerId
                    JOIN users u ON c.userId = u.userId
                    JOIN prescription pr ON o.orderId = pr.orderId
                    WHERE o.orderId = ? AND o.order_type = 'prescription'";

            $stmt = $db->prepare($sql);
            $stmt->execute([$orderId]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ["success" => true, "data" => $data];
        } catch (Exception $e) {
            error_log("Lỗi getPrescriptionDetail: " . $e->getMessage());
            return ["success" => false, "message" => $e->getMessage(), "data" => []];
        }
    }

    // CẬP NHẬT TRẠNG THÁI PRESCRIPTION
    public function updatePrescriptionStatus($prescriptionId, $status) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $db = Database::connect();

            // Bắt đầu transaction
            $db->beginTransaction();

            // Cập nhật trạng thái prescription
            $sql = "UPDATE prescription SET status = ? WHERE prescriptionId = ?";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([$status, $prescriptionId]);

            if (!$result) {
                throw new Exception("Không thể cập nhật trạng thái prescription");
            }

            // Đồng bộ trạng thái đơn hàng cho prescription orders
            // Lấy orderId từ prescription
            $getOrderSql = "SELECT pr.orderId FROM prescription pr WHERE pr.prescriptionId = ?";
            $getOrderStmt = $db->prepare($getOrderSql);
            $getOrderStmt->execute([$prescriptionId]);
            $orderData = $getOrderStmt->fetch(PDO::FETCH_ASSOC);

            if ($orderData && $orderData['orderId']) {
                $orderId = $orderData['orderId'];

                // Kiểm tra xem order này có phải prescription type không
                $checkOrderSql = "SELECT order_type FROM orders WHERE orderId = ?";
                $checkOrderStmt = $db->prepare($checkOrderSql);
                $checkOrderStmt->execute([$orderId]);
                $orderInfo = $checkOrderStmt->fetch(PDO::FETCH_ASSOC);

                if ($orderInfo && $orderInfo['order_type'] === 'prescription') {
                    $orderStatus = null;
                    if ($status === 'Confirmed') {
                        $orderStatus = 'Confirmed';
                    } elseif ($status === 'Completed') {
                        $orderStatus = 'Processing'; // Chuyển giao vận chuyển
                    }

                    if ($orderStatus) {
                        $updateOrderSql = "UPDATE orders SET status = ? WHERE orderId = ?";
                        $updateOrderStmt = $db->prepare($updateOrderSql);
                        $updateOrderStmt->execute([$orderStatus, $orderId]);
                    }
                }
            }

            $db->commit();
            return ["success" => true, "message" => "Cập nhật trạng thái thành công"];

        } catch (Exception $e) {
            $db->rollBack();
            error_log("Lỗi updatePrescriptionStatus: " . $e->getMessage());
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
