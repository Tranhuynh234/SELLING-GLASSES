# SELLING-GLASSES - Hệ Thống Bán Kính Mắt

## Mô Tả Dự Án

Hệ thống quản lý bán kính mắt được xây dựng với PHP MVC framework, cung cấp các tính năng quản lý sản phẩm, đơn hàng, khách hàng, nhân viên, khuyến mãi, đơn trả hàng, và thanh toán.

## Cấu Trúc Dự Án

### Cấu Trúc Thư Mục

```
SELLING-GLASSES/
├── app/
│   ├── controllers/          # Các controller xử lý logic
│   ├── core/                 # Lớp cơ sở (BaseModel)
│   ├── entities/             # Các lớp đại diện dữ liệu
│   ├── middleware/           # Middleware xác thực
│   ├── models/               # Các model tương tác với database
│   ├── services/             # Các service xử lý business logic
│   └── views/                # Các file view hiển thị giao diện
├── config/                   # Cấu hình (database, payment)
├── database/                 # File SQL khởi tạo database
├── public/                   # Thư mục public (CSS, JS, Images)
└── routes/                   # Định tuyến ứng dụng
```

### Sơ Đồ Use Case Hệ Thống

```
                    ┌─────────────────┐
                    │     ADMIN       │
                    │                 │
                    └────────┬────────┘
                             │
        ┌────────────────────┼────────────────────┐
        │ User Management    │ Product Management │
        │ Staff Management   │ Promotion Management
        │                    │
        └────────────────────┼────────────────────┘
                             │
                ┌────────────▼──────────────┐
                │  SELLING-GLASSES SYSTEM   │
                │                          │
                │ - Product Management     │
                │ - Order Management       │
                │ - Payment Processing     │
                │ - Cart Management        │
                │ - Review System          │
                │ - Shipment Tracking      │
                │ - Return Processing      │
                │ - Authentication         │
                │ - Prescription Storage   │
                └────────────┬─────────────┘
                             │
        ┌────────────────────┼─────────────────────┐
        │                    │                     │
        ▼                    ▼                     ▼
    ┌─────────┐         ┌──────────┐         ┌──────────────┐
    │ CUSTOMER │         │  SALES   │         │   MANAGER    │
    │          │         │  STAFF   │         │              │
    └────┬────┘         └────┬─────┘         └───────┬──────┘
         │                   │                       │
    ┌────┴─────────┐    ┌────┴──────┐         ┌──────┴────────┐
    │              │    │           │         │               │
Browse Product │ Add to Cart      │ Create Order    │ Monitor Orders
    │ View Details      │ Process Payment    │ Manage Inventory
    │ Add Reviews       │ Track Shipment     │ Approve Returns
    │ Check Orders      │ Handle Returns     │ View Reports
    │ Return Request    │ Contact Customer   │ Manage Staff
    │ Manage Profile    │                    │ Manage Promotion
    │              │    │                    │
    └──────────────┘    └────────────────────┘──────────────┘
                             │
                    ┌────────▼──────────┐
                    │ PAYMENT GATEWAY   │
                    │                   │
                    │ - Process Payment │
                    │ - Bank Transfer   │
                    │ - Verify Payment  │
                    │ - Refund Process  │
                    └───────────────────┘
```

### Sơ Đồ Tương Tác Chi Tiết

```
┌──────────┐
│ Customer │─── Browse Products ──────┐
│          │                          │
│          │◄─── Order Confirmation ──┤
│          │                          │
│          │─── Add to Cart ──────────┤
│          │                          │
│          │─── Checkout ─────────────┤
│          │                          │
│          │─── Track Order ──────────┤
│          │                          │
│          │─── Request Return ───────┤
│          │                          │
│          │─── Submit Review ────────┤
└──────────┘                          │
                                      ▼
                   ┌──────────────────────────────┐
                   │  SELLING-GLASSES SYSTEM      │
                   │                              │
                   │ Routes (web.php)             │
                   │ ├─ auth/register             │
                   │ ├─ auth/login                │
                   │ ├─ home                      │
                   │ ├─ products                  │
                   │ ├─ cart                      │
                   │ ├─ checkout                  │
                   │ ├─ order                     │
                   │ ├─ payment                   │
                   │ ├─ review                    │
                   │ ├─ prescription              │
                   │ ├─ return                    │
                   │ └─ ops/dashboard             │
                   │                              │
                   │ Database                     │
                   │ ├─ users                     │
                   │ ├─ customers                 │
                   │ ├─ products                  │
                   │ ├─ product_variants          │
                   │ ├─ orders                    │
                   │ ├─ payments                  │
                   │ ├─ shipments                 │
                   │ ├─ reviews                   │
                   │ ├─ promotions                │
                   │ └─ return_requests           │
                   └──────────────────────────────┘
                      │      │      │
        ┌─────────────┼──────┼──────┼──────────────┐
        │             │      │      │              │
        ▼             ▼      ▼      ▼              ▼
    ┌────────┐  ┌──────────┐  ┌────────┐  ┌──────────────┐
    │ ADMIN  │  │SALES STAFF│  │MANAGER │  │PAYMENT GATE  │
    └────────┘  └──────────┘  └────────┘  └──────────────┘
        │             │            │              │
    Manage All  Process Order  Oversee Ops   Process Payment
    Create/Edit  Handle Shipment Inventory      Bank API
    Staff/Product Return Request  Analytics    Verify Status
```

## Các Module Chính

### 1. **Xác Thực & Người Dùng (Authentication & Users)**

- **Controller**: `AuthController.php`, `UserController.php`
- **Model**: `userModel.php`
- **Entity**: `User.php`, `Customer.php`, `Staff.php`
- **Service**: `UserServices.php`
- **Chức năng**:
  - Đăng ký tài khoản
  - Đăng nhập
  - Quản lý hồ sơ người dùng
  - Phân quyền (Admin, Staff, Sales, Customer)

### 2. **Sản Phẩm (Products)**

- **Controller**: `ProductController.php`
- **Models**: `productModel.php`, `product_variantModel.php`, `categoryModel.php`
- **Entities**: `Product.php`, `ProductVariant.php`, `Category.php`
- **Service**: `ProductServices.php`
- **Chức năng**:
  - Quản lý sản phẩm kính mắt
  - Quản lý danh mục sản phẩm
  - Quản lý biến thể sản phẩm (màu sắc, size, v.v.)
  - Hiển thị sản phẩm chi tiết

