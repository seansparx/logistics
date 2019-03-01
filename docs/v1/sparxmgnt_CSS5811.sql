-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2017 at 05:51 PM
-- Server version: 5.6.33-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sparxmgnt_CSS5811`
--

-- --------------------------------------------------------

--
-- Table structure for table `ld_adminlevel`
--

DROP TABLE IF EXISTS `ld_adminlevel`;
CREATE TABLE IF NOT EXISTS `ld_adminlevel` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `ld_adminlevel`
--

INSERT INTO `ld_adminlevel` (`id`, `name`, `status`) VALUES
(-1, 'admin', '1'),
(18, 'TEST1', '1'),
(19, 'TEST12', '1'),
(21, 'Rajesh Yadav', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ld_adminlogin`
--

DROP TABLE IF EXISTS `ld_adminlogin`;
CREATE TABLE IF NOT EXISTS `ld_adminlogin` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `emailId` varchar(250) NOT NULL,
  `userImage` varchar(255) NOT NULL,
  `hash` varchar(250) NOT NULL,
  `adminLevelId` int(5) NOT NULL DEFAULT '0',
  `lastLogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `addDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `addedBy` tinyint(2) NOT NULL DEFAULT '0',
  `modDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modBy` tinyint(2) NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `ld_adminlogin`
--

