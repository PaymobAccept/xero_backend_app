-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2022 at 05:24 PM
-- Server version: 5.7.38-cll-lve
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wldhzlxq_mostafa`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(10) NOT NULL,
  `uniqueId` varchar(20) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(1000) DEFAULT NULL,
  `password` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `uniqueId`, `firstName`, `lastName`, `email`, `password`) VALUES
(1, 'rO9ibkq92ojM', 'Raman', 'Preet', 'raman@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'Wj4n5CApOxPe', 'Mostafa', 'Menessy', 'mostafa@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uniqueId` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(10) DEFAULT NULL,
  `type` int(10) NOT NULL DEFAULT '2' COMMENT '1:admin;2:contact',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uniqueId`, `name`, `email`, `email_verified_at`, `password`, `status`, `type`, `created_at`, `updated_at`) VALUES
(1, 'rO9ibkq92ojM', 'Raman Preet', 'raman@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 1, 2, NULL, NULL),
(2, 'Wj4n5CApOxPe', 'Mostafa Menessy', 'mostafa@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xeroauth`
--

CREATE TABLE `xeroauth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientRef` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clientId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secretKey` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `returnUrl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `xeroauth`
--

INSERT INTO `xeroauth` (`id`, `clientRef`, `clientId`, `secretKey`, `returnUrl`, `created_at`, `updated_at`) VALUES
(1, 'EAD780', 'C67F05C901254CAE99AEB61E8AB4B73F', 'ZO6ZBTmFOMAK21SIBnW2G6ka8I6AwUCu6A3oFO5A4qlygnwE', 'https://yourhelpgroup.com/mostafa/callback', '2022-06-13 08:09:02', '2022-06-13 08:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `xeroorgdetail`
--

CREATE TABLE `xeroorgdetail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientRef` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_legal_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xerotokendetail`
--

CREATE TABLE `xerotokendetail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientRef` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accessToken` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refreshToken` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenantId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenantName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `xerotokendetail`
--

INSERT INTO `xerotokendetail` (`id`, `clientRef`, `accessToken`, `expires`, `refreshToken`, `id_token`, `token_type`, `tenantId`, `tenantName`, `created_at`, `updated_at`) VALUES
(1, 'Wj4n5CApOxPe', 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjFDQUY4RTY2NzcyRDZEQzAyOEQ2NzI2RkQwMjYxNTgxNTcwRUZDMTkiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJISy1PWm5jdGJjQW8xbkp2MENZVmdWY09fQmsifQ.eyJuYmYiOjE2NTcwMjE4OTAsImV4cCI6MTY1NzAyMzY5MCwiaXNzIjoiaHR0cHM6Ly9pZGVudGl0eS54ZXJvLmNvbSIsImF1ZCI6Imh0dHBzOi8vaWRlbnRpdHkueGVyby5jb20vcmVzb3VyY2VzIiwiY2xpZW50X2lkIjoiQzY3RjA1QzkwMTI1NENBRTk5QUVCNjFFOEFCNEI3M0YiLCJzdWIiOiJjZmE2Y2Q2ZTliYWI1MGEyOWExNDYxZmJmMjI0YzdjYyIsImF1dGhfdGltZSI6MTY1NjkyNDM0NywieGVyb191c2VyaWQiOiIyNzQ1ZTYwZi05ZmJjLTRmOWEtYjNkYi03NWIwMTUzNDIyYmYiLCJnbG9iYWxfc2Vzc2lvbl9pZCI6IjM4ZGNhYmYyMzc4MzQ2ZTVhYjQ4YWExMmU4YzJmYWU4IiwianRpIjoiZjNkZDZhMjVkYjQyOWU4YmMxMDE1ZmQ1OWJlNDBlOTQiLCJhdXRoZW50aWNhdGlvbl9ldmVudF9pZCI6ImM1ODc2ZWQxLTNlZGYtNGM0YS1iNWYzLTk3OTcwNGZlOTgzYyIsInNjb3BlIjpbImVtYWlsIiwicHJvZmlsZSIsIm9wZW5pZCIsImFjY291bnRpbmcucmVwb3J0cy5yZWFkIiwicHJvamVjdHMiLCJhY2NvdW50aW5nLnNldHRpbmdzIiwiYWNjb3VudGluZy5hdHRhY2htZW50cyIsImFjY291bnRpbmcudHJhbnNhY3Rpb25zIiwiYWNjb3VudGluZy5qb3VybmFscy5yZWFkIiwiYXNzZXRzIiwiYWNjb3VudGluZy5jb250YWN0cyIsIm9mZmxpbmVfYWNjZXNzIl0sImFtciI6WyJzc28iXX0.DOC-auEEnstumgrTxdEikoQHpftVZS16rhWR9vJx50tpmsomM4ORncmglnS8hBI1hrUzvg7pgQyX6ti6wAJBm6ftUAhnN7oYB8uv8x5rX9ByuI367jvxra6Yx6W7XQbgE3dHsfXFAypJyUew-P_nCshxjjJjfn6n7ioziUMcw5Pz0n33mfPGfhoy-Mr3zVpS9U9WU3qo-HaNAXLQNqwq9x2Sppii2kZFD-RLiIhYNLkuVuesYrjTfk_JKT4E5a_smeDtLjOxdInwj5QM-DMCoYveJn9_LTKunCJ4YLxx8zhUmL8Lwlh9xknk-JTV7UU1J0toCom0z52qoHpDQl5BcQ', '1657023690', '4673cc63b11557b888daf4b9f1ae6e580b6f18a6a3500f368b88c3db715bfdcb', 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjFDQUY4RTY2NzcyRDZEQzAyOEQ2NzI2RkQwMjYxNTgxNTcwRUZDMTkiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJISy1PWm5jdGJjQW8xbkp2MENZVmdWY09fQmsifQ.eyJuYmYiOjE2NTY5MjkzOTEsImV4cCI6MTY1NjkyOTY5MSwiaXNzIjoiaHR0cHM6Ly9pZGVudGl0eS54ZXJvLmNvbSIsImF1ZCI6IkM2N0YwNUM5MDEyNTRDQUU5OUFFQjYxRThBQjRCNzNGIiwiaWF0IjoxNjU2OTI5MzkxLCJhdF9oYXNoIjoiWmxSS2JmQy1jR20zTXdycmItMHFJdyIsInNpZCI6IjM4ZGNhYmYyMzc4MzQ2ZTVhYjQ4YWExMmU4YzJmYWU4Iiwic3ViIjoiY2ZhNmNkNmU5YmFiNTBhMjlhMTQ2MWZiZjIyNGM3Y2MiLCJhdXRoX3RpbWUiOjE2NTY5MjQzNDcsInhlcm9fdXNlcmlkIjoiMjc0NWU2MGYtOWZiYy00ZjlhLWIzZGItNzViMDE1MzQyMmJmIiwiZ2xvYmFsX3Nlc3Npb25faWQiOiIzOGRjYWJmMjM3ODM0NmU1YWI0OGFhMTJlOGMyZmFlOCIsInByZWZlcnJlZF91c2VybmFtZSI6Im1lbmVzc3lAcGF5bW9iLmNvbSIsImVtYWlsIjoibWVuZXNzeUBwYXltb2IuY29tIiwiZ2l2ZW5fbmFtZSI6Ik1vc3RhZmEiLCJmYW1pbHlfbmFtZSI6Ik1lbmVzc3kiLCJuYW1lIjoiTW9zdGFmYSBNZW5lc3N5IiwiYW1yIjpbInNzbyJdfQ.sQBeYgbhDz4YbAYhEGRDg1dgJ-c3aPTCh6Rg9DhJM83nUfPZ5rXnxNr_cOXYzUws15ddBtRHvXH9EBCS0-9SnFq_rzq8GOg8b1smjig3D4yaBEIiPSDoQw-dWfg0ldSJENVrXNlPC9ds23QVPcflKGpN05N-i23TbjtLrI13R0UcBWV9urUdtqJltAg7MdUVADQsSb_C1kVwZsa0rMkiXklmkgxQ03FcXHGhwtQeEFyrkZ5KyGplbxjNvJwYlf2JphcuqfPMLVWV-n-yYLk1HDDm643xRkQv7maqpM3FZmXKTQDEkWzhNHDZiAOI5vcBuo_zqT8VN4ErhNxnx2l1NQ', 'Bearer', '120ab66b-2029-470e-9bbb-bd58e4da9aa7', 'Demo Company (Global)', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `xeroauth`
--
ALTER TABLE `xeroauth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xeroorgdetail`
--
ALTER TABLE `xeroorgdetail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xerotokendetail`
--
ALTER TABLE `xerotokendetail`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `xeroauth`
--
ALTER TABLE `xeroauth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xeroorgdetail`
--
ALTER TABLE `xeroorgdetail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xerotokendetail`
--
ALTER TABLE `xerotokendetail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