### 3. **Giỏ Hàng (Shopping Cart)**

- **Controller**: `CartController.php`
- **Model**: `CartModel.php`
- **Entity**: `CartItem.php`
- **Service**: `CartService.php`
- **Chức năng**:
  - Thêm sản phẩm vào giỏ
  - Xóa sản phẩm khỏi giỏ
  - Cập nhật số lượng
  - Tính toán tổng tiền

### 4. **Đơn Hàng (Orders)**

- **Controller**: `OrderController.php`
- **Models**: `OrderModel.php`, `OrderItemModel.php`, `ShipmentModel.php`
- **Entities**: `Order.php`, `OrderItem.php`, `Shipment.php`
- **Service**: `OrderServices.php`
- **Chức năng**:
  - Tạo đơn hàng từ giỏ
  - Quản lý trạng thái đơn hàng
  - Theo dõi vận chuyển
  - Lịch sử đơn hàng

### 5. **Thanh Toán (Payment)**

- **Controller**: `PaymentController.php`
- **Model**: `PaymentModel.php`
- **Entity**: `Payment.php`
- **Service**: `PaymentService.php`
- **Chức năng**:
  - Xử lý thanh toán
  - Quản lý phương thức thanh toán
  - Lịch sử giao dịch

### 6. **Đơn Trả Hàng (Returns)**

- **Controller**: `ReturnController.php`
- **Model**: `ReturnRequestModel.php`
- **Entity**: `ReturnRequest.php`
- **Service**: `ReturnServices.php`
- **Chức năng**:
  - Yêu cầu trả hàng
  - Quản lý quy trình hoàn tiền
  - Theo dõi trạng thái trả hàng

### 7. **Nhân Viên (Staff)**

- **Controller**: `StaffController.php`
- **Model**: `StaffModel.php`
- **Entity**: `Staff.php`
- **Service**: `StaffServices.php`
- **Chức năng**:
  - Quản lý thông tin nhân viên
  - Phân công bán hàng

### 8. **Khuyến Mãi (Promotions)**

- **Controller**: `PromotionController.php`
- **Models**: `PromotionModel.php`, `PromotionProductModel.php`
- **Entities**: `Promotion.php`, `PromotionProduct.php`
- **Service**: `PromotionServices.php`
- **Chức năng**:
  - Tạo chương trình khuyến mãi
  - Áp dụng giảm giá
  - Quản lý sản phẩm trong khuyến mãi

### 9. **Đơn Kính Mắt (Prescriptions)**

- **Controller**: `PrescriptionController.php`
- **Models**: `PrescriptionModel.php`
- **Entity**: `Prescription.php`
- **Chức năng**:
  - Quản lý đơn kính mắt của khách hàng
  - Lưu trữ thông tin chi tiết kính

### 10. **Đánh Giá (Reviews)**

- **Controller**: `ReviewController.php`
- **Model**: `ReviewModel.php`
- **Entity**: `Review.php`
- **Service**: `ReviewService.php`
- **Chức năng**:
  - Cho phép khách hàng đánh giá sản phẩm
  - Quản lý đánh giá và xếp hạng

## 📡 Các API Endpoint Chính

### Authentication

| Method | Endpoint           | Mô Tả                 |
| ------ | ------------------ | --------------------- |
| POST   | `?action=register` | Đăng ký tài khoản mới |
| POST   | `?action=login`    | Đăng nhập             |
| GET    | `?action=logout`   | Đăng xuất             |
| GET    | `?action=profile`  | Xem hồ sơ người dùng  |

### Products

| Method | Endpoint                 | Mô Tả                          |
| ------ | ------------------------ | ------------------------------ |
| GET    | `?action=home`           | Trang chủ - Danh sách sản phẩm |
| GET    | `?action=product-detail` | Chi tiết sản phẩm              |
| GET    | `?action=all-products`   | Danh sách tất cả sản phẩm      |

### Cart

| Method   | Endpoint                   | Mô Tả                |
| -------- | -------------------------- | -------------------- |
| GET/POST | `?action=cart`             | Xem/Quản lý giỏ hàng |
| POST     | `?action=add-to-cart`      | Thêm vào giỏ         |
| POST     | `?action=remove-from-cart` | Xóa khỏi giỏ         |

### Orders

| Method   | Endpoint           | Mô Tả               |
| -------- | ------------------ | ------------------- |
| POST     | `?action=checkout` | Thanh toán đơn hàng |
| GET/POST | `?action=order`    | Quản lý đơn hàng    |

### Staff Dashboard

| Method | Endpoint      | Mô Tả                      |
| ------ | ------------- | -------------------------- |
| GET    | `?action=ops` | Dashboard quản lý vận hành |

## 🔧 Yêu Cầu Hệ Thống

- PHP 7.4+
- MySQL/MariaDB
- XAMPP (hoặc webserver tương tự)
- Composer (tùy chọn)

## Cơ Sở Dữ Liệu

Database SQL để khởi tạo được lưu tại: `database/selling_glasses.sql`

Để khôi phục database:

```bash
mysql -u root -p < database/selling_glasses.sql
```

## ⚙️ Cấu Hình

### Database Configuration

File: `config/db_connect.php`

- Cập nhật thông tin kết nối MySQL

### Payment Configuration

File: `config/payment_config.php`

- Cấu hình thông tin thanh toán (nếu sử dụng)

## Cách Chạy Ứng Dụng

### Bước 1: Chuẩn Bị Môi Trường

#### Yêu cầu cài đặt trước:

