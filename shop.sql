-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2019 at 01:07 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`ID`, `Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(5, 'Games', 'Choose your favorite game', 0, 0, 0, 0, 1),
(6, 'Movies', 'Choose your best movie', 0, 1, 0, 1, 0),
(7, 'Smart Phone', 'Choose your mobile', 0, 2, 0, 0, 0),
(8, 'Computer', '', 0, 3, 1, 1, 1),
(9, 'Tools', '', 0, 4, 1, 0, 0),
(10, 'Samsung', 'Samsung Mobile', 7, 5, 0, 0, 0),
(11, 'Lenovo', 'Lenovo Laptop', 8, 6, 0, 0, 0),
(12, 'Call of Duty', 'Call of Duty Games', 5, 7, 0, 0, 0),
(13, 'Battlefield', 'Battlefield Games', 5, 8, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Com_ID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  `Com_date` date NOT NULL,
  `Item_id` int(11) NOT NULL,
  `Member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Com_ID`, `Comment`, `Status`, `Com_date`, `Item_id`, `Member_id`) VALUES
(1, 'hallo from', 0, '2018-01-10', 1, 4),
(2, 'hi from', 0, '2018-01-16', 2, 5),
(3, 'The Powerful Laptop', 0, '2018-01-19', 8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`) VALUES
(1, 'IPhone X', 'Apple', '$1000', '2018-01-04', 'US', '', '1', 0, 1, 7, 4),
(2, 'Baby Driver', 'Action', '$20', '2018-01-04', 'US', '', '1', 0, 1, 6, 1),
(3, 'Warfare', 'Call of Duty', '$100', '2018-01-06', 'US', '', '1', 0, 1, 5, 5),
(4, 'Modern Warfare 2', 'Call of Duty', '$100', '2018-01-06', 'US', '', '1', 0, 1, 5, 5),
(5, 'Modern Warfare 3', 'Call of Duty', '$100', '2018-01-06', 'US', '', '1', 0, 1, 5, 5),
(6, 'Battlefield ', 'EA', '$100', '2018-01-06', 'US', '', '1', 0, 1, 5, 4),
(7, 'Red Alert', 'Command & Conquer', '$60', '2018-01-06', 'Us', '', '1', 0, 1, 5, 4),
(8, 'ThinkPad', 'Lenovo', '$3000', '2018-01-06', 'US', '', '1', 0, 1, 8, 5),
(9, 'Hummer', 'For Wood', '10', '2018-01-24', 'EG', '', '1', 0, 1, 9, 5),
(10, 'Google Pixel II', 'The Best Smart Phone The Best Smart Phone The Best Smart Phone Phone', '1000', '2018-01-24', 'US', '', '1', 0, 0, 7, 5),
(11, 'MacPro', 'Apple Laptop', '3000', '2018-01-24', 'CH', '', '1', 0, 0, 8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'User Group',
  `trustStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'user Approval',
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `trustStatus`, `RegStatus`, `Date`) VALUES
(1, 'sambo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sambo@sam.com', 'Abdallah', 1, 0, 1, '0000-00-00'),
(2, 'Aya', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'aya@sam.com', 'Aya Essam', 0, 0, 0, '0000-00-00'),
(3, 'Ahmed', '51eac6b471a284d3341d8c0c63d0f1a286262a18', 'ahmed@sam.com', 'Ahmed Amin', 0, 0, 0, '0000-00-00'),
(4, 'Anas', '51eac6b471a284d3341d8c0c63d0f1a286262a18', 'anas@sam.com', 'Anas', 0, 0, 0, '0000-00-00'),
(5, 'Abdo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'abdo@sam.com', 'Abdo', 0, 0, 1, '0000-00-00'),
(6, 'Omar', '51eac6b471a284d3341d8c0c63d0f1a286262a18', 'omar@sam.com', 'Omar', 0, 0, 1, '0000-00-00'),
(7, 'Mego', '51eac6b471a284d3341d8c0c63d0f1a286262a18', 'mego@sam.com', '', 0, 0, 0, '2018-01-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Com_ID`),
  ADD KEY `com_item` (`Item_id`),
  ADD KEY `com_member` (`Member_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member` (`Member_ID`),
  ADD KEY `cat` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Com_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `com_item` FOREIGN KEY (`Item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `com_member` FOREIGN KEY (`Member_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat` FOREIGN KEY (`Cat_ID`) REFERENCES `category` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
