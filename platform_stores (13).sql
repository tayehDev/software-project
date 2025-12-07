-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 09, 2025 at 09:42 PM
-- Server version: 8.0.43-0ubuntu0.24.04.1
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `platform_stores`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int NOT NULL,
  `topic` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `topic`, `description`) VALUES
(1, 'Office', 'in Ramallah / city center');

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_canceled_row` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`id`, `name`, `is_canceled_row`) VALUES
(1, 'Salfit', 0),
(2, 'Qalqilya', 0),
(3, 'Tulkarm', 0),
(4, 'Bethlehem', 0),
(5, 'Ramalah', 0),
(6, 'Jericho', 0),
(7, 'Hebron', 0),
(8, 'Tobas', 0),
(9, 'Jenin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `sort` int DEFAULT NULL,
  `del` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `sort`, `del`) VALUES
(1, 'Toys & Games', 3, 0),
(2, 'Luggages & Bags', 4, 0),
(3, 'Computer & Phone', 6, 0),
(4, 'Accessories', 7, 0),
(5, 'Watches', 5, 0),
(6, 'Education', 2, 0),
(7, 'Sport', 1, 0),
(8, 'Home Appliances', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `head_img`
--

CREATE TABLE `head_img` (
  `id` int NOT NULL,
  `topic` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `head_img`
--

INSERT INTO `head_img` (`id`, `topic`, `img`) VALUES
(2, 'world of stores', '1548475960_image_upload_1759602215.avif'),
(3, 'Accessories ', '908653025_image_upload_1759602305.avif'),
(4, '  Jewelry', '1779261918_image_upload_1759602371.jpg'),
(5, 'Brands', '20152299_image_upload_1759602130.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders_list`
--

CREATE TABLE `orders_list` (
  `id` int UNSIGNED NOT NULL,
  `sent_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `product_id` int DEFAULT NULL,
  `by_user_id` int DEFAULT NULL,
  `client_response` int DEFAULT '0' COMMENT '0.wait 1.accept 2.reject',
  `amount` int DEFAULT NULL,
  `is_canceled_row` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `orders_list`
--

INSERT INTO `orders_list` (`id`, `sent_datetime`, `product_id`, `by_user_id`, `client_response`, `amount`, `is_canceled_row`) VALUES
(1, '2025-05-26 09:57:24', 4, 1, 0, 56, 0),
(2, '2025-05-26 10:10:00', 1, 1, 0, NULL, 0),
(3, '2025-05-26 11:06:31', 1, 1, 0, 3, 0),
(4, '2025-05-26 11:07:08', 1, 1, 0, 4, 0),
(5, '2025-05-28 08:23:19', 1, 2, 0, 5, 0),
(6, '2025-05-28 09:20:44', 3, 2, 0, 2, 0),
(7, '2025-05-28 09:23:07', 4, 2, 0, 5, 0),
(8, '2025-10-22 11:14:00', 6, 4, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `publish_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `img` varchar(50) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `category_id` int DEFAULT NULL,
  `by_user_id` int DEFAULT NULL,
  `brand_copy` int DEFAULT NULL COMMENT '1.brand 2.copy',
  `is_canceled_row` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `publish_datetime`, `title`, `img`, `price`, `description`, `category_id`, `by_user_id`, `brand_copy`, `is_canceled_row`) VALUES