- **XAMPP** (hoặc wamp, lamp) - Download từ [https://www.apachefriends.org](https://www.apachefriends.org)
- **PHP 7.4 trở lên**
- **MySQL/MariaDB**
- **Git** (tùy chọn)

#### Cài đặt XAMPP:

1. Download XAMPP phiên bản mới nhất
2. Cài đặt vào ổ đĩa (ví dụ: `C:\xampp`)
3. Khởi động XAMPP Control Panel

### Bước 2: Sao Chép Project

```bash
# Nếu chưa có source code, sao chép thư mục SELLING-GLASSES vào:
C:\xampp\htdocs\

# Hoặc dùng git (nếu có repo):
cd C:\xampp\htdocs
git clone <repository_url> SELLING-GLASSES
```

Cấu trúc thư mục sau khi sao chép:

```
C:\xampp\htdocs\
├── SELLING-GLASSES/
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── routes/
│   └── README.md
```

### Bước 3: Cấu Hình Cơ Sở Dữ Liệu

#### 3.1. Khởi động MySQL từ XAMPP

1. Mở **XAMPP Control Panel**
2. Click nút **Start** bên cạnh **MySQL**
3. Chờ cho đến khi MySQL chuyển sang màu xanh

#### 3.2. Tạo Database

**Cách 1: Sử dụng phpMyAdmin**

1. Truy cập: `http://localhost/phpmyadmin`
2. Đăng nhập (mặc định: user `root`, không có password)
3. Click **New** để tạo database mới
4. Nhập tên: `selling_glasses` → Click **Create**
5. Chọn database `selling_glasses`
6. Click tab **Import**
7. Chọn file: `C:\xampp\htdocs\SELLING-GLASSES\database\selling_glasses.sql`
8. Click **Import**

**Cách 2: Sử dụng Command Line**

```bash
# Mở Command Prompt/PowerShell
# Di chuyển vào thư mục XAMPP
cd C:\xampp\mysql\bin

# Đăng nhập MySQL
mysql -u root -p

# Nếu được hỏi password, bấm Enter (mặc định không có password)

# Trong MySQL shell, chạy các lệnh:
CREATE DATABASE selling_glasses;
USE selling_glasses;
SOURCE C:/xampp/htdocs/SELLING-GLASSES/database/selling_glasses.sql;
EXIT;
```

### Bước 4: Cấu Hình Ứng Dụng

#### 4.1. Cấu hình Database (`config/db_connect.php`)

Chỉnh sửa file `C:\xampp\htdocs\SELLING-GLASSES\config\db_connect.php`:

```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');      // Hostname MySQL
define('DB_USER', 'root');           // Username MySQL
define('DB_PASSWORD', '');           // Password MySQL (mặc định để trống)
define('DB_NAME', 'selling_glasses'); // Tên database
define('DB_PORT', 3307);             // Port MySQL (mặc định 3306)

// Kết nối Database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
```

#### 4.2. Cấu hình Payment (`config/payment_config.php`) - Tùy chọn

Nếu sử dụng thanh toán trực tuyến, cấu hình thông tin cổng thanh toán:

```php
<?php
// Payment Gateway Configuration
define('PAYMENT_GATEWAY', 'your_payment_gateway');
define('PAYMENT_KEY', 'your_payment_key');
define('PAYMENT_SECRET', 'your_payment_secret');
?>
```

### Bước 5: Khởi Động Ứng Dụng

#### 5.1. Khởi động Apache từ XAMPP

1. Mở **XAMPP Control Panel**
2. Click nút **Start** bên cạnh **Apache**
3. Chờ cho đến khi Apache chuyển sang màu xanh

#### 5.2. Truy cập Ứng Dụng

Mở trình duyệt web và truy cập các URL sau:

**Trang chủ (Khách hàng):**

```
http://localhost:8088/SELLING-GLASSES/public/home
hoặc
http://localhost:8088/SELLING-GLASSES/public/index.php?action=home
```

**Đăng Nhập:**

```
http://localhost:8088/SELLING-GLASSES/public/auth
```

**Đăng Ký:**

```
http://localhost:8088/SELLING-GLASSES/public/auth
```

**Dashboard (Chỉ dành cho Staff/Admin):**

```
http://localhost:8088/SELLING-GLASSES/public/index.php?action=dashboard
hoặc
http://localhost:8088/SELLING-GLASSES/public/index.php?action=ops
```

**Sản phẩm:**

```
http://localhost:8088/SELLING-GLASSES/public/all-products
```

````

### Bước 6: Kiểm Tra Cấu Hình

#### Tạo file test.php để kiểm tra:

Tạo file `C:\xampp\htdocs\SELLING-GLASSES\test.php`:

```php
<?php
// Kiểm tra phiên bản PHP
echo "PHP Version: " . phpversion() . "<br>";

// Kiểm tra kết nối database
require_once 'config/db_connect.php';

if ($conn->ping()) {
    echo "✓ Database connection successful!<br>";
    echo "Database: selling_glasses<br>";
} else {
    echo "✗ Database connection failed: " . $conn->connect_error . "<br>";
}

// Kiểm tra các thư mục quan trọng
$directories = [
    'app/controllers',
    'app/models',
    'app/views',
    'config',
    'database',
    'public',
    'routes'
];

echo "<br><strong>Directory Check:</strong><br>";
foreach ($directories as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        echo "✓ " . $dir . "<br>";
    } else {
        echo "✗ " . $dir . " (NOT FOUND)<br>";
    }
}
?>
````

Truy cập: `http://localhost:8088/SELLING-GLASSES/test.php`

### Bước 7: Tạo Tài Khoản Test

Sau khi ứng dụng chạy thành công:

1. Truy cập: `http://localhost:8088/SELLING-GLASSES/public/index.php?action=register`
2. Điền thông tin:
   - **Tên**: Test User
   - **Email**: test@example.com
   - **Password**: Test@123
   - **Phone**: 0123456789
3. Click **Register**
4. Đăng nhập với email và password vừa tạo

### Bước 8: Tài Khoản Mặc Định (Nếu có)

Kiểm tra file `database/selling_glasses.sql` hoặc liên hệ admin để lấy thông tin tài khoản admin/staff mặc định.

Ví dụ tài khoản thường có:

- **Email**: test@gmail.com | **Password**: 123456

---

## Cách Sử Dụng

### 1. Trang Chủ & Duyệt Sản Phẩm

- Truy cập trang chủ để xem danh sách sản phẩm
- Click vào sản phẩm để xem chi tiết, giá cả, và hình ảnh
- Có thể lọc theo danh mục hoặc tìm kiếm sản phẩm

### 2. Đăng Ký & Đăng Nhập

- Truy cập trang đăng ký để tạo tài khoản mới
- Sử dụng email và mật khẩu để đăng nhập
- Hệ thống sẽ phân loại người dùng theo role (Admin, Manager, Sale, Customer)
- Sau khi đăng nhập, có thể xem lịch sử mua hàng và thông tin hồ sơ

### 3. Mua Hàng & Thanh Toán

- **Thêm vào giỏ**: Click nút "Add to Cart" trên sản phẩm
- **Xem giỏ**: Truy cập `?action=cart` để xem các sản phẩm đã chọn
- **Cập nhật số lượng**: Thay đổi số lượng trong giỏ hàng
- **Thanh toán**: Click nút "Checkout" để tiến hành thanh toán
- **Nhập địa chỉ**: Điền thông tin giao hàng
- **Chọn phương thức thanh toán**: Chọn từ các phương thức có sẵn
- **Hoàn tất**: Xác nhận đơn hàng

### 4. Quản Lý Đơn Hàng & Theo Dõi

- Truy cập `?action=order` để xem danh sách đơn hàng
- Xem chi tiết từng đơn hàng (trạng thái, sản phẩm, giá cả)
- Theo dõi vận chuyển - kiểm tra trạng thái giao hàng
- Yêu cầu trả hàng nếu cần

### 5. Đánh Giá & Nhận Xét

- Sau khi nhận hàng, khách hàng có thể đánh giá sản phẩm
- Ghi nhận xét chi tiết về sản phẩm
- Xếp hạng từ 1-5 sao

### 6. Quản Lý Đơn Kính (Prescriptions)

- Khách hàng có thể lưu trữ đơn kính mắt
- Theo dõi lịch sử đơn kính cũ
- Sử dụng đơn kính để gợi ý sản phẩm phù hợp

## Roles (Phân Quyền)

1. **Admin** - Quản trị viên hệ thống
2. **Manager** - Quản lý kho
3. **Sale** - Nhân viên bán hàng
4. **Customer** - Khách hàng

## Cấu Trúc Tệp Quan Trọng

- `public/index.php` - Entry point của ứng dụng
- `config/db_connect.php` - Kết nối cơ sở dữ liệu
- `app/core/BaseModel.php` - Lớp cơ sở cho các model
- `app/middleware/AuthMiddleware.php` - Xác thực người dùng

## Ghi Chú

- Ứng dụng sử dụng mô hình MVC
- Sử dụng Session để quản lý đăng nhập
- Các dữ liệu nhạy cảm được mã hóa
- Hỗ trợ các phương thức thanh toán khác nhau

---

## 📖 Chi Tiết Class - Thuộc tính và Phương thức

### ENTITY LAYER

#### class User {
    // Thuộc tính
    private $userId;           // ID người dùng
    private $name;             // Tên người dùng
    private $email;            // Email
    private $password;         // Mật khẩu (đã hash)
    private $phone;            // Số điện thoại
    private $role;             // Vai trò (Admin, Staff, Customer, Sale)
    
    // Phương thức
    public function __construct($data = [])
    public function getUserId()
    public function getName()
    public function getEmail()
    public function getPassword()
    public function getPhone()
    public function getRole()
    public function setName($name)
    public function setEmail($email)
    public function setPassword($password)
    public function setPhone($phone)
    public function setRole($role)
    public function toArray()                      // Chuyển đổi sang mảng
}

