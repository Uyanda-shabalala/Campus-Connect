-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2025 at 07:57 PM
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
-- Database: `campus_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `userId`, `content`, `image_path`, `created_at`) VALUES
(12, 8, 'Stay Vigilant! 💻🛡️\r\n\r\n✅ Keep devices updated\r\n✅ Use strong passwords\r\n✅ Think before clicking links or', 'uploads/post_videos/68f94d281338c_AQMvtb1yGP0gZfCqHhiZ-ednPlfRo8G9n0PuU1OqaU5xGNhsT_itsLT5CbtTUUTKSvYEbM5-min1R6mMJjQ7UMDFk9e3y5dyjrtyA80.mp4', '2025-10-22 21:31:20'),
(13, 7, '🚀 Join the Wits Developer Society 2026 Committee! 💙💛 We’re on the lookout for passionate Witsies ', 'uploads/post_images/68fbbef051e90_wits dev society.webp', '2025-10-24 18:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `userId` int(11) NOT NULL,
  `userName` varchar(128) NOT NULL,
  `userLastName` varchar(128) NOT NULL,
  `userUsername` varchar(128) NOT NULL,
  `userEmail` varchar(128) NOT NULL,
  `userPwd` varchar(128) NOT NULL,
  `userCampus` varchar(128) NOT NULL,
  `userProfilePic` varchar(50) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`userId`, `userName`, `userLastName`, `userUsername`, `userEmail`, `userPwd`, `userCampus`, `userProfilePic`, `reset_token`, `reset_expires`) VALUES
(6, 'uyanda shabalala', '', 'ushabalala', 'uyandashabalala33@gmail.com', '$2y$10$HnFMyjxi4Prrr3saiPIApuqi3pfmtuwNhynTKjbHC5Py8ZJPO1g3.', 'Richfield Graduate Institute of Technology', 'uploads/user_pp/68f7233396b9e_20241213_105848.jpg', NULL, NULL),
(7, 'uyanda', '', 'its_ya_boi', 'ben10come@gmail.com', '$2y$10$qhqafe75.SfUPBAjMtSlQ.Fg6tn6IqRvFvc9gjcxwEPunKNr0tsNS', 'Richfield Graduate Institute of Technology', 'uploads/user_pp/68fc58c74e170_IMG-20251019-WA0049.', '6cc1460e003efc6f95aea70abfdd2823', '2025-10-31 19:52:47'),
(8, 'john Doe', '', 'JohnD', 'JohnDoe@gmail.com', '$2y$10$ngs5rZ.7Dnr7LHxE2feDs.40ZpwL36IqFr725ABv8yGRBtfLMm/nm', 'Stellenbosch University', 'uploads/user_pp/68f91e06919f6.jpg', NULL, NULL),
(9, 'Jane Doe', '', 'JaneD', 'JaneDoe@gmail.com', '$2y$10$4D4koI9Rh9tvce7/NJFL6OGMdkLl0yuJAKNPRoR2u5YZ4jKNDx1LK', 'Richfield Graduate Institute of Technology', 'uploads/user_pp/6904d279ee56c_janedoe.jpg', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users_info` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users_info` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users_info` (`userId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
