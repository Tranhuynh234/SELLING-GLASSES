<?php
// Lưu ý: Không cần session_start() hay check đăng nhập ở đây nữa, 
// vì cái hàm profile() trong AuthController nó đã làm dùm rồi!

$db = Database::connect();
$userId = $_SESSION['user']['userId'];

// 1. Lấy thông tin user
$queryUser = "SELECT u.name, u.email, u.phone, c.address 
              FROM users u 
              LEFT JOIN customers c ON u.userId = c.userId 
              WHERE u.userId = :userId";
$stmtUser = $db->prepare($queryUser);
$stmtUser->execute([':userId' => $userId]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// 2. Lấy đơn hàng
$queryOrders = "SELECT orderId, status, totalPrice 
                FROM orders 
                WHERE customerId = (SELECT customerId FROM customers WHERE userId = :userId)";
$stmtOrders = $db->prepare($queryOrders);
$stmtOrders->execute([':userId' => $userId]);
$orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
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
        
        <a href="/SELLING-GLASSES/public/home" class="menu-item" style="display: block; text-decoration: none; margin-top: 20px;">
            <i class="fas fa-home"></i> Về trang chủ
        </a>
    </aside>

    <main class="content">

        <section id="profile" class="tab active">
            <h1>Hồ sơ của bạn</h1>
            <div class="profile-box glass">
                <div class="row">
                    <div>
                        <label>Tên</label>
                        <p id="nameText"><?php echo htmlspecialchars($user['name'] ?? ''); ?></p>
                        <input id="nameInput" class="hidden">
                    </div>
                    <button onclick="edit('name')">Sửa</button>
                </div>

                <div class="row">
                    <div>
                        <label>Email</label>
                        <p id="emailText"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                        <input id="emailInput" class="hidden">
                    </div>
                    <button onclick="edit('email')">Sửa</button>
                </div>

                <div class="row">
                    <div>
                        <label>SĐT</label>
                        <p id="phoneText"><?php echo htmlspecialchars($user['phone'] ?? 'Chưa cập nhật'); ?></p>
                        <input id="phoneInput" class="hidden">
                    </div>
                    <button onclick="edit('phone')">Sửa</button>
                </div>

                <div class="row">
                    <div>
                        <label>Địa chỉ</label>
                        <p id="addressText"><?php echo htmlspecialchars($user['address'] ?? 'Chưa có địa chỉ'); ?></p>
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

        <section id="payment" class="tab">
            <h1>Thanh toán</h1>
            <div class="profile-box glass">
                <p>Chưa có phương thức thanh toán</p>
            </div>
        </section>

        <section id="orders" class="tab">
            <h1>Đơn hàng</h1>
            <div class="profile-box glass">
                <?php if (empty($orders)): ?>
                    <p>Bạn chưa có đơn hàng nào.</p>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order">
                            <p>#<?php echo htmlspecialchars($order['orderId']); ?> - <?php echo htmlspecialchars($order['status']); ?></p>
                            <span><?php echo number_format($order['totalPrice'], 0, ',', '.'); ?>đ</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>
</div>

<div id="passwordModal" class="modal">
    <div class="modal-box">
        <h3>Đổi mật khẩu</h3>
        <input type="password" id="oldPass" placeholder="Mật khẩu cũ">
        <input type="password" id="newPass" placeholder="Mật khẩu mới">
        <input type="password" id="confirmPass" placeholder="Xác nhận mật khẩu">
        <div class="modal-btn">
            <button onclick="closePassword()">Hủy</button>
            <button class="save" onclick="savePassword()">Lưu</button>
        </div>
    </div>
</div>

<script src="/SELLING-GLASSES/public/assets/js/profile.js"></script>
</body>
</html>