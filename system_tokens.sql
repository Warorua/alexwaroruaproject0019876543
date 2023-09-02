-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2023 at 08:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `twitter`
--

-- --------------------------------------------------------

--
-- Table structure for table `system_tokens`
--

CREATE TABLE `system_tokens` (
  `id` int(11) NOT NULL,
  `bearer_token` varchar(1000) NOT NULL,
  `consumer_key` varchar(1000) NOT NULL,
  `consumer_secret` varchar(1000) NOT NULL,
  `api` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_tokens`
--

INSERT INTO `system_tokens` (`id`, `bearer_token`, `consumer_key`, `consumer_secret`, `api`) VALUES
(1, 'AAAAAAAAAAAAAAAAAAAAAP2CogEAAAAAllObtfxEcGvYlGTA4Q6chmZRDng%3D98LCl0tXOULYIVjBp05fbyCklBNMeF5Fw9B8EsyjYbCus4bK59', 'NU5HVNdlk2fBBD16OCjI7IbU6', 'jYvLpIfUFC220IoTeXQBiOEpbNdZR2yj7c9lw14IrJ__S1LuY_', 'KOTNOVA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `system_tokens`
--
ALTER TABLE `system_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `system_tokens`
--
ALTER TABLE `system_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
