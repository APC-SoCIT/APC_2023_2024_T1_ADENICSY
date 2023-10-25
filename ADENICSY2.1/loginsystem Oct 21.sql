-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2023 at 11:34 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loginsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Table structure for table `d_calendar`
--

CREATE TABLE `d_calendar` (
  `id` int(11) NOT NULL,
  `d_name` varchar(255) DEFAULT NULL,
  `d_code` varchar(11) DEFAULT NULL,
  `date` date NOT NULL,
  `s_time` time NOT NULL,
  `e_time` time NOT NULL,
  `availableSlot` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `d_calendar`
--

INSERT INTO `d_calendar` (`id`, `d_name`, `d_code`, `date`, `s_time`, `e_time`, `availableSlot`) VALUES
(9, 'Guler Marion Regalado', 'GMR', '2023-02-23', '13:50:00', '15:50:00', 0),
(10, 'Arnold Mendoza', 'DAM', '2023-02-25', '08:00:00', '17:16:00', 0),
(11, 'Denroe Apelo', 'DDA', '2023-03-03', '13:39:00', '16:39:00', 0),
(12, 'Guler Marion Regalado', 'GMR', '2023-03-03', '15:44:00', '17:44:00', 0),
(13, 'Arnold Mendoza', 'DAM', '2023-03-03', '16:45:00', '13:49:00', 0),
(14, 'Arnold Mendoza', 'DAM', '2023-03-02', '20:14:00', '19:14:00', 0),
(15, 'Denroe Apelo', 'DDA', '2023-03-02', '20:15:00', '19:14:00', 0),
(16, 'Guler Marion Regalado', 'GMR', '2023-03-02', '19:27:00', '18:27:00', 0),
(17, 'Guler Marion Regalado', 'GMR', '2023-03-10', '16:19:00', '16:20:00', 0),
(18, 'Dr. Lea Benitez', '', '2023-03-06', '00:00:00', '19:30:00', 0),
(19, 'Dr. May Bell Bustillo', 'DBM', '2023-03-06', '09:30:00', '19:30:00', 0),
(20, 'Dr. Ingrid May Pedrola', 'DIMP', '2023-03-06', '09:30:00', '19:30:00', 0),
(21, 'Dr. Gerald Giba', 'DGG', '2023-03-06', '09:30:00', '19:30:00', 15),
(23, 'Dr. Gerald Giba', 'DGG', '2023-03-07', '09:30:00', '19:30:00', 17),
(25, 'Dr. Ingrid May Pedrola', 'DIMP', '2023-03-07', '09:30:00', '19:30:00', 15),
(27, 'Dr. May Bell Bustillo', 'DBM', '2023-03-07', '09:30:00', '19:30:00', 15),
(30, 'Dr. Lea Benitez', '', '2023-03-07', '09:30:00', '19:30:00', 15),
(32, 'Dr. Lea Benitez', 'DLB', '2023-03-08', '09:30:00', '19:30:00', 15),
(33, 'Dr. Ingrid May Pedrola', 'DIMP', '2023-03-08', '09:30:00', '19:30:00', 15),
(34, 'Dr. Lea Benitez', 'DLB', '2023-03-08', '09:30:00', '19:30:00', 15),
(35, 'Dr. Lea Benitez', 'DLB', '2023-03-09', '09:30:00', '19:30:00', 15),
(36, 'Dr. May Bell Bustillo', '', '2023-03-09', '09:30:00', '19:30:00', 15),
(37, 'Dr. Gerald Giba', 'DGG', '2023-03-09', '09:30:00', '19:30:00', 13),
(38, 'Dr. Ingrid May Pedrola', 'DIMP', '2023-03-24', '09:30:00', '19:30:00', 15),
(39, 'Dr. Gerald Giba', 'DGG', '2023-03-24', '09:30:00', '19:30:00', 15),
(40, 'Dr. Lea Benitez', 'DLB', '2023-04-01', '09:30:00', '19:30:00', 15),
(41, 'Dr. Gerald Giba', '', '2023-07-22', '09:30:00', '19:30:00', 15),
(42, 'Dr. Jurist Pedrola', 'JURIST TACT', '2023-07-22', '12:30:00', '19:30:00', 10),
(43, 'Dr. Lea Benitez', 'DLB', '2023-09-23', '09:30:00', '19:30:00', 15),
(44, 'Dr. Gerald Giba', '', '2023-09-23', '09:30:00', '19:30:00', 15),
(45, 'Dr. Lea Benitez', 'DLB', '2023-09-29', '09:30:00', '19:30:00', 15),
(46, 'Dr. Ivan Emmanuel Flores', '', '2023-09-29', '09:30:00', '19:30:00', 15),
(47, 'Dr. Jurist Pedrola', 'JURIST TACT', '2023-09-29', '09:30:00', '19:30:00', 15),
(48, 'Dr. Ingrid May Pedrola', '', '2023-09-29', '09:30:00', '19:30:00', 15),
(49, 'Dr. Lea Benitez', 'DLB', '2023-10-14', '09:30:00', '19:30:00', 15),
(50, 'Dr. Ingrid May Pedrola', '', '2023-10-14', '09:30:00', '19:30:00', 15),
(51, 'Dr. Ingrid May Pedrola', '', '2023-10-11', '09:30:00', '19:30:00', 15),
(52, 'Dr. Ivan Emmanuel Flores', '', '2023-10-11', '09:30:00', '19:30:00', 15),
(53, 'Dr. Ingrid May Pedrola', '', '2023-10-18', '09:30:00', '19:30:00', 15),
(57, 'Dr. Ivan Emmanuel Flores', '', '2023-10-20', '09:30:00', '19:30:00', 15),
(58, 'Dr. New Doctor Employee', 'JTP', '2023-10-20', '09:30:00', '19:30:00', 15),
(59, 'Dr. Lea Benitez', 'DLB', '2023-10-20', '09:30:00', '19:30:00', 15),
(60, 'Dr. New Employee Dentist', 'BNV', '2023-10-20', '09:30:00', '19:30:00', 15),
(61, 'Dr. Jurist Pedrola', 'JURIST TACT', '2023-10-20', '09:30:00', '19:30:00', 15),
(62, 'Dr. Ingrid May Pedrola', '', '2023-10-20', '09:30:00', '19:30:00', 15),
(63, 'Dr. Jurist Pedrola', 'DJP', '2023-10-21', '09:30:00', '19:30:00', 15),
(79, 'Dr. Ingrid May Pedrola', 'DIMP', '2023-10-21', '09:30:00', '19:30:00', 15),
(80, 'Dr. Ingrid May Pedrola', 'DIMP', '0000-00-00', '09:30:00', '19:30:00', 15),
(81, 'Dr. Ivan Emmanuel Flores', '', '0000-00-00', '09:30:00', '19:30:00', 15),
(82, 'Dr. New Doctor Employee', 'JTP', '0000-00-00', '09:30:00', '19:30:00', 15),
(84, 'Dr. New Doctor Employee', 'JTP', '2023-10-21', '09:30:00', '19:30:00', 15),
(85, 'Dr. Lea Benitez', 'DLB', '2023-10-21', '09:30:00', '19:30:00', 15);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contactno` varchar(255) DEFAULT NULL,
  `PRC_ID` int(7) DEFAULT NULL,
  `empRole` varchar(255) NOT NULL,
  `namecode` varchar(15) NOT NULL,
  `timeCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `fname`, `lname`, `email`, `username`, `password`, `contactno`, `PRC_ID`, `empRole`, `namecode`, `timeCreated`) VALUES
