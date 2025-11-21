-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 21, 2025 at 04:51 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u967494580_brgy413`
--

-- --------------------------------------------------------

--
-- Table structure for table `brgyclearance`
--

CREATE TABLE `brgyclearance` (
  `id` int(14) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `age` int(3) NOT NULL,
  `birthday` date NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `civilStat` varchar(100) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `clearPur` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brgyid`
--

CREATE TABLE `brgyid` (
  `id` int(11) NOT NULL,
  `brgyId` int(50) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `birthday` varchar(100) NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brgyid`
--

INSERT INTO `brgyid` (`id`, `brgyId`, `fname`, `birthday`, `date_requested`, `status`, `user_id`) VALUES
(27, 123456, 'Anne Patrice Arbolente ', '2025-01-29', '2025-10-27 05:45:40', 'Approved', NULL),
(28, 123456, 'Anne Patrice Arbolente ', '2025-10-16', '2025-10-28 06:46:39', 'Rejected', NULL),
(29, 123456, 'RC Espino', '2025-10-31', '2025-10-28 15:18:39', 'Approved', NULL),
(30, 123456, 'Tricia Anne Arbolente', '2025-10-29', '2025-10-29 02:43:05', 'Approved', NULL),
(31, 15, 'Rc', '2002-02-21', '2025-10-29 03:01:30', 'Pending', NULL),
(32, 123456, 'Tricia Anne Arbolente', '2025-10-31', '2025-10-29 03:14:59', 'Rejected', NULL),
(33, 123456, 'Tricia Anne Arbolente', '2025-10-29', '2025-10-29 03:49:11', 'Rejected', NULL),
(34, 123, 'Raven Jeanne Magallanes Catindig', '2025-11-28', '2025-11-20 18:13:40', 'Approved', 27);

-- --------------------------------------------------------

--
-- Table structure for table `brgyindigency`
--

CREATE TABLE `brgyindigency` (
  `id` int(14) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brgyofficials`
--

CREATE TABLE `brgyofficials` (
  `id` int(14) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brgyofficials`
--

INSERT INTO `brgyofficials` (`id`, `name`, `position`, `description`, `email`, `contact`, `picture`, `created_at`) VALUES
(7, 'Ricardo \"Ric\" V. Manalo', 'Punong Barangay (Barangay Captain)', 'Head of the Council; Peace and Order, Financial Management.', 'ricardo.manalo.brgy@example.com', '09175551201', 'uploads/officials/official_68ff79ff197858.20915334.png', '2025-10-27 13:56:15'),
(8, 'Maria Luisa \"Malu\" F. Reyes', 'Barangay Kagawad 1 (Council Member)', 'Committee on Health and Sanitation, Women and Family Welfare.', 'malu.reyes.brgy@example.com', '09175551202', 'uploads/officials/official_69002f5d0b86d0.23445608.png', '2025-10-28 02:50:05'),
(9, 'Jose \"Jojo\" T. Dela Cruz', 'Barangay Kagawad 2 (Council Member)', 'Committee on Infrastructure, Public Works, and Safety.', 'jojo.delacruz.brgy@example.com', '09175551203', 'uploads/officials/official_69002f9a5f8901.96131592.png', '2025-10-28 02:51:06'),
(10, 'Sonia L. Garcia', 'Barangay Kagawad 3 (Council Member)', 'Committee on Education, Culture, and Sports.', 'sonia.garcia.brgy@example.com', '0917-555-12', 'uploads/officials/official_69002fe476a010.82917855.png', '2025-10-28 02:52:20'),
(11, 'Liza M. Pascual', 'Barangay Secretary', 'Records Management, Documentation, and Public Information.', 'liza.pascual.brgy@example.com', '09175551205', 'uploads/officials/official_69003023d31f70.56986706.png', '2025-10-28 02:53:23'),
(12, 'Teresita Pascual', 'Barangay Treasurer', 'Budget and Financial Reporting, Fund Collection.', 'teresita.pascual.Brgy@example.com', '09175551206', 'uploads/officials/official_69003077425362.83860038.png', '2025-10-28 02:54:47'),
(13, 'Joshua \"Josh\" B. Lim', 'SK Chairperson (Youth Council Head)', 'Youth Development Programs, SK Funds Management.', 'josh.lim.sk@example.com', '0917555120', 'uploads/officials/official_690030ba8f26e0.98223767.png', '2025-10-28 02:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `busclearance`
--

CREATE TABLE `busclearance` (
  `id` int(14) NOT NULL,
  `appDate` varchar(100) NOT NULL,
  `appType` varchar(100) NOT NULL,
  `busName` varchar(255) NOT NULL,
  `busAdd` varchar(255) NOT NULL,
  `natureOfBus` varchar(255) NOT NULL,
  `busOwnType` varchar(255) NOT NULL,
  `LocStatus` varchar(100) NOT NULL,
  `parkingLot` int(100) NOT NULL,
  `capitalization` int(255) NOT NULL,
  `salesRcpts` int(255) NOT NULL,
  `opeFullName` varchar(255) NOT NULL,
  `contactNo` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nameSig` varchar(255) NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(14) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `date` date NOT NULL,
  `issue` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `picture` varchar(255) NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','processing','resolved') NOT NULL DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'Foreign key to users table (nullable for guests)',
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) DEFAULT 0 COMMENT 'User rating from 1-5 stars (0 = no rating)',
  `suggestions` text DEFAULT NULL COMMENT 'User suggestions for website improvement',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `user_id`, `fullname`, `email`, `subject`, `message`, `rating`, `suggestions`, `created_at`, `updated_at`) VALUES
