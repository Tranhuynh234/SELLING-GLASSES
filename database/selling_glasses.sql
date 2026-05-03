-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th5 03, 2026 lúc 06:24 PM
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
(113, 24, '2026-04-19 17:09:33'),
(114, 1873940, '2026-04-20 06:30:16'),
(115, 26, '2026-04-20 07:08:53'),
(116, 1873945, '2026-05-03 22:28:24'),
(117, 1873942, '2026-05-03 22:54:54'),
(118, 1873947, '2026-05-03 23:15:24');

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
(93, 114, NULL, 17, 1),
(94, 114, NULL, 13, 1),
(95, 114, NULL, 16, 1),
(96, 114, 1, NULL, 1),
(97, 114, NULL, 20, 1),
(98, 114, NULL, 21, 1),
(99, 114, NULL, 22, 1),
(104, 9, 17, NULL, 1),
(105, 9, NULL, 23, 1),
(106, 112, 15, NULL, 1),
(111, 116, NULL, 23, 1),
(112, 116, 7, NULL, 1),
(115, 115, 12, NULL, 1);

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
(14, 'Mùa xuân', 'mùa xuân', NULL, 500000.00, 0, 10, '2026-04-19 09:43:49', '2026-04-19 09:44:05', NULL),
(15, 'mùa xuân', 'mắt kính phù hợp mùa xuân', 'combo_1777037166_69eb6f6ed3fd7.jpg', 998000.00, 1, 1, '2026-04-24 13:26:06', '2026-04-24 13:26:15', '2026-04-24 13:26:15'),
(16, 'mùa xuân', 'mắt kính phù hợp mùa xuân', 'combo_1777037168_69eb6f7008121.jpg', 998000.00, 1, 1, '2026-04-24 13:26:08', '2026-04-24 13:26:08', NULL),
(17, 'combo 5/5', 'kính thời trang hiện đại', 'combo_1777038080_69eb73005f7b8.jpg', 10000000.00, 1, 1, '2026-04-24 13:41:20', '2026-04-24 13:41:20', NULL),
(18, '4/4', '213312', NULL, 99000.00, 1, 1, '2026-04-24 17:25:12', '2026-04-24 17:25:12', NULL),
(19, '4/4', '213312', 'combo_1777051527_69eba787865dd.png', 99000.00, 1, 1, '2026-04-24 17:25:27', '2026-04-24 17:25:27', NULL),
(20, '3/3', '555', 'combo_1777051579_69eba7bb65e99.png', 99000.00, 1, 1, '2026-04-24 17:26:19', '2026-04-24 17:26:19', NULL),
(21, 'ngày xuân', 'kính xịn', 'combo_1777051932_69eba91cd242c.png', 100000.00, 1, 1, '2026-04-24 17:32:12', '2026-04-24 17:32:12', NULL),
(22, 'kính sale mạnh', 'hàng xã kho', 'combo_1777052908_69ebacec89881.jpg', 999000.00, 1, 1, '2026-04-24 17:48:28', '2026-04-24 17:48:28', NULL),
(23, 'Mùa xuân', 'adsfad', 'combo_1777820588_69f763acc76f4.jpg', 498000.00, 1, 10, '2026-05-03 15:02:58', '2026-05-03 15:03:08', NULL);

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
(133, 14, 11, 1, 1),
(136, 15, 13, 1, 0),
(137, 16, 13, 1, 0),
(138, 17, 11, 1, 0),
(139, 17, 13, 1, 1),
(140, 18, 1, 1, 0),
(141, 18, 2, 1, 1),
(142, 19, 1, 1, 0),
(143, 19, 2, 1, 1),
(144, 20, 1, 1, 0),
(145, 20, 2, 1, 1),
(146, 21, 9, 1, 0),
(147, 21, 3, 1, 1),
(148, 22, 11, 1, 0),
(149, 22, 10, 1, 1),
(150, 23, 15, 1, 0),
(151, 23, 12, 1, 1);

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
(21, 21, '8/15 huỳnh thị hai, kp3, tân chánh hiệp, quận 12,,'),
(24, 29, '123, 123, 123, Ho Chi Minh'),
(25, 30, '123,ABC, ABC, ABC, Ho Chi Minh'),
(26, 31, '123,ABC, ABC, ABC, Ho Chi Minh m'),
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
(1873933, NULL, NULL),
(1873940, 35, 'q45rq, 145, 15, Ho Chi Minh'),
(1873941, 36, 'N/A'),
(1873942, 37, 'N/A'),
(1873945, 39, 'N/A'),
(1873946, 2, NULL),
(1873947, 40, 'hcm');

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
(2, 48, 'Staff', 'Chào Thien Tru, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #48 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-04-19 16:45:21', 1, 1),
(3, 49, 'Staff', 'Chào Thien Tru, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #49 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-04-19 16:49:05', 1, 1),
(4, 49, 'Customer', 'helo', '2026-04-19 17:05:23', 1, 1),
(5, 50, 'Customer', 'hi', '2026-04-19 17:12:07', 1, 1),
(6, 52, 'Staff', 'Chào vu, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #52 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-04-20 07:11:19', 1, 1),
(7, 55, 'Staff', 'Chào vu, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #55 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-04-24 20:31:25', 1, 1),
(8, 56, 'Staff', 'Chào vu, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #56 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-04-24 20:38:56', 1, 1),
(9, 57, 'Staff', 'Chào le duc huy, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #57 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-05-02 11:11:36', 1, 1),
(10, 81, 'Staff', 'Chào leduchuy, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #81 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-05-03 23:09:54', 1, 0),
(11, 81, 'Staff', 'Chào leduchuy, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #81 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-05-03 23:10:04', 1, 0),
(12, 82, 'Staff', 'Chào THIEN TRU, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #82 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-05-03 23:11:36', 1, 1),
(13, 83, 'Staff', 'Chào l, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #83 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-05-03 23:19:23', 1, 0),
(14, 83, 'Staff', 'Chào l, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #83 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-05-03 23:19:30', 1, 0),
(15, 83, 'Staff', 'chào', '2026-05-03 23:19:44', 1, 0),
(16, 31, 'Staff', 'Chào Thien Tru, cảm ơn bạn đã mua hàng của chúng tôi, đơn hàng #31 sẽ được gửi đến bạn trong thời gian sớm nhất.', '2026-05-03 23:20:11', 1, 0),
(17, 31, 'Staff', 'hello', '2026-05-03 23:20:17', 1, 0),
(18, 82, 'Customer', 'chào', '2026-05-03 23:20:34', 1, 1),
(19, 82, 'Staff', 'chào', '2026-05-03 23:20:45', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `orderId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `status` enum('Pending','Confirmed','Processing','Shipped','Delivered','Cancelled','Returned') NOT NULL,
  `order_type` enum('ready_stock','pre_order','prescription') DEFAULT 'ready_stock',
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

INSERT INTO `orders` (`orderId`, `customerId`, `orderDate`, `status`, `order_type`, `totalPrice`, `staffId`, `is_contacted`, `subtotal`, `lensCost`, `shippingFee`, `discount`) VALUES
(25, 25, '2026-04-16 07:25:12', 'Pending', 'ready_stock', 250000.00, NULL, 0, 0.00, 0.00, 0.00, 0.00),
(31, 25, '2026-04-17 11:28:36', 'Returned', 'ready_stock', 680000.00, NULL, 1, 0.00, 0.00, 0.00, 0.00),
(39, 25, '2026-04-18 07:34:12', 'Pending', 'ready_stock', 180000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(40, 25, '2026-04-18 07:37:18', 'Pending', 'ready_stock', 580000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(41, 25, '2026-04-18 07:41:32', 'Cancelled', 'ready_stock', 560000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(42, 25, '2026-04-18 07:46:51', 'Cancelled', 'ready_stock', 610000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(43, 25, '2026-04-18 07:52:03', 'Pending', 'ready_stock', 450000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(44, 25, '2026-04-18 07:58:32', 'Pending', 'ready_stock', 610000.00, NULL, 0, 0.00, 0.00, 30000.00, 0.00),
(45, 25, '2026-04-18 11:17:56', 'Pending', 'ready_stock', 680000.00, NULL, 0, 300000.00, 350000.00, 30000.00, 0.00),
(46, 25, '2026-04-18 12:11:23', 'Cancelled', 'ready_stock', 330000.00, NULL, 1, 250000.00, 50000.00, 30000.00, 0.00),
(47, 21, '2026-04-19 10:28:03', 'Pending', 'ready_stock', 990000.00, NULL, 0, 690000.00, 300000.00, 0.00, 0.00),
(48, 21, '2026-04-19 10:29:04', 'Delivered', 'ready_stock', 310000.00, 2, 1, 230000.00, 50000.00, 30000.00, 0.00),
(49, 25, '2026-04-19 16:48:21', 'Delivered', 'ready_stock', 1080000.00, 2, 1, 780000.00, 300000.00, 0.00, 0.00),
(50, 24, '2026-04-19 17:10:26', 'Pending', 'ready_stock', 200000.00, NULL, 1, 200000.00, 0.00, 0.00, 0.00),
(51, 1873940, '2026-04-20 07:00:33', 'Pending', 'ready_stock', 830000.00, NULL, 0, 830000.00, 0.00, 0.00, 0.00),
(52, 1873940, '2026-04-20 07:10:51', 'Delivered', 'ready_stock', 650000.00, 2, 1, 600000.00, 50000.00, 0.00, 0.00),
(53, 1873940, '2026-04-24 20:17:58', 'Pending', 'ready_stock', 950000.00, NULL, 0, 950000.00, 0.00, 0.00, 0.00),
(54, 1873940, '2026-04-24 20:23:03', 'Pending', 'ready_stock', 2920000.00, NULL, 0, 2920000.00, 0.00, 0.00, 0.00),
(55, 1873940, '2026-04-24 20:28:07', 'Confirmed', 'ready_stock', 2798000.00, 2, 1, 2798000.00, 0.00, 0.00, 0.00),
(56, 1873940, '2026-04-24 20:38:32', 'Confirmed', 'ready_stock', 6200000.00, 2, 1, 6200000.00, 0.00, 0.00, 0.00),
(57, 21, '2026-05-02 11:07:53', 'Confirmed', 'ready_stock', 500000.00, 2, 1, 500000.00, 0.00, 0.00, 0.00),
(58, 21, '2026-05-02 11:17:00', 'Pending', 'ready_stock', 300000.00, NULL, 0, 250000.00, 50000.00, 0.00, 0.00),
(59, 21, '2026-05-02 11:21:47', 'Pending', 'ready_stock', 170000.00, NULL, 0, 120000.00, 50000.00, 0.00, 0.00),
(60, 21, '2026-05-02 11:32:36', 'Pending', 'ready_stock', 530000.00, NULL, 0, 230000.00, 300000.00, 0.00, 0.00),
(64, 1873945, '2026-05-03 22:28:42', 'Pending', 'ready_stock', 700000.00, NULL, 0, 700000.00, 0.00, 0.00, 0.00),
(76, 1873942, '2026-05-03 22:55:13', 'Pending', 'ready_stock', 700000.00, NULL, 0, 700000.00, 0.00, 0.00, 0.00),
(81, 1873942, '2026-05-03 23:08:55', 'Pending', 'prescription', 550000.00, NULL, 1, 500000.00, 50000.00, 0.00, 0.00),
(82, 26, '2026-05-03 23:11:14', 'Delivered', 'prescription', 310000.00, 2, 1, 230000.00, 50000.00, 30000.00, 0.00),
(83, 1873947, '2026-05-03 23:16:41', 'Pending', 'prescription', 1500000.00, NULL, 1, 1200000.00, 300000.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_item`
--

CREATE TABLE `order_item` (
  `orderItemId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `variantId` int(11) DEFAULT NULL,
  `comboId` int(11) DEFAULT NULL,
  `quantity` smallint(6) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_item`
--

INSERT INTO `order_item` (`orderItemId`, `orderId`, `variantId`, `comboId`, `quantity`, `price`) VALUES
(19, 25, 2, NULL, 1, 250000.00),
(26, 31, 5, NULL, 1, 300000.00),
(34, 39, 3, NULL, 1, 100000.00),
(35, 40, 1, NULL, 1, 200000.00),
(36, 41, 6, NULL, 1, 230000.00),
(37, 42, 6, NULL, 1, 230000.00),
(38, 43, 7, NULL, 1, 120000.00),
(39, 44, 6, NULL, 1, 230000.00),
(40, 45, 5, NULL, 1, 300000.00),
(41, 46, 2, NULL, 1, 250000.00),
(42, 47, 8, NULL, 2, 230000.00),
(43, 47, 6, NULL, 1, 230000.00),
(44, 48, 6, NULL, 1, 230000.00),
(45, 49, 3, NULL, 1, 100000.00),
(46, 49, 4, NULL, 1, 280000.00),
(47, 49, 1, NULL, 2, 200000.00),
(48, 50, 1, NULL, 1, 200000.00),
(49, 51, 2, NULL, 1, 250000.00),
(50, 51, 6, NULL, 2, 230000.00),
(51, 51, 7, NULL, 1, 120000.00),
(52, 52, 5, NULL, 1, 300000.00),
(53, 52, 3, NULL, 1, 100000.00),
(54, 52, 1, NULL, 1, 200000.00),
(55, 53, 2, NULL, 1, 250000.00),
(56, 53, 6, NULL, 2, 230000.00),
(57, 53, 7, NULL, 2, 120000.00),
(58, 54, NULL, 12, 1, 800000.00),
(59, 54, NULL, 13, 2, 1000000.00),
(60, 54, 7, NULL, 1, 120000.00),
(61, 55, NULL, 12, 1, 800000.00),
(62, 55, NULL, 13, 1, 1000000.00),
(63, 55, NULL, 16, 1, 998000.00),
(64, 56, NULL, 12, 4, 800000.00),
(65, 56, NULL, 13, 3, 1000000.00),
(66, 57, 2, NULL, 2, 250000.00),
(67, 58, 2, NULL, 1, 250000.00),
(68, 59, 7, NULL, 1, 120000.00),
(69, 60, 6, NULL, 1, 230000.00),
(73, 64, 15, NULL, 1, 700000.00),
(85, 76, 15, NULL, 1, 700000.00),
(90, 81, 11, NULL, 1, 500000.00),
(91, 82, 8, NULL, 1, 230000.00),
(92, 83, 12, NULL, 1, 1200000.00);

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
(12, 50, 'Bank Transfer', 'Pending'),
(13, 51, 'Bank Transfer', 'Pending'),
(14, 52, 'Bank Transfer', 'Pending'),
(15, 53, 'Bank Transfer', 'Pending'),
(16, 54, 'Bank Transfer', 'Pending'),
(17, 55, 'Bank Transfer', 'Pending'),
(18, 56, 'Bank Transfer', 'Pending'),
(19, 57, 'Bank Transfer', 'Pending'),
(20, 58, 'Bank Transfer', 'Pending'),
(21, 59, 'Bank Transfer', 'Pending'),
(22, 60, 'Bank Transfer', 'Pending'),
(23, 64, 'Bank Transfer', 'Pending'),
(24, 76, 'Bank Transfer', 'Pending'),
(25, 81, 'Bank Transfer', 'Pending'),
(26, 82, 'Bank Transfer', 'Pending'),
(27, 83, 'Bank Transfer', 'Pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `prescription`
--

CREATE TABLE `prescription` (
  `prescriptionId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL,
  `orderItemId` int(11) DEFAULT NULL,
  `leftEye` varchar(50) NOT NULL,
  `rightEye` varchar(50) NOT NULL,
  `leftPD` decimal(5,2) NOT NULL,
  `rightPD` decimal(5,2) NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `prescription`
--

INSERT INTO `prescription` (`prescriptionId`, `userId`, `orderId`, `orderItemId`, `leftEye`, `rightEye`, `leftPD`, `rightPD`, `imagePath`, `status`) VALUES
(1, 30, NULL, NULL, '{\"sph\":\"1.2\",\"cyl\":\"1.2\",\"axis\":\"2\",\"add\":\"1.3\"}', '{\"sph\":\"1.0\",\"cyl\":\"1.0\",\"axis\":\"3\",\"add\":\"1.4\"}', 30.00, 10.00, NULL, 'Pending'),
(35, 21, NULL, NULL, '{\"sph\":\"4\",\"cyl\":\"4\",\"axis\":\"4\"}', '{\"sph\":\"4\",\"cyl\":\"4\",\"axis\":\"4\"}', 60.00, 60.00, NULL, 'Pending'),
(48, 39, NULL, NULL, '{\"sph\":\"003\",\"cyl\":\"003\",\"axis\":\"03\"}', '{\"sph\":\"3\",\"cyl\":\"3\",\"axis\":\"03\"}', 70.00, 70.00, NULL, 'Pending'),
(51, 37, 81, NULL, '{\"sph\":\"1\",\"cyl\":\"1\",\"axis\":\"1\",\"add\":\"1\"}', '{\"sph\":\"0.5\",\"cyl\":\"1\",\"axis\":\"1\",\"add\":\"1\"}', 1.00, 1.00, NULL, 'Pending'),
(53, 31, 82, NULL, '{\"sph\":\"1\",\"cyl\":\"1\",\"axis\":\"1\",\"add\":\"1\"}', '{\"sph\":\"1\",\"cyl\":\"1\",\"axis\":\"1\",\"add\":\"1\"}', 1.00, 1.00, NULL, 'Pending'),
(56, 40, 83, NULL, '{\"sph\":\"0.5\",\"cyl\":\"2\",\"axis\":\"1\",\"add\":\"\"}', '{\"sph\":\"0.5\",\"cyl\":\"0.5\",\"axis\":\"1\",\"add\":\"1\"}', 32.00, 32.00, NULL, 'Pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(10,2) DEFAULT NULL,
  `categoryId` int(11) NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `staffId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`productId`, `name`, `description`, `price`, `original_price`, `categoryId`, `imagePath`, `staffId`) VALUES
(1, 'Kính Mắt Mèo Nữ', 'Kính mắt gọng mắt mèo trendy cho nữ', 200000.00, 200000.00, 1, 'matmeonu.jpg', 10),
(2, 'Kính Mắt Gọng Siêu Mỏng', 'Kính mắt với thiết kế gọng siêu mỏng', 250000.00, 250000.00, 6, 'sieumong.jpg', 10),
(3, 'Kính Học Sinh', 'Kính Học Sinh', 90000.00, 100000.00, 6, 'gongnam.jpg', 10),
(9, 'Kính SV', 'Kim loại', 100000.00, NULL, 6, 'glass_1776183282_69de67f271c2a.jpg', 1),
(10, 'Kính Văn phòng', 'Siêu mỏng', 100000.00, NULL, 6, 'glass_1776228614_69df190650a92.jpg', 1),
(11, 'Kính Du lịch', 'nhựa', 100000.00, 100000.00, 6, 'glass_1776230556_69df209cb4628.jpg', 1),
(12, 'Kính mát', 'Nhựa', 100000.00, 100000.00, 1, 'glass_1776232828_69df297c43440.jpg', 1),
(13, 'Kính mắt', 'Kim loại', 100000.00, 100000.00, 1, NULL, 1),
(15, 'kinh cận', 'nhựa', 700000.00, 700000.00, 4, 'glass_upd_1777816112.jpg', 10),
(16, 'Kính Phi Công Ray-Ban', 'Gọng kim loại cao cấp, chống tia UV400, phong cách cổ điển', 600000.00, NULL, 5, 'glass_1777817458_69f75772d774d.jpg', 10),
(17, 'Kính Gọng Tròn Retro', 'Chất liệu nhựa Acetate bền bỉ, gọng mảnh nhẹ, phù hợp học sinh.', 700000.00, 700000.00, 2, 'glass_upd_1777817536.jpg', 10),
(18, 'kinh cận', 'nhựa', 700000.00, 700000.00, 6, 'glass_upd_1777820403.jpg', 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variant`
--

CREATE TABLE `product_variant` (
  `variantId` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variant`
--

INSERT INTO `product_variant` (`variantId`, `color`, `size`, `price`, `original_price`, `stock`, `productId`) VALUES
(1, 'Đen', 'M', 200000.00, 200000.00, 100, 1),
(2, 'xám', 'M', 250000.00, 250000.00, 2, 2),
(3, 'Trắng', 'S', 90000.00, 100000.00, 10, 3),
(4, 'Đen', 'M', 280000.00, NULL, 10, 9),
(5, 'Đen', 'L', 300000.00, NULL, 2, 10),
(6, 'Hồng', 'S', 230000.00, 230000.00, 3, 11),
(7, 'Trắng', 'S', 120000.00, 120000.00, 10, 12),
(8, 'Trắng', 'S', 230000.00, 230000.00, 10, 13),
(11, 'Đen', 'M', 500000.00, 500000.00, 10, 15),
(12, 'Đen', 'L', 1200000.00, NULL, 20, 16),
(15, 'Đen', 'XL', 700000.00, 700000.00, 40, 17),
(17, 'Đen', 'L', 600000.00, 600000.00, 20, 18);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotion`
--

CREATE TABLE `promotion` (
  `promotionId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `staffId` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `discountType` enum('percent','fixed') DEFAULT 'percent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `promotion`
--

INSERT INTO `promotion` (`promotionId`, `name`, `discount`, `startDate`, `endDate`, `staffId`, `status`, `discountType`) VALUES
(4, 'Ưu đãi 8/3', 10.00, '2026-03-01', '2026-03-10', 10, 0, 'percent'),
(5, 'Giải phóng 30/4', 30.00, '2026-04-25', '2026-05-05', 10, 1, 'percent'),
(6, 'Ngày hội thành viên', 5.00, '2026-05-20', '2026-05-22', 10, 1, 'percent'),
(7, 'Kính mới giảm sâu', 25.00, '2026-07-01', '2026-07-15', 10, 1, 'percent'),
(8, 'Back to School', 15.50, '2026-08-15', '2026-09-15', 10, 1, 'percent'),
(9, 'Trung thu đoàn viên', 12.00, '2026-09-20', '2026-10-01', 10, 1, 'percent'),
(10, 'Halloween Sale', 31.00, '2026-10-25', '2026-11-01', 10, 1, 'percent'),
(11, 'Black Friday', 50.00, '2026-11-20', '2026-11-30', 10, 0, 'percent'),
(13, 'Khuyến mãi Khai trương', 50.00, '2026-05-10', '2026-05-20', 10, 1, 'percent'),
(14, 'Khuyến mãi Khai trương', 50.00, '2026-05-10', '2026-05-20', 10, 1, 'percent'),
(17, 'Mùa hạ', 9.00, '2026-05-15', '2026-05-15', 1, 1, 'percent');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotion_product`
--

CREATE TABLE `promotion_product` (
  `promotionId` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `promotion_product`
--

INSERT INTO `promotion_product` (`promotionId`, `productId`) VALUES
(4, 3);

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
(11, 26, 'broken', 'Completed', '2026-04-17', NULL, 'bể gọng', NULL);

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
(2, 49, 'GHN-X82B0S', 'GHN', '', 1),
(3, 52, 'GHN-IOACEZ', 'GHN', '', 1),
(4, 82, 'GHN-3XELR3', 'GHN', '', 1);

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
(10, 21, 'manager'),
(16, 35, 'sales'),
(17, 30, 'operation'),
(18, 7, 'operation'),
(19, 29, 'operation'),
(21, 2, 'manager');

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
(2, 'huy', 'long@gmail.com', '$2y$10$ijCV.CmhKs790jyAQQGnfOpRP/lKqzlsjhjVgoGEvi0eRLvDczV7O', '0123456789', 'staff'),
(7, 'leductin', 'tin@gmail.com', '$2y$10$LAN58/7hC2lEhJkzF0Jcy.HXu1rhIbnZcmVq.Aaw5hfELgQS.xtKC', '0916285832', 'staff'),
(21, 'le duc huy', 'test@gmail.com', '$2y$10$jDIcBA2B1UPkBWFzKSHzXeSy8AItX.1qDV/pa9UcgwNJTPVL88rKS', '033226413885', 'staff'),
(29, 'ttru', 'luyen@gmail.com', '$2y$10$jYrp5WO6itTjwp6PghtjkeyYMDHUDdO28tWtsMe5KO5GChDPXtZOa', '0123456789', 'staff'),
(30, 'Thien Tru', 'tru@gmail.com', '$2y$10$LLbkjr5X4JPZo.BitrOmK.LswIBqfX3Ec5uW2zUeOJvvFDVuFbQCe', '0346484951', 'staff'),
(31, 'THIEN TRU', 'tomdth@gmail.com', '$2y$10$MBF4TzSaidUxd2NwWzgrlOdlmSUbs/sUD5Bgn8.KR8d3LIADfPn0m', '012345678', 'staff'),
(33, 'Tôm', 't@gmail.com', '$2y$10$5DqaB..tprxpEV00W8qOTObkQ7SP.POduKAQ.vJMmF.CSIaxRLqOa', '0346484951', 'staff'),
(34, 'Thientru', 'ttru@gmail.com', '$2y$10$E9zKb5QnJwZSeJfrJmfpVeWh6xO35v8S64oiUEB7Tqn/vxFq0ISt6', '0123456789', 'staff'),
(35, 'vu', 'vutruong6kg@gmail.com', '$2y$10$A6A4E5/ve.VqiV0/yJr0ZutTclGy3KnzX.B33CB1p51squDBZUOIq', '12452341415', 'staff'),
(36, 'dsafdfa', 'dsafdda@gmail.com', '$2y$10$BJKiG.V2X.JKxOAzxbKGnufByMcADiTP3oqs5fZX/g4NsqoLhiQbe', '0000000000', 'customer'),
(37, 'leduchuy', 'leduchuy11@gmail.com', '$2y$10$Y/vArGDMS7ESQRQDmTsfVuAxL0lvJ2xEcW2u.T/PXseVl8YbQ/tTG', '0000000000', 'customer'),
(39, 'h', 'huy@gmail.com', '$2y$10$Y.AYG5lVHGdDpm1fSLdhTeXQD0Xi7k4EkidoAvNPHAJFhUgjycaVK', '0000000000', 'customer'),
(40, 'l', 'duchuy@gmail.com', '$2y$10$wSbqPYl6XAk8KqQR8liN3OFxraY0K2QiLLf75aDsp8mQCs9dvZ0e2', '0000000000', 'customer');

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
  ADD KEY `variantId` (`variantId`),
  ADD KEY `idx_order_item_comboId` (`comboId`);

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
  ADD UNIQUE KEY `userId` (`userId`),
  ADD KEY `idx_orderId` (`orderId`),
  ADD KEY `idx_userId` (`userId`);

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
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT cho bảng `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cartItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `combo`
--
ALTER TABLE `combo`
  MODIFY `comboId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `combo_item`
--
ALTER TABLE `combo_item`
  MODIFY `comboItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1873948;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT cho bảng `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT cho bảng `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescriptionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `variantId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `shipmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `staff`
--
ALTER TABLE `staff`
  MODIFY `staffId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`variantId`) REFERENCES `product_variant` (`variantId`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_item_ibfk_3` FOREIGN KEY (`comboId`) REFERENCES `combo` (`comboId`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);

--
-- Các ràng buộc cho bảng `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`orderItemId`) REFERENCES `order_item` (`orderItemId`),
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE;

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
