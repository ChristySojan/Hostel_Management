-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 19, 2025 at 04:06 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

DROP TABLE IF EXISTS `record`;
CREATE TABLE IF NOT EXISTS `record` (
  `slno` int NOT NULL AUTO_INCREMENT,
  `usn` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `status` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`slno`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`slno`, `usn`, `date`, `time`, `status`) VALUES
(34, '4CB22CG004', '2024-12-11', '19:00:17', 'Present'),
(35, '4CB22CG025', '2024-12-11', '22:01:31', 'Absent'),
(36, '4CB22CG004', '2024-12-11', '22:01:47', 'Absent'),
(37, '4CB22CG025', '2024-12-11', '22:01:47', 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `block` varchar(3) NOT NULL,
  `room` varchar(3) NOT NULL,
  `capacity` int NOT NULL,
  `available` tinyint NOT NULL,
  UNIQUE KEY `block` (`block`,`room`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`block`, `room`, `capacity`, `available`) VALUES
('3B', '001', 2, 2),
('2B', '001', 2, 1),
('1B', '001', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `usn` varchar(10) NOT NULL,
  `name` tinytext NOT NULL,
  `cyear` int NOT NULL,
  `phno` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `block` varchar(3) NOT NULL,
  `room_no` varchar(3) NOT NULL,
  `entrykey` varchar(3) NOT NULL,
  `last_promoted_at` date DEFAULT NULL,
  PRIMARY KEY (`usn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usn`, `name`, `cyear`, `phno`, `block`, `room_no`, `entrykey`, `last_promoted_at`) VALUES
('4CB22CG004', 'Charan', 3, '8618492477', '1B', '001', '004', '2024-12-11'),
('4CB22CG025', 'Saiprem', 3, '9635241778', '2B', '001', '025', '2024-12-11');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

DROP TABLE IF EXISTS `user_login`;
CREATE TABLE IF NOT EXISTS `user_login` (
  `id` varchar(5) NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id`, `pass_hash`) VALUES
('user', '$2y$10$G8eRviH.PFb.gj6JQ5HUXePCuoG0EtDDU/9aGbBm8Uv.FY/ccGJYO');

-- --------------------------------------------------------

--
-- Table structure for table `warden_login`
--

DROP TABLE IF EXISTS `warden_login`;
CREATE TABLE IF NOT EXISTS `warden_login` (
  `id` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `warden_login`
--

INSERT INTO `warden_login` (`id`, `pass_hash`) VALUES
('warden', '$2y$10$pYI.Rf0bM1j5gMonWsYOPuwTJXkOwqVhyb2HwaRu2Pp5TeqENrWgG');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
