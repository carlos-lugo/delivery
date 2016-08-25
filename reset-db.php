<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

include_layout_template('header.php');

// $response = array();
// exec('mysql -u root -paaaa -D motos -e "DROP DATABASE motos"');
// exec('mysql -u root -paaaa -e "CREATE DATABASE motos"');
// exec('mysql -u root -paaaa motos < C:\Users\Asus\Documents\UniServerZ\core\mysql\bin\motos.sql', $response);

require_once(LIB_PATH.DS.'database.php');
global $database;

$sql = "

	DROP TABLE bike_comments;
	DROP TABLE bike_fat_tax;
	DROP TABLE bike_orders;
	DROP TABLE bike_order_assignment;
	DROP TABLE bike_postal_codes;
	DROP TABLE bike_restaurants;
	DROP TABLE bike_shipping_fee;
	DROP TABLE bike_users;

	-- phpMyAdmin SQL Dump
	-- version 4.6.3
	-- https://www.phpmyadmin.net/
	--
	-- Host: 127.0.0.1:3306
	-- Generation Time: Aug 08, 2016 at 09:39 AM
	-- Server version: 5.6.31
	-- PHP Version: 7.0.8

	SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
	SET time_zone = '+00:00';


	/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
	/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
	/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
	/*!40101 SET NAMES utf8mb4 */;

	--
	-- Database: `motos`
	--

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_comments`
	--

	CREATE TABLE `bike_comments` (
	  `id` int(11) NOT NULL,
	  `id_user` int(11) NOT NULL,
	  `id_order` int(11) NOT NULL,
	  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `text` text NOT NULL,
	  `status` text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_fat_tax`
	--

	CREATE TABLE `bike_fat_tax` (
	  `id` int(11) NOT NULL,
	  `tax` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Dumping data for table `bike_fat_tax`
	--

	INSERT INTO `bike_fat_tax` (`id`, `tax`) VALUES
	(1, 2);

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_orders`
	--

	CREATE TABLE `bike_orders` (
	  `id` int(11) NOT NULL,
	  `id_rest` int(11) NOT NULL,
	  `street_name` text NOT NULL,
	  `street_number` int(11) NOT NULL,
	  `postal_code` int(11) NOT NULL,
	  `apartment_number` text NOT NULL,
	  `phone` int(11) NOT NULL,
	  `reception_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `accepted_to_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `register_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `assigned_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `estimated_arrival` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `finished_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `id_bike` int(11) NOT NULL,
	  `status` text NOT NULL,
	  `cost` int(11) NOT NULL,
	  `bike_profit` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Dumping data for table `bike_orders`
	--

	INSERT INTO `bike_orders` (`id`, `id_rest`, `street_name`, `street_number`, `postal_code`, `apartment_number`, `phone`, `reception_time`, `accepted_to_time`, `register_time`, `assigned_time`, `estimated_arrival`, `finished_time`, `id_bike`, `status`, `cost`, `bike_profit`) VALUES
	(1, 1, 'Calle de Velázquez', 20, 28001, '3a', 1234, '2016-08-04 17:51:00', '2016-08-04 18:51:00', '2016-08-04 17:56:46', '2016-08-04 17:56:53', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 'Abierto', 5, 3),
	(2, 2, 'Calle de Velázquez', 2, 28001, '4c', 1234, '2016-08-04 17:51:00', '2016-08-04 18:51:00', '2016-08-04 17:57:26', '2016-08-04 17:57:36', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 'Abierto', 4, 2),
	(3, 1, 'Calle de Alcalá', 13, 28014, '1a', 1234, '2016-08-04 17:52:00', '2016-08-04 18:52:00', '2016-08-04 17:58:04', '2016-08-04 17:58:10', '0000-00-00 00:00:00', '2016-08-04 17:59:09', 2, 'Cerrado', 7, 5),
	(4, 1, 'Calle Gran Vía', 17, 28013, '32', 1234, '2016-08-04 17:53:00', '2016-08-04 18:53:00', '2016-08-04 17:58:54', '2016-08-04 17:59:04', '0000-00-00 00:00:00', '2016-08-04 17:59:15', 2, 'Cerrado', 5, 3);

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_order_assignment`
	--

	CREATE TABLE `bike_order_assignment` (
	  `id` int(11) NOT NULL,
	  `id_user` int(11) NOT NULL,
	  `id_order` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_postal_codes`
	--

	CREATE TABLE `bike_postal_codes` (
	  `id` int(11) NOT NULL,
	  `postcode` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Dumping data for table `bike_postal_codes`
	--

	INSERT INTO `bike_postal_codes` (`id`, `postcode`) VALUES
	(1, 28001),
	(5, 28004),
	(6, 28005),
	(7, 28012),
	(2, 28013),
	(4, 28014);

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_restaurants`
	--

	CREATE TABLE `bike_restaurants` (
	  `id` int(11) NOT NULL,
	  `name` text NOT NULL,
	  `street_name` text NOT NULL,
	  `street_number` int(11) NOT NULL,
	  `postal_code` int(11) NOT NULL,
	  `manager` text NOT NULL,
	  `phone` int(11) NOT NULL,
	  `email` text NOT NULL,
	  `status` text NOT NULL,
	  `user` text NOT NULL,
	  `password` text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Dumping data for table `bike_restaurants`
	--

	INSERT INTO `bike_restaurants` (`id`, `name`, `street_name`, `street_number`, `postal_code`, `manager`, `phone`, `email`, `status`, `user`, `password`) VALUES
	(1, 'Big Paella', 'Reina Mercedes', 20, 28020, 'MrPaella', 123, 'paella@asdf.com', 'Activo', 'paella', '1234'),
	(2, 'Delicious Tapas', 'Claudio Coello', 67, 28001, 'MrTapas', 123, 'tapas@asdf.com', 'Activo', 'tapas', '1234'),
	(3, 'Jamón Serrano', 'Velázquez', 6, 28001, 'MrHam', 123, 'jamon@asdf.com', 'Activo', 'jamon', '1234'),
	(4, 'A Bad Restaurant', 'Gran Vía', 3, 28002, 'MrBad', 123, 'bad@asdf.com', 'Borrado', 'bad', '1234');

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_shipping_fee`
	--

	CREATE TABLE `bike_shipping_fee` (
	  `id` int(11) NOT NULL,
	  `id_postal_code` int(11) NOT NULL,
	  `id_rest` int(11) NOT NULL,
	  `price` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Dumping data for table `bike_shipping_fee`
	--

	INSERT INTO `bike_shipping_fee` (`id`, `id_postal_code`, `id_rest`, `price`) VALUES
	(1, 1, 1, 5),
	(2, 2, 1, 5),
	(3, 4, 1, 7),
	(4, 5, 1, 7),
	(5, 6, 1, 15),
	(6, 7, 1, 15),
	(7, 1, 2, 4),
	(8, 2, 2, 4),
	(9, 4, 2, 6),
	(10, 5, 2, 6),
	(11, 6, 2, 13),
	(12, 7, 2, 13),
	(13, 1, 3, 6),
	(14, 2, 3, 6),
	(15, 4, 3, 9),
	(16, 5, 3, 9),
	(17, 6, 3, 14),
	(18, 7, 3, 14);

	-- --------------------------------------------------------

	--
	-- Table structure for table `bike_users`
	--

	CREATE TABLE `bike_users` (
	  `id` int(11) NOT NULL,
	  `first_name` text NOT NULL,
	  `last_name` text NOT NULL,
	  `email` text NOT NULL,
	  `password` text NOT NULL,
	  `phone` int(11) NOT NULL,
	  `level` text NOT NULL,
	  `status` text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Dumping data for table `bike_users`
	--

	INSERT INTO `bike_users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `level`, `status`) VALUES
	(1, 'Boss', 'cclugo', 'manager', '1234', 0, 'Manager', 'Activo'),
	(2, 'DeliveryBoy1', 'delivery', 'boy1', '1234', 1234, 'Motorista', 'Activo'),
	(3, 'DeliveryBoy2', 'Delivery', 'boy2', '1234', 1234, 'Motorista', 'Activo');

	--
	-- Indexes for dumped tables
	--

	--
	-- Indexes for table `bike_comments`
	--
	ALTER TABLE `bike_comments`
	  ADD PRIMARY KEY (`id`);

	--
	-- Indexes for table `bike_fat_tax`
	--
	ALTER TABLE `bike_fat_tax`
	  ADD PRIMARY KEY (`id`);

	--
	-- Indexes for table `bike_orders`
	--
	ALTER TABLE `bike_orders`
	  ADD PRIMARY KEY (`id`);

	--
	-- Indexes for table `bike_order_assignment`
	--
	ALTER TABLE `bike_order_assignment`
	  ADD PRIMARY KEY (`id`);

	--
	-- Indexes for table `bike_postal_codes`
	--
	ALTER TABLE `bike_postal_codes`
	  ADD PRIMARY KEY (`id`),
	  ADD UNIQUE KEY `postcode` (`postcode`);

	--
	-- Indexes for table `bike_restaurants`
	--
	ALTER TABLE `bike_restaurants`
	  ADD PRIMARY KEY (`id`);

	--
	-- Indexes for table `bike_shipping_fee`
	--
	ALTER TABLE `bike_shipping_fee`
	  ADD PRIMARY KEY (`id`);

	--
	-- Indexes for table `bike_users`
	--
	ALTER TABLE `bike_users`
	  ADD PRIMARY KEY (`id`);

	--
	-- AUTO_INCREMENT for dumped tables
	--

	--
	-- AUTO_INCREMENT for table `bike_comments`
	--
	ALTER TABLE `bike_comments`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
	--
	-- AUTO_INCREMENT for table `bike_fat_tax`
	--
	ALTER TABLE `bike_fat_tax`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
	--
	-- AUTO_INCREMENT for table `bike_orders`
	--
	ALTER TABLE `bike_orders`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
	--
	-- AUTO_INCREMENT for table `bike_order_assignment`
	--
	ALTER TABLE `bike_order_assignment`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
	--
	-- AUTO_INCREMENT for table `bike_postal_codes`
	--
	ALTER TABLE `bike_postal_codes`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
	--
	-- AUTO_INCREMENT for table `bike_restaurants`
	--
	ALTER TABLE `bike_restaurants`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
	--
	-- AUTO_INCREMENT for table `bike_shipping_fee`
	--
	ALTER TABLE `bike_shipping_fee`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
	--
	-- AUTO_INCREMENT for table `bike_users`
	--
	ALTER TABLE `bike_users`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
	/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
	/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
	/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

";

$database->multi_query($sql);


?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Database has been reseted successfully</h3>	
	<p><?php //print_r($response, true); ?>
	
</div>

<?php include_layout_template('footer.php'); ?>