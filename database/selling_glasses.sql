-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 01:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `selling_glasses`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `createdDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartId`, `customerId`, `createdDate`) VALUES
(2, 25, '2026-04-08 01:20:18'),
(3, 27, '2026-04-10 15:58:55'),
(4, 26, '2026-04-14 20:13:07'),
(5, 21, '2026-04-14 20:57:12'),
(6, 28, '2026-04-14 23:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cartItemId` int(11) NOT NULL,
  `cartId` int(11) NOT NULL,
  `variantId` int(11) NOT NULL,
  `quantity` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`cartItemId`, `cartId`, `variantId`, `quantity`) VALUES
(13, 2, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `address` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerId`, `userId`, `address`) VALUES
(21, 21, '123,ABC, ABC, ABC, Ho Chi Minh'),
(24, 29, NULL),
(25, 30, '123,ABC, ABC, ABC, Ha Noi'),
(26, 31, '123,ABC, ABC, ABC, Ho Chi Minh'),
(27, 33, NULL),
(28, 34, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `status` enum('Pending','Confirmed','Processing','Shipped','Delivered','Cancelled','Returned') NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `staffId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `customerId`, `orderDate`, `status`, `totalPrice`, `staffId`) VALUES
(23, 25, '2026-04-16 07:00:10', 'Pending', 280000.00, NULL),
(24, 25, '2026-04-16 07:14:14', 'Pending', 260000.00, NULL),
(25, 25, '2026-04-16 07:25:12', 'Pending', 250000.00, NULL),
(26, 25, '2026-04-16 07:28:43', 'Pending', 230000.00, NULL),
(27, 25, '2026-04-16 07:47:30', 'Pending', 710000.00, NULL),
(28, 25, '2026-04-16 12:50:34', 'Pending', 330000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `orderItemId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `variantId` int(11) NOT NULL,
  `quantity` smallint(6) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`orderItemId`, `orderId`, `variantId`, `quantity`, `price`) VALUES
(17, 23, 2, 1, 250000.00),
(18, 24, 8, 1, 230000.00),
(19, 25, 2, 1, 250000.00),
(20, 26, 1, 1, 200000.00),
(21, 27, 3, 1, 100000.00),
(22, 27, 4, 1, 280000.00),
(23, 28, 5, 1, 300000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `paymentMethod` varchar(50) NOT NULL,
  `paymentStatus` enum('Pending','Paid','Failed','Refunded') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentId`, `orderId`, `paymentMethod`, `paymentStatus`) VALUES
(1, 23, 'Bank Transfer', 'Pending'),
(2, 24, 'Bank Transfer', 'Pending'),
(3, 25, 'Bank Transfer', 'Pending'),
(4, 26, 'Bank Transfer', 'Pending'),
(5, 27, 'Bank Transfer', 'Pending'),
(6, 28, 'Bank Transfer', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
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
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`prescriptionId`, `userId`, `orderItemId`, `leftEye`, `rightEye`, `leftPD`, `rightPD`, `imagePath`) VALUES
(1, 30, NULL, '{\"sph\":\"0.00\",\"cyl\":\"0.00\",\"axis\":\"0\"}', '{\"sph\":\"0.00\",\"cyl\":\"0.00\",\"axis\":\"0\"}', 0.00, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `staffId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productId`, `name`, `description`, `price`, `categoryId`, `imagePath`, `staffId`) VALUES
(1, 'Kính Mắt Mèo Nữ', 'Kính mắt gọng mắt mèo trendy cho nữ', 200000, 1, 'matmeonu.jpg', 10),
(2, 'Kính Mắt Gọng Siêu Mỏng', 'Kính mắt với thiết kế gọng siêu mỏng', 250000, 6, 'sieumong.jpg', 10),
(3, 'Kính Học Sinh', 'Kính Học Sinh', 100000, 6, 'gongnam.jpg', 10),
(9, 'Kính SV', 'Kim loại', 0, 6, 'glass_1776183282_69de67f271c2a.jpg', 1),
(10, 'Kính Văn phòng', 'Siêu mỏng', 0, 6, 'glass_1776228614_69df190650a92.jpg', 1),
(11, 'Kính Du lịch', 'nhựa', 0, 6, 'glass_1776230556_69df209cb4628.jpg', 1),
(12, 'Kính mát', 'Nhựa', 0, 1, 'glass_1776232828_69df297c43440.jpg', 1),
(13, 'Kính mắt', 'Kim loại', 0, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_variant`
--

CREATE TABLE `product_variant` (
  `variantId` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variant`
--

INSERT INTO `product_variant` (`variantId`, `color`, `size`, `price`, `stock`, `productId`) VALUES
(1, 'Đen', 'M', 200000.00, 100, 1),
(2, 'xám', 'M', 250000.00, 2, 2),
(3, 'Trắng', 'S', 100000.00, 10, 3),
(4, 'Đen', 'M', 280000.00, 10, 9),
(5, 'Đen', 'L', 300000.00, 2, 10),
(6, 'Hồng', 'S', 230000.00, 3, 11),
(7, 'Trắng', 'S', 120000.00, 10, 12),
(8, 'Trắng', 'S', 230000.00, 10, 13);

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotionId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `staffId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotionId`, `name`, `discount`, `startDate`, `endDate`, `staffId`) VALUES
(2, 'Khuyến mãi mùa hè', 15.00, '2026-04-01', '2026-04-30', 10);

