<?php

require_once __DIR__ . "/../../config/db_connect.php";
require_once __DIR__ . "/../models/CartModel.php";
require_once __DIR__ . "/../models/CustomerModel.php";
require_once __DIR__ . "/../models/StaffModel.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/order/OrderModel.php";
require_once __DIR__ . "/../models/order/OrderItemModel.php";
require_once __DIR__ . "/../models/order/PaymentModel.php";

class PaymentService {
    private $conn;
    private $cartModel;
    private $customerModel;
    private $staffModel;
    private $userModel;
    private $orderModel;
    private $orderItemModel;
    private $paymentModel;
    private $paymentConfig;

    public function __construct() {
        $this->conn = Database::connect();
        $this->cartModel = new CartModel($this->conn);
        $this->customerModel = new CustomerModel();
        $this->staffModel = new StaffModel();
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentModel = new PaymentModel();
        $this->paymentConfig = require __DIR__ . "/../../config/payment_config.php";
    }

    private function response($success, $message, $data = null) {
        return [
            "success" => $success,
            "message" => $message,
            "data" => $data
        ];
    }

    private function normalizeCartItemIds($cartItemIds) {
        if (is_string($cartItemIds)) {
            $cartItemIds = explode(",", $cartItemIds);
        }

        if (!is_array($cartItemIds)) {
            return [];
        }

        $ids = array_values(array_unique(array_filter(array_map("intval", $cartItemIds))));
        return array_values(array_filter($ids, function ($id) {
            return $id > 0;
        }));
    }

    private function getCustomerIdByUserId($userId) {
        $customer = $this->customerModel->findByUserId($userId);
        return $customer ? (int) $customer->getCustomerId() : null;
    }

    private function buildTransferNote($userId) {
        $prefix = $this->paymentConfig['transferPrefix'] ?? 'EYESGLASS';
        return $prefix . "-U" . $userId . "-" . date("YmdHis");
    }

    private function mapPaymentStatusLabel($status) {
        return $status === "Paid" ? "Success" : $status;
    }

    private function calculateSummary($items) {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
        }

        $shippingFee = $subtotal > 0 ? (int) ($this->paymentConfig['defaultShippingFee'] ?? 30000) : 0;