(3, 'May Bell', 'Bustillo', 'BustMA77@gmail.com', 'st_MayAnneB021723', 'ngy456!@vbH', '09678965213', 0, 'Staff', 'DBM', '2023-02-17 10:33:29'),
(16, 'Guiler Marion', 'Regalado', 'janssenpedrola26@gmail.com', 'asda', 'sda', '09982901878', 0, 'Staff', 'DGMR', '2023-02-20 02:03:41'),
(18, 'Marion', 'Regalado', 'mrg@gmail.com', 'marion', '123Qwe', '09302185931', 0, 'Staff', '', '2023-02-23 08:08:17'),
(21, 'Ingrid May', 'Pedrola', 'ingridp26@gmail.com', 'ingrid22', '123Qwe', '09458209546', 9876543, 'Dentist', 'DIMP', '2023-03-02 05:16:26'),
(24, 'Lea', 'Benitez', 'drleaB_@gmail.com', 'dr_leaB321', '0987', '09784326654', 5463728, 'Dentist', 'DLB', '2023-03-08 02:32:30'),
(26, 'Jurist', 'Pedrola', 'arjuristxyxy@gmail.com', 'jurist2323', 'dasdqe23dsa', NULL, 2136778, 'Dentist', 'DJP', '2023-03-24 06:07:45'),
(28, 'New Doctor', 'Employee', 'arjuristxyxy@gmail.com', 'jtp_3123', '123Qwe', NULL, 1234567, 'Dentist', 'JTP', '2023-09-23 08:41:57'),
(29, 'New Employee', 'Dentist', 'arjuristxyxy@gmail.com', 'dasdda', '123Qwe', NULL, 1234567, 'Dentist', 'BNV', '2023-09-30 01:11:50');

