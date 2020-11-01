-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2020 at 09:55 AM
-- Server version: 10.2.10-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cleanpli_cleanp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `unique_id` int(11) DEFAULT NULL,
  `remember_token` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_logs`
--

CREATE TABLE `admin_activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_activity_log_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` int(11) UNSIGNED NOT NULL,
  `admin_dec` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_log` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` double(8,2) NOT NULL,
  `user_agent` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login_time` time NOT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `annoucements`
--

CREATE TABLE `annoucements` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annoucements`
--

INSERT INTO `annoucements` (`id`, `uuid`, `user_id`, `type`, `message`, `location`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'dce5850a-b3c0-4a7e-a86b-6c5c55c2454a', 38, 'type1', 'message1', 'location1', '2020-07-22 10:19:56', '2020-07-22 10:19:56', NULL),
(2, '97187998-2491-4ded-8f7b-5bd928ed1cbb', 38, 'type2', 'message2', 'location2', '2020-07-22 10:19:56', '2020-07-22 10:19:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `api_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_ip` int(11) NOT NULL,
  `user_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_label` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `uuid`, `badge_icon`, `badge_label`, `badge_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'bb299047-c107-45eb-b783-0a4c017fb0bb', 'dDQiP5aW.jpeg', 'rakesh', 'boliya', '2020-06-22 04:48:22', '2020-06-22 04:48:22', NULL),