        return [
            "subtotal" => $subtotal,
            "shippingFee" => $shippingFee,
            "discount" => 0,
            "total" => $subtotal + $shippingFee
        ];
    }

    public function getCheckoutSummary($userId, $selectedCartItems = []) {
        $customerId = $this->getCustomerIdByUserId($userId);
        if (!$customerId) {
            return $this->response(false, "Không tìm thấy khách hàng");
        }

        $selectedIds = $this->normalizeCartItemIds($selectedCartItems);
        $items = $selectedIds
            ? $this->cartModel->getCartDetailsByCustomerAndIds($customerId, $selectedIds)
            : $this->cartModel->getCartDetailsByCustomer($customerId);

        if (!$items) {
            return $this->response(false, "Không có sản phẩm để thanh toán");
        }

        $summary = $this->calculateSummary($items);
        $user = $this->userModel->findUser($userId);

        return $this->response(true, "Lấy dữ liệu thanh toán thành công", [
            "items" => $items,
            "summary" => $summary,
            "bankInfo" => [
                "bankCode" => $this->paymentConfig['bankCode'],
                "bankName" => $this->paymentConfig['bankName'],
                "accountNumber" => $this->paymentConfig['accountNumber'],
                "accountName" => $this->paymentConfig['accountName'],
                "branch" => $this->paymentConfig['branch'],
            ],
            "transferNote" => $this->buildTransferNote($userId),
            "customer" => [
                "name" => $user ? $user->getName() : "",
                "email" => $user ? $user->getEmail() : "",
                "phone" => $user ? $user->getPhone() : "",
            ]
        ]);
    }

    public function createPendingPayment($userId, $payload) {
        $customerId = $this->getCustomerIdByUserId($userId);
        if (!$customerId) {
            return $this->response(false, "Không tìm thấy khách hàng");
        }

        $selectedIds = $this->normalizeCartItemIds($payload['selectedCartItems'] ?? []);
        if (!$selectedIds) {
            return $this->response(false, "Bạn chưa chọn sản phẩm thanh toán");
        }

        $items = $this->cartModel->getCartDetailsByCustomerAndIds($customerId, $selectedIds);
        if (!$items) {
            return $this->response(false, "Giỏ hàng không còn sản phẩm hợp lệ");
        }

        $recipientName = trim($payload['recipientName'] ?? '');
        $recipientPhone = trim($payload['recipientPhone'] ?? '');
        $recipientEmail = trim($payload['recipientEmail'] ?? '');
        $detailAddress = trim($payload['detailAddress'] ?? '');
        $city = trim($payload['city'] ?? '');
        $district = trim($payload['district'] ?? '');
        $ward = trim($payload['ward'] ?? '');
        $note = trim($payload['note'] ?? '');
        $transferNote = trim($payload['transferNote'] ?? '');

        if (!$recipientName || !$recipientPhone || !$recipientEmail || !$detailAddress || !$city || !$district || !$ward) {
            return $this->response(false, "Vui lòng nhập đầy đủ thông tin giao hàng");
        }

        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->response(false, "Email nhận hàng không hợp lệ");
        }

        if ($transferNote === '') {
            $transferNote = $this->buildTransferNote($userId);
        }

        $summary = $this->calculateSummary($items);
        $shippingAddress = implode(", ", array_filter([$detailAddress, $ward, $district, $city]));

        try {
            $this->conn->beginTransaction();

            $this->userModel->update($userId, [
                "name" => $recipientName,
                "phone" => $recipientPhone
            ], "userId");

            $customer = $this->customerModel->findByUserId($userId);
            if ($customer) {
                $this->customerModel->updateCustomer($customer->getCustomerId(), [
                    "address" => $shippingAddress
                ]);
            }

            $orderId = $this->orderModel->create([
                "customerId" => $customerId,
                "orderDate" => date("Y-m-d H:i:s"),
                "status" => "Pending",
                "totalPrice" => $summary['total'],
                "staffId" => null
            ]);

            if (!$orderId) {
                throw new Exception("Không tạo được đơn hàng");
            }

            foreach ($items as $item) {
                $this->orderItemModel->create([
                    "orderId" => $orderId,
                    "variantId" => (int) $item['variantId'],
                    "quantity" => (int) $item['quantity'],
                    "price" => (float) $item['price']
                ]);
            }

            $this->paymentModel->createPayment([
                "orderId" => $orderId,
                "paymentMethod" => "Bank Transfer",
                "paymentStatus" => "Pending"
            ]);

            $this->cartModel->removeItemsByIds($customerId, $selectedIds);

            $this->conn->commit();

            return $this->response(true, "Đã ghi nhận thanh toán, đơn hàng đang chờ admin duyệt", [
                "orderId" => $orderId,
                "paymentStatus" => "Pending",
                "transferNote" => $transferNote,
                "summary" => $summary
            ]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            return $this->response(false, "Không thể tạo đơn thanh toán: " . $e->getMessage());
        }
    }

    public function getAdminPayments($statusFilter = "pending") {
        $paymentStatus = strtolower($statusFilter) === "success" ? "Paid" : "Pending";

        $sql = "SELECT
                    p.paymentId,
                    p.paymentMethod,
                    p.paymentStatus,
                    o.orderId,
                    o.orderDate,
                    o.totalPrice,
                    u.name AS customerName,
                    u.email AS customerEmail,
                    u.phone AS customerPhone,
                    c.address AS customerAddress,
                    item_summary.items
                FROM payment p
                JOIN orders o ON p.orderId = o.orderId
                JOIN customers c ON o.customerId = c.customerId
                JOIN users u ON c.userId = u.userId
                LEFT JOIN (
                    SELECT
                        oi.orderId,
                        GROUP_CONCAT(CONCAT(pr.name, ' x', oi.quantity) SEPARATOR ' | ') AS items
                    FROM order_item oi
                    JOIN product_variant pv ON oi.variantId = pv.variantId
                    JOIN product pr ON pv.productId = pr.productId
                    GROUP BY oi.orderId
                ) item_summary ON item_summary.orderId = o.orderId
                WHERE p.paymentStatus = :paymentStatus
                ORDER BY o.orderDate DESC";

        $rows = $this->paymentModel->queryAll($sql, [
            ":paymentStatus" => $paymentStatus
        ]);

        foreach ($rows as &$row) {
            $row['uiStatus'] = $this->mapPaymentStatusLabel($row['paymentStatus']);
        }

        return $this->response(true, "Lấy danh sách thanh toán thành công", $rows);
    }

    public function approvePayment($paymentId, $managerUserId) {
        $payment = $this->paymentModel->findPayment($paymentId);
        if (!$payment) {
            return $this->response(false, "Không tìm thấy yêu cầu thanh toán");
        }

        if ($payment->paymentStatus !== "Pending") {
            return $this->response(false, "Thanh toán này đã được xử lý");
        }

        $staff = $this->staffModel->findByUserId($managerUserId);
        if (!$staff || $staff->getPosition() !== "manager") {
            return $this->response(false, "Bạn không có quyền duyệt thanh toán");
        }

        try {
            $this->conn->beginTransaction();

            $this->paymentModel->updatePayment($paymentId, [
                "paymentStatus" => "Paid"
            ]);

            $this->orderModel->update($payment->orderId, [
                "status" => "Confirmed",
                "staffId" => $staff->getStaffId()
            ], "orderId");

            $this->conn->commit();

            return $this->response(true, "Đã duyệt thanh toán thành công");
        } catch (Exception $e) {
            $this->conn->rollBack();
            return $this->response(false, "Không thể duyệt thanh toán: " . $e->getMessage());
        }
    }
}