-- --------------------------------------------------------

--
-- Table structure for table `promotion_product`
--

CREATE TABLE `promotion_product` (
  `promotionId` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_request`
--

CREATE TABLE `return_request` (
  `returnId` int(11) NOT NULL,
  `orderItemId` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL,
  `requestDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `shipmentId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `trackingCode` varchar(50) NOT NULL,
  `carrier` varchar(50) NOT NULL,
  `status` enum('Preparing','Ready_to_Ship','Shipping','Delivered','Failed','Returned') NOT NULL,
  `staffId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffId`, `userId`, `position`) VALUES
(1, 34, 'manager'),
(2, 31, 'sales'),
(3, 33, 'operation'),
(10, 21, 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `name`, `email`, `password`, `phone`, `role`) VALUES
(2, 'huy', 'longan@gmail.com', '$2y$10$ijCV.CmhKs790jyAQQGnfOpRP/lKqzlsjhjVgoGEvi0eRLvDczV7O', '0123456799', 'customer'),
(5, 'hacker', 'test1234@gmail.com', '$2y$10$rAWCkW0cKglXUe6kdwKFz.kOCFtatH/VLWO/d9mwF0KXHFYA0u/Q.', '0234567891', 'customer'),
(7, 'leductin', 'tin@gmail.com', '$2y$10$LAN58/7hC2lEhJkzF0Jcy.HXu1rhIbnZcmVq.Aaw5hfELgQS.xtKC', '0916285832', 'customer'),
(21, 'Thien Tru', 'test@gmail.com', '$2y$10$jDIcBA2B1UPkBWFzKSHzXeSy8AItX.1qDV/pa9UcgwNJTPVL88rKS', '0346484951', 'staff'),
(29, 'luyen', 'luyen@gmail.com', '$2y$10$jYrp5WO6itTjwp6PghtjkeyYMDHUDdO28tWtsMe5KO5GChDPXtZOa', '0123456987', 'customer'),
(30, 'Thien Tru', 'tru@gmail.com', '$2y$10$LLbkjr5X4JPZo.BitrOmK.LswIBqfX3Ec5uW2zUeOJvvFDVuFbQCe', '0346484951', 'customer'),
(31, 'THIEN TRU', 'tomdth@gmail.com', '$2y$10$MBF4TzSaidUxd2NwWzgrlOdlmSUbs/sUD5Bgn8.KR8d3LIADfPn0m', '0123456789', 'staff'),
(33, 'Tôm', 't@gmail.com', '$2y$10$5DqaB..tprxpEV00W8qOTObkQ7SP.POduKAQ.vJMmF.CSIaxRLqOa', '0346484951', 'staff'),
(34, 'Thientru', 'ttru@gmail.com', '$2y$10$E9zKb5QnJwZSeJfrJmfpVeWh6xO35v8S64oiUEB7Tqn/vxFq0ISt6', '0123456789', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD UNIQUE KEY `customerId` (`customerId`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cartItemId`),
  ADD UNIQUE KEY `cartId` (`cartId`,`variantId`),
  ADD KEY `variantId` (`variantId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `customerId` (`customerId`),
  ADD KEY `staffId` (`staffId`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`orderItemId`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `variantId` (`variantId`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescriptionId`),
  ADD UNIQUE KEY `orderItemId` (`orderItemId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `staffId` (`staffId`);

--
-- Indexes for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`variantId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotionId`),
  ADD KEY `staffId` (`staffId`);

--
-- Indexes for table `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD PRIMARY KEY (`promotionId`,`productId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `return_request`
--
ALTER TABLE `return_request`
  ADD PRIMARY KEY (`returnId`),
  ADD KEY `orderItemId` (`orderItemId`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipmentId`),
  ADD UNIQUE KEY `orderId` (`orderId`),
  ADD UNIQUE KEY `trackingCode` (`trackingCode`),
  ADD KEY `staffId` (`staffId`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cartItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescriptionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `variantId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `return_request`
--
ALTER TABLE `return_request`
  MODIFY `returnId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipmentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`customerId`);

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`),
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`variantId`) REFERENCES `product_variant` (`variantId`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`customerId`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`variantId`) REFERENCES `product_variant` (`variantId`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`orderItemId`) REFERENCES `order_item` (`orderItemId`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Constraints for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD CONSTRAINT `product_variant_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`);

--
-- Constraints for table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Constraints for table `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD CONSTRAINT `promotion_product_ibfk_1` FOREIGN KEY (`promotionId`) REFERENCES `promotion` (`promotionId`),
  ADD CONSTRAINT `promotion_product_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`);

--
-- Constraints for table `return_request`
--
ALTER TABLE `return_request`
  ADD CONSTRAINT `return_request_ibfk_1` FOREIGN KEY (`orderItemId`) REFERENCES `order_item` (`orderItemId`);

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`),
  ADD CONSTRAINT `shipment_ibfk_2` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
