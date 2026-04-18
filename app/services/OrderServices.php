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

    public function getOrdersByStatus($status) {
        $data = $this->orderModel->getOrdersForOps($status);
        return ["success" => true, "data" => $data];
    }

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
            if ($currentPosition === 'operation' && $currentStaffId) {
                $updateData['staffId'] = $currentStaffId;
            } else {
                $defaultOperation = $this->staffModel->findByPosition('operation');
                if ($defaultOperation) {
                    $updateData['staffId'] = $defaultOperation->getStaffId();
                }
            }
        }

        $res = $this->orderModel->update($orderId, $updateData);
        return [
            "success" => $res,
            "message" => $res ? "Cập nhật đơn hàng thành công" : "Lỗi database khi update đơn"
        ];
    }

    public function getOrderDetail($orderId) {
        $data = $this->orderModel->getOrderDetailWithCustomer($orderId);
        return [
            "success" => !empty($data),
            "data" => $data
        ];
    }

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

    public function getSupportUnreadCountForUser($userId) {
        if (!$userId) {
            return 0;
        }

        return $this->orderModel->getSupportUnreadCountForUser($userId);
    }

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

    public function cancelOrder($orderId) {
        return $this->updateStatus($orderId, 'Cancelled');
    }

    public function returnOrder($orderId) {
        return $this->updateStatus($orderId, 'Returned');
    }

    public function getOrderStats() {
        $data = $this->orderModel->countByStatus();
        return ["success" => true, "data" => $data];
    }

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

    public function contactCustomer($orderId, $message) {
        $result = $this->orderModel->saveMessage($orderId, 'Staff', $message);
        if ($result) {
            return ["success" => true, "message" => "Gửi tin nhắn thành công"];
        }
        return ["success" => false, "message" => "Không thể lưu tin nhắn"];
    }

    public function getMessages($orderId) {
        $this->orderModel->markCustomerMessagesReadForOrder($orderId);
        $messages = $this->orderModel->getMessagesByOrder($orderId);
        return [
            "success" => true,
            "data" => $messages
        ];
    }

    public function getConversationList() {
        $data = $this->orderModel->getAllCustomerFromOrders();
        return ["success" => true, "data" => $data];
    }
}
