-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2019 at 06:45 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `repo`
--

-- --------------------------------------------------------

--
-- 
Table structure for table `marcas`
--


CREATE TABLE `marcas` (
  `marca` varchar(20) NOT NULL,
  `sigla` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `marcas`
--


INSERT INTO `marcas` (`marca`, `sigla`) VALUES
('MITSUBISHI', 'MM'),
('TOYOTA', 'TY'),
('CHEVROLET', 'CH'),
('SSANGYONG', 'SY'),
('PEUEGEOT', 'PG'),
('CITROEN', 'CT'),
('FORD', 'FD'),
('SUBARU', 'SB'),
('AUDI', 'AU'),
('MERCEDES', 'MB'),
('HONDA', 'HO'),
('HYUNDAI', 'HY'),
('KIA', 'KI'),
('VOLVO', 'VL'),
('MAZDA', 'MZ'),
('VOLKSWAGEN', 'VW'),
('SUZUKI', 'SZ'),
('CHRYSLER', 'CS'),
('DODGE', 'DG'),
('FIAT', 'FT'),
('JEEP', 'JE'),
('MAHINDRA', 'MH'),
('MG', 'MG'),
('OPEL', 'OP'),
('RENAULT', 'RL'),
('SAMSUNG', 'SM');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
