-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2025 at 11:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `price`, `quantity`, `image`, `is_deleted`) VALUES
(1, 'pen', 5.00, 91, 'pen.jpeg', 0),
(2, 'pencil', 7.00, 91, 'pencil.jpeg', 0),
(3, 'eraser', 10.00, 100, 'eraser.jpg', 0),
(4, 'scale', 5.00, 100, 'scale.png', 0),
(5, 'sharpener', 10.00, 100, 'sharpener.jpg', 0),
(6, 'geometry box', 95.00, 200, 'geometry.jpg', 0),
(7, 'Magnetic compass', 350.00, 500, 'compass.jpeg', 0),
(8, 'Marker', 15.00, 100, 'marker.jpeg', 0),
(9, 'Highlighter', 30.00, 100, 'highlighter.jpeg', 0),
(10, 'Notebook', 75.00, 200, 'notebook.jpg', 0),
(11, 'Sticky-notes', 40.00, 200, 'sticky-notes.png', 0),
(12, 'Index-cards', 55.00, 200, 'index-cards.jpg', 0),
(13, 'Royal Crayons', 125.00, 5, 'crayons.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  `payment_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_amount`, `shipping_name`, `shipping_address`, `payment_method`, `payment_status`, `payment_id`) VALUES
(1, 2, '2025-06-12 06:40:54', 40.00, 'fm', 'bng', '', 'Paid', NULL),
(2, 2, '2025-06-12 08:31:54', 5.00, 'fm', 'bng', '', 'Pending', NULL),
(3, 7, '2025-06-14 08:48:34', 5.00, 'fathima', 'bng', '', 'Pending', NULL),
(4, 6, '2025-06-14 08:56:10', 10.00, 'fathima', 'bng', 'Online', 'Paid', NULL),
(5, 6, '2025-06-14 10:15:04', 5.00, 'fathima ', 'bng', 'COD', 'Pending', NULL),
(6, 6, '2025-06-14 10:16:19', 5.00, 'fathima ', 'bng', 'Online', 'Initiated', NULL),
(7, 6, '2025-06-14 10:16:25', 5.00, 'fathima ', 'bng', 'COD', 'Pending', NULL),
(8, 6, '2025-06-14 10:17:24', 7.00, 'fathima ', 'bng', 'Online', 'Paid', NULL),
(9, 6, '2025-06-14 10:20:02', 5.00, 'Fathima mahvish', 'bng', 'COD', 'Pending', NULL),
(10, 6, '2025-06-14 10:21:09', 5.00, 'Fathima mahvish', 'bng', 'COD', 'Pending', NULL),
(11, 6, '2025-06-14 10:21:29', 5.00, 'Fathima mahvish', 'bng', 'Online', 'Paid', NULL),
(12, 6, '2025-06-14 16:25:52', 10.00, 'Fathima mahvish', 'bng', 'Online', 'Paid', NULL),
(13, 6, '2025-06-16 05:14:20', 7.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Pending', 'pay_QhkRcKU5s6kroh'),
(14, 6, '2025-06-16 05:24:37', 5.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Pending', 'pay_QhkcgCQirdLTZK'),
(15, 6, '2025-06-16 05:33:36', 7.00, 'Fathima mahvish', 'bng', 'Razorpay', 'paid', NULL),
(16, 6, '2025-06-16 05:37:39', 7.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Paid', 'pay_QhkqRbX7sQd5uo'),
(17, 6, '2025-06-16 05:39:44', 5.00, 'Fathima mahvish', 'bng', 'COD', 'Pending', NULL),
(18, 2, '2025-06-16 05:51:10', 5.00, 'Fathima mahvish', 'bng', 'COD', 'Pending', NULL),
(19, 2, '2025-06-16 10:17:55', 7.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Pending', 'pay_QhpcV2NS58MS7y'),
(20, 2, '2025-06-16 10:19:50', 7.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Pending', 'pay_QhpeRQUbDde7Gq'),
(21, 2, '2025-06-17 05:04:40', 5.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Pending', 'pay_Qi8oiiLwpfFTKL'),
(22, 2, '2025-06-17 05:35:45', 5.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Pending', 'pay_Qi9LYIZTf3hfYf'),
(23, 2, '2025-06-17 06:58:12', 125.00, 'Fathima mahvish', 'bng', 'COD', 'Pending', NULL),
(24, 8, '2025-06-17 08:45:57', 5.00, 'Fathima mahvish', 'bng', 'Razorpay', 'Pending', 'pay_QiCaTCXKDaGTTY');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 5.00),
(2, 1, 2, 5, 7.00),
(3, 2, 1, 1, 5.00),
(4, 3, 1, 1, 5.00),
(5, 4, 3, 1, NULL),
(6, 5, 1, 1, NULL),
(7, 6, 1, 1, NULL),
(8, 7, 1, 1, NULL),
(9, 8, 2, 1, NULL),
(10, 9, 1, 1, NULL),
(11, 10, 1, 1, NULL),
(12, 11, 1, 1, NULL),
(13, 12, 5, 1, NULL),
(14, 13, 2, 1, 7.00),
(15, 14, 1, 1, 5.00),
(16, 15, 2, 1, 7.00),
(17, 16, 2, 1, 7.00),
(18, 17, 1, 1, 5.00),
(19, 18, 1, 1, 5.00),
(20, 19, 2, 1, 7.00),
(21, 20, 2, 1, 7.00),
(22, 21, 1, 1, 5.00),
(23, 22, 1, 1, 5.00),
(24, 23, 13, 1, 125.00),
(25, 24, 1, 1, 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `phone` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `oauth_provider` varchar(50) DEFAULT 'local'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `phone`, `profile_picture`, `email`, `oauth_provider`) VALUES
(2, 'user', '$2y$10$xrtRFdSMYjTip.ADykSNOecFmVN6jVkvuru/inyATj0qtCm3Bcxcy', 'customer', '8722877020', 'mp.jpg', NULL, 'local'),
(3, 'admin', '$2y$10$i0prczL7fiGyu4bxg6PznebMwDI9q0CnY.zR4o/Wut32dk9L0y9b.', 'admin', '8722877020', 'mp.jpg', NULL, 'local'),
(4, 'fm', '$2y$10$M2wLbI36Qya4njArd36Ep.V7H.FnBm2W4.ZU1sAaTr8n.0i569mwG', 'customer', '8722877020', NULL, NULL, 'local'),
(5, 'fathima', '$2y$10$CCjZviIZH5Kqvm.F2l6Qb.OYo6AWIzMl0vLq2hZ6w9DSm87Yr7QcC', 'customer', '8722877020', NULL, NULL, 'local'),
(6, 'fathima12', '$2y$10$AedICsjIKMUwQiXGDaXPtekHFKdda6VJIAKBXWVoz87OofacHo7NK', 'customer', '8722877020', 'mp.jpg', NULL, 'local'),
(7, 'fathima123', '$2y$10$TQRKXgf8H02dl5wQhpgAOOREaErAROrRViAT.GJVfQPOZ1MXaoyNm', 'customer', '8722877020', 'mp.jpg', NULL, 'local'),
(8, 'mahvish2', '$2y$10$T8hlzscN7Azgl8ENvBxQ1.riFtB1XW7HV2goAzbrIpqSVlp1bFJay', 'customer', '8722877020', 'profile_6851135bf0dcf7.68708626.jpg', 'fathimamahvish38@gmail.com', 'local');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `inventory` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `inventory` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
