-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2015 at 07:36 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cms`
--
CREATE DATABASE IF NOT EXISTS `cms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cms`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `postID` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `approved` varchar(255) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`ID`, `userID`, `postID`, `content`, `date`, `approved`) VALUES
(1, 1, 2, 'Hey! This is a comment! Isn''t this cool?', '2015-07-08 05:15:31', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`ID`, `name`, `value`) VALUES
(1, 'SITE_NAME', 'CMS'),
(2, 'SITE_SLOGAN', 'This is a CMS!'),
(3, 'TEMPLATE', 'default'),
(4, 'ADMIN_TEMPLATE', 'default'),
(5, 'FOOTER_CONTENT', '&copy; Pioneer Magazine 2014-2015. Site by Pioneer.');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`ID`, `name`, `token`) VALUES
(1, 'login', 'f29cd97224911bb1bbd0f4c9038fe91e'),
(2, 'login', 'e506b4e5ea3560ecb0ff009262f50276'),
(3, 'login', '1f9287429d6f629f016b9b7ba1f72190'),
(4, 'login', 'f6b153e5298a4956449767880c1a85bb'),
(5, 'login', '9ec7dc96780851131b7e9f3caec6233e');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `publish` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`ID`, `name`, `description`, `date`, `publish`) VALUES
(1, 'online', 'Online content published here.', '2015-07-06', 'published');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `authorID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` datetime NOT NULL,
  `publish` varchar(255) NOT NULL DEFAULT 'draft',
  `issueID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`ID`, `authorID`, `name`, `title`, `body`, `dateCreated`, `dateModified`, `publish`, `issueID`) VALUES
(1, 1, 'firstArticle', 'First Article', '<p>Hello world!</p>', '2015-07-06 04:00:00', '2015-07-06 00:00:00', 'published', 1),
(2, 1, 'pioneer!', 'Welcome to the Pioneer!', '<p>This is the Pioneer site!</p>', '2015-07-08 20:32:19', '2015-07-08 00:00:00', 'publish', 1),
(3, 1, 'gobme', 'Go BMES!', '<p>BME is the best Major!</p>', '2015-07-08 21:04:32', '2015-07-08 05:17:28', 'published', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(255) NOT NULL,
  `capabilities` varchar(255) NOT NULL,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleID`, `roleName`, `capabilities`) VALUES
(-2, 'Banned', 'banned'),
(-1, 'Guest', 'viewPosts'),
(0, 'Admin', 'viewPosts/editPosts/newPosts/deletePosts/flagPosts/approvePosts/newComment/editComment/deleteComment/flagComment/approveComments/adminDash/editDash/moderate/addUsers/editUsers/removeUsers/banUsers/settings'),
(1, 'Moderator', 'viewPosts/editPosts/newPosts/deletePosts/flagPosts/approvePosts/newComment/editComment/deleteComment/flagComment/approveComments/adminDash/editDash/moderate/addUsers/banUsers/'),
(2, 'Writer', 'viewPosts/editPosts/newPosts/deletePosts/flagPosts/approvePosts/newComment/flagComment/adminDash/');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `Num` int(11) NOT NULL AUTO_INCREMENT,
  `sessionID` text NOT NULL,
  `userID` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `lastOnline` datetime NOT NULL,
  `rememberMe` varchar(255) NOT NULL,
  UNIQUE KEY `Null` (`Num`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`Num`, `sessionID`, `userID`, `type`, `IP`, `lastOnline`, `rememberMe`) VALUES
(12, 'e73nd6nsblrtmjn73bp05gmce2', 1, 1, '::1', '2015-07-10 08:45:19', ''),
(13, 'nuo2j7lladbkq484gdkqb4lt26', -1, 0, '::1', '2015-07-10 08:56:43', ''),
(14, 'cep86g303cqedb48svtufah0b1', -1, 0, '::1', '2015-07-10 09:06:54', ''),
(15, 'jojh7p8he0d3gnf9jrdgasb763', -1, 0, '::1', '2015-07-10 09:07:35', ''),
(16, 'sf81tgkooth2mpo81dgoe4jbi0', -1, 0, '::1', '2015-08-08 09:39:25', ''),
(17, 'voj2spre1vp2o9eqr7i6ucva77', 1, 1, '::1', '2015-08-08 12:35:41', ''),
(18, 'g7f7nigq91cqu4k7p40ha8sp87', -1, 0, '::1', '2015-08-10 19:11:57', ''),
(19, '08i3bqdpe2teeiv4qrabjr7qu5', -1, 0, '::1', '2015-08-11 17:17:55', ''),
(20, 'aclj3t4kffuju9bro3s7oovbu6', -1, 0, '::1', '2015-08-12 01:32:14', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `major` varchar(255) NOT NULL,
  `graduationDate` date NOT NULL,
  `DOB` date NOT NULL,
  `country` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `dateJoined` datetime NOT NULL,
  `roleID` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `firstName`, `lastName`, `email`, `password`, `phone`, `website`, `major`, `graduationDate`, `DOB`, `country`, `bio`, `dateJoined`, `roleID`) VALUES
(1, 'shibo', 'Shehab', 'Attia', 'shibo@gatech.edu', '$2y$10$4jjwvO3ct0x35lIT7qAxm.deIXO2b4lxOCvg2VsWAH3WSh/AaWQD2', '407-5178-407', 'prism.gatech.edu/~sattia6', 'BME', '2018-05-01', '2014-03-04', 'Egypt!', 'I''m a web developer BME who likes chocolate and mango ice cream!', '2015-05-24 03:00:00', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