(1, '2025-03-20 11:32:10', 'Gold Dial Analogue Watch', '529511478_image_upload_1759603224.webp', 200, 'Maxima GOLD Men ', 5, 2, 1, 0),
(2, '2025-03-20 11:32:28', 'Traditional women Watch', '1379922850_image_upload_1759603723.webp', 250, NULL, 5, 2, 2, 0),
(3, '2024-03-20 11:32:37', 'Traditional Men Watch', '1646213102_image_upload_1759613448.png', 300, 'New Universal 1080° Swivel Faucet Extender 2 Water Outlet Modes Faucet Aerator Extension with Filter Robotic Arm Bathroom Splash\r\n', 8, 2, 1, 0),
(4, '2024-03-20 11:37:27', 'Spinner Aluminum alloy Triangle ', '862981206_image_upload_1759603677.avif', 20, 'New Luminous Metal Fidget Spinner Aluminum alloy Triangle Hand Spinner Rotating Decompression Toys for Adult EDC Desk Fidget Toy\r\n', 1, 2, 1, 0),
(5, '2024-05-14 16:17:12', 'Helmet MICH2000 Airsoft ', '1918104613_image_upload_1759613611.png', 120, 'Protective Helmet FAST Helmet MICH2000 Airsoft MH Tactical Helmet Outdoor Tactical Painball CS SWAT Riding Protect Equipment\r\n', 4, 3, 2, 0),
(6, '2024-05-14 16:22:31', 'One-Wheel Hoverboard', '6063903_image_upload_1759605565.avif', 400, 'One-Wheel Hoverboard for Adults Electric Self-Balancing Scooter with Gyroor Technology for Off-Road Terrains\r\n', 7, 2, 2, 0),
(7, '2025-03-19 20:14:45', 'Metal Vintage Round Glasses Frame', '797884467_image_upload_1759606162.png', 100, 'New Fashion Women Men Metal Vintage Round Glasses Frame Oversized Eyeglasses Optical Spectacles Vision Care Eyewear for Unisex', 4, 3, 1, 0),
(8, '2025-05-10 10:56:32', 'Half finger Gloves Men\'s', '1031026996_image_upload_1759605801.jpg', 120, 'Tactical Hard Knuckle Half finger Gloves Men\'s Combat Hunting Shooting Paintball Duty - Fingerless\r\n', 4, 1, 1, 0),
(9, '2025-05-10 11:04:07', 'Intel Celeron N5095 Ultraslim Laptop', '1702777255_image_upload_1759613847.png', 1500, 'BYONE 15.6 Inch Intel Celeron N5095 Ultraslim Laptop 12G 16GB RAM 512GB 1TB SSD Keyboard Backlight Fingerprint Portable Computer\r\n', 3, 1, 1, 0),
(10, '2025-05-10 22:27:22', 'Navceker 4K 60Hz ', '1627343990_image_upload_1759613921.png', 380, 'Navceker 4K 60Hz Thunderbolt 3 USB C HDMI KVM Switch 100W PD Charge Type C USB KVM Switcher for Computer PC Macbook 1Monitor\r\n', 3, 1, 1, 0),
(11, '2025-05-26 08:27:10', 'Bincoo Coffee Moka Pot', '657356252_image_upload_1759614020.png', 20, 'Bincoo Coffee Moka Pot Espresso Maker Electric Stove For Italian Home Barista Accessories Coffee Professional Coffee Maker Tools\r\n', 8, 1, 1, 0),
(12, '2025-05-27 09:41:26', 'Coffee Machine', '1867930638_image_upload_1759614113.png', 300, 'Cafelffe 4in1 Cafetera Cappuccino Coffee Machine Dolce gusto Nes Capsule Espresso Maker ESE Pod Ground Gift for Lover,Dad,Mom\r\n', 8, 1, 1, 0),
(13, '2025-06-09 14:01:59', 'Small 3D Printer', '478722562_image_upload_1759604130.jpg', 499, 'Small Frequency Division Multiplexing 3D Printer High Accuracy Fast Heating Compact 3D Printing Machine 100X100X100MM\r\n', 6, 1, 1, 0),
(14, '2025-06-09 18:00:19', 'Travel Luggage', '1759641913_image_upload_1759605903.webp', 340, 'Luggage - Travel Luggage & Rolling Suitcases ', 2, 3, 1, 0),
(15, '2025-06-09 18:00:59', 'Men Crossbody Bags', '1376751614_image_upload_1759605979.webp', 110, 'Men Crossbody Bags Male Nylon Shoulder Bags 4 Zippers Boy Messenger Bags Man Handbags for Travel Casual Large Satchel\r\n', 2, 3, 1, 0),
(16, '2025-06-09 18:01:14', 'MOTAORA 2025 New Women Handbag', '93273141_image_upload_1759606043.avif', 90, 'MOTAORA 2025 New Women Handbag Fashion Leather Shoulder Bag Ladies Large Capaarea Messenger Bags Laptop Bag For 14\" Macbook Air\r\n', 2, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `nickname` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `img` varchar(55) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `password` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `area_id` int DEFAULT NULL,
  `address` text,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `user_role_account` int NOT NULL DEFAULT '3' COMMENT '1.admin 2.seller 3.buyer',
  `is_canceled_row` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `nickname`, `img`, `phone`, `password`, `area_id`, `address`, `latitude`, `longitude`, `user_role_account`, `is_canceled_row`) VALUES
(1, 'iyad alyousef', 'eng IYAD', NULL, '0568222222', '13f4afaff1316760072f042aec3d08052e6076d803af8d4fff5538e395b79808', 1, 'وسط البلد', '32.22503900', '35.26097300', 2, 0),
(2, 'sadeq baker', 'abu rami', '58_17_788eafd00f32c5e7619edefd535f0d70.webp', '0568111111', '28c89b18a5135fb7f4bdcf29970605f583ba11d51dd1a31dc1cd29ecd637b7ef', 6, 'جنين', NULL, NULL, 2, 0),
(3, 'master', 'admin', '894613985_image_upload_1760943387.png', '0568000000', '2b0bae663cd34b55dafa63a2431c469f69ddcdc14410538c67cddd90d80d0551', 1, 'حلحول', '32.22503900', '35.26097300', 1, 0),
(4, 'ahmad rami', 'abu sami', '542249521_image_upload_1761122252.png', '0565000222', '8a1ab1062d04d6317309010f7296a3ee2e43bd68016070db79e152d1c6322b3d', 2, 'دير شرف', '31.53256999', '35.09982722', 3, 0),
(5, 'ali hassan', 'eng', NULL, '5555555555', '91a73fd806ab2c005c13b4dc19130a884e909dea3f72d46e30266fe1a1f588d8', 3, 'بيت لقيا', NULL, NULL, 3, 0),
(6, 'najeh hamdan', 'doctor', NULL, '0568222221', '2b0bae663cd34b55dafa63a2431c469f69ddcdc14410538c67cddd90d80d0551', 5, 'عرابة', NULL, NULL, 3, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `head_img`
--
ALTER TABLE `head_img`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_list`
--
ALTER TABLE `orders_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_from_user_id` (`by_user_id`),
  ADD KEY `fk_order_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category_id` (`category_id`),
  ADD KEY `fk_product_by_user_id` (`by_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loginname` (`phone`),
  ADD KEY `users_area_id` (`area_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `head_img`
--
ALTER TABLE `head_img`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders_list`
--
ALTER TABLE `orders_list`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders_list`
--
ALTER TABLE `orders_list`
  ADD CONSTRAINT `fk_order_from_user_id` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_order_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_by_user_id` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_product_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
