<?php
if (!function_exists('getStatusDetails')) {
    function getStatusDetails($status) {
        switch ($status) {
            case 'Pending':
                return ['text' => 'Chờ xử lý', 'class' => 'status-pending'];
            case 'Confirmed':
                return ['text' => 'Đã xác nhận', 'class' => 'status-confirmed'];
            case 'Processing':
                return ['text' => 'Đang xử lý', 'class' => 'status-processing'];
            case 'Shipped':
                return ['text' => 'Đang giao', 'class' => 'status-shipping'];
            case 'Shipping':
                return ['text' => 'Đang giao', 'class' => 'status-shipping'];
            case 'Delivered':
                return ['text' => 'Đã giao', 'class' => 'status-completed'];
            case 'Completed':
                return ['text' => 'Hoàn tất', 'class' => 'status-completed'];
            case 'Cancelled':
                return ['text' => 'Đã hủy', 'class' => 'status-cancelled'];
            case 'Returned':
                return ['text' => 'Trả hàng', 'class' => 'status-returned'];
            default:
                return ['text' => $status, 'class' => 'status-returned'];
        }
    }
}

if (!function_exists('getRequestTypeLabel')) {
    function getRequestTypeLabel($reason) {
        $text = mb_strtolower(trim($reason), 'UTF-8');
        $returnKeywords = [
            'đổi',
            'trả',
            'return',
            'exchange',
            'giao sai',
            'mẫu kính',
            'màu sắc',
            'đeo không vừa',
            'quá rộng',
            'quá chật',
            'size',
            'kích thước',
            'sai thông số',
            'độ cận',
            'viễn',
            'broken',
            'wrong_item',
            'wrong_prescription',
            'not_fit',
        ];

        foreach ($returnKeywords as $keyword) {
            if (mb_strpos($text, $keyword) !== false) {
                return 'Đổi trả';
            }
        }

        return 'Khiếu nại';
    }
}

if (session_status() === PHP_SESSION_NONE) session_start();
$db = Database::connect();
$userId = $_SESSION['user']['userId'];

// --- 1. LẤY THÔNG TIN USER  ---
$queryUser = "SELECT u.name, u.email, u.phone, c.address, c.customerId 
              FROM users u 
              LEFT JOIN customers c ON u.userId = c.userId 
              WHERE u.userId = :userId";
