-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2016 at 03:18 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new`
--

-- --------------------------------------------------------

--
-- Table structure for table `coins`
--

CREATE TABLE `coins` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL,
  `market` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `coin_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coin_tag` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `coin_currency` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `coin_amount` double NOT NULL,
  `to_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `relative_id` int(11) NOT NULL,
  `ledger_index` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `bsc_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bsc_currency` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `bsc_amount` double NOT NULL,
  `status` tinyint(1) NOT NULL,
  `try_count` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coins_data`
--

CREATE TABLE `coins_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_ledger` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) NOT NULL,
  `name` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `issuer` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `approval` int(1) NOT NULL DEFAULT '0',
  `count` int(2) NOT NULL DEFAULT '0',
  `period` int(2) NOT NULL DEFAULT '0',
  `ratio` int(3) NOT NULL DEFAULT '0',
  `token` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approval_status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `mail_sent_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency_contents`
--

CREATE TABLE `currency_contents` (
  `id` int(11) NOT NULL,
  `curr_id` int(11) NOT NULL,
  `subject` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sender_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issuer` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag` int(10) DEFAULT NULL,
  `external_address` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` text COLLATE utf8_unicode_ci,
  `amount` double DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `paid` int(4) DEFAULT NULL,
  `confirmations` int(11) DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `method` int(4) NOT NULL COMMENT 'method',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `owner_address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int(11) NOT NULL,
  `sender_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `receiver_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_id` int(2) NOT NULL,
  `amount` double NOT NULL,
  `transaction_id` int(15) NOT NULL,
  `paid` int(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verify_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `referral` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_hash` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `qr_token` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authy_id` int(10) DEFAULT NULL,
  `qr_flag` int(1) DEFAULT '0',
  `tfa_flag` int(1) DEFAULT '0',
  `upload_flag` int(1) NOT NULL DEFAULT '0',
  `new_flag` int(1) NOT NULL DEFAULT '0',
  `del_flag` varchar(1) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '削除フラグ',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fb_register` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'FBから登録されたユーザーなのか',
  `licensed` tinyint(4) NOT NULL DEFAULT '0' COMMENT '身分証明',
  `gender` tinyint(4) NOT NULL,
  `birthday` date NOT NULL,
  `telnum` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buildingname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bankname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branchname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accounttype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountnumber` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fax_or_post` tinyint(1) NOT NULL,
  `balance` double NOT NULL,
  `branch1` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branch2` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bonus1` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bonus2` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `identity_front` varchar(128) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '表面',
  `identity_end` varchar(128) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '裏面',
  `del_flag` varchar(1) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '削除フラグ',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_info_log`
--

CREATE TABLE `users_info_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `original_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fb_register` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'FBから登録されたユーザーなのか',
  `licensed` tinyint(4) NOT NULL DEFAULT '0' COMMENT '身分証明',
  `gender` tinyint(4) NOT NULL,
  `birthday` date NOT NULL,
  `telnum` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buildingname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bankname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branchname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accounttype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountnumber` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fax_or_post` tinyint(1) NOT NULL,
  `balance` double NOT NULL,
  `branch1` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branch2` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bonus1` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bonus2` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `identity_front` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identity_end` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `del_flag` varchar(1) COLLATE utf8_unicode_ci DEFAULT '0',
  `operated_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_log`
--

CREATE TABLE `users_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `original_id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verify_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `referral` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qr_token` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qr_flag` int(1) DEFAULT '0',
  `tfa_flag` int(10) DEFAULT '0',
  `upload_flag` int(1) NOT NULL DEFAULT '0',
  `new_flag` int(1) NOT NULL DEFAULT '0',
  `del_flag` varchar(1) COLLATE utf8_unicode_ci DEFAULT '0',
  `operated_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_login_info`
--

CREATE TABLE `users_login_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `connect_ip` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `user_agent` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `browser` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `verify_token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_login_info_log`
--

CREATE TABLE `users_login_info_log` (
  `id` int(11) NOT NULL,
  `original_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `connect_ip` varchar(20) DEFAULT NULL,
  `user_agent` varchar(150) NOT NULL,
  `browser` varchar(15) NOT NULL,
  `verify_token` varchar(50) NOT NULL,
  `operated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_role`
--

CREATE TABLE `users_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_wallet`
--

CREATE TABLE `users_wallet` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_secrethex` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_public` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_publichex` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_wallet_log`
--

CREATE TABLE `users_wallet_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `original_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_secrethex` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_public` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_publichex` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `operated_by` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(5) NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wallet_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wallet_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wallet_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `download_wallet_client` text COLLATE utf8_unicode_ci NOT NULL,
  `logo_coin` text COLLATE utf8_unicode_ci NOT NULL,
  `is_moneypaper` tinyint(1) NOT NULL DEFAULT '0',
  `limit_confirmations` int(11) NOT NULL DEFAULT '3',
  `enable_deposit` tinyint(1) NOT NULL DEFAULT '1',
  `enable_withdraw` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issuer` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag` int(10) DEFAULT NULL,
  `external_address` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `fee_amount` double DEFAULT NULL,
  `receive_amount` double DEFAULT NULL COMMENT 'amount - fee_amount',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(4) NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '振込先名人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coins`
--
ALTER TABLE `coins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coins_data`
--
ALTER TABLE `coins_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_login_info`
--
ALTER TABLE `users_login_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_login_info_log`
--
ALTER TABLE `users_login_info_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_role`
--
ALTER TABLE `users_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_wallet`
--
ALTER TABLE `users_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_wallet_log`
--
ALTER TABLE `users_wallet_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coins`
--
ALTER TABLE `coins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coins_data`
--
ALTER TABLE `coins_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_login_info`
--
ALTER TABLE `users_login_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_login_info_log`
--
ALTER TABLE `users_login_info_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_role`
--
ALTER TABLE `users_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_wallet`
--
ALTER TABLE `users_wallet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_wallet_log`
--
ALTER TABLE `users_wallet_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
