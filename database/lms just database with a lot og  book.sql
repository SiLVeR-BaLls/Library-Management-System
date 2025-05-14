-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 07:49 AM
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

INSERT INTO `book` (`book_id`, `B_title`, `subtitle`, `author`, `edition`, `LCCN`, `ISBN`, `ISSN`, `MT`, `ST`, `place`, `publisher`, `Pdate`, `copyright`, `extent`, `Odetail`, `size`, `url`, `Description`, `UTitle`, `VForm`, `SUTitle`, `volume`, `note`, `photo`) VALUES
(2, 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Batayan sa pagpapakuhulugan ng wika', 'Louie Jane L. Camorahan', '2', '', '', '', 'Book', 'Hardcover', 'Iloilo City : Iloilo Science and Technology University, 2023.', '', '0000-00-00', '2023', ' 87 p', '', '27 cm.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Impluwensiya ng youtube sa pag-aaral ng panitikan ng mga BSED Filipino ng ISAT U', '', 'Dayna C. Cabaya', '1', '', '', '', 'Book', 'Not Assigned', 'Iloilo City : Iloilo Science and Technology University, 2023.', '', '2023-11-04', '2023', '71 p', '', '27 cm.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Bobot', 'an interactive Filipino sign language app ', 'Enrico Augusto Alagao', '1', '', '', '', 'Book', 'Not Assigned', '', '', '2023-11-04', '2023', '104 p', '', ' 27 cm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '67397190a70cb_default.jpg'),
(5, 'Practical english usage', '', 'Radhika', '', '', '978-9-39233349-1', '', 'Book', 'Not Assigned', 'New Delhi, India', 'Paradise Press,', '2023-11-04', ' 2023', '317 pages', '', ' 23 cm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Essential english grammar', '', 'Mathew Stephen.', '', '', '', '', 'Book', 'Not Assigned', 'New Delhi', 'Wisdom Press', '2023-11-04', '2023', '250 pages.', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'English reading proficiency in comprehension of grade VI pupils in Jalandoni Memorial Elementary School ', '', 'JV Demberly V. Gorantes', '', '', '', '', 'Book', 'Not Assigned', 'Iloilo City', 'Iloilo Science and Technology University', '2024-11-04', '2023', '75 p', '', '27 cm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Iloilo sports division athletes performance monitoring system', '', 'Claredy G. Gelloani', '', '', '', '', 'Book', 'Not Assigned', 'Iloilo City', 'Iloilo Science and Technology University', '0000-00-00', '2024', '191 p', '', '27 cm.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Human resource information system', '', 'Reneboy Moritcho Jr.', '', '', '', '', 'Book', 'Not Assigned', 'Iloilo City', 'Iloilo Science and Technology University', '2024-11-04', '2024', '151 p', '', '27 cm.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Algorithms ', 'Design and Analysis', 'Bruce Collins', '', '', '978-1-668686-951-4', '', 'Book', 'Not Assigned', 'USA', ' American Academic Publisher', '2024-12-07', '2024', '319p', 'llustrations; pic. (b&w)', '25 cm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6739710e7371f_ID-Card (10).png'),
(11, 'Computing essentials', 'making IT work for you: Introductory 2023', 'Timothy J. O\'Leary', '', '', '978-1-26526321-8', '', 'Book', 'Not Assigned', 'New York', 'The McGraw-Hill Companies', '0000-00-00', '2023', '27.5 cm', '', '390pages', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'An Introduction to Cyber Security', '', 'J. Menezes.', '', '', '978-1-83535-145-1', '', 'Book', 'LargePrint', 'London ', 'London ED-Tech Press, [2024]', '0000-00-00', '2024', '337', 'illustrations, pic. (b&w)', '25cm', NULL, NULL, NULL, NULL, NULL, 0, '', NULL),
(13, 'Iloilo Science and Technology University Cafeteria online menu and reservation system', '', 'Grace Ann D. De La Cruz, Ailene I. Llena, Mae G. Silva, Mary Lord S. Suay.', '1', '', '', '', 'Book', 'Not Assigned', 'Iloilo City ', 'Iloilo City Iloilo Science and Technology University, 2024', '0000-00-00', '2024', '69 pg', '', '27 cm', NULL, NULL, NULL, NULL, NULL, 0, '(Unpublished Undergraduate Thesis)', NULL),
(14, 'Partial Differential Equations', 'An Introduction', 'Walter A. Strauss', '2nd', '2007038670', '978-0470054567', NULL, 'Mathematics Journal', 'Textbook', 'Hoboken', 'Wiley', '2007-01-01', '2007', '512 pages', 'Hardcover', '24cm', 'http://example.com/partial-differential-equations-strauss', 'An introductory text on partial differential equations.', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'Complex Analysis', NULL, 'Elias M. Stein', '1st', '2002016259', '978-0691113852', NULL, 'Mathematics Journal', 'Textbook', 'Princeton', 'Princeton University Press', '2003-01-01', '2003', '384 pages', 'Hardcover', '24cm', 'http://example.com/complex-analysis-stein', 'A text on complex analysis.', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'Modern Control Systems', NULL, 'Richard C. Dorf', '13th', '2015049969', '978-0134407623', NULL, 'Engineering Journal', 'Textbook', 'Boston', 'Pearson', '2016-01-01', '2016', '960 pages', 'Hardcover', '28cm', 'http://example.com/modern-control-systems', 'A text on control systems.', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'Fluid Mechanics', 'Fundamentals and Applications', 'Yunus A. Çengel', '3rd', '2013038670', '978-0073380322', NULL, 'Engineering Journal', 'Textbook', 'New York', 'McGraw-Hill', '2013-01-01', '2013', '960 pages', 'Hardcover', '28cm', 'http://example.com/fluid-mechanics-cengel', 'A text on fluid mechanics.', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'Heat Transfer', 'A Practical Approach', 'Yunus A. Çengel', '2nd', '2002016259', '978-0072406552', NULL, 'Engineering Journal', 'Textbook', 'New York', 'McGraw-Hill', '2002-01-01', '2002', '992 pages', 'Hardcover', '28cm', 'http://example.com/heat-transfer-cengel', 'A text on heat transfer.', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'Digital Signal Processing', NULL, 'Alan V. Oppenheim', '2nd', '96035071', '978-0132146371', NULL, 'Engineering Journal', 'Textbook', 'Upper Saddle River', 'Prentice Hall', '1996-01-01', '1996', '876 pages', 'Hardcover', '24cm', 'http://example.com/digital-signal-processing-oppenheim', 'A text on digital signal processing.', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'Power System Analysis', NULL, 'John J. Grainger', '1st', '94022201', '978-0070237827', NULL, 'Engineering Journal', 'Textbook', 'New York', 'McGraw-Hill', '1994-01-01', '1994', '672 pages', 'Hardcover', '24cm', 'http://example.com/power-system-analysis-grainger', 'A text on power system analysis.', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'Introduction to Psychology', NULL, 'Dennis Coon', '14th', '2014049887', '978-1285430850', NULL, 'Psychology Journal', 'Textbook', 'Belmont', 'Wadsworth Cengage Learning', '2015-01-01', '2015', '768 pages', 'Hardcover', '28cm', 'http://example.com/intro-psychology-coon', 'An introductory text on psychology.', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'Social Psychology', NULL, 'David Myers', '12th', '2015049969', '978-1259880292', NULL, 'Psychology Journal', 'Textbook', 'New York', 'McGraw-Hill', '2018-01-01', '2018', '768 pages', 'Hardcover', '28cm', 'http://example.com/social-psychology-myers', 'A text on social psychology.', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'Developmental Psychology', NULL, 'Robert S. Feldman', '7th', '2013038670', '978-0205814510', NULL, 'Psychology Journal', 'Textbook', 'Boston', 'Pearson', '2014-01-01', '2014', '768 pages', 'Hardcover', '28cm', 'http://example.com/developmental-psychology-feldman', 'A text on developmental psychology.', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'Cognitive Psychology', NULL, 'Robert J. Sternberg', '7th', '2017049968', '978-1337408277', NULL, 'Psychology Journal', 'Textbook', 'Boston', 'Cengage Learning', '2017-01-01', '2017', '672 pages', 'Hardcover', '28cm', 'http://example.com/cognitive-psychology-sternberg', 'A text on cognitive psychology.', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'Clinical Psychology', NULL, 'Timothy J. Trull', '9th', '2014023274', '978-1118949818', NULL, 'Psychology Journal', 'Textbook', 'Hoboken', 'Wiley', '2015-01-01', '2015', '784 pages', 'Hardcover', '28cm', 'http://example.com/clinical-psychology-trull', 'A text on clinical psychology.', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'Organizational Behavior', NULL, 'Stephen P. Robbins', '18th', '2017049968', '978-0134729336', NULL, 'Business Journal', 'Textbook', 'New York', 'Pearson', '2017-01-01', '2017', '768 pages', 'Hardcover', '28cm', 'http://example.com/organizational-behavior-robbins', 'A text on organizational behavior.', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'Marketing Management 1', NULL, 'Philip Kotler', '15th', '2015049969', '978-0133856460', NULL, 'Business Journal', 'Textbook', 'Boston', 'Pearson', '2016-01-01', '2016', '816 pages', 'Hardcover', '28cm', 'http://example.com/marketing-management-kotler', 'A text on marketing management.', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'Financial Management', NULL, 'Eugene F. Brigham', '15th', '2016041130', '978-1305631510', NULL, 'Business Journal', 'Textbook', 'Mason', 'South-Western Cengage Learning', '2017-01-01', '2017', '960 pages', 'Hardcover', '28cm', 'http://example.com/financial-management-brigham', 'A text on financial management.', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'Operations Management', NULL, 'William J. Stevenson', '13th', '2017049968', '978-1259667473', NULL, 'Business Journal', 'Textbook', 'New York', 'McGraw-Hill', '2018-01-01', '2018', '864 pages', 'Hardcover', '28cm', 'http://example.com/operations-management-stevenson', 'A text on operations management.', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'Human Resource Management', NULL, 'R. Wayne DeSimone', '9th', '2016041130', '978-1259296542', NULL, 'Business Journal', 'Textbook', 'New York', 'McGraw-Hill', '2017-01-01', '2017', '768 pages', 'Hardcover', '28cm', 'http://example.com/human-resource-management-desimone', 'A text on human resource management.', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'Introduction to Philosophy', NULL, 'John Perry', '6th', '2015049969', '978-0190206584', NULL, 'Philosophy Journal', 'Textbook', 'Oxford', 'Oxford University Press', '2017-01-01', '2017', '672 pages', 'Paperback', '23cm', 'http://example.com/introduction-philosophy-perry', 'An introductory text on philosophy.', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'Ethics', 'Theory and Practice', 'Jacques P. Thiroux', '11th', '2014023274', '978-1305957863', NULL, 'Philosophy Journal', 'Textbook', 'Boston', 'Cengage Learning', '2014-01-01', '2014', '576 pages', 'Paperback', '23cm', 'http://example.com/ethics-thiroux', 'A text on ethical theory and practice.', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'Logic', NULL, 'Stan Baronett', '4th', '2018049968', '978-1337515432', NULL, 'Philosophy Journal', 'Textbook', 'Boston', 'Cengage Learning', '2018-01-01', '2018', '768 pages', 'Paperback', '23cm', 'http://example.com/logic-baronett', 'A text on formal logic.', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'Metaphysics', NULL, 'Michael J. Loux', '4th', '2016041130', '978-0415714041', NULL, 'Philosophy Journal', 'Textbook', 'New York', 'Routledge', '2017-01-01', '2017', '384 pages', 'Paperback', '23cm', 'http://example.com/metaphysics-loux', 'A text on metaphysical theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'Epistemology', NULL, 'Linda Zagzebski', '2nd', '2016041130', '978-0199991263', NULL, 'Philosophy Journal', 'Textbook', 'Oxford', 'Oxford University Press', '2017-01-01', '2017', '384 pages', 'Paperback', '23cm', 'http://example.com/epistemology-zagzebski', 'A text on epistemological theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'Introduction to Political Science', NULL, 'Andrew Heywood', '7th', '2018049968', '978-1137606244', NULL, 'Political Science Journal', 'Textbook', 'New York', 'Palgrave Macmillan', '2018-01-01', '2018', '672 pages', 'Paperback', '23cm', 'http://example.com/introduction-political-science-heywood', 'An introductory text on political science.', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'Comparative Politics', NULL, 'Patrick H. O\'Neil', '5th', '2017049968', '978-1337099710', NULL, 'Political Science Journal', 'Textbook', 'Boston', 'Cengage Learning', '2018-01-01', '2018', '672 pages', 'Paperback', '23cm', 'http://example.com/comparative-politics-oneil', 'A text on comparative politics.', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'International Relations', NULL, 'Joshua S. Goldstein', '11th', '2014023274', '978-0133974362', NULL, 'Political Science Journal', 'Textbook', 'Boston', 'Pearson', '2014-01-01', '2014', '672 pages', 'Paperback', '23cm', 'http://example.com/international-relations-goldstein', 'A text on international relations.', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'Public Policy', NULL, 'Theodore J. Lowi', '12th', '2017049968', '978-1337099710', NULL, 'Political Science Journal', 'Textbook', 'Boston', 'Cengage Learning', '2018-01-01', '2018', '672 pages', 'Paperback', '23cm', 'http://example.com/public-policy-lowi', 'A text on public policy.', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'Political Theory', NULL, 'Steven M. Cahn', '3rd', '2016041130', '978-0190206584', NULL, 'Political Science Journal', 'Textbook', 'Oxford', 'Oxford University Press', '2017-01-01', '2017', '480 pages', 'Paperback', '23cm', 'http://example.com/political-theory-cahn', 'A text on political theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'Introduction to Environmental Science', NULL, 'G. Tyler Miller', '15th', '2016041130', '978-1305090439', NULL, 'Environmental Science Journal', 'Textbook', 'Boston', 'Cengage Learning', '2017-01-01', '2017', '672 pages', 'Hardcover', '28cm', 'http://example.com/introduction-environmental-science-miller', 'An introductory text on environmental science.', NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'Ecology', NULL, 'Michael L. Cain', '3rd', '2013038670', '978-0878939081', NULL, 'Environmental Science Journal', 'Textbook', 'Sunderland', 'Sinauer Associates', '2014-01-01', '2014', '768 pages', 'Hardcover', '28cm', 'http://example.com/ecology-cain', 'A text on ecological theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'Conservation Biology', NULL, 'Richard Primack', '6th', '2013038670', '978-1605352339', NULL, 'Environmental Science Journal', 'Textbook', 'Sunderland', 'Sinauer Associates', '2013-01-01', '2013', '672 pages', 'Hardcover', '28cm', 'http://example.com/conservation-biology-primack', 'A text on conservation biology.', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'Climate Change Science', NULL, 'David Archer', '1st', '2011039773', '978-1405187052', NULL, 'Environmental Science Journal', 'Textbook', 'Chichester', 'Wiley-Blackwell', '2011-01-01', '2011', '400 pages', 'Hardcover', '24cm', 'http://example.com/climate-change-science-archer', 'A text on the science of climate change.', NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'Environmental Policy', NULL, 'Daniel J. Fiorino', '3rd', '2010041289', '978-0262014601', NULL, 'Environmental Science Journal', 'Textbook', 'Cambridge', 'MIT Press', '2010-01-01', '2010', '480 pages', 'Paperback', '23cm', 'http://example.com/environmental-policy-fiorino', 'A text on environmental policy.', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'English Grammar', NULL, 'Betty S. Azar', '4th', '2009038670', '978-0132332460', NULL, 'Language Journal', 'Textbook', 'Boston', 'Pearson', '2009-01-01', '2009', '672 pages', 'Paperback', '23cm', 'http://example.com/english-grammar-azar', 'A text on English grammar.', NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'Writing Analytically', NULL, 'David Rosenwasser', '8th', '2017049968', '978-1337286165', NULL, 'Language Journal', 'Textbook', 'Boston', 'Cengage Learning', '2017-01-01', '2017', '480 pages', 'Paperback', '23cm', 'http://example.com/writing-analytically-rosenwasser', 'A text on analytical writing.', NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'History of the English Language', NULL, 'Albert C. Baugh', '6th', '2010041289', '978-0415336625', NULL, 'Language Journal', 'Textbook', 'New York', 'Routledge', '2010-01-01', '2010', '512 pages', 'Paperback', '23cm', 'http://example.com/history-english-language-baugh', 'A text on the history of the English language.', NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'Sociolinguistics', NULL, 'R.A. Hudson', '2nd', '2001047120', '978-0521652756', NULL, 'Language Journal', 'Textbook', 'Cambridge', 'Cambridge University Press', '2001-01-01', '2001', '288 pages', 'Paperback', '23cm', 'http://example.com/sociolinguistics-hudson', 'A text on sociolinguistics.', NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'Language and Mind', NULL, 'Noam Chomsky', '3rd', '2005016259', '978-0521670231', NULL, 'Language Journal', 'Textbook', 'Cambridge', 'Cambridge University Press', '2006-01-01', '2006', '208 pages', 'Paperback', '23cm', 'http://example.com/language-mind-chomsky', 'A text on language and cognitive science.', NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'Introduction to Linguistic Science', NULL, 'Mark Aronoff', '1st', '2010041289', '978-0631215273', NULL, 'Linguistics Journal', 'Textbook', 'Oxford', 'Blackwell', '2010-01-01', '2010', '480 pages', 'Paperback', '23cm', 'http://example.com/introduction-linguistic-science-aronoff', 'An introductory text on linguistic science.', NULL, NULL, NULL, NULL, NULL, NULL),
(52, 'Syntax', NULL, 'Andrew Carnie', '3rd', '2012040003', '978-1405133813', NULL, 'Linguistics Journal', 'Textbook', 'Chichester', 'Wiley-Blackwell', '2012-01-01', '2012', '768 pages', 'Paperback', '23cm', 'http://example.com/syntax-carnie', 'A text on syntactic theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(53, 'Phonology', NULL, 'Larry M. Hyman', '1st', '2007038670', '978-0415278381', NULL, 'Linguistics Journal', 'Textbook', 'New York', 'Routledge', '2007-01-01', '2007', '672 pages', 'Paperback', '23cm', 'http://example.com/phonology-hyman', 'A text on phonological theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(54, 'Semantics', NULL, 'John I. Saeed', '4th', '2015049969', '978-1118788448', NULL, 'Linguistics Journal', 'Textbook', 'Chichester', 'Wiley-Blackwell', '2015-01-01', '2015', '576 pages', 'Paperback', '23cm', 'http://example.com/semantics-saeed', 'A text on semantic theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'Historical Linguistics', NULL, 'Lyle Campbell', '3rd', '2012040003', '978-0262534079', NULL, 'Linguistics Journal', 'Textbook', 'Cambridge', 'MIT Press', '2012-01-01', '2012', '480 pages', 'Paperback', '23cm', 'http://example.com/historical-linguistics-campbell', 'A text on historical linguistic theory.', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 'Comparative Religion', NULL, 'William L. Reese', '1st', '99086887', '978-0684824343', NULL, 'Religion Journal', 'Textbook', 'New York', 'Scribner', '1999-01-01', '1999', '512 pages', 'Hardcover', '24cm', 'http://example.com/comparative-religion-reese', 'A text on comparative religion.', NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'The World\'s Religions', NULL, 'Huston Smith', 'Rev. ed.', '91022201', '978-0060675100', NULL, 'Religion Journal', 'Textbook', 'New York', 'HarperSanFrancisco', '1991-01-01', '1991', '400 pages', 'Paperback', '23cm', 'http://example.com/worlds-religions-smith', 'A text on the world\'s major religions.', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 'Philosophy of Religion', NULL, 'Michael Peterson', '5th', '2013038670', '978-0195335818', NULL, 'Religion Journal', 'Textbook', 'Oxford', 'Oxford University Press', '2013-01-01', '2013', '480 pages', 'Paperback', '23cm', 'http://example.com/philosophy-religion-peterson', 'A text on the philosophy of religion.', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 'Religious Studies', 'A Global View', 'Gerrie ter Haar', '1st', '2010041289', '978-0814736636', NULL, 'Religion Journal', 'Textbook', 'New York', 'New York University Press', '2010-01-01', '2010', '480 pages', 'Paperback', '23cm', 'http://example.com/religious-studies-haar', 'A text on religious studies.', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'The Bible', 'Revised Standard Version', 'Various', '1st', NULL, '978-0005996080', NULL, 'Religion Journal', 'Reference', 'London', 'Collins', '1971-01-01', '1971', '1280 pages', 'Hardcover', '24cm', 'http://example.com/bible-rsv', 'A translation of the Bible.', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 'Journal of Applied Physics', NULL, 'Various', NULL, NULL, NULL, '0021-8979', 'Journal', 'Physics', 'Melville, NY', 'American Institute of Physics', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://jap.aip.org/', 'A journal covering applied physics research.', NULL, 'Journal', NULL, 0, NULL, NULL),
(113, 'Nature', NULL, 'Various', NULL, NULL, NULL, '0028-0836', 'Journal', 'Science', 'London, UK', 'Nature Publishing Group', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://www.nature.com/', 'A multidisciplinary scientific journal.', NULL, 'Journal', NULL, 0, NULL, NULL),
(114, 'Science', NULL, 'Various', NULL, NULL, NULL, '0036-8075', 'Journal', 'Science', 'Washington, DC', 'American Association for the Advancement of Science', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://www.science.org/', 'A leading journal for original scientific research.', NULL, 'Journal', NULL, 0, NULL, NULL),
(115, 'The Astrophysical Journal', NULL, 'Various', NULL, NULL, NULL, '0004-637X', 'Journal', 'Astronomy', 'Chicago, IL', 'University of Chicago Press', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://iopscience.iop.org/0004-637X/', 'A major journal for astronomical research.', NULL, 'Journal', NULL, 0, NULL, NULL),
(116, 'Physical Review Letters', NULL, 'Various', NULL, NULL, NULL, '0031-9007', 'Journal', 'Physics', 'Ridge, NY', 'American Physical Society', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://prl.aps.org/', 'A prestigious journal for physics research.', NULL, 'Journal', NULL, 0, NULL, NULL),
(117, 'Proceedings of the National Academy of Sciences', NULL, 'Various', NULL, NULL, NULL, '0027-8424', 'Journal', 'Science', 'Washington, DC', 'National Academy of Sciences', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://www.pnas.org/', 'A multidisciplinary journal of scientific research.', NULL, 'Journal', NULL, 0, NULL, NULL),
(118, 'Journal of the American Chemical Society', NULL, 'Various', NULL, NULL, NULL, '0002-7863', 'Journal', 'Chemistry', 'Washington, DC', 'American Chemical Society', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://pubs.acs.org/journal/jacsat', 'A leading journal for chemical research.', NULL, 'Journal', NULL, 0, NULL, NULL),
(119, 'Cell', NULL, 'Various', NULL, NULL, NULL, '0092-8674', 'Journal', 'Biology', 'Cambridge, MA', 'Cell Press', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://www.cell.com/', 'A journal for cellular and molecular biology.', NULL, 'Journal', NULL, 0, NULL, NULL),
(120, 'The Lancet', NULL, 'Various', NULL, NULL, NULL, '0140-6736', 'Journal', 'Medical', 'London, UK', 'Elsevier', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://www.thelancet.com/', 'A highly regarded medical journal.', NULL, 'Journal', NULL, 0, NULL, NULL),
(121, 'JAMA', NULL, 'Various', NULL, NULL, NULL, '0098-7484', 'Journal', 'Medical', 'Chicago, IL', 'American Medical Association', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://jama.jamanetwork.com/', 'A leading medical journal.', NULL, 'Journal', NULL, 0, NULL, NULL),
(122, 'New England Journal of Medicine', NULL, 'Various', NULL, NULL, NULL, '0028-4793', 'Journal', 'Medical', 'Boston, MA', 'Massachusetts Medical Society', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://www.nejm.org/', 'A prominent medical journal.', NULL, 'Journal', NULL, 0, NULL, NULL),
(123, 'IEEE Transactions on Pattern Analysis and Machine Intelligence', NULL, 'Various', NULL, NULL, NULL, '0162-8828', 'Journal', 'Computer Science', 'Piscataway, NJ', 'IEEE Computer Society', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://ieeexplore.ieee.org/xpl/RecentIssue.jsp?punumber=34', 'A leading journal in pattern analysis and machine intelligence.', NULL, 'Journal', NULL, 0, NULL, NULL),
(124, 'Communications of the ACM', NULL, 'Various', NULL, NULL, NULL, '0001-0782', 'Journal', 'Computer Science', 'New York, NY', 'Association for Computing Machinery', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://cacm.acm.org/', 'A journal for computer science professionals.', NULL, 'Journal', NULL, 0, NULL, NULL),
(125, 'Journal of Machine Learning Research', NULL, 'Various', NULL, NULL, NULL, '1532-4435', 'Journal', 'Computer Science', 'Cambridge, MA', 'MIT Press', '2004-01-01', '2004', 'Various', 'Periodical', NULL, 'http://www.jmlr.org/', 'A journal for machine learning research.', NULL, 'Journal', NULL, 0, NULL, NULL);

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
  `status` enum('Available','Borrowed','Reserved') DEFAULT NULL,
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
  `note` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_copies`
--

INSERT INTO `book_copies` (`book_copy_ID`, `book_id`, `book_copy`, `copy_ID`, `B_title`, `status`, `callNumber`, `circulationType`, `dateAcquired`, `description1`, `description2`, `description3`, `number1`, `number2`, `number3`, `sublocation`, `vendor`, `fundingSource`, `rating`, `note`) VALUES
(807, 10, 'BOOK0000001', 'BOOK000001', 'Algorithms ', 'Available', '004 C712 2024', 'General Circulation', '2024-12-27', '', '', '', 0, 0, 0, '1', '1', '2', 3, ''),
(808, 13, 'BOOK0000002', '', 'Iloilo Science and Technology University Cafeteria online menu and reservation system', 'Borrowed', 'Fil-R 0840 D331 2024', 'General Circulation', '2024-10-15', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(809, 12, 'BOOK0000003', '', 'An Introduction to Cyber Security', 'Reserved', '005 8 M543 2024', 'General Circulation', '2024-03-12', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(810, 11, 'BOOK0000004', '', 'Computing essentials', 'Borrowed', '004 O999 2023', 'General Circulation', '2023-05-11', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(811, 11, 'BOOK0000005', '', 'Computing essentials', 'Available', '004 O999 2023', 'General Circulation', '2023-05-11', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(812, 11, 'BOOK0000006', '', 'Computing essentials', 'Reserved', '004 O999 2023', 'General Circulation', '2023-05-11', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(813, 9, 'BOOK0000007', '', 'Human resource information system', 'Available', 'Fil-R 0702 M862 2024', 'General Circulation', '2024-04-23', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(814, 9, 'BOOK0000008', '', 'Human resource information system', 'Available', 'Fil-R 0702 M862 2024', 'General Circulation', '2024-04-23', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(815, 8, 'BOOK0000009', '', 'Iloilo sports division athletes performance monitoring system', 'Available', 'Fil-R 0702 G319 2024', 'General Circulation', '2024-02-06', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(816, 8, 'BOOK0000010', '', 'Iloilo sports division athletes performance monitoring system', 'Available', 'Fil-R 0702 G319 2024', 'General Circulation', '2024-02-06', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(817, 8, 'BOOK0000011', '', 'Iloilo sports division athletes performance monitoring system', 'Available', 'Fil-R 0702 G319 2024', 'General Circulation', '2024-02-06', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(818, 8, 'BOOK0000012', '', 'Iloilo sports division athletes performance monitoring system', 'Available', 'Fil-R 0702 G319 2024', 'General Circulation', '2024-02-06', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(819, 8, 'BOOK0000013', '', 'Iloilo sports division athletes performance monitoring system', 'Available', 'Fil-R 0702 G319 2024', 'General Circulation', '2024-02-06', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(820, 7, 'BOOK0000014', '', 'English reading proficiency in comprehension of grade VI pupils in Jalandoni Memorial Elementary School ', 'Borrowed', 'Fil-R 0840 G661 2023', 'General Circulation', '2023-07-18', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(821, 7, 'BOOK0000015', '', 'English reading proficiency in comprehension of grade VI pupils in Jalandoni Memorial Elementary School ', 'Available', 'Fil-R 0840 G661 2023', 'General Circulation', '2023-07-18', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(822, 7, 'BOOK0000016', '', 'English reading proficiency in comprehension of grade VI pupils in Jalandoni Memorial Elementary School ', 'Available', 'Fil-R 0840 G661 2023', 'General Circulation', '2023-07-18', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(823, 6, 'BOOK0000017', '', 'Essential english grammar', 'Available', '428 24 St435 2023', 'General Circulation', '2023-09-03', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(824, 6, 'BOOK0000018', '', 'Essential english grammar', 'Reserved', '428 24 St435 2023', 'General Circulation', '2023-09-03', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(825, 6, 'BOOK0000019', '', 'Essential english grammar', 'Reserved', '428 24 St435 2023', 'General Circulation', '2023-09-03', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(826, 6, 'BOOK0000020', '', 'Essential english grammar', 'Available', '428 24 St435 2023', 'General Circulation', '2023-09-03', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(827, 6, 'BOOK0000021', '', 'Essential english grammar', 'Available', '428 24 St435 2023', 'General Circulation', '2023-09-03', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(828, 6, 'BOOK0000022', '', 'Essential english grammar', 'Available', '428 24 St435 2023', 'General Circulation', '2023-09-03', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(829, 5, 'BOOK0000023', '', 'Practical english usage', 'Available', '428 24 R128 2023', 'General Circulation', '2023-06-13', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(830, 5, 'BOOK0000024', '', 'Practical english usage', 'Available', '428 24 R128 2023', 'General Circulation', '2023-06-13', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(831, 5, 'BOOK0000025', '', 'Practical english usage', 'Available', '428 24 R128 2023', 'General Circulation', '2023-06-13', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(832, 5, 'BOOK0000026', '', 'Practical english usage', 'Available', '428 24 R128 2023', 'General Circulation', '2023-06-13', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(833, 5, 'BOOK0000027', '', 'Practical english usage', 'Available', '428 24 R128 2023', 'General Circulation', '2023-06-13', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(834, 4, 'BOOK0000028', '', 'Bobot', 'Borrowed', 'Fil-R 0702 A316 2024', 'General Circulation', '2023-05-26', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(835, 3, 'BOOK0000029', '', 'Impluwensiya ng youtube sa pag-aaral ng panitikan ng mga BSED Filipino ng ISAT U', 'Reserved', 'Fil-R 0803 C113 2023', 'General Circulation', '2023-12-01', '', '', '', 0, 0, 0, '1', '1', '2', 5, ''),
(836, 2, 'BOOK0000030', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(837, 2, 'BOOK0000031', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(838, 2, 'BOOK0000032', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(839, 2, 'BOOK0000033', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(840, 2, 'BOOK0000034', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(841, 2, 'BOOK0000035', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(842, 2, 'BOOK0000036', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(843, 2, 'BOOK0000037', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(844, 2, 'BOOK0000038', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(845, 2, 'BOOK0000039', '', 'Pagsusuri ng gay lingo sa mga piling pelikulang Pilipino', 'Available', 'Fil-R 0803 C185 2023', 'General Circulation', '2023-03-24', '', '', '', 0, 0, 0, '', '', '', 5, ''),
(846, 10, 'BOOK0000040', '', 'Algorithms ', 'Available', '004 C712 2024', 'General Circulation', '2025-03-29', '', '', '', 0, 0, 0, '3', '1', '1', 5, ''),
(847, 10, 'BOOK0000041', '', 'Algorithms ', 'Available', '004 C712 2024', 'General Circulation', '2025-03-29', '', '', '', 0, 0, 0, '3', '1', '1', 5, ''),
(848, 10, 'BOOK0000042', '', 'Algorithms ', 'Available', '004 C712 2024', 'General Circulation', '2025-03-29', '', '', '', 0, 0, 0, '3', '1', '1', 5, ''),
(849, 14, 'BOOK0000043', 'BC14-1', 'Partial Differential Equations', 'Available', 'QA374.S78', 'General Circulation', '2023-01-15', 'Hardcover, 2nd Edition', 'Mathematics Textbook', NULL, NULL, NULL, NULL, 'Main Library', 'Books R Us', 'Library Budget', 5, ''),
(850, 14, 'BOOK0000044', 'BC14-2', 'Partial Differential Equations', 'Available', 'QA374.S78', 'General Circulation', '2023-01-15', 'Hardcover, 2nd Edition', 'Mathematics Textbook', NULL, NULL, NULL, NULL, 'Main Library', 'Books R Us', 'Library Budget', 5, ''),
(851, 15, 'BOOK0000045', 'BC15-1', 'Complex Analysis', 'Available', 'QA300.S74', 'General Circulation', '2023-01-15', 'Hardcover, 1st Edition', 'Mathematics Textbook', NULL, NULL, NULL, NULL, 'Main Library', 'Academic Books', 'Library Budget', 5, ''),
(852, 15, 'BOOK0000046', 'BC15-2', 'Complex Analysis', 'Available', 'QA300.S74', 'General Circulation', '2023-01-15', 'Hardcover, 1st Edition', 'Mathematics Textbook', NULL, NULL, NULL, NULL, 'Main Library', 'Academic Books', 'Library Budget', 5, ''),
(853, 16, 'BOOK0000047', 'BC16-1', 'Modern Control Systems', 'Available', 'TJ213.D67', 'General Circulation', '2023-01-15', 'Hardcover, 13th Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(854, 16, 'BOOK0000048', 'BC16-2', 'Modern Control Systems', 'Available', 'TJ213.D67', 'General Circulation', '2023-01-15', 'Hardcover, 13th Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(855, 17, 'BOOK0000049', 'BC17-1', 'Fluid Mechanics', 'Available', 'TA357.C46', 'General Circulation', '2023-01-15', 'Hardcover, 3rd Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(856, 17, 'BOOK0000050', 'BC17-2', 'Fluid Mechanics', 'Available', 'TA357.C46', 'General Circulation', '2023-01-15', 'Hardcover, 3rd Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(857, 18, 'BOOK0000051', 'BC18-1', 'Heat Transfer', 'Available', 'TJ260.C46', 'General Circulation', '2023-01-15', 'Hardcover, 2nd Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(858, 18, 'BOOK0000052', 'BC18-2', 'Heat Transfer', 'Available', 'TJ260.C46', 'General Circulation', '2023-01-15', 'Hardcover, 2nd Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(859, 19, 'BOOK0000053', 'BC19-1', 'Digital Signal Processing', 'Available', 'TK5102.9.O67', 'General Circulation', '2023-01-15', 'Hardcover, 2nd Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(860, 19, 'BOOK0000054', 'BC19-2', 'Digital Signal Processing', 'Available', 'TK5102.9.O67', 'General Circulation', '2023-01-15', 'Hardcover, 2nd Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(861, 20, 'BOOK0000055', 'BC20-1', 'Power System Analysis', 'Available', 'TK3001.G73', 'General Circulation', '2023-01-15', 'Hardcover, 1st Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(862, 20, 'BOOK0000056', 'BC20-2', 'Power System Analysis', 'Available', 'TK3001.G73', 'General Circulation', '2023-01-15', 'Hardcover, 1st Edition', 'Engineering Textbook', NULL, NULL, NULL, NULL, 'Engineering Section', 'Engineering Supplies', 'Engineering Grant', 5, ''),
(863, 21, 'BOOK0000057', 'BC21-1', 'Introduction to Psychology', 'Available', 'BF121.C66', 'General Circulation', '2023-01-15', 'Hardcover, 14th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(864, 21, 'BOOK0000058', 'BC21-2', 'Introduction to Psychology', 'Available', 'BF121.C66', 'General Circulation', '2023-01-15', 'Hardcover, 14th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(865, 22, 'BOOK0000059', 'BC22-1', 'Social Psychology', 'Available', 'HM1033.M94', 'General Circulation', '2023-01-15', 'Hardcover, 12th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(866, 22, 'BOOK0000060', 'BC22-2', 'Social Psychology', 'Available', 'HM1033.M94', 'General Circulation', '2023-01-15', 'Hardcover, 12th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(867, 23, 'BOOK0000061', 'BC23-1', 'Developmental Psychology', 'Available', 'BF713.F45', 'General Circulation', '2023-01-15', 'Hardcover, 7th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(868, 23, 'BOOK0000062', 'BC23-2', 'Developmental Psychology', 'Available', 'BF713.F45', 'General Circulation', '2023-01-15', 'Hardcover, 7th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(869, 24, 'BOOK0000063', 'BC24-1', 'Cognitive Psychology', 'Available', 'BF311.S74', 'General Circulation', '2023-01-15', 'Hardcover, 7th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(870, 24, 'BOOK0000064', 'BC24-2', 'Cognitive Psychology', 'Available', 'BF311.S74', 'General Circulation', '2023-01-15', 'Hardcover, 7th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(871, 25, 'BOOK0000065', 'BC25-1', 'Clinical Psychology', 'Available', 'RC467.T78', 'General Circulation', '2023-01-15', 'Hardcover, 9th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(872, 25, 'BOOK0000066', 'BC25-2', 'Clinical Psychology', 'Available', 'RC467.T78', 'General Circulation', '2023-01-15', 'Hardcover, 9th Edition', 'Psychology Textbook', NULL, NULL, NULL, NULL, 'Psychology Section', 'Psychology Books', 'Social Science Grant', 5, ''),
(873, 26, 'BOOK0000067', 'BC26-1', 'Organizational Behavior', 'Available', 'HD58.7.R62', 'General Circulation', '2023-01-15', 'Hardcover, 18th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(874, 26, 'BOOK0000068', 'BC26-2', 'Organizational Behavior', 'Available', 'HD58.7.R62', 'General Circulation', '2023-01-15', 'Hardcover, 18th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(875, 27, 'BOOK0000069', 'BC27-1', 'Marketing Management', 'Available', 'HF5415.K68', 'General Circulation', '2023-01-15', 'Hardcover, 15th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(876, 27, 'BOOK0000070', 'BC27-2', 'Marketing Management', 'Available', 'HF5415.K68', 'General Circulation', '2023-01-15', 'Hardcover, 15th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(877, 28, 'BOOK0000071', 'BC28-1', 'Financial Management', 'Available', 'HG4026.B75', 'General Circulation', '2023-01-15', 'Hardcover, 15th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(878, 28, 'BOOK0000072', 'BC28-2', 'Financial Management', 'Available', 'HG4026.B75', 'General Circulation', '2023-01-15', 'Hardcover, 15th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(879, 29, 'BOOK0000073', 'BC29-1', 'Operations Management', 'Available', 'TS155.S74', 'General Circulation', '2023-01-15', 'Hardcover, 13th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(880, 29, 'BOOK0000074', 'BC29-2', 'Operations Management', 'Available', 'TS155.S74', 'General Circulation', '2023-01-15', 'Hardcover, 13th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(881, 30, 'BOOK0000075', 'BC30-1', 'Human Resource Management', 'Available', 'HF5549.D47', 'General Circulation', '2023-01-15', 'Hardcover, 9th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(882, 30, 'BOOK0000076', 'BC30-2', 'Human Resource Management', 'Available', 'HF5549.D47', 'General Circulation', '2023-01-15', 'Hardcover, 9th Edition', 'Business Textbook', NULL, NULL, NULL, NULL, 'Business Section', 'Business Books', 'Business Grant', 5, ''),
(883, 31, 'BOOK0000077', 'BC31-1', 'Introduction to Philosophy', 'Available', 'B72.P47', 'General Circulation', '2023-01-15', 'Paperback, 6th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(884, 31, 'BOOK0000078', 'BC31-2', 'Introduction to Philosophy', 'Available', 'B72.P47', 'General Circulation', '2023-01-15', 'Paperback, 6th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(885, 32, 'BOOK0000079', 'BC32-1', 'Ethics', 'Available', 'BJ1012.T45', 'General Circulation', '2023-01-15', 'Paperback, 11th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(886, 32, 'BOOK0000080', 'BC32-2', 'Ethics', 'Available', 'BJ1012.T45', 'General Circulation', '2023-01-15', 'Paperback, 11th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(887, 33, 'BOOK0000081', 'BC33-1', 'Logic', 'Available', 'BC71.B37', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(888, 33, 'BOOK0000082', 'BC33-2', 'Logic', 'Available', 'BC71.B37', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(889, 34, 'BOOK0000083', 'BC34-1', 'Metaphysics', 'Available', 'BD111.L68', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(890, 34, 'BOOK0000084', 'BC34-2', 'Metaphysics', 'Available', 'BD111.L68', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(891, 35, 'BOOK0000085', 'BC35-1', 'Epistemology', 'Available', 'BD161.Z34', 'General Circulation', '2023-01-15', 'Paperback, 2nd Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(892, 35, 'BOOK0000086', 'BC35-2', 'Epistemology', 'Available', 'BD161.Z34', 'General Circulation', '2023-01-15', 'Paperback, 2nd Edition', 'Philosophy Textbook', NULL, NULL, NULL, NULL, 'Philosophy Section', 'Philosophy Books', 'Humanities Grant', 5, ''),
(893, 36, 'BOOK0000087', 'BC36-1', 'Introduction to Political Science', 'Available', 'JA71.H49', 'General Circulation', '2023-01-15', 'Paperback, 7th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(894, 36, 'BOOK0000088', 'BC36-2', 'Introduction to Political Science', 'Available', 'JA71.H49', 'General Circulation', '2023-01-15', 'Paperback, 7th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 4, ''),
(895, 37, 'BOOK0000089', 'BC37-1', 'Comparative Politics', 'Available', 'JF51.O54', 'General Circulation', '2023-01-15', 'Paperback, 5th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(896, 37, 'BOOK0000090', 'BC37-2', 'Comparative Politics', 'Available', 'JF51.O54', 'General Circulation', '2023-01-15', 'Paperback, 5th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(897, 38, 'BOOK0000091', 'BC38-1', 'International Relations', 'Available', 'JX1305.G65', 'General Circulation', '2023-01-15', 'Paperback, 11th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(898, 38, 'BOOK0000092', 'BC38-2', 'International Relations', 'Available', 'JX1305.G65', 'General Circulation', '2023-01-15', 'Paperback, 11th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(899, 39, 'BOOK0000093', 'BC39-1', 'Public Policy', 'Available', 'H97.L68', 'General Circulation', '2023-01-15', 'Paperback, 12th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(900, 39, 'BOOK0000094', 'BC39-2', 'Public Policy', 'Available', 'H97.L68', 'General Circulation', '2023-01-15', 'Paperback, 12th Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(901, 40, 'BOOK0000095', 'BC40-1', 'Political Theory', 'Available', 'JA71.C34', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(902, 40, 'BOOK0000096', 'BC40-2', 'Political Theory', 'Available', 'JA71.C34', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Political Science Textbook', NULL, NULL, NULL, NULL, 'Political Science Section', 'Political Science Books', 'Social Science Grant', 5, ''),
(903, 41, 'BOOK0000097', 'BC41-1', 'Introduction to Environmental Science', 'Available', 'GE105.M54', 'General Circulation', '2023-01-15', 'Hardcover, 15th Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(904, 41, 'BOOK0000098', 'BC41-2', 'Introduction to Environmental Science', 'Available', 'GE105.M54', 'General Circulation', '2023-01-15', 'Hardcover, 15th Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(905, 42, 'BOOK0000099', 'BC42-1', 'Ecology', 'Available', 'QH541.C34', 'General Circulation', '2023-01-15', 'Hardcover, 3rd Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(906, 42, 'BOOK0000100', 'BC42-2', 'Ecology', 'Available', 'QH541.C34', 'General Circulation', '2023-01-15', 'Hardcover, 3rd Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(907, 43, 'BOOK0000101', 'BC43-1', 'Conservation Biology', 'Available', 'QH75.P75', 'General Circulation', '2023-01-15', 'Hardcover, 6th Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(908, 43, 'BOOK0000102', 'BC43-2', 'Conservation Biology', 'Available', 'QH75.P75', 'General Circulation', '2023-01-15', 'Hardcover, 6th Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(909, 44, 'BOOK0000103', 'BC44-1', 'Climate Change Science', 'Available', 'QC981.8.C5A73', 'General Circulation', '2023-01-15', 'Hardcover, 1st Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(910, 44, 'BOOK0000104', 'BC44-2', 'Climate Change Science', 'Available', 'QC981.8.C5A73', 'General Circulation', '2023-01-15', 'Hardcover, 1st Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(911, 45, 'BOOK0000105', 'BC45-1', 'Environmental Policy', 'Available', 'HC79.E5F56', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(912, 45, 'BOOK0000106', 'BC45-2', 'Environmental Policy', 'Available', 'HC79.E5F56', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Environmental Science Textbook', NULL, NULL, NULL, NULL, 'Science Section', 'Science Books', 'Science Grant', 5, ''),
(913, 46, 'BOOK0000107', 'BC46-1', 'English Grammar', 'Available', 'PE1112.A93', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(914, 46, 'BOOK0000108', 'BC46-2', 'English Grammar', 'Available', 'PE1112.A93', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(915, 47, 'BOOK0000109', 'BC47-1', 'Writing Analytically', 'Available', 'PE1408.R67', 'General Circulation', '2023-01-15', 'Paperback, 8th Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(916, 47, 'BOOK0000110', 'BC47-2', 'Writing Analytically', 'Available', 'PE1408.R67', 'General Circulation', '2023-01-15', 'Paperback, 8th Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(917, 48, 'BOOK0000111', 'BC48-1', 'History of the English Language', 'Available', 'PE1075.B38', 'General Circulation', '2023-01-15', 'Paperback, 6th Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(918, 48, 'BOOK0000112', 'BC48-2', 'History of the English Language', 'Available', 'PE1075.B38', 'General Circulation', '2023-01-15', 'Paperback, 6th Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(919, 49, 'BOOK0000113', 'BC49-1', 'Sociolinguistics', 'Available', 'P40.H83', 'General Circulation', '2023-01-15', 'Paperback, 2nd Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(920, 49, 'BOOK0000114', 'BC49-2', 'Sociolinguistics', 'Available', 'P40.H83', 'General Circulation', '2023-01-15', 'Paperback, 2nd Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(921, 50, 'BOOK0000115', 'BC50-1', 'Language and Mind', 'Available', 'P107.C46', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(922, 50, 'BOOK0000116', 'BC50-2', 'Language and Mind', 'Available', 'P107.C46', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Language Textbook', NULL, NULL, NULL, NULL, 'Language Section', 'Language Books', 'Language Grant', 5, ''),
(923, 51, 'BOOK0000117', 'BC51-1', 'Introduction to Linguistic Science', 'Available', 'P121.A76', 'General Circulation', '2023-01-15', 'Paperback, 1st Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(924, 51, 'BOOK0000118', 'BC51-2', 'Introduction to Linguistic Science', 'Available', 'P121.A76', 'General Circulation', '2023-01-15', 'Paperback, 1st Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(925, 52, 'BOOK0000119', 'BC52-1', 'Syntax', 'Available', 'P291.C37', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(926, 52, 'BOOK0000120', 'BC52-2', 'Syntax', 'Available', 'P291.C37', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(927, 53, 'BOOK0000121', 'BC53-1', 'Phonology', 'Available', 'P217.H95', 'General Circulation', '2023-01-15', 'Paperback, 1st Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(928, 53, 'BOOK0000122', 'BC53-2', 'Phonology', 'Available', 'P217.H95', 'General Circulation', '2023-01-15', 'Paperback, 1st Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(929, 54, 'BOOK0000123', 'BC54-1', 'Semantics', 'Available', 'P325.S23', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(930, 54, 'BOOK0000124', 'BC54-2', 'Semantics', 'Available', 'P325.S23', 'General Circulation', '2023-01-15', 'Paperback, 4th Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(931, 55, 'BOOK0000125', 'BC55-1', 'Historical Linguistics', 'Available', 'P140.C36', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(932, 55, 'BOOK0000126', 'BC55-2', 'Historical Linguistics', 'Available', 'P140.C36', 'General Circulation', '2023-01-15', 'Paperback, 3rd Edition', 'Linguistics Textbook', NULL, NULL, NULL, NULL, 'Linguistics Section', 'Linguistics Books', 'Linguistics Grant', 5, ''),
(933, 56, 'BOOK0000127', 'BC56-1', 'Comparative Religion', 'Available', 'BL80.R44', 'General Circulation', '2023-01-15', 'Hardcover, 1st Edition', 'Religion Textbook', NULL, NULL, NULL, NULL, 'Religion Section', 'Religion Books', 'Religion Grant', 5, ''),
(1045, 112, 'BOOK0000128', 'BC112-1', 'Journal of Applied Physics', 'Available', 'QC1.J85', 'General Circulation', '2023-01-15', 'Volume 95, Issue 10', 'Physics Journal', '2004', NULL, NULL, NULL, 'Physics Journal Section', 'Journal Subscription', 'Science Grant', 2, NULL),
(1046, 112, 'BOOK0000129', 'BC112-2', 'Journal of Applied Physics', 'Available', 'QC1.J85', 'General Circulation', '2023-01-15', 'Volume 95, Issue 11', 'Physics Journal', '2004', NULL, NULL, NULL, 'Physics Journal Section', 'Journal Subscription', 'Science Grant', 4, NULL),
(1047, 113, 'BOOK0000130', 'BC113-1', 'Nature', 'Available', 'Q1.N2', 'General Circulation', '2023-01-15', 'Volume 428, Issue 6982', 'Science Journal', '2004', NULL, NULL, NULL, 'Science Journal Section', 'Journal Subscription', 'Science Grant', 3, NULL),
(1048, 113, 'BOOK0000131', 'BC113-2', 'Nature', 'Available', 'Q1.N2', 'General Circulation', '2023-01-15', 'Volume 428, Issue 6983', 'Science Journal', '2004', NULL, NULL, NULL, 'Science Journal Section', 'Journal Subscription', 'Science Grant', 2, NULL),
(1049, 114, 'BOOK0000132', 'BC114-1', 'Science', 'Available', 'Q1.S35', 'General Circulation', '2023-01-15', 'Volume 304, Issue 5671', 'Science Journal', '2004', NULL, NULL, NULL, 'Science Journal Section', 'Journal Subscription', 'Science Grant', 5, NULL),
(1050, 114, 'BOOK0000133', 'BC114-2', 'Science', 'Available', 'Q1.S35', 'General Circulation', '2023-01-15', 'Volume 304, Issue 5672', 'Science Journal', '2004', NULL, NULL, NULL, 'Science Journal Section', 'Journal Subscription', 'Science Grant', 4, NULL),
(1051, 115, 'BOOK0000134', 'BC115-1', 'The Astrophysical Journal', 'Available', 'QB1.A87', 'General Circulation', '2023-01-15', 'Volume 606, Issue 2', 'Astronomy Journal', '2004', NULL, NULL, NULL, 'Astronomy Journal Section', 'Journal Subscription', 'Science Grant', 4, NULL),
(1052, 115, 'BOOK0000135', 'BC115-2', 'The Astrophysical Journal', 'Available', 'QB1.A87', 'General Circulation', '2023-01-15', 'Volume 607, Issue 1', 'Astronomy Journal', '2004', NULL, NULL, NULL, 'Astronomy Journal Section', 'Journal Subscription', 'Science Grant', 4, NULL),
(1053, 116, 'BOOK0000136', 'BC116-1', 'Physical Review Letters', 'Available', 'QC1.P71', 'General Circulation', '2023-01-15', 'Volume 92, Issue 19', 'Physics Journal', '2004', NULL, NULL, NULL, 'Physics Journal Section', 'Journal Subscription', 'Science Grant', 5, NULL),
(1054, 116, 'BOOK0000137', 'BC116-2', 'Physical Review Letters', 'Available', 'QC1.P71', 'General Circulation', '2023-01-15', 'Volume 92, Issue 20', 'Physics Journal', '2004', NULL, NULL, NULL, 'Physics Journal Section', 'Journal Subscription', 'Science Grant', 4, NULL),
(1055, 117, 'BOOK0000138', 'BC117-1', 'Proceedings of the National Academy of Sciences', 'Available', 'Q11.N26', 'General Circulation', '2023-01-15', 'Volume 101, Issue 20', 'Science Journal', '2004', NULL, NULL, NULL, 'Science Journal Section', 'Journal Subscription', 'Science Grant', 5, NULL),
(1056, 117, 'BOOK0000139', 'BC117-2', 'Proceedings of the National Academy of Sciences', 'Available', 'Q11.N26', 'General Circulation', '2023-01-15', 'Volume 101, Issue 21', 'Science Journal', '2004', NULL, NULL, NULL, 'Science Journal Section', 'Journal Subscription', 'Science Grant', 2, NULL),
(1057, 118, 'BOOK0000140', 'BC118-1', 'Journal of the American Chemical Society', 'Available', 'QD1.A51', 'General Circulation', '2023-01-15', 'Volume 126, Issue 19', 'Chemistry Journal', '2004', NULL, NULL, NULL, 'Chemistry Journal Section', 'Journal Subscription', 'Science Grant', 3, NULL),
(1058, 118, 'BOOK0000141', 'BC118-2', 'Journal of the American Chemical Society', 'Available', 'QD1.A51', 'General Circulation', '2023-01-15', 'Volume 126, Issue 20', 'Chemistry Journal', '2004', NULL, NULL, NULL, 'Chemistry Journal Section', 'Journal Subscription', 'Science Grant', 4, NULL),
(1059, 119, 'BOOK0000142', 'BC119-1', 'Cell', 'Available', 'QH573.C45', 'General Circulation', '2023-01-15', 'Volume 117, Issue 5', 'Biology Journal', '2004', NULL, NULL, NULL, 'Biology Journal Section', 'Journal Subscription', 'Science Grant', 2, NULL),
(1060, 119, 'BOOK0000143', 'BC119-2', 'Cell', 'Available', 'QH573.C45', 'General Circulation', '2023-01-15', 'Volume 117, Issue 6', 'Biology Journal', '2004', NULL, NULL, NULL, 'Biology Journal Section', 'Journal Subscription', 'Science Grant', 5, NULL),
(1061, 120, 'BOOK0000144', 'BC120-1', 'The Lancet', 'Available', 'R11.L3', 'General Circulation', '2023-01-15', 'Volume 363, Issue 9423', 'Medical Journal', '2004', NULL, NULL, NULL, 'Medical Journal Section', 'Journal Subscription', 'Science Grant', 1, NULL),
(1062, 120, 'BOOK0000145', 'BC120-2', 'The Lancet', 'Available', 'R11.L3', 'General Circulation', '2023-01-15', 'Volume 363, Issue 9424', 'Medical Journal', '2004', NULL, NULL, NULL, 'Medical Journal Section', 'Journal Subscription', 'Science Grant', 5, NULL),
(1063, 121, 'BOOK0000146', 'BC121-1', 'JAMA', 'Available', 'R15.J25', 'General Circulation', '2023-01-15', 'Volume 291, Issue 20', 'Medical Journal', '2004', NULL, NULL, NULL, 'Medical Journal Section', 'Journal Subscription', 'Science Grant', 1, NULL),
(1064, 121, 'BOOK0000147', 'BC121-2', 'JAMA', 'Available', 'R15.J25', 'General Circulation', '2023-01-15', 'Volume 291, Issue 21', 'Medical Journal', '2004', NULL, NULL, NULL, 'Medical Journal Section', 'Journal Subscription', 'Science Grant', 5, NULL),
(1065, 122, 'BOOK0000148', 'BC122-1', 'New England Journal of Medicine', 'Available', 'R11.N47', 'General Circulation', '2023-01-15', 'Volume 350, Issue 20', 'Medical Journal', '2004', NULL, NULL, NULL, 'Medical Journal Section', 'Journal Subscription', 'Science Grant', 2, NULL),
(1066, 122, 'BOOK0000149', 'BC122-2', 'New England Journal of Medicine', 'Available', 'R11.N47', 'General Circulation', '2023-01-15', 'Volume 350, Issue 21', 'Medical Journal', '2004', NULL, NULL, NULL, 'Medical Journal Section', 'Journal Subscription', 'Science Grant', 3, NULL),
(1067, 123, 'BOOK0000150', 'BC123-1', 'IEEE Transactions on Pattern Analysis and Machine Intelligence', 'Available', 'TA1632.I33', 'General Circulation', '2023-01-15', 'Volume 26, Issue 5', 'Computer Science Journal', '2004', NULL, NULL, NULL, 'Computer Science Journal Section', 'Journal Subscription', 'Computer Science Grant', 1, NULL),
(1068, 123, 'BOOK0000151', 'BC123-2', 'IEEE Transactions on Pattern Analysis and Machine Intelligence', 'Available', 'TA1632.I33', 'General Circulation', '2023-01-15', 'Volume 26, Issue 6', 'Computer Science Journal', '2004', NULL, NULL, NULL, 'Computer Science Journal Section', 'Journal Subscription', 'Computer Science Grant', 3, NULL),
(1069, 124, 'BOOK0000152', 'BC124-1', 'Communications of the ACM', 'Available', 'QA76.C64', 'General Circulation', '2023-01-15', 'Volume 47, Issue 5', 'Computer Science Journal', '2004', NULL, NULL, NULL, 'Computer Science Journal Section', 'Journal Subscription', 'Computer Science Grant', 3, NULL),
(1070, 124, 'BOOK0000153', 'BC124-2', 'Communications of the ACM', 'Available', 'QA76.C64', 'General Circulation', '2023-01-15', 'Volume 47, Issue 6', 'Computer Science Journal', '2004', NULL, NULL, NULL, 'Computer Science Journal Section', 'Journal Subscription', 'Computer Science Grant', 4, NULL),
(1071, 125, 'BOOK0000154', 'BC125-1', 'Journal of Machine Learning Research', 'Available', 'QA76.5.J68', 'General Circulation', '2023-01-15', 'Volume 5', 'Computer Science Journal', '2004', NULL, NULL, NULL, 'Computer Science Journal Section', 'Journal Subscription', 'Computer Science Grant', 1, NULL),
(1072, 125, 'BOOK0000155', 'BC125-2', 'Journal of Machine Learning Research', 'Available', 'QA76.5.J68', 'General Circulation', '2023-01-15', 'Volume 6', 'Computer Science Journal', '2004', NULL, NULL, NULL, 'Computer Science Journal Section', 'Journal Subscription', 'Computer Science Grant', 4, NULL);

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
(1, '2021-8764-A', 'BOOK0000002', 0, '2025-03-30 13:53:31', NULL, '2025-04-02'),
(2, '2021-8764-A', 'BOOK0000003', 0, '2025-03-30 13:53:31', '2025-03-30 15:16:40', '2025-04-02'),
(3, '2018-1865-A', 'BOOK0000001', 0, '2025-03-30 14:14:11', '2025-03-30 15:35:12', '2025-03-30'),
(4, '2020-9452-A', 'BOOK0000001', 0, '2025-03-30 14:17:23', '2025-03-30 15:35:12', '2025-04-02'),
(5, '2020-9452-A', 'BOOK0000004', 0, '2025-03-30 14:19:17', NULL, '2025-04-02'),
(6, '2021-8764-A', 'BOOK0000001', 0, '2025-03-30 14:26:47', '2025-03-30 15:35:12', '2025-04-02'),
(7, '2020-9452-A', 'BOOK0000001', 0, '2025-03-30 14:29:52', '2025-03-30 15:35:12', '2025-04-02'),
(8, '2020-7656-A', 'BOOK0000001', 0, '2025-03-30 14:32:54', '2025-03-30 15:35:12', '2025-04-02'),
(9, '2021-0909-A', 'BOOK0000001', 0, '2025-03-30 14:38:15', '2025-03-30 15:35:12', '2025-04-02'),
(10, '2020-9452-a', 'BOOK0000007', 0, '2025-03-30 14:49:58', '2025-03-30 15:14:08', '2025-04-02'),
(11, '2014-6823-a', 'BOOK0000001', 0, '2025-03-30 14:50:04', '2025-03-30 15:35:12', '2025-04-02'),
(12, '2020-9452-a', 'BOOK0000001', 0, '2025-03-30 15:00:18', '2025-03-30 15:35:12', '2025-04-02'),
(13, 'admin1', 'BOOK0000001', 0, '2025-03-30 15:10:19', '2025-03-30 15:35:12', '2025-04-02'),
(14, 'admin1', 'BOOK0000001', 0, '2025-03-30 15:11:47', '2025-03-30 15:35:12', '2025-04-02'),
(15, '2020-7656-A', 'BOOK0000001', 0, '2025-03-30 15:28:06', '2025-03-30 15:35:12', '2025-04-02'),
(16, '2020-7656-A', 'BOOK0000001', 0, '2025-03-30 15:34:18', '2025-03-30 15:35:12', '2025-04-02'),
(17, '2020-7656-A', 'BOOK0000028', 0, '2025-03-30 15:36:52', NULL, '2025-04-02'),
(18, 'admin1', 'BOOK0000014', 0, '2025-03-30 15:37:23', NULL, '2025-06-30');

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

--
-- Dumping data for table `coauthor`
--

INSERT INTO `coauthor` (`co_author_id`, `book_id`, `Co_Name`, `Co_Date`, `Co_Role`) VALUES
(1, 2, 'Ivy Joy A. Cañamo', '2024-11-04', 'N/A'),
(2, 2, 'Lyka Antoinette P. Tomulto', '2024-11-04', 'N/A'),
(3, 3, 'Jenissa C. Cañonaso', '2024-11-04', 'N/A'),
(4, 3, 'Nicole Anne Rhea G. Cañonaso', '2024-11-04', 'N/A'),
(5, 4, 'Anthony Kerr G. Dulla Jr.', '0000-00-00', ''),
(6, 4, 'Lance Gabriel V. Poblacion', '2024-11-04', 'N/A'),
(7, 4, 'Daniel A. Reysoma', '2024-11-04', 'N/A'),
(8, 4, 'Merique Q. Villaflor.', '2024-11-04', 'N/A'),
(9, 5, 'N/A', '2024-11-04', 'N/A'),
(10, 6, 'N/A', '2024-11-04', 'N/A'),
(11, 7, 'Aiyah Ace D. Montelijao', '2024-11-04', 'N/A'),
(12, 7, 'Lyndsey Terry L. Villasis', '2024-11-04', 'N/A'),
(13, 8, 'Leriz Wane L. Illaga', '2024-11-04', 'N/A'),
(14, 8, 'Justin G. Magallanes', '2024-11-04', 'N/A'),
(15, 8, 'Queen Anne Poliapoy', '2024-11-04', 'N/A'),
(16, 8, 'Adrian C. Tesara', '2024-11-04', 'N/A'),
(17, 9, 'Bea Longino', '2024-11-04', 'N/A'),
(18, 9, 'April Joy Elevado', '2024-11-04', 'N/A'),
(19, 9, 'Eunice Jane Mueda', '2024-11-04', 'N/A'),
(20, 9, 'Mauriz Jade Discar', '2024-11-04', 'N/A'),
(21, 10, 'N/A', '2024-11-04', 'N/A'),
(22, 11, 'Daniel A. O\'Leary', '2024-11-04', 'N/A'),
(23, 11, 'Linda I. O\'Leary', '2024-11-04', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `name`, `program_id`) VALUES
(1, 'BS in English', 1),
(5, 'BS in Human Services', 1),
(6, 'BS in Bio', 1),
(7, 'BS in Comm Dev', 1),
(8, 'BS in Comp Sci', 1),
(9, 'BS in Info Systems', 1),
(10, 'BS in Info Tech', 1),
(11, 'BS in Math', 1),
(12, 'Comp Sci', 1),
(13, 'MA in Math', 1),
(14, 'Doctor of Ed', 2),
(15, 'MA in Ed', 2),
(16, 'MS in Home Econ', 2),
(17, 'MS in TVET Ed', 2),
(18, 'Diploma in Teaching', 2),
(19, 'BEEd', 2),
(20, 'BSEd', 2),
(21, 'BTVTEd', 2),
(22, 'BS in Ind Ed', 2),
(23, 'BS in Architecture', 3),
(24, 'BS in Civil Eng', 3),
(25, 'BS in Elec Eng', 3),
(26, 'BS in Mech Eng', 3),
(27, 'BS in Elec Eng (ECE)', 3),
(28, 'Doctor of Ind Tech (DIT) - Level I', 4),
(29, 'Master of Ind Tech (MIT) - Level I', 4),
(30, 'BS in Ind Tech (BIT) - Level III', 4),
(31, 'BS in Auto Tech (BSAT) - Level III', 4),
(32, 'BS in Hotel & Rest Tech (BSHRT) - Level II', 4),
(33, 'BS in Elec Tech (BSELT) - Level III', 4),
(34, 'BS in Elec Eng (BSELX) - Level III', 4),
(35, 'BS in Fashion Design & Merch (BSFDM) - Level II', 4),
(36, 'Arch Drafting', 4),
(37, 'Auto Tech', 4),
(38, 'Const Tech', 4),
(39, 'Elec Tech', 4),
(40, 'Elec Eng Tech', 4),
(41, 'Fashion & Apparel Tech', 4),
(42, 'Furn & Cabinet Making Tech', 4),
(43, 'Mech Tech', 4),
(44, 'Refrig & Air Cond Tech', 4),
(45, 'Food Tech', 4),
(46, 'Welding & Fab Tech', 4),
(47, 'Auto Mechanics', 4),
(48, 'Machine Shop Tech', 4),
(49, 'Welding & Fab', 4),
(50, 'HVACR Tech', 4),
(51, 'Apparel Tech', 4),
(52, 'Culinary Arts', 4),
(53, 'Ind Elec', 4),
(54, 'Cosmetology', 4),
(55, 'Auto Servicing', 4),
(56, 'Dom Refrig & Air Cond', 4),
(57, 'Comm Refrig & Air Cond', 4),
(58, 'Dressmaking', 4),
(59, 'Lathe Machine Op', 4),
(60, 'Comm Cooking', 4),
(61, 'Consumer Elec Tech', 4),
(62, 'Plate Welding (SMAW)', 4),
(63, 'BS in Entrep', 4),
(64, 'BS in Hosp Management', 4),
(65, 'BS in Tourism Management', 4),
(66, 'Cruiseship', 4);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(1, 'Library');

-- --------------------------------------------------------

--
-- Table structure for table `fundingsource`
--

CREATE TABLE `fundingsource` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fundingsource`
--

INSERT INTO `fundingsource` (`id`, `name`) VALUES
(1, 'funding source');

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
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `name`) VALUES
(1, 'College of Arts and Science'),
(2, 'College of Education'),
(3, 'College of Engineering and Architecture'),
(4, 'College of Industrial Technology');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `book_copy` varchar(255) NOT NULL,
  `IDno` varchar(255) NOT NULL,
  `reserved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `book_copy`, `IDno`, `reserved_at`) VALUES
(338, 'BOOK0000003', '2020-7656-A', '2025-03-30 07:39:58'),
(339, 'BOOK0000018', 'admin1', '2025-03-30 07:50:41'),
(340, 'BOOK0000019', 'admin1', '2025-03-30 07:50:42'),
(341, 'BOOK0000029', 'admin1', '2025-03-30 07:51:20'),
(342, 'BOOK0000006', '2020-7656-A', '2025-03-30 07:59:39');

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
(10, 'logo_67d7aee16a20e4.40671656.png', '#bbb9b9', '#ffffff', '#1905a3', '#ffd700', '#ffd700', '#1905a3', '#0056b3', '#003580', '#000000');

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
-- Table structure for table `sublocation`
--

CREATE TABLE `sublocation` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sublocation`
--

INSERT INTO `sublocation` (`id`, `name`) VALUES
(3, 'sublocation');

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
('2014-6823-A', 'KRIZZA', 'infante', 'JALECO', 'N/A', '', NULL, 'krizza@gmail.com', '09123456753', 'ILOILO CITY', 'MAGDALO', 'ILOILO', '1996-10-30', 'cas', 'BS in Info Systems', '4 A', '3', '', 'active', 'KRIZZA', 'KRIZZA@30', 'student', 'approved'),
('2018-1865-A', 'REYMARK', 'DALIDA', 'APUES', 'N/A', 'Male', NULL, 'marshall23@gmail.com', '09279731594', 'ESTANCIA, ILOILO', 'BULAQUEÑA', 'ILOILO', '1998-10-23', 'cas', '', '', '3', 'Non-Teaching Personnel', 'active', 'marshall', 'Marshall*23', 'faculty', 'approved'),
('2020-7656-A', 'Jane', 'Fanthear', 'Ban', 'N/A', 'Male', NULL, 'Jane@gmail.com', '09046789101', 'Ivisan Capiz', 'Agcabugao,Cuartero', 'Iloilo', '2001-07-11', 'cit', 'BS in Auto Tech (BSAT) - Level III', '2 B', '3', NULL, 'active', 'Jane34', 'Jane34', 'student', 'approved'),
('2020-9452-A', 'Yano', 'Tachibana', 'Yamamoshi', 'N/A', 'f', NULL, 'Yano@gmail.com', '09956789101', 'Pavia', 'Cabugao Sur', 'Iloilo', '2008-02-28', 'coe', 'BEEd ', '5 C', '3', NULL, 'active', 'Yano56', 'Yano56', 'student', 'approved'),
('2021-0909-A', 'Kenley', 'Tachibana', 'L', 'N/A', 'm', NULL, 'Kenley24@gmail.com', '09709543216', 'Jaro', 'San Jose', 'Iloilo', '2001-11-11', 'cit', 'BS in Elec Eng (BSELX) - Level III', '3 D', '3', NULL, 'active', 'Kenley753', 'Kenley753', 'student', 'approved'),
('2021-0994-A', 'William', 'Dickhead', 'T', 'N/A', 'm', NULL, 'William3@gmail.com', '09786543219', 'Pavia', 'Zone1', 'Iloilo', '2001-08-09', 'cit', 'BS in Hotel & Rest Tech (BSHRT) - Level II', '4 B', '3', NULL, 'active', 'William12', 'William12', 'student', 'rejected'),
('2021-1234-A', 'Allah', 'Muhhamad', 'D', 'N/A', 'm', NULL, 'allah1@gmail.com', '09956789101', 'Santa Barbara', 'Burgos st.', 'Iloilo', '2001-06-12', 'cas', 'BS in Comm Dev', '3 C', '3', NULL, 'active', 'allah123', 'allah123', 'student', 'rejected'),
('2021-1235-A', 'Brooke', 'Arbobro', 'L', 'N/A', 'm', NULL, 'Brooke09@gmail.com', '09709543216', 'Lapaz', 'San Jose', 'Iloilo', '2014-08-14', 'cas', 'BS in Info Systems', '3 A', '3', NULL, 'active', 'Brooke098', 'Brooke098', 'student', 'approved'),
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
('23-0082', 'LOUISE AGUSTIN', 'TEDIOS', 'LICUANAN`', 'N/A', '', NULL, 'louiaguted@gmail.com', '09369955702', 'ALIMODIAN, ILOILO', 'LAYLAYAN', 'ILOILO', '2001-08-20', 'cas', '', '', '3', 'Non-Teaching Personnel', 'active', 'LOUISE', 'Louise.20', 'faculty', 'approved'),
('admin1', 'Ad', 'In', 'M', '1', 'Male', NULL, 'vonjohn.suropia@students.isatu.edu.ph', '09111111111', '', '', '', '2025-01-01', 'Library', '', '', '3', 'Non-Teaching Personnel', 'active', 'admin1', 'admin1', 'admin', 'approved'),
('librarian1', 'libr', 'librarian', 'arian', 'N/A', 'Male', NULL, 'vonjohnsuropia116@gmail.com', '09000000000', 'Mlibrarian', 'Blibrarian', 'Plibrarian', '2024-12-01', 'cas', 'BS in English', '5 A', '3', NULL, 'active', 'librarian1', 'librarian1', 'librarian', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`id`, `name`) VALUES
(1, 'vendor');

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
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fundingsource`
--
ALTER TABLE `fundingsource`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_copy` (`book_copy`),
  ADD KEY `IDno` (`IDno`);

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
-- Indexes for table `sublocation`
--
ALTER TABLE `sublocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`IDno`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

--
-- AUTO_INCREMENT for table `book_copies`
--
ALTER TABLE `book_copies`
  MODIFY `book_copy_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1073;

--
-- AUTO_INCREMENT for table `borrow_book`
--
ALTER TABLE `borrow_book`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `coauthor`
--
ALTER TABLE `coauthor`
  MODIFY `co_author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fundingsource`
--
ALTER TABLE `fundingsource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

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
-- AUTO_INCREMENT for table `sublocation`
--
ALTER TABLE `sublocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users_info` (`IDno`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users_info` (`IDno`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`book_copy`) REFERENCES `book_copies` (`book_copy`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`IDno`) REFERENCES `users_info` (`IDno`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `fk_subject` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
