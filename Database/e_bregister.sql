-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 12:23 PM
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
-- Database: `e_bregister`
--

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_monthly_orders`
--

CREATE TABLE `dashboard_monthly_orders` (
  `id` int(11) NOT NULL,
  `month_label` varchar(10) NOT NULL,
  `total_orders` int(11) NOT NULL,
  `delivered` int(11) NOT NULL,
  `canceled` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_monthly_orders`
--

INSERT INTO `dashboard_monthly_orders` (`id`, `month_label`, `total_orders`, `delivered`, `canceled`) VALUES
(1, 'Jan', 1000, 12000, 100),
(2, 'Feb', 2000, 11000, 200),
(3, 'Mar', 3000, 10000, 300),
(4, 'Apr', 4000, 9000, 400),
(5, 'May', 5000, 8000, 500),
(6, 'Jun', 6000, 7000, 600),
(7, 'Jul', 7000, 6000, 700),
(8, 'Aug', 8000, 5000, 800),
(15, 'Sep', 9000, 4000, 900),
(16, 'Oct', 10000, 3000, 1000),
(17, 'Nov', 11000, 2000, 1100),
(18, 'Dec', 12000, 1000, 1200);

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_monthly_revenue`
--

CREATE TABLE `dashboard_monthly_revenue` (
  `id` int(11) NOT NULL,
  `month_label` varchar(10) NOT NULL,
  `revenue` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_monthly_revenue`
--

