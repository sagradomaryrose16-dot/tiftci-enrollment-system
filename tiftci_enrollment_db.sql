-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 09:19 PM
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
-- Database: `tiftci_enrollment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_year`
--

CREATE TABLE `academic_year` (
  `id` int(11) NOT NULL,
  `year` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_year`
--

INSERT INTO `academic_year` (`id`, `year`, `created_at`, `updated_at`) VALUES
(2, '2025--2026', '2025-05-13 00:09:14', '2025-05-13 00:09:14'),
(3, '2024--2025', '2025-05-13 00:09:34', '2025-05-13 00:09:34'),
(4, '2026--2027', '2025-05-14 19:12:06', '2025-05-14 19:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','faculty','student') DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123', 'admin', '2025-05-11 21:51:28', '2025-05-11 21:51:28'),
(2, 'admin1', '$2y$10$IgE02EvlBXCrnAXBSb7HzuIOkKnoLKbP.KXFwZJZJJ1SzfUZld8zu', '', '2025-05-11 22:02:03', '2025-05-11 22:02:03'),
(3, 'superadmin', '$2y$10$ojvLt86jHK7dW/LtgDtWn.CB/FJkRX1qNFFV9lbHvma6DT8FsiXCe', '', '2025-05-11 22:04:07', '2025-05-11 22:04:07'),
(8, 'theadmin', '$2y$10$79fLOO48nvBSscc4UDyLvutVv30hDBFaw/wZth4r6.4FfGnVUW98i', '', '2025-05-11 22:05:35', '2025-05-11 22:05:35');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `last_name`, `first_name`, `middle_name`, `gender`, `contact_number`, `email`, `address`, `date_of_birth`) VALUES
(1, 'SAGRADO', 'MARY', 'ROSE', 'Female', '09671560343', 'sagradomaryrose16@gmail.com', 'blk5 l15 ascension hills subd.', '1995-06-07'),
(2, 'Akiatan', 'Edmierose', 'S', 'Female', '09156580830', 'edmierosesagrado@gmail.com', 'CENTENNIAL VILLAGE, WESTERN BICUTAN', '1997-02-27');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_assignments`
--

CREATE TABLE `faculty_assignments` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `level_section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_assignments`
--

INSERT INTO `faculty_assignments` (`id`, `faculty_id`, `level_section_id`) VALUES
(1, 1, 1),
(2, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `levels_courses`
--

CREATE TABLE `levels_courses` (
  `id` int(11) NOT NULL,
  `levels` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `levels_courses`
--

INSERT INTO `levels_courses` (`id`, `levels`, `section`) VALUES
(1, 'First Year', 'BACHELOR OF SCIENCE IN INFORMATION SYSTEM'),
(3, 'Fourth Year', 'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT'),
(4, 'Fourth Year', 'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP'),
(5, 'Fourth Year', 'BACHELOR OF SCIENCE IN INFORMATION SYSTEM'),
(6, 'First Year', 'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT'),
(7, 'First Year', 'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(20) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `civil_status` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `parents_contact` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `grade_level` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `last_name`, `first_name`, `middle_name`, `gender`, `civil_status`, `date_of_birth`, `religion`, `contact_number`, `parents_contact`, `address`, `course`, `grade_level`) VALUES
('', 'SAGRADO', 'MARY ROSE', 'PIQUERO', 'Female', 'Single', '2002-04-16', 'CATHOLIC', '09671560343', '09123456789', 'blk5 l15 ascension hills subd.', 'Information Technology', 'Third Year');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `current_school_year` varchar(50) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `school_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `school_name`, `current_school_year`, `logo_path`, `created_at`, `school_address`) VALUES
(1, 'TIFTCI', '2024-2025', 'uploads/logo.png', '2025-05-13 02:39:45', '123 Main St.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_year`
--
ALTER TABLE `academic_year`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faculty_assignments`
--
ALTER TABLE `faculty_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `level_section_id` (`level_section_id`);

--
-- Indexes for table `levels_courses`
--
ALTER TABLE `levels_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_year`
--
ALTER TABLE `academic_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty_assignments`
--
ALTER TABLE `faculty_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `levels_courses`
--
ALTER TABLE `levels_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_assignments`
--
ALTER TABLE `faculty_assignments`
  ADD CONSTRAINT `faculty_assignments_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`),
  ADD CONSTRAINT `faculty_assignments_ibfk_2` FOREIGN KEY (`level_section_id`) REFERENCES `levels_courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
