-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2021 at 12:30 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tatva_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `recurrence` varchar(255) DEFAULT NULL,
  `select_one` int(11) DEFAULT NULL,
  `select_two` int(11) DEFAULT NULL,
  `select_three` int(11) DEFAULT NULL,
  `select_four` int(11) DEFAULT NULL,
  `select_five` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `start_date`, `end_date`, `recurrence`, `select_one`, `select_two`, `select_three`, `select_four`, `select_five`, `created`, `modified`) VALUES
(4, 'Hello All', '2021-04-11', '2021-04-17', 'first_repeat', 3, 3, 1, 0, 3, '2021-04-17 13:50:54', '2021-04-17 13:54:39'),
(5, 'Simple Testing', '2021-04-18', '2021-04-24', 'first_repeat', 1, 2, 2, 1, 6, '2021-04-17 13:51:45', '2021-04-17 15:29:36'),
(7, 'this is testing.', '2021-04-01', '2021-04-30', 'second_repeat', 1, 1, 1, 0, 1, '2021-04-17 14:14:59', '2021-04-17 14:15:40'),
(8, 'New Test', '2021-04-01', '2021-04-30', 'second_repeat', 1, 1, 2, 3, 3, '2021-04-17 14:16:07', '2021-04-17 14:33:06'),
(9, 'Today', '2021-04-01', '2021-04-30', 'first_repeat', 1, 1, 1, 0, 1, '2021-04-17 14:21:49', '2021-04-17 14:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `admin_email` varchar(150) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `site_logo` varchar(255) DEFAULT NULL,
  `date_format` varchar(100) DEFAULT NULL,
  `meta_title` varchar(150) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `admin_email`, `site_name`, `site_logo`, `date_format`, `meta_title`, `created`, `modified`) VALUES
(1, 'admin@gmail.com', '', NULL, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `role_id` int(11) DEFAULT '2' COMMENT '1 = admin, 2 = user, 3 = subadmin',
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `team_name` varchar(255) DEFAULT NULL,
  `date_of_bith` varchar(50) DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0=Female, 1=male',
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `address` text,
  `image` varchar(255) DEFAULT NULL,
  `fb_id` varchar(50) DEFAULT NULL,
  `google_id` varchar(50) DEFAULT NULL,
  `refer_id` varchar(15) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `otp_time` datetime DEFAULT NULL,
  `is_login` tinyint(4) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `device_id` text,
  `device_type` varchar(100) DEFAULT NULL,
  `module_access` text,
  `current_password` varchar(150) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `cash_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `winning_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonus_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(11) DEFAULT '0',
  `is_updated` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>Not Updated,1=>Updated',
  `email_verified` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>Not Verified;1=>.verified',
  `verify_string` text,
  `sms_notify` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=>true,0=>false',
  `auth_token` varchar(50) DEFAULT NULL,
  `stock_device_id` text,
  `stock_device_type` varchar(100) DEFAULT NULL,
  `stock_auth_token` varchar(50) DEFAULT NULL,
  `reward_level` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `role_id`, `email`, `phone`, `password`, `team_name`, `date_of_bith`, `gender`, `country`, `state`, `city`, `postal_code`, `address`, `image`, `fb_id`, `google_id`, `refer_id`, `otp`, `otp_time`, `is_login`, `last_login`, `device_id`, `device_type`, `module_access`, `current_password`, `language`, `cash_balance`, `winning_balance`, `bonus_amount`, `status`, `is_updated`, `email_verified`, `verify_string`, `sms_notify`, `auth_token`, `stock_device_id`, `stock_device_type`, `stock_auth_token`, `reward_level`, `created`, `modified`) VALUES
(1, 'Admin', 'Panel', 1, 'admin@gmail.com', '9876543210', '$2y$10$sDysWtDuPSi0CaiK.9majOhZnlalrJzBUxpl6XgtvB8QmzZd3422a', 'Admin', '1991-09-19', 1, 'india', 'Rajsthan', 'jaipur', '302019', 'Durgapura', NULL, '', '', '', '', NULL, 0, '0000-00-00 00:00:00', '', '', NULL, NULL, '', '0.00', '0.00', '0.00', 1, 0, 0, '1550307075Z2F5YXN1ZGRpbi5raGFuQG9jdGFsaW5mb3NvbHV0aW9uLmNvbQ==', 1, NULL, NULL, NULL, NULL, 0, '2017-08-30 00:00:00', '2018-10-05 18:14:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45159;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