(36, NULL, 'Anne', 'annepatricearbolente@gmail.com', 'Suggestion: Organizing a Community Clean-Up Drive', 'asdfghjkl;asdfghjklasdhjkl', 2, 'khalhsfklasoifdsbldfhakdfd', '2025-10-27 05:21:47', '2025-10-27 05:21:47'),
(37, NULL, 'Anne', 'annepatricearbolente@gmail.com', 'Suggestion: Organizing a Community Clean-Up Drive', 'asdfghjkl', 5, 'wew', '2025-10-27 12:49:55', '2025-10-27 12:49:55'),
(38, NULL, 'Anne', 'annepatricearbolente@gmail.com', 'Suggestion: Organizing a Community Clean-Up Drive', 'qwertyuiopasdfghjkl', 4, 'nyaknyaknyaknyaknyaknyak', '2025-10-28 13:53:04', '2025-10-28 13:53:04'),
(39, NULL, 'Anne', 'ap@gmail.com', 'Suggestion: Organizing a Community Clean-Up Drive', 'asdfghjkertiofghjklbnm', 5, 'dgjhbj,bmnmbmbnjl', '2025-10-28 14:32:08', '2025-10-28 14:32:08'),
(40, NULL, 'Anne', 'ap@gmail.com', 'Suggestion: Organizing a Community Clean-Up Drive', 'asdfghjkertiofghjklbnm', 5, 'dgjhbj,bmnmbmbnjl', '2025-10-28 14:33:03', '2025-10-28 14:33:03'),
(41, NULL, 'asdfghjkl', 'rc@gmail.com', 'kshdjlafahhdfasjdfkalhaj', 'kfjkasfjdksjdffjopdfiufsjfksjfkjsaf ajfdlajfasjdfksafjla', 5, 'dhlsafn pasnsufojaf', '2025-10-28 15:22:46', '2025-10-28 15:22:46'),
(42, NULL, 'Anne', 'annepatricearbolente@gmail.com', 'Urgent: Dangerous Pothole and Poor Lighting on Elm St. near 5th Ave.', 'fjlasdfkjalfjsajdf;lasfjasldf', 5, 'adskljflsa;djflaskjfaljdfkla;jflafjdkalfas', '2025-10-29 02:45:22', '2025-10-29 02:45:22'),
(43, NULL, 'vhvbhb', 'annepatricearbolente@gmail.com', 'Urgent: Dangerous Pothole and Poor Lighting on Elm St. near 5th Ave.', 'bhbhbbj', 2, 'jjkbkjjbjbkj', '2025-10-29 03:03:02', '2025-10-29 03:03:02'),
(44, NULL, 'Anne', 'annepatricearbolente@gmail.com', 'Urgent: Dangerous Pothole and Poor Lighting on Elm St. near 5th Ave.', 'djfsalfjalkfjaljfaslfjsdlkfjlsanvc,asdnc,ad', 5, 'sdajfal;fjdkasjfksdfjalsjflasjflas', '2025-10-29 03:50:40', '2025-10-29 03:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `news_updates`
--

CREATE TABLE `news_updates` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date_posted` datetime DEFAULT current_timestamp(),
  `posted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_updates`
--

INSERT INTO `news_updates` (`id`, `title`, `content`, `date_posted`, `posted_by`) VALUES
(15, 'Disaster Response and Community Safety', 's families leave their homes to visit cemeteries for the observance of Undas (All Saints\' Day and All Souls\' Day).', '2025-10-27 21:53:23', 'Patricia Arbolente'),
(19, 'dlskjkaljfslajdflasf', 'sjdflsajflasjflasdka', '2025-10-29 03:52:05', 'Patricia Arbolente');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(14) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','user','secretary','kagawad','captain') NOT NULL DEFAULT 'user',
  `status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_active` varchar(50) DEFAULT '1 minute ago'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `contact`, `password`, `role`, `status`, `created_at`, `last_active`) VALUES
(16, 'Patricia', 'Arbolente', 'maparbolente@tip.edu.ph', '09667403386', '$2y$10$t5sgtQYxgcTIaapCGZKwMetx4GyG3B23MQU5LwKzAWepWlG5BQkvm', 'admin', 'Active', '2025-10-26 09:02:14', '2025-11-20 18:41:02'),
(23, 'Rozeya', 'Espina', 'rcespino@gmail.com', '09667403386', '$2y$10$RuZhY42i.zOZELXpZnJ0/.w3dUgLx4cYPRCSDLelC2jYxqZEhskQy', 'captain', 'Active', '2025-11-20 18:03:11', '2025-11-20 18:46:04'),
(24, 'Raven', 'Catindig', 'rjcatindig@gmail.com', '09929305853', '$2y$10$F5B3O4zx.Y0Fs0QYoAaUaO3g4F9SeappZN64BGLOLiQ/QySZiaCvu', 'secretary', 'Active', '2025-11-20 18:03:11', '2025-11-20 18:43:30'),
(25, 'Patricia', 'Arbolente', 'aparbolente@gmail.com', '09636165880', '$2y$10$uafOKots1NVgIxaeiQvoHukwNSJv3ZZLKTP.U6Dytmq0FRCAoNvLu', 'kagawad', 'Active', '2025-11-20 18:03:11', '2025-11-20 18:42:45'),
(26, 'Joe', 'Smith', 'jsmith@gmail.com', '09967403386', '$2y$10$I8Gox9xm1b4lrXetUcnKqOYeRAzWPiP/DdWvButFQVBFfC7.a6vJC', 'user', 'Active', '2025-11-20 18:03:11', '2025-11-20 18:05:59'),
(27, 'Raven Jeanne', 'Catindig', 'catindigravenjeanne@gmail.com', '09674806895', '$2y$10$XT7QhzlYJMp3L1Ga8f2N4uCHX2fxKEtunSdV/HVfYTI.DqjJHYIBu', 'user', 'Active', '2025-11-20 18:13:01', '2025-11-20 18:43:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brgyclearance`
--
ALTER TABLE `brgyclearance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `brgyid`
--
ALTER TABLE `brgyid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_brgyid_user` (`user_id`);

--
-- Indexes for table `brgyindigency`
--
ALTER TABLE `brgyindigency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_brgyindigency_user` (`user_id`);

--
-- Indexes for table `brgyofficials`
--
ALTER TABLE `brgyofficials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `busclearance`
--
ALTER TABLE `busclearance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_busclearance_user` (`user_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `news_updates`
--
ALTER TABLE `news_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brgyclearance`
--
ALTER TABLE `brgyclearance`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `brgyid`
--
ALTER TABLE `brgyid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `brgyindigency`
--
ALTER TABLE `brgyindigency`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `brgyofficials`
--
ALTER TABLE `brgyofficials`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `busclearance`
--
ALTER TABLE `busclearance`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `news_updates`
--
ALTER TABLE `news_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brgyclearance`
--
ALTER TABLE `brgyclearance`
  ADD CONSTRAINT `brgyclearance_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_brgyclearance_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `brgyid`
--
ALTER TABLE `brgyid`
  ADD CONSTRAINT `fk_brgyid_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `brgyindigency`
--
ALTER TABLE `brgyindigency`
  ADD CONSTRAINT `brgyindigency_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_brgyindigency_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `busclearance`
--
ALTER TABLE `busclearance`
  ADD CONSTRAINT `busclearance_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_busclearance_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_complaints_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
