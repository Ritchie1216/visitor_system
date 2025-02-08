-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 11:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visitor_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$B98kiPC9YNsMFK6mR1SlM.2euJ0emaa35570zwtiRK7a4OpscJCzG');

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` int(11) NOT NULL,
  `visitor_id` int(11) DEFAULT NULL,
  `qr_code` text NOT NULL,
  `generated_at` datetime NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_codes`
--

INSERT INTO `qr_codes` (`id`, `visitor_id`, `qr_code`, `generated_at`, `expires_at`) VALUES
(23, 27, 'chengwei_20241204', '2024-12-04 03:24:13', '2024-12-05 00:00:00'),
(24, 28, 'lize_20250106', '2025-01-06 01:24:18', '2025-01-07 00:00:00'),
(28, 33, 'qw_20250125', '2025-01-06 14:17:33', '2025-01-26 00:00:00'),
(30, 36, 'Chah_20250115', '2025-01-15 09:44:15', '2025-01-22 00:00:00'),
(31, 44, 'Richie Chah_20250115', '2025-01-15 09:56:54', '2025-01-26 00:00:00'),
(32, 47, 'sy_20250116', '2025-01-15 09:58:38', '2025-01-18 00:00:00'),
(36, 51, 'Richie Chah_20250130', '2025-01-15 10:15:17', '2025-02-02 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `unit`, `phone`) VALUES
(9, 'edmon', 'edmond@gmail.com', '$2y$10$i1eLT083vbbglS.iGMa7ouD4vx9O9kbUXDx/wLbjQpmv6lFsUcbPy', '199', '0115678032'),
(11, 'Richie Chah', 'ritchie121600@gmail.com', '$2y$10$7CJO6Y4jiN8OofyHCubD6.9igEI4iApfGRTsb6nky9G8JZmQm6Yvi', '201', '0123456789'),
(12, 'rrrr', 'tfdshd123@gmail.com', '$2y$10$nPKGgq2uxcoaCn5ZolK9meLamrrFUsm7joHdsMuA4cOhDje7eQ8km', '88', '12345678'),
(13, 'lekhong', 'lekhong121600@gmail.com', '$2y$10$jz.wmRVUM2GFhBToAw5fUuDaCDyLgoDh.yMIkHgfJ8DgLV9Jbg.GS', '11', '(012) 457-8967'),
(14, 'yizhang and zoe', 'yizhang123@gmail.com', '$2y$10$//Ht8bVHYhAHSnIpXPN/pubrZ5BK.rhoWSUuVPxqbHk2qNmm76lUu', '2', '(123) 456-783');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `IC` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `visitor_code` varchar(255) NOT NULL,
  `qr_code` text DEFAULT NULL,
  `visit_date` date NOT NULL,
  `status` enum('pending','approved','rejected','checked_in') DEFAULT 'pending',
  `owner_id` int(11) DEFAULT NULL,
  `valid_days` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `name`, `IC`, `email`, `phone`, `visitor_code`, `qr_code`, `visit_date`, `status`, `owner_id`, `valid_days`) VALUES
(27, 'chengwei', 2147483647, 'chengwei@gmail.com', '01164533550', 'chengwei_20241204', '../qrcodes/chengwei_20241204.png', '2024-12-04', 'approved', NULL, 1),
(28, 'lize', 2147483647, 'lize@gmail.com', '0115678032', 'lize_20250106', '../qrcodes/lize_20250106.png', '2025-01-06', 'approved', NULL, 1),
(33, 'qingwei', 2147483647, 'ritchie121990@gmail.com', '12345678', 'qingwei_20250123', '../qrcodes/qingwei_20250123.png', '2025-01-23', 'approved', 11, 1),
(36, 'Chah', 2147483647, 'ritchie121600@gmail.com', '(123) 456-7890', 'Chah_20250115', '../qrcodes/Chah_20250115.png', '2025-01-15', 'approved', 11, 5),
(44, 'Richie ', 2147483647, 'ritchie121600@gmail.com', '(789) 954-5665', 'Richie _20250115', '../qrcodes/Richie _20250115.png', '2025-01-15', 'approved', 9, 11),
(47, 'sy', 2147483647, 'sy21600@gmail.com', '(123) 456-7890', 'sy_20250116', '../qrcodes/sy_20250116.png', '2025-01-16', 'approved', 9, 2),
(51, 'Richie Chah', 2147483647, 'ritchie121600@gmail.com', '(012) 345-6789', 'Richie Chah_20250130', '../qrcodes/Richie Chah_20250130.png', '2025-01-30', 'approved', 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `visitor_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `visit_date` datetime DEFAULT NULL,
  `status` enum('pending','approved','rejected','checked_in','checked_out') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visitor_id` (`visitor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `visitor_code` (`visitor_code`),
  ADD UNIQUE KEY `owner_id` (`owner_id`,`visit_date`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visitor_id` (`visitor_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `visitors_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visits_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