INSERT INTO `dashboard_monthly_revenue` (`id`, `month_label`, `revenue`) VALUES
(1, 'Jan', 1000000),
(2, 'Feb', 2000000),
(3, 'Mar', 3000000),
(4, 'Apr', 4000000),
(5, 'May', 5000000),
(6, 'Jun', 6000000),
(7, 'Jul', 7000000),
(8, 'Aug', 8000000),
(9, 'Sep', 9000000),
(10, 'Oct', 10000000),
(11, 'Nov', 11000000),
(12, 'Dec', 12000000);

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_stats`
--

CREATE TABLE `dashboard_stats` (
  `id` int(11) NOT NULL,
  `metric_key` varchar(50) NOT NULL,
  `metric_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_stats`
--

INSERT INTO `dashboard_stats` (`id`, `metric_key`, `metric_value`) VALUES
(5, 'total_orders', 1000),
(6, 'total_delivered', 900),
(7, 'total_canceled', 100),
(8, 'total_revenue', 10000),
(28, 'open_orders', 1023);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(225) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `price`, `image_path`, `is_active`) VALUES
(4, 'Adobong Manok', 'Main Course', 180.00, '../../Image/Customer/Adobo.jpg', 1),
(5, 'Peanut Cake', 'Dessert', 80.00, '../../Image/Customer/Peanut Butter.jpg', 2),
(6, 'Mystery Juice', 'Drinks', 50.00, '../../Image/Customer/Drinks.jpg', 3),
(7, 'Dinakdakan', 'Main Course', 180.00, '../../Image/Customer/Dinakdakan.jpg', 4),
(8, 'Dinuguan', 'Main Course', 180.00, '../../Image/Customer/Dinuguan.jpg', 5),
(9, 'Pineapple Juice', 'Drinks', 60.00, '../../Image/Customer/Pineapple.jpg', 6),
(10, 'Ice Cream Sandwich Cake', 'Dessert', 80.00, '../../Image/Customer/Ice Cream Sandwich Cake.jpg', 7),
(11, 'Rice', 'Main Course', 50.00, '../../Image/Customer/Rice.jpg', 8),
(12, 'Coke', 'Drinks', 80.00, '../../Image/Customer/Coke.jpg', 9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `from_address` varchar(255) NOT NULL,
  `to_address` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'preparing',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `from_address`, `to_address`, `subtotal`, `delivery_fee`, `total`, `status`, `created_at`) VALUES
(1, 2, 'Lingsat, City of San Fernando, La Union, Ilocos Region', 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 440.00, 9.00, 449.00, 'completed', '2025-12-03 18:05:02'),
(2, 2, 'Embarcadero, Batad, Iloilo, Western Visayas', 'Dulong Bayan, Quezon, Nueva Ecija, Central Luzon', 1580.00, 9.00, 1589.00, 'delivering', '2025-12-03 18:06:31'),
(3, 2, 'Baligang, Camalig, Albay, Bicol Region', 'Lugo, Borbon, Cebu, Central Visayas', 720.00, 9.00, 729.00, 'preparing', '2025-12-03 18:23:59'),
(4, 2, 'A. Dalusag, General Emilio Aguinaldo, Cavite, CALABARZON', 'San Rafael, San Felipe, Zambales, Central Luzon', 80.00, 9.00, 89.00, 'preparing', '2025-12-03 18:25:16'),
(5, 2, 'San Lorenzo, San Nicolas, Ilocos Norte, Ilocos Region', 'Lower Pulacan, Labangan, Zamboanga Del Sur, Zamboanga Peninsula', 1380.00, 9.00, 1389.00, 'preparing', '2025-12-03 21:19:03'),
(6, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 230.00, 9.00, 239.00, 'preparing', '2025-12-03 21:32:34'),
(7, 2, 'Linongan, Akbar, Basilan, BARMM', 'Marinawa, Bato, Catanduanes, Bicol Region', 500.00, 9.00, 509.00, 'preparing', '2025-12-03 21:34:56'),
(8, 2, 'Kilang, Galimuyod, Ilocos Sur, Ilocos Region', 'Lanipga, Carmen, Cebu, Central Visayas', 310.00, 9.00, 319.00, 'preparing', '2025-12-03 21:42:58'),
(9, 2, 'Magdapio, Pagsanjan, Laguna, CALABARZON', 'San Roque, Bolinao, Pangasinan, Ilocos Region', 420.00, 9.00, 429.00, 'preparing', '2025-12-03 22:09:55'),
(10, 2, 'Balibago, City of Angeles, Pampanga, Central Luzon', 'San Antonio (Pob.), Libjo, Dinagat Islands, Caraga', 260.00, 9.00, 269.00, 'preparing', '2025-12-03 22:17:13'),
(11, 2, 'Barangay 6 (Pob.), Camalig, Albay, Bicol Region', 'Nuevo Campo, San Benito, Surigao Del Norte, Caraga', 380.00, 9.00, 389.00, 'completed', '2025-12-03 22:26:18'),
(12, 2, 'Mongkay, Simunul, Tawi-Tawi, BARMM', 'Suyoc, Placer, Surigao Del Norte, Caraga', 910.00, 9.00, 919.00, 'preparing', '2025-12-03 22:27:51'),
(13, 2, 'Larap, Jose Panganiban, Camarines Norte, Bicol Region', 'Samoki, Bontoc, Mountain Province, CAR', 600.00, 9.00, 609.00, 'delivering', '2025-12-03 23:12:09'),
(14, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 670.00, 9.00, 679.00, 'delivering', '2025-12-04 00:27:53'),
(15, 2, 'Cagsao, Calabanga, Camarines Sur, Bicol Region', 'Beleng, Bayambang, Pangasinan, Ilocos Region', 490.00, 9.00, 499.00, 'delivering', '2025-12-04 05:31:14'),
(16, 2, 'Ayudante, City of Candon, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 100.00, 9.00, 109.00, 'delivering', '2025-12-04 05:51:23'),
(17, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Sigayan, Sultan Naga Dimaporo, Lanao Del Norte, Northern Mindanao', 830.00, 9.00, 839.00, 'delivering', '2025-12-04 07:46:12'),
(18, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 2170.00, 9.00, 2179.00, 'delivering', '2025-12-04 08:12:27'),
(19, 2, 'Coguit, Balatan, Camarines Sur, Bicol Region', 'San Jose, Flora, Apayao, CAR', 1860.00, 9.00, 1869.00, 'completed', '2025-12-04 08:45:12'),
(20, 2, 'San Jose-San Pablo (Pob.), Camaligan, Camarines Sur, Bicol Region', 'New Opon, Magsaysay, Davao Del Sur, Davao Region', 3100.00, 9.00, 3109.00, 'preparing', '2025-12-04 08:59:46'),
(21, 2, 'Dansalan, Buadiposo-Buntong, Lanao Del Sur, BARMM', 'Cabunga-an, Dagami, Leyte, Eastern Visayas', 310.00, 9.00, 319.00, 'completed', '2025-12-04 09:31:02'),
(22, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 290.00, 9.00, 299.00, 'completed', '2025-12-04 13:43:35'),
(23, 2, 'Poblacion, Morong, Bataan, Central Luzon', 'Longos, Calumpit, Bulacan, Central Luzon', 180.00, 9.00, 189.00, 'completed', '2025-12-04 13:51:49'),
(24, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Santa Cruz, Matungao, Lanao Del Norte, Northern Mindanao', 410.00, 9.00, 419.00, 'completed', '2025-12-04 21:36:21'),
(25, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 670.00, 9.00, 679.00, 'completed', '2025-12-05 22:43:56'),
(26, 5, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 310.00, 9.00, 319.00, 'completed', '2025-12-06 13:06:59'),
(27, 5, 'Iruhin South, City of Tagaytay, Cavite, CALABARZON', 'San Francisco, Rizal, Kalinga, CAR', 1120.00, 9.00, 1129.00, 'completed', '2025-12-06 13:58:26'),
(28, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 2010.00, 9.00, 2019.00, 'delivering', '2025-12-06 15:11:44'),
(29, 6, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Lingsat, City of San Fernando, La Union, Ilocos Region', 260.00, 9.00, 269.00, 'delivering', '2025-12-06 15:26:47'),
(30, 2, 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 'Conconig East, Santa Lucia, Ilocos Sur, Ilocos Region', 940.00, 9.00, 949.00, 'preparing', '2025-12-06 19:05:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(1, 1, 4, 'Adobo Manok', 180.00, 2),
(2, 1, 5, 'Peanut Cake', 80.00, 1),
(3, 2, 4, 'Adobo Manok', 180.00, 7),
(4, 2, 5, 'Peanut Cake', 80.00, 4),
(5, 3, 4, 'Adobo Manok', 180.00, 4),
(6, 4, 5, 'Peanut Cake', 80.00, 1),
(7, 5, 4, 'Adobo Manok', 180.00, 1),
(8, 5, 5, 'Peanut Cake', 80.00, 15),
(9, 6, 4, 'Adobo Manok', 180.00, 1),
(10, 6, 6, 'Juice', 50.00, 1),
(11, 7, 5, 'Peanut Cake', 80.00, 5),
(12, 7, 6, 'Juice', 50.00, 2),
(13, 8, 4, 'Adobo Manok', 180.00, 1),
(14, 8, 5, 'Peanut Cake', 80.00, 1),
(15, 8, 6, 'Juice', 50.00, 1),
(16, 9, 4, 'Adobo Manok', 180.00, 1),
(17, 9, 5, 'Peanut Cake', 80.00, 3),
(18, 10, 4, 'Adobo Manok', 180.00, 1),
(19, 10, 5, 'Peanut Cake', 80.00, 1),
(20, 11, 5, 'Peanut Cake', 80.00, 1),
(21, 11, 6, 'Juice', 50.00, 6),
(22, 12, 4, 'Adobo Manok', 180.00, 3),
(23, 12, 5, 'Peanut Cake', 80.00, 4),
(24, 12, 6, 'Juice', 50.00, 1),
(25, 13, 6, 'Juice', 50.00, 12),
(26, 14, 4, 'Adobo Manok', 180.00, 3),
(27, 14, 5, 'Peanut Cake', 80.00, 1),
(28, 14, 6, 'Juice', 50.00, 1),
(29, 15, 4, 'Adobo Manok', 180.00, 2),
(30, 15, 5, 'Peanut Cake', 80.00, 1),
(31, 15, 6, 'Juice', 50.00, 1),
(32, 16, 6, 'Juice', 50.00, 2),
(33, 17, 4, 'Adobo Manok', 180.00, 2),
(34, 17, 5, 'Peanut Cake', 80.00, 4),
(35, 17, 6, 'Juice', 50.00, 3),
(36, 18, 4, 'Adobo Manok', 180.00, 7),
(37, 18, 5, 'Peanut Cake', 80.00, 7),
(38, 18, 6, 'Juice', 50.00, 7),
(39, 19, 4, 'Adobo Manok', 180.00, 6),
(40, 19, 5, 'Peanut Cake', 80.00, 6),
(41, 19, 6, 'Juice', 50.00, 6),
(42, 20, 4, 'Adobo Manok', 180.00, 10),
(43, 20, 5, 'Peanut Cake', 80.00, 10),
(44, 20, 6, 'Juice', 50.00, 10),
(45, 21, 4, 'Adobo Manok', 180.00, 1),
(46, 21, 5, 'Peanut Cake', 80.00, 1),
(47, 21, 6, 'Juice', 50.00, 1),
(48, 22, 5, 'Peanut Cake', 80.00, 3),
(49, 22, 6, 'Juice', 50.00, 1),
(50, 23, 5, 'Peanut Cake', 80.00, 1),
(51, 23, 6, 'Juice', 50.00, 2),
(52, 24, 4, 'Adobo Manok', 180.00, 1),
(53, 24, 6, 'Juice', 50.00, 1),
(54, 24, 7, 'Dinakdakan', 180.00, 1),
(55, 25, 5, 'Peanut Cake', 80.00, 1),
(56, 25, 6, 'Juice', 50.00, 1),
(57, 25, 7, 'Dinakdakan', 180.00, 3),
(58, 26, 4, 'Adobong Manok', 180.00, 1),
(59, 26, 5, 'Peanut Cake', 80.00, 1),
(60, 26, 6, 'Mystery Juice', 50.00, 1),
(61, 27, 4, 'Adobong Manok', 180.00, 4),
(62, 27, 11, 'Rice', 50.00, 8),
(63, 28, 4, 'Adobong Manok', 180.00, 10),
(64, 28, 10, 'Ice Cream Sandwich Cake', 80.00, 1),
(65, 28, 11, 'Rice', 50.00, 1),
(66, 28, 12, 'Coke', 80.00, 1),
(67, 29, 4, 'Adobong Manok', 180.00, 1),
(68, 29, 5, 'Peanut Cake', 80.00, 1),
(69, 30, 4, 'Adobong Manok', 180.00, 1),
(70, 30, 5, 'Peanut Cake', 80.00, 1),
(71, 30, 6, 'Mystery Juice', 50.00, 1),
(72, 30, 7, 'Dinakdakan', 180.00, 1),
(73, 30, 8, 'Dinuguan', 180.00, 1),
(74, 30, 9, 'Pineapple Juice', 60.00, 1),
(75, 30, 10, 'Ice Cream Sandwich Cake', 80.00, 1),
(76, 30, 11, 'Rice', 50.00, 1),
(77, 30, 12, 'Coke', 80.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('delivery','admin','customer','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`) VALUES
(1, 'Ardy Aquino', 'ardy@gmail.com', '123', 'admin'),
(2, 'Brent Alabag', 'brent@gmail.com', 'ardy', 'customer'),
(3, 'Mark Lester', 'lester@gmail.com', '123', 'delivery'),
(5, 'brentna', 'brentna@gmail.com', '123', 'customer'),
(6, 'rosie', 'rosie@gmail.com', '123', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dashboard_monthly_orders`
--
ALTER TABLE `dashboard_monthly_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `month_label` (`month_label`);

--
-- Indexes for table `dashboard_monthly_revenue`
--
ALTER TABLE `dashboard_monthly_revenue`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `month_label` (`month_label`);

--
-- Indexes for table `dashboard_stats`
--
ALTER TABLE `dashboard_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `metric_key` (`metric_key`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dashboard_monthly_orders`
--
ALTER TABLE `dashboard_monthly_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

--
-- AUTO_INCREMENT for table `dashboard_monthly_revenue`
--
ALTER TABLE `dashboard_monthly_revenue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dashboard_stats`
--
ALTER TABLE `dashboard_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