#### class Customer {
    // Thuộc tính
    private $customerId;       // ID khách hàng
    private $userId;           // ID người dùng (Foreign key)
    private $address;          // Địa chỉ giao hàng
    
    // Phương thức
    public function __construct($data = [])
    public function getCustomerId()
    public function getUserId()
    public function getAddress()
    public function setCustomerId($customerId)
    public function setUserId($userId)
    public function setAddress($address)
}

#### class Staff {
    // Thuộc tính
    private $staffId;          // ID nhân viên
    private $userId;           // ID người dùng (Foreign key)
    private $position;         // Chức vụ (manager, operation, sales)
    
    // Phương thức
    public function __construct($data = [])
    public function getStaffId()
    public function getUserId()
    public function getPosition()
    public function setUserId($userId)
    public function setPosition($position)
}

#### class Product {
    // Thuộc tính
    public $productId;         // ID sản phẩm
    public $variantId;         // ID biến thể
    public $name;              // Tên sản phẩm
    public $price;             // Giá
    public $stock;             // Số lượng tồn kho
    public $color;             // Màu sắc
    public $size;              // Kích cỡ
    public $description;       // Mô tả
    public $categoryId;        // ID danh mục
    public $imagePath;         // Đường dẫn hình ảnh
    public $staffId;           // ID nhân viên tạo
    
    // Phương thức
    public function __construct($data = [])
}

#### class ProductVariant {
    // Thuộc tính
    public $variantId;         // ID biến thể
    public $color;             // Màu sắc
    public $size;              // Kích cỡ
    public $price;             // Giá của biến thể
    public $stock;             // Số lượng tồn
    public $productId;         // ID sản phẩm (Foreign key)
    
    // Phương thức
    public function __construct($data = [])
}

#### class Category {
    // Thuộc tính
    public $categoryId;        // ID danh mục
    public $name;              // Tên danh mục
    
    // Phương thức
    public function __construct($data = [])
}

#### class Orders {
    // Thuộc tính
    public $orderId;           // ID đơn hàng
    public $customerId;        // ID khách hàng
    public $orderDate;         // Ngày tạo đơn (auto-generated)
    public $status;            // Trạng thái (Pending, Processing, Shipped, Delivered)
    public $totalPrice;        // Tổng giá tiền
    public $staffId;           // ID nhân viên xử lý
    
    // Phương thức
    public function __construct($data = [])
}

#### class OrderItem {
    // Thuộc tính
    public $orderItemId;       // ID item trong đơn
    public $quantity;          // Số lượng
    public $price;             // Giá từng item
    public $orderId;           // ID đơn hàng (Foreign key)
    public $variantId;         // ID biến thể sản phẩm
    
    // Phương thức
    public function __construct($data = [])
}

