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

        $db = Database::connect();

        try {
            $db->beginTransaction();

            $orderId = $this->orderModel->create($data);
            if (!$orderId) {
                throw new Exception("Không tạo được order");
            }

            $cart = $_SESSION['cart'] ?? [];
            foreach ($cart as $item) {
                $stmt = $db->prepare(
                    "INSERT INTO order_item (orderId, variantId, quantity, price) VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([
                    $orderId,
                    $item['variantId'] ?? $item['productId'] ?? null,
                    $item['quantity'],
                    $item['price']
                ]);

                $orderItemId = $db->lastInsertId();

                if (!empty($_SESSION['prescription_data'])) {
                    $p = $_SESSION['prescription_data'];

                    $pres = new Prescription();
                    $pres->userId = $p['userId'] ?? ($_SESSION['user']['userId'] ?? null);
                    $pres->orderItemId = $orderItemId;
                    $pres->leftEye = $p['leftEye'];
                    $pres->rightEye = $p['rightEye'];
                    $pres->leftPD = $p['leftPD'];
                    $pres->rightPD = $p['rightPD'];
                    $pres->imagePath = $p['imagePath'];
                    $pres->save($db);
                    unset($_SESSION['prescription_data']);
                }
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

    // KHÁCH HÀNG YÊU CẦU HỦY ĐƠN HÀNG
    public function sendCustomerMessageForUser($userId, $message) {
        if (!$userId || empty($message)) {
            return false;
        }

        $orderId = $this->orderModel->findLatestOrderIdByUserId($userId);
        if (!$orderId) {
            return false;
        }

        return $this->updateStatus($orderId, 'Cancelled');
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
}