(2, '6cd590b0-c587-405f-ba5a-a981b7380c14', NULL, 'rakesh', 'boliya', '2020-07-01 01:45:04', '2020-07-01 01:45:04', NULL),
(3, 'b062d2c8-0267-41cb-9977-8903ea6cc3ea', NULL, 'rakesh', 'boliya', '2020-07-01 05:28:34', '2020-07-01 05:28:34', NULL),
(4, '90f8a41c-ce4d-4b06-b71e-343686375dc3', NULL, 'rakesh', 'boliya', '2020-07-21 04:20:46', '2020-07-21 04:20:46', NULL),
(5, '10c106da-2abd-4356-b0d9-1eae9825da65', NULL, 'rakesh', 'boliya', '2020-07-21 04:20:59', '2020-07-21 04:20:59', NULL),
(6, 'b6d9a541-7ea3-49c0-a2b9-344c10aa4814', NULL, 'rakesh', 'boliya', '2020-07-21 04:24:23', '2020-07-21 04:24:23', NULL),
(7, '855eab77-7fa5-47b1-b2e0-cdb97b90c17a', NULL, 'rakesh', 'boliya', '2020-07-21 04:25:52', '2020-07-21 04:25:52', NULL),
(8, '13658c22-fce3-4c43-81ed-cd5e4730057f', NULL, 'rakesh', 'boliya', '2020-07-21 04:57:15', '2020-07-21 04:57:15', NULL),
(9, '218cb434-279e-4e46-9ecc-f158d4272e81', NULL, 'rakesh', 'boliya', '2020-07-21 05:01:07', '2020-07-21 05:01:07', NULL),
(10, '1aa695a9-a273-462c-9ed3-22e78de6d568', NULL, 'rakesh', 'boliya', '2020-07-21 05:01:38', '2020-07-21 05:01:38', NULL),
(11, 'acc29e90-c184-4c4b-8ce8-29aa6508a1d4', NULL, 'rakesh', 'boliya', '2020-07-21 05:05:17', '2020-07-21 05:05:17', NULL),
(12, '05aa86c1-f44c-492d-84d4-e0dea71c1f86', NULL, 'rakesh', 'boliya', '2020-07-21 05:05:29', '2020-07-21 05:05:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED DEFAULT NULL,
  `booking_status_id` int(11) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL,
  `parent_event_id` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `booking_end_time` time DEFAULT NULL,
  `booking_postcode` int(11) NOT NULL,
  `booking_provider_type` enum('freelancer','agency') COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_type` enum('just once','weekly','beweekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `promocode` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_cost` double(8,2) NOT NULL,
  `discount` double(8,2) NOT NULL,
  `final_cost` double(8,2) NOT NULL,
  `is_flexible` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `uuid`, `user_id`, `provider_id`, `booking_status_id`, `description`, `is_recurring`, `parent_event_id`, `booking_date`, `booking_time`, `booking_end_time`, `booking_postcode`, `booking_provider_type`, `plan_type`, `promocode`, `total_cost`, `discount`, `final_cost`, `is_flexible`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 1, 4, 'hello', 0, 'test', '2020-05-26', '14:00:00', '15:00:00', 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 06:52:22', '2020-05-29 07:57:04', '2020-05-29 07:57:04'),
(2, NULL, 1, 1, 4, 'hello', 0, 'test', '2020-05-26', '18:00:00', '19:00:00', 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 06:55:11', '2020-05-29 07:59:12', '2020-05-29 07:59:12'),
(3, NULL, 3, 3, 4, 'hello', 0, 'test', '2020-05-26', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 08:03:27', '2020-05-29 08:03:56', '2020-05-29 08:03:56'),
(4, NULL, 3, 3, 4, 'hellooooo', 0, 'test', '2020-05-26', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 08:27:13', '2020-05-29 08:35:40', NULL),
(5, NULL, 3, 2, 4, 'hello', 0, 'test', '2020-05-26', '18:00:00', '19:00:00', 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 08:41:59', '2020-05-29 08:41:59', NULL),
(6, NULL, 2, 2, 4, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 08:42:02', '2020-05-29 08:42:02', NULL),
(7, NULL, 2, 2, 4, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 23:37:25', '2020-05-29 23:37:25', NULL),
(8, NULL, 2, 2, 4, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 23:40:48', '2020-05-29 23:40:48', NULL),
(9, NULL, 2, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-05-29 23:40:50', '2020-05-29 23:40:50', NULL),
(10, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 02:39:58', '2020-06-18 02:39:58', NULL),
(11, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 02:40:50', '2020-06-18 02:40:50', NULL),
(12, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 02:41:53', '2020-06-18 02:41:53', NULL),
(13, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 02:42:38', '2020-06-18 02:42:38', NULL),
(14, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 02:52:21', '2020-06-18 02:52:21', NULL),
(15, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 23:58:21', '2020-06-18 23:58:21', NULL),
(16, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 23:59:08', '2020-06-18 23:59:08', NULL),
(17, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 23:59:17', '2020-06-18 23:59:17', NULL),
(18, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-18 23:59:49', '2020-06-18 23:59:49', NULL),
(19, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-19 00:01:02', '2020-06-19 00:01:02', NULL),
(20, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-19 00:01:09', '2020-06-19 00:01:09', NULL),
(21, NULL, 1, NULL, 1, 'hello', 0, 'test', '2020-05-26', '00:00:00', NULL, 364290, 'freelancer', 'just once', 'rakesh', 0.00, 0.00, 0.00, 0, '2020-06-19 00:01:51', '2020-06-19 00:01:51', NULL),
(22, '4912f491-478d-4e88-bbb1-7cd450ee8a94', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 14:34:38', '2020-07-29 14:34:38', NULL),
(23, '3b0c710d-2e7f-400a-a546-0a0b1b552c86', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 14:35:54', '2020-07-29 14:35:54', NULL),
(24, '71e2352a-a9cf-495c-b116-79f2c652f828', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 22:49:39', '2020-07-29 22:49:39', NULL),
(25, '43a5fcaa-3529-4906-9bd7-2857f4048139', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 22:50:49', '2020-07-29 22:50:49', NULL),
(26, '73db126c-435c-4e90-9cb2-73a13b96ea64', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 22:52:06', '2020-07-29 22:52:06', NULL),
(27, '0b19b325-8e05-431d-86a2-625528cebd02', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 22:54:11', '2020-07-29 22:54:11', NULL),
(28, '545165f4-4eaf-4a1f-9b6f-946b16222574', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 22:55:03', '2020-07-29 22:55:03', NULL),
(29, '140d8be4-6fba-448c-be39-2fa8380acdc3', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 22:55:11', '2020-07-29 22:55:11', NULL),
(30, 'fc40b043-f9d4-43c8-a56b-49377047dd0c', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 22:55:22', '2020-07-29 22:55:22', NULL),
(31, 'd3bed257-e6b5-4ef6-aad2-e82d55bdd313', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 23:02:09', '2020-07-29 23:02:09', NULL),
(32, '6b817b88-1dee-49c5-ad79-f769bb924f5f', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 23:15:00', '2020-07-29 23:15:00', NULL),
(33, 'c821326e-7ca0-43cf-93e0-0d02c113a098', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-29 23:17:39', '2020-07-29 23:17:39', NULL),
(34, 'cdd4457b-c962-4fb6-a9c4-f19472ee1a10', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 00:00:29', '2020-07-30 00:00:29', NULL),
(35, 'a207f3d0-bd24-4dbe-9d8c-28937a8d3ecc', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 00:02:50', '2020-07-30 00:02:50', NULL),
(36, '68376ff0-39de-442b-8e40-1eb975427476', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 00:03:26', '2020-07-30 00:03:26', NULL),
(37, '7093fa5a-26c1-40b8-b7e9-e91e9b759ed2', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 00:03:31', '2020-07-30 00:03:31', NULL),
(38, '1a1a620d-86ba-4900-8975-1ae2dca7dd4f', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 00:23:13', '2020-07-30 00:23:13', NULL),
(39, 'f69ae51a-722b-45aa-a4a3-1a3335cb0d75', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 00:24:11', '2020-07-30 00:24:11', NULL),
(40, '15c294d9-1ddc-423e-aaca-72b459280504', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 01:54:42', '2020-07-30 01:54:42', NULL),
(41, '1a3c3e99-72f9-4648-b00e-947784e25017', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:01:58', '2020-07-30 02:01:58', NULL),
(42, 'f56fcc61-4792-477c-9ab4-a69877e3646f', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:38:01', '2020-07-30 02:38:01', NULL),
(43, '049571bf-563f-4e2a-ac14-95a583a31373', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:41:38', '2020-07-30 02:41:38', NULL),
(44, '9d98e862-c94e-4204-9182-919ad227af57', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:42:34', '2020-07-30 02:42:34', NULL),
(45, '61f114f2-2f44-4b49-b95d-a43902f48908', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:42:41', '2020-07-30 02:42:41', NULL),
(46, 'b4d7998e-a4ae-4a55-8a7a-0fb87812c8f0', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:43:00', '2020-07-30 02:43:00', NULL),
(47, '8593f33e-3dba-4fbf-ae33-e071f5a5f238', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:43:21', '2020-07-30 02:43:21', NULL),
(48, 'f2390ee3-8eae-4620-ab9e-c9b874848d9b', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 02:44:34', '2020-07-30 02:44:34', NULL),
(49, '4841c735-4e14-43e9-8d18-d409b2f83e39', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 04:59:53', '2020-07-30 04:59:53', NULL),
(50, '4e5ed4e2-d67f-4e8a-a134-a28e9a73a3f2', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 05:19:32', '2020-07-30 05:19:32', NULL),
(51, '7ca974c9-1366-4eba-b2df-3a05829d5da8', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-30 05:19:51', '2020-07-30 05:19:51', NULL),
(52, '97e48841-601c-4e9b-99b0-e2b209b9ef44', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-07-31 08:50:32', '2020-07-31 08:50:32', NULL),
(53, 'b10ea18e-fc31-475e-be03-8c318987f3f2', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:30:21', '2020-08-01 07:30:21', NULL),
(54, 'a12a2c55-894c-4a98-9b3c-57eda0f1087a', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:31:54', '2020-08-01 07:31:54', NULL),
(55, '37f62f0b-f112-4e62-8e80-5fe826e4d2e7', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:35:22', '2020-08-01 07:35:22', NULL),
(56, 'c288cc2c-1d2d-4bfe-b74c-feb4776bc4cc', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:35:55', '2020-08-01 07:35:55', NULL),
(57, '3f32c378-e9ec-4ad8-8c0c-8cf415f95ec1', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:36:10', '2020-08-01 07:36:10', NULL),
(58, '7e999c60-d6b4-45e1-b4c7-d3fb18728536', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:37:06', '2020-08-01 07:37:06', NULL),
(59, '1500535f-a882-4581-866c-d42875d9da05', 1, 2, 1, 'test', 0, 'test', '2020-07-30', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:37:41', '2020-08-01 07:37:41', NULL),
(60, '1baed047-dfcc-454f-b74f-876fd2beb7d9', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:38:03', '2020-08-01 07:38:03', NULL),
(61, 'c1f8b519-76f9-4cd0-9a8a-fcbfeda54320', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:42:47', '2020-08-01 07:42:47', NULL),
(62, '3faf3570-e793-4b81-82be-94ad3d92367c', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:43:29', '2020-08-01 07:43:29', NULL),
(63, '83426b80-d401-4520-b001-761452ee8283', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:45:30', '2020-08-01 07:45:30', NULL),
(64, 'f23260b3-55ba-47df-835f-71db71eda584', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:47:51', '2020-08-01 07:47:51', NULL),
(65, '4a8707b5-fea1-4443-add2-bb75214cce79', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:51:28', '2020-08-01 07:51:28', NULL),
(66, '4241f213-5cf6-41af-95a7-dc69b13947aa', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:52:16', '2020-08-01 07:52:16', NULL),
(67, 'bf9a6d6c-3c96-4392-9253-0b6e7ab6d8f8', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:52:44', '2020-08-01 07:52:44', NULL),
(68, '85ed1643-ec77-4145-b09f-b353b946d6e3', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:53:23', '2020-08-01 07:53:23', NULL),
(69, '59c673bb-3f4c-4da1-ba0a-842c756b5231', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:54:31', '2020-08-01 07:54:31', NULL),
(70, 'e12dd9aa-408c-43a6-93df-ee34cf209ce4', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:55:00', '2020-08-01 07:55:00', NULL),
(71, 'e3d13737-c553-4715-8e64-76b46d93d54a', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:55:27', '2020-08-01 07:55:27', NULL),
(72, '7059127d-2498-4a85-ba11-fd1aa988b119', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:56:22', '2020-08-01 07:56:22', NULL),
(73, 'c508af6c-28ee-41b2-8abc-8bdf0955416d', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:56:58', '2020-08-01 07:56:58', NULL),
(74, 'f931cfd0-7e4c-495b-8ec0-8fa8778eb515', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:58:10', '2020-08-01 07:58:10', NULL),
(75, '9523e521-97dc-4dc8-b612-690086e6d8d1', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:58:34', '2020-08-01 07:58:34', NULL),
(76, '724fdafd-b6a7-461c-ad41-f8bbe12f5858', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 07:59:42', '2020-08-01 07:59:42', NULL),
(77, 'ac8d481c-336f-4d3f-a3c3-ff2d2af640eb', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:00:18', '2020-08-01 08:00:18', NULL),
(78, '80890c47-0498-4713-a0c3-3195a39c8880', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:00:50', '2020-08-01 08:00:50', NULL),
(79, '811538fe-1846-4fd9-81c2-15e13d4dd878', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:01:08', '2020-08-01 08:01:08', NULL),
(80, 'ef998a81-a790-4abb-9018-5ae06ea97380', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:02:15', '2020-08-01 08:02:15', NULL),
(81, '51586d65-617d-4ad6-9908-ff352e3cb05b', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:02:29', '2020-08-01 08:02:29', NULL),
(82, 'be26f492-81c6-4810-9031-39ee1b0ca836', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:03:06', '2020-08-01 08:03:06', NULL),
(83, '42c10979-dff4-41fa-87c0-38a7e34019c7', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'beweekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:04:06', '2020-08-01 08:04:06', NULL),
(84, '4c3e8261-11bf-4e1b-8564-2b01a0b161c3', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:13:22', '2020-08-01 08:13:22', NULL),
(85, 'ec5bb7c4-76ed-41ae-b047-6c91e32ed3ac', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:13:28', '2020-08-01 08:13:28', NULL),
(86, '0d3b6002-c81b-4e34-b472-2e4f9e2644b4', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:14:24', '2020-08-01 08:14:24', NULL),
(87, '3e7c6420-4092-4b7c-9b9b-d1f688f73d14', 1, 2, 1, 'test', 0, 'test', '2020-07-01', '16:00:00', '17:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 1, '2020-08-01 08:14:50', '2020-08-01 08:14:50', NULL),
(88, 'bfb188d2-5258-4acf-bc03-29bc51acf7a9', 38, 1, 1, 'test', 0, 'test', '2020-08-08', '16:00:00', '19:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 0, '2020-08-07 23:37:07', '2020-08-07 23:37:07', NULL),
(89, 'fd954e92-9dc6-496d-b8c1-ab864df97df5', 38, 1, 1, 'test', 0, 'test', '2020-08-08', '16:00:00', '19:00:00', 364290, 'freelancer', 'just once', 'FREE5', 5000.00, 5.00, 4750.00, 0, '2020-08-07 23:38:44', '2020-08-07 23:38:44', NULL),
(90, 'e086802c-0dad-4f28-a07c-647085d168c9', 38, 1, 1, 'test', 0, 'test', '2020-08-08', '16:00:00', '19:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 0, '2020-08-07 23:44:10', '2020-08-07 23:44:10', NULL),
(91, '366f5036-3646-4504-9768-2b94d96f3b06', 38, 1, 1, 'test', 0, 'test', '2020-08-08', '16:00:00', '19:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 0, '2020-08-07 23:44:42', '2020-08-07 23:44:42', NULL),
(92, '56cf1ae2-340f-4506-adc9-7fdef83210e6', 38, 1, 1, 'test', 0, 'test', '2020-08-08', '16:00:00', '19:00:00', 364290, 'freelancer', 'weekly', 'FREE5', 5000.00, 5.00, 4750.00, 0, '2020-08-07 23:46:12', '2020-08-07 23:46:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_activity_logs`
--

CREATE TABLE `booking_activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_activity_log_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `status` enum('requested','accepted','arrived','completed','cancelled','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_recurring` tinyint(1) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `booking_postcode` int(11) NOT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_addresses`
--

CREATE TABLE `booking_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `address_line1` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line2` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subrub` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_addresses`
--

INSERT INTO `booking_addresses` (`id`, `uuid`, `booking_id`, `address_line1`, `address_line2`, `subrub`, `state`, `postcode`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 'asdctest', 'testdfnsdjfjsdf', 'test', 'abc', 360005, '2020-05-29 06:52:22', '2020-05-29 07:57:04', '2020-05-29 07:57:04'),
(2, NULL, 2, 'asdctest', 'testdfnsdjfjsdf', 'test', 'abc', 360005, '2020-05-29 06:55:14', '2020-05-29 07:59:12', '2020-05-29 07:59:12'),
(3, NULL, 3, 'asdctest', 'testdfnsdjfjsdf', 'test', 'abc', 360005, '2020-05-29 08:03:27', '2020-05-29 08:03:56', '2020-05-29 08:03:56'),
(65, '35b98100-c056-4e27-9da6-7c6ab7d3c3be', 88, 'testing1', 'testing2', 'test', 'gujarat', 364290, '2020-08-07 23:37:07', '2020-08-07 23:37:07', NULL),
(66, '22c452aa-7290-4e3c-98d0-0f6558e09bde', 89, 'testing1', 'testing2', 'test', 'gujarat', 364290, '2020-08-07 23:38:45', '2020-08-07 23:38:45', NULL),
(67, 'f50952cd-d9ed-4b9d-baeb-e528834785a8', 90, 'testing1', 'testing2', 'test', 'gujarat', 364290, '2020-08-07 23:44:10', '2020-08-07 23:44:10', NULL),
(68, '5f089be5-64d2-4c2c-8222-9d9b985e62b0', 91, 'testing1', 'testing2', 'test', 'gujarat', 364290, '2020-08-07 23:44:42', '2020-08-07 23:44:42', NULL),
(69, '9443df2a-68e7-480a-a627-1aeeb04ac8c5', 92, 'testing1', 'testing2', 'test', 'gujarat', 364290, '2020-08-07 23:46:12', '2020-08-07 23:46:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_changes`
--

CREATE TABLE `booking_changes` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `is_rescheduled` tinyint(1) DEFAULT NULL,
  `is_cancelled` tinyint(1) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `number_of_hours` int(11) DEFAULT NULL,
  `agreed_service_amount` double(8,2) DEFAULT NULL,
  `comments` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_by_user` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_questions`
--

CREATE TABLE `booking_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `service_question_id` int(11) UNSIGNED NOT NULL,
  `answer` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_questions`
--

INSERT INTO `booking_questions` (`id`, `uuid`, `booking_id`, `service_question_id`, `answer`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 1, 'testing', '2020-05-29 06:52:22', '2020-05-29 07:57:04', '2020-05-29 07:57:04'),
(2, NULL, 2, 1, 'testing', '2020-05-29 06:55:14', '2020-05-29 07:59:12', '2020-05-29 07:59:12'),
(3, NULL, 3, 1, 'testing', '2020-05-29 08:03:28', '2020-05-29 08:03:56', '2020-05-29 08:03:56'),
(4, NULL, 4, 1, 'testingtest', '2020-05-29 08:27:14', '2020-05-29 08:36:58', NULL),
(5, NULL, 5, 1, 'testing', '2020-05-29 08:42:00', '2020-05-29 08:42:00', NULL),
(6, NULL, 6, 1, 'testing', '2020-05-29 08:42:03', '2020-05-29 08:42:03', NULL),
(7, NULL, 7, 1, 'testing', '2020-05-29 23:37:26', '2020-05-29 23:37:26', NULL),
(8, NULL, 8, 1, 'testing', '2020-05-29 23:40:48', '2020-05-29 23:40:48', NULL),
(9, NULL, 9, 1, 'testing', '2020-05-29 23:40:51', '2020-05-29 23:40:51', NULL),
(10, NULL, 21, 1, 'testing', '2020-06-19 00:01:53', '2020-06-19 00:01:53', NULL),
(11, 'be79768f-0a40-49ad-b89a-e2ea60635f33', 41, 1, 'testing', '2020-07-30 02:01:58', '2020-07-30 02:01:58', NULL),
(12, 'c39ecfce-78c8-4c4c-9719-8e6eede3074f', 1, 1, 'test1', '2020-07-30 02:25:40', '2020-07-30 02:25:40', NULL),
(13, '62c38eb6-f2a6-402d-8d27-d2e7bccdf402', 1, 1, 'test2', '2020-07-30 02:25:40', '2020-07-30 02:25:40', NULL),
(14, '3ced4fd0-b017-457f-b239-3e5db3066faa', 1, 1, 'test3', '2020-07-30 02:25:40', '2020-07-30 02:25:40', NULL),
(17, 'd207327d-dfd0-461b-b35a-9d2ae5d13163', 1, 1, 'test1', '2020-07-30 04:50:52', '2020-07-30 04:50:52', NULL),
(18, '59aff162-7850-464e-bdc0-48007ae5a5c9', 1, 1, 'test2', '2020-07-30 04:50:52', '2020-07-30 04:50:52', NULL),
(19, 'f610e9c9-3719-49bd-8da1-acbee3a9e64a', 1, 1, 'test3', '2020-07-30 04:50:52', '2020-07-30 04:50:52', NULL),
(20, 'de6e3da4-6cbf-45ef-babd-66f915ff2816', 1, 1, 'test1', '2020-07-30 06:02:30', '2020-07-30 06:02:30', NULL),
(21, 'd3f0a3d1-8e46-4bcf-88d7-7206a60ea6d1', 1, 1, 'test2', '2020-07-30 06:02:30', '2020-07-30 06:02:30', NULL),
(22, 'b858a759-edf4-43f4-84c3-c6951e65e100', 1, 1, 'test3', '2020-07-30 06:02:30', '2020-07-30 06:02:30', NULL),
(23, '5f575295-71b8-4bad-82b2-5e7389eede61', 1, 1, 'test1', '2020-07-30 06:03:20', '2020-07-30 06:03:20', NULL),
(24, 'd0ada686-9ba9-46f4-a8a0-57f07f3609f9', 1, 1, 'test2', '2020-07-30 06:03:20', '2020-07-30 06:03:20', NULL),
(25, '10409d8b-ae4f-4c02-b66f-79c84a3cf52a', 1, 1, 'test3', '2020-07-30 06:03:21', '2020-07-30 06:03:21', NULL),
(26, '1a1ee179-23ea-4fae-b007-8af2adfe145d', 1, 1, 'test1', '2020-08-07 05:47:24', '2020-08-07 05:47:24', NULL),
(27, '6149d845-8e00-4ec4-b6dc-51c07b107584', 1, 1, 'test2', '2020-08-07 05:47:25', '2020-08-07 05:47:25', NULL),
(28, '9f8bc9dd-947e-47c9-9eb8-7173ceab1679', 1, 1, 'test3', '2020-08-07 05:47:25', '2020-08-07 05:47:25', NULL),
(29, 'ad380568-5246-4b7c-8489-cb7c53f8a2a3', 1, 1, 'test1', '2020-08-07 06:01:11', '2020-08-07 06:01:11', NULL),
(30, 'da2b3a2c-2d2f-4c7c-8bd3-0b757c42f6c7', 1, 1, 'test2', '2020-08-07 06:01:11', '2020-08-07 06:01:11', NULL),
(31, 'abdd84c4-0892-4183-9b49-25380787cc38', 1, 1, 'test3', '2020-08-07 06:01:12', '2020-08-07 06:01:12', NULL),
(32, '563daad2-2e33-4c9f-80c3-335f41e64319', 1, 1, 'test1', '2020-08-07 06:28:47', '2020-08-07 06:28:47', NULL),
(33, '104a9e28-7c0a-41cd-a7ac-890ee0f0dea9', 1, 1, 'test2', '2020-08-07 06:28:47', '2020-08-07 06:28:47', NULL),
(34, '48c6556f-4274-4427-8f97-f57a06097d86', 1, 1, 'test3', '2020-08-07 06:28:47', '2020-08-07 06:28:47', NULL),
(35, '6d89c8c2-97a0-44ae-9054-7c260e3a294a', 1, 1, 'test1', '2020-08-07 07:09:32', '2020-08-07 07:09:32', NULL),
(36, '92963d34-1d09-401c-adc5-c93ecb479968', 1, 1, 'test2', '2020-08-07 07:09:32', '2020-08-07 07:09:32', NULL),
(37, '61534c45-f70a-4080-a819-b90936b0f3bf', 1, 1, 'test3', '2020-08-07 07:09:33', '2020-08-07 07:09:33', NULL),
(38, 'fcedfc83-7e55-4940-8735-ac5a3785e0f6', 1, 1, 'test1', '2020-08-07 07:10:24', '2020-08-07 07:10:24', NULL),
(39, 'c39194fd-b2b2-496b-a0b7-54ffa76e3882', 1, 1, 'test2', '2020-08-07 07:10:24', '2020-08-07 07:10:24', NULL),
(40, 'd4eb62af-2753-4e6a-a13e-e7337e8183c9', 1, 1, 'test3', '2020-08-07 07:10:24', '2020-08-07 07:10:24', NULL),
(41, 'f9dc7c89-3af2-42ca-a93c-100fc1d6dc12', 1, 1, 'test1', '2020-08-07 07:10:28', '2020-08-07 07:10:28', NULL),
(42, 'cae02791-1167-43bf-ac42-f36d0225eb3c', 1, 1, 'test2', '2020-08-07 07:10:29', '2020-08-07 07:10:29', NULL),
(43, 'ef8a2dff-36e5-4527-9ae1-7296849e94d4', 1, 1, 'test3', '2020-08-07 07:10:29', '2020-08-07 07:10:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_recurring_patterns`
--

CREATE TABLE `booking_recurring_patterns` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `recurring_type` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurring_separation_count` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_number_of_occurrences` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day_of_week` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `week_of_month` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day_of_month` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month_of_year` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_request_providers`
--

CREATE TABLE `booking_request_providers` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `provider_user_id` int(11) UNSIGNED NOT NULL,
  `status` enum('accepted','rejected','pending') COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_comment` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible_to_enduser` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_request_providers`
--

INSERT INTO `booking_request_providers` (`id`, `uuid`, `booking_id`, `provider_user_id`, `status`, `provider_comment`, `visible_to_enduser`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 1, 'accepted', 'test comment', 1, '2020-05-29 06:52:23', '2020-05-29 07:40:35', NULL),
(2, NULL, 2, 1, 'accepted', 'test comment', 1, '2020-05-29 06:55:15', '2020-05-29 07:59:12', '2020-05-29 07:59:12'),
(3, NULL, 3, 1, 'accepted', 'test comment', 1, '2020-05-29 08:03:29', '2020-05-29 08:03:56', '2020-05-29 08:03:56'),
(4, NULL, 4, 1, 'accepted', 'test', 1, '2020-05-29 08:27:15', '2020-05-29 08:38:57', NULL),
(5, NULL, 5, 1, 'accepted', 'test comment', 1, '2020-05-29 08:42:00', '2020-05-29 08:42:00', NULL),
(6, NULL, 6, 1, 'accepted', 'test comment', 1, '2020-05-29 08:42:03', '2020-05-29 08:42:03', NULL),
(7, NULL, 7, 1, 'accepted', 'test comment', 1, '2020-05-29 23:37:27', '2020-05-29 23:37:27', NULL),
(8, NULL, 8, 1, 'accepted', 'test comment', 1, '2020-05-29 23:40:49', '2020-05-29 23:40:49', NULL),
(9, NULL, 9, 1, 'accepted', 'test comment', 1, '2020-05-29 23:40:52', '2020-05-29 23:40:52', NULL),
(10, NULL, 21, 1, 'accepted', 'test comment', 1, '2020-06-19 00:01:54', '2020-06-19 00:01:54', NULL),
(11, 'f6cc4bd9-e1f5-464c-9737-90064620ab04', 39, 1, 'accepted', 'test comment', 1, '2020-07-30 00:24:11', '2020-07-30 00:24:11', NULL),
(12, '07ffbbf3-b9f2-4514-a290-2864556db9cf', 42, 1, 'accepted', 'test comment', 1, '2020-07-30 02:38:02', '2020-07-30 02:38:02', NULL),
(13, '2eefd9e8-38f6-4218-bfd6-6aa26527d045', 43, 1, 'accepted', 'test comment', 1, '2020-07-30 02:41:38', '2020-07-30 02:41:38', NULL),
(14, '5192bd5b-8428-4869-9816-0ef3e2824a58', 44, 1, 'accepted', 'test comment', 1, '2020-07-30 02:42:35', '2020-07-30 02:42:35', NULL),
(15, 'aab1ee63-f68a-4677-a0a2-287d52125774', 45, 1, 'accepted', 'test comment', 1, '2020-07-30 02:42:41', '2020-07-30 02:42:41', NULL),
(16, '6633a562-d0e2-4ef3-a31c-fca5ad1b2e7a', 46, 1, 'accepted', 'test comment', 1, '2020-07-30 02:43:00', '2020-07-30 02:43:00', NULL),
(17, '40f2235c-22b3-4e88-90bf-1514cbd29476', 47, 1, 'accepted', 'test comment', 1, '2020-07-30 02:43:21', '2020-07-30 02:43:21', NULL),
(18, 'dd704193-d2b9-4145-8899-2f57df5ea61d', 48, 1, 'accepted', 'test comment', 1, '2020-07-30 02:44:34', '2020-07-30 02:44:34', NULL),
(19, 'e2907df5-3e25-4150-ae0d-afd27fbf8617', 49, 1, 'accepted', 'test comment', 1, '2020-07-30 04:59:53', '2020-07-30 04:59:53', NULL),
(20, '5c7f6136-0b03-4e7b-801e-a068ca231578', 52, 1, 'accepted', 'test comment', 1, '2020-07-31 08:50:37', '2020-07-31 08:50:37', NULL),
(21, 'afb75d0c-cacc-437f-a969-71314aa42498', 53, 1, 'accepted', 'test comment', 1, '2020-08-01 07:30:23', '2020-08-01 07:30:23', NULL),
(22, '1709b7dc-dda7-4029-a373-2efbf9e5e171', 54, 1, 'accepted', 'test comment', 1, '2020-08-01 07:31:54', '2020-08-01 07:31:54', NULL),
(23, 'd9b7c0bb-d149-4631-aad7-f62f523be647', 55, 1, 'accepted', 'test comment', 1, '2020-08-01 07:35:22', '2020-08-01 07:35:22', NULL),
(24, '4732943c-0245-4f5d-9bd1-d76e9888b3e4', 56, 1, 'accepted', 'test comment', 1, '2020-08-01 07:35:55', '2020-08-01 07:35:55', NULL),
(25, 'd2b0a5c1-27dc-4f76-a6fe-ef99e43afc17', 57, 1, 'accepted', 'test comment', 1, '2020-08-01 07:36:10', '2020-08-01 07:36:10', NULL),
(26, 'a3367387-0a0c-41e6-9337-7a77e9ca54c0', 58, 1, 'accepted', 'test comment', 1, '2020-08-01 07:37:06', '2020-08-01 07:37:06', NULL),
(27, '49328533-48ad-4f6e-bf00-b430131e2cd4', 59, 1, 'accepted', 'test comment', 1, '2020-08-01 07:37:41', '2020-08-01 07:37:41', NULL),
(28, '66ab84db-366e-4123-8daf-9d51f20c4130', 60, 1, 'accepted', 'test comment', 1, '2020-08-01 07:38:03', '2020-08-01 07:38:03', NULL),
(29, '121e2ccb-a6a7-4f3e-a28c-8c129f3ba473', 61, 1, 'accepted', 'test comment', 1, '2020-08-01 07:42:47', '2020-08-01 07:42:47', NULL),
(30, '3077bead-e8e4-4c43-b140-b52f5c0b2efa', 62, 1, 'accepted', 'test comment', 1, '2020-08-01 07:43:29', '2020-08-01 07:43:29', NULL),
(31, '4f823d57-a728-41c2-a894-0ec18c893f21', 63, 1, 'accepted', 'test comment', 1, '2020-08-01 07:45:31', '2020-08-01 07:45:31', NULL),
(32, '1ab6127b-1c8f-43e7-b0d9-feb11207d73f', 64, 1, 'accepted', 'test comment', 1, '2020-08-01 07:47:51', '2020-08-01 07:47:51', NULL),
(33, '34ad75f3-6f6e-45a8-91c6-0c0919d106ba', 65, 1, 'accepted', 'test comment', 1, '2020-08-01 07:51:28', '2020-08-01 07:51:28', NULL),
(34, '533db098-1255-4132-acb0-9c5eeeb3c789', 66, 1, 'accepted', 'test comment', 1, '2020-08-01 07:52:16', '2020-08-01 07:52:16', NULL),
(35, '64060f13-01f7-497c-9437-7c3006d7ef62', 67, 1, 'accepted', 'test comment', 1, '2020-08-01 07:52:44', '2020-08-01 07:52:44', NULL),
(36, 'a9253cf1-6ad8-4e6a-9f93-c0b33198e45e', 68, 1, 'accepted', 'test comment', 1, '2020-08-01 07:53:24', '2020-08-01 07:53:24', NULL),
(37, '9083ffa9-e17d-4d3a-9ee0-5f44bd1f4ace', 69, 1, 'accepted', 'test comment', 1, '2020-08-01 07:54:32', '2020-08-01 07:54:32', NULL),
(38, '77de48fe-4e95-4786-97dc-60cca5ee9cff', 70, 1, 'accepted', 'test comment', 1, '2020-08-01 07:55:00', '2020-08-01 07:55:00', NULL),
(39, '691bed6a-b38d-4a4c-9c79-319ef4e010d0', 71, 1, 'accepted', 'test comment', 1, '2020-08-01 07:55:28', '2020-08-01 07:55:28', NULL),
(40, 'e56e4c4a-ea14-4da5-b539-155f0c060125', 72, 1, 'accepted', 'test comment', 1, '2020-08-01 07:56:23', '2020-08-01 07:56:23', NULL),
(41, 'b5bbafe5-00ab-49d4-9036-49c7b4d6cd24', 73, 1, 'accepted', 'test comment', 1, '2020-08-01 07:56:59', '2020-08-01 07:56:59', NULL),
(42, 'a02515bd-10e8-4c31-ae6f-9b23961020a8', 74, 1, 'accepted', 'test comment', 1, '2020-08-01 07:58:10', '2020-08-01 07:58:10', NULL),
(43, 'e1b3553c-c2ad-4dac-aeec-7c96060c9dd1', 75, 1, 'accepted', 'test comment', 1, '2020-08-01 07:58:35', '2020-08-01 07:58:35', NULL),
(44, '8560dcbf-d8fe-4265-8452-9ca63a44b42b', 76, 1, 'accepted', 'test comment', 1, '2020-08-01 07:59:43', '2020-08-01 07:59:43', NULL),
(45, 'c8412927-376b-4ba0-bc40-39d596c42abf', 77, 1, 'accepted', 'test comment', 1, '2020-08-01 08:00:18', '2020-08-01 08:00:18', NULL),
(46, 'ffea3677-e02e-400c-9407-be795cefc310', 78, 1, 'accepted', 'test comment', 1, '2020-08-01 08:00:50', '2020-08-01 08:00:50', NULL),
(47, '27f3a872-7212-4ab5-9850-cc89e7c6c7ac', 79, 1, 'accepted', 'test comment', 1, '2020-08-01 08:01:08', '2020-08-01 08:01:08', NULL),
(48, 'a4249802-e692-414d-8046-6e8687bfdba5', 80, 1, 'accepted', 'test comment', 1, '2020-08-01 08:02:15', '2020-08-01 08:02:15', NULL),
(49, '191f70c0-916c-4098-bef7-3fe96a3b6ca9', 81, 1, 'accepted', 'test comment', 1, '2020-08-01 08:02:29', '2020-08-01 08:02:29', NULL),
(50, 'abbcc3cb-f66e-4a0d-aedf-41125691e6ec', 82, 1, 'accepted', 'test comment', 1, '2020-08-01 08:03:07', '2020-08-01 08:03:07', NULL),
(51, 'e2f7f09f-b078-4839-a198-793065efba7c', 83, 1, 'accepted', 'test comment', 1, '2020-08-01 08:04:06', '2020-08-01 08:04:06', NULL),
(52, 'eb5c42c4-d7af-4b23-8218-23b06a4979d7', 84, 1, 'accepted', 'test comment', 1, '2020-08-01 08:13:23', '2020-08-01 08:13:23', NULL),
(53, '12ba47f8-e792-48e1-98e7-512bb9122b0b', 85, 1, 'accepted', 'test comment', 1, '2020-08-01 08:13:28', '2020-08-01 08:13:28', NULL),
(54, '84119ff7-4482-4013-9421-7354126dfcbc', 86, 1, 'accepted', 'test comment', 1, '2020-08-01 08:14:24', '2020-08-01 08:14:24', NULL),
(55, '031fb070-0191-4f88-9e22-5d5f553e047d', 87, 1, 'accepted', 'test comment', 1, '2020-08-01 08:14:51', '2020-08-01 08:14:51', NULL),
(56, '7efb05d8-9ccc-4497-88c4-54b4fe4d853a', 89, 1, 'accepted', 'test comment', 1, '2020-08-07 23:38:46', '2020-08-07 23:38:46', NULL),
(57, '05e0a48b-a219-4824-9f24-1dd896f932cb', 90, 1, 'accepted', 'test comment', 1, '2020-08-07 23:44:10', '2020-08-07 23:44:10', NULL),
(58, '16d71684-662c-4080-b81a-a8841e9bbe1d', 91, 1, 'accepted', 'test comment', 1, '2020-08-07 23:44:42', '2020-08-07 23:44:42', NULL),
(59, 'e2c6d25c-6e26-4ec4-b508-b6add524024d', 92, 1, 'accepted', 'test comment', 1, '2020-08-07 23:46:13', '2020-08-07 23:46:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `initial_number_of_hours` decimal(24,1) DEFAULT NULL,
  `initial_service_cost` double(8,2) NOT NULL,
  `final_number_of_hours` decimal(24,1) DEFAULT NULL,
  `final_service_cost` double(8,2) NOT NULL,
  `completion_notes` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_services`
--

INSERT INTO `booking_services` (`id`, `uuid`, `booking_id`, `service_id`, `initial_number_of_hours`, `initial_service_cost`, `final_number_of_hours`, `final_service_cost`, `completion_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'cd1f5db2-c36f-474b-847a-8bea762f43ef', 1, 1, '1.0', 100.00, '1.0', 100.00, NULL, '2020-07-21 06:01:35', '2020-07-21 06:01:35', NULL),
(2, '1db1efa0-37c9-4d3b-bde3-38c2a17ea909', 2, 1, '2.0', 200.00, '3.0', 200.00, NULL, '2020-07-21 06:01:35', '2020-07-21 06:01:35', NULL),
(3, 'f9601e84-d0f1-44aa-8304-976e8c571f25', 2, 3, '1.5', 300.00, '2.0', 300.00, NULL, '2020-07-21 06:01:35', '2020-07-21 06:01:35', NULL),
(7, '86dfc3f0-18d6-4d5c-b619-b0095ec47b21', 1, 1, '2.5', 100.00, '3.0', 100.00, NULL, '2020-08-07 23:59:54', '2020-08-07 23:59:54', NULL),
(8, '6d7f6dd0-ff65-4a87-abee-c9779310de04', 1, 2, '2.0', 200.00, '3.0', 200.00, NULL, '2020-08-07 23:59:54', '2020-08-07 23:59:54', NULL),
(9, 'e2f4b580-124e-4160-ba3a-a0cdc6f0ef0c', 1, 3, '1.5', 300.00, '2.0', 300.00, NULL, '2020-08-07 23:59:54', '2020-08-07 23:59:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_status`
--

CREATE TABLE `booking_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','arrived','completed','cancelled','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_status`
--

INSERT INTO `booking_status` (`id`, `uuid`, `status`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '8ba49684-0387-4933-9380-6e338c2fa2c4', 'pending', 'pending', NULL, NULL, NULL),
(2, 'dcc9153a-177e-4697-8271-dadbe1f3a775', 'approved', 'approved', NULL, NULL, NULL),
(3, '45e7f4d5-c70a-4bb1-a408-48109191ee31', 'arrived', 'arrived', NULL, NULL, NULL),
(4, '5272f442-3234-4cf7-8f99-788b90344696', 'completed', 'completed', NULL, NULL, NULL),
(5, '9a13d978-7455-4b00-8f48-d82532a00c3b', 'cancelled', 'cancelled', NULL, NULL, NULL),
(6, '1126c8c5-815f-4928-a4d2-bfd5a69ee26a', 'rejected', 'rejected', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `chat_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `receiverid` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `cmspages_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pages_type` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heading` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `completed_bookings`
-- (See below for the actual view)
--
CREATE TABLE `completed_bookings` (
`comp_total` bigint(21)
,`provider_id` int(11) unsigned
);

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `cronjob_uuid` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cronjob_url` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cronjob_path` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cronjob_schedule` datetime NOT NULL,
  `cronjob_log` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_metadata`
--

CREATE TABLE `customer_metadata` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `status` enum('active','block','expire') COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_number` bigint(20) NOT NULL,
  `card_name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_card_type` enum('visa','master','international') COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_cvv` int(11) NOT NULL,
  `expiry_month` int(11) NOT NULL,
  `expiry_year` int(11) NOT NULL,
  `user_card_expiry` date NOT NULL,
  `user_card_last_four` int(11) NOT NULL,
  `user_stripe_customer_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_metadata`
--

INSERT INTO `customer_metadata` (`id`, `uuid`, `user_id`, `status`, `card_number`, `card_name`, `user_card_type`, `card_cvv`, `expiry_month`, `expiry_year`, `user_card_expiry`, `user_card_last_four`, `user_stripe_customer_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'd71a01e0-f4f0-4e39-b5dc-8678898f6019', 1, 'block', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 00:02:50', '2020-07-30 00:02:50', NULL),
(2, 'd2094d59-7052-4614-96fc-d788ccd43513', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 00:03:26', '2020-07-30 00:03:26', NULL),
(3, 'c4333884-b048-4004-a624-d8f3886ab275', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 00:03:31', '2020-07-30 00:03:31', NULL),
(4, 'e7eb89b0-a0bb-4341-a4c3-bd4a7e2f37da', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 00:23:13', '2020-07-30 00:23:13', NULL),
(5, 'cf661e28-b71e-4948-93c0-e7a5cd62c514', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 02:38:02', '2020-07-30 02:38:02', NULL),
(6, 'df87a30b-f30a-4983-ac16-be12fc7021cf', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 02:41:38', '2020-07-30 02:41:38', NULL),
(7, '9bae47c5-3d58-4ec4-a3be-75f4829c0250', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 02:42:35', '2020-07-30 02:42:35', NULL),
(8, '52ce3ae1-bb5b-4470-84b5-7e41a4e500a1', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 02:42:41', '2020-07-30 02:42:41', NULL),
(9, '399afa45-b4c3-46dc-a9e8-2fb5c7f03ae3', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 02:43:00', '2020-07-30 02:43:00', NULL),
(10, 'e278f703-953b-45a6-965e-2957fd678b9f', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 02:43:21', '2020-07-30 02:43:21', NULL),
(11, '740c65da-4224-4dea-b8a7-b4f23bb315fc', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 02:44:34', '2020-07-30 02:44:34', NULL),
(12, '7cdd6b14-19f5-4dec-8dd6-3e91b6c9e57b', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 04:59:53', '2020-07-30 04:59:53', NULL),
(13, '6d0dd888-384f-4f59-85dd-da2a0532b38c', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 05:19:33', '2020-07-30 05:19:33', NULL),
(14, 'af7d8a16-a0cc-4521-af14-06d1cc1f2b1f', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-30 05:19:52', '2020-07-30 05:19:52', NULL),
(15, '08fa9fab-e826-4b8b-ae83-7cb86afd0471', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-07-31 08:50:36', '2020-07-31 08:50:36', NULL),
(16, '4ec3d7e1-f75e-4f96-8d92-f422f5b76fa7', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:30:22', '2020-08-01 07:30:22', NULL),
(17, '69274441-323f-49ec-ad0a-c163ba169605', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:31:54', '2020-08-01 07:31:54', NULL),
(18, 'd0b37c50-1eb7-4384-a150-e04c09af0e73', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:35:22', '2020-08-01 07:35:22', NULL),
(19, '6e59a117-6ca7-4366-8704-7bd38c641079', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:35:55', '2020-08-01 07:35:55', NULL),
(20, 'da77aa69-e240-43e6-aabf-c1fa2ce8a48a', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:36:10', '2020-08-01 07:36:10', NULL),
(21, '2c89c8e7-a9f4-47b1-8c1b-845a21df2fd7', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:37:06', '2020-08-01 07:37:06', NULL),
(22, '852927b3-6d19-454e-994f-dc3022377a23', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:37:41', '2020-08-01 07:37:41', NULL),
(23, 'd2c92cb5-2551-4a3e-8d16-ac201be3a08b', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:38:03', '2020-08-01 07:38:03', NULL),
(24, 'c1bc03ec-ac7c-40fa-a333-744a86681907', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:42:47', '2020-08-01 07:42:47', NULL),
(25, 'be79e9d9-93fd-43af-b014-0328d6604a82', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:43:29', '2020-08-01 07:43:29', NULL),
(26, 'f2931089-b4e9-4e6a-8639-7147c2957460', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:45:31', '2020-08-01 07:45:31', NULL),
(27, 'b487588a-148e-4619-93bb-7613ab4fb87f', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:47:51', '2020-08-01 07:47:51', NULL),
(28, 'e6983c45-2657-4bac-8054-7cca8d9d24ba', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:51:28', '2020-08-01 07:51:28', NULL),
(29, '78a8ff1c-7b7d-4277-bbc6-e527fd84b02c', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:52:16', '2020-08-01 07:52:16', NULL),
(30, '86c340c4-b2bc-4d1f-9606-a81b1bd572ee', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:52:44', '2020-08-01 07:52:44', NULL),
(31, '466039d7-8512-4d6d-bd70-d8beb1b90f61', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:53:23', '2020-08-01 07:53:23', NULL),
(32, '836b16f0-a057-4a1f-b8f2-1d7e3d6c2017', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:54:31', '2020-08-01 07:54:31', NULL),
(33, '2cb35d6c-49e6-4f80-a31a-9acf1138d18a', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:55:00', '2020-08-01 07:55:00', NULL),
(34, '3a76b035-1bcd-4026-bf37-a12de77e720b', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:55:28', '2020-08-01 07:55:28', NULL),
(35, 'fdc8063c-7ba6-4d44-a364-778bbf4ba709', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:56:23', '2020-08-01 07:56:23', NULL),
(36, 'dc55a0eb-124c-44b5-99cd-82a989530a76', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:56:58', '2020-08-01 07:56:58', NULL),
(37, '0741bca5-e940-4732-b6bb-57cef6c50d8d', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:58:10', '2020-08-01 07:58:10', NULL),
(38, 'a070e59c-39e1-44c4-a004-a21a7d12f52d', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:58:35', '2020-08-01 07:58:35', NULL),
(39, '4cf295e0-7ff8-47f0-9c99-0f065f8ff1b2', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 07:59:42', '2020-08-01 07:59:42', NULL),
(40, 'bd367f7c-c493-4713-a155-433510dd9008', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:00:18', '2020-08-01 08:00:18', NULL),
(41, '0d767a51-84e9-47f1-bedf-92044ec01d1e', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:00:50', '2020-08-01 08:00:50', NULL),
(42, '706a8804-bdca-49b3-9a22-a7fb360d7ca1', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:01:08', '2020-08-01 08:01:08', NULL),
(43, '698c6f53-64ef-494e-896f-1a124c3dd389', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:02:15', '2020-08-01 08:02:15', NULL),
(44, 'b2c2c8c6-43bd-4c87-89dd-635dbd986df9', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:02:29', '2020-08-01 08:02:29', NULL),
(45, 'f7bedaa6-f219-498c-86a1-22ccead0415f', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:03:07', '2020-08-01 08:03:07', NULL),
(46, '389c380a-09d8-4636-a7c3-a86e7715512a', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:04:06', '2020-08-01 08:04:06', NULL),
(47, '0fc555a8-07a8-4867-b2c6-55b62a69bf61', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:13:22', '2020-08-01 08:13:22', NULL),
(48, '170d0fb2-f53b-438b-b444-c8e98e1c847e', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:13:28', '2020-08-01 08:13:28', NULL),
(49, '3cfcc3d4-a30b-442a-a3ba-ba2ea29bc7c8', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:14:24', '2020-08-01 08:14:24', NULL),
(50, '1051fd02-79f6-4154-b91b-de18ad95c145', 1, 'active', 123456, 'test', 'visa', 123, 5, 2022, '2020-05-26', 1234, 123456789, '2020-08-01 08:14:50', '2020-08-01 08:14:50', NULL),
(51, '5129d415-7192-41bc-ae57-16d20d50d4ae', 38, 'block', 123456, 'rakehs', 'visa', 102, 5, 2022, '2020-05-26', 1234, 1234567, '2020-08-07 23:38:46', '2020-08-07 23:38:46', NULL),
(52, 'd99ae45f-4b52-4fb5-abad-b1733fd8f127', 38, 'block', 123456, 'rakehs', 'visa', 102, 5, 2022, '2020-05-26', 1234, 1234567, '2020-08-07 23:44:10', '2020-08-07 23:44:10', NULL),
(53, '28a47aa7-98dc-4f69-8635-d0f40d7f0908', 38, 'block', 123456, 'rakehs', 'visa', 102, 5, 2022, '2020-05-26', 1234, 1234567, '2020-08-07 23:44:42', '2020-08-07 23:44:42', NULL),
(54, 'fbe1d504-4e69-49c2-9c3f-78ec191522bd', 38, 'block', 123456, 'rakehs', 'visa', 102, 5, 2022, '2020-05-26', 1234, 1234567, '2020-08-07 23:46:13', '2020-08-07 23:46:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'demo', 'demo', 'demo', 'demo', '2020-06-03 11:25:48', NULL, '2020-06-03 05:58:36', NULL),
(2, NULL, 'test', 'test', 'test', 'test error', '2020-06-03 11:21:28', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hacking_activities`
--

CREATE TABLE `hacking_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `hacking_activity_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count_request` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blockrequest` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trustedip` int(11) NOT NULL,
  `blockip` int(11) NOT NULL,
  `log_ativity` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `third_party_api` tinyint(4) NOT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_uuid` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date_printed` date NOT NULL,
  `po_number` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` int(11) NOT NULL,
  `terms` tinyint(4) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `cost` double(8,2) NOT NULL,
  `late_fees` double(8,2) NOT NULL,
  `taxes` double(8,2) NOT NULL,
  `total_due` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_activity_logs`
--

CREATE TABLE `login_activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `login_activity_log_uuid` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ip` int(11) DEFAULT NULL,
  `type` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_time` time DEFAULT NULL,
  `user_activity_log` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 2),
(3, '2017_10_21_222133_permissible_create_roles_and_permissions', 2),
(4, '2017_12_30_222156_permissible_modify_users_table', 2),
(5, '2017_12_31_222156_permissible_add_name_index_to_users_table', 3),
(6, '2020_05_25_085508_create_tempu2s_table', 4),
(7, '2020_05_25_100539_create_failedjobs_table', 5),
(8, '2020_05_25_125822_create_customerusers_table', 6),
(9, '2020_05_26_053935_create_hackingactivities_table', 7),
(10, '2020_05_27_040654_create_notifications_table', 8),
(11, '2020_05_27_041312_create_notificationlogs_table', 9),
(12, '2020_05_27_051247_create_payments_table', 10),
(13, '2020_05_27_052332_create_payment_acivities_table', 10),
(14, '2020_05_27_053217_create_paymentactivitylogs_table', 10),
(15, '2020_05_27_071654_create_once_booking_alternate_dates_table', 11),
(16, '2020_05_27_124635_create_endusermetadata_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_type` enum('transactional','tasks update','cleaner alerts','task recommendations') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `allow_sms` tinyint(1) NOT NULL,
  `allow_email` tinyint(1) NOT NULL,
  `allow_push` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `notification_log_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_type` tinyint(4) NOT NULL,
  `notification_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_user_id` int(11) UNSIGNED NOT NULL,
  `status` enum('read','unread') COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('01465df33d561c83d37c2f473869052d2f3460036a32599b8a2472c7f5b4a7ea3dd3fc19bae60684', 56, '2', NULL, '[\"provider\"]', 0, '2020-07-30 23:33:49', '2020-07-30 23:33:49', '2020-08-15 05:03:49'),
('023e25ee589097c5d2cbba8b37327c2633e9a50858d14aada644f7aaeba1cbec1214ba35f96bb60a', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:49:17', '2020-07-30 06:49:17', '2020-08-14 12:19:17'),
('03dd5ae930db88c8e4dea603b18fef1055885baa6591009ecbaba1d84b5d91d495ef7622800bcbef', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 10:08:25', '2020-07-30 10:08:25', '2020-08-14 15:38:25'),
('05de95bdfe6f7367d1c8ae665b9073fe462d22804b7faaa1a1e1c7b68ad61783fa60b4270e1c5b1e', 55, '2', NULL, '[\"customer\"]', 0, '2020-07-31 05:48:59', '2020-07-31 05:48:59', '2020-08-15 11:18:59'),
('063564d7c6eba872165a99f3ce7bf9f8cebaabdca2ac5655ad23f0f5c47fba799bfb63df4441a0e7', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-25 02:50:45', '2020-07-25 02:50:45', '2020-08-09 08:20:45'),
('069f042b9182515145c9a1ffa1f5a60a64303052813434f6eb3f9e1b60f7c5ea3db2856eebc78e6e', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:18:07', '2020-07-13 04:18:07', '2020-07-28 09:48:07'),
('06bb30a06c550abbe2b36f5120a3c39369f94d86599911e33476691dac5bd2753dcb9e04d47bef70', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:02:40', '2020-07-13 04:02:40', '2020-07-28 09:32:40'),
('09f31d9230b51b79924108c991cb5ed5f26ff28c19692c707e385bd470f4b28ca120562288027562', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:48:58', '2020-07-30 06:48:58', '2020-08-14 12:18:58'),
('0a6daf8254ad2c517fd5ad94636715083d984a37c2ff535731a88914f5ddae926851a584ade09aeb', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 07:01:39', '2020-07-31 07:01:39', '2020-08-15 12:31:39'),
('0d852c99ba80137c326cb87872b5b3984407ef2ea35d8fa5fb84c4ad48f990c288b3e861a52248dd', 36, '2', NULL, '[\"admin\"]', 0, '2020-07-13 07:18:23', '2020-07-13 07:18:23', '2020-07-28 12:48:23'),
('0e36d5b874493497d97d037977fee7fe7ce07cc1eb30ff24d3ab84ab56ddf21bde18a4e9b5547a06', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:41:14', '2020-07-30 01:41:14', '2020-08-14 07:11:14'),
('0f01a71d6f275f48255ef2ead3761a2ae68c48a14a8a030708e01c60228181331b7e79077499fb95', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:08:59', '2020-07-13 05:08:59', '2020-07-28 10:38:59'),
('0f8973b76429167141b38a92b18047a35bad503651fece1c0dee8669907a4771588b53a0761f25ea', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:44:32', '2020-07-30 01:44:32', '2020-08-14 07:14:32'),
('132adfc673a31dd1a9a96b00c75f6e0e615b4f9cd756964414966ee19e7a90655ed27687635d9816', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:06:01', '2020-07-13 04:06:01', '2020-07-28 09:36:01'),
('138de23cb74e42c6315e3731cd3c1e885c46557d32099dcfe9cb92e15ac4bf051fd8a36962c20866', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:54:40', '2020-07-30 06:54:40', '2020-08-14 12:24:40'),
('1498f8471e2aa906adf6098aba6ee8410c54ae7137ff8296038ff4cdd3bbbaaf85b8d8373c9dd226', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:29:59', '2020-07-30 02:29:59', '2020-08-14 07:59:58'),
('1511f057cd53081bd7879ef473e35dc32f68726cc2c3237cade69dd215181107935a6edb4b3965a2', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:14:56', '2020-07-13 04:14:56', '2020-07-28 09:44:56'),
('1604f69effd7bc5a8e7f70a724703827469345fe3a6dc70adf4d69f8ded5ae32b6714a67f3016381', 38, '2', NULL, '[\"customer\"]', 0, '2020-08-08 08:27:07', '2020-08-08 08:27:07', '2020-08-23 13:57:00'),
('161e34d27ad144af718a5272ca3692a35b616998fc25786b0a329e214037f9a96ba312ca20be5924', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:38:41', '2020-07-30 06:38:41', '2020-08-14 12:08:41'),
('16ddb7634d7ca85c1f8441ef1ec9a846b44a1aad1c19f4a1ba56d4bf2e81496a9eb384623f5f159d', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:27:36', '2020-07-30 01:27:36', '2020-08-14 06:57:35'),
('18360516c954bba7e639d8be71c10a57d83b7acf162d05d17c2758ad2fe0783a44457633325392d8', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:23:32', '2020-07-13 04:23:32', '2020-07-28 09:53:32'),
('192d8d923442031165788e4c53d4a75ba42c6bdd5372a9684e6ed7c64f445fe67a6f98bc5d73bbd7', 46, '2', NULL, '[\"provider\"]', 0, '2020-07-30 05:40:43', '2020-07-30 05:40:43', '2020-08-14 11:10:43'),
('197af639247136c20f3a1506c1a395e46fdad55961d559fe18df5d5461382d26caec4028f0a3079b', 10, '2', NULL, '[\"customer\"]', 0, '2020-07-10 00:54:18', '2020-07-10 00:54:18', '2020-07-25 06:24:18'),
('19ff9cd238aed517e289beebfabcc1c21b9bd86d93cc600fce0c8f8bcd208e77125e215f57c02c99', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:39:40', '2020-07-30 01:39:40', '2020-08-14 07:09:40'),
('1a7c1a15d62b265b99dc86270b173e85ca03fc6294beb03d9de5b62808b94c05bbd3fcbd2c5c7bae', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:11:24', '2020-07-30 02:11:24', '2020-08-14 07:41:24'),
('1c09d87d1fd43607dea5f7a6bed7983d53bd1b418436509ac908b8b065608cb499b74c6d4c19dafe', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 03:57:24', '2020-07-13 03:57:24', '2020-07-28 09:27:24'),
('1cc36e4aeba0c9fd80b13794ff4e4c6742c44718bcf3eb02d7121fbfdddbc47fa31d873b377c5d1a', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-30 23:38:32', '2020-07-30 23:38:32', '2020-08-15 05:08:32'),
('1dba345ba73ade1eb7e4753df1cfab43dfb39a515aa95bbe1b6bd34545961c93b188f2fdf2be5e4c', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:50:10', '2020-07-13 05:50:10', '2020-07-28 11:20:10'),
('1efd3e90907fcaae664175d72b06ba8aeabefe9fd25bed3c99bd83d6be30edd674265986b39b4919', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:56:00', '2020-07-30 06:56:00', '2020-08-14 12:25:59'),
('1f427063f746f582d8b0060dc9fae964d4aadee652267ebaff94b0ea80fca2fb3934e67ef3d2af1b', 38, '2', NULL, '[\"customer\"]', 0, '2020-08-04 04:07:58', '2020-08-04 04:07:58', '2020-08-19 09:37:57'),
('21f941a194462faaf14001e9874a75945054b143c49681d13c260f92f87999ba3523fed9491fd0f7', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:27:25', '2020-07-30 01:27:25', '2020-08-14 06:57:25'),
('229bb945193daea617cc7c1ccd81a9ccbe06ec3b2519c5693d2945f5fa65d76e2ac8313c357d79b4', 19, '2', NULL, '[\"admin\"]', 0, '2020-07-10 05:10:31', '2020-07-10 05:10:31', '2020-07-25 10:40:31'),
('246f133d38e661bd5a4aeec4719ff56128bfbac98b2106d08f92075dcb5d53869665ef769b0c4a1f', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:35:32', '2020-07-13 04:35:32', '2020-07-28 10:05:32'),
('2640254b7c6bb18b17382fae8bcd457098346f5a3efff02aebfc6a21876d981dbcfcdac280c69d82', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 06:18:59', '2020-07-31 06:18:59', '2020-08-15 11:48:58'),
('278e2ea246eb697945e691cec6ff40bb3649b850edf27f866d9b9a5e65b14fcd20c9e80e51c4771e', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:46:53', '2020-07-30 06:46:53', '2020-08-14 12:16:53'),
('2cd014d6786ebafada3904f157fbff20b37248af4d5a2385165552699f8a511483b776ae2d898f0f', 56, '2', NULL, '[\"provider\"]', 0, '2020-07-30 23:17:53', '2020-07-30 23:17:53', '2020-08-15 04:47:53'),
('2e37a365e4563810d82ee62bbc31845e4db2797914ca8bb008ad7c262851d61c47edbcc7dd461b55', 45, '2', NULL, '[\"customer\"]', 0, '2020-08-07 02:20:33', '2020-08-07 02:20:33', '2020-08-22 07:50:33'),
('2e5fad220da929d0905e8170b3bbd48c6dc7d31ab9dd46da2e867784db2ec2eb3fa416ea127deece', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:45:33', '2020-07-30 01:45:33', '2020-08-14 07:15:33'),
('3088abd231459f1b6ed9e4330db811c337957d1d39cd7b238abd25de443bc6173aea601d1bd63618', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:51:15', '2020-07-30 02:51:15', '2020-08-14 08:21:15'),
('316d1c0e3237c134b06df1ccf031eb5acd1b0492c10da3fe6b7050d56fab598098173c4d629e8cea', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 03:38:21', '2020-07-30 03:38:21', '2020-08-14 09:08:21'),
('31fe544d8af1825b7ffad9d6f72645376c4bebf00cc9aa33804e9db832abc3eb15b410c5e853bced', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 08:16:22', '2020-07-31 08:16:22', '2020-08-15 13:46:22'),
('32eab4bc29cca1ce122e370e9981a4f7747c6e156a442356d35ad6ed95ab537ba7e0d20cd08f5d67', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 23:23:13', '2020-07-31 23:23:13', '2020-08-16 04:53:13'),
('33d432dfb42a825db0b2c512565333cbfb51150881142deb4f4e9898f9c5199572a1246ded651ae8', 46, '2', NULL, '[\"provider\"]', 0, '2020-07-30 05:53:47', '2020-07-30 05:53:47', '2020-08-14 11:23:47'),
('348cf751b9da01fa4a75afd319584c5d11572abe026dc9069e7c4dc75ea47f1b8fa6708a3d27c913', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-22 23:57:44', '2020-07-22 23:57:44', '2020-08-07 05:27:44'),
('3529a345b0a670bf8fb375c487597f21201e4de2f45634c56d2d8ece72fc29b9e24e84acd519e568', 38, '2', NULL, '[\"provider\"]', 0, '2020-08-07 01:15:15', '2020-08-07 01:15:15', '2020-08-22 06:45:15'),
('37d76aab86479094126d03eaf8267f53b0db226f25cf3222dc1030a7bd6ea6092f97f43fb0356e50', NULL, '913c1d97-5456-4ee1-9419-dc038667d948', NULL, '[\"customer\"]', 0, '2020-08-08 05:55:37', '2020-08-08 05:55:37', '2020-08-23 11:25:33'),
('3da72acd4443b3dc33ec0901e5418685bef24035ab109c8d64a78e3f7a6e6f8b836974d63c270f64', 57, '2', NULL, '[\"customer\"]', 0, '2020-07-31 05:54:10', '2020-07-31 05:54:10', '2020-08-15 11:24:10'),
('3dfc9239f7502ea24f22c0faaeeeecf3f719f7ae4fa1d98d178c474523f4f348c478e0f3eec94da1', 1, '2', NULL, '[\"admin\"]', 0, '2020-07-09 07:26:09', '2020-07-09 07:26:09', '2020-07-24 12:56:09'),
('3f00f6281345950b42093f2b5460d337ea20348726cdb28703a640765324ad71ca622cc12e2ad05e', 57, '2', NULL, '[\"customer\"]', 0, '2020-07-31 06:00:07', '2020-07-31 06:00:07', '2020-08-15 11:30:06'),
('3f7ab0a46b705257af79bcbb8db043da2a2658123a684a2245f6af6ace32eecfbd08226abbd4276f', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 07:56:11', '2020-07-31 07:56:11', '2020-08-15 13:25:42'),
('3fb0bfffe3ede8a2888384c7e7879236331b967235068026017a2c559bf1798a35e462db57312442', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:38:43', '2020-07-30 01:38:43', '2020-08-14 07:08:43'),
('405052eddb85c73da42e4684ab9bd97b3b935897f9d9ebd963e6acf5e933c6ccca865784fcb07e05', 55, '2', NULL, '[\"provider\"]', 0, '2020-07-30 08:23:52', '2020-07-30 08:23:52', '2020-08-14 13:53:51'),
('441026e5390cd7b31cd01ae6305b3e2812d7fb49f1b810161d6dea114baab3f7e01c54b7839d562e', 38, '2', NULL, '[\"customer\"]', 0, '2020-08-07 01:14:20', '2020-08-07 01:14:20', '2020-08-22 06:44:20'),
('45faeeffb212abd5e16480e59e29b437a53f4abd5d96bf5ed4b89ccdfac5ca0a112f942538b27caa', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:43:38', '2020-07-30 01:43:38', '2020-08-14 07:13:38'),
('46a9580cb31fee7588b3e7d657316a4313170a929fdf27789922f0c3c244a7c1e80df13110b20e88', NULL, '913c689e-7458-401b-a785-b10938a83794', NULL, '[\"customer\"]', 0, '2020-08-08 07:11:27', '2020-08-08 07:11:27', '2020-08-23 12:41:23'),
('46fba4f1485e48dee5324170d7be85d95b95fae4f638f7a92f2c19a835bee3f4f80b1e9a52b3b56b', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 01:24:50', '2020-07-13 01:24:50', '2020-07-28 06:54:50'),
('480b27cb67d33e269150e2224660078138532745cf5b8193ef6ac978995723b81b0216f3928ad2bd', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:57:41', '2020-07-13 05:57:41', '2020-07-28 11:27:41'),
('4844f078e00e4fba67ad1a7775e59686c6a7b2e3525ba3bb23d4cc683711da2acf0a64af10c4619e', 36, '2', NULL, '[\"admin\"]', 0, '2020-07-13 07:22:26', '2020-07-13 07:22:26', '2020-07-28 12:52:25'),
('4a9aa426f0d7d360de0f27f6f6a871771ce9d52440953e78c553f355a3fd1f624b0d444390f91569', 38, '2', NULL, '[\"public\"]', 0, '2020-08-07 01:13:03', '2020-08-07 01:13:03', '2020-08-22 06:42:55'),
('4de570f1245ac4a412fafd3149fc0d3e2f2749e6a3ced8fe685642f43237765f98b04c6f86b72b5e', 57, '2', NULL, '[\"provider\"]', 0, '2020-07-30 23:13:46', '2020-07-30 23:13:46', '2020-08-15 04:43:46'),
('4e4c0b7a6a28a16cdd4f7fefff7fad78c2bf0f8bc62d010cea6cdc1fe12a72c626815e44c4e87bef', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 08:18:39', '2020-07-31 08:18:39', '2020-08-15 13:48:39'),
('4f36a00c037ec21bc8d0ce9c09d54baec968507f4345c71e75dc006177bd07de0fb0b5646bb0179c', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:38:31', '2020-07-30 01:38:31', '2020-08-14 07:08:31'),
('506c12de29ec91190d09d71c02791b3785452cb4aad8d18ea169781f2a325b126e69f0eb90b53dc7', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:33:09', '2020-07-13 04:33:09', '2020-07-28 10:03:09'),
('50d2dac999dcd7218e8cbd2f5f0ac0c021ca1c30a501bc35cfe36d6d3da567504e9867445b9c52ff', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:15:24', '2020-07-13 04:15:24', '2020-07-28 09:45:24'),
('52d3f8287d6a3af6a0ce2e38fc35a3d8bbeaeb5e960f6b51b8ea1a9ab50153cb298382f85a9eaeff', 38, '2', NULL, '[\"customer\"]', 0, '2020-08-07 02:29:22', '2020-08-07 02:29:22', '2020-08-22 07:59:21'),
('5700697a08f9d01c6d1604455d5d56c6aa54889b01bbe1f2e94e287ef71aa27b5f6d1940db6f0b94', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:17:20', '2020-07-13 04:17:20', '2020-07-28 09:47:20'),
('5b4a5421710d12ab7374613fc31150ad45bfb48feed6245cbaf9c9bd10a98fbb73f54ef5b9a3178a', 16, '2', NULL, '[\"admin\"]', 0, '2020-07-10 02:12:52', '2020-07-10 02:12:52', '2020-07-25 07:42:52'),
('5b85f148aef0540cd939e93cd22df2ca8fb6d50effc2ed25037f354534001d21de9006dd31b556f8', 36, '2', NULL, '[\"admin\"]', 0, '2020-07-13 07:26:31', '2020-07-13 07:26:31', '2020-07-28 12:56:30'),
('5b8a7c2d1cca1282f4745bd4718b405bd3c944d3941a3bdf9fc40bcb2a67328ce1437bcb974b999b', 56, '2', NULL, '[\"provider\"]', 0, '2020-07-30 23:49:03', '2020-07-30 23:49:03', '2020-08-15 05:19:02'),
('5c369d00ecd33a50663912742c6fa1766750fb31e7ec48304194db30e495df121b5cac08fbcfc0e5', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 03:48:22', '2020-07-13 03:48:22', '2020-07-28 09:18:22'),
('5c9a84fbe15ccc4f4d97263e478897211be577448445c98d976e3b5d7f04911f89eace6e3405f4a2', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:03:20', '2020-07-13 04:03:20', '2020-07-28 09:33:20'),
('5d266c9e3aad19be292345cd0fd5c65127c0e14889312359d6c54baf211d85d0e30f16de07f66c77', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-30 01:22:18', '2020-07-30 01:22:18', '2020-08-14 06:52:18'),
('5ff5ea5d13f4008f50af12cc5a2e61811f4e77cf0ceea542b90f8ee19dd38f5bd371ba363d427d18', 58, '2', NULL, '[\"customer\"]', 0, '2020-07-31 00:14:29', '2020-07-31 00:14:29', '2020-08-15 05:44:29'),
('647717a0a85fe8095205a663a8a280cde0fd2db099fec20a8ab3f0f7733f5da6f57f84a5f6a51312', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:40:42', '2020-07-30 01:40:42', '2020-08-14 07:10:42'),
('66febe40eef476f3f2bda3118080d6fc9d0e05ed27087acdb98b0eb1ba71f647f99a1453cf7db30d', 1, '2', NULL, '[]', 0, '2020-07-09 06:35:36', '2020-07-09 06:35:36', '2020-07-24 12:05:36'),
('69119dabc1ed96226b1afe13c5b1f81418d2e4f1b274b7d7eadd0455342dae57ace17d27bae20bea', 16, '2', NULL, '[\"admin\"]', 0, '2020-07-10 02:08:13', '2020-07-10 02:08:13', '2020-07-25 07:38:13'),
('693d537cd98c3b2c8d0640b0d6fafa40216016badfbd8681086a2afe60f597877c7872f1b98e6d33', 1, '2', NULL, '[\"admin\"]', 0, '2020-07-10 00:02:02', '2020-07-10 00:02:02', '2020-07-25 05:32:02'),
('6bae91426c3b708f5ee016abbf91e248e9b7cd6d56e1defd9a3d4bec7e5dce686ba0693b910706de', NULL, '913c689e-7458-401b-a785-b10938a83794', NULL, '[\"customer\"]', 0, '2020-08-08 06:07:40', '2020-08-08 06:07:40', '2020-08-23 11:37:40'),
('6bcdc64afef24946936d0414e8116f8d1d84f9343a780c6de850079a5a4d877a7cc3ff4009148c07', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:45:35', '2020-07-30 06:45:35', '2020-08-14 12:15:34'),
('6d6f95935f240f3af589ff0f93bbf18d0ed3be7b92506b5b1fec9d80e2a14c3bda78b4f52bd5a26e', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-21 05:54:02', '2020-07-21 05:54:02', '2020-08-05 11:24:02'),
('6d74f3842e791d37b00de5b03f63799edd9e5bec9e3ec8daf54fbdb092ffd2c1c6a0e04e95c39a91', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 06:02:23', '2020-07-13 06:02:23', '2020-07-28 11:32:23'),
('70ed04535c60ec468954f8816bab25545d2b5fda7cba3aacfb2c2d6a0f35a4886a425ec7da0888d9', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:10:17', '2020-07-30 02:10:17', '2020-08-14 07:40:17'),
('71cae1952efc5f1e8a0aec4a0a72cf5b30031cc3b4f9390daa5283d260ee1d2304cadcdbbccddaee', 55, '2', NULL, '[\"customer\"]', 0, '2020-07-30 08:20:32', '2020-07-30 08:20:32', '2020-08-14 13:50:32'),
('7375324aaa395f1d3e7513ac36dc9fc61d3a2cd4112dfaabd7858a2d0076d15b5cc791cf5210ef79', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 08:08:57', '2020-07-31 08:08:57', '2020-08-15 13:38:57'),
('73e0c4fa2cc40aad98ae8b50317330a16afc710a82261b376c98db5b3be20fd102cc3f02338d334f', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 07:08:15', '2020-07-13 07:08:15', '2020-07-28 12:38:15'),
('741fd09782b8f1c5cec6eb3d1301d5ddaf8af0c0a1fa4b5325901c7d2f6f64726d1ebf7fa37ea07f', 56, '2', NULL, '[\"customer\"]', 0, '2020-07-30 07:52:09', '2020-07-30 07:52:09', '2020-08-14 13:22:09'),
('74a1b3e11da82a1fa18d907966c4f23e8b836b11a18670f013a9b556f9489969da0c4eb8fc9a6a9f', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:09:45', '2020-07-30 02:09:45', '2020-08-14 07:39:45'),
('7680f72dd33bb9433082f107f042a16aeeac8ddfa737e608f060d1b51751d966786406613b78e0df', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-30 10:52:29', '2020-07-30 10:52:29', '2020-08-14 16:22:29'),
('7697209a450c5d22d9ef4095056686a52f8ebc69747aac870f5422a04b399b117038a9a619c2c144', 17, '2', NULL, '[\"admin\"]', 0, '2020-07-10 04:33:55', '2020-07-10 04:33:55', '2020-07-25 10:03:55'),
('76f6577b0e946c63b394fe762fd3475c695d0b79885650d2ebaebf1a22180d5e8e3a7566b2c90111', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:17:45', '2020-07-13 04:17:45', '2020-07-28 09:47:45'),
('787ab0eeb407ae2a743be68400795b918c9f1f8f2bc8804639c176311488be9858aa36e5f9889035', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:51:29', '2020-07-13 04:51:29', '2020-07-28 10:21:29'),
('796fb1afbd43f5ea813724b328203657d0b07a128dbc3729a8883e1b7fdf17e7a83320ef00de7212', 56, '2', NULL, '[\"provider\"]', 0, '2020-07-30 23:44:42', '2020-07-30 23:44:42', '2020-08-15 05:14:42'),
('7d826a664637b2b95ac000413a0041bf9f80afe31d0b6b1c902d8275f6cb29e8661f00c52748cbf7', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:34:50', '2020-07-30 06:34:50', '2020-08-14 12:04:50'),
('7db9af177818af48f3a22ed8434cd68798e3409a5e5f9f3b4177ff0b1e4245f61a5c8b9337d3df65', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:53:50', '2020-07-30 06:53:50', '2020-08-14 12:23:50'),
('7e04ebec148ded4891159b9d63c8b795509eab8e58ecbb8bb9f1185244f998a1beb2ce509b67617a', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 05:49:29', '2020-07-31 05:49:29', '2020-08-15 11:19:28'),
('8107a21d5b72cd93ea2676a22e89053177033db36eb655f07f8071c06bbd714c9bd2d5d5161021eb', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:44:24', '2020-07-30 06:44:24', '2020-08-14 12:14:24'),
('81894e351af68aef308f2c6ad3802ff8afa7e2f61b9e3f0c6c490b00e4dc921550fdd722678f9b47', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:35:28', '2020-07-30 01:35:28', '2020-08-14 07:05:28'),
('82b2eefa6221e9737043874915e4e0db9bd7676c26f72aa0627cde2705aa1066e04b8b7e4e23880f', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:11:30', '2020-07-30 02:11:30', '2020-08-14 07:41:29'),
('850b221773a6fac7596e3f82001e26b08d50952eaa974d13b8bd3896aa2624facd19e755bfcc8ee1', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 07:08:04', '2020-07-13 07:08:04', '2020-07-28 12:38:03'),
('85682843df5ee069d242d4630b98d9dffb8ea0046ae7e77b0d94a00eb89df1fed1fef2613f69cb8f', 45, '2', NULL, '[\"customer\"]', 0, '2020-08-07 02:06:42', '2020-08-07 02:06:42', '2020-08-22 07:36:39'),
('86034c54c7d8bb51abfc69f7fee1231b6b185d325d9d5913017ee3d5c38eb3854e1361c37ccc2ab7', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 08:11:19', '2020-07-31 08:11:19', '2020-08-15 13:41:18'),
('8679b90644ffb8364e11b61b2391e633f933d345a102130ee55d98bd379f50d6b80a7579de3c265d', 38, '2', NULL, '[\"provider\"]', 0, '2020-08-07 01:15:18', '2020-08-07 01:15:18', '2020-08-22 06:45:18'),
('86b81a0546d5e410bedf7b9aa3a3c227b30e868689ce06693233c122f49f1916e99594cb49ba84ac', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:42:05', '2020-07-30 01:42:05', '2020-08-14 07:12:05'),
('896d25b5d755e3039b4f56c7cc07674363a9946c2c109ee8494458d1583ade528e58712a8b210d68', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 03:56:14', '2020-07-13 03:56:14', '2020-07-28 09:26:14'),
('899a7d35be67f8c7084bc5fc23b6aba2396e08fcb2cb620ba563720bc9ff8d1f76502d0877e6f287', 56, '2', NULL, '[\"provider\"]', 0, '2020-07-31 00:47:00', '2020-07-31 00:47:00', '2020-08-15 06:17:00'),
('8b052d7a3837f9e93dea69cf13592f9a40216ec5f741de2aa8bbd2975707b77eeb9c3d6f4fe5b2fe', 38, '2', NULL, '[\"customer\"]', 0, '2020-08-07 01:00:55', '2020-08-07 01:00:55', '2020-08-22 06:30:47'),
('8bdadaaa9d5b503de4ad35d979b36939e6d4e569e9b5b2b8bc86172780979c59ae247923250a184f', 45, '2', NULL, '[\"customer\"]', 0, '2020-08-07 02:27:34', '2020-08-07 02:27:34', '2020-08-22 07:57:33'),
('8bee810a52f0523e4804b05656713a80effd1aba589c47709a67b6beb4b7d4c86b10680342c482ab', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 08:11:31', '2020-07-31 08:11:31', '2020-08-15 13:41:31'),
('8dab91476376fe43cf22a4ad0f02cca46cea24426a901e23280b4075530657466c0c695964a6b77a', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:32:46', '2020-07-13 05:32:46', '2020-07-28 11:02:46'),
('8dd710cede263e145c66b157b0c5500ec821f16a2bc6b9c3da2278f419bc78a5cffe73a46411884d', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:09:09', '2020-07-30 02:09:09', '2020-08-14 07:39:09'),
('8de845b536f6f434c56c6ff1fea56b778afd2cd722990a030dcfa137126743294b2afb9a272a85ed', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-29 23:54:45', '2020-07-29 23:54:45', '2020-08-14 05:24:45'),
('8e5357495fa12ded052fd5cb6291f4c7cdb2f51d5c18c9d068defeae788c87ac86d80aeae62f37b4', 1, '2', NULL, '[]', 0, '2020-07-09 07:07:48', '2020-07-09 07:07:48', '2020-07-24 12:37:48'),
('91ac383836c6e9a11667e3043ed51641be694c327eb1b287b82ad07b39e3a0e626125786cd7a9605', 19, '2', NULL, '[\"customer\"]', 0, '2020-07-10 05:24:22', '2020-07-10 05:24:22', '2020-07-25 10:54:21'),
('921aa3f457c4d432e5cc17648b61494ceda77d989eadf8babd7354e7e0608bab921115bea193c3da', NULL, '913c689e-7458-401b-a785-b10938a83794', NULL, '[\"customer\"]', 0, '2020-08-08 08:27:39', '2020-08-08 08:27:39', '2020-08-23 13:57:39'),
('965b14997efeb6a5d881025ae24fb0ef7894ac33a396a1bfa20fd2c61a3b9411c272ef796bab8775', 36, '2', NULL, '[\"admin\"]', 0, '2020-07-13 03:56:57', '2020-07-13 03:56:57', '2020-07-28 09:26:57'),
('97ad12e9b1d8fbc6427686cf280e8bd99eb741180645604041c47117bd206c22c201c6d76e85d59c', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 05:49:11', '2020-07-31 05:49:11', '2020-08-15 11:19:11'),
('97b5fbf63c0783c0c6ed98cf3914a21b1ccbae682afcf9e8ed2077d7ad2ad4235e5e5b25658d939c', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 08:12:05', '2020-07-31 08:12:05', '2020-08-15 13:42:05'),
('9a3d33249a5075f3c62d99ab40a9af67fc18b849d938f2c11119b1a11c4c20bf65bbfb5e33b7507c', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:37:12', '2020-07-30 01:37:12', '2020-08-14 07:07:12'),
('9b01fb2f735b3b9e2eb48f4d043fcbcbbd3fb6b5310d4fa8bbd4919333a6217b6b8290ddc7316cd4', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:04:44', '2020-07-13 04:04:44', '2020-07-28 09:34:44'),
('9d55557e62543d889c08a1e7f3749fa1888c2ac7f03ce18ffec434cc102ee4fbc9d1dc0a6bf71668', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:16:49', '2020-07-13 04:16:49', '2020-07-28 09:46:49'),
('9e68320431360e48ac59659d2e57fce393526f5110fd912b7e2e70cc23e331d609c454358582b8ee', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 10:10:32', '2020-07-30 10:10:32', '2020-08-14 15:40:31'),
('a003a87dd0f59f85e7b8cb90e9793eabc1fda4e722a1a95faacf18dae1f641a1f5662f8d70b6a346', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:50:01', '2020-07-13 05:50:01', '2020-07-28 11:20:01'),
('a2a4fe858820f401bfb9434d4fbc5d2ee9420686c5844393a3c7b7e7ee47e491e342c282b2ef5e23', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:18:19', '2020-07-13 04:18:19', '2020-07-28 09:48:19'),
('a3023c028ca80b08f4c6a89622c6b6b4c6000f8ac1f3c53ad4a6b818b8fd3036d66223dfb946a328', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:15:54', '2020-07-30 06:15:54', '2020-08-14 11:45:54'),
('a341134870f1601dcef55b45983e6f852e232d638ae737cb06b4722677e502d8e2936ef42aa33ab0', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:52:05', '2020-07-13 04:52:05', '2020-07-28 10:22:05'),
('a6f1713d432d90e673b55ed36bcb8a52f5ccee9a80148933313ca2a420336578d414ebb82f10313d', 19, '2', NULL, '[\"admin\"]', 0, '2020-07-10 05:34:05', '2020-07-10 05:34:05', '2020-07-25 11:04:05'),
('a712ab02a4615d4c03c575e3539e1bd1707e91684d7c57a8e2a8c5534db7f1d1fcb67040901ce5c4', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:14:54', '2020-07-30 02:14:54', '2020-08-14 07:44:54'),
('a7570d2ce23bbc8d8509eb4fd02ddb3d4b728037bfe78399de1fc60bc30ed16e8911a615bd4c8a50', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 02:55:07', '2020-07-31 02:55:07', '2020-08-15 08:25:07'),
('a7930120c481c37e3252ab1bd58dc29390db2b8feb3e10858a0b5bfc6b572a721d6cad9986e2b60c', 36, '2', NULL, '[\"admin\"]', 0, '2020-07-14 00:12:58', '2020-07-14 00:12:58', '2020-07-29 05:42:57'),
('a7ff0bc78d699f10c600f09781d726ea8c2e3bd8a7cb69c39e8389172fe7c0c1b33dbcac68b5e146', 56, '2', NULL, '[\"customer\"]', 0, '2020-07-30 07:51:35', '2020-07-30 07:51:35', '2020-08-14 13:21:35'),
('aa49bb1b5ac73a664272e84498c94d71066bba25a51215c268b52fc850c37ab5c5d46efc13f42a8b', 38, '2', NULL, '[\"admin\"]', 0, '2020-08-07 01:14:02', '2020-08-07 01:14:02', '2020-08-22 06:44:02'),
('ad950610b2c6cd095495e1a2ea6336ff6d22db5a763fca52d06f5fbe5c868e9d904dd2b8578defdb', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 05:51:09', '2020-07-31 05:51:09', '2020-08-15 11:21:08'),
('ae40277b9512d7a1d8ed3eed2be15680429b1be66d79907a90c40161c96a037c28396207466e0f9f', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-14 06:08:06', '2020-07-14 06:08:06', '2020-07-29 11:38:06'),
('b272663a9f6dadbdda3a51f6330b0ed7bb09a48b3a0c54a6790cd31e565920f9385b43861665860c', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:44:50', '2020-07-30 01:44:50', '2020-08-14 07:14:49'),
('b314a4ab3f442f08e830056f08e0b617c2d3331ba2e97e04834c79ab3558e012be1642ffd4e85702', 12, '2', NULL, '[\"admin\"]', 0, '2020-07-10 01:06:38', '2020-07-10 01:06:38', '2020-07-25 06:36:38'),
('b3e7c7c8d98e3502b9cb01d2e7d0e8d5971f5647718ce07c88aea10e9201b1de2362867901e2c84f', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:26:58', '2020-07-30 01:26:58', '2020-08-14 06:56:58'),
('b4d1036f5fa771a566fbbcafcfed8c8164d9f6fd2560f4d88e85dd08f4a29e36d01208fafb798c57', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:09:56', '2020-07-13 04:09:56', '2020-07-28 09:39:56'),
('b6af1f0d4532767378335fb62a1a8a677ac99856313f0cd2a74a1d612c9665d74ae83f02943dee7f', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:32:43', '2020-07-30 02:32:43', '2020-08-14 08:02:43'),
('b7ff8d7590ff352e4dfd01d0e0270c70c0f858f8772f8583fdb83bbd96b457aacaa516359981f1ae', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 03:49:15', '2020-07-13 03:49:15', '2020-07-28 09:19:15'),
('b9fbce6934721919b29034106746f4025bcde1ea2ac96cde79acebf230a103b92afd1c435aa0f6a4', 57, '2', NULL, '[\"provider\"]', 0, '2020-07-30 08:27:23', '2020-07-30 08:27:23', '2020-08-14 13:57:22'),
('ba38765d3a3c8ff8a0e6afa95fca4235668794014e8ed2a54b79fceb13c00a6f256642e66dd506f1', 45, '2', NULL, '[\"customer\"]', 0, '2020-08-07 02:23:04', '2020-08-07 02:23:04', '2020-08-22 07:53:03'),
('baa1c081e233b0a92fce8d8a789a41574afbb16d075dadf2c5b9f57d9bd4efdc4d9b472937afa60e', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:16:36', '2020-07-13 04:16:36', '2020-07-28 09:46:36'),
('badeda3edcf8b265541a5feea1c106fa362683f6229ddf53598851bdc4fd4443ecf978212757336d', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 02:09:28', '2020-07-30 02:09:28', '2020-08-14 07:39:28'),
('bc9e806c879535ccaedb7ca882903b792c298f68d93f21c449139189752bbc8f6a34f4ef49cb0592', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 10:18:33', '2020-07-30 10:18:33', '2020-08-14 15:48:33'),
('bcd247bad00ecaed0066ac1bdf7f7b83f2886a4188bc376a7ef66ea464db93b40aeeaf2bf417a543', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 10:34:20', '2020-07-30 10:34:20', '2020-08-14 16:04:20'),
('bda1acc39a9121e4406a637a8c0fe75aaa1f160cd9de7767f9e26586facf1fd91a77ef43c12267f3', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:18:14', '2020-07-13 04:18:14', '2020-07-28 09:48:14'),
('bdcf601703f01e5ba888886c28d7994b449bba0012b4a517a7ee66de58467ac9792a889543817edb', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:18:25', '2020-07-13 04:18:25', '2020-07-28 09:48:25'),
('bdd2e2f5e0e175c6f2466835dd5792403e445ef4ba4acb225e1f4fd1e8d394eb1eaaff32777f5b92', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:10:11', '2020-07-13 04:10:11', '2020-07-28 09:40:11'),
('be56344e4e49e0825c4481f5dd66f54360896b3247838e9e91de35b3b7eb8f0b1d16a5d8ce1dd51e', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-15 01:38:32', '2020-07-15 01:38:32', '2020-07-30 07:08:31'),
('c6ce2a3f9d5bf6b7ffbe798155e14faf457d4d99b2ca4932f205179146c485908630954d03d9b02a', 46, '2', NULL, '[\"provider\"]', 0, '2020-07-30 04:50:02', '2020-07-30 04:50:02', '2020-08-14 10:20:02'),
('c70762300e99340bc5c6aab89277492bf5cb6f1abe540911bedb141eccdc64621be721823c0a280a', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:54:00', '2020-07-13 05:54:00', '2020-07-28 11:24:00'),
('caefae56318a95ebb88e874034da9eb02929f43fa4d51faf495819c66500d0c59850d72b543767c5', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-27 01:51:04', '2020-07-27 01:51:04', '2020-08-11 07:21:03'),
('cb3de322fb7e019f63f037362c352092b701f9d382d30e4b0167aef5a76bcda71821ce8cef588166', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:04:16', '2020-07-13 04:04:16', '2020-07-28 09:34:16'),
('cbbc9ec7b9e471917f2a25dcb59fb83fbfabd30b08b4091f7e085d2c810798811245fdb776153a8a', 36, '2', NULL, '[\"admin\"]', 0, '2020-07-13 01:22:07', '2020-07-13 01:22:07', '2020-07-28 06:52:07'),
('cbdd111ea8c8df522898072b86566275c43e27b1035bcc618e00be66fb0135335585494024e15806', 56, '2', NULL, '[\"provider\"]', 0, '2020-07-30 23:15:52', '2020-07-30 23:15:52', '2020-08-15 04:45:52'),
('cd4aa6aba807be974ce5e5556c6b3081cba1285cf6f6c7b33d8dd573864395539b4a5d5c7890561e', 42, '2', NULL, '[\"customer\"]', 0, '2020-07-22 00:07:18', '2020-07-22 00:07:18', '2020-08-06 05:37:17'),
('ce9fd5a52ea1d7c81c8e31670b5fd3da35c320bc9825a446d0ebdad5e98b869b5c01e90098653dd0', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:37:30', '2020-07-30 01:37:30', '2020-08-14 07:07:30'),
('cf62012dff70a8f4cd27a7458b558fba85185fcaea2c8e00f7dc94fb5acff7b6ba9d90229b716d18', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:54:13', '2020-07-13 05:54:13', '2020-07-28 11:24:13'),
('cfb34cb36f6f631ffd9c3e516a8ebc60096c190ee55fbfdf2b5d924e3c21da978e3308c5b4111bef', 38, '2', NULL, '[\"admin\"]', 0, '2020-07-23 05:57:40', '2020-07-23 05:57:40', '2020-08-07 11:27:39'),
('d2a803a640dac881a540cd018e0e36624299200a5cdbe9cacbec35349073ef8daceecec075de7ae1', NULL, '913c689e-7458-401b-a785-b10938a83794', NULL, '[\"customer\"]', 0, '2020-08-08 06:04:29', '2020-08-08 06:04:29', '2020-08-23 11:34:28'),
('d40caaa2e9b18dfaf8d0a8926de453689a769b3d4c64e5e35ce132a38438eab6079c21ec23a39d74', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:33:41', '2020-07-13 04:33:41', '2020-07-28 10:03:41'),
('d4ba5bb7baaa438b49eee2810c9e1bbc4216938349fc3d88ac1661565b7d628e67dd887885a98e8e', 44, '2', NULL, '[\"provider\"]', 0, '2020-07-30 04:00:52', '2020-07-30 04:00:52', '2020-08-14 09:30:52'),
('d4f4d0b79613755b78b0c997387733e8f535ceb154abb52113b5a35b56dd0f8f1661b12a4c253fc7', 1, '2', NULL, '[\"customer\"]', 0, '2020-07-10 00:12:07', '2020-07-10 00:12:07', '2020-07-25 05:42:07'),
('d7b5d261a6450ade5b6f984856461a610f752fec1149bb1a2880e0ebd6e9e9c8697d4b63a8f8d3c2', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:45:13', '2020-07-30 01:45:13', '2020-08-14 07:15:12'),
('d8b34e1dd912f039b1d1ce4475ec2a11ae93a3a625d23a8290373570d30e2aea129eeedeb513a1a3', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 03:37:50', '2020-07-30 03:37:50', '2020-08-14 09:07:50'),
('d944919f51e90361a514c63bedb44cf91ccf19289bb9dbc69147b003b3a52d18e28c2e16aa821d94', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 09:20:48', '2020-07-30 09:20:48', '2020-08-14 14:50:48'),
('d9f1ad1927eef76a6cc5bd1aca4af921571d63e6050cfe6776e42011e015f37e53ad818cfdc78eba', 46, '2', NULL, '[\"provider\"]', 0, '2020-07-30 05:50:53', '2020-07-30 05:50:53', '2020-08-14 11:20:53'),
('da7c59fe522aa0078c85360bc7f1d7d2d899da9ac7c15d98fd5e7c9b9a1290d339faa5ea06756545', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:45:41', '2020-07-30 01:45:41', '2020-08-14 07:15:41'),
('dae0382620ee953c9a2aedbf2632f198a3b206561ef652a39d2c61abffca97ec9e13d82925751cc8', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:07:39', '2020-07-13 05:07:39', '2020-07-28 10:37:39'),
('db1cc21667b74116c237e67359c94195f2534cc00ac78d67ad11fff7806bb6bc57a94606ccd8c295', 12, '2', NULL, '[\"customer\"]', 0, '2020-07-10 00:59:55', '2020-07-10 00:59:55', '2020-07-25 06:29:55'),
('dc670339ba708209f4b5257fea9f8a3de12792a4e96bc3656ff53c4b1dcef515cba8e8a60273b038', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:35:37', '2020-07-30 01:35:37', '2020-08-14 07:05:37'),
('dd2d413881b5929ab4635b1140af7acae46a40155941f6b10b643cd7a10a399d446c2c9df6ffce47', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:41:36', '2020-07-30 06:41:36', '2020-08-14 12:11:36'),
('dd77748343b784ed10ded263a7bf8ef90293b19295c6bdf7e65cb2a5d4ed3c49ad0329d2e01d1995', 38, '2', NULL, '[\"provider\"]', 0, '2020-08-07 01:34:13', '2020-08-07 01:34:13', '2020-08-22 07:04:13'),
('ddd63b83309061ba40a5ac3245abf1ee9f0da54beb3c6286065501639bba3c989b144ce8659b59a0', 38, '2', NULL, '[\"admin\"]', 0, '2020-07-21 05:51:30', '2020-07-21 05:51:30', '2020-08-05 11:21:30'),
('dea75ec11eb5ae3a68b8977a7b223d3981f2995f8714bc84c8fd1692dafade4ef221b8f14fbe94da', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:09:34', '2020-07-13 05:09:34', '2020-07-28 10:39:34'),
('e04272eb119f623e4f3fb01f420be685af77e5168084ae46708201ba86d93d339897ee3bda54d583', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:41:59', '2020-07-30 01:41:59', '2020-08-14 07:11:58'),
('e2c1bed2c8feacaee153076f6e0ab93b125c2edf3d52bd6ec4fae65f94e7c84936b924440c2a13ff', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:41:22', '2020-07-30 01:41:22', '2020-08-14 07:11:22'),
('e3efc5925c6fbb3929e427c2586b06f7b7c08664b6b3a207c1277f3b57fa2b170f5ddd03e202b952', 19, '2', NULL, '[\"customer\"]', 0, '2020-07-10 05:13:17', '2020-07-10 05:13:17', '2020-07-25 10:43:17'),
('e5e8384e1ef23dfe8cd25e8ba82893ae547b4cb8b96fc968ee5ee527970efca7d26d6daf39b0e3fd', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:06:17', '2020-07-13 05:06:17', '2020-07-28 10:36:17'),
('e5fbb61f6159f3050326c72c5e1478979334cc7fbfc0481f3c726185dec06d23f810b247536a3e3b', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 07:15:00', '2020-07-13 07:15:00', '2020-07-28 12:45:00'),
('e6929410182c6bde66b8f570b51de59604a93f101e53d9c271ef067ff10413a5519e24523f66d89e', 38, '2', NULL, '[\"customer\"]', 0, '2020-08-08 06:08:19', '2020-08-08 06:08:19', '2020-08-23 11:38:04'),
('e6fad39d6433e5fafed052b7e23c7774988f07ee7d751c08e84a45cc1bde10e1bc78ea3d9e40049f', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 06:02:38', '2020-07-13 06:02:38', '2020-07-28 11:32:38'),
('e73e37f38d86af85d55618804d4315c50553569dafb2cc69825ef7ca9c0a37acb296834a87876693', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-30 00:29:05', '2020-07-30 00:29:05', '2020-08-14 05:59:05'),
('e7734137e97b8deb06ba873f4ea6e38f446f1359adceff4b6afdbb9cb1584a2c5a804f02af715508', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 06:03:34', '2020-07-13 06:03:34', '2020-07-28 11:33:33'),
('e9947ffa3c1eb10d4d98fda75e84f7011c00306691f052d21edb69b2f0adaec77bcca4bd6184f334', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:24:44', '2020-07-30 01:24:44', '2020-08-14 06:54:44'),
('e9cc1f5ee2b78c4e5010f001170f643b0bb69178fa11ae74c2cf9e05203aeb03609a4fe7e03afe5d', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 04:17:04', '2020-07-13 04:17:04', '2020-07-28 09:47:04'),
('ec0f64871ea2f2000b0694a97cd295b501701f64d6d302c3e03fe1eec6281e9f6f1589cb70eb9bf1', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:54:20', '2020-07-30 06:54:20', '2020-08-14 12:24:19'),
('ec73c118adb06d4e5f0b218259d62b330e573ecfb71b18d35ff8d4acdc66551aab2115471dac3ac1', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 03:59:16', '2020-07-13 03:59:16', '2020-07-28 09:29:16'),
('ec9529f49e9c6dc7720a47b085f247d605fe758c0419ec3557fcf9867bcce122d37e2dcb41286766', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-24 02:49:51', '2020-07-24 02:49:51', '2020-08-08 08:19:51'),
('ed4288e7c45b7678f32caca67602f91c8ea6da40a8abf32e81624566a4fed5e19ec7a34c3fe30085', 56, '2', NULL, '[\"provider\"]', 0, '2020-07-30 23:17:10', '2020-07-30 23:17:10', '2020-08-15 04:47:10'),
('ee365b6541bbd537ea7396ee67250fdf76440dd46899c9843a88f9b621679260fc44c0cebc42081c', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-30 06:45:25', '2020-07-30 06:45:25', '2020-08-14 12:15:25'),
('ee9763fc2742a0223f1af76a6af9c58c762fc4c6bec9aaf9c900515b78654fa0483b9ad7f6340be3', 45, '2', NULL, '[\"customer\"]', 0, '2020-08-07 02:20:09', '2020-08-07 02:20:09', '2020-08-22 07:50:08'),
('eede23627f898090aee751593200f0359f44fb5d3fd49e70cfa340805fd69711a7f507e2bb5eb17e', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:40:57', '2020-07-30 01:40:57', '2020-08-14 07:10:57'),
('efefd9ed1ada0405598207d07d6a95786e639115737c2863b11539dbf1230eca5de082d9fb57cc0a', 38, '2', NULL, '[\"customer\"]', 0, '2020-08-04 04:07:37', '2020-08-04 04:07:37', '2020-08-19 09:37:37'),
('f057694fdd0ea6a8351e9512e73cb2485e3760033f049bd24edf5dfef6a75677a6db97c25a8f33fb', 18, '2', NULL, '[\"admin\"]', 0, '2020-07-10 04:43:53', '2020-07-10 04:43:53', '2020-07-25 10:13:53'),
('f0b28c8566519d5304fcfc0ce51fbe5856650beb6e8ad2c07ad0c99dcbc55ecb8333e54b3842cddd', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 03:49:49', '2020-07-13 03:49:49', '2020-07-28 09:19:49'),
('f1df39e25008a4d42e7c35b3f1d2859180c95b1c15abde8ba31c83a9f61491182dba93a8020970d5', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-31 04:50:13', '2020-07-31 04:50:13', '2020-08-15 10:20:13'),
('f1f5e9b221530808bb5cb886f51c95c1bddc4a1eb29fcf20dcc771e7059c76cc964947f72909d617', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-14 23:34:17', '2020-07-14 23:34:17', '2020-07-30 05:04:17'),
('f4c1e55004ceed565457b5cdaea22221d6a9c3530591ce7cd813ff944e75e39b5eff57aa44e0084b', 54, '2', NULL, '[\"customer\"]', 0, '2020-07-31 00:06:31', '2020-07-31 00:06:31', '2020-08-15 05:36:31'),
('f4f2213b1f527ed275795483e99814e2e6a74d0d20def118dcccf5f7dc083704502dee3448f23a90', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-21 00:08:49', '2020-07-21 00:08:49', '2020-08-05 05:38:48'),
('f531f27e6ad9603425ca7a6cf3c515c904c945116c9748eb8193ba5bfde3c5189acb6d32c7ee4647', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-15 01:39:38', '2020-07-15 01:39:38', '2020-07-30 07:09:38'),
('f629344c41b3b7ff44bee0b276417784e6bad0c969a1d98e04d9a624db28a912280face9bd1db7b8', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:47:49', '2020-07-13 05:47:49', '2020-07-28 11:17:49'),
('f6c572a2ae562e71ddcf79d430f7b4001c5072fc68a79b4afec9880e30f1b81019ab557586d9ef40', 38, '2', NULL, '[\"provider\"]', 0, '2020-08-07 01:31:38', '2020-08-07 01:31:38', '2020-08-22 07:01:33'),
('f6de51f87da388072ca1436477d19a6fa12385f76110ab96d314ce5e4c9525f24cc03d707134a168', 38, '2', NULL, '[\"provider\"]', 0, '2020-07-30 01:43:51', '2020-07-30 01:43:51', '2020-08-14 07:13:51'),
('f8abffa7a11cf34fa69f86cc7a1755b8055abfa909943e592d3d718582d57b485e0439554da6d580', 36, '2', NULL, '[\"admin\"]', 0, '2020-07-13 05:08:38', '2020-07-13 05:08:38', '2020-07-28 10:38:38'),
('fa5027f897ca60b54f487855359adba854477ae328914b89f791ea00aefa0b01a2a8baea7b05674b', 38, '2', NULL, '[\"customer\"]', 0, '2020-07-17 01:34:31', '2020-07-17 01:34:31', '2020-08-01 07:04:31'),
('fdce2a22f5040ac451a55d14807857ef1c49853ad5bec7b8ac9d93600a4b689fbf87967002a7261c', 36, '2', NULL, '[\"customer\"]', 0, '2020-07-13 05:05:36', '2020-07-13 05:05:36', '2020-07-28 10:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
('1', NULL, 'Laravel Personal Access Client', '9HmcLOeYAZLucr7zehLoa5t7IlzO4LPkCYrzVmHF', NULL, 'http://localhost', 1, 0, 0, '2020-07-08 08:44:34', '2020-07-08 08:44:34'),
('2', NULL, 'Laravel Password Grant Client', '885GMWiodScyRqVE1SwVfMvh8ila5UrDdFcZNO86', 'users', 'http://localhost', 0, 1, 0, '2020-07-08 08:44:34', '2020-07-08 08:44:34'),
('913c689e-7458-401b-a785-b10938a83794', 38, 'yash', 'wU2s59rvOBi4aTAfEEjfM0YtcInzgffkg3Ceu8B5', NULL, 'http://localhost/auth/callback', 0, 0, 0, '2020-08-08 06:03:12', '2020-08-08 06:03:12');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, '90b7a054-1745-446a-bfe6-909574ccb2c4', '2020-06-03 05:53:52', '2020-06-03 05:53:52');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('009398f0a053b4a16c7521b4d33c5b043101f7b258ce77b5f6ad6e37df2037b2512aaebdc04afd6c', '9e68320431360e48ac59659d2e57fce393526f5110fd912b7e2e70cc23e331d609c454358582b8ee', 0, '2020-08-29 15:40:31'),
('0316125f259da8f014f825b320a4ddb034fd828eee07dafd06f7a2ddb5634c2d3aadff69f3fed11e', 'c70762300e99340bc5c6aab89277492bf5cb6f1abe540911bedb141eccdc64621be721823c0a280a', 0, '2020-08-12 11:24:00'),
('0378c89da50f1d85b43aa74d009da41627709775e74781b064261f9a27941c9a535c880b3a210fa4', '7680f72dd33bb9433082f107f042a16aeeac8ddfa737e608f060d1b51751d966786406613b78e0df', 0, '2020-08-29 16:22:29'),
('0a2fa3816679c17e04375ae6d62cb644c76438077efcd8401b5d5b298c64a3e6f9b239dd4455deff', 'dd77748343b784ed10ded263a7bf8ef90293b19295c6bdf7e65cb2a5d4ed3c49ad0329d2e01d1995', 0, '2020-09-06 07:04:13'),
('0a7fa031786bbf4af1ca6854917e36246874ce124be9310c81af3d24adb8fa72f404bd68370a660b', 'b4d1036f5fa771a566fbbcafcfed8c8164d9f6fd2560f4d88e85dd08f4a29e36d01208fafb798c57', 0, '2020-08-12 09:39:56'),
('0e172443a6303e69128927edfa69b31ca7210dd1101a5463f504e41744ee96eb8643cab399c7f809', 'd40caaa2e9b18dfaf8d0a8926de453689a769b3d4c64e5e35ce132a38438eab6079c21ec23a39d74', 0, '2020-08-12 10:03:41'),
('0e58fc0108776a5a6c8a862c077733d324c4184d4bc9c5d9e4aac47d2cc212735b9186f84aeae5ab', '8b052d7a3837f9e93dea69cf13592f9a40216ec5f741de2aa8bbd2975707b77eeb9c3d6f4fe5b2fe', 0, '2020-09-06 06:30:47'),
('0ec314303d204f77cce98e6ef96b6e33a093df6361e1501c78b4f5fe37f94935094c778fe9cfc227', '9d55557e62543d889c08a1e7f3749fa1888c2ac7f03ce18ffec434cc102ee4fbc9d1dc0a6bf71668', 0, '2020-08-12 09:46:49'),
('0ee3a894ee81f540dc6bbc0e1badcbd156892b1c9e97c06ba3cb07adf4ff486417303ce37767fd06', '5b8a7c2d1cca1282f4745bd4718b405bd3c944d3941a3bdf9fc40bcb2a67328ce1437bcb974b999b', 0, '2020-08-30 05:19:02'),
('0f3f9d614f702ca0540f274753c4c803adb1a15c1e43002f5857e807a1f1afcb574abc27d740dd08', '197af639247136c20f3a1506c1a395e46fdad55961d559fe18df5d5461382d26caec4028f0a3079b', 0, '2020-08-09 06:24:18'),
('1168c9633db4ca0300dcd7cc5ddf2cb376cbe3d22b792a4a6088a19bbd2a96ac298b1f8ced5f2e98', '03dd5ae930db88c8e4dea603b18fef1055885baa6591009ecbaba1d84b5d91d495ef7622800bcbef', 0, '2020-08-29 15:38:25'),
('11ad70aa3b7ccd0d145784b7bbbea288d3933c53ac905fbe8349765e45527dbfc4b01b6dec1c870b', '73e0c4fa2cc40aad98ae8b50317330a16afc710a82261b376c98db5b3be20fd102cc3f02338d334f', 0, '2020-08-12 12:38:15'),
('12e770b1828f051a028d4faee696fb318a1abd6a9aaab4017b463f4907c0f960db8349a64bd9be9d', 'e9947ffa3c1eb10d4d98fda75e84f7011c00306691f052d21edb69b2f0adaec77bcca4bd6184f334', 0, '2020-08-29 06:54:44'),
('140641583b4e42a66166ce18aaf511097e1130694f32489d1e2ba34d20f449add693d9dd643048fe', '8bee810a52f0523e4804b05656713a80effd1aba589c47709a67b6beb4b7d4c86b10680342c482ab', 0, '2020-08-30 13:41:31'),
('155f3cf731527691b01fc3ccae40b8ad1e3ee6a206f4ab82704801427db07901ff6d38d4db99de9a', '82b2eefa6221e9737043874915e4e0db9bd7676c26f72aa0627cde2705aa1066e04b8b7e4e23880f', 0, '2020-08-29 07:41:29'),
('16e93d0196b52c4f57ef616760e27a3e8d650ea74897435a6507f9d474a736a632e00e702613bb48', 'cd4aa6aba807be974ce5e5556c6b3081cba1285cf6f6c7b33d8dd573864395539b4a5d5c7890561e', 0, '2020-08-21 05:37:17'),
('190782a84dbde4a17465af00547224bf2562b9d4fcc12b28ae3b28c602704b0a3ddc35cb0a3e2ae1', '899a7d35be67f8c7084bc5fc23b6aba2396e08fcb2cb620ba563720bc9ff8d1f76502d0877e6f287', 0, '2020-08-30 06:17:00'),
('1927ab0f290084c14eacccef96109e4058754a7d0006a42a2b5c45480cabde956501de949a448266', '5c9a84fbe15ccc4f4d97263e478897211be577448445c98d976e3b5d7f04911f89eace6e3405f4a2', 0, '2020-08-12 09:33:20'),
('19d137b0f9960d7c202f3f06ddc2908552a0da9eb2179fb25068aed7aba57c218aecb2ecf73a674d', '31fe544d8af1825b7ffad9d6f72645376c4bebf00cc9aa33804e9db832abc3eb15b410c5e853bced', 0, '2020-08-30 13:46:22'),
('1a4847a0c71ec9723d3c0111b5869bf670a765a9d645dcf8a9767e8914139fafb7bac35de11d3a3f', '8e5357495fa12ded052fd5cb6291f4c7cdb2f51d5c18c9d068defeae788c87ac86d80aeae62f37b4', 0, '2020-08-08 12:37:48'),
('1beacad56dd1ef1599b62a2c29262447d228e53557ef07ad381204deb9b315f8b63d798578e0bb21', 'c6ce2a3f9d5bf6b7ffbe798155e14faf457d4d99b2ca4932f205179146c485908630954d03d9b02a', 0, '2020-08-29 10:20:02'),
('1c1e8b09329a7bd3cf8cfb08df1ef3edc5b3dfe87aa1f0ac3e1f66437897f7faa2d5b4bbb31c853e', '023e25ee589097c5d2cbba8b37327c2633e9a50858d14aada644f7aaeba1cbec1214ba35f96bb60a', 0, '2020-08-29 12:19:17'),
('1c96cb8adf149087ebfe3cc167f65082560f6d84e01b774c1a711a2e5eac0dd23937b5af90ccb81d', '3088abd231459f1b6ed9e4330db811c337957d1d39cd7b238abd25de443bc6173aea601d1bd63618', 0, '2020-08-29 08:21:15'),
('1d0c17eb0afb424a97e0acda6c69cb95719fcfbf58ed57e2ee6a3941ee7f48f66b570ee364070b0e', 'a712ab02a4615d4c03c575e3539e1bd1707e91684d7c57a8e2a8c5534db7f1d1fcb67040901ce5c4', 0, '2020-08-29 07:44:54'),
('1d16c40f781da07ce465ec90dbcb36a46b6cdd131313490c4f1f47a3fa68b882683a74236e67c02f', '81894e351af68aef308f2c6ad3802ff8afa7e2f61b9e3f0c6c490b00e4dc921550fdd722678f9b47', 0, '2020-08-29 07:05:28'),
('1d7391e52fe280a045c0f94d41a2520e21b1fb3b204f1969b4532d0e474dd0c494d789798a452ffd', '6d74f3842e791d37b00de5b03f63799edd9e5bec9e3ec8daf54fbdb092ffd2c1c6a0e04e95c39a91', 0, '2020-08-12 11:32:23'),
('1daa5c6ac39c62cdc2a675f025b1248634f83282642d5e9f72a646d11c7880866e7dafed2ab3df00', '8dd710cede263e145c66b157b0c5500ec821f16a2bc6b9c3da2278f419bc78a5cffe73a46411884d', 0, '2020-08-29 07:39:09'),
('1ebd3892a856b47da3c106a2664796674afdcf826dcba55e740c6752f70153f05b3a07032cc57d4c', '8dab91476376fe43cf22a4ad0f02cca46cea24426a901e23280b4075530657466c0c695964a6b77a', 0, '2020-08-12 11:02:46'),
('1ee3a57c7e7d54c7c175c887c224503c1cc84ddd4710fa9918d2242dd426060111a6c18d93de19c6', 'a2a4fe858820f401bfb9434d4fbc5d2ee9420686c5844393a3c7b7e7ee47e491e342c282b2ef5e23', 0, '2020-08-12 09:48:19'),
('1f10b3eafbc75b142eb7ae5f1a9a4e1a13858a6304ef8dfe82e62e40bbb84b7c45c520f9a17cdf76', '4de570f1245ac4a412fafd3149fc0d3e2f2749e6a3ced8fe685642f43237765f98b04c6f86b72b5e', 0, '2020-08-30 04:43:46'),
('21a8038087ec3c0204952d4e0efb4b372eaa876cb07b851da43169a9999cb4bded851716b25bcca4', '063564d7c6eba872165a99f3ce7bf9f8cebaabdca2ac5655ad23f0f5c47fba799bfb63df4441a0e7', 0, '2020-08-24 08:20:45'),
('226898d0b64cb2e01d0ad0bd66283b64119361c857f3d7cacfe6696115a10fa530334e20d959f323', '5ff5ea5d13f4008f50af12cc5a2e61811f4e77cf0ceea542b90f8ee19dd38f5bd371ba363d427d18', 0, '2020-08-30 05:44:29'),
('226c445054aba4307e3c7ecda89b59e8db49d1854d3f1cb7d5047aa0e0440d0c2f64a3f1d80a1cb3', 'b314a4ab3f442f08e830056f08e0b617c2d3331ba2e97e04834c79ab3558e012be1642ffd4e85702', 0, '2020-08-09 06:36:38'),
('2330181ba60d28c35af566c7cfd1bfeab71fbc5e608c8f3f16d144b7ffcacd1504d62eecc01d3a24', '5d266c9e3aad19be292345cd0fd5c65127c0e14889312359d6c54baf211d85d0e30f16de07f66c77', 0, '2020-08-29 06:52:18'),
('241ba737399775a672b6c79e1b5b4c0dd39590ccd5543cdfda7a981bd1ae4a3bef4db752489eb5d9', 'dc670339ba708209f4b5257fea9f8a3de12792a4e96bc3656ff53c4b1dcef515cba8e8a60273b038', 0, '2020-08-29 07:05:37'),
('24a585337657b9170d3de7bfa05546824c76a1a4cd39dd71adc446a191ec4ee9bd92fbd6c4c660c6', '2e37a365e4563810d82ee62bbc31845e4db2797914ca8bb008ad7c262851d61c47edbcc7dd461b55', 0, '2020-09-06 07:50:33'),
('25406f8144b18a873b692d483e01d456afb090d15bd2a2bb8f41e1272aad48107119d872a1b18abf', '8de845b536f6f434c56c6ff1fea56b778afd2cd722990a030dcfa137126743294b2afb9a272a85ed', 0, '2020-08-29 05:24:45'),
('25f4d248886235b22fe04d9678a556fc9d713bd8ffcecef749b664b88657fdd3688d29b39c3908f4', 'ce9fd5a52ea1d7c81c8e31670b5fd3da35c320bc9825a446d0ebdad5e98b869b5c01e90098653dd0', 0, '2020-08-29 07:07:30'),
('26a2e786953cab127fa9257c1b896e246102a3193a875a015466ae796c44bb1c300758f31b8c3c25', 'e6fad39d6433e5fafed052b7e23c7774988f07ee7d751c08e84a45cc1bde10e1bc78ea3d9e40049f', 0, '2020-08-12 11:32:38'),
('2ae0018cb654fdad1fb676d2f457aa6fad0c155a0577b8af90b48488423627639bfb51248d84d835', 'e5e8384e1ef23dfe8cd25e8ba82893ae547b4cb8b96fc968ee5ee527970efca7d26d6daf39b0e3fd', 0, '2020-08-12 10:36:17'),
('2afb9566075a8faf163cbfe4d5579b8242a52c3b9059af8af6a8f8ab5c65e57b7af34a6d4ac17de4', '405052eddb85c73da42e4684ab9bd97b3b935897f9d9ebd963e6acf5e933c6ccca865784fcb07e05', 0, '2020-08-29 13:53:51'),
('2c6837105cebc042167fc12ebdf53df11a73f206baed1a87f921215dfc78ca641e32575e2949e725', '441026e5390cd7b31cd01ae6305b3e2812d7fb49f1b810161d6dea114baab3f7e01c54b7839d562e', 0, '2020-09-06 06:44:20'),
('2cedffcc9acb79404612068895398f3fbd2a2867d766b63130f35d2740e5126716fe26c93734ab06', '5c369d00ecd33a50663912742c6fa1766750fb31e7ec48304194db30e495df121b5cac08fbcfc0e5', 0, '2020-08-12 09:18:22'),
('2d440dba9d156251672b68d985ac4142c99575ee4e17be86d5513a54e1869a4c6c28e68860b13a51', '741fd09782b8f1c5cec6eb3d1301d5ddaf8af0c0a1fa4b5325901c7d2f6f64726d1ebf7fa37ea07f', 0, '2020-08-29 13:22:09'),
('2ffaa05bf96cf92c378094db9e7c86e8b170f6835dbe50138a06e7f1052597c9ef24532847905834', '45faeeffb212abd5e16480e59e29b437a53f4abd5d96bf5ed4b89ccdfac5ca0a112f942538b27caa', 0, '2020-08-29 07:13:38'),
('31713247dfbbcb8bd663bb4c00cc3c79d379f678725477d66d718f6ecf97172950f43497132a5297', 'e9cc1f5ee2b78c4e5010f001170f643b0bb69178fa11ae74c2cf9e05203aeb03609a4fe7e03afe5d', 0, '2020-08-12 09:47:04'),
('31dbc2545f7444bd211f5738f67b70431e87faed7947d9d860a0b088b9e8a9437c84dcda88429b9f', '1f427063f746f582d8b0060dc9fae964d4aadee652267ebaff94b0ea80fca2fb3934e67ef3d2af1b', 0, '2020-09-03 09:37:57'),
('323eee5c3d6f706cfdaf13059b28d65da1823ce3b1286dada339d570da5bd7d1464942ae32dd9ed7', '0f8973b76429167141b38a92b18047a35bad503651fece1c0dee8669907a4771588b53a0761f25ea', 0, '2020-08-29 07:14:32'),
('3381cfc439faff6f4e2679693df5dcea6ce90d30c77385bfbdc09a5ab85e8f522ad0908841331688', '32eab4bc29cca1ce122e370e9981a4f7747c6e156a442356d35ad6ed95ab537ba7e0d20cd08f5d67', 0, '2020-08-31 04:53:13'),
('33b490f3bd64645863aa3c6b819890f72d1c8b1b82322f00a8987c1186b8e1b5d494d8a2469888ae', '71cae1952efc5f1e8a0aec4a0a72cf5b30031cc3b4f9390daa5283d260ee1d2304cadcdbbccddaee', 0, '2020-08-29 13:50:32'),
('33d3b1f658a4ba820d309b67d1ff140eeb98d5cd9b587295963bf8ba454af87a53c9274782c0f224', 'db1cc21667b74116c237e67359c94195f2534cc00ac78d67ad11fff7806bb6bc57a94606ccd8c295', 0, '2020-08-09 06:29:55'),
('35bd82d498581793ec0288bdc528b5c177f6716d70c10864d1ed41f761daf49a660e105c1cf236b8', 'f6c572a2ae562e71ddcf79d430f7b4001c5072fc68a79b4afec9880e30f1b81019ab557586d9ef40', 0, '2020-09-06 07:01:33'),
('39e18a82eebfe4fb491a2a28006c027404c682b9c7db3c738ba55342b7af2c58e4f8940c0b46812c', 'ae40277b9512d7a1d8ed3eed2be15680429b1be66d79907a90c40161c96a037c28396207466e0f9f', 0, '2020-08-13 11:38:06'),
('3b0584de43cf62fb2927eda134f067a296787854af58c4d453d253f4c64c7b22af0efd045428889d', '161e34d27ad144af718a5272ca3692a35b616998fc25786b0a329e214037f9a96ba312ca20be5924', 0, '2020-08-29 12:08:41'),
('3b16fb5955dac1a734cb0265434f4d36ccecd48884ede279fd430d336e1e5db03e34f5c7c22474c4', 'e6929410182c6bde66b8f570b51de59604a93f101e53d9c271ef067ff10413a5519e24523f66d89e', 0, '2020-09-07 11:38:04'),
('3f44fcf31eaf723ef55e174e13ef245a36e8bc1b03f8abd666c7157e74edb2d219b2a69f46135a5f', 'ec9529f49e9c6dc7720a47b085f247d605fe758c0419ec3557fcf9867bcce122d37e2dcb41286766', 0, '2020-08-23 08:19:51'),
('4016ae22f3542ad9b221ca3f40d21420cd5d6c8c14d1f30b1f183b6df1a137f3fa801737a67a705b', '1efd3e90907fcaae664175d72b06ba8aeabefe9fd25bed3c99bd83d6be30edd674265986b39b4919', 0, '2020-08-29 12:25:59'),
('4019833778ad70ef1531dabfa3eea796a52dfc034a1237fbc65c6db383f7d4d0d3e6113e49a572e3', 'cbdd111ea8c8df522898072b86566275c43e27b1035bcc618e00be66fb0135335585494024e15806', 0, '2020-08-30 04:45:52'),
('404df44d8b9621c4e9d23653b6dae4ef9d28922e2d9dfd26bb73635c83be90da1683b9e7eb99e148', '896d25b5d755e3039b4f56c7cc07674363a9946c2c109ee8494458d1583ade528e58712a8b210d68', 0, '2020-08-12 09:26:14'),
('42c63f0420967308b21f2e7c47463efbfca26a69bb0772fdfc7fcbff466e645632f140f6122f24c2', 'f8abffa7a11cf34fa69f86cc7a1755b8055abfa909943e592d3d718582d57b485e0439554da6d580', 0, '2020-08-12 10:38:38'),
('42f5dfbfeb756495fb9435f1c27bf629ed29de5f94e11f15ca32ae5ee10bcab35ed46166176ca6cb', '138de23cb74e42c6315e3731cd3c1e885c46557d32099dcfe9cb92e15ac4bf051fd8a36962c20866', 0, '2020-08-29 12:24:40'),
('430a7e8c6df9f23d2143eaa1285a3e09c252c324fded29a4a506f7395b1c71a4d8466540b1a613cf', '4e4c0b7a6a28a16cdd4f7fefff7fad78c2bf0f8bc62d010cea6cdc1fe12a72c626815e44c4e87bef', 0, '2020-08-30 13:48:39'),
('43e01d49a7ccdc90b1d70d8d236c2ed64103d1a030d7fd9f75717534f277d234a4e3768b8dca94c0', 'cb3de322fb7e019f63f037362c352092b701f9d382d30e4b0167aef5a76bcda71821ce8cef588166', 0, '2020-08-12 09:34:16'),
('4671884578d680a213fd44cfc68d82096fa4ec1ba2897b0771b67d019883c11de66ad0df3452c9ef', '6d6f95935f240f3af589ff0f93bbf18d0ed3be7b92506b5b1fec9d80e2a14c3bda78b4f52bd5a26e', 0, '2020-08-20 11:24:02'),
('473a34c76b0977a8fbf59f83f841008c3931749cbe418f0b7be8d86f6f7e1f891c61c0d3daccab98', '316d1c0e3237c134b06df1ccf031eb5acd1b0492c10da3fe6b7050d56fab598098173c4d629e8cea', 0, '2020-08-29 09:08:21'),
('49946ba3e337fd42064f5a21ab9a893fbb25d7018f31a005ccab6721e2666e50049d79029bd1c3c2', '132adfc673a31dd1a9a96b00c75f6e0e615b4f9cd756964414966ee19e7a90655ed27687635d9816', 0, '2020-08-12 09:36:01'),
('4ad94645ab4290601876ce09e2d13a7b40c9ccac37cb8146d623e1031f13199f07dfc4e483513fc4', '0a6daf8254ad2c517fd5ad94636715083d984a37c2ff535731a88914f5ddae926851a584ade09aeb', 0, '2020-08-30 12:31:39'),
('4be12ed578839ac85fe258f779fd4464c47d951a61fdb23c395d1d8d4eb1adfb8c157114cf7d9745', '46fba4f1485e48dee5324170d7be85d95b95fae4f638f7a92f2c19a835bee3f4f80b1e9a52b3b56b', 0, '2020-08-12 06:54:50'),
('4e06d150526a02d6da18c690f86f7e6dc622d85f501f84460ea530ffcdc5c33fc534001b74bdc891', '8107a21d5b72cd93ea2676a22e89053177033db36eb655f07f8071c06bbd714c9bd2d5d5161021eb', 0, '2020-08-29 12:14:24'),
('4f3f86b611d800a212bb45212a8265cb67695e4d709f15ee32f93653a14ace056ff27c9777e26d52', '1c09d87d1fd43607dea5f7a6bed7983d53bd1b418436509ac908b8b065608cb499b74c6d4c19dafe', 0, '2020-08-12 09:27:24'),
('4fbe8bd6fdc04688bcef6314aa49e76e99becccc07e2320cc958dfcd4c83b0f3a1b175a5e1acf597', 'cbbc9ec7b9e471917f2a25dcb59fb83fbfabd30b08b4091f7e085d2c810798811245fdb776153a8a', 0, '2020-08-12 06:52:07'),
('51360624b31ced05f44ad9b933f4a46857e2dc774a5e3faed41d6de6251157622a79cfc84aa10909', '693d537cd98c3b2c8d0640b0d6fafa40216016badfbd8681086a2afe60f597877c7872f1b98e6d33', 0, '2020-08-09 05:32:02'),
('5259ed9cafe682e3b9499f5985e0cd4590b06470f70344c618f6b4e82e6935dfed16bd62b99fd3d7', '21f941a194462faaf14001e9874a75945054b143c49681d13c260f92f87999ba3523fed9491fd0f7', 0, '2020-08-29 06:57:25'),
('552cd42e85e4f4fc9a09268e48ed6249cbabf2574569ed30d0b2498cd514c44a5411d3b7b26cade0', '7697209a450c5d22d9ef4095056686a52f8ebc69747aac870f5422a04b399b117038a9a619c2c144', 0, '2020-08-09 10:03:55'),
('55b42638596e112abff3b5d474b2a799a1b1af9e1c17f5af4b96a838e1c75a3f86a8d5478a2bd488', 'ba38765d3a3c8ff8a0e6afa95fca4235668794014e8ed2a54b79fceb13c00a6f256642e66dd506f1', 0, '2020-09-06 07:53:03'),
('572c5786b934f422eaba474a725ad21c90f52f3c9e88bc89318c088f40eb15e515441684c4f30d36', 'bcd247bad00ecaed0066ac1bdf7f7b83f2886a4188bc376a7ef66ea464db93b40aeeaf2bf417a543', 0, '2020-08-29 16:04:20'),
('591e95a6773fe97b79f432513923e53a5b1bb6ae8ef3e856d8d5ab99e4383a7324a7d7f15967d6be', 'd944919f51e90361a514c63bedb44cf91ccf19289bb9dbc69147b003b3a52d18e28c2e16aa821d94', 0, '2020-08-29 14:50:48'),
('59852191142011cbdd428a3c310b1c224eb38b52e33f8f7585a19fb714ce7b37a54224ae4be9113b', '7e04ebec148ded4891159b9d63c8b795509eab8e58ecbb8bb9f1185244f998a1beb2ce509b67617a', 0, '2020-08-30 11:19:28'),
('5b0b1d413f727ae0cabd696ae5e10ad15b43153977e54ba3313f9ac7f70b9c1ca559199e8c8a5e5d', 'f1df39e25008a4d42e7c35b3f1d2859180c95b1c15abde8ba31c83a9f61491182dba93a8020970d5', 0, '2020-08-30 10:20:13'),
('5b4251431bff1c8b95479e2d0af1cf4e62a4abf2b15453104904c4c065cea2c0934e02aac402cf33', '1604f69effd7bc5a8e7f70a724703827469345fe3a6dc70adf4d69f8ded5ae32b6714a67f3016381', 0, '2020-09-07 13:57:01'),
('5eeb1da0c36784d1d6c2d14ab37d5d7f43ce055d84ac3c9353f442100f7359e444f38220bf203f56', 'be56344e4e49e0825c4481f5dd66f54360896b3247838e9e91de35b3b7eb8f0b1d16a5d8ce1dd51e', 0, '2020-08-14 07:08:31'),
('60a1ac7b09a2a5bb8e125fb910fa6afe5c29097779e2f93f4ef4ea96cb2d7ef22513f63809a8b700', '4a9aa426f0d7d360de0f27f6f6a871771ce9d52440953e78c553f355a3fd1f624b0d444390f91569', 0, '2020-09-06 06:42:55'),
('627260bba1927d52fcbf15c7be7fdce5c9a2725163805cf128f78f15fdc76b585a1fcec721916b50', '3529a345b0a670bf8fb375c487597f21201e4de2f45634c56d2d8ece72fc29b9e24e84acd519e568', 0, '2020-09-06 06:45:15'),
('6426245fa2a3e1447554fc1ee1f4e626fd6d2eb3321b2d9fd811acc090a322eb60c1ad7935a64633', 'f4c1e55004ceed565457b5cdaea22221d6a9c3530591ce7cd813ff944e75e39b5eff57aa44e0084b', 0, '2020-08-30 05:36:31'),
('65d4da9e592e2eed7d516e223b1e4fb7924c35decd4a0ca7507d5b3beb90404161b4da80e903384e', '0f01a71d6f275f48255ef2ead3761a2ae68c48a14a8a030708e01c60228181331b7e79077499fb95', 0, '2020-08-12 10:38:59'),
('68140096f4f522fa6d7843df22d1164ea35261b3d9edd1ab0d9451082f8dba55902c7968ae8d8d38', 'e7734137e97b8deb06ba873f4ea6e38f446f1359adceff4b6afdbb9cb1584a2c5a804f02af715508', 0, '2020-08-12 11:33:33'),
('68d51bd8dd9d1c48199a80f9e25b77c79e72073716fc569d3f5901176345d725b2fe890ce60ec9cd', 'fdce2a22f5040ac451a55d14807857ef1c49853ad5bec7b8ac9d93600a4b689fbf87967002a7261c', 0, '2020-08-12 10:35:36'),
('6941f7f116b6e3cd5c984bd523d9c973ad6d8397e38fdb8dbbf45c42c03edf0473d798cd743414c2', '7375324aaa395f1d3e7513ac36dc9fc61d3a2cd4112dfaabd7858a2d0076d15b5cc791cf5210ef79', 0, '2020-08-30 13:38:57'),
('698fb772fca7c60e587de55d40cbc857f042360acf1e0b4a877f93c3d36c78b08144f8a166766e63', 'dae0382620ee953c9a2aedbf2632f198a3b206561ef652a39d2c61abffca97ec9e13d82925751cc8', 0, '2020-08-12 10:37:39'),
('69efbd6045bcb7b45a3854448567b92ea9d5f9bc16bebd9d2c90fa7a1768da7aa6a1e33b6e1d7690', '76f6577b0e946c63b394fe762fd3475c695d0b79885650d2ebaebf1a22180d5e8e3a7566b2c90111', 0, '2020-08-12 09:47:45'),
('6a61595dca140ec42e9489d466f3ed5af7be25a39a51cd8d6b50125826196c863e5523d9a8f90218', 'bdcf601703f01e5ba888886c28d7994b449bba0012b4a517a7ee66de58467ac9792a889543817edb', 0, '2020-08-12 09:48:25'),
('6b9eb6ee5139108c068b9d0a9afa554858b2eb9fa1800391272e107539f017a64e7a690d331d5548', 'da7c59fe522aa0078c85360bc7f1d7d2d899da9ac7c15d98fd5e7c9b9a1290d339faa5ea06756545', 0, '2020-08-29 07:15:41'),
('6da076df69cce6bb5c014aae348089f95a7b6b0d57d742f3bd5ddc3a251c456fd52e92cb692752ec', 'b9fbce6934721919b29034106746f4025bcde1ea2ac96cde79acebf230a103b92afd1c435aa0f6a4', 0, '2020-08-29 13:57:22'),
('6dd642426c9faa1f84cbe55836d9908d578d743d29081609a51f0a914d6ab430a578cd12b950bb4c', 'ec73c118adb06d4e5f0b218259d62b330e573ecfb71b18d35ff8d4acdc66551aab2115471dac3ac1', 0, '2020-08-12 09:29:16'),
('7150184a34bbcab8b0bfcc4679f1c75d19239220642d7465b81ad782340ce4a2b3d87999691f0d3d', '5b4a5421710d12ab7374613fc31150ad45bfb48feed6245cbaf9c9bd10a98fbb73f54ef5b9a3178a', 0, '2020-08-09 07:42:52'),
('717cdecfa538a7f991dbe15dd6bef842a966b4073da207640bb8786231e01e9a3bbab37fb1370847', '1dba345ba73ade1eb7e4753df1cfab43dfb39a515aa95bbe1b6bd34545961c93b188f2fdf2be5e4c', 0, '2020-08-12 11:20:10'),
('73abf2fadc5ca3338c186d633e8b0594488ab5be7723f85e299be823a048fa914ca05f13b343007c', '70ed04535c60ec468954f8816bab25545d2b5fda7cba3aacfb2c2d6a0f35a4886a425ec7da0888d9', 0, '2020-08-29 07:40:17'),
('756a301832aad5f2d09f16f7aa935283f1e7a9f41af4dd8e9e3ba174bee7d98d8c1d15bc872a287a', '246f133d38e661bd5a4aeec4719ff56128bfbac98b2106d08f92075dcb5d53869665ef769b0c4a1f', 0, '2020-08-12 10:05:32'),
('770c823c172d7700941eca9589316d6310dc85e6f80f22923dccc25a966f2a4a5d1f042c11f6163a', 'aa49bb1b5ac73a664272e84498c94d71066bba25a51215c268b52fc850c37ab5c5d46efc13f42a8b', 0, '2020-09-06 06:44:02'),
('78542f19f206c841ac8067030254ae10d23cbdc015359b26147c259d0bec95895597c78d15cc1aa1', '8679b90644ffb8364e11b61b2391e633f933d345a102130ee55d98bd379f50d6b80a7579de3c265d', 0, '2020-09-06 06:45:18'),
('785926ccb37d21fdf822ab0c5391333c9070f88f28d032c0ac82798db4e57245ce829599b523b1bf', '01465df33d561c83d37c2f473869052d2f3460036a32599b8a2472c7f5b4a7ea3dd3fc19bae60684', 0, '2020-08-30 05:03:49'),
('789b2b52651ed931f3d9124c1f4b641a3b403640a8eecef61d1d84131b77ccce4f52896d44761d99', '850b221773a6fac7596e3f82001e26b08d50952eaa974d13b8bd3896aa2624facd19e755bfcc8ee1', 0, '2020-08-12 12:38:03'),
('7c6d118c58b69bed2d797b6fed6d5358e6ec8e83e455f76de8d71154b8715987284325d3edfef260', 'fa5027f897ca60b54f487855359adba854477ae328914b89f791ea00aefa0b01a2a8baea7b05674b', 0, '2020-08-16 07:04:31'),
('7f3ea5614f2737a09dd4ca2c8600db92298f2041ce656dc9671258fce383216dacc5ebc107c271a6', 'ddd63b83309061ba40a5ac3245abf1ee9f0da54beb3c6286065501639bba3c989b144ce8659b59a0', 0, '2020-08-20 11:21:30'),
('80f17e73b58b7263eaf8e0df307dbfe4731f5776ae0bca9ed628de3754350c08ba252e795af41392', 'ee365b6541bbd537ea7396ee67250fdf76440dd46899c9843a88f9b621679260fc44c0cebc42081c', 0, '2020-08-29 12:15:25'),
('81968fb44ad32874a09950eca99d10759d8e6520b199c22779304de7c7b4884fe17b11677cc55e63', 'a6f1713d432d90e673b55ed36bcb8a52f5ccee9a80148933313ca2a420336578d414ebb82f10313d', 0, '2020-08-09 11:04:05'),
('8244268caa26b7d718671ecfa7da6d62cc92c47c2fb3064a05b4af3caebedbd895ced61612a30a53', '796fb1afbd43f5ea813724b328203657d0b07a128dbc3729a8883e1b7fdf17e7a83320ef00de7212', 0, '2020-08-30 05:14:42'),
('82f58a54805fa2fc3695b9c5b6705261963356da6c464ee083474665bbd958ed09f7ba6e8209a74b', '2640254b7c6bb18b17382fae8bcd457098346f5a3efff02aebfc6a21876d981dbcfcdac280c69d82', 0, '2020-08-30 11:48:58'),
('86c1193531a43a40f6bdbff6257b036f740b01761c867d91aefe0c65d3d3f66ce2735dd34259e090', 'd7b5d261a6450ade5b6f984856461a610f752fec1149bb1a2880e0ebd6e9e9c8697d4b63a8f8d3c2', 0, '2020-08-29 07:15:12'),
('8869f279e01c8f2393f1e080f483e48a4a77819fcba2565dff370b93a988c7eb78d975799d988ad8', 'baa1c081e233b0a92fce8d8a789a41574afbb16d075dadf2c5b9f57d9bd4efdc4d9b472937afa60e', 0, '2020-08-12 09:46:36'),
('896cdb325a87767dacebac0c3e3e1bfe96c3f01f4e60a34ef256e922cdc4a9bc93144811ac90f5e3', '86034c54c7d8bb51abfc69f7fee1231b6b185d325d9d5913017ee3d5c38eb3854e1361c37ccc2ab7', 0, '2020-08-30 13:41:18'),
('89b043b2c61ead0a959b1c412c1f3643bed43ac05909bf17776db93d175f9aea91c669d54149886b', 'a3023c028ca80b08f4c6a89622c6b6b4c6000f8ac1f3c53ad4a6b818b8fd3036d66223dfb946a328', 0, '2020-08-29 11:45:54'),
('89c63bf557f7fb0a080942f743dd76da452d367bf486f20fadc3b698d0b30dee3af751eff78b02f2', '787ab0eeb407ae2a743be68400795b918c9f1f8f2bc8804639c176311488be9858aa36e5f9889035', 0, '2020-08-12 10:21:29'),
('8a8793717f75bc5f36022e807cebda13b6705ef211cb948278a288efa65842f10467d61bbbc69d3e', 'a7ff0bc78d699f10c600f09781d726ea8c2e3bd8a7cb69c39e8389172fe7c0c1b33dbcac68b5e146', 0, '2020-08-29 13:21:35'),
('8c17f884e4757f256e94b7fb30db7d941c0f435522c4cabdfbb6589ef517d88895dd8b6983e0b811', '278e2ea246eb697945e691cec6ff40bb3649b850edf27f866d9b9a5e65b14fcd20c9e80e51c4771e', 0, '2020-08-29 12:16:53'),
('8c9604f148ba8f8073e89289033a3b264347424aa4439d2d2c8a1792b8b1bf4160422d4a57a3574e', '5700697a08f9d01c6d1604455d5d56c6aa54889b01bbe1f2e94e287ef71aa27b5f6d1940db6f0b94', 0, '2020-08-12 09:47:20'),
('8d67fd3f70717bdfa1155722498abad20b2dd7cb3c842384faab616790d6231fe144aa4b3ab81dbf', 'd4f4d0b79613755b78b0c997387733e8f535ceb154abb52113b5a35b56dd0f8f1661b12a4c253fc7', 0, '2020-08-09 05:42:08'),
('8e247adaf15a344c963f00d92355098ed8fe837667d26034e0b4f241143e8821cd2665a707edc112', '3dfc9239f7502ea24f22c0faaeeeecf3f719f7ae4fa1d98d178c474523f4f348c478e0f3eec94da1', 0, '2020-08-08 12:56:09'),
('91f758f3f164f7713b5748ddd3ce1dcd112038624a79cc88eb2a8bcd616d8517b6720491a885ffcf', 'a003a87dd0f59f85e7b8cb90e9793eabc1fda4e722a1a95faacf18dae1f641a1f5662f8d70b6a346', 0, '2020-08-12 11:20:01'),
('91fd453cc1e4dd42f17eaee46d4aaa7bbd24a7c65602a2971d786d353bdd2af68b7552fb629e390c', 'f057694fdd0ea6a8351e9512e73cb2485e3760033f049bd24edf5dfef6a75677a6db97c25a8f33fb', 0, '2020-08-09 10:13:53'),
('9451796b0f367eaa755ccf179066432e62a49bf1729b47282e0e266531e44a951097a4d2a47bc01d', 'bc9e806c879535ccaedb7ca882903b792c298f68d93f21c449139189752bbc8f6a34f4ef49cb0592', 0, '2020-08-29 15:48:33'),
('948bb2e9f7276bcd3817c783784998068a81a16818118cb5f04a4264be889be3f2721677d484efa1', '4f36a00c037ec21bc8d0ce9c09d54baec968507f4345c71e75dc006177bd07de0fb0b5646bb0179c', 0, '2020-08-29 07:08:31'),
('952a9ada42d22f8297b93dacfa08ca7d1fc2e36d258b8650f390befc8090d1961a5ec64767821cec', '8bdadaaa9d5b503de4ad35d979b36939e6d4e569e9b5b2b8bc86172780979c59ae247923250a184f', 0, '2020-09-06 07:57:35'),
('98b0c0c75e140eb75c1d5a318fd41d13fb4db78ad19d8c9b37529abb6aa82176ec368969467548b9', 'b3e7c7c8d98e3502b9cb01d2e7d0e8d5971f5647718ce07c88aea10e9201b1de2362867901e2c84f', 0, '2020-08-29 06:56:58'),
('98fd88d344cdcd1a30ef3b5afc8b874ebf3e646da17d547425d241021eee35f1af232178a25a9034', 'f6de51f87da388072ca1436477d19a6fa12385f76110ab96d314ce5e4c9525f24cc03d707134a168', 0, '2020-08-29 07:13:51'),
('9946dd1406baca117e1661ceb4b3efcd9a7f838fdbb5d7d7c66fbe949827fc5b87be7fbaeaadb4bd', '74a1b3e11da82a1fa18d907966c4f23e8b836b11a18670f013a9b556f9489969da0c4eb8fc9a6a9f', 0, '2020-08-29 07:39:45'),
('9953e98f61349a7d2e0e36ce86678229f4203cb44b3a0e935da9414f5b4ecaafccd6f3da6ef891ce', '0d852c99ba80137c326cb87872b5b3984407ef2ea35d8fa5fb84c4ad48f990c288b3e861a52248dd', 0, '2020-08-12 12:48:23'),
('99a74776c03bbc871f147ed7eed1004c3c1ab2b5660db7c5b23a3fa2ff5fc4346bac911fde2e1a15', 'eede23627f898090aee751593200f0359f44fb5d3fd49e70cfa340805fd69711a7f507e2bb5eb17e', 0, '2020-08-29 07:10:57'),
('99f0bf511b633aa1f3deb5aa79a5221b549874563164309f4d03a17d3b249ad6ae3662e4a3879338', 'f0b28c8566519d5304fcfc0ce51fbe5856650beb6e8ad2c07ad0c99dcbc55ecb8333e54b3842cddd', 0, '2020-08-12 09:19:49'),
('9bcfcad262453b970ffa75ccc88640d2b4408f1bd3df80813ba7dfd949e01a632be326506c16c98a', '09f31d9230b51b79924108c991cb5ed5f26ff28c19692c707e385bd470f4b28ca120562288027562', 0, '2020-08-29 12:18:58'),
('9c216c6c0eb2a506ac79badf642c7ff3c75f757a746e8a09d656a6da00bbebe8c5cc4e473eaee4bd', '86b81a0546d5e410bedf7b9aa3a3c227b30e868689ce06693233c122f49f1916e99594cb49ba84ac', 0, '2020-08-29 07:12:05'),
('9d0790ab181838c1c87d74fa3dfcfa303a7c8576c6f94ac8ec08e704db613512749f65c9dc7c456d', '16ddb7634d7ca85c1f8441ef1ec9a846b44a1aad1c19f4a1ba56d4bf2e81496a9eb384623f5f159d', 0, '2020-08-29 06:57:35'),
('9dff00569baf7d77170e39d7aa80a57d03069f0f5650b4b815a523b1006c8f735e9cb09aac00cfd1', 'e5fbb61f6159f3050326c72c5e1478979334cc7fbfc0481f3c726185dec06d23f810b247536a3e3b', 0, '2020-08-12 12:45:00'),
('9e85fa540319abdb545e5c6d9ef17793f1d518366fd02d41c63264c00402bf24135f5ab511797683', '4844f078e00e4fba67ad1a7775e59686c6a7b2e3525ba3bb23d4cc683711da2acf0a64af10c4619e', 0, '2020-08-12 12:52:25'),
('a0cf93a576399a23e5f0d701bf5c1b1f90a610e4c84cc4b297c63ff34ac190ea2eedebf8b7663b02', '3f00f6281345950b42093f2b5460d337ea20348726cdb28703a640765324ad71ca622cc12e2ad05e', 0, '2020-08-30 11:30:06'),
('a0fcbc8412d8e90ef6f261f3a1b45d727c5fde67a89147b19a253a0ed5cd3d91cd03a6e9335f90d7', '52d3f8287d6a3af6a0ce2e38fc35a3d8bbeaeb5e960f6b51b8ea1a9ab50153cb298382f85a9eaeff', 0, '2020-09-06 07:59:21'),
('a1401576badde572f48309fce3b6937ea370a058b4647a17f0a4c9e64b171c8e54867061b77a9081', 'b7ff8d7590ff352e4dfd01d0e0270c70c0f858f8772f8583fdb83bbd96b457aacaa516359981f1ae', 0, '2020-08-12 09:19:15'),
('a1ecc8acd840bb521cd7dd3326baa6687a8881f01b52d8418500b573bc4c1addcd49e185fc8eeffe', 'badeda3edcf8b265541a5feea1c106fa362683f6229ddf53598851bdc4fd4443ecf978212757336d', 0, '2020-08-29 07:39:28'),
('a2e840ba0eed3543f0b349d69c7a8ee20ae3e8a5d953c11610e537d173f7314c2a904115a79e8870', 'd4ba5bb7baaa438b49eee2810c9e1bbc4216938349fc3d88ac1661565b7d628e67dd887885a98e8e', 0, '2020-08-29 09:30:52'),
('a4a33d35790cc0e39eafae4d46e4566b8b4ba1ac57c334d00884a4cf54f642fac2348269d5b6c1fa', 'a341134870f1601dcef55b45983e6f852e232d638ae737cb06b4722677e502d8e2936ef42aa33ab0', 0, '2020-08-12 10:22:05'),
('a781b44aeffdda482494d0966a97ce458ffaae852a14fd2f329039190e5b1ab3940c3525d8cc57c6', 'ad950610b2c6cd095495e1a2ea6336ff6d22db5a763fca52d06f5fbe5c868e9d904dd2b8578defdb', 0, '2020-08-30 11:21:08'),
('a86a7131716c14bcf2b46eeb8e83d916768fa3abc7e79c1759beec4bc843f97731eadbb2477b3c57', 'f531f27e6ad9603425ca7a6cf3c515c904c945116c9748eb8193ba5bfde3c5189acb6d32c7ee4647', 0, '2020-08-14 07:09:38'),
('aa5aad737e7f367a10ebfec5afc952e0190898cd39a8147bc01d778fbed457a40de30ea8f4088244', 'cfb34cb36f6f631ffd9c3e516a8ebc60096c190ee55fbfdf2b5d924e3c21da978e3308c5b4111bef', 0, '2020-08-22 11:27:39'),
('ac8b9074a400efe3c5e74e0a58df46fba7d4e72761931fe75d7884553d65485363d6bf9914f36ce0', '480b27cb67d33e269150e2224660078138532745cf5b8193ef6ac978995723b81b0216f3928ad2bd', 0, '2020-08-12 11:27:41'),
('b07d7323ad3f132e3e703a139dfd5f2eaf2879f67ca5f47c40ff59c979affdaaab1fbb4343cf12fe', '7db9af177818af48f3a22ed8434cd68798e3409a5e5f9f3b4177ff0b1e4245f61a5c8b9337d3df65', 0, '2020-08-29 12:23:50'),
('b2f599decc3382e44acb2c9a0476d1fb3e25f40c36ebf883ce8830fefc9c9c51865f794ad5d95a1a', '9b01fb2f735b3b9e2eb48f4d043fcbcbbd3fb6b5310d4fa8bbd4919333a6217b6b8290ddc7316cd4', 0, '2020-08-12 09:34:44'),
('b3af53a1f125c0e51bb3925a5328f8d7c0ed4c7693ec7219267f5cbb40f8f04b32fb4079708dcb94', 'efefd9ed1ada0405598207d07d6a95786e639115737c2863b11539dbf1230eca5de082d9fb57cc0a', 0, '2020-09-03 09:37:37'),
('b51b7491bffc51925954b32bf023d22576c867ca725034689f619584775e5c4cc588d9560bd75d9a', '0e36d5b874493497d97d037977fee7fe7ce07cc1eb30ff24d3ab84ab56ddf21bde18a4e9b5547a06', 0, '2020-08-29 07:11:14'),
('b539b5b42e324a08f2547fed2fb593e8b24d43344f25a3d39143d9e615cd37c153c389ce1d41eb20', '2e5fad220da929d0905e8170b3bbd48c6dc7d31ab9dd46da2e867784db2ec2eb3fa416ea127deece', 0, '2020-08-29 07:15:33'),
('b5a4ec26963f23672fb49e96973098a57e0fa08226fef73e15e6f7bacb6b253bcc788be6c7446ad9', '229bb945193daea617cc7c1ccd81a9ccbe06ec3b2519c5693d2945f5fa65d76e2ac8313c357d79b4', 0, '2020-08-09 10:40:31'),
('bb66bac8c8225e1e6c3182ca63099004642b6d38ddf91a119d29cea56f7e047de3319103f3d23611', '965b14997efeb6a5d881025ae24fb0ef7894ac33a396a1bfa20fd2c61a3b9411c272ef796bab8775', 0, '2020-08-12 09:26:57'),
('bb8d497f307de2e99182e78675462771b59fa48a9cfea535d3b906d1d8b4f7f72a76c5616ab0f07b', '3f7ab0a46b705257af79bcbb8db043da2a2658123a684a2245f6af6ace32eecfbd08226abbd4276f', 0, '2020-08-30 13:25:42'),
('bdd2b072e355bfaae89735c9ad9a4640ed56d0f1fb49bc36c96250fe1a2c6f11928c8c32836c7caf', '97b5fbf63c0783c0c6ed98cf3914a21b1ccbae682afcf9e8ed2077d7ad2ad4235e5e5b25658d939c', 0, '2020-08-30 13:42:05'),
('bdf64b8f7f0f81b4b11e33b4ac7e0dbef853b900fdb429707cc42b0f0323fd5fc1cdf4aca5176809', 'b272663a9f6dadbdda3a51f6330b0ed7bb09a48b3a0c54a6790cd31e565920f9385b43861665860c', 0, '2020-08-29 07:14:49'),
('c0333509f1f7526396b61d4ecf560ab094886542b6b5fe126ac4511ea32cb91a73a5347bc596a075', '506c12de29ec91190d09d71c02791b3785452cb4aad8d18ea169781f2a325b126e69f0eb90b53dc7', 0, '2020-08-12 10:03:09'),
('c0cec20ee89d4c40e9cffda602f886d55bf87628fd419dc44e3529b666db5c4098359d4c4ce86c14', '66febe40eef476f3f2bda3118080d6fc9d0e05ed27087acdb98b0eb1ba71f647f99a1453cf7db30d', 0, '2020-08-08 12:05:36'),
('c1cb8d5c920ced122f41f20fe43cafaae621017249ecdaa186d3c4c87eb3b6ab1b608cb8d57f0932', '3da72acd4443b3dc33ec0901e5418685bef24035ab109c8d64a78e3f7a6e6f8b836974d63c270f64', 0, '2020-08-30 11:24:10'),
('c4a3f9adae5f17ff937140cf4fc52f97bfd7446f850d21f49caf0ae5314f569ec2024f61bd57a350', 'b6af1f0d4532767378335fb62a1a8a677ac99856313f0cd2a74a1d612c9665d74ae83f02943dee7f', 0, '2020-08-29 08:02:43'),
('c5b473676621c95241eb0621dfab44664b368746d9f1d7b2ef496ab2c7de13fccf6b8f1faf7d9fe0', 'a7930120c481c37e3252ab1bd58dc29390db2b8feb3e10858a0b5bfc6b572a721d6cad9986e2b60c', 0, '2020-08-13 05:42:57'),
('c655c82a6ceb23eb79db020eabf2303a668debc6d936d7d9fa21c899743530ba0faa91f0d7be2df5', 'dea75ec11eb5ae3a68b8977a7b223d3981f2995f8714bc84c8fd1692dafade4ef221b8f14fbe94da', 0, '2020-08-12 10:39:34'),
('c6f80bf673237ee2706c8c178544648f081d528ba42c7a13f99b2b60bb17736e79a46ca70779d42d', 'f1f5e9b221530808bb5cb886f51c95c1bddc4a1eb29fcf20dcc771e7059c76cc964947f72909d617', 0, '2020-08-14 05:04:17'),
('c79160532c4462dc7f3aa828ec63d2ca59aa2b478146733e4b745dcc9aa3caee0c6d3ead8fd6200c', '18360516c954bba7e639d8be71c10a57d83b7acf162d05d17c2758ad2fe0783a44457633325392d8', 0, '2020-08-12 09:53:32'),
('c7bf15c6a3f3e72408e8433d1611e2dbe21fc0ae896f0762e778b03ad4677926ff7550baf2dde7c5', '1cc36e4aeba0c9fd80b13794ff4e4c6742c44718bcf3eb02d7121fbfdddbc47fa31d873b377c5d1a', 0, '2020-08-30 05:08:32'),
('c8f0b93d686213f4611f46a544e4275336fed41dacb45a25d47dc6fa51de91beb0034299ff1bba09', 'f4f2213b1f527ed275795483e99814e2e6a74d0d20def118dcccf5f7dc083704502dee3448f23a90', 0, '2020-08-20 05:38:48'),
('ca3ff2ea02932ca9d2784d9727182fe9b449d488bc11311661b80b8c98a5bf878f4def6f702071f5', '9a3d33249a5075f3c62d99ab40a9af67fc18b849d938f2c11119b1a11c4c20bf65bbfb5e33b7507c', 0, '2020-08-29 07:07:12'),
('cacbcbe7707b175b243e8fd8086294ef80ff9aa69115ea2ab91c2fc3acc7a816039bd721c28888bb', '1a7c1a15d62b265b99dc86270b173e85ca03fc6294beb03d9de5b62808b94c05bbd3fcbd2c5c7bae', 0, '2020-08-29 07:41:24'),
('ccfeda24950bf23001128691b048b0ceb43b5812c3f0ce367b382e9be9a4d266495c17906c479597', '97ad12e9b1d8fbc6427686cf280e8bd99eb741180645604041c47117bd206c22c201c6d76e85d59c', 0, '2020-08-30 11:19:11'),
('cd2aa35a2ea449cd49bb3897b3076f1785c60e9fa4f340f6d34104d77d772ee059656d6072eff489', 'e3efc5925c6fbb3929e427c2586b06f7b7c08664b6b3a207c1277f3b57fa2b170f5ddd03e202b952', 0, '2020-08-09 10:43:17'),
('ce89afdee3b85c3f7ddcdff5d9076d442a3dd8ccd2a7574557caaf558bc65394bd7a294a1da675d9', '069f042b9182515145c9a1ffa1f5a60a64303052813434f6eb3f9e1b60f7c5ea3db2856eebc78e6e', 0, '2020-08-12 09:48:07'),
('cea6a9bbf91a3bc56ef6a9082920f203f2eaeb43f61920179796694c4000c901934312e599d20cd2', 'e73e37f38d86af85d55618804d4315c50553569dafb2cc69825ef7ca9c0a37acb296834a87876693', 0, '2020-08-29 05:59:05'),
('cf337947fdcf96de36f665f114753da73858adeecb760e637209c6874d98bdb644d4a0846067a589', 'f629344c41b3b7ff44bee0b276417784e6bad0c969a1d98e04d9a624db28a912280face9bd1db7b8', 0, '2020-08-12 11:17:49'),
('cfe47a5eb386c522a72b4e25570e55920222e80e98de1018438ab0cac281d3f43dd7c04ec82bbcf3', 'caefae56318a95ebb88e874034da9eb02929f43fa4d51faf495819c66500d0c59850d72b543767c5', 0, '2020-08-26 07:21:04'),
('d07bc758b02b055b0f370eb94aa737372a13da2fca8c62eaeba38180b6a3231bf66fd3f26235a750', 'ed4288e7c45b7678f32caca67602f91c8ea6da40a8abf32e81624566a4fed5e19ec7a34c3fe30085', 0, '2020-08-30 04:47:10'),
('d2088aaca2ae0f37a0318c91632076f7e610c997263d321df3f0f81397310584a7a85d537ce2fe5e', 'ee9763fc2742a0223f1af76a6af9c58c762fc4c6bec9aaf9c900515b78654fa0483b9ad7f6340be3', 0, '2020-09-06 07:50:09'),
('d294f5ce44643f6a1682f6e6318f920ab5744bb1388caeed139a8200adb6169f0507dea271f16ec6', '33d432dfb42a825db0b2c512565333cbfb51150881142deb4f4e9898f9c5199572a1246ded651ae8', 0, '2020-08-29 11:23:47'),
('d29aa92b9d1db8930287d3581e8cc7f0191fbef08fca5f45d56378f7ab216d162e986a9b388c735e', '06bb30a06c550abbe2b36f5120a3c39369f94d86599911e33476691dac5bd2753dcb9e04d47bef70', 0, '2020-08-12 09:32:40'),
('d4119aa76b6a136db9601c652bd8560ecfc773cc1fbd60a92326cbaa6d8a1e160204ee003ee7c46b', 'ec0f64871ea2f2000b0694a97cd295b501701f64d6d302c3e03fe1eec6281e9f6f1589cb70eb9bf1', 0, '2020-08-29 12:24:19'),
('d7822053453b4abdbfaf0ff446acc75dfac020f113112fd856ef7e3420735a7d3b3da9e838c3736f', 'e2c1bed2c8feacaee153076f6e0ab93b125c2edf3d52bd6ec4fae65f94e7c84936b924440c2a13ff', 0, '2020-08-29 07:11:22'),
('d78b21552a250221ab86e25bb6904ae67913c33f6fa3e561d626c61532d49b54fc334b914e484511', '192d8d923442031165788e4c53d4a75ba42c6bdd5372a9684e6ed7c64f445fe67a6f98bc5d73bbd7', 0, '2020-08-29 11:10:43'),
('dd5d2ffc9d68edffcd0c4c21c625eb43d739396d2bf2614f33c99d0174537045852bef554b2978e4', '1511f057cd53081bd7879ef473e35dc32f68726cc2c3237cade69dd215181107935a6edb4b3965a2', 0, '2020-08-12 09:44:56'),
('dd6151ce27fc94f7e02f76e30c4539caa4fbb400fcaf75ab91f8afe795e137f094f6bb8eafc9c0de', '647717a0a85fe8095205a663a8a280cde0fd2db099fec20a8ab3f0f7733f5da6f57f84a5f6a51312', 0, '2020-08-29 07:10:42'),
('dd749c5bbc00eeb8fdb76db0598f65f8df906f279840564d0d256eedb87fcc76ad9d9e0a113f8233', '2cd014d6786ebafada3904f157fbff20b37248af4d5a2385165552699f8a511483b776ae2d898f0f', 0, '2020-08-30 04:47:53'),
('e202197f37efe20e7ddedbc379dee3702244a5275b779d405be15cc53a41d5b894ef69f579bdcf56', '05de95bdfe6f7367d1c8ae665b9073fe462d22804b7faaa1a1e1c7b68ad61783fa60b4270e1c5b1e', 0, '2020-08-30 11:18:59'),
('e2f0622108499010037632d3e8991d42d60ff23f10012486c2de287475d1095838ef4ebeedb9b0ff', 'dd2d413881b5929ab4635b1140af7acae46a40155941f6b10b643cd7a10a399d446c2c9df6ffce47', 0, '2020-08-29 12:11:36'),
('e5b1f52643f06e9607bc805006973b932f6694e5e8efed9ff224d8089c9ee1e48f58930fbc6fe8ef', '5b85f148aef0540cd939e93cd22df2ca8fb6d50effc2ed25037f354534001d21de9006dd31b556f8', 0, '2020-08-12 12:56:30'),
('e6136a08318986d87fc69d939020d83a7dc19460c35d537a8e11b72c237e67eac1d6f35d51829130', '7d826a664637b2b95ac000413a0041bf9f80afe31d0b6b1c902d8275f6cb29e8661f00c52748cbf7', 0, '2020-08-29 12:04:50'),
('e63d64df01b753a56851d411e23f329900fcbd33a1637327a17ca8b53db50bc4caff533ce53ded68', 'bda1acc39a9121e4406a637a8c0fe75aaa1f160cd9de7767f9e26586facf1fd91a77ef43c12267f3', 0, '2020-08-12 09:48:14'),
('e651839a3d7d1a7f7c0852accbb11222d8022cf4ae4e6c1d97fbcb1d7aa96e76fc0e09e6b2b4c450', 'cf62012dff70a8f4cd27a7458b558fba85185fcaea2c8e00f7dc94fb5acff7b6ba9d90229b716d18', 0, '2020-08-12 11:24:13'),
('e8773e25625cdc4d84ad161f17708d82eb4ebbb11a91e98bc03fe56da70379a380b7755493b95a15', 'd9f1ad1927eef76a6cc5bd1aca4af921571d63e6050cfe6776e42011e015f37e53ad818cfdc78eba', 0, '2020-08-29 11:20:53'),
('ea68fd7c62faecc5667a19b5261af40acc42caa0468f6aded0a83c7db1d29b824e9c9c0e26880c3c', '1498f8471e2aa906adf6098aba6ee8410c54ae7137ff8296038ff4cdd3bbbaaf85b8d8373c9dd226', 0, '2020-08-29 07:59:58'),
('ecfcf5a2b8a2d21e3387e428f77cfe9382b8c8ad1cd718fa56894d839afe3b177597bbdbb4ab9396', 'd8b34e1dd912f039b1d1ce4475ec2a11ae93a3a625d23a8290373570d30e2aea129eeedeb513a1a3', 0, '2020-08-29 09:07:50'),
('eefda5f13db6b2995843d7e688ce2b41a2556695a156545b5d14d18d3c7429ea1c2129364f8482c3', '91ac383836c6e9a11667e3043ed51641be694c327eb1b287b82ad07b39e3a0e626125786cd7a9605', 0, '2020-08-09 10:54:22'),
('f1a62ae6978da91598ae10ee72ebbe5ac963ea669b51b2da16bad0ee0c4724aa9c6cc9dcedc69d84', '6bcdc64afef24946936d0414e8116f8d1d84f9343a780c6de850079a5a4d877a7cc3ff4009148c07', 0, '2020-08-29 12:15:34'),
('f1cce660744bfd39bfd040727a30dfe4d392a4dde38dfb22fde36b827044aea39078c3e1490ea2cb', '348cf751b9da01fa4a75afd319584c5d11572abe026dc9069e7c4dc75ea47f1b8fa6708a3d27c913', 0, '2020-08-22 05:27:44'),
('f54d7d2a4531fe6d7a153ca071c69ea8bda361bcc9c7ea3ee57f5a8fb017450831ce12b7c718c601', 'bdd2e2f5e0e175c6f2466835dd5792403e445ef4ba4acb225e1f4fd1e8d394eb1eaaff32777f5b92', 0, '2020-08-12 09:40:11'),
('f6cf5e3a010930e5ce12c38a45b44b6d6541b8d57452e79bdfba4ae881534b9b3a22ce54fa186ea8', 'e04272eb119f623e4f3fb01f420be685af77e5168084ae46708201ba86d93d339897ee3bda54d583', 0, '2020-08-29 07:11:58'),
('f80ead6ba9bafa5fc6d3e28c347ff617d2e8cd7e96cbe0a619dfb42d479719fb3bf29c72a62787cc', '19ff9cd238aed517e289beebfabcc1c21b9bd86d93cc600fce0c8f8bcd208e77125e215f57c02c99', 0, '2020-08-29 07:09:40'),
('f86a90dd4de1bf2bea923358c65846bb30c8e9f6288b39dfcf742cb95d2dac062e233a5461d274d8', '50d2dac999dcd7218e8cbd2f5f0ac0c021ca1c30a501bc35cfe36d6d3da567504e9867445b9c52ff', 0, '2020-08-12 09:45:24'),
('fa265a8f5bbafcef7556af436f7b120b16989e515e91b82a4fdfda9edbaef4b5b4113b5b26563d67', '3fb0bfffe3ede8a2888384c7e7879236331b967235068026017a2c559bf1798a35e462db57312442', 0, '2020-08-29 07:08:43'),
('faa3b36767a58cf0658e0817166480592d9a4dfee36552b9670aa02640b1117145c3dd9226c5375e', '69119dabc1ed96226b1afe13c5b1f81418d2e4f1b274b7d7eadd0455342dae57ace17d27bae20bea', 0, '2020-08-09 07:38:13'),
('fd291c6383370ebdc2ebae392e992769aa46d091d3029ed9fa961d03744703aab8f3b3d37902bd14', '85682843df5ee069d242d4630b98d9dffb8ea0046ae7e77b0d94a00eb89df1fed1fef2613f69cb8f', 0, '2020-09-06 07:36:39'),
('fdb7e7ecb4c637b70fc47e611fefa0e1b0f6517124cedefb5768f7dcc5f052ddfba3eb89d36323f4', 'a7570d2ce23bbc8d8509eb4fd02ddb3d4b728037bfe78399de1fc60bc30ed16e8911a615bd4c8a50', 0, '2020-08-30 08:25:07');

-- --------------------------------------------------------

--
-- Table structure for table `once_booking_alternate_dates`
--

CREATE TABLE `once_booking_alternate_dates` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `once_booking_alternate_dates`
--

INSERT INTO `once_booking_alternate_dates` (`id`, `uuid`, `booking_id`, `booking_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, '58903714-535d-4e45-8d73-b39142e04241', 92, '2020-08-08', '2020-08-07 23:46:13', '2020-08-07 23:46:13', NULL),
(3, '69d94a68-aaf6-4aad-93bc-d9459277d9f4', 92, '2020-08-15', '2020-08-07 23:46:13', '2020-08-07 23:46:13', NULL),
(4, '0301fd62-cae5-40e9-969b-a10c6e0f9a3d', 92, '2020-08-22', '2020-08-07 23:46:13', '2020-08-07 23:46:13', NULL),
(5, '9c84f174-8516-4293-a9a2-c18a7649a2e5', 92, '2020-08-29', '2020-08-07 23:46:13', '2020-08-07 23:46:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `stripe_charge_status` enum('pending','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge_completion_datetime` datetime NOT NULL,
  `payment_descriptor` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `payout_status` enum('processing','sent','returned','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payout_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `uuid`, `booking_id`, `user_id`, `stripe_charge_status`, `charge_completion_datetime`, `payment_descriptor`, `total_amount`, `payout_status`, `payout_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 1, 'pending', '2020-05-26 09:31:22', 'test pyment', 100000.00, 'processing', '2020-05-26', '2020-05-29 06:52:23', '2020-05-29 07:40:35', '2020-05-29 07:40:35'),
(2, NULL, 2, 1, 'pending', '2020-05-26 09:31:22', 'test pyment', 100000.00, 'processing', '2020-05-26', '2020-05-29 06:55:15', '2020-05-29 07:59:12', '2020-05-29 07:59:12'),
(3, NULL, 3, 1, 'pending', '2020-05-26 09:31:22', 'test pyment', 100000.00, 'processing', '2020-05-26', '2020-05-29 08:03:28', '2020-05-29 08:03:56', '2020-05-29 08:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `payment_acivities`
--

CREATE TABLE `payment_acivities` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_acivity_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_connected_account_id` int(11) NOT NULL,
  `user_agent` int(11) NOT NULL,
  `user_ip` int(11) NOT NULL,
  `device` int(11) NOT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_activity_logs`
--

CREATE TABLE `payment_activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_agent` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` int(11) NOT NULL,
  `country` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_log` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` int(10) UNSIGNED NOT NULL,
  `payout_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_datetime` datetime NOT NULL,
  `payout_amount` double NOT NULL,
  `provider_user_id` int(11) UNSIGNED NOT NULL,
  `payout_status` enum('processing','sent','returned','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_name` enum('just once','weekly','beweekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_type` enum('just once','weekly','beweekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `uuid`, `plan_name`, `plan_type`, `features`, `discount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'a9574ab6-2e68-4d96-99a1-4ac872337359', 'beweekly', 'just once', 'beweekly', 15.80, '2020-05-26 13:50:08', '2020-06-22 03:41:17', NULL),
(2, 'bb299047-c107-45eb-b783-0a4c017fb0bb', 'beweekly', 'just once', 'hellosssssssssssssss', 15.80, '2020-05-26 14:14:04', '2020-06-22 03:31:05', NULL),
(3, 'bb299047-c107-45eb-b783-0a4c017fb0bd', 'just once', 'just once', 'just once', 0.00, NULL, NULL, NULL),
(4, 'bb299047-c107-45eb-b783-0a4c017fb0yy', 'monthly', 'just once', 'monthly', 7.00, NULL, NULL, NULL),
(5, '3741bd65-e93b-471d-996c-cffec0a0b1fe', 'beweekly', 'just once', 'hellosssssssssssssss', 15.80, '2020-06-22 03:04:29', '2020-06-22 03:40:46', NULL),
(6, '93d1271e-abc9-45c0-9657-54d9fd0246ea', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:06:32', '2020-06-22 03:06:32', NULL),
(7, '7e354f3c-2bb9-49bf-b98e-c77f986f0541', 'beweekly', 'just once', 'hellosssssssssssssss', 15.80, '2020-06-22 03:07:06', '2020-06-22 03:42:22', NULL),
(8, '4689b66e-ba0d-49df-b68b-09e1fd56908e', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:07:36', '2020-06-22 03:07:36', NULL),
(9, '6130f073-2729-4f1f-a42c-71c5dc1eefeb', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:07:50', '2020-06-22 03:07:50', NULL),
(10, 'fa537ff1-c367-4330-9b02-c76220fdfe8b', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:08:28', '2020-06-22 03:08:28', NULL),
(11, '795d214e-917f-440b-aefb-6de109644df0', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:09:45', '2020-06-22 03:09:45', NULL),
(12, '6c874cc1-ff96-4d58-8d19-d374b17514c3', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:13:34', '2020-06-22 03:13:34', NULL),
(13, '80ddbfca-895f-4832-bb6d-811d5647cf5a', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:15:12', '2020-06-22 03:15:12', NULL),
(14, '2cdb1459-8c92-4072-ab6a-72bf85d06848', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:15:36', '2020-06-22 03:15:36', NULL),
(15, '6248083c-72f1-46b2-afaa-9d2b98c1f589', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:16:04', '2020-06-22 03:16:04', NULL),
(16, 'ad40e092-68df-4a69-bc6f-df444b7a9930', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:20:49', '2020-06-22 03:20:49', NULL),
(17, '8dc5ea46-9a54-43e3-a0f6-f8a8ce940b86', 'beweekly', 'just once', 'hellosssssssssssssss', 15.80, '2020-06-22 03:22:21', '2020-06-22 03:33:59', NULL),
(18, '0f2ace79-45cf-450c-89ec-f1f1ef6bca6d', 'beweekly', 'just once', 'hello', 15.80, '2020-06-22 03:29:39', '2020-06-22 03:29:39', NULL),
(19, '5d8585a3-724e-48d9-941a-55a38fdffd76', 'beweekly', 'just once', 'hellosssssssssssssss', 15.80, '2020-06-22 03:29:46', '2020-06-22 03:29:46', NULL),
(20, '710911d4-7b00-4394-8287-b87c7433df37', 'beweekly', 'just once', 'hellosssssssssssssss', 15.80, '2020-06-22 03:30:19', '2020-06-22 03:30:19', NULL),
(21, '6f4c3681-8f32-4756-a025-2118102408e4', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:43:08', '2020-06-22 03:43:08', NULL),
(22, '07d0c908-5de2-484c-a38b-112055cda7de', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:43:27', '2020-06-22 03:43:27', NULL),
(23, 'dbea1448-6e37-43fa-aa9e-9365bc0ec51f', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:43:47', '2020-06-22 03:43:47', NULL),
(24, 'ff6233ff-810b-4428-b5db-e0ecb327e4db', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:44:21', '2020-06-22 03:44:21', NULL),
(25, 'c4541a14-07f9-4607-a8e5-c0e3e5dd07f9', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 03:44:55', '2020-06-22 03:44:55', NULL),
(26, 'bab08b7e-a103-4122-998c-eb808eb7755e', 'weekly', 'just once', 'weekly', 5.20, '2020-06-22 04:47:39', '2020-06-22 04:47:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `postcodes`
--

CREATE TABLE `postcodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` int(11) NOT NULL,
  `suburb` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `postcodes`
--

INSERT INTO `postcodes` (`id`, `uuid`, `postcode`, `suburb`, `state`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 364290, 'test', 'ghj', '2020-05-26 14:45:12', '2020-05-26 14:55:10', NULL),
(2, NULL, 364291, 'help', 'abc', '2020-05-26 14:47:28', '2020-05-26 14:54:41', NULL),
(3, NULL, 364292, 'testing', 'xyz', '2020-05-26 14:51:12', '2020-05-26 14:51:12', NULL),
(4, NULL, 380089, 'helping', 'jkl', '2020-05-26 14:56:28', '2020-05-26 14:56:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promocodes`
--

INSERT INTO `promocodes` (`id`, `uuid`, `name`, `discount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'a9574ab6-2e68-4d96-99a1-4ac872337359', 'FREE5', 5.00, '2020-05-26 14:25:51', '2020-07-15 00:19:32', NULL),
(2, NULL, 'rakesh', 100.00, '2020-05-26 14:30:41', '2020-05-26 14:34:34', NULL),
(3, '823baeea-eb0f-47d6-ac9f-ee5d2b38f614', 'rakeshtestss', 500.00, '2020-06-23 01:00:48', '2020-06-23 01:10:06', NULL),
(4, 'b12073e1-117f-45c8-b0f0-e7e01ec48077', 'rakesh', 100.00, '2020-06-23 01:01:21', '2020-06-23 01:10:59', '2020-06-23 01:10:59'),
(5, '760fa1e8-8737-4549-89a9-18e85c5350f2', 'rakesh', 100.00, '2020-06-23 01:02:41', '2020-06-23 01:02:41', NULL),
(6, '0d2d322a-4249-4a4d-b111-995e1fd90412', 'rakeshtest', 500.00, '2020-06-23 01:05:04', '2020-06-23 01:05:04', NULL),
(7, '4a495413-d7a8-4aa3-99ca-41b1426a5f2c', 'rakeshtestss', 5000.00, '2020-06-23 01:05:23', '2020-06-27 02:57:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(10) UNSIGNED NOT NULL,
  `promotion_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `discount_type` enum('winter','summar','monsson','festival') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_metadata`
--

CREATE TABLE `provider_metadata` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_metadata_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `skills` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_agency` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_bsb` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` int(11) NOT NULL,
  `stripe_connected_account_id` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_fee_percentage` double(8,2) NOT NULL,
  `verify` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_metadata`
--

INSERT INTO `provider_metadata` (`id`, `provider_metadata_uuid`, `provider_id`, `skills`, `is_agency`, `bank_account_name`, `bank_bsb`, `bank_account_number`, `stripe_connected_account_id`, `service_fee_percentage`, `verify`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '123145', 1, 'test', '1', 'test', 'test', 123456789, 'gwaghedwq', 10.00, 1, NULL, NULL, NULL),
(2, '3151', 2, 'home', '1', 'home', 'home', 1231651, 'dfd4d56165', 20.00, 1, NULL, NULL, NULL),
(3, '45456', 3, 'dmeo', '1', 'demo', 'demo', 531354, 'dfnsh464', 25.00, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provider_portfolios`
--

CREATE TABLE `provider_portfolios` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_portfolio_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_postcode_maps`
--

CREATE TABLE `provider_postcode_maps` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `postcode_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `provider_postcode_maps`
--

INSERT INTO `provider_postcode_maps` (`id`, `uuid`, `provider_id`, `postcode_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '60c368d8-29d3-43e9-878a-8a7cec0cfbfd', 1, 1, '2020-07-13 12:08:44', '2020-07-13 12:08:44', NULL),
(2, 'e3f66263-9d13-4353-a392-6ae060eaaaef', 2, 1, '2020-07-13 12:10:11', '2020-07-13 12:10:11', NULL),
(3, 'aab90c56-d79a-41c7-a01d-c302c11029df', 3, 1, '2020-07-14 04:57:06', '2020-07-14 04:57:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provider_service_maps`
--

CREATE TABLE `provider_service_maps` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_service_map_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL,
  `experience_in_months` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_offering_description` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('billingrateperhour','billingrateonetime') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_service_maps`
--

INSERT INTO `provider_service_maps` (`id`, `provider_service_map_uuid`, `service_id`, `provider_id`, `status`, `experience_in_months`, `service_offering_description`, `type`, `amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 1, 1, '5', 'test', 'billingrateperhour', 5000.00, NULL, NULL, NULL),
(2, NULL, 2, 2, 1, '5', 'demo', 'billingrateperhour', 3000.00, NULL, NULL, NULL),
(3, NULL, 2, 3, 1, '5', '', 'billingrateperhour', 1000.00, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provider_skills`
--

CREATE TABLE `provider_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `skill_name` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_skills`
--

INSERT INTO `provider_skills` (`id`, `uuid`, `user_id`, `skill_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '9307c6f8-59e7-4aed-a45c-be17ae3c8208', 38, 'java', '2020-07-24 00:07:13', '2020-07-24 00:39:19', '2020-07-24 00:39:19'),
(2, 'e846b2d9-3f8b-44d5-9e1c-9d6b2117bf96', 38, 'php', '2020-07-24 00:40:00', '2020-07-24 00:40:00', NULL),
(3, '8f391b12-8fd5-4ea5-9ba8-e50838c6db00', NULL, 'java', '2020-07-24 01:05:06', '2020-07-24 01:05:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provider_working_hours`
--

CREATE TABLE `provider_working_hours` (
  `id` int(11) NOT NULL,
  `uuid` varchar(32) NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `working_days` varchar(256) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `provider_working_hours`
--

INSERT INTO `provider_working_hours` (`id`, `uuid`, `provider_id`, `working_days`, `start_time`, `end_time`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '4551', 1, 'mon,tue,wed,thu,fri,sat,sun', '10:00:00', '20:00:00', '2020-07-11 07:05:37', '2020-07-22 19:41:17', NULL),
(3, '123132', 2, 'mon,tue,wed,thu,fri,sat', '10:00:00', '20:00:00', '2020-07-11 07:35:14', '2020-07-22 19:41:34', NULL),
(5, '56465', 3, 'mon,tue,wed,thu', '10:00:00', '13:00:00', '2020-07-11 07:35:59', '2020-07-11 07:56:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reported_incidents`
--

CREATE TABLE `reported_incidents` (
  `id` int(10) UNSIGNED NOT NULL,
  `reported_incidents_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userid` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `reported_by` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('user','provider','fraud','incident') COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usernotes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('resolved','open','closed','inprogress') COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminnote` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reported_incidents`
--

INSERT INTO `reported_incidents` (`id`, `reported_incidents_uuid`, `userid`, `booking_id`, `reported_by`, `type`, `comments`, `usernotes`, `status`, `adminnote`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 1, 'hi', 'user', 'hi', 'hi', 'open', 'hjghjghj', '2020-06-03 06:11:45', '2020-06-03 06:15:05', NULL),
(2, NULL, 1, 1, 'test', 'user', 'test', 'test', 'open', 'test', '2020-06-03 06:12:01', '2020-06-03 06:12:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '2020-07-10 06:11:30', '2020-07-10 06:11:30'),
(2, 'provider', 'provider', '2020-07-10 06:11:30', '2020-07-10 06:11:30'),
(3, 'customer', 'customer', '2020-07-10 06:11:30', '2020-07-10 06:11:30'),
(4, 'public', 'public', '2020-07-14 09:02:21', '2020-07-14 09:02:21');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 10, '2020-07-10 00:50:42', '2020-07-10 00:50:42'),
(2, 2, 10, '2020-07-10 00:50:42', '2020-07-10 00:50:42'),
(3, 1, 10, '2020-07-10 00:50:42', '2020-07-10 00:50:42'),
(4, 3, 12, '2020-07-10 00:53:41', '2020-07-10 00:53:41'),
(5, 2, 12, '2020-07-10 00:53:41', '2020-07-10 00:53:41'),
(6, 1, 15, '2020-07-10 01:55:18', '2020-07-10 01:55:18'),
(7, 3, 15, '2020-07-10 01:55:18', '2020-07-10 01:55:18'),
(8, 2, 15, '2020-07-10 01:55:18', '2020-07-10 01:55:18'),
(9, 1, 16, '2020-07-10 02:07:37', '2020-07-10 02:07:37'),
(10, 1, 17, '2020-07-10 04:32:57', '2020-07-10 04:32:57'),
(11, 3, 17, '2020-07-10 04:32:57', '2020-07-10 04:32:57'),
(12, 3, 18, '2020-07-10 04:43:30', '2020-07-10 04:43:30'),
(13, 1, 19, '2020-07-10 05:10:15', '2020-07-10 05:10:15'),
(14, 1, 23, '2020-07-12 23:46:20', '2020-07-12 23:46:20'),
(15, 1, 24, '2020-07-12 23:46:51', '2020-07-12 23:46:51'),
(16, 1, 25, '2020-07-12 23:54:13', '2020-07-12 23:54:13'),
(17, 1, 26, '2020-07-12 23:56:26', '2020-07-12 23:56:26'),
(18, 1, 27, '2020-07-12 23:57:28', '2020-07-12 23:57:28'),
(19, 1, 28, '2020-07-13 00:03:34', '2020-07-13 00:03:34'),
(20, 1, 33, '2020-07-13 00:29:05', '2020-07-13 00:29:05'),
(21, 1, 34, '2020-07-13 00:30:10', '2020-07-13 00:30:10'),
(22, 1, 35, '2020-07-13 00:30:20', '2020-07-13 00:30:20'),
(23, 3, 36, '2020-07-13 00:56:46', '2020-07-13 00:56:46'),
(25, 1, 38, '2020-07-14 03:35:42', '2020-07-14 03:35:42'),
(26, 3, 38, '2020-07-14 03:35:42', '2020-07-14 03:35:42'),
(27, 2, 38, '2020-07-14 03:35:42', '2020-07-14 03:35:42'),
(28, 4, 38, '2020-07-14 03:35:42', '2020-07-14 03:35:42'),
(29, 1, 40, '2020-07-18 02:19:23', '2020-07-18 02:19:23'),
(30, 3, 40, '2020-07-18 02:19:23', '2020-07-18 02:19:23'),
(31, 2, 40, '2020-07-18 02:19:23', '2020-07-18 02:19:23'),
(32, 4, 40, '2020-07-18 02:19:23', '2020-07-18 02:19:23'),
(33, 3, 42, '2020-07-20 01:27:01', '2020-07-20 01:27:01'),
(34, 3, 43, '2020-07-31 09:09:13', '2020-07-31 09:09:13'),
(35, 3, 44, '2020-08-04 01:51:58', '2020-08-04 01:51:58'),
(36, 2, 1, NULL, NULL),
(37, 2, 2, NULL, NULL),
(38, 2, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default_service` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `service_type` enum('hourly','oneof') COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_cost` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `uuid`, `category_id`, `name`, `description`, `image`, `is_default_service`, `active`, `service_type`, `service_cost`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '45454', 1, 'home cleaning', 'home cleaning', '', 1, 1, 'hourly', 100.70, NULL, NULL, NULL),
(2, '45464', 1, 'home', 'home', '', 0, 1, 'hourly', 150.50, NULL, NULL, NULL),
(3, '2153251', 2, 'office cleaning', 'office cleaning', '', 1, 1, 'hourly', 200.00, NULL, NULL, NULL),
(4, '45f2c', 2, 'office', 'office', '', 0, 1, 'hourly', 120.00, NULL, NULL, NULL),
(5, '4552154', 3, 'garden cleaning', 'garden cleaning', '', 1, 1, 'hourly', 130.00, NULL, NULL, NULL),
(6, '42fvghfgd5', 3, 'garden', 'garden', '', 0, 1, 'hourly', 125.00, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `uuid`, `name`, `description`, `position`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'bb299047-c107-45eb-b783-0a4c017fb0bb', 'home cleaning', 'Home cleanng', '1', 1, '2020-05-26 15:39:54', '2020-05-26 15:43:22', NULL),
(2, 'a0c5cfe2-53f7-44d1-9b80-816a39485fss', 'office cleaning', 'office cleanng', '3', 1, '2020-05-26 15:46:58', '2020-05-26 15:46:58', NULL),
(3, 'a0c5cfe2-53f7-44d1-9b80-816a39485ffb', 'garden cleaning', 'Garden cleanning', '2', 1, '2020-06-22 08:54:30', '2020-06-22 09:39:34', NULL),
(4, '41020f0d-96ca-44dc-b769-2453dc341904', 'testsss', 'testting', '7', 0, '2020-06-22 08:59:28', '2020-06-22 09:40:04', '2020-06-22 09:40:04'),
(5, '09bf3fc2-5b9d-4e6f-bdc5-19b3178b0abe', 'testsss', 'testting', '6', 0, '2020-06-22 09:01:13', '2020-06-22 09:41:10', '2020-06-22 09:41:10'),
(6, '544872cf-193b-4f13-8049-9c4ece8da5f5', 'testsss', 'testting', '4', 0, '2020-06-22 09:01:36', '2020-06-22 09:01:36', NULL),
(7, '73f289b5-553a-4341-a5ee-478966d5621c', 'testsss', 'testting', '5', 1, '2020-06-22 09:01:55', '2020-06-22 09:01:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_questions`
--

CREATE TABLE `service_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `question_type` enum('home','office','room') COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_values` enum('text','radio','checkbox','list') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_questions`
--

INSERT INTO `service_questions` (`id`, `uuid`, `service_id`, `question_type`, `question_values`, `title`, `question`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 'home', 'list', 'test', 'test', 'test', '2020-05-26 16:24:35', '2020-05-26 16:28:40', NULL),
(2, NULL, 1, 'home', 'text', 'dcsadas', 'abc', 'asjkdkjkj', '2020-05-26 16:33:01', '2020-05-26 16:33:01', NULL),
(3, NULL, 1, 'home', 'text', 'dcsadas', 'abc', 'asjkdkjkj', '2020-05-26 16:33:46', '2020-05-26 16:33:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('payment','sms','email','firebase','ios','android') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `uuid`, `type`, `key`, `value`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'db70aa11-dc51-4d0e-add9-acc94c68c448', 'payment', 'stripe', '{\"strip_secret_key\":\"sk_test_tVIWBNGg2HuiCv7zfMr3Tiit\",\"strip_publishable_key\":\"pk_test_cse7ZUEUUJvrbtdGpt9ti9Qp\",\"commission\":\"100\"}', '2020-07-23 05:44:36', '2020-07-24 01:22:26', NULL),
(2, 'b8e7a5e7-bcee-42e7-81f7-191ca792d1b3', 'sms', 'sms', '{\"sms_provider\":\"Zipcom\",\"sms_api_key\":\"pk_test_cse7ZUEUUJvrbtdGpt9ti9Qp\",\"sms_host_api_endpoint\":\"sms_host_api_endpoint\"}', '2020-07-23 05:58:36', '2020-07-23 05:58:36', NULL),
(3, '0bccfc90-6594-437d-a684-43d43d498b51', 'email', 'email', '{\"smtp_host\":\"smtp.elasticemail.com\",\"smtp_port\":\"2525\",\"smtp_email\":\"dev@webminds.me\",\"smtp_password\":\"C451D56ACD619A2DFDC879062750CCAD6E6B\"}', '2020-07-23 06:11:16', '2020-07-23 06:11:16', NULL),
(4, 'bf2208c1-5ad0-4336-9e42-98f103e6d682', 'firebase', 'firebase', '{\"server_key\":\"server_key\"}', '2020-07-23 06:14:55', '2020-07-23 06:14:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_plan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_items`
--

CREATE TABLE `subscription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `support_ticket_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('billing','accounts','serviceprovider','clients') COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `support_ticket_uuid`, `type`, `userid`, `booking_id`, `details`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'billing', 1, 1, 'hiiiiiiiiiiiiiiiii', '2020-06-03 07:22:43', '2020-06-18 01:17:22', '2020-06-18 01:17:22'),
(2, NULL, 'billing', 1, 1, 'teting', '2020-06-03 07:34:57', '2020-06-03 07:34:57', NULL),
(3, NULL, 'billing', 1, 1, 'teting', '2020-06-03 07:34:59', '2020-06-03 07:34:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_histories`
--

CREATE TABLE `support_ticket_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `support_ticket_id` int(11) UNSIGNED NOT NULL,
  `status` enum('resovled','open','inprogress') COLLATE utf8mb4_unicode_ci NOT NULL,
  `adminnote` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `usernote` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_ticket_histories`
--

INSERT INTO `support_ticket_histories` (`id`, `uuid`, `support_ticket_id`, `status`, `adminnote`, `usernote`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 'resovled', 'testing', 'hello', NULL, '2020-06-03 08:04:05', NULL),
(2, NULL, 2, 'open', 'fnbvhdsg', 'dsfhdf', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `abn` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_login` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `email_verify` tinyint(4) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_time` time DEFAULT NULL,
  `loging_attemp` int(11) DEFAULT NULL,
  `status` enum('active','email_verification','manual_verification','inactive','blocked') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profilepic` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last_four` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `first_name`, `last_name`, `email`, `password`, `date_of_birth`, `address`, `mobile_number`, `abn`, `description`, `social_login`, `email_verified_at`, `email_verify`, `remember_token`, `reset_time`, `loging_attemp`, `status`, `fcm_token`, `profilepic`, `created_at`, `updated_at`, `deleted_at`, `stripe_id`, `card_brand`, `card_last_four`, `trial_ends_at`) VALUES
(1, 'b290f0c6-6066-47cc-9c73-8649f605e20c', 'Super', 'User', 'super_user@app.dev', '$2y$10$R97kwDnZ1ngwa4n3Kh9mpeJP6Ov2XoJCXfaQuvAaNDrjAKul5YSH2', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-09 07:06:27', '2020-07-09 07:06:27', NULL, NULL, NULL, NULL, NULL),
(2, '0e7801e2-f31f-4c0c-928b-76b4094fb11a', 'parbat', 'bhatiya', 'webomlinux@gmal.com', '$2y$10$k0GY19JJDeDTG6JJhLcSOu/4sgyBoDUYbKnvr8Dl0z6eklPjUz.CK', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-09 07:06:53', '2020-07-09 07:06:53', NULL, NULL, NULL, NULL, NULL),
(3, 'b01b30e2-3342-4176-98b9-e6eb7ebd72ae', 'kaushik', 'kaushik', 'kaushik@gmal.com', '$2y$10$S9SIBtPggZLqPPFgmaUGjei4U7ai.C8BMtjoYBNZ153xmHZBANAe2', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-09 07:07:08', '2020-07-09 07:07:08', NULL, NULL, NULL, NULL, NULL),
(10, '3cb3730b-025b-4ce7-b029-585fef4ed868', 'yash', 'pwtech', 'yash.pwt@gmal.com', '$2y$10$lR9BJModnHLqP/EbsI1iwum1UCU2cHkfOKYcXPAnc.MMKtAWHwjpG', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 00:50:42', '2020-07-10 00:50:42', NULL, NULL, NULL, NULL, NULL),
(33, '53c13e82-89dc-49e3-97b6-efe97a19c578', 'parbat', 'pwtech', 'parbat@livepf.pw', '$2y$10$n4PDH3g5ugjkj1mPaILoUu/U6KDDz4KlfED7IfP/VHmIrrgmJJXze', NULL, NULL, 1234567890, NULL, NULL, NULL, '2020-07-13 00:44:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:29:02', '2020-07-13 00:44:20', NULL, NULL, NULL, NULL, NULL),
(34, '77a7d225-6e4f-4e81-adb4-5daa99deeb97', 'parbat', 'pwtech', 'parbatwebmaster@gmail.com', '$2y$10$lNxUlrlMIKh39/wU6RGSiuho7s4W6gh6ibDBMxlCPUHBZCpRoSAv6', NULL, NULL, 1234567890, NULL, NULL, NULL, '2020-07-13 00:43:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:30:07', '2020-07-13 00:43:45', NULL, NULL, NULL, NULL, NULL),
(35, '52a46292-2f1a-4e45-92c7-65d7b7c24dc4', 'parbat', 'pwtech', 'parbat@pwtech.pw', '$2y$10$BFg.RGEPglRjZKKzd28pU.mb84jLAranFQIK/83agl2btBq1XhDfO', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:30:18', '2020-07-13 00:30:18', NULL, NULL, NULL, NULL, NULL),
(38, '6d654510-2d1a-49af-8193-fa3dbb13e23f', 'yash1', 'pwtech2', 'yash.pwtech@gmail.com', '$2y$10$atgzVfHg2Qv/nHkCycoc6uM59e7m6j6iUtPXHlJ54hWeV5uRi.C9m', NULL, NULL, 8758481510, NULL, NULL, NULL, '2020-07-22 00:07:05', NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-14 03:35:27', '2020-08-04 07:59:06', NULL, NULL, NULL, NULL, NULL),
(40, 'a2e2aca8-15e4-49c7-8663-d9b42fdb1c22', 'yash', 'pwt', 'yash.pwt@gmail.com', '$2y$10$X3GxOBwY3mR92cZs33sAye3zl732lEn7PGk4IyN3Dl/hA1pv0qFva', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-07-18 02:19:18', '2020-07-18 02:19:19', NULL, 'cus_HfVohRyDUP4mVK', NULL, NULL, NULL),
(42, '1f6d4a68-5336-4cc3-ba5f-688b9308d880', 'keval', 'pwt', 'keval.pwt@gmail.com', '$2y$10$MJgMmz44sbcwnI9ltsEP7ulrtJYBBqNyTgSPNm2x24p2SsEjlDfTW', NULL, NULL, 1234567890, NULL, NULL, NULL, '2020-07-22 00:07:05', NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-20 01:26:57', '2020-07-22 00:07:05', NULL, 'cus_HgFQJI3O6W6jIy', NULL, NULL, NULL),
(44, 'ccd514ad-5292-4190-8846-090f99155ff3', 'bhargav', 'bhargav', 'bhargav.pwtech@gmail.com', '$2y$10$rpmkIUkAADRVDmnB6toGQOn3DjlUQXotGvvgFk/OTdxLnq2ErIpN.', NULL, NULL, 7894561230, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-04 01:51:54', '2020-08-04 01:51:59', NULL, 'cus_HlsDj7eAynf77a', NULL, NULL, NULL),
(45, '05350cb8-d5b5-4378-a44a-30d77690e595', 'rakesh', 'boliya', 'rakesh.pwtech@gmail.com', '$2y$10$lEgxwPj9bQOL7Nh2icmESO7fOYuHWdVX3.Ht44.Pk9kCxA8rRqp4y', NULL, NULL, 9737225179, NULL, NULL, NULL, '2020-07-22 00:07:05', NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 01:42:19', '2020-08-07 01:42:19', NULL, NULL, NULL, NULL, NULL),
(46, '7b43a964-4ab6-4a63-89d6-086108206b0e', 'rakesh', 'boliya', 'rakeshtech.pwtech@gmail.com', '$2y$10$Gc7CUPNgRUEuduUJCNU7NuFThxdr7cE8bnDjtAmj.DZNJA3vydZ4G', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 04:44:28', '2020-08-07 04:44:28', NULL, NULL, NULL, NULL, NULL),
(48, '0360ca86-6478-4f5b-af96-5789bc4a74a8', 'rakesh', 'boliya', 'rakeshtechh.pwtech@gmail.com', '$2y$10$6Kg4MxtCWRbP1A.yVzvVx.ZIynwhVEQOvKqHC9tbC/m6Afqu.cpCq', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 04:56:43', '2020-08-07 04:56:43', NULL, NULL, NULL, NULL, NULL),
(49, '268e1f28-ed40-4fe6-a709-4ad39819bab9', 'rakesh', 'boliya', 'rakeshtechhh.pwtech@gmail.com', '$2y$10$BiSSM4UtxAnbDI0TWxHvEOMpC1NaCia.cEVRjau6Hxryys6T8CsLO', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 04:57:54', '2020-08-07 04:57:54', NULL, NULL, NULL, NULL, NULL),
(50, 'b6c50e22-2402-438e-a7ab-a4f7a47a143f', 'rakesh', 'boliya', 'rakeshtechhhh.pwtech@gmail.com', '$2y$10$jFgNaHetnaJhBXcKc44ATebztmGXRVOGoBYRESgcTDC5p68HlOPpa', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 04:58:37', '2020-08-07 04:58:37', NULL, NULL, NULL, NULL, NULL),
(51, '350c860d-f2ae-45c4-b96d-3f7566846550', 'rakesh', 'boliya', 'rakeshtechhhh1.pwtech@gmail.com', '$2y$10$tUwHO2CQSpG2/zL9pkEdSe1812rKVPVJc6l.GwZAddSEerFJ1Tqwa', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 04:59:44', '2020-08-07 04:59:44', NULL, NULL, NULL, NULL, NULL),
(52, 'c4b392e3-bee1-426b-901a-6ad50e501884', 'rakesh', 'boliya', 'rakeshtechhhh12.pwtech@gmail.com', '$2y$10$W6hTgUpeOcM4R3ijn65ZxO.wvO5j217w58O5IBoOv2.Y07FIdsyDu', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 04:59:53', '2020-08-07 04:59:53', NULL, NULL, NULL, NULL, NULL),
(53, '3ca7f692-202d-4c94-a76b-f491ba3bf136', 'rakesh', 'boliya', 'rakeshtechhh1h12.pwtech@gmail.com', '$2y$10$sc.rUTgaCg2iEoPS1lD7zebyWBUWhKD3Fhvzg/3eAXTt5DNC8/E7u', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 05:00:15', '2020-08-07 05:00:15', NULL, NULL, NULL, NULL, NULL),
(54, '64b6d704-979c-40cf-8e9d-da39b814ced1', 'rakesh', 'boliya', 'rakeshtechhsh1h12.pwtech@gmail.com', '$2y$10$cpHHldtMPWXZDyzm7hT4HOKJOG7DNbYeMDVabNY5Tidynz26opIJC', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 05:01:17', '2020-08-07 05:01:17', NULL, NULL, NULL, NULL, NULL),
(55, 'cc6ed537-77d6-4477-a91f-3e53ce1d68ce', 'rakesh', 'boliya', 'rakeshtechhsh1hd12.pwtech@gmail.com', '$2y$10$4qZVb0RBNApRQBzxGqM9P.NZH7YVPpb28rr9PVrwJWMYbeN1lAw9q', NULL, NULL, 9737225179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email_verification', NULL, NULL, '2020-08-07 05:04:31', '2020-08-07 05:04:31', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_activity_log_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `login_id` int(11) NOT NULL,
  `user_ip` int(11) NOT NULL,
  `user_times` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_agent` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` enum('home','office','business') COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line1` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line2` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subrub` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_badge_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `badge_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_notification_uuid` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `notification_id` int(11) UNSIGNED DEFAULT NULL,
  `sms` tinyint(4) NOT NULL,
  `email` tinyint(4) NOT NULL,
  `push` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_reviews`
--

CREATE TABLE `user_reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_review_for` int(11) UNSIGNED DEFAULT NULL,
  `user_review_by` int(11) UNSIGNED DEFAULT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `rating` enum('1','2','3','4','5') COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_reviews`
--

INSERT INTO `user_reviews` (`id`, `uuid`, `user_review_for`, `user_review_by`, `booking_id`, `rating`, `comments`, `published`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 3, 1, '3', 'testing comments', 1, NULL, NULL, NULL),
(2, NULL, 2, 3, 3, '5', 'demo', 1, NULL, NULL, NULL),
(3, NULL, 3, 1, 12, '2', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure for view `completed_bookings`
--
DROP TABLE IF EXISTS `completed_bookings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `completed_bookings`  AS  select count(`bookings`.`provider_id`) AS `comp_total`,`bookings`.`provider_id` AS `provider_id` from (((`bookings` join `users` on(`bookings`.`provider_id` = `users`.`id`)) join `role_user` on(`role_user`.`user_id` = `users`.`id`)) join `roles` on(`role_user`.`role_id` = `roles`.`id`)) where `roles`.`id` = 2 and `bookings`.`booking_status_id` = 4 group by `bookings`.`provider_id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_admin_uuid_unique` (`admin_uuid`);

--
-- Indexes for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adminactivitylogs_admin_activity_log_uuid_unique` (`admin_activity_log_uuid`),
  ADD KEY `admin_acitvity` (`admin_id`);

--
-- Indexes for table `annoucements`
--
ALTER TABLE `annoucements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `annoucements_uuid_unique` (`uuid`),
  ADD KEY `announcements_user_id` (`user_id`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `apilogs_api_uuid_unique` (`api_uuid`),
  ADD KEY `user_api` (`user_id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `badges_badge_uuid_unique` (`uuid`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_uuid_unique` (`uuid`),
  ADD KEY `user` (`user_id`),
  ADD KEY `booking_status` (`booking_status_id`);

--
-- Indexes for table `booking_activity_logs`
--
ALTER TABLE `booking_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingactivitylogs_booking_activity_log_uuid_unique` (`booking_activity_log_uuid`),
  ADD KEY `booking_activtiy` (`booking_id`),
  ADD KEY `user_activtiy` (`user_id`);

--
-- Indexes for table `booking_addresses`
--
ALTER TABLE `booking_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingaddresses_booking_address_uuid_unique` (`uuid`),
  ADD KEY `booking_address_user` (`booking_id`);

--
-- Indexes for table `booking_changes`
--
ALTER TABLE `booking_changes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingchanges_booking_changes_uuid_unique` (`uuid`),
  ADD KEY `booking_changes` (`booking_id`),
  ADD KEY `booking_changes_changed_by_user` (`changed_by_user`);

--
-- Indexes for table `booking_questions`
--
ALTER TABLE `booking_questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingquestions_booking_question_uuid_unique` (`uuid`),
  ADD KEY `booking_question` (`booking_id`),
  ADD KEY `services_question` (`service_question_id`);

--
-- Indexes for table `booking_recurring_patterns`
--
ALTER TABLE `booking_recurring_patterns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingrecurringpatterns_booking_recurring_pattern_uuid_unique` (`uuid`),
  ADD KEY `booking_recurring_pattern` (`booking_id`);

--
-- Indexes for table `booking_request_providers`
--
ALTER TABLE `booking_request_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingrequestproviders_booking_request_providers_uuid_unique` (`uuid`),
  ADD KEY `booking_request_providers` (`booking_id`),
  ADD KEY `provider_user_id` (`provider_user_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingservices_booking_service_uuid_unique` (`uuid`),
  ADD KEY `booking_service` (`booking_id`),
  ADD KEY `services` (`service_id`);

--
-- Indexes for table `booking_status`
--
ALTER TABLE `booking_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingstatuses_booking_status_uuid_unique` (`uuid`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chats_chat_uuid_unique` (`chat_uuid`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `receiver_id` (`receiverid`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cmspages_cmspages_uuid_unique` (`cmspages_uuid`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cronjobs_cronjob_uuid_unique` (`cronjob_uuid`),
  ADD KEY `cronjobs_cronjob_url_index` (`cronjob_url`);

--
-- Indexes for table `customer_metadata`
--
ALTER TABLE `customer_metadata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customermetadata_enduser_metadata_uuid_unique` (`uuid`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failedjobs_failed_job_uuid_unique` (`uuid`);

--
-- Indexes for table `hacking_activities`
--
ALTER TABLE `hacking_activities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hackingactivities_hacking_activity_uuid_unique` (`hacking_activity_uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_uuid_unique` (`invoice_uuid`),
  ADD KEY `invoice` (`user_id`);

--
-- Indexes for table `login_activity_logs`
--
ALTER TABLE `login_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_activity_logs_login_activity_log_uuid_unique` (`login_activity_log_uuid`),
  ADD KEY `login_acitivty` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notifications_notification_uuid_unique` (`uuid`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notificationlogs_notification_log_uuid_unique` (`notification_log_uuid`),
  ADD KEY `recipient_user_id` (`recipient_user_id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `once_booking_alternate_dates`
--
ALTER TABLE `once_booking_alternate_dates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `once_booking_alternate_dates_booking_alternate_dates_uuid_unique` (`uuid`),
  ADD KEY `once_booking_id` (`booking_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_payment_uuid_unique` (`uuid`),
  ADD KEY `payments_booking_id` (`booking_id`),
  ADD KEY `payments_user_id` (`user_id`);

--
-- Indexes for table `payment_acivities`
--
ALTER TABLE `payment_acivities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_acivities_payment_acivity_uuid_unique` (`payment_acivity_uuid`);

--
-- Indexes for table `payment_activity_logs`
--
ALTER TABLE `payment_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `paymentactivitylogs_payment_activity_log_uuid_unique` (`uuid`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payouts_payout_uuid_unique` (`payout_uuid`),
  ADD KEY `providers_user` (`provider_user_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_plan_uuid_unique` (`uuid`);

--
-- Indexes for table `postcodes`
--
ALTER TABLE `postcodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `postcodes_postcode_uuid_unique` (`uuid`);

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promocodes_promocode_uuid_unique` (`uuid`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promotions_promotion_uuid_unique` (`promotion_uuid`),
  ADD KEY `promotion_category` (`category_id`),
  ADD KEY `promotion_user` (`user_id`);

--
-- Indexes for table `provider_metadata`
--
ALTER TABLE `provider_metadata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `providermetadata_provider_metadata_uuid_unique` (`provider_metadata_uuid`),
  ADD KEY `peovider_user` (`provider_id`);

--
-- Indexes for table `provider_portfolios`
--
ALTER TABLE `provider_portfolios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `providerportfolios_provider_portfolio_uuid_unique` (`provider_portfolio_uuid`),
  ADD KEY `portfolio_user` (`user_id`);

--
-- Indexes for table `provider_postcode_maps`
--
ALTER TABLE `provider_postcode_maps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `providers_postcode` (`postcode_id`);

--
-- Indexes for table `provider_service_maps`
--
ALTER TABLE `provider_service_maps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `providerservicemaps_provider_service_map_uuid_unique` (`provider_service_map_uuid`),
  ADD KEY `provider_services_user` (`provider_id`),
  ADD KEY `provider_services` (`service_id`);

--
-- Indexes for table `provider_skills`
--
ALTER TABLE `provider_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `providerskill_uuid_unique` (`uuid`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `provider_working_hours`
--
ALTER TABLE `provider_working_hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `provider_working` (`provider_id`);

--
-- Indexes for table `reported_incidents`
--
ALTER TABLE `reported_incidents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reportedincidents_reported_incidents_uuid_unique` (`reported_incidents_uuid`),
  ADD KEY `reported_booking` (`booking_id`),
  ADD KEY `reported_userid` (`userid`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_role` (`role_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_service_uuid_unique` (`uuid`),
  ADD KEY `service_category` (`category_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `servicecategories_service_category_uuid_unique` (`uuid`);

--
-- Indexes for table `service_questions`
--
ALTER TABLE `service_questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `servicequestions_service_question_uuid_unique` (`uuid`),
  ADD KEY `services_ques` (`service_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_uuid_unique` (`uuid`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`);

--
-- Indexes for table `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_items_subscription_id_stripe_plan_unique` (`subscription_id`,`stripe_plan`),
  ADD KEY `subscription_items_stripe_id_index` (`stripe_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supporttickets_support_ticket_uuid_unique` (`support_ticket_uuid`),
  ADD KEY `user_support` (`userid`),
  ADD KEY `booking_supprot` (`booking_id`);

--
-- Indexes for table `support_ticket_histories`
--
ALTER TABLE `support_ticket_histories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supporttickethistories_support_ticket_history_uuid_unique` (`uuid`),
  ADD KEY `support_ticket` (`support_ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`),
  ADD KEY `users_stripe_id_index` (`stripe_id`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `useractivitylogs_user_activity_log_uuid_unique` (`user_activity_log_uuid`),
  ADD KEY `user_activity` (`user_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `useraddresses_user_address_uuid_unique` (`uuid`),
  ADD KEY `user_fk_addresses` (`user_id`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userbadges_user_badge_uuid_unique` (`user_badge_uuid`),
  ADD KEY `user_badges` (`user_id`),
  ADD KEY `badges` (`badge_id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usernotifications_user_notification_uuid_unique` (`user_notification_uuid`),
  ADD KEY `user_notifications` (`user_id`),
  ADD KEY `notification_id` (`notification_id`);

--
-- Indexes for table `user_reviews`
--
ALTER TABLE `user_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userreviews_user_reviews_uuid_unique` (`uuid`),
  ADD KEY `booking_review` (`booking_id`),
  ADD KEY `user_review` (`user_review_for`),
  ADD KEY `provider_review` (`user_review_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `annoucements`
--
ALTER TABLE `annoucements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `booking_activity_logs`
--
ALTER TABLE `booking_activity_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_addresses`
--
ALTER TABLE `booking_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `booking_changes`
--
ALTER TABLE `booking_changes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_questions`
--
ALTER TABLE `booking_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `booking_recurring_patterns`
--
ALTER TABLE `booking_recurring_patterns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_request_providers`
--
ALTER TABLE `booking_request_providers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `booking_status`
--
ALTER TABLE `booking_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_metadata`
--
ALTER TABLE `customer_metadata`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hacking_activities`
--
ALTER TABLE `hacking_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_activity_logs`
--
ALTER TABLE `login_activity_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `once_booking_alternate_dates`
--
ALTER TABLE `once_booking_alternate_dates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payment_acivities`
--
ALTER TABLE `payment_acivities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_activity_logs`
--
ALTER TABLE `payment_activity_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `postcodes`
--
ALTER TABLE `postcodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_metadata`
--
ALTER TABLE `provider_metadata`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `provider_portfolios`
--
ALTER TABLE `provider_portfolios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_postcode_maps`
--
ALTER TABLE `provider_postcode_maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `provider_service_maps`
--
ALTER TABLE `provider_service_maps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `provider_skills`
--
ALTER TABLE `provider_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `provider_working_hours`
--
ALTER TABLE `provider_working_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reported_incidents`
--
ALTER TABLE `reported_incidents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_questions`
--
ALTER TABLE `service_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_items`
--
ALTER TABLE `subscription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `support_ticket_histories`
--
ALTER TABLE `support_ticket_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_reviews`
--
ALTER TABLE `user_reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD CONSTRAINT `admin_acitvity` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Constraints for table `annoucements`
--
ALTER TABLE `annoucements`
  ADD CONSTRAINT `announcements_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD CONSTRAINT `user_api` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `booking_status` FOREIGN KEY (`booking_status_id`) REFERENCES `booking_status` (`id`),
  ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_activity_logs`
--
ALTER TABLE `booking_activity_logs`
  ADD CONSTRAINT `booking_activtiy` FOREIGN KEY (`booking_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_activtiy` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_addresses`
--
ALTER TABLE `booking_addresses`
  ADD CONSTRAINT `booking_address_user` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `booking_changes`
--
ALTER TABLE `booking_changes`
  ADD CONSTRAINT `booking_changes` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_changes_changed_by_user` FOREIGN KEY (`changed_by_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_questions`
--
ALTER TABLE `booking_questions`
  ADD CONSTRAINT `booking_question` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `services_question` FOREIGN KEY (`service_question_id`) REFERENCES `service_questions` (`id`);

--
-- Constraints for table `booking_recurring_patterns`
--
ALTER TABLE `booking_recurring_patterns`
  ADD CONSTRAINT `booking_recurring_pattern` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `booking_request_providers`
--
ALTER TABLE `booking_request_providers`
  ADD CONSTRAINT `booking_request_providers` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `provider_user_id` FOREIGN KEY (`provider_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_service` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `services` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `booking_id` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `receiver_id` FOREIGN KEY (`receiverid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sender_id` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_metadata`
--
ALTER TABLE `customer_metadata`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoice` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `login_activity_logs`
--
ALTER TABLE `login_activity_logs`
  ADD CONSTRAINT `login_acitivty` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD CONSTRAINT `recipient_user_id` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `once_booking_alternate_dates`
--
ALTER TABLE `once_booking_alternate_dates`
  ADD CONSTRAINT `once_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `payments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `providers_user` FOREIGN KEY (`provider_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotion_category` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`),
  ADD CONSTRAINT `promotion_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `provider_metadata`
--
ALTER TABLE `provider_metadata`
  ADD CONSTRAINT `peovider_user` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `provider_portfolios`
--
ALTER TABLE `provider_portfolios`
  ADD CONSTRAINT `portfolio_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `provider_postcode_maps`
--
ALTER TABLE `provider_postcode_maps`
  ADD CONSTRAINT `provider_id` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `providers_postcode` FOREIGN KEY (`postcode_id`) REFERENCES `postcodes` (`id`);

--
-- Constraints for table `provider_service_maps`
--
ALTER TABLE `provider_service_maps`
  ADD CONSTRAINT `provider_services` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `provider_services_user` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `provider_skills`
--
ALTER TABLE `provider_skills`
  ADD CONSTRAINT `provider_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `provider_working_hours`
--
ALTER TABLE `provider_working_hours`
  ADD CONSTRAINT `provider_working` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reported_incidents`
--
ALTER TABLE `reported_incidents`
  ADD CONSTRAINT `reported_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `reported_userid` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `service_category` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`);

--
-- Constraints for table `service_questions`
--
ALTER TABLE `service_questions`
  ADD CONSTRAINT `services_ques` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `booking_supprot` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `user_support` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `support_ticket_histories`
--
ALTER TABLE `support_ticket_histories`
  ADD CONSTRAINT `support_ticket` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`);

--
-- Constraints for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD CONSTRAINT `user_activity` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_fk_addresses` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `badges` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`),
  ADD CONSTRAINT `user_badges` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `notification_id` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`),
  ADD CONSTRAINT `user_notifications` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_reviews`
--
ALTER TABLE `user_reviews`
  ADD CONSTRAINT `booking_review` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `provider_review` FOREIGN KEY (`user_review_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_review` FOREIGN KEY (`user_review_for`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