#### class Payment {
    // Thuộc tính
    public $paymentId;         // ID thanh toán
    public $paymentMethod;     // Phương thức thanh toán
    public $paymentStatus;     // Trạng thái thanh toán
    public $orderId;           // ID đơn hàng (Foreign key)
    public $transferNote;      // Ghi chú chuyển khoản
    public $approvedByStaffId; // ID nhân viên duyệt
    public $approvedAt;        // Thời gian duyệt
    
    // Phương thức
    public function __construct($data = [])
}

#### class Shipment {
    // Thuộc tính
    public $shipmentId;        // ID vận chuyển
    public $trackingCode;      // Mã vận đơn (tự động sinh)
    public $carrier;           // Hãng vận chuyển (mặc định: GHTK)
    public $status;            // Trạng thái vận chuyển
    public $staffId;           // ID nhân viên tạo
    public $orderId;           // ID đơn hàng (Foreign key)
    
    // Phương thức
    public function __construct($data = [])
}

#### class Review {
    // Thuộc tính
    public $reviewId;          // ID đánh giá
    public $customerId;        // ID khách hàng
    public $orderId;           // ID đơn hàng
    public $rating;            // Xếp hạng (1-5 sao)
    public $comment;           // Bình luận
    public $createdDate;       // Ngày tạo (auto-generated)
    
    // Phương thức
    public function __construct($customerId = null, $orderId = null, $rating = null, $comment = null)
}

#### class Promotion {
    // Thuộc tính
    public $promotionId;       // ID khuyến mãi
    public $name;              // Tên khuyến mãi
    public $discount;          // Tỉ lệ giảm giá (%)
    public $startDate;         // Ngày bắt đầu
    public $endDate;           // Ngày kết thúc
    public $staffId;           // ID nhân viên tạo
}

#### class CartItem {
    // Thuộc tính
    private $cartItemId;       // ID item trong giỏ
    private $name;             // Tên sản phẩm
    private $price;            // Giá
    private $quantity;         // Số lượng
    
    // Phương thức
    public function __construct($cartItemId, $name, $price, $quantity)
    public function getCartItemId()
    public function getName()
    public function getPrice()
    public function getQuantity()
}

#### class Prescription {
    // Thuộc tính
    public $prescriptionId;    // ID đơn kính
    public $orderItemId;       // ID item đơn hàng
    public $userId;            // ID người dùng
    public $leftEye;           // Chỉ số mắt trái
    public $rightEye;          // Chỉ số mắt phải
    public $leftPD;            // PD mắt trái
    public $rightPD;           // PD mắt phải
    public $imagePath;         // Đường dẫn ảnh đơn
    
    // Phương thức
    public function __construct($data = [])
    public function save($conn)                    // Lưu prescription vào DB
}

#### class ReturnRequest {
    // Thuộc tính
    public $returnId;          // ID yêu cầu trả
    public $reason;            // Lý do trả hàng
    public $status;            // Trạng thái (Requested, Approved, Refunded)
    public $requestDate;       // Ngày yêu cầu
    public $orderItemId;       // ID item đơn hàng
    public $staffId;           // ID nhân viên xử lý
    public $note;              // Ghi chú
    public $imagePath;         // Đường dẫn ảnh chứng minh
}

---

### CORE LAYER

#### class BaseModel {
    // Thuộc tính
    protected $conn;           // PDO database connection
    protected $table;          // Tên bảng trong database
    
    // Phương thức
    public function __construct()
    public function beginTransaction()             // Bắt đầu transaction
    public function commit()                       // Commit transaction
    public function rollBack()                     // Rollback transaction
    public function all()                          // Lấy tất cả records
    public function find($id, $primaryKey = "id")  // Tìm record theo primary key
    public function create($data)                  // Tạo record mới
    public function update($id, $data, $primaryKey = "userId")  // Cập nhật record
    public function delete($id, $primaryKey = "userId")        // Xóa record
    public function findBy($field, $value)                      // Tìm theo field custom
    public function queryAll($sql, $params = [])   // Execute SELECT query lấy tất cả
    public function queryOne($sql, $params = [])   // Execute SELECT query lấy 1 row
}

---

### MODEL LAYER

#### class UserModel extends BaseModel {
    // Thuộc tính
    protected $table = "users";
    
    // Phương thức
    public function findByEmail($email)            // Tìm người dùng theo email
    public function findUser($id)                  // Tìm người dùng theo ID
    public function getAllUsers()                  // Lấy tất cả người dùng
    public function searchUsers($keyword)          // Tìm kiếm theo name, email, phone
}

#### class ProductModel extends BaseModel {
    // Thuộc tính
    protected $table = "product";
    
    // Phương thức
    public function findByName($name)              // Tìm sản phẩm theo tên
    public function findProduct($id)               // Tìm sản phẩm theo ID
    public function getAllProducts()               // Lấy tất cả sản phẩm
    public function getProducts($limit, $offset)   // Lấy sản phẩm có phân trang
    public function addProduct($data)              // Thêm sản phẩm mới
    public function addVariant($data)              // Thêm biến thể sản phẩm
    public function deleteProductComplete($id)     // Xóa sản phẩm + variants (transaction)
    public function getAllProductsWithVariants()   // Lấy tất cả với variants
    public function deleteProduct($id)             // Xóa sản phẩm
    public function updateProduct($id, $data)      // Cập nhật sản phẩm
    public function deleteVariantsByProductId($id) // Xóa tất cả variants của product
    public function countProducts()                // Đếm tổng sản phẩm
}

#### class ProductVariantModel extends BaseModel {
    // Thuộc tính
    protected $table = "product_variant";
    
    // Phương thức
    public function findVariant($id)               // Tìm biến thể theo ID
    public function getVariantsByProductId($productId)  // Lấy tất cả variants của product
}

#### class CategoryModel extends BaseModel {
    // Thuộc tính
    protected $table = "category";
    
    // Phương thức
    public function findByName($name)              // Tìm danh mục theo tên
    public function findCategory($id)              // Tìm danh mục theo ID
    public function getAllCategories()             // Lấy tất cả danh mục
}

