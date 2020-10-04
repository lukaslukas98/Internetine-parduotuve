-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2020 m. Spa 04 d. 17:21
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `main`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `items`
--

CREATE TABLE `items` (
  `id` int(8) NOT NULL,
  `name` varchar(255) CHARACTER SET cp1257 COLLATE cp1257_lithuanian_ci NOT NULL,
  `code` varchar(255) CHARACTER SET cp1257 COLLATE cp1257_lithuanian_ci NOT NULL,
  `image` text CHARACTER SET cp1257 COLLATE cp1257_lithuanian_ci NOT NULL,
  `price` double(10,2) NOT NULL,
  `kategorija` varchar(255) CHARACTER SET cp1257 COLLATE cp1257_lithuanian_ci DEFAULT NULL,
  `gamintojas` varchar(255) CHARACTER SET cp1257 COLLATE cp1257_lithuanian_ci DEFAULT NULL,
  `sandeli` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `items`
--

INSERT INTO `items` (`id`, `name`, `code`, `image`, `price`, `kategorija`, `gamintojas`, `sandeli`) VALUES
(1, 'Canon sf 123', 'csf123', 'canonsf.png', 1500.00, 'Camera', 'Canon', 0),
(2, 'Sony ss 123', 'sss123', 'sonyss.jpeg', 300.00, 'Camera', 'Sony', 1),
(3, 'Asus 123', 'a123', 'asus123.jpg', 800.00, 'Computer', 'Asus', 1),
(4, 'HP 123', 'hp123', 'hp123.jpg', 123.00, 'Computer', 'HP', 0),
(5, 'Samsung GSII', 'sgsii', 'samsung gs2.jpg', 89.00, 'Smartphone', 'Samsung', 0),
(6, 'Iphone 5', 'i5', 'iphone 5.jpg', 555.00, 'Smartphone', 'Apple', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `admin`) VALUES
(1, 'admin', 'admin@admin', '21232f297a57a5a743894a0e4a801fc3', 1),
(2, 'test', 'test@tes', '098f6bcd4621d373cade4e832627b4f6', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
