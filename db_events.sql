-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 09-03-2018 a las 06:16:02
-- Versión del servidor: 5.7.19
-- Versión de PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_events`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_access`
--

DROP TABLE IF EXISTS `ev_access`;
CREATE TABLE IF NOT EXISTS `ev_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usertype` enum('admin','seller') NOT NULL,
  `view_access` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_access`
--

INSERT INTO `ev_access` (`id`, `usertype`, `view_access`) VALUES
(1, 'admin', 'event'),
(2, 'admin', 'category'),
(3, 'admin', 'product'),
(4, 'admin', 'inventory'),
(5, 'admin', 'order'),
(6, 'seller', 'order'),
(7, 'admin', 'report'),
(8, 'seller', 'report');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_category`
--

DROP TABLE IF EXISTS `ev_category`;
CREATE TABLE IF NOT EXISTS `ev_category` (
  `company_id` int(11) DEFAULT NULL,
  `description` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `active` enum('Y','N') DEFAULT 'Y',
  `creation_date` datetime DEFAULT NULL,
  `uid_creator` int(11) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_category`
--

INSERT INTO `ev_category` (`company_id`, `description`, `category_id`, `active`, `creation_date`, `uid_creator`) VALUES
(1, 'Categoria 1', 1, 'Y', '2018-03-04 19:59:00', 1),
(14, 'Dulces', 2, 'Y', '2018-03-08 20:02:00', 1),
(14, 'Salado', 3, 'Y', '2018-03-08 20:02:00', 1),
(14, 'Bebidas Frias', 4, 'Y', '2018-03-08 21:50:00', 27),
(15, 'bebidas', 5, 'Y', '2018-03-08 23:47:00', 30),
(15, 'Comida caliente', 6, 'Y', '2018-03-08 23:47:00', 30),
(15, 'snacks', 7, 'Y', '2018-03-08 23:48:00', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_company`
--

DROP TABLE IF EXISTS `ev_company`;
CREATE TABLE IF NOT EXISTS `ev_company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `nit` varchar(15) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `manager` varchar(20) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `uid_creator` int(11) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_company`
--

INSERT INTO `ev_company` (`company_id`, `name`, `nit`, `address`, `phone`, `manager`, `creation_date`, `uid_creator`, `active`) VALUES
(1, 'Empresa 1', '123456', 'empresa', '44455566', 'Encargado', '2018-03-04 19:00:00', 1, 'Y'),
(2, 'Empresa 2', '123213', 'Direccion', 'telefono', 'encargado', '2018-03-08 19:38:00', 1, 'Y'),
(4, 'Empresa 4', '123213', 'Direccion', 'telefono', 'encargado', '2018-03-08 19:38:00', 1, 'Y'),
(5, 'Empresa  5', '123132132', 'Direccion', '33324324', 'test', '2018-03-08 19:44:00', 1, 'Y'),
(6, 'Empresa  6', '123132132', 'Direccion', '33324324', 'test', '2018-03-08 19:44:00', 1, 'Y'),
(7, 'Empresa  6', '123132132', 'Direccion', '33324324', 'test', '2018-03-08 19:44:00', 1, 'Y'),
(8, 'Empresa  6', '123132132', 'Direccion', '33324324', 'test', '2018-03-08 19:44:00', 1, 'Y'),
(9, 'Empresa  6', '123132132', 'Direccion', '33324324', 'test', '2018-03-08 19:44:00', 1, 'Y'),
(10, 'Empresa  6', '123132132', 'Direccion', '33324324', 'test', '2018-03-08 19:44:00', 1, 'Y'),
(11, 'Empresa  6', '123132132', 'Direccion', '33324324', 'test', '2018-03-08 19:44:00', 1, 'Y'),
(12, 'Empresa 12', '23234', 'Direccion', '342343', 'Encargado', '2018-03-08 19:58:00', 1, 'Y'),
(13, 'Empresa 12', '3243423', 'Direccion', '2343242', 'Encargado', '2018-03-08 19:59:00', 1, 'Y'),
(14, 'Empresa 14', '23432423', 'Direccion', '23342', 'Encargado', '2018-03-08 20:00:00', 1, 'Y'),
(15, 'la gran papaya', '1235678', 'direccion', '34243432', 'dikembe', '2018-03-08 23:44:00', 1, 'Y');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_event`
--

DROP TABLE IF EXISTS `ev_event`;
CREATE TABLE IF NOT EXISTS `ev_event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `uid_creator` int(11) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`event_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_event`
--

INSERT INTO `ev_event` (`event_id`, `company_id`, `description`, `start_date`, `end_date`, `creation_date`, `uid_creator`, `active`) VALUES
(1, 1, 'Evento 1', '2018-03-10', '2018-03-10', '2018-03-04 20:21:00', 1, 'Y'),
(2, 14, 'Evento semana santa', '2018-03-08', '2018-03-08', '2018-03-08 20:40:00', 1, 'Y'),
(3, 14, 'Evento pre semana santa', '2018-03-08', '2018-03-08', '2018-03-08 23:10:00', 1, 'Y'),
(4, 15, 'que papaya', '2018-03-08', '2018-03-09', '2018-03-08 23:49:00', 30, 'Y');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_inventory`
--

DROP TABLE IF EXISTS `ev_inventory`;
CREATE TABLE IF NOT EXISTS `ev_inventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `uid_creator` int(11) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_inventory`
--

INSERT INTO `ev_inventory` (`inventory_id`, `event_id`, `creation_date`, `uid_creator`, `active`) VALUES
(3, 1, '2018-03-05 10:40:53', 1, 'Y'),
(4, 2, '2018-03-08 22:38:51', 27, 'Y'),
(5, 4, '2018-03-08 23:50:29', 30, 'Y');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_inventory_detail`
--

DROP TABLE IF EXISTS `ev_inventory_detail`;
CREATE TABLE IF NOT EXISTS `ev_inventory_detail` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT 'Initial Quantity',
  `price` decimal(10,0) NOT NULL,
  `status` enum('initial','supply') DEFAULT 'initial',
  `quantity_sold` int(11) DEFAULT '0',
  PRIMARY KEY (`detail_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_inventory_detail`
--

INSERT INTO `ev_inventory_detail` (`detail_id`, `inventory_id`, `product_id`, `quantity`, `price`, `status`, `quantity_sold`) VALUES
(1, 3, 1, 100, '10', 'initial', 25),
(2, 3, 2, 200, '20', 'initial', 7),
(3, 3, 3, 300, '30', 'initial', 21),
(4, 3, 4, 400, '40', 'initial', 0),
(6, 3, 1, 50, '10', 'supply', 0),
(7, 3, 2, 700, '7', 'supply', 0),
(8, 3, 3, 80, '20', 'supply', 0),
(9, 3, 8, 100, '6', 'initial', 16),
(10, 3, 8, 500, '10', 'supply', 0),
(11, 4, 19, 100, '10', 'initial', 2),
(12, 4, 16, 200, '20', 'initial', 2),
(13, 4, 18, 500, '15', 'initial', 5),
(14, 5, 21, 30, '6', 'initial', 1),
(15, 5, 20, 30, '5', 'initial', 30),
(16, 5, 22, 300, '3', 'initial', 2),
(17, 5, 21, 50, '0', 'supply', 0),
(18, 5, 20, 80, '0', 'supply', 0),
(19, 5, 21, 67, '0', 'supply', 0),
(20, 5, 21, 7, '0', 'supply', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_order`
--

DROP TABLE IF EXISTS `ev_order`;
CREATE TABLE IF NOT EXISTS `ev_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `uid_creator` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `order_amount` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`order_id`),
  KEY `company_id` (`company_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_order`
--

INSERT INTO `ev_order` (`order_id`, `event_id`, `company_id`, `uid_creator`, `creation_date`, `order_amount`) VALUES
(7, 1, NULL, 1, '2018-03-05 16:29:40', '50.00'),
(8, 1, NULL, 1, '2018-03-06 20:27:26', '10.00'),
(9, 1, NULL, 1, '2018-03-06 20:31:24', '10.00'),
(10, 1, NULL, 1, '2018-03-06 20:31:39', '10.00'),
(11, 1, NULL, 1, '2018-03-06 20:33:01', '10.00'),
(12, 1, NULL, 1, '2018-03-06 21:36:35', '40.00'),
(13, 1, NULL, 1, '2018-03-06 21:45:19', '40.00'),
(14, 1, NULL, 1, '2018-03-06 22:42:13', '40.00'),
(15, 1, NULL, 1, '2018-03-07 19:09:10', '6.00'),
(16, 1, NULL, 1, '2018-03-07 19:10:14', '6.00'),
(17, 1, NULL, 1, '2018-03-07 19:10:41', '6.00'),
(18, 1, NULL, 1, '2018-03-07 20:07:58', '272.00'),
(19, 1, NULL, 1, '2018-03-07 20:22:54', '272.00'),
(20, 1, NULL, 1, '2018-03-07 20:24:10', '312.00'),
(21, 1, NULL, 1, '2018-03-07 20:28:50', '312.00'),
(22, 1, NULL, 1, '2018-03-07 21:12:02', '240.00'),
(23, 1, NULL, 1, '2018-03-07 21:12:12', '240.00'),
(24, 1, NULL, 1, '2018-03-07 21:12:21', '240.00'),
(25, 1, NULL, 1, '2018-03-07 21:12:47', '100.00'),
(26, 1, NULL, 1, '2018-03-07 21:14:18', '100.00'),
(27, 1, NULL, 1, '2018-03-07 21:14:39', '100.00'),
(28, 1, NULL, 1, '2018-03-07 21:16:20', '12.00'),
(29, 1, NULL, 1, '2018-03-07 21:17:55', '12.00'),
(30, 1, NULL, 1, '2018-03-07 21:18:09', '12.00'),
(31, 1, NULL, 1, '2018-03-07 21:20:57', '12.00'),
(32, 1, NULL, 1, '2018-03-07 21:21:21', '12.00'),
(33, 1, NULL, 1, '2018-03-07 21:22:08', '0.00'),
(34, 1, NULL, 1, '2018-03-07 21:22:25', '0.00'),
(35, 1, NULL, 1, '2018-03-07 21:23:19', '12.00'),
(36, 1, NULL, 1, '2018-03-07 21:31:37', '12.00'),
(37, 1, NULL, 1, '2018-03-07 21:31:54', '122.00'),
(38, 1, NULL, 1, '2018-03-07 21:32:24', '722.00'),
(39, 1, NULL, 1, '2018-03-07 21:32:45', '52.00'),
(40, 1, NULL, 1, '2018-03-07 21:32:57', '68.00'),
(41, 1, NULL, 1, '2018-03-07 21:33:13', '68.00'),
(42, 1, NULL, 1, '2018-03-07 22:03:58', '60.00'),
(43, 2, NULL, 27, '2018-03-08 22:53:32', '50.00'),
(44, 2, NULL, 28, '2018-03-08 22:55:04', '85.00'),
(45, 4, NULL, 30, '2018-03-08 23:50:44', '14.00'),
(46, 4, NULL, 31, '2018-03-08 23:56:15', '8.00'),
(47, 4, NULL, 30, '2018-03-09 00:05:19', '140.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_order_detail`
--

DROP TABLE IF EXISTS `ev_order_detail`;
CREATE TABLE IF NOT EXISTS `ev_order_detail` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `product_id` (`product_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_order_detail`
--

INSERT INTO `ev_order_detail` (`detail_id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 7, 2, 1, '20.00'),
(2, 7, 3, 1, '30.00'),
(3, 8, 1, 1, '10.00'),
(4, 9, 1, 1, '10.00'),
(5, 10, 1, 1, '10.00'),
(6, 11, 1, 1, '10.00'),
(7, 12, 4, 1, '40.00'),
(8, 13, 4, 1, '40.00'),
(9, 14, 4, 1, '40.00'),
(10, 15, 8, 1, '6.00'),
(11, 16, 8, 1, '6.00'),
(12, 17, 8, 1, '6.00'),
(13, 18, 8, 2, '6.00'),
(14, 18, 4, 3, '40.00'),
(15, 18, 1, 4, '10.00'),
(16, 18, 2, 5, '20.00'),
(17, 19, 8, 2, '6.00'),
(18, 19, 4, 3, '40.00'),
(19, 19, 1, 4, '10.00'),
(20, 19, 2, 5, '20.00'),
(21, 20, 8, 2, '6.00'),
(22, 20, 4, 4, '40.00'),
(23, 20, 1, 4, '10.00'),
(24, 20, 2, 5, '20.00'),
(25, 21, 8, 2, '6.00'),
(26, 21, 4, 4, '40.00'),
(27, 21, 1, 4, '10.00'),
(28, 21, 2, 5, '20.00'),
(29, 22, 4, 5, '40.00'),
(30, 22, 3, 2, '20.00'),
(31, 23, 4, 5, '40.00'),
(32, 23, 3, 2, '20.00'),
(33, 24, 4, 5, '40.00'),
(34, 24, 3, 2, '20.00'),
(35, 25, 4, 2, '40.00'),
(36, 25, 8, 2, '10.00'),
(37, 26, 4, 2, '40.00'),
(38, 26, 8, 2, '10.00'),
(39, 27, 4, 2, '40.00'),
(40, 27, 8, 2, '10.00'),
(41, 28, 8, 2, '6.00'),
(42, 29, 8, 2, '6.00'),
(43, 30, 8, 2, '6.00'),
(44, 31, 8, 2, '6.00'),
(45, 32, 8, 2, '6.00'),
(46, 34, 8, 2, '6.00'),
(47, 35, 8, 2, '6.00'),
(48, 36, 8, 2, '6.00'),
(49, 37, 8, 2, '6.00'),
(50, 37, 1, 5, '10.00'),
(51, 37, 2, 3, '20.00'),
(52, 38, 8, 2, '6.00'),
(53, 38, 1, 5, '10.00'),
(54, 38, 2, 3, '20.00'),
(55, 38, 3, 20, '30.00'),
(56, 39, 8, 2, '6.00'),
(57, 39, 1, 4, '10.00'),
(58, 40, 8, 3, '6.00'),
(59, 40, 1, 5, '10.00'),
(60, 41, 8, 3, '6.00'),
(61, 41, 1, 5, '10.00'),
(62, 42, 1, 1, '10.00'),
(63, 42, 2, 1, '20.00'),
(64, 42, 3, 1, '30.00'),
(65, 43, 18, 2, '15.00'),
(66, 43, 16, 1, '20.00'),
(67, 44, 18, 3, '15.00'),
(68, 44, 16, 1, '20.00'),
(69, 44, 19, 2, '10.00'),
(70, 45, 22, 1, '3.00'),
(71, 45, 21, 1, '6.00'),
(72, 45, 20, 1, '5.00'),
(73, 46, 20, 1, '5.00'),
(74, 46, 22, 1, '3.00'),
(75, 47, 20, 28, '5.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_product`
--

DROP TABLE IF EXISTS `ev_product`;
CREATE TABLE IF NOT EXISTS `ev_product` (
  `company_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `creation_date` datetime DEFAULT NULL,
  `uid_creator` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_product`
--

INSERT INTO `ev_product` (`company_id`, `category_id`, `product_id`, `description`, `active`, `creation_date`, `uid_creator`) VALUES
(1, 1, 1, 'Producto 1', 'Y', '2018-03-04 20:13:00', 1),
(1, 1, 2, 'Producto 2', 'Y', '2018-03-04 20:13:00', 1),
(1, 1, 3, 'Producto 3', 'Y', '2018-03-04 20:13:00', 1),
(1, 1, 4, 'Producto 4', 'Y', '2018-03-04 20:13:00', 1),
(1, 1, 5, 'Producto 5', 'Y', '2018-03-04 20:13:00', 1),
(1, 1, 6, 'Producto 6', 'Y', '2018-03-04 20:13:00', 1),
(1, 1, 7, 'Producto 7', 'N', '2018-03-04 20:13:00', 1),
(1, 1, 8, 'Producto 8', 'N', '2018-03-04 20:13:00', 1),
(14, 2, 15, 'Crepas', 'Y', '2018-03-08 20:31:00', 1),
(14, 2, 16, 'Crepas', 'Y', '2018-03-08 20:31:00', 1),
(14, 3, 17, 'Pan con frijol', 'Y', '2018-03-08 20:31:00', 1),
(14, 4, 18, 'Cerveza Gallo', 'Y', '2018-03-08 22:35:00', 27),
(14, 4, 19, 'Gaseosa', 'Y', '2018-03-08 22:35:00', 27),
(15, 5, 20, 'pepsi', 'Y', '2018-03-08 23:48:00', 30),
(15, 6, 21, 'shucos', 'Y', '2018-03-08 23:48:00', 30),
(15, 7, 22, 'tortrix picante', 'Y', '2018-03-08 23:48:00', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_user`
--

DROP TABLE IF EXISTS `ev_user`;
CREATE TABLE IF NOT EXISTS `ev_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `lastname` varchar(20) DEFAULT NULL,
  `usertype` enum('superadmin','admin','seller') NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `uid_creator` int(11) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varbinary(100) NOT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ev_user`
--

INSERT INTO `ev_user` (`user_id`, `company_id`, `name`, `lastname`, `usertype`, `creation_date`, `uid_creator`, `username`, `password`, `last_access`) VALUES
(1, NULL, 'Administrador', NULL, 'superadmin', NULL, NULL, 'admin', 0x6531306164633339343962613539616262653536653035376632306638383365, '2018-03-08 11:03:46'),
(18, 1, 'test', '', 'admin', '2018-03-05 22:56:00', 1, 'usuario', 0x6638303332643563616533646532306663656338383766333935656339613661, '2018-03-05 10:03:55'),
(19, 10, 'admin_010', NULL, 'admin', '2018-03-08 19:48:00', 1, 'admin_010', 0x3935636230336636353031393730626539326633383862303937306335653461, NULL),
(20, 10, 'vendedor_010', NULL, 'seller', '2018-03-08 19:48:00', 1, 'vendedor_010', 0x3537356236303661363262313534313433323666373165306334666164316662, NULL),
(21, 11, 'admin_011', NULL, 'admin', '2018-03-08 19:50:00', 1, 'admin_011', 0x6261393964366531376564346364313961663637653464623761323764643465, NULL),
(22, 11, 'vendedor_011', NULL, 'seller', '2018-03-08 19:50:00', 1, 'vendedor_011', 0x6366323234393365313730323731326435623137306438386339383566663934, NULL),
(23, 12, 'admin_012', NULL, 'admin', '2018-03-08 19:59:00', 1, 'admin_012', 0x3639666538623461336631376230636339626433393165616364336631343564, NULL),
(24, 12, 'vendedor_012', NULL, 'seller', '2018-03-08 19:59:00', 1, 'vendedor_012', 0x6463303839323738383866316135613864316135346261343435646431616236, NULL),
(25, 13, 'admin_013', NULL, 'admin', '2018-03-08 20:00:00', 1, 'admin_013', 0x3331346337356361303238353463333862316632616638663566393766613236, NULL),
(26, 13, 'vendedor_013', NULL, 'seller', '2018-03-08 20:00:00', 1, 'vendedor_013', 0x6438613063653235383262656136653961373464633132326465356632366139, NULL),
(27, 14, 'admin_014', NULL, 'admin', '2018-03-08 20:01:00', 1, 'admin_014', 0x6238663439373133643362623337653661616133663932616530356461353533, '2018-03-08 11:03:49'),
(28, 14, 'vendedor_014', NULL, 'seller', '2018-03-08 20:01:00', 1, 'vendedor_014', 0x3630666535353039646433653963613961653563343964626237343931396336, '2018-03-08 10:03:28'),
(29, 14, 'test', 'test', 'seller', '2018-03-08 22:23:00', 27, 'test', 0x6531306164633339343962613539616262653536653035376632306638383365, NULL),
(30, 15, 'admin_015', '', 'admin', '2018-03-08 23:46:00', 1, 'admin_015', 0x6531306164633339343962613539616262653536653035376632306638383365, '2018-03-08 11:03:11'),
(31, 15, 'vendedor_015', '', 'seller', '2018-03-08 23:45:00', 1, 'vendedor_015', 0x6531306164633339343962613539616262653536653035376632306638383365, '2018-03-08 11:03:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metatabla`
--

DROP TABLE IF EXISTS `metatabla`;
CREATE TABLE IF NOT EXISTS `metatabla` (
  `ID_Metatabla` int(10) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) NOT NULL,
  `PK` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Metatabla`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `metatabla`
--

INSERT INTO `metatabla` (`ID_Metatabla`, `Nombre`, `PK`) VALUES
(1, 'Metatabla', 'ID_Metatabla'),
(2, 'ev_category', 'category_id'),
(3, 'ev_company', 'company_id'),
(4, 'ev_product', 'product_id'),
(5, 'ev_event', 'event_id'),
(6, 'ev_user', 'user_id');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ev_category`
--
ALTER TABLE `ev_category`
  ADD CONSTRAINT `ev_category_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `ev_company` (`company_id`);

--
-- Filtros para la tabla `ev_event`
--
ALTER TABLE `ev_event`
  ADD CONSTRAINT `ev_event_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `ev_company` (`company_id`);

--
-- Filtros para la tabla `ev_inventory_detail`
--
ALTER TABLE `ev_inventory_detail`
  ADD CONSTRAINT `ev_inventory_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `ev_product` (`product_id`);

--
-- Filtros para la tabla `ev_order`
--
ALTER TABLE `ev_order`
  ADD CONSTRAINT `ev_order_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `ev_company` (`company_id`),
  ADD CONSTRAINT `ev_order_ibfk_4` FOREIGN KEY (`event_id`) REFERENCES `ev_event` (`event_id`);

--
-- Filtros para la tabla `ev_order_detail`
--
ALTER TABLE `ev_order_detail`
  ADD CONSTRAINT `ev_order_detail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ev_product` (`product_id`),
  ADD CONSTRAINT `ev_order_detail_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `ev_order` (`order_id`);

--
-- Filtros para la tabla `ev_product`
--
ALTER TABLE `ev_product`
  ADD CONSTRAINT `ev_product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ev_category` (`category_id`),
  ADD CONSTRAINT `ev_product_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `ev_company` (`company_id`);

--
-- Filtros para la tabla `ev_user`
--
ALTER TABLE `ev_user`
  ADD CONSTRAINT `ev_user_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `ev_company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