#### class CartModel {
    // Thuộc tính
    private $conn;
    
    // Phương thức
    public function __construct($conn)
    public function getCartByCustomer($customerId) // Lấy giỏ hàng của khách
    public function getCartDetailsByCustomer($customerId)  // Lấy chi tiết giỏ hàng
    public function getCartDetailsByCustomerAndIds($customerId, $cartItemIds)  // Lấy items cụ thể
    public function removeItemsByIds($customerId, $cartItemIds)                 // Xóa items
    public function addItem($cartId, $variantId, $quantity)                     // Thêm item
}

#### class CustomerModel extends BaseModel {
    // Thuộc tính
    protected $table = "customers";
    
    // Phương thức
    public function findCustomer($id)              // Tìm khách hàng theo ID
    public function getAllCustomers()              // Lấy tất cả khách hàng
    public function createCustomer($data)          // Tạo khách hàng mới
    public function updateCustomer($id, $data)     // Cập nhật khách hàng
    public function findByUserId($userId)          // Tìm khách hàng theo userID
    public function deleteByUserId($id)            // Xóa khách hàng theo userID
}

#### class OrderModel extends BaseModel {
    // Thuộc tính
    protected $table = "orders";
    protected $primaryKey = "orderId";
    
    // Phương thức
    public function findByCustomer($customerId)    // Lấy đơn hàng của khách
    public function findByStatus($status)          // Lọc đơn theo trạng thái
    public function findLatestOrderIdByUserId($userId)  // Lấy đơn mới nhất
    public function getOrderDetailWithCustomer($orderId) // Lấy chi tiết đơn + khách hàng
    public function updateOrder($orderId, $data)   // Cập nhật đơn hàng
    public function getOrdersForOps($status = null)     // Lấy đơn cho nhân viên vận hành
    public function getOrdersForSales($status = null)   // Lấy đơn cho nhân viên bán hàng
    public function countByStatus()                // Đếm đơn theo trạng thái
    public function createShipment($data)          // Tạo vận chuyển
    public function update($orderId, $data, $primaryKey = 'orderId')  // Cập nhật với custom key
    public function saveMessage($orderId, $senderType, $content)      // Lưu tin nhắn
    public function getMessagesByOrder($orderId)   // Lấy tin nhắn của đơn
}

#### class OrderItemModel extends BaseModel {
    // Thuộc tính
    protected $table = "order_item";
    
    // Phương thức
    public function findByOrderId($orderId)        // Lấy items của đơn hàng
}

#### class PaymentModel extends BaseModel {
    // Thuộc tính
    protected $table = "payment";
    
    // Phương thức
    public function findPayment($paymentId)        // Tìm thanh toán theo ID
    public function findByOrderId($orderId)        // Tìm thanh toán theo orderId
    public function createPayment($data)           // Tạo thanh toán mới
    public function updateStatusByOrderId($orderId, $status)  // Cập nhật trạng thái
    public function updatePayment($paymentId, $data)         // Cập nhật thanh toán
}

#### class ShipmentModel extends BaseModel {
    // Thuộc tính
    protected $table = "shipments";
    
    // Phương thức
    public function findByOrderId($orderId)        // Tìm vận chuyển theo orderId
    public function findByTrackingCode($trackingCode)     // Tìm theo mã vận đơn
    public function updateStatusByTrackingCode($trackingCode, $status)  // Cập nhật trạng thái
}

#### class ReviewModel {
    // Phương thức
    public function create($customerId, $orderId, $rating, $comment = '')  // Tạo review
    public function getAll()                       // Lấy tất cả reviews
    public function getByOrderId($orderId)         // Lấy review của đơn hàng
    public function getByCustomerId($customerId)   // Lấy reviews của khách
    public function getLatestForHomePage($limit = 5)     // Lấy latest reviews
    public function checkExistingReview($orderId)  // Kiểm tra đã review chưa
    public function deleteReview($reviewId)        // Xóa review
}

#### class StaffModel extends BaseModel {
    // Thuộc tính
    protected $table = "staff";
    
    // Phương thức
    public function findStaff($id)                 // Tìm nhân viên theo ID
    public function getAllStaff()                  // Lấy tất cả nhân viên
    public function createStaff($data)             // Tạo nhân viên mới
    public function updateStaff($id, $data)        // Cập nhật nhân viên
    public function findByUserId($userId)          // Tìm nhân viên theo userID
    public function deleteStaff($id)               // Xóa nhân viên
    public function deleteByUserId($userId)        // Xóa nhân viên theo userID
    public function isManagerByUserId($userId)     // Kiểm tra là manager không
    public function findByPosition($position)      // Tìm nhân viên theo chức vụ
}

#### class PromotionModel extends BaseModel {
    // Thuộc tính
    protected $table = "promotion";
    
    // Phương thức
    public function getPromotionDetail($id)        // Lấy chi tiết khuyến mãi
    public function searchPromotions($filters = [], $page = 1, $limit = 10)  // Tìm kiếm
    public function countSearch($filters = [])     // Đếm kết quả tìm kiếm
    public function getActivePromotionByProductId($productId)  // Lấy khuyến mãi active
    public function createPromotion($data)         // Tạo khuyến mãi mới
    public function updatePromotion($id, $data, $productIds = [])  // Cập nhật
    public function deletePromotion($id)           // Xóa khuyến mãi
    public function saveRelationProducts($promotionId, $productIds)  // Lưu liên kết products
}

#### class PrescriptionModel {
    // Phương thức
    public function uploadPrescription($data)      // Lưu đơn kính mắt
}

#### class ReturnRequestModel {
    // Phương thức
    public function createRequest($data)           // Tạo yêu cầu trả hàng
    public function fetchRequests($type = 'all')   // Lấy yêu cầu trả
    public function getRequestById($returnId)      // Lấy yêu cầu theo ID
    public function getRequestByOrderId($orderId)  // Lấy yêu cầu theo orderId
    public function updateRequestStatus($returnId, $newStatus)  // Cập nhật trạng thái
    public function updateOrderStatus($orderId, $status)        // Cập nhật trạng thái đơn
}

---

### SERVICE LAYER

