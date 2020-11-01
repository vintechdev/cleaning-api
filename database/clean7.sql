-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 13, 2020 at 03:37 AM
-- Server version: 5.7.28
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
CREATE DATABASE cleanv7;
--
-- Database: `cleanv7`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(6, '2016_06_01_000004_create_oauth_clients_table', 2),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2),
(8, '2020_07_09_075124_users_field_changes', 3),
(9, '2020_07_10_055247_create_roles_table', 4),
(10, '2020_07_10_055525_create_create_role_user_tables_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('197af639247136c20f3a1506c1a395e46fdad55961d559fe18df5d5461382d26caec4028f0a3079b', 10, 2, NULL, '[\"customer\"]', 0, '2020-07-10 00:54:18', '2020-07-10 00:54:18', '2020-07-25 06:24:18'),
('229bb945193daea617cc7c1ccd81a9ccbe06ec3b2519c5693d2945f5fa65d76e2ac8313c357d79b4', 19, 2, NULL, '[\"admin\"]', 0, '2020-07-10 05:10:31', '2020-07-10 05:10:31', '2020-07-25 10:40:31'),
('3dfc9239f7502ea24f22c0faaeeeecf3f719f7ae4fa1d98d178c474523f4f348c478e0f3eec94da1', 1, 2, NULL, '[\"admin\"]', 0, '2020-07-09 07:26:09', '2020-07-09 07:26:09', '2020-07-24 12:56:09'),
('46fba4f1485e48dee5324170d7be85d95b95fae4f638f7a92f2c19a835bee3f4f80b1e9a52b3b56b', 36, 2, NULL, '[\"customer\"]', 0, '2020-07-13 01:24:50', '2020-07-13 01:24:50', '2020-07-28 06:54:50'),
('5b4a5421710d12ab7374613fc31150ad45bfb48feed6245cbaf9c9bd10a98fbb73f54ef5b9a3178a', 16, 2, NULL, '[\"admin\"]', 0, '2020-07-10 02:12:52', '2020-07-10 02:12:52', '2020-07-25 07:42:52'),
('66febe40eef476f3f2bda3118080d6fc9d0e05ed27087acdb98b0eb1ba71f647f99a1453cf7db30d', 1, 2, NULL, '[]', 0, '2020-07-09 06:35:36', '2020-07-09 06:35:36', '2020-07-24 12:05:36'),
('69119dabc1ed96226b1afe13c5b1f81418d2e4f1b274b7d7eadd0455342dae57ace17d27bae20bea', 16, 2, NULL, '[\"admin\"]', 0, '2020-07-10 02:08:13', '2020-07-10 02:08:13', '2020-07-25 07:38:13'),
('693d537cd98c3b2c8d0640b0d6fafa40216016badfbd8681086a2afe60f597877c7872f1b98e6d33', 1, 2, NULL, '[\"admin\"]', 0, '2020-07-10 00:02:02', '2020-07-10 00:02:02', '2020-07-25 05:32:02'),
('7697209a450c5d22d9ef4095056686a52f8ebc69747aac870f5422a04b399b117038a9a619c2c144', 17, 2, NULL, '[\"admin\"]', 0, '2020-07-10 04:33:55', '2020-07-10 04:33:55', '2020-07-25 10:03:55'),
('8e5357495fa12ded052fd5cb6291f4c7cdb2f51d5c18c9d068defeae788c87ac86d80aeae62f37b4', 1, 2, NULL, '[]', 0, '2020-07-09 07:07:48', '2020-07-09 07:07:48', '2020-07-24 12:37:48'),
('91ac383836c6e9a11667e3043ed51641be694c327eb1b287b82ad07b39e3a0e626125786cd7a9605', 19, 2, NULL, '[\"customer\"]', 0, '2020-07-10 05:24:22', '2020-07-10 05:24:22', '2020-07-25 10:54:21'),
('a6f1713d432d90e673b55ed36bcb8a52f5ccee9a80148933313ca2a420336578d414ebb82f10313d', 19, 2, NULL, '[\"admin\"]', 0, '2020-07-10 05:34:05', '2020-07-10 05:34:05', '2020-07-25 11:04:05'),
('b314a4ab3f442f08e830056f08e0b617c2d3331ba2e97e04834c79ab3558e012be1642ffd4e85702', 12, 2, NULL, '[\"admin\"]', 0, '2020-07-10 01:06:38', '2020-07-10 01:06:38', '2020-07-25 06:36:38'),
('cbbc9ec7b9e471917f2a25dcb59fb83fbfabd30b08b4091f7e085d2c810798811245fdb776153a8a', 36, 2, NULL, '[\"admin\"]', 0, '2020-07-13 01:22:07', '2020-07-13 01:22:07', '2020-07-28 06:52:07'),
('d4f4d0b79613755b78b0c997387733e8f535ceb154abb52113b5a35b56dd0f8f1661b12a4c253fc7', 1, 2, NULL, '[\"customer\"]', 0, '2020-07-10 00:12:07', '2020-07-10 00:12:07', '2020-07-25 05:42:07'),
('db1cc21667b74116c237e67359c94195f2534cc00ac78d67ad11fff7806bb6bc57a94606ccd8c295', 12, 2, NULL, '[\"customer\"]', 0, '2020-07-10 00:59:55', '2020-07-10 00:59:55', '2020-07-25 06:29:55'),
('e3efc5925c6fbb3929e427c2586b06f7b7c08664b6b3a207c1277f3b57fa2b170f5ddd03e202b952', 19, 2, NULL, '[\"customer\"]', 0, '2020-07-10 05:13:17', '2020-07-10 05:13:17', '2020-07-25 10:43:17'),
('f057694fdd0ea6a8351e9512e73cb2485e3760033f049bd24edf5dfef6a75677a6db97c25a8f33fb', 18, 2, NULL, '[\"admin\"]', 0, '2020-07-10 04:43:53', '2020-07-10 04:43:53', '2020-07-25 10:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, NULL, 'Laravel Personal Access Client', '9HmcLOeYAZLucr7zehLoa5t7IlzO4LPkCYrzVmHF', NULL, 'http://localhost', 1, 0, 0, '2020-07-08 08:44:34', '2020-07-08 08:44:34'),
(2, NULL, 'Laravel Password Grant Client', '885GMWiodScyRqVE1SwVfMvh8ila5UrDdFcZNO86', 'users', 'http://localhost', 0, 1, 0, '2020-07-08 08:44:34', '2020-07-08 08:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-07-08 08:44:34', '2020-07-08 08:44:34');

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
('0f3f9d614f702ca0540f274753c4c803adb1a15c1e43002f5857e807a1f1afcb574abc27d740dd08', '197af639247136c20f3a1506c1a395e46fdad55961d559fe18df5d5461382d26caec4028f0a3079b', 0, '2020-08-09 06:24:18'),
('1a4847a0c71ec9723d3c0111b5869bf670a765a9d645dcf8a9767e8914139fafb7bac35de11d3a3f', '8e5357495fa12ded052fd5cb6291f4c7cdb2f51d5c18c9d068defeae788c87ac86d80aeae62f37b4', 0, '2020-08-08 12:37:48'),
('226c445054aba4307e3c7ecda89b59e8db49d1854d3f1cb7d5047aa0e0440d0c2f64a3f1d80a1cb3', 'b314a4ab3f442f08e830056f08e0b617c2d3331ba2e97e04834c79ab3558e012be1642ffd4e85702', 0, '2020-08-09 06:36:38'),
('33d3b1f658a4ba820d309b67d1ff140eeb98d5cd9b587295963bf8ba454af87a53c9274782c0f224', 'db1cc21667b74116c237e67359c94195f2534cc00ac78d67ad11fff7806bb6bc57a94606ccd8c295', 0, '2020-08-09 06:29:55'),
('4be12ed578839ac85fe258f779fd4464c47d951a61fdb23c395d1d8d4eb1adfb8c157114cf7d9745', '46fba4f1485e48dee5324170d7be85d95b95fae4f638f7a92f2c19a835bee3f4f80b1e9a52b3b56b', 0, '2020-08-12 06:54:50'),
('4fbe8bd6fdc04688bcef6314aa49e76e99becccc07e2320cc958dfcd4c83b0f3a1b175a5e1acf597', 'cbbc9ec7b9e471917f2a25dcb59fb83fbfabd30b08b4091f7e085d2c810798811245fdb776153a8a', 0, '2020-08-12 06:52:07'),
('51360624b31ced05f44ad9b933f4a46857e2dc774a5e3faed41d6de6251157622a79cfc84aa10909', '693d537cd98c3b2c8d0640b0d6fafa40216016badfbd8681086a2afe60f597877c7872f1b98e6d33', 0, '2020-08-09 05:32:02'),
('552cd42e85e4f4fc9a09268e48ed6249cbabf2574569ed30d0b2498cd514c44a5411d3b7b26cade0', '7697209a450c5d22d9ef4095056686a52f8ebc69747aac870f5422a04b399b117038a9a619c2c144', 0, '2020-08-09 10:03:55'),
('7150184a34bbcab8b0bfcc4679f1c75d19239220642d7465b81ad782340ce4a2b3d87999691f0d3d', '5b4a5421710d12ab7374613fc31150ad45bfb48feed6245cbaf9c9bd10a98fbb73f54ef5b9a3178a', 0, '2020-08-09 07:42:52'),
('81968fb44ad32874a09950eca99d10759d8e6520b199c22779304de7c7b4884fe17b11677cc55e63', 'a6f1713d432d90e673b55ed36bcb8a52f5ccee9a80148933313ca2a420336578d414ebb82f10313d', 0, '2020-08-09 11:04:05'),
('8d67fd3f70717bdfa1155722498abad20b2dd7cb3c842384faab616790d6231fe144aa4b3ab81dbf', 'd4f4d0b79613755b78b0c997387733e8f535ceb154abb52113b5a35b56dd0f8f1661b12a4c253fc7', 0, '2020-08-09 05:42:08'),
('8e247adaf15a344c963f00d92355098ed8fe837667d26034e0b4f241143e8821cd2665a707edc112', '3dfc9239f7502ea24f22c0faaeeeecf3f719f7ae4fa1d98d178c474523f4f348c478e0f3eec94da1', 0, '2020-08-08 12:56:09'),
('91fd453cc1e4dd42f17eaee46d4aaa7bbd24a7c65602a2971d786d353bdd2af68b7552fb629e390c', 'f057694fdd0ea6a8351e9512e73cb2485e3760033f049bd24edf5dfef6a75677a6db97c25a8f33fb', 0, '2020-08-09 10:13:53'),
('b5a4ec26963f23672fb49e96973098a57e0fa08226fef73e15e6f7bacb6b253bcc788be6c7446ad9', '229bb945193daea617cc7c1ccd81a9ccbe06ec3b2519c5693d2945f5fa65d76e2ac8313c357d79b4', 0, '2020-08-09 10:40:31'),
('c0cec20ee89d4c40e9cffda602f886d55bf87628fd419dc44e3529b666db5c4098359d4c4ce86c14', '66febe40eef476f3f2bda3118080d6fc9d0e05ed27087acdb98b0eb1ba71f647f99a1453cf7db30d', 0, '2020-08-08 12:05:36'),
('cd2aa35a2ea449cd49bb3897b3076f1785c60e9fa4f340f6d34104d77d772ee059656d6072eff489', 'e3efc5925c6fbb3929e427c2586b06f7b7c08664b6b3a207c1277f3b57fa2b170f5ddd03e202b952', 0, '2020-08-09 10:43:17'),
('eefda5f13db6b2995843d7e688ce2b41a2556695a156545b5d14d18d3c7429ea1c2129364f8482c3', '91ac383836c6e9a11667e3043ed51641be694c327eb1b287b82ad07b39e3a0e626125786cd7a9605', 0, '2020-08-09 10:54:22'),
('faa3b36767a58cf0658e0817166480592d9a4dfee36552b9670aa02640b1117145c3dd9226c5375e', '69119dabc1ed96226b1afe13c5b1f81418d2e4f1b274b7d7eadd0455342dae57ace17d27bae20bea', 0, '2020-08-09 07:38:13');

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
(3, 'customer', 'customer', '2020-07-10 06:11:30', '2020-07-10 06:11:30');

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
(24, 1, 37, '2020-07-13 00:57:58', '2020-07-13 00:57:58');

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
  `status` enum('active','deactive','fraud','block') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profilepic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `first_name`, `last_name`, `email`, `password`, `date_of_birth`, `address`, `mobile_number`, `abn`, `description`, `social_login`, `email_verified_at`, `email_verify`, `remember_token`, `reset_time`, `loging_attemp`, `status`, `fcm_token`, `profilepic`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'b290f0c6-6066-47cc-9c73-8649f605e20c', 'Super', 'User', 'super_user@app.dev', '$2y$10$R97kwDnZ1ngwa4n3Kh9mpeJP6Ov2XoJCXfaQuvAaNDrjAKul5YSH2', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-09 07:06:27', '2020-07-09 07:06:27', NULL),
(2, '0e7801e2-f31f-4c0c-928b-76b4094fb11a', 'parbat', 'bhatiya', 'webomlinux@gmal.com', '$2y$10$k0GY19JJDeDTG6JJhLcSOu/4sgyBoDUYbKnvr8Dl0z6eklPjUz.CK', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-09 07:06:53', '2020-07-09 07:06:53', NULL),
(3, 'b01b30e2-3342-4176-98b9-e6eb7ebd72ae', 'kaushik', 'kaushik', 'kaushik@gmal.com', '$2y$10$S9SIBtPggZLqPPFgmaUGjei4U7ai.C8BMtjoYBNZ153xmHZBANAe2', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2020-07-09 07:07:08', '2020-07-09 07:07:08', NULL),
(10, '3cb3730b-025b-4ce7-b029-585fef4ed868', 'yash', 'pwtech', 'yash.pwt@gmal.com', '$2y$10$lR9BJModnHLqP/EbsI1iwum1UCU2cHkfOKYcXPAnc.MMKtAWHwjpG', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 00:50:42', '2020-07-10 00:50:42', NULL),
(12, 'ac436e62-d080-49bf-87b2-84b23433c51e', 'yash', 'pwtech', 'yash.pwtech1@gmal.com', '$2y$10$ryUJogJkycqDDG2OHrXikuo8ZrYd90Rppyk2LwIXwfE1HTEhCgFmK', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 00:53:41', '2020-07-10 00:53:41', NULL),
(15, '7dd13361-6d6c-4e12-ae0f-7b9a0ce98f5c', 'yash', 'pwtech', 'yash.pwtech2@gmal.com', '$2y$10$toCiqw/ivpig/KPLe31SPOKSivy25ryQf.N6THYy./g0hf.y6ztSy', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 01:55:18', '2020-07-10 01:55:18', NULL),
(16, '214b7fa3-7a13-4dc3-9ae7-559ad1c2282a', 'yash', 'pwtech', 'yash.pwtech3@gmal.com', '$2y$10$faAE99c9TVRS/2DqCyLGRuz7CVyKM.B5bUPBr0BZ2apgJKl4K2A3S', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 02:07:37', '2020-07-10 02:07:37', NULL),
(17, 'e7c3ad69-0382-4e5e-b52b-505c2c00e113', 'yash', 'pwtech', 'yash.pwtech4@gmal.com', '$2y$10$vM9gYomq5VKXvtYAqLFrrOHArlNoJE4BxhzjrgTwf8RFHuk6gquxO', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 04:32:57', '2020-07-10 04:32:57', NULL),
(18, 'db5ca335-25b9-467f-b120-6c1d8c107cc4', 'yash', 'pwtech', 'yash.pwtech5@gmal.com', '$2y$10$KrPd1ni4jy6.EkSpZ/cDYOf87tkA1miXT2MfW9oyWQ71aFBCdVdAK', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 04:43:30', '2020-07-10 04:43:30', NULL),
(19, '4d3fae66-1e34-4364-81eb-1aa1a970035a', 'yash', 'pwtech', 'yash.pwtech6@gmal.com', '$2y$10$SsGzFXGDfc8Biq0U4utWturxPpUZrIAKuYQctiXrUK1A.8WccMn2m', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-10 05:10:15', '2020-07-10 05:10:15', NULL),
(21, '19a1b7ce-fccb-4959-a82b-6e27483e8af3', 'yash', 'pwtech', 'yash.pwtech7@gmal.com', '$2y$10$iWGXUijE1h2vYJwPQ31u8emi3XUXQWWnJ9DwtKGO0nwiMUn6rEKVq', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-12 23:28:14', '2020-07-12 23:28:14', NULL),
(22, '89189bd4-b642-4329-a5de-5482fe6e259f', 'yash', 'pwtech', 'yash.pwtech8@gmal.com', '$2y$10$kO34znT.ejF5Tho7Wdb9aOZ9F8H6/UvxEkKIlyTZuKOpPFNao6WiG', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-12 23:44:46', '2020-07-12 23:44:46', NULL),
(23, '17d108a2-0456-49c3-97af-daff2d8d88a1', 'yash', 'pwtech', 'yash.pwtech9@gmal.com', '$2y$10$lEPT51vdfDiHUPDuepf1kubQOK7yCbs5UL.001iJuPs78Iawk1nZS', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-12 23:46:17', '2020-07-12 23:46:17', NULL),
(33, '53c13e82-89dc-49e3-97b6-efe97a19c578', 'parbat', 'pwtech', 'parbat@livepf.pw', '$2y$10$n4PDH3g5ugjkj1mPaILoUu/U6KDDz4KlfED7IfP/VHmIrrgmJJXze', NULL, NULL, 1234567890, NULL, NULL, NULL, '2020-07-13 00:44:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:29:02', '2020-07-13 00:44:20', NULL),
(34, '77a7d225-6e4f-4e81-adb4-5daa99deeb97', 'parbat', 'pwtech', 'parbatwebmaster@gmail.com', '$2y$10$lNxUlrlMIKh39/wU6RGSiuho7s4W6gh6ibDBMxlCPUHBZCpRoSAv6', NULL, NULL, 1234567890, NULL, NULL, NULL, '2020-07-13 00:43:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:30:07', '2020-07-13 00:43:45', NULL),
(35, '52a46292-2f1a-4e45-92c7-65d7b7c24dc4', 'parbat', 'pwtech', 'parbat@pwtech.pw', '$2y$10$BFg.RGEPglRjZKKzd28pU.mb84jLAranFQIK/83agl2btBq1XhDfO', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:30:18', '2020-07-13 00:30:18', NULL),
(36, 'f26b8d01-e1c8-4859-ae2d-4e4bab5fb147', 'yash', 'pwtech', 'yash.pwtech@gmail.com', '$2y$10$7Byk3Q1OClixKm9yQ81oRuydZMA.HtwwtNZjgQXTXLkDVwpiqjJ0O', NULL, NULL, 1234567890, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:56:44', '2020-07-13 00:56:44', NULL),
(37, 'a7df7c83-275a-42e8-9443-0291facd6baa', 'keval', 'pwt', 'keval.pwt@gmail.com', '$2y$10$kY3kuqp9Go3SdZqVX3em/edeG/Hu9AHL1Sy54QDVaolOqY3sP4.l2', NULL, NULL, 1234567890, NULL, NULL, NULL, '2020-07-13 00:58:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-07-13 00:57:51', '2020-07-13 00:58:34', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