INSERT INTO `ld_adminlogin` (`id`, `username`, `password`, `emailId`, `userImage`, `hash`, `adminLevelId`, `lastLogin`, `addDate`, `addedBy`, `modDate`, `modBy`, `status`) VALUES
(1, 'admin', '9f5e50d369f302c14743ea09f8cc83b6:d4', 'corephp0@gmail.com', 'avatar2.jpg', '6cb3ae1204d48d623f889eed034dd490', -1, '2017-01-27 12:07:07', '2013-08-06 13:41:09', 1, '2015-02-27 03:07:26', 0, '1'),
(16, 'TEST1', '9f5e50d369f302c14743ea09f8cc83b6:d4', 'amit.kumar@sparxitsolutions.com', 'lion_king_3-wallpaper-800x6002.jpg', 'f490f09d7ff8c56a7c6fb4eef521d2e9', 18, '2016-01-08 13:28:59', '2015-05-27 16:06:30', 0, '2016-01-08 13:28:49', 0, '1'),
(17, 'TEST12', '9f5e50d369f302c14743ea09f8cc83b6:d4', 'kabhi3202@gmail.com', 'cursed-wallpaper-1366x768.jpg', 'a32167dee7b43f6ff292818d1e8ade73', 19, '0000-00-00 00:00:00', '2015-05-27 16:07:53', 0, '2016-01-08 13:28:42', 0, '1'),
(19, 'Rajesh Yadav', 'efa4e1924994fdf0e1b82bcb66e301a5:d4', 'rajesh.yadav@sparxitsolutions.com', 'aptitute1.jpeg', 'aa41f725fd51791358f32b8a3950999c', 21, '2016-09-06 11:21:55', '2016-09-06 10:37:27', 0, '0000-00-00 00:00:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `ld_adminpermission`
--

DROP TABLE IF EXISTS `ld_adminpermission`;
CREATE TABLE IF NOT EXISTS `ld_adminpermission` (
  `adminLevelId` int(11) NOT NULL DEFAULT '0',
  `menuid` int(5) NOT NULL DEFAULT '0',
  `add_record` enum('0','1') DEFAULT '0',
  `edit_record` enum('0','1') DEFAULT '0',
  `delete_record` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ld_adminpermission`
--

INSERT INTO `ld_adminpermission` (`adminLevelId`, `menuid`, `add_record`, `edit_record`, `delete_record`) VALUES
(-1, 42, '1', '1', '1'),
(-1, 20, '1', '1', '1'),
(-1, 19, NULL, NULL, NULL),
(-1, 41, '1', '1', '1'),
(-1, 40, '1', '1', '1'),
(-1, 39, '1', '1', '1'),
(-1, 35, '1', '1', '1'),
(-1, 34, '1', '1', '1'),
(-1, 33, '1', '1', '1'),
(-1, 32, '1', '1', '1'),
(-1, 30, '1', '1', '1'),
(-1, 29, '1', '1', '1'),
(-1, 28, '1', '1', '1'),
(-1, 27, '1', '1', '1'),
(-1, 18, '1', '1', '1'),
(-1, 16, '1', '1', '1'),
(-1, 15, '1', '1', '1'),
(-1, 14, '1', '1', '1'),
(-1, 12, '1', '1', '1'),
(-1, 9, '1', '1', '1'),
(-1, 8, '1', '1', '1'),
(-1, 7, '1', '1', '1'),
(-1, 6, NULL, NULL, NULL),
(-1, 5, '1', '1', '1'),
(-1, 4, '1', '1', '1'),
(-1, 2, NULL, NULL, NULL),
(-1, 1, NULL, NULL, NULL),
(-1, 43, '1', '1', '1'),
(-1, 44, '1', '1', '1'),
(-1, 45, '1', '1', '1'),
(-1, 46, '1', '1', '1'),
(-1, 46, '1', '1', '1'),
(16, 44, '1', '1', '1'),
(16, 45, '1', '1', '1'),
(19, 1, NULL, NULL, NULL),
(19, 2, NULL, NULL, NULL),
(19, 4, '1', '1', '1'),
(19, 5, '1', '1', '1'),
(19, 44, NULL, NULL, NULL),
(19, 45, '1', '1', '1'),
(19, 46, '1', '1', '1'),
(18, 1, NULL, NULL, NULL),
(18, 2, NULL, NULL, NULL),
(18, 4, '1', '1', '1'),
(18, 5, '1', '1', '1'),
(18, 44, NULL, NULL, NULL),
(18, 45, '1', '1', '1'),
(18, 46, '1', '1', '1'),
(-1, 47, '1', '1', '1'),
(-1, 48, '1', '1', '1'),
(-1, 49, '1', '1', '1'),
(-1, 50, '1', '1', '1'),
(21, 1, NULL, NULL, NULL),
(21, 2, NULL, NULL, NULL),
(21, 4, '1', '1', '1'),
(21, 5, '1', '1', '1'),
(-1, 51, '1', '1', '1'),
(-1, 52, '1', '1', '1'),
(-1, 53, '1', '1', '1'),
(-1, 54, '1', '1', '1'),
(-1, 55, '1', '1', '1'),
(-1, 56, '1', '1', '1'),
(-1, 57, '1', '1', '1'),
(-1, 58, '1', '1', '1'),
(-1, 59, '1', '1', '1'),
(-1, 60, '1', '1', '1'),
(-1, 60, '1', '1', '1'),
(-1, 61, '1', '1', '1'),
(-1, 62, '1', '1', '1'),
(-1, 63, '1', '1', '1'),
(-1, 64, '1', '1', '1'),
(-1, 65, '1', '1', '1'),
(-1, 66, '1', '1', '1'),
(-1, 67, '1', '1', '1'),
(-1, 68, '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ld_department`
--

DROP TABLE IF EXISTS `ld_department`;
CREATE TABLE IF NOT EXISTS `ld_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `ld_department`
--

INSERT INTO `ld_department` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'sparx', 'the quickspard ', 'active', '2017-01-24 00:00:00', '2017-01-27 08:58:39', NULL),
(10, 'Test Department', '  test  ', 'inactive', '2017-01-27 10:45:40', '2017-01-27 11:36:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ld_employees`
--

DROP TABLE IF EXISTS `ld_employees`;
CREATE TABLE IF NOT EXISTS `ld_employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_name` varchar(100) NOT NULL,
  `state` varchar(255) NOT NULL,
  `contract` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `ld_employees`
--

INSERT INTO `ld_employees` (`id`, `emp_name`, `state`, `contract`, `category`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(13, 'Sean rock', 'assign', 'fixed', 'driver', 'active', '2017-01-23 10:00:17', '2017-01-27 07:54:39', NULL),
(14, 'Sean Rocks', 'on leave', 'part-time', 'services', 'active', '2017-01-23 10:15:40', '2017-01-27 06:04:33', NULL),
(15, 'fhdfg', 'on leave', 'fixed', 'driver', 'inactive', '2017-01-23 10:17:35', '2017-01-23 10:17:35', 2017),
(16, 'Test Employee', 'assign', 'part-time', 'services', 'inactive', '2017-01-23 10:24:01', '2017-01-23 10:24:07', 2017),
(17, 'rtytryrtyrty', 'on leave', 'fixed', 'driver', 'inactive', '2017-01-27 05:41:51', '2017-01-27 05:41:57', 2017),
(18, 'Emp 001', 'assign', 'part-time', 'exhibitions', 'inactive', '2017-01-27 05:53:50', '2017-01-27 11:07:24', NULL),
(19, 'Emp 002', 'assign', 'fixed', 'services', 'active', '2017-01-27 06:01:32', '2017-01-27 06:49:46', NULL),
(20, 'Emp 003', 'assign', 'fixed', 'services', 'active', '2017-01-27 06:02:36', '2017-01-27 06:49:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ld_language`
--

DROP TABLE IF EXISTS `ld_language`;
CREATE TABLE IF NOT EXISTS `ld_language` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(250) NOT NULL,
  `language_code` varchar(250) NOT NULL,
  `language_flag` varchar(250) NOT NULL,
  `isDefault` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `addedBy` tinyint(2) NOT NULL DEFAULT '0',
  `modBy` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ld_language`
--

INSERT INTO `ld_language` (`id`, `language_name`, `language_code`, `language_flag`, `isDefault`, `status`, `addedBy`, `modBy`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'English', 'en', 'england2.png', '1', '1', 1, 1, 1417611098, 1417611098, NULL),
(2, 'Swedish', 'se', 'Swidden1.png', '0', '1', 1, 0, 1417611098, 1418878890, 1433228090),
(6, 'French', 'fr', 'th.jpg', '0', '1', 1, 0, 1418719483, 1422964298, 1433228090),
(8, 'test', 'te', '', '0', '1', 0, 0, 1421755441, 1421755459, 1421814108),
(9, 'Norwegian', 'no', 'norway220pxl.jpg', '0', '1', 0, 0, 1433228333, 1443089385, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ld_menu`
--

DROP TABLE IF EXISTS `ld_menu`;
CREATE TABLE IF NOT EXISTS `ld_menu` (
  `menuId` int(5) NOT NULL AUTO_INCREMENT,
  `menuName` varchar(100) NOT NULL DEFAULT '',
  `menuUrl` varchar(100) NOT NULL DEFAULT '',
  `menuImage` varchar(255) NOT NULL,
  `menuClass` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `parentId` int(5) NOT NULL DEFAULT '0',
  `menu_type` enum('0','1') NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `ld_menu`
--

INSERT INTO `ld_menu` (`menuId`, `menuName`, `menuUrl`, `menuImage`, `menuClass`, `status`, `parentId`, `menu_type`, `sort_order`) VALUES
(1, 'Dashboard', 'adminarea.php', 'left-icon7.png', 'glyphicons dashboard', 1, 0, '1', 0),
(2, 'Configuration Manager', '#', 'icon_configuration_manager.png', 'glyphicons settings', 1, 0, '1', 0),
(4, 'Administrator', 'manageadministrator.php', '', '', 0, 2, '0', 0),
(5, 'System Configuration', 'systemconfiguration.php', '', '', 1, 2, '0', 0),
(44, 'Layout', '#', '', '', 0, 0, '1', 0),
(45, 'Manage Layout', 'layout/manage', '', '', 1, 44, '0', 0),
(46, 'Manage Pages', 'managepages/manage', '', '', 1, 44, '0', 0),
(47, 'Variants', '#', '', '', 1, 0, '1', 0),
(48, 'Manage banner area', 'variants/manage', '', '', 1, 47, '0', 0),
(49, 'Manage form area', 'variants/form_area', '', '', 1, 47, '0', 0),
(50, 'Manage step area', 'variants/step_area', '', '', 1, 47, '0', 0),
(51, 'Manage about area', 'variants/about_area', '', '', 1, 47, '0', 0),
(52, 'Manage compare area', 'variants/compare_area', '', '', 1, 47, '0', 0),
(53, 'Manage testimonial area', 'variants/testimonial_area', '', '', 1, 47, '0', 0),
(54, 'Manage  form2 area', 'variants/form2_area', '', '', 1, 47, '0', 0),
(55, 'Landing Page', '#', '', '', 1, 0, '1', 0),
(56, 'Manage Landing Page', 'layout/manage', '', '', 1, 55, '0', 0),
(57, 'Brand', '#', '', '', 1, 0, '1', 0),
(58, 'Manage Department', 'department/manage', '', '', 1, 57, '0', 0),
(59, 'Test', '#', '', '', 1, 0, '1', 0),
(60, 'Manage Test', 'test/manage', '', '', 1, 59, '0', 0),
(61, 'Test Urls', 'test/test_url', '', '', 1, 59, '0', 0),
(62, 'Result & Optimation', '#', '', '', 1, 0, '1', 0),
(63, 'Test Optimization', 'optimization/manage', '', '', 1, 62, '0', 0),
(64, 'Network', '#', '', '', 1, 0, '1', 0),
(65, 'Manage Networks', 'network/manage', '', '', 1, 64, '0', 0),
(66, 'Manage Archive Data', 'network/archive', '', '', 1, 64, '0', 0),
(67, 'Manage Networks Data', 'network/data', '', '', 1, 64, '0', 0),
(68, 'Manage Header Footer', 'systemconfiguration/add', '', '', 1, 2, '0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ld_menuposition`
--

DROP TABLE IF EXISTS `ld_menuposition`;
CREATE TABLE IF NOT EXISTS `ld_menuposition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ld_menuposition`
--

INSERT INTO `ld_menuposition` (`id`, `position`) VALUES
(1, 'Header Top'),
(2, 'Footer'),
(3, 'Footer Bottom');

-- --------------------------------------------------------

--
-- Table structure for table `ld_projects`
--

DROP TABLE IF EXISTS `ld_projects`;
CREATE TABLE IF NOT EXISTS `ld_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ld_projects`
--

INSERT INTO `ld_projects` (`id`, `code`, `customer_name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', 'sparx', 'the quickspard', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2017),
(2, '', 'schools', 'the quick brown fox', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2017),
(3, '', 'fadfadsfadsf adsfasd', 'dfadsfad  ', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1485149332),
(4, '', 'dfadsfa', ' fadsfads', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1484914401),
(5, '', 'Accounts', 'dggdfg ', '0000-00-00 00:00:00', '2017-01-23 08:57:53', 2017),
(6, '', 'testsss', ' dfgdfg ssss', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1485156352),
(7, 'SAMPLECODE', 'REGN034654', ' MODEL890DFGDFSG', '0000-00-00 00:00:00', '2017-01-23 09:35:21', NULL),
(8, 'CODECODE', 'REGN34896904', 'The quick', '0000-00-00 00:00:00', '2017-01-23 09:35:06', NULL),
(9, '', 'ryry', 'reyrty', '2017-01-23 08:57:21', '0000-00-00 00:00:00', 2017),
(10, 'CODE001AA', 'CUSTNAMEBB', 'the quick brown foxthe quick brown foxthe quick brown fox', '2017-01-23 09:31:57', '2017-01-23 09:34:47', NULL),
(11, '6345', 'rteyrte', 'fghdhfgdfhfghdfhfh', '2017-01-23 09:36:00', '2017-01-23 09:36:00', 2017);

-- --------------------------------------------------------

--
-- Table structure for table `ld_services`
--

DROP TABLE IF EXISTS `ld_services`;
CREATE TABLE IF NOT EXISTS `ld_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `service_title` varchar(100) NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `service_desc` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ld_services`
--

INSERT INTO `ld_services` (`id`, `start_date`, `end_date`, `start_time`, `end_time`, `service_title`, `department_id`, `project_id`, `service_desc`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2017-01-25', '2017-01-30', '08:00:00', '17:00:00', 'Service 2', 1, 8, 'the quick brown fox jumps ', '2017-01-25 11:47:41', '2017-01-25 11:47:41', NULL),
(2, '2017-02-01', '2017-02-07', '10:00:00', '19:00:00', 'Service 1', 1, 10, 'thds dfsgdf', '2017-01-25 11:52:33', '2017-01-25 11:52:33', NULL),
(3, '2017-03-01', '2017-03-10', '00:00:00', '00:00:00', 'Service 3', 1, 7, 'dsafsd dsfsd', '2017-01-25 13:40:33', '2017-01-25 13:40:33', NULL),
(4, '2017-03-22', '2017-03-23', '00:00:00', '00:00:00', 'Service 4', 1, 7, 'dfg fdgdfgdf g', '2017-01-25 13:41:48', '2017-01-25 13:41:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ld_services_detail`
--

DROP TABLE IF EXISTS `ld_services_detail`;
CREATE TABLE IF NOT EXISTS `ld_services_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(10) unsigned NOT NULL,
  `service_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `employee_id` varchar(250) NOT NULL,
  `vehicle_id` varchar(250) NOT NULL,
  `ut` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `ld_services_detail`
--

INSERT INTO `ld_services_detail` (`id`, `service_id`, `service_date`, `start_time`, `end_time`, `employee_id`, `vehicle_id`, `ut`) VALUES
(1, 1, '2017-01-25', '08:00:00', '17:00:00', '13', '7', '2017-01-25 11:47:41'),
(2, 1, '2017-01-26', '08:00:00', '17:00:00', '13', '7', '2017-01-25 11:47:41'),
(3, 1, '2017-01-27', '08:00:00', '17:00:00', '13', '7', '2017-01-25 11:47:41'),
(4, 1, '2017-01-28', '08:00:00', '17:00:00', '13', '7', '2017-01-25 11:47:41'),
(5, 1, '2017-01-29', '08:00:00', '17:00:00', '13', '7', '2017-01-25 11:47:41'),
(6, 1, '2017-01-30', '08:00:00', '17:00:00', '13', '7', '2017-01-25 11:47:41'),
(7, 2, '2017-02-01', '10:00:00', '19:00:00', '12', '7,8', '2017-01-25 11:52:33'),
(8, 2, '2017-02-02', '10:00:00', '19:00:00', '12', '7,8', '2017-01-25 11:52:33'),
(9, 2, '2017-02-03', '10:00:00', '19:00:00', '12', '7,8', '2017-01-25 11:52:33'),
(10, 2, '2017-02-04', '10:00:00', '19:00:00', '12', '7,8', '2017-01-25 11:52:33'),
(11, 2, '2017-02-05', '10:00:00', '19:00:00', '12', '7,8', '2017-01-25 11:52:33'),
(12, 2, '2017-02-06', '10:00:00', '19:00:00', '12', '7,8', '2017-01-25 11:52:33'),
(13, 2, '2017-02-07', '10:00:00', '19:00:00', '12', '7,8', '2017-01-25 11:52:33'),
(14, 3, '2017-03-01', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(15, 3, '2017-03-02', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(16, 3, '2017-03-03', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(17, 3, '2017-03-04', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(18, 3, '2017-03-05', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(19, 3, '2017-03-06', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(20, 3, '2017-03-07', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(21, 3, '2017-03-08', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(22, 3, '2017-03-09', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(23, 3, '2017-03-10', '00:00:00', '00:00:00', '12,14', '8', '2017-01-25 13:40:33'),
(24, 4, '2017-03-22', '00:00:00', '00:00:00', '13,14', '8', '2017-01-25 13:41:49'),
(25, 4, '2017-03-23', '00:00:00', '00:00:00', '13,14', '8', '2017-01-25 13:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `ld_sessiondetail`
--

DROP TABLE IF EXISTS `ld_sessiondetail`;
CREATE TABLE IF NOT EXISTS `ld_sessiondetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessionId` varchar(50) CHARACTER SET latin1 NOT NULL,
  `adminId` int(5) NOT NULL DEFAULT '0',
  `ipAddress` varchar(30) CHARACTER SET latin1 NOT NULL,
  `signInDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `signOutDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `signDate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `ld_sessiondetail`
--

INSERT INTO `ld_sessiondetail` (`id`, `sessionId`, `adminId`, `ipAddress`, `signInDateTime`, `signOutDateTime`, `signDate`) VALUES
(1, '4ad5dc7b948542a3c9a8079a43e8b789', 1, '10.0.0.1', '2017-01-06 13:50:47', '0000-00-00 00:00:00', '2017-01-06'),
(2, '837ef639da6a3dabff0539e4fe97fdba', 1, '46.196.49.113', '2017-01-06 14:05:24', '0000-00-00 00:00:00', '2017-01-06'),
(3, 'fe23e3dbe406d0727b72ee2e3e854c2a', 1, '46.196.49.113', '2017-01-06 14:10:18', '0000-00-00 00:00:00', '2017-01-06'),
(4, '9ed84b90e45e61286303c2629789fcbd', 1, '46.196.49.113', '2017-01-06 21:04:35', '0000-00-00 00:00:00', '2017-01-06'),
(5, '5f5bad7cfd197bf2015c9136dbb8c1a7', 1, '103.77.186.183', '2017-01-07 05:05:52', '0000-00-00 00:00:00', '2017-01-07'),
(6, '96e037f441afa8ad38ef3c0802003024', 1, '103.77.186.183', '2017-01-07 18:21:47', '0000-00-00 00:00:00', '2017-01-07'),
(7, 'ff839ebea165f7d875bbb8e11a0850f5', 1, '103.77.186.183', '2017-01-08 03:24:52', '0000-00-00 00:00:00', '2017-01-08'),
(8, '632329424e77a479202608344f683501', 1, '103.77.186.183', '2017-01-08 10:59:06', '0000-00-00 00:00:00', '2017-01-08'),
(9, '44da998d50d26e62b2ed12cf46593d7c', 1, '46.196.49.113', '2017-01-08 14:10:07', '0000-00-00 00:00:00', '2017-01-08'),
(10, '44da998d50d26e62b2ed12cf46593d7c', 1, '46.196.49.113', '2017-01-08 14:13:57', '0000-00-00 00:00:00', '2017-01-08'),
(11, '3985e1e81a0819c527a17a0917bedda1', 1, '103.72.6.37', '2017-01-08 16:40:17', '0000-00-00 00:00:00', '2017-01-08'),
(12, 'ea926dbf0d742b9dcb30410db943f8f9', 1, '46.196.49.113', '2017-01-08 20:16:54', '0000-00-00 00:00:00', '2017-01-08'),
(13, 'dac682dc5cc7452c6cdc1f5032801bab', 1, '46.196.49.113', '2017-01-08 20:37:34', '0000-00-00 00:00:00', '2017-01-08'),
(14, '710c5ca5d648ff5ef4f590137455f6b4', 1, '46.196.49.113', '2017-01-09 02:04:23', '0000-00-00 00:00:00', '2017-01-09'),
(15, '73e0b4305c14f8009ee12847769d1829', 1, '103.72.6.37', '2017-01-09 02:23:34', '0000-00-00 00:00:00', '2017-01-09'),
(16, '2007fb8600fce19de742eb5c7ee61228', 1, '46.196.49.113', '2017-01-09 02:41:27', '0000-00-00 00:00:00', '2017-01-09'),
(17, 'd09dee3155f039e04e764aa6f6202550', 1, '10.0.0.1', '2017-01-09 05:01:40', '0000-00-00 00:00:00', '2017-01-09'),
(18, '32c896dd0f92e3138b0bbfc25ac2042a', 1, '10.0.0.176', '2017-01-09 05:39:03', '0000-00-00 00:00:00', '2017-01-09'),
(19, '30fa7ae605e660b75d25c4c4d20955f5', 1, '10.0.0.66', '2017-01-09 07:37:12', '0000-00-00 00:00:00', '2017-01-09'),
(20, 'c4aaa979846692cac90f89956258f47f', 1, '10.0.0.65', '2017-01-09 09:04:57', '0000-00-00 00:00:00', '2017-01-09'),
(21, 'e32e69b521ef40323ce5dd1b098364b2', 1, '10.0.0.66', '2017-01-10 04:19:17', '0000-00-00 00:00:00', '2017-01-10'),
(22, 'a15933a3ef2a5e8a91a00afe84c9fae6', 1, '10.0.0.66', '2017-01-11 10:34:34', '0000-00-00 00:00:00', '2017-01-11'),
(23, '73328a95179a20ece6e918d23df6284c', 1, '10.0.0.66', '2017-01-13 04:08:56', '0000-00-00 00:00:00', '2017-01-13'),
(24, '6abaf453c1f57d4341b47caded0dec04', 1, '10.0.0.65', '2017-01-13 04:38:40', '0000-00-00 00:00:00', '2017-01-13'),
(25, 'e63dea623ca4b8fee96262a5f4b1f30e', 1, '10.0.0.66', '2017-01-13 11:01:15', '0000-00-00 00:00:00', '2017-01-13'),
(26, '4fc7c4a570f9c39e5540f91a906aa6c2', 1, '10.0.0.66', '2017-01-16 04:17:22', '0000-00-00 00:00:00', '2017-01-16'),
(27, 'ecdaea675717d0c59025103e514110ad', 1, '10.0.0.65', '2017-01-16 04:52:28', '0000-00-00 00:00:00', '2017-01-16'),
(28, '8c220d645537be4b2722ca8dd6c1594a', 1, '10.0.0.66', '2017-01-16 05:31:50', '0000-00-00 00:00:00', '2017-01-16'),
(29, '8c735fa99bdfddd0a857722cf11ed554', 1, '10.0.0.65', '2017-01-16 11:44:28', '0000-00-00 00:00:00', '2017-01-16'),
(30, 'dd47c8ca6d0254ee96351c818b4e1ea9', 1, '10.0.0.66', '2017-01-16 12:14:58', '0000-00-00 00:00:00', '2017-01-16'),
(31, '5632504fb3be6f9de91ae3ce7f692aac', 1, '10.0.0.66', '2017-01-17 04:37:33', '0000-00-00 00:00:00', '2017-01-17'),
(32, '6b9be264ce74ba726786061048ed63fa', 1, '10.0.0.65', '2017-01-17 08:42:20', '0000-00-00 00:00:00', '2017-01-17'),
(33, '93605ea740540c9674c697c50e31885c', 1, '10.0.0.66', '2017-01-17 08:59:17', '0000-00-00 00:00:00', '2017-01-17'),
(34, '20f5e488c55186c9cde3682c8e398079', 1, '10.0.0.66', '2017-01-17 09:22:31', '0000-00-00 00:00:00', '2017-01-17'),
(35, 'a40dffa803777ad9680a9f6b0da2c86a', 1, '10.0.0.66', '2017-01-17 09:29:28', '0000-00-00 00:00:00', '2017-01-17'),
(36, 'c40c19a0c0b798dc2ec6881476ad38f1', 1, '10.0.0.67', '2017-01-17 10:00:01', '0000-00-00 00:00:00', '2017-01-17'),
(37, 'd1c02f1a6a2a6ead873dd18d76e16258', 1, '10.0.0.66', '2017-01-17 13:49:42', '0000-00-00 00:00:00', '2017-01-17'),
(38, 'b155acbb138e9a7a6a7f2d27217a056b', 1, '10.0.0.65', '2017-01-18 04:59:18', '0000-00-00 00:00:00', '2017-01-18'),
(39, '663b019a884705a0a86a1f8ca7ea24b1', 1, '10.0.0.66', '2017-01-18 05:20:34', '0000-00-00 00:00:00', '2017-01-18'),
(40, '7dd0a3d234d362b20df6a1f9460c5cbf', 1, '10.0.0.66', '2017-01-18 11:52:50', '0000-00-00 00:00:00', '2017-01-18'),
(41, 'e181b0c25dc43648d9ace08dd2844fa3', 1, '10.0.0.66', '2017-01-18 14:25:58', '0000-00-00 00:00:00', '2017-01-18'),
(42, '43f294649e7b9677339beb3e19c69ea4', 1, '10.0.0.65', '2017-01-18 14:26:13', '0000-00-00 00:00:00', '2017-01-18'),
(43, 'bf5d45f17c51fd437911121daafbad02', 1, '10.0.0.66', '2017-01-18 14:33:57', '0000-00-00 00:00:00', '2017-01-18'),
(44, 'a1272d541996495e09ee7f617c092241', 1, '10.0.0.65', '2017-01-19 04:28:36', '0000-00-00 00:00:00', '2017-01-19'),
(45, 'a9cefeccf73869b326be6a9cdfc826f8', 1, '10.0.0.66', '2017-01-19 04:57:56', '0000-00-00 00:00:00', '2017-01-19'),
(46, '769af18f240478451df4291d0207859b', 1, '10.0.0.66', '2017-01-19 09:55:23', '0000-00-00 00:00:00', '2017-01-19'),
(47, 'e9bd5d25f3a4affc4fb89c51a3160b89', 1, '10.0.0.65', '2017-01-19 12:31:24', '0000-00-00 00:00:00', '2017-01-19'),
(48, '67fc348785aaf3f0bc6b661ea44055c3', 1, '10.0.0.66', '2017-01-20 04:47:15', '0000-00-00 00:00:00', '2017-01-20'),
(49, '0ef7b46a78641daed61bb7fa1eb8a1e3', 1, '10.0.0.66', '2017-01-20 06:40:49', '0000-00-00 00:00:00', '2017-01-20'),
(50, '29004c220ef4a509d6e1e221ba805dad', 1, '10.0.0.66', '2017-01-20 09:52:46', '0000-00-00 00:00:00', '2017-01-20'),
(51, '616413256dd6e97ab25b8238822f59fb', 1, '10.0.0.66', '2017-01-20 10:01:14', '0000-00-00 00:00:00', '2017-01-20'),
(52, '616413256dd6e97ab25b8238822f59fb', 1, '10.0.0.66', '2017-01-20 10:04:14', '0000-00-00 00:00:00', '2017-01-20'),
(53, '96bb9c9691473f156632adc19a80cdfa', 1, '10.0.0.66', '2017-01-20 10:13:15', '0000-00-00 00:00:00', '2017-01-20'),
(54, '96bb9c9691473f156632adc19a80cdfa', 1, '10.0.0.66', '2017-01-20 10:16:07', '0000-00-00 00:00:00', '2017-01-20'),
(55, '360cd888f8d3e29898625713dce0cd2c', 1, '10.0.0.68', '2017-01-20 10:28:41', '0000-00-00 00:00:00', '2017-01-20'),
(56, 'cd91cd74f50f2be821d8bd744032158a', 1, '10.0.0.66', '2017-01-23 04:37:01', '0000-00-00 00:00:00', '2017-01-23'),
(57, 'cd91cd74f50f2be821d8bd744032158a', 1, '10.0.0.66', '2017-01-23 04:41:11', '0000-00-00 00:00:00', '2017-01-23'),
(58, 'fa0111d2152295c1c30bfdb7549f3f2c', 1, '10.0.0.80', '2017-01-23 04:43:43', '0000-00-00 00:00:00', '2017-01-23'),
(59, '339891b4a46b3c2c3270696b410a7e33', 1, '10.0.0.80', '2017-01-23 04:49:51', '0000-00-00 00:00:00', '2017-01-23'),
(60, 'b4760f4228f8c400f02cb6b5ea73299f', 1, '10.0.0.80', '2017-01-23 05:24:51', '0000-00-00 00:00:00', '2017-01-23'),
(61, 'cef918f3c86aea52a020d42a11dcc7d0', 1, '10.0.0.80', '2017-01-23 05:25:53', '0000-00-00 00:00:00', '2017-01-23'),
(62, 'cac3ecca0b4f62064179a0924054d104', 1, '10.0.0.80', '2017-01-23 07:43:15', '0000-00-00 00:00:00', '2017-01-23'),
(63, '00390e7ffff3bd3d04ae83eab640fd14', 1, '10.0.0.80', '2017-01-23 08:00:45', '0000-00-00 00:00:00', '2017-01-23'),
(64, 'b556933961e1ee825ca43798ab6719b2', 1, '10.0.0.80', '2017-01-23 11:57:17', '0000-00-00 00:00:00', '2017-01-23'),
(65, 'f79437dea8d39b53c465b8326d0f5859', 1, '10.0.0.80', '2017-01-24 04:44:49', '0000-00-00 00:00:00', '2017-01-24'),
(66, '134a05589ace1d95c97a02f9b61a1418', 1, '10.0.0.80', '2017-01-24 09:43:21', '0000-00-00 00:00:00', '2017-01-24'),
(67, 'a73f8dee3783c4dad04a6b4bc1123c68', 1, '10.0.0.80', '2017-01-25 04:46:23', '0000-00-00 00:00:00', '2017-01-25'),
(68, '50d5e5ba180a098200a4ebbd5c20c28c', 1, '10.0.0.65', '2017-01-25 05:34:33', '0000-00-00 00:00:00', '2017-01-25'),
(69, 'f95aefacab20e47a7e28ca8042eaef08', 1, '10.0.0.80', '2017-01-27 04:35:36', '0000-00-00 00:00:00', '2017-01-27'),
(70, 'b75c58eddbd922e4f4d44c79bc37c17b', 1, '10.0.0.65', '2017-01-27 09:00:47', '0000-00-00 00:00:00', '2017-01-27'),
(71, '2be08a3d449ea0dbd7a9fbbb3cd2bfa7', 1, '10.0.0.80', '2017-01-27 09:02:07', '0000-00-00 00:00:00', '2017-01-27'),
(72, 'a4b755d179e22939544188ff88a1033f', 1, '10.0.0.80', '2017-01-27 12:07:07', '0000-00-00 00:00:00', '2017-01-27');

-- --------------------------------------------------------

--
-- Table structure for table `ld_system_config`
--

DROP TABLE IF EXISTS `ld_system_config`;
CREATE TABLE IF NOT EXISTS `ld_system_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systemName` varchar(250) NOT NULL,
  `systemVal` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `ld_system_config`
--

INSERT INTO `ld_system_config` (`id`, `systemName`, `systemVal`) VALUES
(1, 'SITE_ONLINE', '1'),
(2, 'SITE_NAME', 'Logistic'),
(3, 'SITE_EMAIL', 'corephp0@gmail.com'),
(4, 'EMAIL_US', 'rajesh.yadav@sparxitsolutions.com'),
(5, 'VM_LEAD_API', 'http://www.vmleads.co.uk/integrations/rest?is_test=1'),
(6, 'VMFORM_HASH', '6E245'),
(7, 'VMFORM_SITEID', '1227'),
(8, 'AFFILIATED_CAMPAIGN_ID', '156'),
(9, 'WSDL_API', 'http://test.webservices.affiliate-university.com/api?wsdl'),
(10, 'WSDL_API_KEYS', '0w30Z8H4GaM3075TSD6lXxawi787Vosr'),
(11, 'TARGET_DB', 'Studentdb'),
(12, 'TCH_USER', 'amit3202'),
(13, 'TCH_PASS', 'amit@3202');

-- --------------------------------------------------------

--
-- Table structure for table `ld_vehicles`
--

DROP TABLE IF EXISTS `ld_vehicles`;
CREATE TABLE IF NOT EXISTS `ld_vehicles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regn_number` varchar(255) NOT NULL,
  `model` text NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `ld_vehicles`
--

INSERT INTO `ld_vehicles` (`id`, `regn_number`, `model`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'sparx', 'the quickspard', 'inactive', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2017),
(2, 'schools', 'the quick brown fox', 'inactive', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2017),
(3, 'fadfadsfadsf adsfasd', 'dfadsfad  ', 'inactive', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1485149332),
(4, 'dfadsfa', ' fadsfads', 'inactive', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1484914401),
(5, 'Accounts', 'dggdfg ', 'inactive', '0000-00-00 00:00:00', '2017-01-23 08:57:53', 2017),
(6, 'testsss', ' dfgdfg ssss', 'inactive', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1485156352),
(7, 'REGN034654', ' MODEL890DFGDFSG', 'active', '0000-00-00 00:00:00', '2017-01-27 07:33:47', NULL),
(8, 'REGN34896904', 'The quick', 'active', '0000-00-00 00:00:00', '2017-01-27 07:33:36', NULL),
(9, 'ryry', 'reyrty', 'inactive', '2017-01-23 08:57:21', '0000-00-00 00:00:00', 2017),
(14, 'TEST32156496', 'TESTMODEL', 'inactive', '2017-01-27 11:37:23', '2017-01-27 11:37:23', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