#### class UserService {
    // Thuộc tính
    private $userModel;
    private $customerModel;
    private $staffModel;
    
    // Phương thức
    public function __construct()
    public function register($data)                // Đăng ký tài khoản (tạo User + Customer)
    public function login($email, $password)       // Đăng nhập
    public function getUserById($id)               // Lấy người dùng
    public function updateUser($id, $data)         // Cập nhật người dùng
    public function deleteUser($id)                // Xóa người dùng
    public function updateProfile($userId, $data)  // Cập nhật hồ sơ
    public function getUserByEmail($email)         // Tìm user theo email
    public function getAllUsers()                  // Lấy tất cả users
    public function searchUsers($keyword)          // Tìm kiếm users
    public function createUserByAdmin($data)       // Tạo user (admin)
    private function response($success, $message, $data = null)  // Format response
}

#### class ProductServices {
    // Thuộc tính
    private $productModel;
    private $variantModel;
    private $categoryModel;
    
    // Phương thức
    public function __construct()
    public function getAllCategories()             // Lấy tất cả danh mục
    public function addCategory($data)             // Thêm danh mục
    public function editCategory($id, $data)       // Sửa danh mục
    public function deleteCategory($id)            // Xóa danh mục
    public function getAllProducts()               // Lấy tất cả sản phẩm
    public function getProductDetail($id)          // Lấy chi tiết sản phẩm
    public function addProduct($data)              // Thêm sản phẩm
    public function editProduct($id, $data)        // Sửa sản phẩm
    public function deleteProduct($id)             // Xóa sản phẩm
    public function addVariant($data)              // Thêm biến thể
    public function updateVariant($variantId, $data)  // Cập nhật biến thể
    public function addFullProductAndVariants($postData, $fileData)  // Tạo product + variants
    public function updateFullProductAndVariants($id, $data, $files) // Cập nhật product + variants
}

#### class CartService {
    // Thuộc tính
    private $model;
    private $conn;
    
    // Phương thức
    public function __construct($conn)
    public function findOrCreateCartId($customerId)      // Lấy/tạo giỏ hàng
    public function updateItem($cartItemId, $quantity)   // Cập nhật số lượng
    public function removeItem($cartItemId)              // Xóa item
    public function getCart($customerId)                 // Lấy giỏ hàng
    public function addToCart($customerId, $variantId, $quantity)  // Thêm vào giỏ
}

#### class OrderService {
    // Thuộc tính
    private $orderModel;
    private $staffModel;
    
    // Phương thức
    public function __construct()
    public function createOrder($data)              // Tạo đơn hàng (với transaction)
    public function getOrdersByStatus($status)      // Lấy đơn theo trạng thái
    public function updateStatus($orderId, $status, $trackingCode = null)  // Cập nhật status
    public function getOrderDetail($orderId)        // Lấy chi tiết đơn
    public function getCustomerMessagesForUser($userId)  // Lấy messages của user
    public function getSupportUnreadCountForUser($userId) // Đếm unread messages
    public function sendCustomerMessageForUser($userId, $message)  // Gửi message
    public function returnOrder($orderId)           // Xử lý trả hàng
    public function getOrderStats()                 // Lấy thống kê đơn
    public function handleContactAndMessage($orderId, $message)  // Auto contact
    public function contactCustomer($orderId, $message)  // Liên hệ khách hàng
    public function getMessages($orderId)           // Lấy messages
    public function getConversationList()           // Lấy danh sách hội thoại
}

#### class PaymentService {
    // Thuộc tính
    private $conn;
    private $cartModel;
    private $customerModel;
    private $staffModel;
    private $userModel;
    private $orderModel;
    private $orderItemModel;
    private $paymentModel;
    private $paymentConfig;
    
    // Phương thức
    public function __construct()
    public function getCheckoutSummary($userId, $selectedCartItems = [])  // Lấy tóm tắt checkout
    public function createPendingPayment($userId, $payload)               // Tạo pending payment
    public function getAdminPayments($statusFilter = "pending")           // Lấy payments (admin)
    public function approvePayment($paymentId, $managerUserId)            // Duyệt payment
    private function response($success, $message, $data = null)           // Format response
    private function calculateSummary($items)                            // Tính tổng
}

#### class ReviewService {
    // Thuộc tính
    private $reviewModel;
    
    // Phương thức
    public function __construct()
    public function submitReview($customerId, $orderId, $rating, $comment = '')  // Submit review
    public function getAllReviews()                 // Lấy tất cả reviews
    public function getReviewsForHomePage($limit = 5)    // Lấy latest reviews
    public function getReviewByOrderId($orderId)    // Lấy review của đơn
    public function getReviewsByCustomerId($customerId)  // Lấy reviews của khách
    public function hasReview($orderId)             // Kiểm tra đã review chưa
}

#### class ReturnServices {
    // Thuộc tính
    private $promotionModel;
    private $promotionProductModel;
    private $prescriptionModel;
    private $returnModel;
    
    // Phương thức
    public function __construct($conn)
    public function requestReturn($data)            // Yêu cầu trả hàng
    public function getComplaints($type = 'all')    // Lấy danh sách trả hàng
    public function processRequest($returnId, $action)  // Xử lý yêu cầu trả
}

#### class StaffService {
    // Thuộc tính
    private $staffModel;
    private $userModel;
    private $customerModel;
    
    // Phương thức
    public function __construct()
    public function createOrUpdateStaff($userId, $position)  // Tạo/cập nhật staff
    public function deleteUser($userId)             // Xóa user
}

#### class PromotionService {
    // Thuộc tính
    private $promotionModel;
    
    // Phương thức
    public function __construct()
    public function createPromotion($data)          // Tạo khuyến mãi
    public function updatePromotion($id, $data)     // Cập nhật khuyến mãi
    public function deletePromotion($id)            // Xóa khuyến mãi
    public function getPromotionDetail($id)         // Lấy chi tiết
    public function searchPromotions($filters = [], $page = 1, $limit = 10)  // Tìm kiếm
    public function getActivePromotionByProduct($productId)  // Lấy khuyến mãi active
    public function applyPromotion($promotionId, $productIds) // Áp dụng khuyến mãi
    private function response($success, $message, $data = null)  // Format response
}

#### class HomeService {
    // Thuộc tính
    private $productModel;
    private $reviewModel;
    
    // Phương thức
    public function __construct()
    public function getHomeData($page = 1, $limit = 8)  // Lấy dữ liệu trang chủ
    public function getLatestReviews($limit = 5)    // Lấy reviews mới nhất
}

