-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th4 19, 2026 lúc 11:48 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `selling_glasses`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `createdDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`cartId`, `customerId`, `createdDate`) VALUES
(8, 25, '2026-04-19 12:19:36'),
(9, 21, '2026-04-19 13:39:24'),
(57, 1210546, '2026-04-19 14:02:03'),
(58, 1539703, '2026-04-19 14:02:06'),
(59, 1688896, '2026-04-19 14:02:07'),
(60, 1437710, '2026-04-19 14:02:10'),
(61, 1215168, '2026-04-19 14:02:10'),
(62, 1290133, '2026-04-19 14:02:10'),
(63, 1784336, '2026-04-19 14:02:11'),
(64, 1184314, '2026-04-19 14:02:19'),
(65, 1294173, '2026-04-19 14:02:19'),
(66, 1129655, '2026-04-19 14:02:19'),
(67, 1713346, '2026-04-19 14:02:22'),
(68, 1348239, '2026-04-19 14:02:26'),
(69, 1065808, '2026-04-19 14:02:27'),
(70, 1300639, '2026-04-19 14:02:27'),
(71, 1020597, '2026-04-19 14:02:29'),
(72, 1055638, '2026-04-19 14:02:40'),
(73, 1701011, '2026-04-19 14:02:49'),
(74, 1754476, '2026-04-19 14:02:51'),
(75, 1751525, '2026-04-19 14:02:53'),
(76, 1500493, '2026-04-19 14:02:55'),
(77, 1432593, '2026-04-19 14:02:55'),
(78, 1498173, '2026-04-19 14:02:55'),
(79, 1624975, '2026-04-19 14:02:55'),
(80, 1639389, '2026-04-19 14:02:59'),
(81, 1298750, '2026-04-19 14:02:59'),
(82, 1220286, '2026-04-19 14:02:59'),
(83, 1588851, '2026-04-19 14:02:59'),
(84, 1557301, '2026-04-19 14:02:59'),
(85, 1234233, '2026-04-19 14:02:59'),
(86, 1411524, '2026-04-19 14:02:59'),
(87, 1536484, '2026-04-19 14:03:00'),
(88, 1808223, '2026-04-19 14:03:10'),
(89, 1040187, '2026-04-19 14:03:11'),
(90, 1214705, '2026-04-19 14:03:17'),
(91, 1589683, '2026-04-19 14:03:18'),
(92, 1454816, '2026-04-19 14:06:22'),
(93, 1140758, '2026-04-19 14:06:24'),
(94, 1442284, '2026-04-19 14:06:26'),
(95, 1605493, '2026-04-19 14:06:26'),
(96, 1873933, '2026-04-19 14:06:26'),
(97, 1081712, '2026-04-19 14:06:26'),
(98, 1782308, '2026-04-19 14:06:26'),
(99, 1101674, '2026-04-19 14:06:26'),
(100, 1552649, '2026-04-19 14:06:26'),
(101, 1376100, '2026-04-19 14:06:26'),
(102, 1417116, '2026-04-19 14:06:27'),
(103, 1868821, '2026-04-19 14:06:27'),
(104, 1243868, '2026-04-19 14:06:27'),
(105, 1289332, '2026-04-19 14:06:27'),
(106, 1290171, '2026-04-19 14:06:27'),
(107, 1823640, '2026-04-19 14:06:27'),
(108, 1123950, '2026-04-19 14:06:34'),
(109, 1701343, '2026-04-19 14:06:41'),
(110, 1301449, '2026-04-19 14:06:42'),
(111, 1321375, '2026-04-19 14:06:55'),
(112, 27, '2026-04-19 14:53:41'),
(113, 24, '2026-04-19 17:09:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_item`
--