$stmtUser = $db->prepare($queryUser);
$stmtUser->execute([':userId' => $userId]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$paymentData = $paymentController->getPaymentHistory(); 
$payments = $paymentData['payments'] ?? [];
$totalSpent = $paymentData['totalSpent'] ?? 0;

$customerId = $user['customerId'] ?? 0;

// --- 2. LẤY DANH SÁCH ĐƠN HÀNG  ---
$queryOrders = "SELECT o.orderId, o.status, o.totalPrice, o.orderDate,
                o.subtotal, o.lensCost, o.shippingFee, o.discount,
                p.first_product_name, 
                p.product_image,
                p.has_prescription 
                FROM orders o
                LEFT JOIN (
                    SELECT oi.orderId, pr.name as first_product_name, pr.imagePath as product_image,
                    MAX(CASE WHEN pres.prescriptionId IS NOT NULL THEN 1 ELSE 0 END) as has_prescription 
                    FROM order_item oi
                    LEFT JOIN product_variant pv ON oi.variantId = pv.variantId
                    LEFT JOIN product pr ON pv.productId = pr.productId
                    LEFT JOIN prescription pres ON oi.orderItemId = pres.orderItemId 
                    GROUP BY oi.orderId
                ) p ON o.orderId = p.orderId
                WHERE o.customerId = :customerId 
                ORDER BY o.orderDate DESC";

$stmtOrders = $db->prepare($queryOrders);
$stmtOrders->execute([':customerId' => $customerId]);
$orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

// --- 2.5 LẤY YÊU CẦU ĐỔI TRẢ / KHIẾU NẠI ---
$returnRequests = [];
$stmtRequests = $db->prepare("SELECT rr.returnId, o.orderId, rr.reason, rr.note, rr.status, rr.imagePath, rr.requestDate
              FROM return_request rr
              JOIN order_item oi ON rr.orderItemId = oi.orderItemId
              JOIN orders o ON oi.orderId = o.orderId
              JOIN customers c ON o.customerId = c.customerId
              WHERE c.userId = :userId
              ORDER BY rr.requestDate DESC");
$stmtRequests->execute([':userId' => $userId]);
$returnRequestsData = $stmtRequests->fetchAll(PDO::FETCH_ASSOC);
foreach ($returnRequestsData as $req) {
    if (!isset($returnRequests[$req['orderId']])) {
        $req['request_type'] = getRequestTypeLabel($req['reason']);
        $returnRequests[$req['orderId']] = $req;
    }
}

// --- 3. LẤY CHI TIẾT ĐƠN HÀNG  ---
$orderId = $_GET['order_id'] ?? null;
$orderInfo = null;
$items = [];

if ($orderId) {
    $stmtDetail = $db->prepare("SELECT o.*, 
                                      o.subtotal, o.lensCost, o.shippingFee, o.discount
                               FROM orders o
                               WHERE o.orderId = :id AND o.customerId = :cid");
    $stmtDetail->execute([':id' => $orderId, ':cid' => $customerId]);
    $orderInfo = $stmtDetail->fetch(PDO::FETCH_ASSOC);

    if ($orderInfo) {
        $queryItems = "SELECT oi.*, 
                             p.name as product_name, 
                             p.imagePath as product_image, 
                             pr.leftEye, pr.rightEye, pr.leftPD, pr.rightPD,
                             pr.imagePath as prescription_image
                      FROM order_item oi
                      JOIN product_variant pv ON oi.variantId = pv.variantId
                      JOIN product p ON pv.productId = p.productId
                      LEFT JOIN prescription pr ON oi.orderItemId = pr.orderItemId
                      WHERE oi.orderId = :orderId";
        
        $stmtItems = $db->prepare($queryItems);
        $stmtItems->execute([':orderId' => $orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }
}

// --- 4. LẤY ĐƠN KÍNH MẪU (Lấy cái mới nhất bất kể nguồn nào) ---
$stmtPres = $db->prepare("SELECT * FROM prescription WHERE userId = :userId ORDER BY prescriptionId DESC LIMIT 1");
$stmtPres->execute([':userId' => $userId]);
$userPres = $stmtPres->fetch(PDO::FETCH_ASSOC);

// Kiểm tra xem dữ liệu JSON có hợp lệ không trước khi decode
$rightE = ($userPres && !empty($userPres['rightEye'])) ? json_decode($userPres['rightEye'], true) : [];
$leftE = ($userPres && !empty($userPres['leftEye'])) ? json_decode($userPres['leftEye'], true) : [];

// Tính tổng PD để hiện lên ở Profile
$pdVal = $userPres ? ($userPres['leftPD'] + $userPres['rightPD']) : '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ cá nhân - EYESGLASS</title>
    <link rel="stylesheet" href="/SELLING-GLASSES/public/assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <h3>Tài khoản cá nhân</h3>

        <div class="menu-item active" onclick="showTab('profile')" id="btn-profile">
            <i class="fas fa-user-circle"></i> Hồ sơ của bạn
        </div>

        <div class="menu-item" onclick="showTab('payment')" id="btn-payment">
            <i class="fas fa-credit-card"></i> Thanh toán
        </div>

        <div class="menu-item" onclick="showTab('orders')" id="btn-orders">
            <i class="fas fa-receipt"></i> Đơn hàng
        </div>

        <div class="menu-item" onclick="showTab('prescription')" id="btn-prescription">
            <i class="fas fa-glasses"></i> Đơn kính của tôi
        </div>
        
        <a href="/SELLING-GLASSES/public/home" class="menu-item" style="display: block; text-decoration: none; margin-top: 20px;">
            <i class="fas fa-home"></i> Về trang chủ
        </a>
    </aside>

    <main class="content">

        <section id="profile" class="tab <?= (!isset($_GET['order_id']) && ($_GET['tab'] ?? 'profile') === 'profile') ? 'active' : '' ?>">
            <h1>Hồ sơ của bạn</h1>
            <div class="profile-box glass">
                <div class="row">
                    <div>
                        <label>Tên</label>
                        <p id="nameText"><?php echo htmlspecialchars($_SESSION['user']['name'] ?? $user['name'] ?? ''); ?></p>
                        <input id="nameInput" class="hidden">
                    </div>
                    <button onclick="edit('name')">Sửa</button>
                </div>

                <div class="row">
                    <div>
                        <label>Email</label>
                        <p id="emailText"><?php echo htmlspecialchars($_SESSION['user']['email'] ?? $user['email'] ?? ''); ?></p>
                        <input id="emailInput" class="hidden">
                    </div>
                    <button onclick="edit('email')">Sửa</button>
                </div>

                <div class="row">
                    <div>
                        <label>SĐT</label>
                        <p id="phoneText"><?php echo htmlspecialchars($_SESSION['user']['phone'] ?? $user['phone'] ?? 'Chưa cập nhật'); ?></p>
                        <input id="phoneInput" class="hidden">
                    </div>
                    <button onclick="edit('phone')">Sửa</button>
                </div>

                <div class="row">
                    <div>
                        <label>Địa chỉ</label>
                        <p id="addressText"><?php echo htmlspecialchars($_SESSION['user']['address'] ?? $user['address'] ?? 'Chưa có địa chỉ'); ?></p>
                        <input id="addressInput" class="hidden">
                    </div>
                    <button onclick="edit('address')">Sửa</button>
                </div>
            </div>

            <div class="profile-box glass">
                <h3>Bảo mật</h3>
                <div class="row">
                    <div>
                        <label>Mật khẩu</label>
                        <p>••••••••</p>
                    </div>
                    <button onclick="openPassword()">Đổi</button>
                </div>
            </div>
        </section>

        <section id="payment" class="tab <?= ($_GET['tab'] ?? '') === 'payment' ? 'active' : '' ?>">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h1>Lịch sử thanh toán</h1>
                <div class="payment-badge">
                    <i class="fas fa-shield-alt"></i> Thanh toán bảo mật
                </div>
            </div>
            
            <div class="profile-box glass summary-payment-box">
                <div class="summary-item">
                    <p>Tổng chi tiêu</p>
                    <h2 class="total-amount"><?= number_format($totalSpent, 0, ',', '.') ?>đ</h2>
                </div>
                <div class="summary-info">
                    <p><i class="fas fa-info-circle"></i> Thông tin thanh toán được cập nhật sau khi nhân viên xác nhận giao dịch qua <strong>QR Code (Momo/Ngân hàng)</strong>.</p>
                </div>
            </div>

            <div class="profile-box glass" style="padding: 0; overflow: hidden;">
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Mã GD</th>
                            <th>Đơn hàng</th>
                            <th>Ngày giao dịch</th>
                            <th>Phương thức</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">Bạn chưa có giao dịch nào.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $p): ?>
                            <tr>
                                <td><span class="pay-id">#PAY<?= $p['paymentId'] ?></span></td>
                                <td><span class="order-ref">#<?= $p['orderId'] ?></span></td>
                                <td><?= date('d/m/Y', strtotime($p['orderDate'])) ?></td>
                                <td>
                                    <div class="method-tag">
                                        <?= htmlspecialchars($p['paymentMethod']) ?>
                                    </div>
                                </td>
                                <td class="amount-val negative">
                                    -<?= number_format($p['totalPrice'], 0, ',', '.') ?>đ
                                </td>
                                <td>
                                    <?php 
                                        $status = $p['paymentStatus'];
                                        // Map màu sắc theo đúng Enum 
                                        $statusClass = 'status-pending'; // Mặc định là Pending
                                        if ($status === 'Paid') $statusClass = 'status-success';
                                        if ($status === 'Failed') $statusClass = 'status-failed';
                                        if ($status === 'Refunded') $statusClass = 'status-refund';
                                        
                                        $statusText = $status;
                                        if ($status === 'Paid') $statusText = 'Thành công';
                                        if ($status === 'Pending') $statusText = 'Đang xác thực';
                                        if ($status === 'Failed') $statusText = 'Thất bại';
                                        if ($status === 'Refunded') $statusText = 'Đã hoàn tiền';
                                    ?>
                                    <span class="badge-pay <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="orders" class="tab <?= (isset($_GET['order_id']) || ($_GET['tab'] ?? '') === 'orders') ? 'active' : '' ?>">
            <?php if ($orderId): ?>
                <div class="order-detail-view glass">
                    <a href="?url=profile&tab=orders" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                    
                    <?php if ($orderInfo): ?>
                        <div class="detail-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h2>Đơn hàng #<?= $orderInfo['orderId'] ?></h2>
                            
                            <div class="header-actions" style="display: flex; align-items: center; gap: 10px;">
                                <?php $statusDetail = getStatusDetails($orderInfo['status']); ?>
                                <span class="status-badge <?= $statusDetail['class'] ?>" style="padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                    <?= $statusDetail['text'] ?>
                                </span>
                                <?php
                                    $statusRaw = strtolower($orderInfo['status'] ?? '');
                                    $existingOrderRequest = $returnRequests[$orderInfo['orderId']] ?? null;
                                ?>

                                <?php if ($statusRaw === 'pending'): ?>
                                    <button onclick="cancelOrder('<?= $orderInfo['orderId'] ?>')" 
                                            class="btn-cancel-order" 
                                            style="padding: 6px 15px; border: 1px solid #dc2626; color: #dc2626; background: white; border-radius: 6px; cursor: pointer; font-size: 12px; transition: 0.3s;">
                                        Hủy đơn hàng
                                    </button>
                                <?php endif; ?>

                                <?php if ($existingOrderRequest): ?>
                                    <div style="margin-left: 10px; margin-top: 8px; padding: 10px 14px; border-radius: 12px; background: #f4f4f4; color: #333; font-size: 0.95rem;">
                                        <strong>Yêu cầu <?= htmlspecialchars($existingOrderRequest['request_type']) ?>:</strong>
                                        <?= $existingOrderRequest['status'] === 'Pending' ? 'Đang chờ xử lý' : 'Đã hoàn tất' ?>
                                    </div>
                                <?php elseif (in_array($statusRaw, ['delivered', 'completed'])): ?>
                                    <button onclick="openReturnModal('<?= $orderInfo['orderId'] ?>')" 
                                            class="btn-return" 
                                            style="padding: 6px 14px; border-radius: 8px; font-size: 12px;">
                                        Trả hàng/ Hoàn tiền
                                    </button>

                                    <button onclick="openReviewPrompt('<?= $orderInfo['orderId'] ?>')" 
                                            class="btn-review" 
                                            style="padding: 6px 14px; border-radius: 8px; font-size: 12px;">
                                        Đánh giá
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="detail-list">
                            <?php 
                                // 1. Tính toán subtotal TRƯỚC khi vào vòng lặp hiển thị
                                $subtotal = 0;
                                foreach ($items as $item) {
                                    $subtotal += ($item['price'] * $item['quantity']);
                                }
                            ?>

                            <?php foreach ($items as $item): ?>
                                <div class="item-card-detail">
                                    <img src="/SELLING-GLASSES/public/assets/images/products/<?= $item['product_image'] ?>" class="product-img">
                                    
                                    <div class="product-info">
                                        <h4><?= $item['product_name'] ?></h4>
                                        <p class="product-type">Loại: Đơn cắt tròng / Có sẵn</p>
                                        <p class="product-qty">Số lượng: x<?= $item['quantity'] ?></p>
                                    </div>

                                    <div class="shipping-info">
                                        <p class="info-title"><i class="fas fa-truck"></i> Thông tin nhận hàng</p>
                                        <p><strong>Người nhận:</strong> <?= htmlspecialchars($user['name']) ?></p>
                                        <p><strong>SĐT:</strong> <?= htmlspecialchars($user['phone']) ?></p>
                                        <p><strong>Đ/C:</strong> <?= htmlspecialchars($user['address']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="price-info" style="text-align: right; margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                                <!-- Nếu database không lưu subtotal, tính lại từ items -->
                                <?php 
                                    $displaySubtotal = !empty($orderInfo['subtotal']) && $orderInfo['subtotal'] > 0 
                                        ? $orderInfo['subtotal'] 
                                        : $subtotal;
                                    $displayLensCost = $orderInfo['lensCost'] ?? 0;
                                    $displayShippingFee = $orderInfo['shippingFee'] ?? 0;
                                    $displayDiscount = $orderInfo['discount'] ?? 0;
                                ?>
                                
                                <p>Tiền hàng: <strong><?= number_format($displaySubtotal) ?>đ</strong></p>
                                
                                <?php if ($displayLensCost > 0): ?>
                                    <p>Chi phí tròng kính: <strong><?= number_format($displayLensCost) ?>đ</strong></p>
                                <?php endif; ?>
                                
                                <?php if ($displayShippingFee > 0): ?>
                                    <p>Phí vận chuyển: <strong><?= number_format($displayShippingFee) ?>đ</strong></p>
                                <?php endif; ?>
                                
                                <?php if ($displayDiscount > 0): ?>
                                    <p>Giảm giá: <strong style="color: #16a34a;">-<?= number_format($displayDiscount) ?>đ</strong></p>
                                <?php endif; ?>
                                
                                <h3 style="color: #dc2626; margin-top: 10px;">
                                    Tổng thanh toán: <?= number_format($orderInfo['totalPrice']) ?>đ
                                </h3>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-msg">
                            <p>Không tìm thấy thông tin đơn hàng.</p>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <h1>Lịch sử đơn hàng</h1>
                <div class="orders-container">
                    <?php if (empty($orders)): ?>
                        <p>Bạn chưa có đơn hàng nào.</p>
                    <?php else: foreach ($orders as $order): ?>
                        <?php $orderRequest = $returnRequests[$order['orderId']] ?? null; ?>
                        <div class="order-card-main" style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #eee; background: #fff; margin-bottom: 15px; border-radius: 12px;">
                            <div class="order-card-left" style="display: flex; align-items: center; gap: 15px;">
                                <div class="product-icon">
                                    <?php if (!empty($order['product_image'])): ?>
                                        <img src="/SELLING-GLASSES/public/assets/images/products/<?= $order['product_image'] ?>" 
                                            style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;"
                                            onerror="this.src='/SELLING-GLASSES/public/assets/images/products/default.jpg'">
                                    <?php else: ?>
                                        <i class="fas fa-box-open" style="font-size: 30px; color: #ddd;"></i>
                                    <?php endif; ?>
                                </div>

                                <div class="product-info">
                                    <span style="font-size: 13px; color: var(--primary); font-weight: bold;">#<?= $order['orderId'] ?></span>
                                    <span style="font-size: 12px; color: #999; margin-left: 10px;"><?= date("d/m/Y H:i", strtotime($order['orderDate'])) ?></span>
                                    <h4 style="margin: 5px 0;"><?= !empty($order['first_product_name']) ? $order['first_product_name'] : 'Sản phẩm trong đơn hàng' ?></h4>
                                    <p style="font-size: 12px; color: #ea580c; margin: 0;">Loại: Đơn cắt tròng / Có sẵn</p>
                                </div>
                            </div>

                            <div class="order-card-right" style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                                <?php $statusDetail = getStatusDetails($order['status']); ?>
                                <span class="status-badge <?= $statusDetail['class'] ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold;">
                                    <?= $statusDetail['text'] ?>
                                </span>

                                <?php if ($orderRequest): ?>
                                    <span style="font-size: 12px; color: #d97706;">Yêu cầu <?= htmlspecialchars($orderRequest['request_type']) ?>: <?= $orderRequest['status'] === 'Pending' ? 'Chờ xử lý' : 'Hoàn tất' ?></span>
                                <?php elseif (in_array(strtolower($order['status']), ['delivered', 'completed'])): ?>
                                    <button onclick="openReturnModal('<?= $order['orderId'] ?>')" style="margin-top: 4px; padding: 4px 10px; border: 1px solid #d97706; color: #d97706; background: white; border-radius: 6px; cursor: pointer; font-size: 11px;">Yêu cầu đổi/trả</button>
                                <?php endif; ?>

                                <div class="price-box">
                                    <p style="font-size: 12px; color: #666; margin: 0;">Tổng thanh toán:</p>
                                    <h3 style="margin: 2px 0; color: #000; font-size: 18px;"><?= number_format($order['totalPrice']) ?>đ</h3>
                                </div>

                                <div class="actions" style="margin-top: 5px;">
                                    <a href="?url=profile&tab=orders&order_id=<?= $order['orderId'] ?>" class="btn-chi-tiet" style="text-decoration: none; padding: 5px 15px; border: 1px solid #ddd; border-radius: 5px; color: #333; font-size: 12px;">Chi tiết</a>
                                    
                                    <?php if ($order['status'] === 'Pending'): ?>
                                        <button onclick="cancelOrder('<?= $order['orderId'] ?>')" style="margin-left: 5px; padding: 5px 10px; border: 1px solid #dc2626; color: #dc2626; background: none; border-radius: 5px; cursor: pointer; font-size: 12px;">Hủy đơn</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="prescription" class="tab">
            <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: 2rem; margin-bottom: 20px;">Số đo đơn kính</h1>
            
            <div class="profile-box glass">
                <p style="margin-bottom: 15px; font-size: 0.9rem; color: var(--text-muted);">
                    Lưu lại thông số độ mắt để đặt tròng kính chính xác hơn.
                </p>
                
                <form id="prescriptionForm" action="/SELLING-GLASSES/public/index.php?url=prescription-store" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="orderItemId" value="">
                    <table class="pres-table">
                        <thead>
                            <tr>
                                <th>Mắt</th>
                                <th>Cận/Viễn (SPH)</th>
                                <th>Loạn (CYL)</th>
                                <th>Trục (AXIS)</th>
                                <th>K/c đồng tử (PD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="eye-label">Mắt Phải (OD)</td>
                                <td><input type="text" name="rightEye_sph" class="pres-input" placeholder="0.00" value="0.00"></td>
                                <td><input type="text" name="rightEye_cyl" class="pres-input" placeholder="0.00" value="0.00"></td>
                                <td><input type="text" name="rightEye_axis" class="pres-input" placeholder="0" value="0"></td>
                                <td rowspan="2" class="pd-cell">
                                    <input type="text" name="pd" class="pres-input" placeholder="60-70" value="60-70">
                                </td>
                            </tr>
                            <tr>
                                <td class="eye-label">Mắt Trái (OS)</td>
                                <td><input type="text" name="leftEye_sph" class="pres-input" placeholder="0.00" value="0.00"></td>
                                <td><input type="text" name="leftEye_cyl" class="pres-input" placeholder="0.00" value="0.00"></td>
                                <td><input type="text" name="leftEye_axis" class="pres-input" placeholder="0" value="0"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="text-align: right; margin-top: 20px;">
                        <button type="submit" class="save-pres-btn">Lưu thông số</button>
                    </div>
            </div>

            <div class="profile-box glass" style="margin-top: 20px;">
                <h3 style="font-size: 1.1rem; font-weight: bold; margin-bottom: 10px;">Hình ảnh đơn thuốc (nếu có)</h3>
                <div class="upload-area">
                    <div style="margin-bottom: 10px;">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">Tải lên ảnh chụp kết quả khám mắt để chúng tôi kiểm tra</p>
                    <input type="file" name="prescription_img" style="font-size: 0.8rem;">
                </div>
            </div>
            </form> 
        </section>

    </main>
</div>

<div id="returnModal" class="modal">
    <div class="modal-box" style="width: 450px;">
        <h3 style="font-family: 'Playfair Display', serif; color: var(--primary);">Yêu cầu Đổi trả / Hoàn tiền</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">
            Đơn hàng: <span id="returnOrderId" font-weight: bold;>#14</span>
        </p>

        <div class="form-group">
            <label>Lý do hoàn trả</label>
            <select id="returnReason" style="width: 100%; margin-top: 5px;">
                <option value="">-- Chọn lý do --</option>
                <option value="Sản phẩm bị nứt, vỡ gọng/tròng">Sản phẩm bị nứt, vỡ gọng/tròng</option>
                <option value="Giao sai mẫu kính/màu sắc">Giao sai mẫu kính/màu sắc</option>
                <option value="Sai thông số độ cận/viễn">Sai thông số độ cận/viễn</option>
                <option value="Đeo không vừa (quá rộng/chật)">Đeo không vừa (quá rộng/chật)</option>
                <option value="Lý do khác...">Lý do khác...</option>
            </select>
        </div>

        <div class="form-group" style="margin-top: 15px;">
            <label>Ghi chú chi tiết</label>
            <textarea id="returnNote" placeholder="Mô tả thêm về tình trạng sản phẩm..." 
                style="width: 100%; height: 80px; margin-top: 5px; padding: 10px; border-radius: 10px; border: 1px solid #ddd; font-family: inherit; resize: none;"></textarea>
        </div>

        <div class="form-group" style="margin-top: 15px;">
            <label>Hình ảnh minh họa</label>
            <input id="returnImage" name="return_img" type="file" accept="image/*" style="font-size: 0.8rem; margin-top: 5px;">
        </div>

        <div class="modal-btn" style="margin-top: 20px;">
            <button onclick="closeReturnModal()">Hủy</button>
            <button class="save" onclick="confirmReturn()" style="background: #dc2626;">Gửi yêu cầu</button>
        </div>
    </div>
</div>

<div id="reviewModal" class="modal">
    <div class="modal-box" style="width: 500px;">
        <h3 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 15px;">Đánh Giá Sản Phẩm</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">
            Đơn hàng: <span id="reviewOrderId" style="font-weight: bold;">#14</span>
        </p>

        <div class="form-group">
            <label style="display: block; margin-bottom: 10px; font-weight: bold;">Đánh Giá (Sao)</label>
            <div id="starRating" style="display: flex; gap: 8px; margin-bottom: 15px;">
                <i class="fa-solid fa-star" data-rating="1" style="font-size: 28px; cursor: pointer; color: #ddd; transition: 0.3s;" onclick="setRating(1)"></i>
                <i class="fa-solid fa-star" data-rating="2" style="font-size: 28px; cursor: pointer; color: #ddd; transition: 0.3s;" onclick="setRating(2)"></i>
                <i class="fa-solid fa-star" data-rating="3" style="font-size: 28px; cursor: pointer; color: #ddd; transition: 0.3s;" onclick="setRating(3)"></i>
                <i class="fa-solid fa-star" data-rating="4" style="font-size: 28px; cursor: pointer; color: #ddd; transition: 0.3s;" onclick="setRating(4)"></i>
                <i class="fa-solid fa-star" data-rating="5" style="font-size: 28px; cursor: pointer; color: #ddd; transition: 0.3s;" onclick="setRating(5)"></i>
            </div>
            <input type="hidden" id="selectedRating" value="0">
            <p id="ratingText" style="font-size: 0.85rem; color: #d97706; min-height: 20px;">Vui lòng chọn số sao</p>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Nhận Xét (Tùy Chọn)</label>
            <textarea id="reviewComment" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." 
                style="width: 100%; height: 100px; padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; resize: none; font-size: 0.9rem;"></textarea>
            <p style="font-size: 0.75rem; color: #999; margin-top: 5px;">Tối đa 500 ký tự</p>
        </div>

        <div class="modal-btn" style="margin-top: 25px; gap: 10px;">
            <button onclick="closeReviewModal()" style="padding: 10px 20px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer; font-weight: 500;">Hủy</button>
            <button class="save" onclick="confirmReview()" style="padding: 10px 20px; background: #d97706; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">Gửi Đánh Giá</button>
        </div>
    </div>
</div>

<script src="/SELLING-GLASSES/public/assets/js/profile.js?v=<?= time() ?>"></script>
</body>
</html>