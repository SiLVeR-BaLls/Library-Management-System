-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 08:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `ID` int(11) NOT NULL,
  `IDno` varchar(50) NOT NULL,
  `TIMEIN` time DEFAULT NULL,
  `TIMEOUT` time DEFAULT NULL,
  `LOGDATE` date NOT NULL,
  `STATUS` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`ID`, `IDno`, `TIMEIN`, `TIMEOUT`, `LOGDATE`, `STATUS`) VALUES
(25, '2020-7656-A', '08:05:11', NULL, '2024-11-23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `book_id` int(11) NOT NULL,
  `B_title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `edition` varchar(255) DEFAULT NULL,
  `LCCN` varchar(255) DEFAULT NULL,
  `ISBN` varchar(255) DEFAULT NULL,
  `ISSN` varchar(255) DEFAULT NULL,
  `MT` varchar(255) DEFAULT NULL,
  `ST` varchar(255) DEFAULT NULL,
  `journal` varchar(255) NOT NULL,
  `place` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `Pdate` date DEFAULT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  `extent` varchar(255) DEFAULT NULL,
  `Odetail` text DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `UTitle` varchar(255) DEFAULT NULL,
  `VForm` varchar(255) DEFAULT NULL,
  `SUTitle` varchar(255) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `B_title`, `subtitle`, `author`, `edition`, `LCCN`, `ISBN`, `ISSN`, `MT`, `ST`, `journal`, `place`, `publisher`, `Pdate`, `copyright`, `extent`, `Odetail`, `size`, `url`, `Description`, `UTitle`, `VForm`, `SUTitle`, `volume`, `note`, `photo`) VALUES
(1, 'adarna ', 'the bird', 'JOSE', '1', '121212', '112112', '122122', 'book', 'LargePrint', '', 'dono', 'la ko maan', '2024-01-01', '2021', '1 page', 'bahu', '2mm', 'https://coolors.co/f0d3f7-b98ea7-a57982-302f4d-120d31', 'hallow', 'Utilte', 'Vform', 'Sutitle', 10, NULL, '6756c35a12551_windows.jpg'),
(2, 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Batayan sa pagpapakuhulugan ng wika', 'Louie Jane L. Camorahan', '', '', '', '', '', 'Hardcover', '', 'Iloilo City : Iloilo Science and Technology University, 2023.', '', '0000-00-00', '2023', ' 87 p', '', '27 cm.', NULL, NULL, '', '', '', 0, NULL, NULL),
(3, 'Impluwensiya ng youtube sa pag-aaral ng panitikan ng mga BSED Filipino ng ISAT U', '', 'Dayna C. Cabaya', '', '', '', '', '', 'Not Assigned', '', 'Iloilo City : Iloilo Science and Technology University, 2023.', '', '2023-11-04', '2023', '71 p', '', '27 cm.', NULL, NULL, '', '', '', 0, NULL, NULL),
(4, 'Bobot', 'an interactive Filipino sign language app ', 'Enrico Augusto Alagao', '', '', '', '', '', 'Not Assigned', '', '', '', '2023-11-04', '2023', '104 p', '', ' 27 cm', NULL, NULL, '', '', '', 0, NULL, '67397190a70cb_default.jpg'),
(5, 'Practical english usage', '', 'Radhika', '', '', '978-9-39233349-1', '', '', 'Not Assigned', '', 'New Delhi, India', 'Paradise Press,', '2023-11-04', ' 2023', '317 pages', '', ' 23 cm', NULL, NULL, '', '', '', 0, NULL, NULL),
(6, 'Essential english grammar', '', 'Mathew Stephen.', '', '', '', '', '', 'Not Assigned', '', 'New Delhi', 'Wisdom Press', '2023-11-04', '2023', '250 pages.', '', '', NULL, NULL, '', '', '', 0, NULL, NULL),
(7, 'English reading proficiency in comprehension of grade VI pupils in Jalandoni Memorial Elementary School ', '', 'JV Demberly V. Gorantes', '', '', '', '', '', 'Not Assigned', '', 'Iloilo City', 'Iloilo Science and Technology University', '2024-11-04', '2023', '75 p', '', '27 cm', '', '', '', '', '', 0, NULL, '67911bd971cf5_gold_wheat_.png'),
(8, 'Iloilo sports division athletes performance monitoring system', '', 'Claredy G. Gelloani', '', '', '', '', '', 'Not Assigned', '', 'Iloilo City', 'Iloilo Science and Technology University', '0000-00-00', '2024', '191 p', '', '27 cm.', NULL, NULL, '', '', '', 0, NULL, NULL),
(9, 'Human resource information system', '', 'Reneboy Moritcho Jr.', '', '', '', '', '', 'Not Assigned', '', 'Iloilo City', 'Iloilo Science and Technology University', '2024-11-04', '2024', '151 p', '', '27 cm.', NULL, NULL, '', '', '', 0, NULL, NULL),
(10, 'Algorithms ', 'Design and Analysis', 'Bruce Collins', '', '', '', '', '', 'Not Assigned', '', 'USA ', 'American Academic Publisher', '2024-12-07', '2024', '319p', 'llustrations; pic. (b&w)', '25 cm', NULL, NULL, '', '', '', 0, NULL, '6739710e7371f_ID-Card (10).png'),
(11, 'Computing essentials', 'making IT work for you: Introductory 2023', 'Timothy J. O\'Leary', '', '', '978-1-26526321-8', '', '', 'Not Assigned', '', 'New York', 'The McGraw-Hill Companies', '0000-00-00', '2023', '27.5 cm', '', '390pages', NULL, NULL, '', '', '', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `book_copies`
--

CREATE TABLE `book_copies` (
  `book_copy_ID` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_copy` varchar(50) NOT NULL,
  `copy_ID` varchar(50) NOT NULL,
  `B_title` varchar(255) NOT NULL,
  `status` enum('Available','Borrowed','Reserve') DEFAULT NULL,
  `callNumber` varchar(50) DEFAULT NULL,
  `circulationType` varchar(50) DEFAULT NULL,
  `dateAcquired` date DEFAULT NULL,
  `description1` text DEFAULT NULL,
  `description2` text DEFAULT NULL,
  `description3` text DEFAULT NULL,
  `number1` int(11) DEFAULT NULL,
  `number2` int(11) DEFAULT NULL,
  `number3` int(11) DEFAULT NULL,
  `sublocation` varchar(100) DEFAULT NULL,
  `vendor` varchar(100) DEFAULT NULL,
  `fundingSource` varchar(100) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `note` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_copies`
--

INSERT INTO `book_copies` (`book_copy_ID`, `book_id`, `book_copy`, `copy_ID`, `B_title`, `status`, `callNumber`, `circulationType`, `dateAcquired`, `description1`, `description2`, `description3`, `number1`, `number2`, `number3`, `sublocation`, `vendor`, `fundingSource`, `rating`, `note`) VALUES
(768, 1, 'BOOK0000005', '1', 'adarna ', 'Available', '1', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 3, ''),
(770, 1, 'BOOK0000007', '1', 'adarna ', 'Borrowed', '1222222', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section2', 'DTFOS Bookstore1', 'Purchased1', 2, 'helloqwe2'),
(771, 1, 'BOOK0000008', '1', 'adarna ', 'Borrowed', '1', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 2, ''),
(775, 1, 'BOOK0000012', '1', 'adarna ', 'Available', '1', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 2, ''),
(783, 10, 'BOOK0000013', 'q', 'Algorithms ', 'Borrowed', 'q', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 5, ''),
(784, 10, 'BOOK0000014', 'q', 'Algorithms ', 'Available', 'q', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 3, ''),
(785, 10, 'BOOK0000015', 'q', 'Algorithms ', 'Borrowed', 'q', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 1, ' '),
(786, 10, 'BOOK0000016', 'q', 'Algorithms ', 'Available', 'q', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 0, ' '),
(787, 10, 'BOOK0000017', 'q', 'Algorithms ', 'Available', 'q', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 5, ''),
(788, 10, 'BOOK0000018', 'q', 'Algorithms ', 'Available', 'q', 'General Circulation', '2025-01-21', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 5, ''),
(790, 7, 'BOOK0000020', 'ISATU000031287', 'English reading proficiency in comprehension of grade VI pupils in Jalandoni Memorial Elementary School ', 'Available', 'Fil-R 0803 C185 2023', 'Reference', '2025-03-13', '', '', '', 0, 0, 0, 'Technical Section', 'DTFOS Bookstore', 'Purchased', 4, 'pang hawan'),
(800, 6, 'BOOK0000030', 'ISATUMC0013175', 'Essential english grammar', 'Available', '428.24 R128 2023', 'Reference', '2025-03-14', '', '', '', 0, 0, 0, '1', '1', '2', 5, 'none '),
(801, 6, 'BOOK0000031', 'ISATUMC0013175', 'Essential english grammar', 'Available', '428.24 R128 2023', 'Reference', '2025-03-14', '', '', '', 0, 0, 0, '1', '1', '2', 5, 'none '),
(802, 6, 'BOOK0000032', 'ISATUMC0013175', 'Essential english grammar', 'Available', '428.24 R128 2023', 'Reference', '2025-03-14', '', '', '', 0, 0, 0, '1', '1', '2', 5, 'none '),
(803, 6, 'BOOK0000033', 'ISATUMC0013175', 'Essential english grammar', 'Available', '428.24 R128 2023', 'Reference', '2025-03-14', '', '', '', 0, 0, 0, '1', '1', '2', 5, 'none '),
(804, 4, 'BOOK0000034', '123456789', 'Bobot', 'Available', '09876543214', 'General Circulation', '1990-12-23', '', '', '', 0, 0, 0, '3', 'ad', 'hi', 5, ''),
(805, 4, 'BOOK0000035', '123456789', 'Bobot', 'Available', '09876543214', 'General Circulation', '1990-12-23', '', '', '', 0, 0, 0, '3', 'ad', 'hi', 5, ''),
(806, 4, 'BOOK0000036', '123456789', 'Bobot', 'Available', '09876543214', 'General Circulation', '1990-12-23', '', '', '', 0, 0, 0, '3', 'ad', 'hi', 5, '');

--
-- Triggers `book_copies`
--
DELIMITER $$
CREATE TRIGGER `before_insert_book_copies` BEFORE INSERT ON `book_copies` FOR EACH ROW BEGIN
  -- Declare a variable to store the next book_copy
  DECLARE next_id INT;

  -- Find the maximum current book_copy number and increment it
  SELECT IFNULL(MAX(CAST(SUBSTRING(book_copy, 5) AS UNSIGNED)), 0) + 1 INTO next_id
  FROM `book_copies`;

  -- Set the book_copy value in the desired format
  SET NEW.book_copy = CONCAT('BOOK', LPAD(next_id, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_book`
--

CREATE TABLE `borrow_book` (
  `borrow_id` int(11) NOT NULL,
  `IDno` varchar(11) NOT NULL,
  `book_copy` varchar(50) NOT NULL,
  `ID` int(11) NOT NULL,
  `borrow_date` datetime NOT NULL DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_book`
--

INSERT INTO `borrow_book` (`borrow_id`, `IDno`, `book_copy`, `ID`, `borrow_date`, `return_date`, `due_date`) VALUES
(203, '2021-8764-D', 'BOOK0000007', 0, '2025-01-10 03:05:42', '2025-03-15 02:20:13', '2025-01-10'),
(204, '2021-1235-A', 'BOOK0000012', 0, '2025-01-10 03:07:14', '2025-03-24 10:27:39', '2025-01-14'),
(207, 'admin1', 'BOOK0000008', 0, '2025-01-10 14:08:41', '2025-03-03 08:30:26', '2025-04-10'),
(208, '2021-3645-A', 'BOOK0000005', 0, '2025-01-10 14:09:51', '2025-03-24 14:37:40', '2025-01-15'),
(212, '2021-4375-A', 'BOOK0000005', 0, '2025-01-11 08:58:10', '2025-03-24 14:37:40', '2025-01-15'),
(213, 'librarian1', 'BOOK0000008', 0, '2025-01-11 09:11:05', '2025-03-03 08:30:26', '2025-04-11'),
(214, 'librarian1', 'BOOK0000007', 0, '2025-01-11 09:12:40', '2025-03-15 02:20:13', '2025-04-11'),
(215, '2021-8724-A', 'BOOK0000005', 0, '2025-01-11 09:18:31', '2025-03-24 14:37:40', '2025-01-15'),
(217, '2021-0909-A', 'BOOK0000008', 0, '2025-01-11 09:20:22', '2025-03-03 08:30:26', '2025-01-15'),
(218, '2021-0909-A', 'BOOK0000008', 0, '2025-01-11 09:25:33', '2025-03-03 08:30:26', '2025-01-15'),
(219, '2021-8724-A', 'BOOK0000012', 0, '2025-01-11 09:26:29', '2025-03-24 10:27:39', '2025-01-15'),
(220, '2021-7654-A', 'BOOK0000008', 0, '2025-01-11 09:28:37', '2025-03-03 08:30:26', '2025-01-15'),
(221, '2021-1235-A', 'BOOK0000005', 0, '2025-01-11 09:50:41', '2025-03-24 14:37:40', '2025-01-15'),
(222, 'librarian1', 'BOOK0000007', 0, '2025-01-11 09:51:38', '2025-03-15 02:20:13', '2025-04-11'),
(223, 'librarian1', 'BOOK0000012', 0, '2025-01-11 09:52:11', '2025-03-24 10:27:39', '2025-04-11'),
(224, 'librarian1', 'BOOK0000005', 0, '2025-01-11 09:53:29', '2025-03-24 14:37:40', '2025-04-11'),
(226, '2021-8724-A', 'BOOK0000012', 0, '2025-01-12 02:11:21', '2025-03-24 10:27:39', '2025-01-15'),
(227, 'librarian1', 'BOOK0000007', 0, '2025-01-12 02:11:37', '2025-03-15 02:20:13', '2025-04-11'),
(230, 'librarian1', 'BOOK0000014', 0, '2025-01-13 22:43:14', '2025-03-12 16:27:43', '2025-04-13'),
(232, 'librarian1', 'BOOK0000016 ', 0, '2025-01-13 23:27:18', '2025-03-24 10:27:41', '2025-04-13'),
(233, 'librarian1', 'BOOK0000017 ', 0, '2025-01-13 23:27:18', '2025-03-03 08:30:37', '2025-04-13'),
(235, 'librarian1', 'BOOK0000005', 0, '2025-01-13 23:32:50', '2025-03-24 14:37:40', '2025-04-13'),
(236, 'librarian1', 'BOOK0000007 ', 0, '2025-01-13 23:33:08', '2025-03-15 02:20:13', '2025-04-13'),
(237, 'librarian1', 'BOOK0000008 ', 0, '2025-01-13 23:33:08', '2025-03-03 08:30:26', '2025-04-13'),
(238, 'librarian1', 'BOOK0000012', 0, '2025-01-13 23:39:59', '2025-03-24 10:27:39', '2025-04-13'),
(239, 'librarian1', 'BOOK0000013', 0, '2025-01-13 23:40:15', '2025-03-03 08:30:29', '2025-04-13'),
(243, 'librarian1', 'BOOK0000016', 0, '2025-01-13 23:44:45', '2025-03-24 10:27:41', '2025-04-13'),
(244, 'librarian1', 'BOOK0000017', 0, '2025-01-13 23:46:43', '2025-03-03 08:30:37', '2025-04-13'),
(245, 'librarian1', 'BOOK0000018', 0, '2025-01-13 23:47:16', '2025-03-03 08:30:42', '2025-04-13'),
(246, '2021-7654-A', 'BOOK0000005 ', 0, '2025-01-13 23:50:17', '2025-03-24 14:37:40', '2025-01-16'),
(247, '2021-7654-A', 'BOOK0000007 ', 0, '2025-01-13 23:50:17', '2025-03-15 02:20:13', '2025-01-16'),
(248, '2021-7654-A', 'BOOK0000015 ', 0, '2025-01-13 23:50:17', '2025-03-24 10:26:03', '2025-01-16'),
(251, '2021-7654-A', 'BOOK0000013', 0, '2025-01-13 23:58:20', '2025-03-03 08:30:29', '2025-01-16'),
(252, '2021-1235-A', 'BOOK0000014', 0, '2025-01-14 00:10:31', '2025-03-12 16:27:43', '2025-01-16'),
(253, 'librarian1', 'BOOK0000016', 0, '2025-01-14 00:12:10', '2025-03-24 10:27:41', '2025-04-13'),
(254, 'librarian1', 'BOOK0000017', 0, '2025-01-14 00:22:38', '2025-03-03 08:30:37', '2025-04-13'),
(255, 'librarian1', 'BOOK0000018', 0, '2025-01-14 00:25:57', '2025-03-03 08:30:42', '2025-04-13'),
(256, 'librarian1', 'BOOK0000005', 0, '2025-01-14 00:32:55', '2025-03-24 14:37:40', '2025-04-13'),
(257, 'librarian1', 'BOOK0000007 ', 0, '2025-01-14 00:33:19', '2025-03-15 02:20:13', '2025-04-13'),
(258, 'librarian1', 'BOOK0000008 ', 0, '2025-01-14 00:33:19', '2025-03-03 08:30:26', '2025-04-13'),
(263, '2021-8733-A', 'BOOK0000016', 0, '2025-01-14 00:55:19', '2025-03-24 10:27:41', '2025-01-16'),
(269, 'admin1', 'BOOK0000014', 0, '2025-01-14 01:01:30', '2025-03-12 16:27:43', '2025-04-13'),
(270, '2021-8764-D', 'BOOK0000015', 0, '2025-01-14 01:02:00', '2025-03-24 10:26:03', '2025-01-16'),
(274, 'librarian1', 'BOOK0000007', 0, '2025-01-14 01:10:36', '2025-03-15 02:20:13', '2025-04-13'),
(276, 'librarian1', 'BOOK0000013', 0, '2025-03-02 22:37:01', '2025-03-03 08:30:29', '2025-06-02'),
(277, 'admin1', 'BOOK0000012', 0, '2025-03-03 08:26:27', '2025-03-24 10:27:39', '2025-06-03'),
(278, 'admin1', 'BOOK0000012', 0, '2025-03-03 08:40:29', '2025-03-24 10:27:39', '2025-06-03'),
(281, 'librarian1', 'BOOK0000014', 0, '2025-03-12 10:59:50', '2025-03-12 16:27:43', '2025-06-12'),
(282, 'librarian1', 'BOOK0000007', 0, '2025-03-15 02:19:56', '2025-03-15 02:20:13', '2025-06-14'),
(287, '2021-4375-A', 'BOOK0000020 ', 0, '2025-03-24 10:25:52', '2025-03-24 10:26:06', '2025-03-27'),
(291, 'admin1', 'BOOK0000007', 0, '2025-03-24 13:09:43', NULL, '2025-06-24'),
(292, 'admin1', 'BOOK0000015', 0, '2025-03-24 13:09:43', NULL, '2025-06-24'),
(293, '2021-3829-A', 'BOOK0000013', 0, '2025-03-27 12:51:41', NULL, '2025-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `coauthor`
--

CREATE TABLE `coauthor` (
  `co_author_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `Co_Name` varchar(255) DEFAULT NULL,
  `Co_Date` date DEFAULT NULL,
  `Co_Role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `from_user_id` varchar(11) NOT NULL,
  `to_user_id` varchar(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `from_user_id`, `to_user_id`, `message`, `timestamp`, `is_read`) VALUES
(47, '2024-4536-A', 'admin1', 'hi lle', '2025-03-04 05:26:04', 1),
(48, 'admin1', '2024-4536-A', 'can it be?', '2025-03-04 05:26:22', 1),
(49, 'admin1', '2024-4536-A', 'huy', '2025-03-10 06:12:41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `background_color` varchar(10) DEFAULT '#ffffff',
  `text_color1` varchar(10) DEFAULT '#000000',
  `button_color` varchar(10) DEFAULT '#007bff',
  `header_color` varchar(10) DEFAULT '#333333',
  `footer_color` varchar(10) DEFAULT '#333333',
  `sidebar_color` varchar(10) DEFAULT '#f8f9fa',
  `button_hover_color` varchar(10) DEFAULT '#0056b3',
  `button_active_color` varchar(10) DEFAULT '#003366',
  `text_color2` varchar(10) DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `logo`, `background_color`, `text_color1`, `button_color`, `header_color`, `footer_color`, `sidebar_color`, `button_hover_color`, `button_active_color`, `text_color2`) VALUES
(10, 'logo_67d7aee16a20e4.40671656.png', '#f2f2f2', '#ffffff', '#1905a3', '#f5d000', '#f5d000', '#1905a3', '#0056b3', '#2c67ba', '#ffffff');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `Sub_Head` varchar(255) DEFAULT NULL,
  `Sub_Head_input` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `IDno` varchar(11) NOT NULL,
  `Fname` varchar(50) DEFAULT NULL,
  `Sname` varchar(50) DEFAULT NULL,
  `Mname` varchar(50) DEFAULT NULL,
  `Ename` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `college` varchar(100) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `yrLVL` varchar(50) DEFAULT NULL,
  `A_LVL` varchar(11) DEFAULT NULL,
  `personnel_type` enum('Teaching Personnel','Non-Teaching Personnel') DEFAULT NULL,
  `status_details` enum('active','inactive','restricted') DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `U_Type` enum('admin','student','staff','librarian','faculty') DEFAULT NULL,
  `status_log` enum('pending','approved','rejected') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`IDno`, `Fname`, `Sname`, `Mname`, `Ename`, `gender`, `photo`, `email`, `contact`, `municipality`, `barangay`, `province`, `DOB`, `college`, `course`, `yrLVL`, `A_LVL`, `personnel_type`, `status_details`, `username`, `password`, `U_Type`, `status_log`) VALUES
('', '', '', '', 'N/A', '', NULL, '', '', '', '', '', '0000-00-00', '', '', '', '3', '', 'inactive', '', '', 'admin', 'rejected'),
('12', '1', '1', '1', 'N/A', '', NULL, '1@gm.dc', '11111111111', '', '', '', '2024-12-06', 'cas', '', '', '3', 'Non-Teaching Personnel', 'active', '23', '23', 'faculty', 'pending'),
('1211-3223-d', '1', '1', '1', 'N/A', '', NULL, 'Newser@gmail.com', '11111111111', '', '', '', '2024-12-17', 'cea', '', '', '3', '', 'active', 'hellpme', '22#@DEdd', '', 'pending'),
('1q', '1', '1', '1', 'N/A', '', NULL, '1@gm.dc', '11111111111', '', '', '', '2024-12-26', 'cas', '', '', '3', '', 'active', '1', '12', 'student', 'pending'),
('1test1', '1test', '1test', '1test', 'N/A', '', NULL, 'vonjohnsuropia116@gmail.com', '42534656733', '1testm', '1testb', '1testp', '2024-12-28', 'cas', 'BS in Math', '5 C', '3', '', 'active', '1test!AA', '1test!AA', 'student', 'pending'),
('1test112', '1test', '1test', '1test', 'N/A', '', NULL, 'vonjohnsuropia116@gmail.com', '42534656733', '1testm', '1testb', '1testp', '2024-12-28', 'cea', '', '5 C', '3', 'Non-Teaching Personnel', 'active', '1test!AA', '1test!AA', 'student', 'pending'),
('1test1121', '1test', '1test', '1test', 'N/A', '', NULL, 'vonjohnsuropia116@gmail.com', '42534656733', '1testm', '1testb', '1testp', '2024-12-28', 'cea', '', '5 C', '3', 'Teaching Personnel', 'active', '1test!AA', '1test!AA', 'student', 'pending'),
('2', '2', '2', '2', 'N/A', '', NULL, '2222@d.dd', '22111111111', '1', '1', '1', '2024-12-05', 'cea', '', '', '3', '', 'active', '2', '2@Eddddd', 'student', 'pending'),
('2002021', '1', '1', '1', 'N/A', '', NULL, '1@gm.dc', '11111111111', '11', '1', '1', '2024-12-28', 'cea', '', '', '3', 'Teaching Personnel', 'active', '22', '22', 'faculty', 'pending'),
('2020-7656-A', 'Jane', 'Fanthear', 'Ban', 'N/A', 'Male', NULL, 'Jane@gmail.com', '09046789101', 'Ivisan Capiz', 'Agcabugao,Cuartero', 'Iloilo', '2001-07-11', 'cit', 'BS in Auto Tech (BSAT) - Level III', '2 B', '3', NULL, 'active', 'Jane34', 'Jane34', 'student', 'rejected'),
('2020-9452-A', 'Yano', 'Tachibana', 'Yamamoshi', 'N/A', 'f', NULL, 'Yano@gmail.com', '09956789101', 'Pavia', 'Cabugao Sur', 'Iloilo', '2008-02-28', 'coe', 'BEEd ', '5 C', '3', NULL, 'active', 'Yano56', 'Yano56', 'student', 'approved'),
('2021-0909-A', 'Kenley', 'Tachibana', 'L', 'N/A', 'm', NULL, 'Kenley24@gmail.com', '09709543216', 'Jaro', 'San Jose', 'Iloilo', '2001-11-11', 'cit', 'BS in Elec Eng (BSELX) - Level III', '3 D', '3', NULL, 'active', 'Kenley753', 'Kenley753', 'student', 'approved'),
('2021-0994-A', 'William', 'Dickhead', 'T', 'N/A', 'm', NULL, 'William3@gmail.com', '09786543219', 'Pavia', 'Zone1', 'Iloilo', '2001-08-09', 'cit', 'BS in Hotel & Rest Tech (BSHRT) - Level II', '4 B', '3', NULL, 'active', 'William12', 'William12', 'student', 'rejected'),
('2021-1234-A', 'Allah', 'Muhhamad', 'D', 'N/A', 'm', NULL, 'allah1@gmail.com', '09956789101', 'Santa Barbara', 'Burgos st.', 'Iloilo', '2001-06-12', 'cas', 'BS in Comm Dev', '3 C', '3', NULL, 'active', 'allah123', 'allah123', 'student', 'rejected'),
('2021-1235-A', 'Brooke', 'Arbobro', 'L', 'N/A', 'm', NULL, 'Brooke09@gmail.com', '09709543216', 'Lapaz', 'San Jose', 'Iloilo', '2014-08-14', 'cas', 'BS in Info Systems', '3 A', '3', NULL, 'active', 'Brooke098', 'Brooke098', 'student', 'approved'),
('2021-3185-A', '1', '1', '1', 'N/A', '', NULL, 'vonjohn.suropia@students.isatu.edu.ph', '09876564521', '1', '', '', '2024-12-14', 'cas', 'BS in Human Services', '4 B', '3', '', 'active', '@edadDghg', '@edadDghg', 'student', 'pending'),
('2021-3645-A', 'Noah', 'Muller', 'V', 'N/A', 'm', NULL, 'Noah1234@gmail.com', '09456789101', 'Cabatuan', 'Danao', 'Iloilo', '2002-08-02', 'cea', 'BS in Architecture', '4 A', '3', NULL, 'active', 'Noah09133', 'Noah09133', 'student', 'approved'),
('2021-3737-A', 'Elizabeth', 'Claus', 'K', 'N/A', 'f', NULL, 'Elizabeth38@gmail.com', '09786543219', 'Mohon', 'Zone1', 'Iloilo', '2001-11-07', 'cas', 'BS in Info Tech', '4 B', '3', NULL, 'active', 'Elizabeth87', 'Elizabeth87', 'student', 'rejected'),
('2021-3829-A', 'Jimmie ', 'Hechanova', 'Susmiran', 'N/A', '', NULL, 'jimmie.hechanova@students.isatu.edu.ph', '09127703571', 'Santa Barbara', 'Talongadian', 'Iloilo', '2002-11-26', 'cas', 'BS in Info Tech', '4 D', '3', '', 'active', 'Jimmie', '2021-3829-A', 'student', 'approved'),
('2021-4375-A', 'Jane Girl', 'Rock', 'G', 'N/A', 'f', NULL, 'JaneGirl@gmail.com', '09956789101', 'Cabatuan', 'Cabugao Sur', 'Iloilo', '2001-08-25', 'cea', 'BS in Elec Eng (ECE)', '4 D', '3', NULL, 'active', 'JaneGirl7', 'JaneGirl7', 'student', 'approved'),
('2021-4653-A', 'Philip', 'Grey', 'F', 'N/A', 'm', NULL, 'Philip14@gmail.com', '09709543216', 'Cabatuan', 'Bolong Este', 'Iloilo', '2003-08-06', 'coe', 'BS in Ind Ed', '4 C', '3', NULL, 'active', 'Philip88', 'Philip88', 'student', 'approved'),
('2021-4785-A', 'Edgar', 'Gomez', 'B', 'N/A', 'm', NULL, 'Edgar146@gmail.com', '09709543216', 'Pavia', 'Sambag', 'Iloilo', '2002-04-21', 'cea', 'BS in Elec Eng', '4 B', '3', NULL, 'active', 'Edgar109', 'Edgar109', 'student', 'rejected'),
('2021-5630-A', 'Anna', 'Thomas', 'X', 'N/A', 'Male', NULL, 'Anna74@gmail.com', '09456789101', 'Jaro', 'Zone1', 'Iloilo', '2001-05-29', 'coe', 'BTVTEd', '4 D', '3', NULL, 'active', 'Anna1265', 'Anna1265', 'student', 'rejected'),
('2021-7432-A', 'Jeremy', 'Grime', 'M', 'N/A', 'm', NULL, 'Jeremy74@gmail.com', '09786543219', 'Lapaz', 'Burgos st.', 'Iloilo', '2002-07-05', 'cea', 'BS in Mech Eng', '4 A', '3', NULL, 'active', 'Jeremy744', 'Jeremy744', 'student', 'pending'),
('2021-7654-A', 'Benjamin', 'Brown', 'S', 'N/A', 'm', NULL, 'Benjamin1@gmail.com', '09709543216', 'Jaro', 'San Jose', 'Iloilo', '2001-11-30', 'cea', 'BS in Civil Eng', '4 D', '3', NULL, 'active', 'Benjamin12', 'Benjamin12', 'student', 'approved'),
('2021-8724-A', 'Taylor', 'Black', 'N', 'N/A', 'f', NULL, 'Taylor72@gmail.com', '09456789101', 'Santa Barbara', 'Bolong Este', 'Iloilo', '2001-05-07', 'coe', 'BSEd ', '4 B', '3', NULL, 'active', 'Taylor733', 'Taylor733', 'student', 'approved'),
('2021-8733-A', 'Calvin', 'Palaaway', 'C', 'N/A', 'm', NULL, 'Calvin4@gmail.com', '09786543219', 'Lapaz', 'Burgos st.', 'Iloilo', '2002-03-31', 'cit', 'BS in Elec Tech (BSELT) - Level III', '4 D', '3', NULL, 'active', 'Calvin335', 'Calvin335', 'student', 'approved'),
('2021-8764-A', 'Gina', 'Dickens', 'Cren', 'N/A', 'f', NULL, 'gina@gmail.com', '09709543216', 'Jaro', 'Sambag', 'Iloilo', '2002-12-01', 'cas', 'BS in Info Tech', '1 C', '3', NULL, 'active', 'Mika23', 'Mika23', 'student', 'approved'),
('2021-8764-D', 'Claire', 'Stewart', 'H', 'N/A', 'f', NULL, 'Claire65@gmail.com', '09098555642', 'Pavia', 'Cabugao Sur', 'Iloilo', '2003-09-24', 'cas', 'BS in Comp Sci', '3 C', '3', NULL, 'active', 'Claire545', 'Claire545', 'student', 'approved'),
('2022-6742-A', 'Mika Jane', 'Yato', 'Fen', 'N/A', 'f', NULL, 'mikajane@gmail.com', '09098735642', 'Lapaz', 'Burgos st.', 'Iloilo', '2004-04-02', 'cas', 'BS in Comp Sci', '4 B', '3', NULL, 'active', 'Mikajane@gmail', 'Mikajane@gmail', 'student', 'approved'),
('2022-9078-A', 'Damn', 'Stitch', 'Men', 'N/A', 'o', NULL, 'Damn@gmail.com', '09786673219', 'Janiuay', 'Anhawan', 'Iloilo', '2003-11-15', 'cit', 'BS in Elec Tech (BSELT) - Level III', '3 A', '3', NULL, 'active', 'Damn7', 'Damn7', 'admin', 'approved'),
('2023-5478-A', 'Umay', 'Gad', 'Ho', 'N/A', 'm', NULL, 'UMami@gmail.com', '09128555642', 'Guimbal', 'Cabubugan', 'Iloilo', '2004-12-06', 'coe', 'BTVTEd', '2 C', '3', NULL, 'active', 'Umai11', 'Umai11', 'admin', 'approved'),
('2024-2981-V', 'Destiny', 'Kramel', 'J', 'N/A', 'f', NULL, 'vonjohn.suropia@students.isatu.edu.ph', '09098735642', 'Jaro', 'Zone1', 'Iloilo', '2005-06-09', 'cas', 'BS in English', '1 C', '3', NULL, 'active', 'Destiny00', 'Destiny00', 'student', 'rejected'),
('2024-4536-A', 'Trans', 'Gender', 'LGBT', 'N/A', 'o', NULL, 'Trans@gmail.com', '09736543219', 'Cabatuan', 'Linis Patag', 'Iloilo', '2006-09-07', 'coe', 'BSEd ', '1 D', '3', NULL, 'active', 'Trans7', 'Trans7', 'admin', 'rejected'),
('2024-7878-A', 'Crams', 'Cammy', 'Pie', 'N/A', 'm', NULL, 'Cramps@gmail.com', '09788735642', 'Roxas ', 'Paralan,Maayon', 'Iloilo', '2005-03-08', 'cit', 'BS in Hotel & Rest Tech (BSHRT) - Level II', '1 B', '3', NULL, 'active', 'Crams56', 'Crams56', 'admin', 'approved'),
('a1-Asbart', 'a1-Asbart', 'a1-Asbart', 'a1-Asbart', 'N/A', 'o', NULL, 'vonjohnsuropia116@gmail.com', '56783456789', 'a1-Asbart', 'a1-Asbart', 'a1-Asbart', '2024-12-31', 'cas', 'BS in English', '4 B', '3', '', 'active', 'a1-Asbart', 'a1-Asbart', 'student', 'pending'),
('a1-Asbartq', 'a1-Asbart', 'a1-Asbart', 'a1-Asbart', 'N/A', 'o', NULL, 'vonjohnsuropia116@gmail.com', '56783456789', 'a1-Asbart', 'a1-Asbart', 'a1-Asbart', '2024-12-31', 'cas', 'BS in English', '4 B', '3', '', 'active', 'a1-Asbart', 'a1-Asbart', 'student', 'pending'),
('admin1', 'Ad', 'In', 'M', '1', 'Male', NULL, 'vonjohn.suropia@students.isatu.edu.ph', '09111111111', '', '', '', '2025-01-01', 'Library', '', '', '3', 'Non-Teaching Personnel', 'active', 'admin1', 'admin1', 'admin', 'approved'),
('another', 'another', 'another', 'another', 'N/A', 'm', NULL, 'vonjohnsuropia116@gmail.com', '98765432222', 'another', 'another', 'another', '2024-12-07', 'cea', '', '4 A', '3', '', 'active', 'another11@A', 'another11@A', 'student', 'pending'),
('librarian1', 'libr', 'librarian', 'arian', 'N/A', 'Male', NULL, 'vonjohnsuropia116@gmail.com', '09000000000', 'Mlibrarian', 'Blibrarian', 'Plibrarian', '2024-12-01', 'cas', 'BS in English', '5 A', '3', NULL, 'active', 'librarian1', 'librarian1', 'librarian', 'approved'),
('user1', '1', '1', '1', 'N/A', '', NULL, '1@gm.dc', '11111111111', '1', '1', '1', '2024-12-13', 'cea', '', '', '3', '', 'active', 'hellpme', '!dDwwewe', 'student', 'pending'),
('user1q', '2', '2', '2', 'N/A', '', NULL, '2222@d.dd', '11111111111', '', '', '', '2024-12-12', 'cea', 'BS in Architecture', '4 B', '3', '', 'active', 'hellpme', '22#@DEdd', 'student', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_idno_logdate` (`IDno`,`LOGDATE`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `B_title` (`B_title`);

--
-- Indexes for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD PRIMARY KEY (`book_copy_ID`),
  ADD KEY `B_title` (`B_title`),
  ADD KEY `book_id` (`book_copy`),
  ADD KEY `book_id_2` (`book_id`),
  ADD KEY `book_id_3` (`book_id`);

--
-- Indexes for table `borrow_book`
--
ALTER TABLE `borrow_book`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `borrow_book_ibfk_1` (`IDno`),
  ADD KEY `borrow_book_ibfk_2` (`ID`),
  ADD KEY `book_id` (`book_copy`);

--
-- Indexes for table `coauthor`
--
ALTER TABLE `coauthor`
  ADD PRIMARY KEY (`co_author_id`),
  ADD KEY `B_title` (`book_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `B_title` (`book_id`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`IDno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=318;

--
-- AUTO_INCREMENT for table `book_copies`
--
ALTER TABLE `book_copies`
  MODIFY `book_copy_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=807;

--
-- AUTO_INCREMENT for table `borrow_book`
--
ALTER TABLE `borrow_book`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `coauthor`
--
ALTER TABLE `coauthor`
  MODIFY `co_author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=690;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_users_info` FOREIGN KEY (`IDno`) REFERENCES `users_info` (`IDno`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD CONSTRAINT `fk_book_copies` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `borrow_book`
--
ALTER TABLE `borrow_book`
  ADD CONSTRAINT `borrow_book_ibfk_1` FOREIGN KEY (`IDno`) REFERENCES `users_info` (`IDno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `borrow_book_ibfk_3` FOREIGN KEY (`book_copy`) REFERENCES `book_copies` (`book_copy`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coauthor`
--
ALTER TABLE `coauthor`
  ADD CONSTRAINT `fk_coauthor` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users_info` (`IDno`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users_info` (`IDno`) ON DELETE CASCADE;

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `fk_subject` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
