-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2023 at 12:57 PM
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
-- Database: `mandilinks`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `permissions` varchar(255) DEFAULT NULL,
  `type` tinyint(4) DEFAULT 1 COMMENT '0= super_admin, 1 = staff user',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0 = inactive, 1 = active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password`, `phone_no`, `permissions`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$12$1SBXOjUAPRrZz045jr.jUep.bTIWnde.rKGE3kzxBSqu0INAU/y5S', NULL, NULL, 0, 1, '2022-04-15 12:25:42', '2022-11-01 07:25:41'),
(2, 'Editor', 'editor@gmail.com', '$2y$10$eNh4woW4n/ElMf.DedPs5O73un4022n8wFvRY0T285X2F5GKrQO3y', '03009988777', 'add,edit', 1, 0, '2022-11-01 08:11:31', '2022-11-01 08:16:00'),
(3, 'Email Sender', 'sender@gmail.com', '$2y$10$0YBLmvswTL9BltJU7EruPO8JOiuHtD.WY8qROohMj5r6epVzsL0LG', '030009988777', 'email', 1, 1, '2022-11-01 08:12:37', '2022-11-01 08:12:37'),
(4, 'Admin', 'admin_user@gmail.com', '$2y$10$8MBtfFUTwsN6dpO5/RLOKelGDGW.D39FyB2usVtrwucHCOFfRJBRu', '03009988777', 'add,edit,email', 1, 1, '2022-11-01 08:15:07', '2022-11-01 08:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `meta_tag` varchar(255) DEFAULT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `meta_tag`, `meta_key`, `meta_value`) VALUES
(1, 'project', 'site_title', 'Mandi Links'),
(2, 'project', 'short_site_title', 'ML'),
(3, 'project', 'site_logo', 'logo.svg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `zip` int(5) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT 'https://explorelogicsit.net/mandilinks/public/assets/upload_images/user.png',
  `image_name` varchar(255) DEFAULT 'user.png',
  `otp` int(4) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=inactive, 1=active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone_no`, `city`, `address`, `zip`, `image_path`, `image_name`, `otp`, `status`, `created_at`, `updated_at`) VALUES
(78, 'John Doe', 'calebjanaltair@gmail.com', '$2y$12$1SBXOjUAPRrZz045jr.jUep.bTIWnde.rKGE3kzxBSqu0INAU/y5S', '+923394008600', 'Lahore', '4170 Barkat Market', 54000, 'https://explorelogicsit.net/mandilinks/public/assets/upload_images/user.png', 'user.png', NULL, 1, '2023-12-26 10:47:34', '2023-12-26 11:28:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
