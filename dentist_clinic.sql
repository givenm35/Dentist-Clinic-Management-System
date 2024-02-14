-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2022 at 01:59 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dentist_clinic`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `average price` ()  BEGIN
	DECLARE total int;
    DECLARE num_rows int;
    DECLARE avg double;
    SET total = (SELECT SUM(price) from products);
    SET num_rows = (SELECT COUNT(*) from products);
    SET avg = total /num_rows;
    SELECT avg;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Get appointment` ()  BEGIN
	DECLARE name varchar(20);
    SET name = 'Fred';
	select c.first_name, c.last_name, a.time FROM
    appointments a 
    JOIN clients c ON c.client_id = a.client_id
    WHERE c.first_name = name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Increase price` ()  BEGIN
	DECLARE price_inc int;
    SET price_inc = 30;
    UPDATE products
    SET price = price + price_inc;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Insert records` ()  BEGIN
 DECLARE count INT DEFAULT 0;
 WHILE count < 2 DO
   INSERT INTO `products` (`product_id`, `name`, 	   	`price`, `stock`) VALUES (NULL, CONCAT('New 		product', count), '100', '100');
   SET count = count + 1;
 END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sales` ()  BEGIN
	DECLARE sales int;
    
    SET sales = (SELECT SUM(p.price) 
FROM `orders` o 
JOIN products p On p.product_id = o.product_id);

	IF sales >2200 THEN
    	SELECT "GOOD" as sales_condition;
    ELSE
    	SELECT "BAD" as sales_condition;
END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `client_id`, `time`, `user_id`, `action`) VALUES
(300, 203, '2022-01-26 00:39:28', 102, 'Teeth filling'),
(301, 200, '2022-01-20 12:45:00', 102, 'Operation on molars'),
(302, 204, '2022-04-15 01:20:00', 106, 'Full teeth inspection'),
(303, 201, '2022-01-21 09:35:00', 107, 'Regular treatment'),
(307, 201, '2022-01-06 01:17:00', 107, 'foods');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `first_name`, `last_name`, `address`, `phone`) VALUES
(200, 'Samantha', 'Lorde', '420 High rd.', '2341980403'),
(201, 'Jessica', 'Lorde', '123 Low rd.', '2341120401'),
(202, 'James', 'Myer', '8484 Lindton street', '2341980403'),
(203, 'Fred', 'Kruger', '888 High rd.', '2341988432'),
(204, 'Samantha', 'Smith', 'Cyprus way 43.', '2340080400');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phone_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`user_id`, `first_name`, `last_name`, `address`, `phone_no`) VALUES
(100, 'Wolf', 'Blitz', '8484 Lindton street', '254783765065'),
(101, 'Papis', 'Cise', '8484 Congo Town street', '254783765065'),
(102, 'Joseph', 'Michael', 'Safaricom 345 rd.', '254783765065'),
(103, 'Karl', 'Drago', '123 Drakrys hill', '254783765065'),
(104, 'Jade', 'Ojal', 'Jericho Makadara street', '254783765111'),
(105, 'Joel', 'Linton', '67 Park Ave', '254783765065'),
(106, 'Josh', 'King', 'Bourenmouth city, Apt 5', '254783765065'),
(107, 'Donatelo', 'Telo', 'In the sewers', '254783765065'),
(108, 'Agent', '47', '006 Jupiter', '254783765065'),
(109, 'Love', 'Jay', 'Mistake street', '254783765060');

-- --------------------------------------------------------

--
-- Table structure for table `medecines`
--

CREATE TABLE `medecines` (
  `medecine_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medecines`
--

INSERT INTO `medecines` (`medecine_id`, `name`, `stock`) VALUES
(1, 'Anbesol', 100),
(2, 'Orajel', 100),
(3, 'Emjel', 500),
(4, 'PerioGuard', 45),
(5, 'Atridoxx', 2555),
(6, 'Teracylyne', 870);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `product_id`, `client_id`, `date`) VALUES
(500, 404, 202, '2022-01-10'),
(503, 405, 201, '2021-10-05'),
(504, 402, 201, '2022-01-01'),
(505, 400, 201, '2022-01-01'),
(506, 404, 202, '2022-01-02'),
(507, 404, 201, '2022-01-04');

-- --------------------------------------------------------

--
-- Table structure for table `patient_medecine`
--

CREATE TABLE `patient_medecine` (
  `client_id` int(11) NOT NULL,
  `medecine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `instructions` varchar(255) NOT NULL,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_medecine`
--

INSERT INTO `patient_medecine` (`client_id`, `medecine_id`, `quantity`, `instructions`, `expiry_date`) VALUES
(200, 6, 150, '3 per day after meals', '2023-01-16'),
(201, 1, 300, 'Used while brushing teeth', '2023-06-14'),
(201, 3, 250, '1 pill a day after dinner', '2022-09-10'),
(202, 1, 300, '3 pills a day after meals', '2024-01-17'),
(202, 4, 100, 'Only when pain persists', '2024-02-08'),
(203, 1, 125, 'Used while brushing teeth', '2022-11-25'),
(204, 5, 450, 'Once a day', '2022-12-25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `stock`) VALUES
(400, 'OneDent Toothbrush', 65, 120),
(401, 'OneDent Toothpaste', 85, 100),
(402, 'Molar Mouthwash', 65, 120),
(403, 'OneDent Whitener', 45, 220),
(404, 'Listerine', 75, 20),
(405, 'Electric Toothbrush', 55, 200),
(407, 'New product', 100, 100),
(408, 'New product0', 100, 100),
(409, 'New product1', 100, 100);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(100, 'admin', 'admin', 'admin'),
(101, 'pipi', '1234', 'nurse'),
(102, 'jojo', '1234', 'dentist'),
(103, 'drago', '1234', 'admin'),
(104, 'jayjay', 'password', 'employee'),
(105, 'leon', 'abcd', 'employee'),
(106, 'king', 'qwerty', 'dentist'),
(107, 'donn', 'lmfao', 'dentist'),
(108, 'sky', '4321', 'employee'),
(109, 'lovergirl2', 'abc', 'dentist');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `client_id` (`client_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `medecines`
--
ALTER TABLE `medecines`
  ADD PRIMARY KEY (`medecine_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `product_id` (`product_id`,`client_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `patient_medecine`
--
ALTER TABLE `patient_medecine`
  ADD PRIMARY KEY (`client_id`,`medecine_id`),
  ADD KEY `client_id` (`client_id`,`medecine_id`),
  ADD KEY `medecine_id` (`medecine_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `medecines`
--
ALTER TABLE `medecines`
  MODIFY `medecine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=508;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=410;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `employees` (`user_id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_medecine`
--
ALTER TABLE `patient_medecine`
  ADD CONSTRAINT `patient_medecine_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `patient_medecine_ibfk_2` FOREIGN KEY (`medecine_id`) REFERENCES `medecines` (`medecine_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
