-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2022 at 03:39 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `products`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `sku` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `sku`, `name`, `price`) VALUES
(1, 'BK111', 'The Book 1', 11),
(2, 'DV111', 'The DVD 1', 5),
(3, 'FN111', 'The Furniture 1', 200);

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute`
--

CREATE TABLE `product_attribute` (
  `product_attribute_id` int(11) NOT NULL,
  `attribute_type_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_attribute`
--

INSERT INTO `product_attribute` (`product_attribute_id`, `attribute_type_id`, `product_id`, `value`) VALUES
(1, 1, 1, '1'),
(2, 2, 2, '750'),
(3, 3, 3, '150'),
(4, 4, 3, '75'),
(5, 5, 3, '45');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_type`
--

CREATE TABLE `product_attribute_type` (
  `attribute_type_id` int(11) NOT NULL,
  `attribute_type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_attribute_type`
--

INSERT INTO `product_attribute_type` (`attribute_type_id`, `attribute_type_name`) VALUES
(1, 'Weight'),
(2, 'Size'),
(3, 'Height'),
(4, 'Width'),
(5, 'Length');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD PRIMARY KEY (`product_attribute_id`),
  ADD KEY `attribute_type_id` (`attribute_type_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_attribute_type`
--
ALTER TABLE `product_attribute_type`
  ADD PRIMARY KEY (`attribute_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_attribute`
--
ALTER TABLE `product_attribute`
  MODIFY `product_attribute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_attribute_type`
--
ALTER TABLE `product_attribute_type`
  MODIFY `attribute_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD CONSTRAINT `product_attribute_ibfk_1` FOREIGN KEY (`attribute_type_id`) REFERENCES `product_attribute_type` (`attribute_type_id`),
  ADD CONSTRAINT `product_attribute_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