-- --------------------------------------------------------

--
-- Table structure for table `inventory1`
--

CREATE TABLE `inventory1` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `metric` varchar(255) NOT NULL,
  `critical_level` int(11) NOT NULL,
  `common_max_qty` int(11) NOT NULL,
  `last_modified` varchar(255) NOT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventory1`
--

INSERT INTO `inventory1` (`id`, `item_name`, `quantity`, `metric`, `critical_level`, `common_max_qty`, `last_modified`, `last_modified_date`, `created`) VALUES
(1, 'Gloves', 200, 'pcs', 10, 3000, 'admin', '2023-09-27 16:37:37', '2023-09-13 09:13:33'),
(2, 'Mouth Wash', 2000, 'liters', 20, 2000, 'Marion', '2023-09-14 15:01:04', '2023-09-14 05:03:00'),
(3, 'Face Masks', 900, 'pcs', 40, 1000, 'admin', '2023-09-20 14:07:01', '0000-00-00 00:00:00'),
(4, 'Rubbers', 300, 'pcs', 40, 0, 'Marion', '2023-09-14 13:05:59', '0000-00-00 00:00:00'),
(5, 'Index Cards', 299, 'pcs', 30, 1000, 'admin', '2023-09-20 13:59:55', '0000-00-00 00:00:00'),
(6, 'Wires', 180, 'pcs', 20, 1000, 'Marion', '2023-09-15 15:34:23', '0000-00-00 00:00:00'),
(7, 'Disposable Cups', 500, 'pcs', 10, 1000, 'admin', '2023-09-15 15:18:12', '0000-00-00 00:00:00'),
(8, 'Clips', 200, 'pcs', 10, 1000, 'admin', '2023-09-19 14:19:06', '0000-00-00 00:00:00'),
(9, 'Elastics', 199, 'pcs', 20, 1000, 'admin', '2023-09-20 14:58:55', '0000-00-00 00:00:00'),
(13, 'Index Cards', 200, 'pcs', 20, 1000, 'Marion', '2023-10-18 14:26:52', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `dr_ID` int(11) NOT NULL,
  `dr_date` date NOT NULL,
  `dr_patientID` int(211) NOT NULL,
  `dr_procedure` varchar(255) NOT NULL,
  `dr_note` varchar(255) NOT NULL,
  `dr_done` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`dr_ID`, `dr_date`, `dr_patientID`, `dr_procedure`, `dr_note`, `dr_done`) VALUES
(1, '2021-10-01', 16, 'Root Canal Therapy', 'Some Details Here', 'Dr. Denroe'),
(2, '0000-00-00', 16, 'Cleaning', 'Some Details Here', 'Dr. Marie'),
(3, '0000-00-00', 16, 'Cleaning', 'Some Details Here', 'Dr. Reynold'),
(4, '0000-00-00', 16, 'Adjustment', 'Some Details Here', 'Dr. Allan'),
(5, '2023-02-23', 0, 'Tooth Extraction', 'Tooth Decay on tooth 12', 'Dr. Marion'),
(6, '2023-02-23', 0, 'Cleaning', 'Tooth Decay on tooth 12', 'Dr. Lea'),
(7, '2023-02-25', 15, 'Cleaning', 'Tooth Filling on tooth 12', 'Dr. Marion'),
(8, '2023-02-17', 15, 'Tooth Extraction', 'Tooth Decay on tooth 12', 'Dr. Lea'),
(10, '2023-03-04', 16, 'Cleaning 24', 'Tooth Filling on tooth 12', 'Dr. Marion'),
(11, '2023-03-04', 16, 'Cleaning 24', 'Tooth Filling on tooth 12', 'Dr. Marion'),
(12, '2023-03-08', 15, 'Brace Adjustment', '2 brackets lost, dental filling were added', 'Dr. Marion'),
(13, '2023-03-03', 15, 'Tooth Extraction', 'Tooth Decay on tooth 12', 'Dr. Lea Benitez'),
(14, '2023-03-03', 15, 'Tooth Extraction', 'Tooth Decay on tooth 12', 'Dr. Lea Benitez'),
(15, '2023-03-03', 15, 'Tooth Extraction', 'Tooth Decay on tooth 12', 'Dr. Lea Benitez'),
(16, '2023-03-09', 15, 'Cleaning 24', 'Shalalalalalala', 'Dr. Lea Benitez'),
(17, '2023-03-09', 15, 'Brace Adjustment', 'New Bracket Installed on teeth 24', 'Dr. Lea Benitez'),
(18, '2023-03-09', 15, 'Tooth Extraction', 'Tooth Decay on tooth 12', 'Dr. Lea Benitez'),
(19, '2023-03-09', 22, 'Tooth cleaning and extraction', '24 Molar needs RCA on next session', 'Dr. Lea Benitez'),
(20, '2023-03-08', 18, 'Cleaning tooth 24', 'Dental Cavities on teeth 23', 'Dr. Lea Benitez'),
(21, '2023-03-09', 15, 'Tooth Extraction  24', 'Needs to have xray for next session', 'Dr. Lea Benitez'),
(22, '2023-03-24', 15, 'Tooth Molar Extracted', 'Panoramic Xray on tooth 12', 'Dr. Lea Benitez'),
(23, '2023-09-13', 16, 'Teeth Extraction', 'Teeth Extraction', 'Dr. Jurist Pedrola'),
(24, '2023-09-13', 16, 'Teeth Extraction', 'Teeth Extraction', 'Dr. Jurist Pedrola');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) NOT NULL,
  `Age` int(211) NOT NULL,
  `password` varchar(300) DEFAULT NULL,
  `token` varchar(100) NOT NULL,
  `contactno` varchar(11) DEFAULT NULL,
  `posting_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `h_password` varchar(255) DEFAULT NULL,
  `verified` tinyint(4) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `fname`, `lname`, `email`, `birthday`, `Age`, `password`, `token`, `contactno`, `posting_date`, `h_password`, `verified`, `reset_token`, `reset_token_expire`) VALUES
