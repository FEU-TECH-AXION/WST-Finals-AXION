-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2025 at 03:59 PM
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
-- Database: `axiondatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrow_log`
--

CREATE TABLE `borrow_log` (
  `borrow_id` int(11) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `borrow_date` datetime NOT NULL,
  `expected_return_date` datetime DEFAULT NULL,
  `status` enum('borrowed','returned') DEFAULT 'borrowed',
  `date_created` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` int(11) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `action` enum('borrowed','returned','status_change') NOT NULL,
  `borrowed_date` datetime DEFAULT NULL,
  `returned_date` datetime DEFAULT NULL,
  `previous_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` varchar(20) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_type` enum('equipment','accessory') NOT NULL,
  `parent_item_id` varchar(20) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `item_condition` enum('Good','Fair','Poor') NOT NULL,
  `location` varchar(50) DEFAULT NULL,
  `status` enum('active','unusable','under repair') NOT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `item_name`, `item_type`, `parent_item_id`, `quantity`, `item_condition`, `location`, `status`, `date_created`, `date_updated`) VALUES
('EQ-0001', 'Laptop', 'equipment', NULL, 10, 'Good', 'IT Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0002', 'DLP Projector', 'equipment', NULL, 5, 'Good', 'AV Room', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0003', 'Keyboard & Mouse Set', 'equipment', NULL, 8, 'Good', 'Mac Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0004', 'Wacom Drawing Tablet', 'equipment', NULL, 6, 'Good', 'Design Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0005', 'Speaker Set', 'equipment', NULL, 4, 'Good', 'Audio Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0006', 'Webcam', 'equipment', NULL, 10, 'Good', 'IT Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0007', 'Lab Room Keys', 'equipment', NULL, 15, 'Good', 'Admin Office', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0008', 'Cable Crimping Tool', 'equipment', NULL, 5, 'Good', 'Electronics Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('EQ-0009', 'Cable Tester', 'equipment', NULL, 5, 'Good', 'Electronics Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('AC-0001', 'Laptop Charger', 'accessory', 'EQ-0001', 10, 'Good', 'IT Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('AC-0002', 'Extension Cord', 'accessory', 'EQ-0002', 5, 'Good', 'AV Room', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('AC-0003', 'VGA/HDMI Cable', 'accessory', 'EQ-0002', 5, 'Good', 'AV Room', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('AC-0004', 'Power Cable', 'accessory', 'EQ-0002', 5, 'Good', 'AV Room', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('AC-0005', 'Lightning Cable', 'accessory', 'EQ-0003', 8, 'Good', 'Mac Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52'),
('AC-0006', 'Wacom Pen', 'accessory', 'EQ-0004', 6, 'Good', 'Design Lab', 'active', '2025-11-29 21:22:52', '2025-11-29 21:22:52');

--
-- Triggers `inventory`
--
DELIMITER $$
CREATE TRIGGER `trg_inventory_before_insert` BEFORE INSERT ON `inventory` FOR EACH ROW BEGIN
    DECLARE prefix VARCHAR(10);
    DECLARE max_id INT;

    -- Set prefix based on item_type
    IF NEW.item_type = 'equipment' THEN
        SET prefix = 'EQ-';
    ELSEIF NEW.item_type = 'accessory' THEN
        SET prefix = 'AC-';
    ELSE
        SET prefix = 'ITEM-'; -- fallback
    END IF;

    -- Only generate ID if empty
    IF NEW.item_id IS NULL OR NEW.item_id = '' THEN
        SET max_id = (SELECT IFNULL(MAX(CAST(SUBSTRING(item_id, LENGTH(prefix)+1) AS UNSIGNED)), 0)
                      FROM inventory
                      WHERE item_id LIKE CONCAT(prefix, '%'));

        SET NEW.item_id = CONCAT(prefix, LPAD(max_id + 1, 4, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `reserved_date` date NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('active','cancelled','completed') DEFAULT 'active',
  `date_created` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('itso','associate','student') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `date_created` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `profile_photo`, `password`, `role`, `status`, `date_created`, `date_updated`) VALUES
('ASC001', 'Default Associate', 'associate@example.com', 'default-avatar.png', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'associate', 'active', '2025-11-29 16:58:53', '2025-11-29 16:59:18'),
('ASC002', 'Alexa Mhel V. Gagan', 'slsuls.gaganalexa@gmail.com', 'default-avatar.png', '$2y$10$1ROi.eXr2KsyRsi6bL4YTOln67NybR.jQphIoXLQnwZFoNJFyFpce', 'associate', 'active', '2025-11-29 12:55:34', '2025-11-29 12:55:34'),
('ASC003', 'John Michael', 'janmikel@gmail.com', 'default-avatar.png', '$2y$10$dVTvVOASTpatS7.crIMtsO0br.4W5DHWVJxWbjLoS.EDtF9fgUrcy', 'associate', 'active', '2025-11-29 13:18:00', '2025-11-29 13:18:15'),
('ITSO001', 'Admin User', 'admin@example.com', 'default-avatar.png', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'itso', 'active', '2025-11-29 13:09:52', '2025-11-29 19:17:12'),
('STD001', 'Default Student', 'student@example.com', 'default-avatar.png', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active', '2025-11-29 13:18:40', '2025-11-29 13:03:27'),
('STD002', 'Pork Sinigang', 'mhelgagan18@gmail.com', NULL, '$2y$10$/0O0vs5Ldino7oFDGuU4cenGMRH.Dt./3jMiw6cDp7cn4bq3wPGge', 'associate', 'active', '2025-11-29 12:44:00', '2025-11-29 13:05:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_token` (`token`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
