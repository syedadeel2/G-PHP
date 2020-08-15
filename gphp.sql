-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2020 at 12:55 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `app_key` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `app_api_slug` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `name`, `description`, `app_key`, `app_api_slug`) VALUES
(8, '24h Fitness Gym', 'Fitness mobile app store', 'b5327a24-9604-4a1c-b4fe-252b7d7e0af6', 'adeel');

--
-- Triggers `applications`
--
DELIMITER $$
CREATE TRIGGER `remove_whitelists` AFTER DELETE ON `applications` FOR EACH ROW DELETE FROM domain_whitelist WHERE domain_whitelist.application_id = old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `domain_whitelist`
--

CREATE TABLE `domain_whitelist` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `domain` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `ip_address` varchar(16) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_stats`
--

CREATE TABLE `request_stats` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `total_request` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_response` int(11) NOT NULL,
  `total_failed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `domain_whitelist`
--
ALTER TABLE `domain_whitelist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_ip_address` (`ip_address`),
  ADD KEY `idx_domain` (`domain`);

--
-- Indexes for table `request_stats`
--
ALTER TABLE `request_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `domain_whitelist`
--
ALTER TABLE `domain_whitelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `request_stats`
--
ALTER TABLE `request_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
