-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 30, 2021 at 02:15 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id16456968_is424_group8_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `job_code` int(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `first_name`, `last_name`, `job_code`) VALUES
(2, 'Tom', 'Thompson', 3),
(111, 'Bob', 'Johnson', 2),
(123, 'aa', 'bb', 3);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
  `qty_in_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`product_id`, `product_name`, `description`, `qty_in_stock`) VALUES
(21, 'CLOTHES', 'aaa', 0),
(22, '19 DOLLAR FORTNITE CARD', 'I know you took my fortnite card', 45),
(23, 'TANKTOP', ' try asking again', 6),
(24, 'CLOTHING', 'gggg', 234),
(25, 'NEW ITEM', 'I just love using \'\'\'\' apostrophes \'\'\'\' !!!!', 321),
(26, 'NEWITEM2', 'aaa', 432);

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `retail_price` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`sale_id`, `product_id`, `transaction_id`, `quantity_sold`, `retail_price`) VALUES
(1, 1, 4, 6, 4.00);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_purchase`
--

CREATE TABLE `supplier_purchase` (
  `purchase_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `quantity_purchased` int(11) NOT NULL,
  `cost` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `supplier_purchase`
--

INSERT INTO `supplier_purchase` (`purchase_id`, `transaction_id`, `product_id`, `supplier_name`, `quantity_purchased`, `cost`) VALUES
(17, 54, 22, 'GUCCI', 45, 6.66),
(18, 55, 23, 'HOWTOBASIC', 6, 6.50),
(20, 58, 25, 'SUPPLIER\'S CHOICE', 321, 23.88),
(21, 59, 26, 'SUPPLIER\'S ISSUE', 432, 5.60);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `transaction_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `transaction_datetime`, `employee_id`) VALUES
(4, '2021-04-28 08:15:05', 123),
(54, '2021-04-29 03:48:29', 2),
(55, '2021-04-29 04:00:08', 111),
(57, '2021-04-29 04:13:39', 123),
(58, '2021-04-29 04:14:56', 123),
(59, '2021-04-29 07:36:19', 123);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`sale_id`,`product_id`,`transaction_id`),
  ADD KEY `sale_transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `supplier_purchase`
--
ALTER TABLE `supplier_purchase`
  ADD PRIMARY KEY (`purchase_id`,`transaction_id`,`product_id`),
  ADD KEY `sale_product_id` (`product_id`),
  ADD KEY `purchase_transaction_id` (`transaction_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`,`transaction_datetime`,`employee_id`),
  ADD KEY `transaction_employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `supplier_purchase`
--
ALTER TABLE `supplier_purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `inventory` (`product_id`),
  ADD CONSTRAINT `sale_transaction_id` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`);

--
-- Constraints for table `supplier_purchase`
--
ALTER TABLE `supplier_purchase`
  ADD CONSTRAINT `purchase_transaction_id` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`),
  ADD CONSTRAINT `sale_product_id` FOREIGN KEY (`product_id`) REFERENCES `inventory` (`product_id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