CREATE TABLE `cart_item` (
  `cartItemId` int(11) NOT NULL,
  `cartId` int(11) NOT NULL,
  `variantId` int(11) DEFAULT NULL,
  `comboId` int(11) DEFAULT NULL,
  `quantity` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_item`
--

INSERT INTO `cart_item` (`cartItemId`, `cartId`, `variantId`, `comboId`, `quantity`) VALUES
(3, 8, 7, NULL, 1),
(4, 8, 6, NULL, 1),
(5, 8, 2, NULL, 2),
(6, 8, NULL, 12, 2),
(7, 8, 5, NULL, 2),
(12, 57, 8, NULL, 1),
(13, 58, 7, NULL, 1),
(14, 59, 7, NULL, 1),
(15, 60, 6, NULL, 1),
(16, 61, 6, NULL, 1),
(17, 62, 6, NULL, 1),
(18, 63, 6, NULL, 1),
(19, 64, 8, NULL, 1),
(20, 65, 8, NULL, 1),
(21, 66, 8, NULL, 1),
(22, 67, 7, NULL, 1),
(23, 68, 8, NULL, 1),
(24, 69, 8, NULL, 1),
(25, 70, 8, NULL, 1),
(26, 71, 7, NULL, 1),
(27, 72, 2, NULL, 1),
(28, 73, 6, NULL, 1),
(29, 74, 5, NULL, 1),
(30, 75, 5, NULL, 1),
(31, 76, 5, NULL, 1),
(32, 77, 5, NULL, 1),
(33, 78, 5, NULL, 1),
(34, 79, 5, NULL, 1),
(35, 80, 4, NULL, 1),
(36, 81, 4, NULL, 1),
(37, 82, 4, NULL, 1),
(38, 83, 4, NULL, 1),
(39, 84, 4, NULL, 1),
(40, 85, 4, NULL, 1),
(41, 86, 4, NULL, 1),
(42, 87, 4, NULL, 1),
(43, 88, 3, NULL, 1),
(44, 89, 3, NULL, 1),
(45, 90, 3, NULL, 1),
(46, 91, 3, NULL, 1),
(47, 92, 8, NULL, 1),
(48, 93, 7, NULL, 1),
(49, 94, 7, NULL, 1),
(50, 95, 7, NULL, 1),
(51, 96, 7, NULL, 1),
(52, 97, 7, NULL, 1),
(53, 98, 7, NULL, 1),
(54, 99, 7, NULL, 1),
(55, 100, 7, NULL, 1),
(56, 101, 7, NULL, 1),
(57, 102, 7, NULL, 1),
(58, 103, 7, NULL, 1),
(59, 104, 7, NULL, 1),
(60, 105, 7, NULL, 1),
(61, 106, 7, NULL, 1),
(62, 107, 7, NULL, 1),
(63, 108, 6, NULL, 1),
(64, 109, 5, NULL, 1),
(65, 110, 5, NULL, 1),
(66, 111, 8, NULL, 1),
(67, 112, 7, NULL, 2),
(68, 112, 5, NULL, 1),
(69, 112, 6, NULL, 1),
(72, 9, 2, NULL, 1),
(73, 9, 6, NULL, 1),
(75, 113, 4, NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`categoryId`, `name`) VALUES
(4, 'Chống Ánh Sáng Xanh'),
(1, 'Gọng Nam\r\n'),
(2, 'Gọng Nữ'),
(6, 'Gọng Siêu Mỏng'),
(3, 'Gọng Trẻ Em'),
(5, 'Kính Đổi Màu');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `combo`
--

CREATE TABLE `combo` (
  `comboId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1,
  `staffId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deletedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `combo`
--

INSERT INTO `combo` (`comboId`, `name`, `description`, `imagePath`, `price`, `isActive`, `staffId`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
(12, 'Mùa Hè', 'Tới hè mới sài nha', NULL, 800000.00, 1, 10, '2026-04-18 15:39:03', '2026-04-18 17:14:13', NULL),
(13, 'Mùa Đông', 'Thấy lạnh xách ra đeo dô', 'combo_1776533191_69e3bec773d56.jpg', 1000000.00, 1, 10, '2026-04-18 15:40:37', '2026-04-19 07:29:09', NULL),
(14, 'Mùa xuân', 'mùa xuân', NULL, 500000.00, 0, 10, '2026-04-19 09:43:49', '2026-04-19 09:44:05', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `combo_item`
--

CREATE TABLE `combo_item` (
  `comboItemId` int(11) NOT NULL,
  `comboId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` smallint(6) NOT NULL DEFAULT 1,
  `sortOrder` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `combo_item`
--

INSERT INTO `combo_item` (`comboItemId`, `comboId`, `productId`, `quantity`, `sortOrder`) VALUES
(85, 12, 12, 1, 0),
(86, 12, 11, 1, 1),
(113, 13, 11, 1, 0),
(114, 13, 1, 1, 3),
(117, 13, 3, 1, 1),
(118, 13, 10, 1, 2),
(132, 14, 12, 1, 0),
(133, 14, 11, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `customerId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`customerId`, `userId`, `address`) VALUES
(21, 21, '123,ABC, ABC, ABC, Ha Noi'),
(24, 29, '123, 123, 123, Ho Chi Minh'),
(25, 30, '123,ABC, ABC, ABC, Ho Chi Minh'),
(26, 31, '123,ABC, ABC, ABC, Ho Chi Minh'),
(27, 33, NULL),
(28, 34, NULL),
(1020597, NULL, NULL),
(1040187, NULL, NULL),
(1055638, NULL, NULL),
(1065808, NULL, NULL),
(1081712, NULL, NULL),
(1101674, NULL, NULL),
(1123950, NULL, NULL),
(1129655, NULL, NULL),
(1140758, NULL, NULL),
(1184314, NULL, NULL),
(1210546, NULL, NULL),
(1214705, NULL, NULL),
(1215168, NULL, NULL),
(1220286, NULL, NULL),
(1234233, NULL, NULL),
(1243868, NULL, NULL),
(1289332, NULL, NULL),
(1290133, NULL, NULL),
(1290171, NULL, NULL),
(1294173, NULL, NULL),
(1298750, NULL, NULL),
(1300639, NULL, NULL),
(1301449, NULL, NULL),
(1321375, NULL, NULL),
(1348239, NULL, NULL),
(1376100, NULL, NULL),
(1411524, NULL, NULL),
(1417116, NULL, NULL),
(1432593, NULL, NULL),
(1437710, NULL, NULL),
(1442284, NULL, NULL),
(1454816, NULL, NULL),
(1498173, NULL, NULL),
(1500493, NULL, NULL),
(1536484, NULL, NULL),
(1539703, NULL, NULL),
(1552649, NULL, NULL),
(1557301, NULL, NULL),
(1588851, NULL, NULL),
(1589683, NULL, NULL),
(1605493, NULL, NULL),
(1624975, NULL, NULL),
(1639389, NULL, NULL),
(1688896, NULL, NULL),
(1701011, NULL, NULL),
(1701343, NULL, NULL),
(1713346, NULL, NULL),
(1751525, NULL, NULL),
(1754476, NULL, NULL),
(1782308, NULL, NULL),
(1784336, NULL, NULL),
(1808223, NULL, NULL),
(1823640, NULL, NULL),
(1868821, NULL, NULL),
(1873933, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `sender_type` enum('Staff','Customer') NOT NULL,
  `message_content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_read_by_staff` tinyint(1) DEFAULT 0,
  `is_read_by_customer` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`message_id`, `order_id`, `sender_type`, `message_content`, `created_at`, `is_read_by_staff`, `is_read_by_customer`) VALUES
