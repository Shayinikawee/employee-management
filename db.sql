-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 09, 2026 at 05:37 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rda_employee_management`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `insert_employees`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employees` ()   BEGIN
    DECLARE i INT DEFAULT 1;

    WHILE i <= 100 DO
        INSERT INTO employees (
            user_id,
            name,
            pf_number,
            address,
            email,
            nic,
            contact_number,
            date_of_birth,
            grade,
            current_designation,
            date_of_first_appointment,
            date_of_confirmation,
            unit_id,
            is_active,
            created_at,
            updated_at
        ) VALUES (
            NULL,
            CONCAT('Employee ', i),
            CONCAT('PF', 1000 + i),
            CONCAT('Address ', i),
            CONCAT('employee', i, '@example.com'),
            CONCAT('NIC', 100000 + i),
            CONCAT('0771234', LPAD(i, 3, '0')),
            DATE_SUB(CURDATE(), INTERVAL (20 + FLOOR(RAND()*20)) YEAR),
            CONCAT('Grade ', FLOOR(1 + RAND()*5)),
            CONCAT('Designation ', FLOOR(1 + RAND()*10)),
            DATE_SUB(CURDATE(), INTERVAL FLOOR(RAND()*10) YEAR),
            DATE_SUB(CURDATE(), INTERVAL FLOOR(RAND()*5) YEAR),
            NULL,
            1,
            NOW(),
            NOW()
        );

        SET i = i + 1;
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `status` enum('present','absent','late','half_day','on_leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `import_batch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_employee_id_date_unique` (`employee_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pf_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_first_appointment` date NOT NULL,
  `date_of_confirmation` date DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_pf_number_unique` (`pf_number`),
  UNIQUE KEY `employees_email_unique` (`email`),
  UNIQUE KEY `employees_nic_unique` (`nic`),
  KEY `employees_user_id_foreign` (`user_id`),
  KEY `employees_unit_id_foreign` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `name`, `pf_number`, `address`, `email`, `nic`, `contact_number`, `date_of_birth`, `grade`, `current_designation`, `date_of_first_appointment`, `date_of_confirmation`, `unit_id`, `is_active`, `approval_status`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Employee 1', 'PF1001', 'Address 1', 'employee1@example.com', 'NIC100001', '0771234001', '1991-05-03', 'Grade 4', 'Designation 8', '2024-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(2, NULL, 'Employee 2', 'PF1002', 'Address 2', 'employee2@example.com', 'NIC100002', '0771234002', '1995-05-03', 'Grade 4', 'Designation 4', '2018-05-03', '2025-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(3, NULL, 'Employee 3', 'PF1003', 'Address 3', 'employee3@example.com', 'NIC100003', '0771234003', '2005-05-03', 'Grade 2', 'Designation 7', '2017-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(4, NULL, 'Employee 4', 'PF1004', 'Address 4', 'employee4@example.com', 'NIC100004', '0771234004', '2001-05-03', 'Grade 1', 'Designation 1', '2018-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(5, NULL, 'Employee 5', 'PF1005', 'Address 5', 'employee5@example.com', 'NIC100005', '0771234005', '1990-05-03', 'Grade 4', 'Designation 6', '2022-05-03', '2024-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(6, NULL, 'Employee 6', 'PF1006', 'Address 6', 'employee6@example.com', 'NIC100006', '0771234006', '1987-05-03', 'Grade 4', 'Designation 1', '2023-05-03', '2023-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(7, NULL, 'Employee 7', 'PF1007', 'Address 7', 'employee7@example.com', 'NIC100007', '0771234007', '1995-05-03', 'Grade 4', 'Designation 7', '2024-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(8, NULL, 'Employee 8', 'PF1008', 'Address 8', 'employee8@example.com', 'NIC100008', '0771234008', '1990-05-03', 'Grade 1', 'Designation 3', '2017-05-03', '2026-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(9, NULL, 'Employee 9', 'PF1009', 'Address 9', 'employee9@example.com', 'NIC100009', '0771234009', '1996-05-03', 'Grade 2', 'Designation 7', '2020-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(10, NULL, 'Employee 10', 'PF1010', 'Address 10', 'employee10@example.com', 'NIC100010', '0771234010', '2003-05-03', 'Grade 2', 'Designation 7', '2020-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(11, NULL, 'Employee 11', 'PF1011', 'Address 11', 'employee11@example.com', 'NIC100011', '0771234011', '1997-05-03', 'Grade 2', 'Designation 6', '2019-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(12, NULL, 'Employee 12', 'PF1012', 'Address 12', 'employee12@example.com', 'NIC100012', '0771234012', '2003-05-03', 'Grade 3', 'Designation 10', '2025-05-03', '2026-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(13, NULL, 'Employee 13', 'PF1013', 'Address 13', 'employee13@example.com', 'NIC100013', '0771234013', '1988-05-03', 'Grade 2', 'Designation 7', '2024-05-03', '2024-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(14, NULL, 'Employee 14', 'PF1014', 'Address 14', 'employee14@example.com', 'NIC100014', '0771234014', '1989-05-03', 'Grade 4', 'Designation 10', '2021-05-03', '2022-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(15, NULL, 'Employee 15', 'PF1015', 'Address 15', 'employee15@example.com', 'NIC100015', '0771234015', '2002-05-03', 'Grade 2', 'Designation 7', '2023-05-03', '2022-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(16, NULL, 'Employee 16', 'PF1016', 'Address 16', 'employee16@example.com', 'NIC100016', '0771234016', '1996-05-03', 'Grade 5', 'Designation 6', '2025-05-03', '2026-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(17, NULL, 'Employee 17', 'PF1017', 'Address 17', 'employee17@example.com', 'NIC100017', '0771234017', '1996-05-03', 'Grade 1', 'Designation 7', '2026-05-03', '2024-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(18, NULL, 'Employee 18', 'PF1018', 'Address 18', 'employee18@example.com', 'NIC100018', '0771234018', '1999-05-03', 'Grade 2', 'Designation 6', '2018-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(19, NULL, 'Employee 19', 'PF1019', 'Address 19', 'employee19@example.com', 'NIC100019', '0771234019', '2001-05-03', 'Grade 2', 'Designation 1', '2026-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(20, NULL, 'Employee 20', 'PF1020', 'Address 20', 'employee20@example.com', 'NIC100020', '0771234020', '2006-05-03', 'Grade 3', 'Designation 1', '2026-05-03', '2022-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(21, NULL, 'Employee 21', 'PF1021', 'Address 21', 'employee21@example.com', 'NIC100021', '0771234021', '1993-05-03', 'Grade 4', 'Designation 10', '2025-05-03', '2024-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(22, NULL, 'Employee 22', 'PF1022', 'Address 22', 'employee22@example.com', 'NIC100022', '0771234022', '1998-05-03', 'Grade 2', 'Designation 7', '2024-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(23, NULL, 'Employee 23', 'PF1023', 'Address 23', 'employee23@example.com', 'NIC100023', '0771234023', '1998-05-03', 'Grade 2', 'Designation 6', '2021-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(24, NULL, 'Employee 24', 'PF1024', 'Address 24', 'employee24@example.com', 'NIC100024', '0771234024', '2005-05-03', 'Grade 5', 'Designation 3', '2019-05-03', '2022-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(25, NULL, 'Employee 25', 'PF1025', 'Address 25', 'employee25@example.com', 'NIC100025', '0771234025', '1994-05-03', 'Grade 1', 'Designation 1', '2019-05-03', '2024-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(26, NULL, 'Employee 26', 'PF1026', 'Address 26', 'employee26@example.com', 'NIC100026', '0771234026', '1987-05-03', 'Grade 3', 'Designation 10', '2018-05-03', '2024-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(27, NULL, 'Employee 27', 'PF1027', 'Address 27', 'employee27@example.com', 'NIC100027', '0771234027', '1995-05-03', 'Grade 4', 'Designation 9', '2017-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(28, NULL, 'Employee 28', 'PF1028', 'Address 28', 'employee28@example.com', 'NIC100028', '0771234028', '1992-05-03', 'Grade 4', 'Designation 1', '2023-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(29, NULL, 'Employee 29', 'PF1029', 'Address 29', 'employee29@example.com', 'NIC100029', '0771234029', '1987-05-03', 'Grade 4', 'Designation 10', '2023-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(30, NULL, 'Employee 30', 'PF1030', 'Address 30', 'employee30@example.com', 'NIC100030', '0771234030', '2004-05-03', 'Grade 3', 'Designation 4', '2026-05-03', '2025-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(31, NULL, 'Employee 31', 'PF1031', 'Address 31', 'employee31@example.com', 'NIC100031', '0771234031', '1997-05-03', 'Grade 3', 'Designation 6', '2019-05-03', '2022-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(32, NULL, 'Employee 32', 'PF1032', 'Address 32', 'employee32@example.com', 'NIC100032', '0771234032', '1999-05-03', 'Grade 1', 'Designation 8', '2023-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(33, NULL, 'Employee 33', 'PF1033', 'Address 33', 'employee33@example.com', 'NIC100033', '0771234033', '2001-05-03', 'Grade 4', 'Designation 3', '2022-05-03', '2024-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(34, NULL, 'Employee 34', 'PF1034', 'Address 34', 'employee34@example.com', 'NIC100034', '0771234034', '1998-05-03', 'Grade 2', 'Designation 7', '2017-05-03', '2023-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(35, NULL, 'Employee 35', 'PF1035', 'Address 35', 'employee35@example.com', 'NIC100035', '0771234035', '2003-05-03', 'Grade 3', 'Designation 7', '2026-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(36, NULL, 'Employee 36', 'PF1036', 'Address 36', 'employee36@example.com', 'NIC100036', '0771234036', '1997-05-03', 'Grade 3', 'Designation 7', '2017-05-03', '2023-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(37, NULL, 'Employee 37', 'PF1037', 'Address 37', 'employee37@example.com', 'NIC100037', '0771234037', '1988-05-03', 'Grade 3', 'Designation 4', '2021-05-03', '2024-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(38, NULL, 'Employee 38', 'PF1038', 'Address 38', 'employee38@example.com', 'NIC100038', '0771234038', '2002-05-03', 'Grade 3', 'Designation 8', '2023-05-03', '2024-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(39, NULL, 'Employee 39', 'PF1039', 'Address 39', 'employee39@example.com', 'NIC100039', '0771234039', '2004-05-03', 'Grade 3', 'Designation 7', '2025-05-03', '2023-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(40, NULL, 'Employee 40', 'PF1040', 'Address 40', 'employee40@example.com', 'NIC100040', '0771234040', '1997-05-03', 'Grade 5', 'Designation 3', '2023-05-03', '2025-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(41, NULL, 'Employee 41', 'PF1041', 'Address 41', 'employee41@example.com', 'NIC100041', '0771234041', '1987-05-03', 'Grade 2', 'Designation 2', '2025-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(42, NULL, 'Employee 42', 'PF1042', 'Address 42', 'employee42@example.com', 'NIC100042', '0771234042', '2001-05-03', 'Grade 1', 'Designation 3', '2023-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(43, NULL, 'Employee 43', 'PF1043', 'Address 43', 'employee43@example.com', 'NIC100043', '0771234043', '2002-05-03', 'Grade 3', 'Designation 3', '2020-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(44, NULL, 'Employee 44', 'PF1044', 'Address 44', 'employee44@example.com', 'NIC100044', '0771234044', '2006-05-03', 'Grade 1', 'Designation 10', '2022-05-03', '2023-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(45, NULL, 'Employee 45', 'PF1045', 'Address 45', 'employee45@example.com', 'NIC100045', '0771234045', '2001-05-03', 'Grade 1', 'Designation 6', '2023-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(46, NULL, 'Employee 46', 'PF1046', 'Address 46', 'employee46@example.com', 'NIC100046', '0771234046', '1994-05-03', 'Grade 1', 'Designation 5', '2024-05-03', '2024-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(47, NULL, 'Employee 47', 'PF1047', 'Address 47', 'employee47@example.com', 'NIC100047', '0771234047', '2000-05-03', 'Grade 5', 'Designation 7', '2023-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(48, NULL, 'Employee 48', 'PF1048', 'Address 48', 'employee48@example.com', 'NIC100048', '0771234048', '1988-05-03', 'Grade 3', 'Designation 10', '2017-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(49, NULL, 'Employee 49', 'PF1049', 'Address 49', 'employee49@example.com', 'NIC100049', '0771234049', '1989-05-03', 'Grade 4', 'Designation 5', '2020-05-03', '2026-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(50, NULL, 'Employee 50', 'PF1050', 'Address 50', 'employee50@example.com', 'NIC100050', '0771234050', '1992-05-03', 'Grade 2', 'Designation 1', '2020-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(51, NULL, 'Employee 51', 'PF1051', 'Address 51', 'employee51@example.com', 'NIC100051', '0771234051', '2004-05-03', 'Grade 3', 'Designation 2', '2023-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(52, NULL, 'Employee 52', 'PF1052', 'Address 52', 'employee52@example.com', 'NIC100052', '0771234052', '1989-05-03', 'Grade 5', 'Designation 9', '2020-05-03', '2023-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(53, NULL, 'Employee 53', 'PF1053', 'Address 53', 'employee53@example.com', 'NIC100053', '0771234053', '1995-05-03', 'Grade 4', 'Designation 9', '2024-05-03', '2024-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(54, NULL, 'Employee 54', 'PF1054', 'Address 54', 'employee54@example.com', 'NIC100054', '0771234054', '1990-05-03', 'Grade 4', 'Designation 8', '2017-05-03', '2024-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(55, NULL, 'Employee 55', 'PF1055', 'Address 55', 'employee55@example.com', 'NIC100055', '0771234055', '1990-05-03', 'Grade 3', 'Designation 10', '2025-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(56, NULL, 'Employee 56', 'PF1056', 'Address 56', 'employee56@example.com', 'NIC100056', '0771234056', '1992-05-03', 'Grade 3', 'Designation 4', '2024-05-03', '2025-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(57, NULL, 'Employee 57', 'PF1057', 'Address 57', 'employee57@example.com', 'NIC100057', '0771234057', '2006-05-03', 'Grade 5', 'Designation 7', '2023-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(58, NULL, 'Employee 58', 'PF1058', 'Address 58', 'employee58@example.com', 'NIC100058', '0771234058', '1987-05-03', 'Grade 4', 'Designation 9', '2017-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(59, NULL, 'Employee 59', 'PF1059', 'Address 59', 'employee59@example.com', 'NIC100059', '0771234059', '1995-05-03', 'Grade 4', 'Designation 1', '2026-05-03', '2026-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(60, NULL, 'Employee 60', 'PF1060', 'Address 60', 'employee60@example.com', 'NIC100060', '0771234060', '1987-05-03', 'Grade 4', 'Designation 10', '2021-05-03', '2022-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(61, NULL, 'Employee 61', 'PF1061', 'Address 61', 'employee61@example.com', 'NIC100061', '0771234061', '2004-05-03', 'Grade 4', 'Designation 9', '2024-05-03', '2023-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(62, NULL, 'Employee 62', 'PF1062', 'Address 62', 'employee62@example.com', 'NIC100062', '0771234062', '1987-05-03', 'Grade 4', 'Designation 5', '2025-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(63, NULL, 'Employee 63', 'PF1063', 'Address 63', 'employee63@example.com', 'NIC100063', '0771234063', '1998-05-03', 'Grade 5', 'Designation 6', '2018-05-03', '2024-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(64, NULL, 'Employee 64', 'PF1064', 'Address 64', 'employee64@example.com', 'NIC100064', '0771234064', '1993-05-03', 'Grade 1', 'Designation 6', '2023-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(65, NULL, 'Employee 65', 'PF1065', 'Address 65', 'employee65@example.com', 'NIC100065', '0771234065', '1995-05-03', 'Grade 4', 'Designation 1', '2017-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(66, NULL, 'Employee 66', 'PF1066', 'Address 66', 'employee66@example.com', 'NIC100066', '0771234066', '2005-05-03', 'Grade 1', 'Designation 8', '2019-05-03', '2024-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(67, NULL, 'Employee 67', 'PF1067', 'Address 67', 'employee67@example.com', 'NIC100067', '0771234067', '1989-05-03', 'Grade 1', 'Designation 8', '2020-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(68, NULL, 'Employee 68', 'PF1068', 'Address 68', 'employee68@example.com', 'NIC100068', '0771234068', '1988-05-03', 'Grade 4', 'Designation 4', '2026-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(69, NULL, 'Employee 69', 'PF1069', 'Address 69', 'employee69@example.com', 'NIC100069', '0771234069', '2005-05-03', 'Grade 1', 'Designation 8', '2026-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(70, NULL, 'Employee 70', 'PF1070', 'Address 70', 'employee70@example.com', 'NIC100070', '0771234070', '1997-05-03', 'Grade 4', 'Designation 2', '2020-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(71, NULL, 'Employee 71', 'PF1071', 'Address 71', 'employee71@example.com', 'NIC100071', '0771234071', '2003-05-03', 'Grade 2', 'Designation 1', '2023-05-03', '2024-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(72, NULL, 'Employee 72', 'PF1072', 'Address 72', 'employee72@example.com', 'NIC100072', '0771234072', '2004-05-03', 'Grade 2', 'Designation 5', '2026-05-03', '2022-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(73, NULL, 'Employee 73', 'PF1073', 'Address 73', 'employee73@example.com', 'NIC100073', '0771234073', '2003-05-03', 'Grade 3', 'Designation 6', '2022-05-03', '2023-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(74, NULL, 'Employee 74', 'PF1074', 'Address 74', 'employee74@example.com', 'NIC100074', '0771234074', '1993-05-03', 'Grade 4', 'Designation 3', '2026-05-03', '2023-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(75, NULL, 'Employee 75', 'PF1075', 'Address 75', 'employee75@example.com', 'NIC100075', '0771234075', '1995-05-03', 'Grade 4', 'Designation 6', '2020-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(76, NULL, 'Employee 76', 'PF1076', 'Address 76', 'employee76@example.com', 'NIC100076', '0771234076', '2004-05-03', 'Grade 1', 'Designation 5', '2018-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(77, NULL, 'Employee 77', 'PF1077', 'Address 77', 'employee77@example.com', 'NIC100077', '0771234077', '2006-05-03', 'Grade 2', 'Designation 7', '2024-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(78, NULL, 'Employee 78', 'PF1078', 'Address 78', 'employee78@example.com', 'NIC100078', '0771234078', '1990-05-03', 'Grade 5', 'Designation 2', '2017-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(79, NULL, 'Employee 79', 'PF1079', 'Address 79', 'employee79@example.com', 'NIC100079', '0771234079', '2001-05-03', 'Grade 4', 'Designation 6', '2018-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(80, NULL, 'Employee 80', 'PF1080', 'Address 80', 'employee80@example.com', 'NIC100080', '0771234080', '1996-05-03', 'Grade 3', 'Designation 5', '2025-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(81, NULL, 'Employee 81', 'PF1081', 'Address 81', 'employee81@example.com', 'NIC100081', '0771234081', '2004-05-03', 'Grade 4', 'Designation 2', '2019-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(82, NULL, 'Employee 82', 'PF1082', 'Address 82', 'employee82@example.com', 'NIC100082', '0771234082', '1992-05-03', 'Grade 1', 'Designation 1', '2017-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(83, NULL, 'Employee 83', 'PF1083', 'Address 83', 'employee83@example.com', 'NIC100083', '0771234083', '2002-05-03', 'Grade 4', 'Designation 6', '2019-05-03', '2026-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(84, NULL, 'Employee 84', 'PF1084', 'Address 84', 'employee84@example.com', 'NIC100084', '0771234084', '2005-05-03', 'Grade 1', 'Designation 5', '2026-05-03', '2023-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(85, NULL, 'Employee 85', 'PF1085', 'Address 85', 'employee85@example.com', 'NIC100085', '0771234085', '2000-05-03', 'Grade 4', 'Designation 3', '2025-05-03', '2025-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(86, NULL, 'Employee 86', 'PF1086', 'Address 86', 'employee86@example.com', 'NIC100086', '0771234086', '1994-05-03', 'Grade 2', 'Designation 1', '2024-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(87, NULL, 'Employee 87', 'PF1087', 'Address 87', 'employee87@example.com', 'NIC100087', '0771234087', '2004-05-03', 'Grade 4', 'Designation 6', '2024-05-03', '2022-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(88, NULL, 'Employee 88', 'PF1088', 'Address 88', 'employee88@example.com', 'NIC100088', '0771234088', '2002-05-03', 'Grade 4', 'Designation 8', '2018-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(89, NULL, 'Employee 89', 'PF1089', 'Address 89', 'employee89@example.com', 'NIC100089', '0771234089', '2001-05-03', 'Grade 2', 'Designation 2', '2022-05-03', '2022-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(90, NULL, 'Employee 90', 'PF1090', 'Address 90', 'employee90@example.com', 'NIC100090', '0771234090', '2003-05-03', 'Grade 2', 'Designation 7', '2019-05-03', '2024-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(91, NULL, 'Employee 91', 'PF1091', 'Address 91', 'employee91@example.com', 'NIC100091', '0771234091', '2002-05-03', 'Grade 4', 'Designation 6', '2019-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(92, NULL, 'Employee 92', 'PF1092', 'Address 92', 'employee92@example.com', 'NIC100092', '0771234092', '2003-05-03', 'Grade 5', 'Designation 1', '2021-05-03', '2024-05-03', 3, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(93, NULL, 'Employee 93', 'PF1093', 'Address 93', 'employee93@example.com', 'NIC100093', '0771234093', '2004-05-03', 'Grade 1', 'Designation 8', '2019-05-03', '2025-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(94, NULL, 'Employee 94', 'PF1094', 'Address 94', 'employee94@example.com', 'NIC100094', '0771234094', '1998-05-03', 'Grade 2', 'Designation 9', '2021-05-03', '2025-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(95, NULL, 'Employee 95', 'PF1095', 'Address 95', 'employee95@example.com', 'NIC100095', '0771234095', '1988-05-03', 'Grade 3', 'Designation 3', '2024-05-03', '2023-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(96, NULL, 'Employee 96', 'PF1096', 'Address 96', 'employee96@example.com', 'NIC100096', '0771234096', '1998-05-03', 'Grade 2', 'Designation 10', '2025-05-03', '2023-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(97, NULL, 'Employee 97', 'PF1097', 'Address 97', 'employee97@example.com', 'NIC100097', '0771234097', '1988-05-03', 'Grade 4', 'Designation 6', '2017-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(98, NULL, 'Employee 98', 'PF1098', 'Address 98', 'employee98@example.com', 'NIC100098', '0771234098', '2000-05-03', 'Grade 1', 'Designation 7', '2025-05-03', '2024-05-03', 2, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(99, NULL, 'Employee 99', 'PF1099', 'Address 99', 'employee99@example.com', 'NIC100099', '0771234099', '1987-05-03', 'Grade 3', 'Designation 10', '2017-05-03', '2022-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(100, NULL, 'Employee 100', 'PF1100', 'Address 100', 'employee100@example.com', 'NIC100100', '0771234100', '1993-05-03', 'Grade 4', 'Designation 3', '2023-05-03', '2026-05-03', 1, 1, 'pending', NULL, '2026-05-03 18:08:31', '2026-05-03 18:08:31'),
(101, NULL, 'sdf', '23', 'fe', 'admin@gmail.com', '123456789123', '1234567890', '2026-04-26', '3', '3', '2026-05-04', '2026-05-12', NULL, 1, 'pending', NULL, '2026-05-03 12:47:00', '2026-05-03 12:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holiday_date` date NOT NULL,
  `holiday_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
CREATE TABLE IF NOT EXISTS `leaves` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type_id` bigint UNSIGNED NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `number_of_days` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaves_employee_id_foreign` (`employee_id`),
  KEY `leaves_leave_type_id_foreign` (`leave_type_id`),
  KEY `leaves_approved_by_foreign` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE IF NOT EXISTS `leave_balances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type_id` bigint UNSIGNED NOT NULL,
  `year` int NOT NULL,
  `total_days` int NOT NULL,
  `used_days` int NOT NULL DEFAULT '0',
  `remaining_days` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_balances_employee_id_leave_type_id_year_unique` (`employee_id`,`leave_type_id`,`year`),
  KEY `leave_balances_leave_type_id_foreign` (`leave_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `employee_id`, `leave_type_id`, `year`, `total_days`, `used_days`, `remaining_days`, `created_at`, `updated_at`) VALUES
(1, 101, 1, 2026, 7, 0, 7, '2026-05-03 12:47:00', '2026-05-03 12:47:00'),
(2, 101, 2, 2026, 21, 0, 21, '2026-05-03 12:47:00', '2026-05-03 12:47:00'),
(3, 101, 3, 2026, 14, 0, 14, '2026-05-03 12:47:00', '2026-05-03 12:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE IF NOT EXISTS `leave_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_days` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `default_days`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Casual Leave', 7, 'Casual leave for personal matters', '2026-04-21 11:22:11', '2026-04-21 11:22:11'),
(2, 'Medical Leave', 21, 'Sick leave with medical certificate', '2026-04-21 11:22:11', '2026-04-21 11:22:11'),
(3, 'Annual Leave', 14, 'Annual vacation leave', '2026-04-21 11:22:11', '2026-04-21 11:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_02_000001_create_units_table', 1),
(5, '2025_01_02_000002_create_employees_table', 1),
(6, '2025_01_02_000003_create_work_histories_table', 1),
(7, '2025_01_02_000004_create_attendances_table', 1),
(8, '2025_01_02_000005_create_leave_types_table', 1),
(9, '2025_01_02_000006_create_leaves_table', 1),
(10, '2025_01_02_000007_create_leave_balances_table', 1),
(11, '2025_01_02_000008_create_holidays_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bH1PjWGKeIA7wczzUHsHXVJHiHfYhz35EwGE8UdK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMjBOdnljWEVhN3FHM2hmS1dSR2M5UnJ5Nk02UFQ1ZndUeUdCQ0p5RyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fX0=', 1778348104),
('dq6VqYb96mC6N1ofvLaE87StUqx3et0qk3YPivmt', NULL, '127.0.0.1', 'Symfony', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieFB2clczdzhDckdMMDB5MGRZbzRUbllUaHhicVowbDZZZ0ZLOU9jYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNjoiaHR0cDovL2xvY2FsaG9zdCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjE2OiJodHRwOi8vbG9jYWxob3N0IjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778345343),
('O6Ftb444cLq3m6f6fMjd8ObVHy3Rf8nDI5zJRLZx', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.3.27 Chrome/142.0.7444.265 Electron/39.8.1 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoib2poZWp2OFRYOGNiQ1EzZmJEZDZpUDhJemVyYk9jYjJaUEZzUjUxQiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1778342288);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Administration Unit', 'Administrative and management functions', '2026-04-21 11:22:11', '2026-04-21 11:22:11'),
(2, 'Technical Unit', 'Engineering and technical operations', '2026-04-21 11:22:11', '2026-04-21 11:22:11'),
(3, 'Maintenance Unit', 'Road maintenance and field operations', '2026-04-21 11:22:11', '2026-04-21 11:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@rda.gov.lk', NULL, '$2y$12$HEKnHN2F.CJ2wb94Pr0zh.yJeSPRh8pRRfEg17ReEQEf6MwdKWBhG', 'admin', NULL, '2026-04-21 11:22:11', '2026-04-21 11:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `work_histories`
--

DROP TABLE IF EXISTS `work_histories`;
CREATE TABLE IF NOT EXISTS `work_histories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_histories_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leaves_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_balances_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_histories`
--
ALTER TABLE `work_histories`
  ADD CONSTRAINT `work_histories_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