(15, 'Janssen_APC', 'Pedrola', 'janssenpedrola26@gmail.com', 'January 26, 2002', 20, '123456', '', '09994491952', '2023-01-11 07:20:25', '$2y$10$t6BZMeW1ZdfzSnMyQvK9qePybliPH.UmjYrX06WNk26FQ/Y965ULi', 1, 'b438be053a300dd9896abb2f45a2bd7ef44a3ea36692d2ee2b2735faa967a2b4', '2023-09-23 17:20:11'),
(16, 'Ana', 'Santos', 'anasantos@gmail.com', 'April 04, 1997', 26, '123456ASs', '', '09929239211', '2023-01-12 07:52:31', '$2y$10$XIUJldTyfYbMFeeMXgdiPevdrBEHatIhMF7JYbcz9GlnjEyC3wq4S', 0, NULL, NULL),
(18, 'Alex', 'Reyes', 'al@gmail.com', 'September 04, 1964', 59, 'qwere3d', '', '09946751289', '2023-02-22 03:47:53', '$2y$10$Qx4CcEch6h5BAJjkbEh0nucmposURr5oUeKLwpC..mx1x68Fjj0Gi', 0, NULL, NULL),
(19, 'Christine', 'Alegre', 'rocketship@gmail.com', 'May 02, 1975', 47, 'oiuaos99023', '', '09563348787', '2023-02-22 03:47:53', '$2y$10$U1f0QjcwmsUWT7sJ45x4nONvNKuPsOI/R6ayUdfy0N4PX11IJRzGy', 0, NULL, NULL),
(22, 'Michel Anne', 'Zamora', 'mazamora@gmail.com', 'August 29, 2001', 22, 'mzamora0829', '', '0998734561', '2023-02-22 11:01:52', '$2y$10$YHEG6YnnMk11ceE7RWnXVeyJlF.YlMLaKi7AHWVfi7cWDd7VALOUK', 0, NULL, NULL),
(23, 'Sara', 'Snyder', 'sarasnyder@gmail.com', 'April 07, 2000', 23, 'sarasnyder07', '', '09341564782', '2023-02-22 11:01:52', '$2y$10$4NH5TzaeZR6rY5h9Va2u0O0guVK8x4Z5.H4JjkLocNFXTMw5KggbG', 0, NULL, NULL),
(24, 'Frances Shara', 'Delo Santos', 'francesdelosantos@gmail.com', 'March 05, 2002', 21, 'francesshara2002', '', '09984567281', '2023-02-22 11:01:52', '$2y$10$qgzHt5wqVWlJtS0tnNWsJOJffslv0n2uiF4Qvzke0afdU/Hd82Pvi', 0, NULL, NULL),
(25, 'Patrick', 'Dixon', 'patdixon@gmail.com', 'November 11, 1995', 28, 'patrick1995', '', '09644628103', '2023-02-22 11:01:52', '$2y$10$BnGX88Gy1ec/Q7YG.J7bb.fGS0LfRgU4KNetPf2xmfe0vrfB6nmHa', 0, NULL, NULL),
(27, 'Kimberly', 'Bell', 'kimbell@gmail.com', 'August 25, 2000', 23, 'Kimberly2000', '', '09988212731', '2023-02-22 11:01:52', '$2y$10$JXFXeqx8MgSXtsIq/hT4JOGHKUK8lYJS/IQsFoF6MXOmYdic3LsK2', 0, NULL, NULL),
(29, 'Guiler Marion ', 'Regalado ', 'marionregalado20@gmail.com', '', 0, 'Qwerty_12', '', '09982901878', '2023-03-07 08:07:35', '$2y$10$RR57AkTaon.RwhlWSZMN9OVTXZQbJPXMs.6paUjqnI1PdykYeoM9S', 0, NULL, NULL),
(30, 'Patricia ', 'Meltran ', 'plmeltran@student.apc.edu.ph', '', 0, 'Meltran24', '', '09123456789', '2023-03-07 08:07:39', '$2y$10$EgjJ6IAhk8e91TNCyHucqumiPTuxzLQA4j3DcGpqFQU/0JWajyKv6', 0, NULL, NULL),
(31, 'Dexter', 'Reyes', 'dexter24@gmail.com', '', 0, 'Dx123456', '', '09678965213', '2023-03-09 07:31:58', '$2y$10$Tz7KyB2GQ.08NqzVV8cvkOACK05fy0WWMOf6Auy/K17t4hadbbnIy', 0, NULL, NULL),
(32, 'Arthur', 'Santos', 'arthur12@gmail.com', '', 0, 'Asantos123', '', '09929239212', '2023-03-24 05:32:50', '$2y$10$ua6iJsoQFyxo8iuuQvgUzuvX/mrQds.zLxh7Efj8ZtXWhU6Lmy5zm', 0, NULL, NULL),
(33, 'Janssen', 'Pedrola', 'guen@gmail.com', '', 0, 'Olarte123', '', '09678965213', '2023-07-22 04:20:33', '$2y$10$nKgy6plQs45gPpszaMsxVO99nEYnJOhtHrgYOKgw2ckGKlp1utbLW', 0, NULL, NULL),
(34, 'Janssen', 'Pedrola', 'imgreedme@gmail.com', '', 0, '1JCena', '', '09678965213', '2023-09-09 10:43:21', '$2y$10$PlkAJwKbjsejYuRhG76/N.hrthq2P8STmhVrHraE4oE1kHPoi81S.', 0, NULL, NULL),
(35, 'Janssen', 'Pedrola', 'jtpedrolass@student.apc.edu.ph', '', 0, '123Qw_', '', '09678965213', '2023-09-16 01:30:50', '$2y$10$mfxU/AfthoF1sAm3/ZqnK.E67/YuEudS.cW7yvhjrpV5HBHgkuJGm', 0, NULL, NULL),
(36, 'Janssen', 'Pedrola', 'mazamorasampl09@gmail.com', '', 0, '$2y$10$xYO/c7ddxxVdrUOdQiTel.APOaVRxLsib/XWjGlfoYtSeaKlMr7ei', '67914e40e13e92c194745c69a8a04577411f16ba39e68f0385e578985d6d5b3be51d1a4bae83fb9191446cb5be37e3c53c47', '09678965213', '2023-09-21 08:17:18', '$2y$10$xqDMuIc6CXalMOGXm9SfaOMjbahe2TXwyWBvj4b66G1hYq5ejLJXy', 0, NULL, NULL),
(37, 'Janssen', 'Pedrola', 'imgreedsdasme@gmail.com', '', 0, NULL, '43d645c0d7be535dc2ae4152a26e4f965ddf39536ee96724c5aa2a9a67a9642f0e786f7928572e3fe16b43491779a95cd580', '09678965213', '2023-09-21 08:44:24', '$2y$10$rGXQAuUkgHjx9iqakRGBcOkasJBFtEYfkAhALUqHjRMULA.8MzdNa', 0, NULL, NULL),
(57, 'Janssen111', 'Pedrola', 'jtpedrola@student.apc.edu.ph', '', 0, NULL, '70a88bca95b2c503dc399ee098c5af7be5a9752456b88bb803d04d32088830c0ee424d584b9e8320aec17334579f363ea2b9', '09994491952', '2023-09-23 08:15:54', '$2y$10$QlpQpVrzcFHoE5tv1PZZ3eJ1GJU57oldydRX69yQ/sFjZvj7ceyli', 1, NULL, NULL),
(58, 'Janssen', 'Pedrola', 'janssenpedasdrola26@gmail.com', '', 0, '123Qwe', '', '09678965213', '2023-09-29 07:46:23', NULL, 0, NULL, NULL),
(59, 'Janssen', 'Pedrola', 'asdvwe2@gmail.com', '', 0, NULL, 'ee70ea153a70623f6b3798f894ba1dbf31997b52147797964b26a2dd4e06077c1f82dd36d8292aeb7a482387274adec11e3e', '09994491952', '2023-10-18 06:30:41', '$2y$10$FqMTH2DE8nhwNqrxXgF/XuR0DjXn8.RKg1gRCxBJw9XDy0N5Z0PIu', 0, NULL, NULL),
(60, 'Janssensad', 'Pedrola', 'zdfafawq@gmail.com', '', 0, NULL, 'f3fa8735c8fa2d84adf8bcaaaa77c2706fa6495c3c9ab36b1a04aea51dfd291a8d1248d77c1e8513d303ee0dc609ed53dae4', '09678965213', '2023-10-18 06:31:10', '$2y$10$4M5k49NYtmAGOVTrxoMK5O3f2K0vYD52lBiBrbb0ARednX4xy/dgi', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` int(11) NOT NULL,
  `patiendID` int(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `patiendID`, `name`, `type`, `size`, `path`, `caption`, `time_created`) VALUES
(14, 15, 'sample-teethunderbite-after.jpg', 'image/jpeg', 43633, 'uploads/pictures/sample-teethunderbite-after.jpg', '', '2023-03-07 06:45:13'),
(15, 15, 'sample-teethunderbite-after.jpg', 'image/jpeg', 43633, 'uploads/pictures/sample-teethunderbite-after.jpg', 'asas', '2023-03-07 06:45:27'),
(16, 16, '06-10-06smile.jpg', 'image/jpeg', 637678, 'uploads/pictures/06-10-06smile.jpg', 'After Braces', '2023-03-07 06:52:32'),
(17, 15, 'sampleteeth with brace.jpg', 'image/jpeg', 131354, 'uploads/pictures/sampleteeth with brace.jpg', 'Teeth Picture after brace installement', '2023-03-08 11:34:48'),
(18, 15, 'sampleteeth with brace 2.jpg', 'image/jpeg', 916114, 'uploads/pictures/sampleteeth with brace 2.jpg', 'Teeth Picture after brace installement other view', '2023-03-08 11:36:55'),
(19, 22, 'sampleteeth2.jpeg', 'image/jpeg', 29424, 'uploads/pictures/sampleteeth2.jpeg', 'Teeth picture of patient in her first visit', '2023-03-09 05:28:33'),
(20, 18, 'sampleteeth2.jpeg', 'image/jpeg', 29424, 'uploads/pictures/sampleteeth2.jpeg', 'Teeth of patient in first session', '2023-03-09 05:34:48'),
(21, 19, 'sampleteeth2.jpeg', 'image/jpeg', 29424, 'uploads/pictures/sampleteeth2.jpeg', 'First teeth picture in the first session', '2023-03-09 08:02:07'),
(22, 29, 'sampleteethxray.jpg', 'image/jpeg', 28732, 'uploads/pictures/sampleteethxray.jpg', 'Initial X ray', '2023-07-22 00:17:17'),
(23, 29, 'sampleteethxray2.jpg', 'image/jpeg', 612903, 'uploads/pictures/sampleteethxray2.jpg', '2nd X ray after 3rd Month', '2023-07-22 00:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `queueing_list`
--

CREATE TABLE `queueing_list` (
  `queueing_number` int(211) NOT NULL,
  `patient_id` int(211) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `concern` varchar(255) NOT NULL,
  `preffDoctor` varchar(255) NOT NULL,
  `time_arrived` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'On-queued'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `queueing_list`
--

INSERT INTO `queueing_list` (`queueing_number`, `patient_id`, `patient_name`, `contact`, `concern`, `preffDoctor`, `time_arrived`, `status`) VALUES
(1, 15, 'Janssen_APC Pedrola', '09994491952', 'Tooth Extraction', 'Dr. Jurist Pedrola', '0000-00-00 00:00:00', 'Canceled'),
(2, 15, 'Janssen_APC Pedrola', '09994491952', 'Tooth Extraction', 'Dr. Jurist Pedrola', '0000-00-00 00:00:00', 'On-queued'),
(3, 57, 'Janssen111 Pedrola', '09994491952', 'Tooth Extraction', 'Dr. Jurist Pedrola', '0000-00-00 00:00:00', 'On-queued');

-- --------------------------------------------------------

--
-- Table structure for table `queueing_list_priority`
--

CREATE TABLE `queueing_list_priority` (
  `queueing_number` int(211) NOT NULL,
  `patient_id` int(211) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `concern` varchar(255) NOT NULL,
  `preffDoctor` varchar(255) NOT NULL,
  `time_arrived` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'On-queued'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `queueing_num_priority`
--

CREATE TABLE `queueing_num_priority` (
  `id` int(6) NOT NULL,
  `queue_number` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `queueing_num_priority`
--

INSERT INTO `queueing_num_priority` (`id`, `queue_number`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `queue_num`
--

CREATE TABLE `queue_num` (
  `id` int(6) UNSIGNED NOT NULL,
  `queue_number` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `queue_num`
--

INSERT INTO `queue_num` (`id`, `queue_number`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `s_payment`
--

CREATE TABLE `s_payment` (
  `s_payID` int(11) NOT NULL,
  `s_date` date NOT NULL,
  `s_patiendID` int(11) NOT NULL,
  `s_procedure` varchar(255) NOT NULL,
  `s_total` int(255) NOT NULL,
  `s_amount` int(255) DEFAULT NULL,
  `dentist_assigned_ID` int(11) NOT NULL,
  `dentist_assigned` varchar(255) NOT NULL,
  `added_by` varchar(25) NOT NULL,
  `s_modify` varchar(255) NOT NULL,
  `s_balance` int(11) GENERATED ALWAYS AS (`s_total` - `s_amount`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `s_payment`
--

INSERT INTO `s_payment` (`s_payID`, `s_date`, `s_patiendID`, `s_procedure`, `s_total`, `s_amount`, `dentist_assigned_ID`, `dentist_assigned`, `added_by`, `s_modify`) VALUES
(44, '2023-09-23', 15, 'Tooth Extraction', 500, 400, 24, 'Lea Benitez', 'Lea Benitez', 'Marion Regalado'),
(45, '2023-09-29', 15, 'Tooth Extraction', 0, 500, 0, 'Gerald Giba', 'Marion Regalado', 'Marion Regalado'),
(46, '2023-09-29', 15, 'Tooth Extraction', 500, 500, 21, 'Ingrid May Pedrola', 'Ingrid May Pedrola', 'Marion Regalado'),
(47, '2023-09-29', 15, 'Tooth Extraction', 0, 500, 21, 'Ingrid May Pedrola', 'Marion Regalado', 'Marion Regalado'),
(48, '2023-09-29', 15, 'Tooth Extraction', 0, 200, 0, 'Gerald Giba', 'Marion Regalado', 'Marion Regalado'),
(49, '2023-09-30', 15, 'Tooth Fillings', 0, 200, 27, 'Ivan Emmanuel Flores', 'Marion Regalado', 'Marion Regalado'),
(50, '2023-09-29', 15, 'Tooth Extraction on Molar', 800, 800, 21, 'Ingrid May Pedrola', 'Ingrid May Pedrola', 'Marion Regalado'),
(51, '2023-09-30', 15, 'Tooth Fillings', 0, 1000, 0, 'Ingrid May Pedrola', 'Marion Regalado', 'Marion Regalado'),
(52, '2023-09-29', 15, 'Tooth Extraction', 0, -1, 0, 'Ingrid May Pedrola', 'Marion Regalado', 'Marion Regalado'),
(53, '2023-09-13', 15, 'Teeth Extraction', 500, NULL, 25, 'Jurist Pedrola', 'Jurist Pedrola', ''),
(54, '2023-09-30', 15, 'Tooth Extraction', 500, NULL, 21, 'Ingrid May Pedrola', 'Ingrid May Pedrola', ''),
(55, '2023-10-14', 15, 'Tooth Fillings', 500, 300, 25, 'Jurist Pedrola', 'Jurist Pedrola', 'Marion Regalado');

-- --------------------------------------------------------

--
-- Table structure for table `xray`
--

CREATE TABLE `xray` (
  `id` int(11) NOT NULL,
  `patiendID` int(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `timeCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `xray`
--

INSERT INTO `xray` (`id`, `patiendID`, `name`, `type`, `size`, `path`, `caption`, `timeCreated`) VALUES
(2, 15, 'samplexray.jpg', 'image/jpeg', 5832, 'uploads/xrays/samplexray.jpg', 'Panoramic xray taken March 06 before braces', '2023-03-07 07:22:25'),
(3, 15, 'sidexray.jpg', 'image/jpeg', 14492, 'uploads/xrays/sidexray.jpg', 'Side Xray of patient', '2023-03-07 07:27:27'),
(4, 15, 'sampleteeth xray.jpg', 'image/jpeg', 347844, 'uploads/xrays/sampleteeth xray.jpg', 'New xray for broken jaw', '2023-03-08 11:38:14'),
(5, 18, 'sampleteeth xray.jpg', 'image/jpeg', 347844, 'uploads/xrays/sampleteeth xray.jpg', 'Teeth Xray', '2023-03-09 01:57:06'),
(6, 15, 'sampleperiapical xray.jpg', 'image/jpeg', 5212, 'uploads/xrays/sampleperiapical xray.jpg', 'Periapical Xray of on tooth 25', '2023-03-09 05:01:50'),
(7, 22, 'sampleteeth xray.jpg', 'image/jpeg', 347844, 'uploads/xrays/sampleteeth xray.jpg', 'Full teeth xray of patient', '2023-03-09 05:29:26'),
(8, 19, 'sampleteeth xray.jpg', 'image/jpeg', 347844, 'uploads/xrays/sampleteeth xray.jpg', 'Xray of patient in first session', '2023-03-09 08:02:25'),
(9, 15, 'Sample Teeth xray.jpg', 'image/jpeg', 280942, 'uploads/xrays/Sample Teeth xray.jpg', 'March 24 2023 Xray picture', '2023-03-24 05:56:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `d_calendar`
--
ALTER TABLE `d_calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `inventory1`
--
ALTER TABLE `inventory1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`dr_ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queueing_list`
--
ALTER TABLE `queueing_list`
  ADD PRIMARY KEY (`queueing_number`);

--
-- Indexes for table `queueing_list_priority`
--
ALTER TABLE `queueing_list_priority`
  ADD PRIMARY KEY (`queueing_number`);

--
-- Indexes for table `queueing_num_priority`
--
ALTER TABLE `queueing_num_priority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queue_num`
--
ALTER TABLE `queue_num`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_payment`
--
ALTER TABLE `s_payment`
  ADD PRIMARY KEY (`s_payID`);

--
-- Indexes for table `xray`
--
ALTER TABLE `xray`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `d_calendar`
--
ALTER TABLE `d_calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `inventory1`
--
ALTER TABLE `inventory1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `dr_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `queueing_list`
--
ALTER TABLE `queueing_list`
  MODIFY `queueing_number` int(211) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `queueing_list_priority`
--
ALTER TABLE `queueing_list_priority`
  MODIFY `queueing_number` int(211) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queueing_num_priority`
--
ALTER TABLE `queueing_num_priority`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `queue_num`
--
ALTER TABLE `queue_num`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `s_payment`
--
ALTER TABLE `s_payment`
  MODIFY `s_payID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `xray`
--
ALTER TABLE `xray`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