(1, 46, 'Customer', 'hi', '2026-04-19 16:16:29', 0, 1),
(2, 48, 'Staff', 'Chào Thien Tru, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #48 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-04-19 16:45:21', 1, 0),
(3, 49, 'Staff', 'Chào Thien Tru, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #49 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-04-19 16:49:05', 1, 1),
(4, 49, 'Customer', 'helo', '2026-04-19 17:05:23', 1, 1),
(5, 50, 'Customer', 'hi', '2026-04-19 17:12:07', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `orderId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `status` enum('Pending','Confirmed','Processing','Shipped','Delivered','Cancelled','Returned') NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `staffId` int(11) DEFAULT NULL,
  `is_contacted` tinyint(1) NOT NULL DEFAULT 0,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `lensCost` decimal(10,2) DEFAULT 0.00,
  `shippingFee` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`orderId`, `customerId`, `orderDate`, `status`, `totalPrice`, `staffId`, `is_contacted`, `subtotal`, `lensCost`, `shippingFee`, `discount`) VALUES
(25, 25, '2026-04-16 07:25:12', 'Pending', 250000.00, NULL, 0, 0.00, 0.00, 0.00, 0.00),
(31, 25, '2026-04-17 11:28:36', 'Delivered', 680000.00, NULL, 0, 0.00, 0.00, 0.00, 0.00),
(39, 25, '2026-04-18 07:34:12', 'Pending', 180000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(40, 25, '2026-04-18 07:37:18', 'Pending', 580000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(41, 25, '2026-04-18 07:41:32', 'Cancelled', 560000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(42, 25, '2026-04-18 07:46:51', 'Cancelled', 610000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(43, 25, '2026-04-18 07:52:03', 'Pending', 450000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(44, 25, '2026-04-18 07:58:32', 'Pending', 610000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(45, 25, '2026-04-18 11:17:56', 'Pending', 680000.00, NULL, 0, 300000.00, 350000.00, 30000.00, 0.00),
(46, 25, '2026-04-18 12:11:23', 'Cancelled', 330000.00, NULL, 1, 250000.00, 50000.00, 30000.00, 0.00),
(47, 21, '2026-04-19 10:28:03', 'Pending', 990000.00, NULL, 0, 690000.00, 300000.00, 0.00, 0.00),
(48, 21, '2026-04-19 10:29:04', 'Delivered', 310000.00, 2, 1, 230000.00, 50000.00, 30000.00, 0.00),
(49, 25, '2026-04-19 16:48:21', 'Delivered', 1080000.00, 2, 1, 780000.00, 300000.00, 0.00, 0.00),
(50, 24, '2026-04-19 17:10:26', 'Pending', 200000.00, NULL, 1, 200000.00, 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_item`
--

CREATE TABLE `order_item` (
  `orderItemId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `variantId` int(11) NOT NULL,
  `quantity` smallint(6) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_item`
--

INSERT INTO `order_item` (`orderItemId`, `orderId`, `variantId`, `quantity`, `price`) VALUES
(19, 25, 2, 1, 250000.00),
(26, 31, 5, 1, 300000.00),
(34, 39, 3, 1, 100000.00),
(35, 40, 1, 1, 200000.00),
(36, 41, 6, 1, 230000.00),
(37, 42, 6, 1, 230000.00),
(38, 43, 7, 1, 120000.00),
(39, 44, 6, 1, 230000.00),
(40, 45, 5, 1, 300000.00),
(41, 46, 2, 1, 250000.00),
(42, 47, 8, 2, 230000.00),
(43, 47, 6, 1, 230000.00),
(44, 48, 6, 1, 230000.00),
(45, 49, 3, 1, 100000.00),
(46, 49, 4, 1, 280000.00),
(47, 49, 1, 2, 200000.00),
(48, 50, 1, 1, 200000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment`
--

CREATE TABLE `payment` (
  `paymentId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `paymentMethod` varchar(50) NOT NULL,
  `paymentStatus` enum('Pending','Paid','Failed','Refunded') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payment`
--

INSERT INTO `payment` (`paymentId`, `orderId`, `paymentMethod`, `paymentStatus`) VALUES
(1, 39, 'Bank Transfer', 'Pending'),
(2, 40, 'Bank Transfer', 'Pending'),
(3, 41, 'Bank Transfer', 'Pending'),
(4, 42, 'Bank Transfer', 'Pending'),
(5, 43, 'Bank Transfer', 'Pending'),
(6, 44, 'Bank Transfer', 'Pending'),
(7, 45, 'Bank Transfer', 'Pending'),
(8, 46, 'Bank Transfer', 'Pending'),
(9, 47, 'Bank Transfer', 'Pending'),
(10, 48, 'Bank Transfer', 'Pending'),
(11, 49, 'Bank Transfer', 'Refunded'),
(12, 50, 'Bank Transfer', 'Pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `prescription`
--

CREATE TABLE `prescription` (
  `prescriptionId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `orderItemId` int(11) DEFAULT NULL,
  `leftEye` varchar(50) NOT NULL,
  `rightEye` varchar(50) NOT NULL,
  `leftPD` decimal(5,2) NOT NULL,
  `rightPD` decimal(5,2) NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `prescription`
--

INSERT INTO `prescription` (`prescriptionId`, `userId`, `orderItemId`, `leftEye`, `rightEye`, `leftPD`, `rightPD`, `imagePath`) VALUES
(1, 30, NULL, '{\"sph\":\"1.2\",\"cyl\":\"1.2\",\"axis\":\"2\",\"add\":\"1.3\"}', '{\"sph\":\"1.0\",\"cyl\":\"1.0\",\"axis\":\"3\",\"add\":\"1.4\"}', 30.00, 10.00, NULL),
(35, 21, NULL, '{\"sph\":\"1\",\"cyl\":\"1\",\"axis\":\"1\"}', '{\"sph\":\"1.2\",\"cyl\":\"1\",\"axis\":\"1\"}', 60.00, 60.00, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `staffId` int(11) NOT NULL,
  `originalPrice` int(11) DEFAULT NULL,
  `appliedPromotionId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`productId`, `name`, `description`, `price`, `categoryId`, `imagePath`, `staffId`, `originalPrice`, `appliedPromotionId`) VALUES
(1, 'Kính Mắt Mèo Nữ', 'Kính mắt gọng mắt mèo trendy cho nữ', 200000, 1, 'matmeonu.jpg', 10, NULL, NULL),
(2, 'Kính Mắt Gọng Siêu Mỏng', 'Kính mắt với thiết kế gọng siêu mỏng', 250000, 6, 'sieumong.jpg', 10, NULL, NULL),
(3, 'Kính Học Sinh', 'Kính Học Sinh', 100000, 6, 'gongnam.jpg', 10, NULL, NULL),
(9, 'Kính SV', 'Kim loại', 0, 6, 'glass_1776183282_69de67f271c2a.jpg', 1, NULL, NULL),
(10, 'Kính Văn phòng', 'Siêu mỏng', 0, 6, 'glass_1776228614_69df190650a92.jpg', 1, NULL, NULL),
(11, 'Kính Du lịch', 'nhựa', 0, 6, 'glass_1776230556_69df209cb4628.jpg', 1, NULL, NULL),
(12, 'Kính mát', 'Nhựa', 0, 1, 'glass_1776232828_69df297c43440.jpg', 1, NULL, NULL),
(13, 'Kính mắt', 'Kim loại', 0, 1, NULL, 1, NULL, NULL),
(14, 'kinh can', 'nhua', 700000, 4, 'glass_1776624805_69e524a515810.jpg', 10, NULL, NULL),
(15, 'kinh trẻ em', 'nhôm', 600000, 4, 'glass_1776629072_69e53550bbb90.jpg', 10, NULL, NULL),
(16, 'kinh cận', 'nhưa', 500000, 4, 'glass_1776629117_69e5357d8fe7d.jpg', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variant`
--

CREATE TABLE `product_variant` (
  `variantId` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `originalPrice` decimal(10,2) DEFAULT NULL,
  `appliedPromotionId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variant`
--

INSERT INTO `product_variant` (`variantId`, `color`, `size`, `price`, `stock`, `productId`, `originalPrice`, `appliedPromotionId`) VALUES
(1, 'Đen', 'M', 200000.00, 100, 1, NULL, NULL),
(2, 'xám', 'M', 250000.00, 2, 2, NULL, NULL),
(3, 'Trắng', 'S', 100000.00, 10, 3, NULL, NULL),
(4, 'Đen', 'M', 280000.00, 10, 9, NULL, NULL),
(5, 'Đen', 'L', 300000.00, 2, 10, NULL, NULL),
(6, 'Hồng', 'S', 230000.00, 3, 11, NULL, NULL),
(7, 'Trắng', 'S', 120000.00, 10, 12, NULL, NULL),
(8, 'Trắng', 'S', 230000.00, 10, 13, NULL, NULL),
(9, 'Đen', 'm', 700000.00, 25, 14, NULL, NULL),
(10, 'Đen', 'L', 55000.00, 35, 15, NULL, NULL),
(11, 'đỏ', 'X', 550000.00, 60, 16, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotion`
--

CREATE TABLE `promotion` (
  `promotionId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `staffId` int(11) NOT NULL,
  `discountType` enum('percent','amount') DEFAULT 'percent',
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `promotion`
--

INSERT INTO `promotion` (`promotionId`, `name`, `discount`, `startDate`, `endDate`, `staffId`, `discountType`, `status`) VALUES
(2, 'Khuyến mãi mùa hè', 15.00, '2026-04-01', '2026-04-30', 10, 'percent', 'inactive'),
(3, 'Giảm giá tháng 5', 10.00, '2026-05-01', '2026-05-30', 1, 'percent', 'active'),
(4, 'Khuyến mãi lễ', 15.00, '2026-04-30', '2026-05-03', 1, 'percent', 'active'),
(5, 'Sale xả kho', 50.00, '2026-04-19', '2026-04-25', 1, 'percent', 'active'),
(6, 'Ưu đãi SV', 5.00, '2026-01-01', '2026-12-31', 1, 'percent', 'active'),
(7, 'Black Friday 1', 20.00, '2026-11-01', '2026-11-30', 1, 'percent', 'active'),
(8, 'Black Friday 2', 25.00, '2026-11-01', '2026-11-30', 1, 'percent', 'active'),
(9, 'Black Friday 3', 30.00, '2026-11-01', '2026-11-30', 1, 'percent', 'active'),
(10, 'Khai trương CN1', 10.00, '2026-04-19', '2026-05-19', 1, 'percent', 'active'),
(11, 'Khai trương CN2', 10.00, '2026-04-19', '2026-05-19', 1, 'percent', 'active'),
(13, 'Mùa hè rực rỡ 1', 15.00, '2026-06-01', '2026-08-30', 10, 'percent', 'active'),
(14, 'Mùa hè rực rỡ 2', 20.00, '2026-06-01', '2026-08-30', 1, 'percent', 'active'),
(15, 'Mùa hè rực rỡ 3', 25.00, '2026-06-01', '2026-08-30', 1, 'percent', 'active'),
(16, 'Cuối năm 1', 40.00, '2026-12-01', '2026-12-31', 1, 'percent', 'active'),
(17, 'Cuối năm 2', 45.00, '2026-12-01', '2026-12-31', 1, 'percent', 'active'),
(18, 'Flash Sale 1', 70.00, '2026-04-20', '2026-04-20', 1, 'percent', 'active'),
(19, 'Flash Sale 2', 80.00, '2026-04-21', '2026-04-21', 1, 'percent', 'active'),
(20, 'Member VIP', 12.00, '2026-01-01', '2026-12-31', 1, 'percent', 'active'),
(21, 'Back to School', 18.00, '2026-04-15', '2026-09-15', 10, 'percent', 'active'),
(22, 'Mùa hè rực rỡ 3', 0.10, '2026-04-19', '2026-04-23', 10, 'percent', 'inactive'),
(23, 'Mùa hè rực rỡ 4', 15.00, '2026-04-19', '2026-04-22', 10, '', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotion_product`
--

CREATE TABLE `promotion_product` (
  `promotionId` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `return_request`
--

CREATE TABLE `return_request` (
  `returnId` int(11) NOT NULL,
  `orderItemId` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL,
  `requestDate` date NOT NULL,
  `staffId` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `imagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `return_request`
--

INSERT INTO `return_request` (`returnId`, `orderItemId`, `reason`, `status`, `requestDate`, `staffId`, `note`, `imagePath`) VALUES
(11, 26, 'broken', 'Pending', '2026-04-17', NULL, 'bể gọng', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review`
--

CREATE TABLE `review` (
  `reviewId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `createdDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `review`
--

INSERT INTO `review` (`reviewId`, `customerId`, `orderId`, `rating`, `comment`, `createdDate`) VALUES
(1, 25, 31, 5, 'quá là xinh đẹp!!!!', '2026-04-17 18:45:59'),
(2, 25, 49, 5, 'sao k có sao thứ 6??', '2026-04-19 17:26:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipment`
--

CREATE TABLE `shipment` (
  `shipmentId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `trackingCode` varchar(50) NOT NULL,
  `carrier` varchar(50) NOT NULL,
  `status` enum('Preparing','Ready_to_Ship','Shipping','Delivered','Failed','Returned') NOT NULL,
  `staffId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `shipment`
--

INSERT INTO `shipment` (`shipmentId`, `orderId`, `trackingCode`, `carrier`, `status`, `staffId`) VALUES
(1, 48, 'GHN-IWU7EL', 'GHN', '', 1),
(2, 49, 'GHN-X82B0S', 'GHN', '', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staff`
--

CREATE TABLE `staff` (
  `staffId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `staff`
--

INSERT INTO `staff` (`staffId`, `userId`, `position`) VALUES
(1, 34, 'manager'),
(2, 31, 'sales'),
(3, 33, 'operation'),
(10, 21, 'manager');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`userId`, `name`, `email`, `password`, `phone`, `role`) VALUES
(2, 'huy', 'longan@gmail.com', '$2y$10$ijCV.CmhKs790jyAQQGnfOpRP/lKqzlsjhjVgoGEvi0eRLvDczV7O', '0123456799', 'customer'),
(5, 'hacker', 'test1234@gmail.com', '$2y$10$rAWCkW0cKglXUe6kdwKFz.kOCFtatH/VLWO/d9mwF0KXHFYA0u/Q.', '0234567891', 'customer'),
(7, 'leductin', 'tin@gmail.com', '$2y$10$LAN58/7hC2lEhJkzF0Jcy.HXu1rhIbnZcmVq.Aaw5hfELgQS.xtKC', '0916285832', 'customer'),
(21, 'Thien Tru', 'test@gmail.com', '$2y$10$jDIcBA2B1UPkBWFzKSHzXeSy8AItX.1qDV/pa9UcgwNJTPVL88rKS', '0346484951', 'staff'),
(29, 'ttru', 'luyen@gmail.com', '$2y$10$jYrp5WO6itTjwp6PghtjkeyYMDHUDdO28tWtsMe5KO5GChDPXtZOa', '0123456789', 'customer'),
(30, 'Thien Tru', 'tru@gmail.com', '$2y$10$LLbkjr5X4JPZo.BitrOmK.LswIBqfX3Ec5uW2zUeOJvvFDVuFbQCe', '0346484951', 'customer'),
(31, 'THIEN TRU', 'tomdth@gmail.com', '$2y$10$MBF4TzSaidUxd2NwWzgrlOdlmSUbs/sUD5Bgn8.KR8d3LIADfPn0m', '0123456789', 'staff'),
(33, 'Tôm', 't@gmail.com', '$2y$10$5DqaB..tprxpEV00W8qOTObkQ7SP.POduKAQ.vJMmF.CSIaxRLqOa', '0346484951', 'staff'),
(34, 'Thientru', 'ttru@gmail.com', '$2y$10$E9zKb5QnJwZSeJfrJmfpVeWh6xO35v8S64oiUEB7Tqn/vxFq0ISt6', '0123456789', 'staff');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD UNIQUE KEY `customerId` (`customerId`);

--
-- Chỉ mục cho bảng `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cartItemId`),
  ADD UNIQUE KEY `cartId` (`cartId`,`variantId`),
  ADD KEY `variantId` (`variantId`),
  ADD KEY `idx_comboId` (`comboId`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `combo`
--
ALTER TABLE `combo`
  ADD PRIMARY KEY (`comboId`),
  ADD KEY `staffId` (`staffId`),
  ADD KEY `idx_isActive` (`isActive`),
  ADD KEY `idx_createdAt` (`createdAt`);

--
-- Chỉ mục cho bảng `combo_item`
--
ALTER TABLE `combo_item`
  ADD PRIMARY KEY (`comboItemId`),
  ADD UNIQUE KEY `unique_combo_product` (`comboId`,`productId`),
  ADD KEY `idx_productId` (`productId`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `customerId` (`customerId`),
  ADD KEY `staffId` (`staffId`);

--
-- Chỉ mục cho bảng `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`orderItemId`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `variantId` (`variantId`);

--
-- Chỉ mục cho bảng `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `orderId` (`orderId`);

--
-- Chỉ mục cho bảng `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescriptionId`),
  ADD UNIQUE KEY `orderItemId` (`orderItemId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `staffId` (`staffId`);

--
-- Chỉ mục cho bảng `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`variantId`),
  ADD KEY `productId` (`productId`);

--
-- Chỉ mục cho bảng `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotionId`),
  ADD KEY `staffId` (`staffId`);

--
-- Chỉ mục cho bảng `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD PRIMARY KEY (`promotionId`,`productId`),
  ADD KEY `productId` (`productId`);

--
-- Chỉ mục cho bảng `return_request`
--
ALTER TABLE `return_request`
  ADD PRIMARY KEY (`returnId`),
  ADD KEY `orderItemId` (`orderItemId`);

--
-- Chỉ mục cho bảng `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`reviewId`),
  ADD KEY `customerId` (`customerId`),
  ADD KEY `orderId` (`orderId`);

--
-- Chỉ mục cho bảng `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipmentId`),
  ADD UNIQUE KEY `orderId` (`orderId`),
  ADD UNIQUE KEY `trackingCode` (`trackingCode`),
  ADD KEY `staffId` (`staffId`);

--
-- Chỉ mục cho bảng `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT cho bảng `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cartItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `combo`
--
ALTER TABLE `combo`
  MODIFY `comboId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `combo_item`
--
ALTER TABLE `combo_item`
  MODIFY `comboItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1873934;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT cho bảng `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT cho bảng `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescriptionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `variantId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `return_request`
--
ALTER TABLE `return_request`
  MODIFY `returnId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `review`
--
ALTER TABLE `review`
  MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `staff`
--
ALTER TABLE `staff`
  MODIFY `staffId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`customerId`);

--
-- Các ràng buộc cho bảng `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`),
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`variantId`) REFERENCES `product_variant` (`variantId`),
  ADD CONSTRAINT `cart_item_ibfk_3` FOREIGN KEY (`comboId`) REFERENCES `combo` (`comboId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `combo`
--
ALTER TABLE `combo`
  ADD CONSTRAINT `combo_ibfk_1` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `combo_item`
--
ALTER TABLE `combo_item`
  ADD CONSTRAINT `combo_item_ibfk_1` FOREIGN KEY (`comboId`) REFERENCES `combo` (`comboId`) ON DELETE CASCADE,
  ADD CONSTRAINT `combo_item_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`orderId`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`customerId`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Các ràng buộc cho bảng `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`variantId`) REFERENCES `product_variant` (`variantId`);

--
-- Các ràng buộc cho bảng `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);

--
-- Các ràng buộc cho bảng `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`orderItemId`) REFERENCES `order_item` (`orderItemId`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Các ràng buộc cho bảng `product_variant`
--
ALTER TABLE `product_variant`
  ADD CONSTRAINT `product_variant_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`);

--
-- Các ràng buộc cho bảng `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Các ràng buộc cho bảng `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD CONSTRAINT `promotion_product_ibfk_1` FOREIGN KEY (`promotionId`) REFERENCES `promotion` (`promotionId`),
  ADD CONSTRAINT `promotion_product_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`);

--
-- Các ràng buộc cho bảng `return_request`
--
ALTER TABLE `return_request`
  ADD CONSTRAINT `return_request_ibfk_1` FOREIGN KEY (`orderItemId`) REFERENCES `order_item` (`orderItemId`);

--
-- Các ràng buộc cho bảng `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`customerId`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);

--
-- Các ràng buộc cho bảng `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`),
  ADD CONSTRAINT `shipment_ibfk_2` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Các ràng buộc cho bảng `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