---

### CONTROLLER LAYER

#### class AuthController {
    // Thuộc tính
    private $userService;
    
    // Phương thức
    public function __construct()
    public function register()                      // Xử lý đăng ký
    public function login()                         // Xử lý đăng nhập
    public function logout()                        // Xử lý đăng xuất
    public function updateProfile()                 // Cập nhật hồ sơ
    public function profile()                       // Hiển thị hồ sơ
    public function showLogin()                     // Hiển thị trang login
}

#### class ProductController {
    // Thuộc tính
    private $productService;
    
    // Phương thức
    public function __construct()
    public function getAllCategories()              // Lấy tất cả danh mục
    public function addCategory()                   // Thêm danh mục
    public function updateCategory($id)             // Cập nhật danh mục
    public function deleteCategory($id)             // Xóa danh mục
    public function addProduct()                    // Thêm sản phẩm
    public function updateProduct($id)              // Cập nhật sản phẩm
    public function deleteProduct($id)              // Xóa sản phẩm
    public function addVariant()                    // Thêm biến thể
    public function updateVariant($variantId)       // Cập nhật biến thể
    public function index()                         // Danh sách sản phẩm
    public function detail($id)                     // Chi tiết sản phẩm
    private function handleError($message)          // Xử lý lỗi
}

#### class CartController {
    // Thuộc tính
    private $cartService;
    private $conn;
    
    // Phương thức
    public function __construct()
    public function getCustomerId()                 // Lấy customerId từ session
    public function showCartPage()                  // Hiển thị trang giỏ
    public function showCheckoutPage()              // Hiển thị trang checkout
    public function getCart()                       // Lấy giỏ hàng (JSON)
    public function add()                           // Thêm vào giỏ
    public function update()                        // Cập nhật số lượng
    public function remove()                        // Xóa item
    public function getCheckoutSummary()            // Lấy tóm tắt checkout
    public function checkout()                      // Xử lý checkout
}

#### class OrderController {
    // Thuộc tính
    private $orderService;
    private $orderModel;
    
    // Phương thức
    public function __construct()
    public function create()                        // Tạo đơn hàng
    public function getByStatus()                   // Lấy đơn theo trạng thái
    public function updateStatus()                  // Cập nhật trạng thái đơn
    public function stats()                         // Lấy thống kê đơn
    public function getOrderDetail()                // Lấy chi tiết đơn
    public function handleAutoContact()             // Auto contact
    public function contactCustomer()               // Gửi message tới khách
    public function getCustomerMessages()           // Lấy messages
    public function getSupportUnreadCount()         // Đếm unread messages
    public function sendCustomerMessage()           // Gửi message
    public function getMessages()                   // Lấy messages
    public function getConversationList()           // Lấy danh sách hội thoại
    public function showDetail($orderId)            // Hiển thị chi tiết đơn
    public function cancelOrder()                   // Hủy đơn hàng
}

#### class PaymentController {
    // Thuộc tính
    private $paymentService;
    private $staffModel;
    
    // Phương thức
    public function __construct()
    public function showCheckoutPage()              // Hiển thị trang checkout
    public function getCheckoutSummary()            // Lấy tóm tắt checkout
    public function createPendingPayment()          // Tạo pending payment
    public function showAdminPage()                 // Hiển thị trang admin
    public function getPaymentRequests()            // Lấy requests thanh toán
    public function approvePayment()                // Duyệt thanh toán
    public function getPaymentHistory()             // Lấy lịch sử thanh toán
    private function sendJson($data)                // Gửi JSON response
    private function requireManager($json = true)   // Kiểm tra quyền manager
}

#### class UserController {
    // Thuộc tính
    private $userService;
    
    // Phương thức
    public function __construct()
    public function setUserService($userService)    // Set user service
    public function getUserService()                // Get user service
    public function getAllUsers()                   // Lấy tất cả users
    public function searchUsers()                   // Tìm kiếm users
    public function updateUser()                    // Cập nhật user
    public function deleteUser()                    // Xóa user
    public function createUser()                    // Tạo user mới
    public function updatePrescription()            // Cập nhật đơn kính
    public function updateProfile()                 // Cập nhật hồ sơ
    public function changePassword()                // Thay đổi mật khẩu
}

#### class ReviewController {
    // Thuộc tính
    private $reviewService;
    
    // Phương thức
    public function __construct()
    public function submitReview()                  // Submit đánh giá
    public function getReviews()                    // Lấy đánh giá
    public function getReviewByOrder()              // Lấy đánh giá theo đơn
}

#### class ReturnController {
    // Thuộc tính
    private $service;
    
    // Phương thức
    public function __construct()
    public function requestReturn()                 // Yêu cầu trả hàng
    public function getComplaints()                 // Lấy danh sách trả hàng
    public function processRequest()                // Xử lý yêu cầu
}

#### class StaffController {
    // Thuộc tính
    private $staffService;
    private $userService;
    
    // Phương thức
    public function __construct()
    public function save()                          // Lưu thông tin nhân viên
    public function delete()                        // Xóa nhân viên
}

#### class PromotionController {
    // Thuộc tính
    private $promotionService;
    
    // Phương thức
    public function __construct()
    public function createPromotion()               // Tạo khuyến mãi
    public function updatePromotion()               // Cập nhật khuyến mãi
    public function deletePromotion()               // Xóa khuyến mãi
    public function getPromotionDetail()            // Lấy chi tiết
    public function searchPromotions()              // Tìm kiếm khuyến mãi
    public function getActivePromotionByProduct()   // Lấy khuyến mãi active
    public function applyPromotion()                // Áp dụng khuyến mãi
    private function getJsonInput()                 // Lấy JSON input
}

#### class PrescriptionController {
    // Thuộc tính
    private $conn;
    
    // Phương thức
    public function __construct($dbConnection)
    public function create()                        // Hiển thị form đơn kính
    public function store()                         // Lưu đơn kính
}

#### class HomeController {
    // Thuộc tính
    private $homeService;
    
    // Phương thức
    public function __construct()
    public function index()                         // Hiển thị trang chủ
}
