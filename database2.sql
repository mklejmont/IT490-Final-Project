-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 09, 2024 at 10:00 PM
-- Server version: 8.0.36-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_id` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pwd_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `height` decimal(3,2) NOT NULL DEFAULT '0.00',
  `weight` int NOT NULL DEFAULT '0',
  `time_available` int NOT NULL DEFAULT '30',
  `gym_access` tinyint(1) NOT NULL DEFAULT '0',
  `exercise_goals` varchar(20) NOT NULL DEFAULT 'Build Muscle',
  `equipment` varchar(500) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`user_id`, `username`, `email`, `pwd_hash`, `height`, `weight`, `time_available`, `gym_access`, `exercise_goals`, `equipment`) VALUES
(25, 'jimbo', 'joeyant1367@gmail.com', '$2y$10$H72Hd7aMME8tOAsax3vh.O3/EbRAP3WTpXKMysUWt4grteky4B4Z2', '7.70', 100, 12, 1, 'Build Muscle', 'rope');

-- --------------------------------------------------------

--
-- Table structure for table `push_notifications`
--

CREATE TABLE `push_notifications` (
  `email` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `push_notifications`
--

INSERT INTO `push_notifications` (`email`, `phone`) VALUES
('ja639@njit.edu', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('[value-1]', '[value-2]'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176'),
('joeyant1367@gmail.com', '9084771176');

-- --------------------------------------------------------

--
-- Table structure for table `user_ratings`
--

CREATE TABLE `user_ratings` (
  `user` varchar(20) NOT NULL,
  `exercise` varchar(50) NOT NULL,
  `rating` int NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_ratings`
--

INSERT INTO `user_ratings` (`user`, `exercise`, `rating`) VALUES
('demo', 'burpee', 1),
('j', 'burpee', 1),
('jimbo', 'air bike', 1),
('jimbo', 'burpee', 1),
('jimbo', 'jump rope', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_ratings`
--
ALTER TABLE `user_ratings`
  ADD PRIMARY KEY (`user`,`exercise`);

--
-- AUTO_INCREMENT for dumped tables
--

-- Table structure for table `user_calendar`
CREATE TABLE `user_calendar` (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `exercise_name` varchar(50) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
