-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 08:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `faculty_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`) VALUES
(1, 'Introduction to Computer Science'),
(2, 'Data Structures and Algorithms'),
(3, 'Database Management Systems'),
(4, 'Web Development Fundamentals'),
(5, 'Software Engineering'),
(6, 'Artificial Intelligence'),
(7, 'Machine Learning'),
(8, 'Computer Networks'),
(9, 'Cyber Security'),
(10, 'Cloud Computing');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `dept_name` varchar(255) DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `professional_interest` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `full_name`, `email`, `phone`, `designation`, `dept_name`, `expertise`, `professional_interest`, `profile_image`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Dr. Ayesha Khan', 'ayesha.khan@university.edu', '0300-1111111', 'Professor', 'Computer Science', 'AI, Machine Learning, NLP', 'Deep Learning, Model Optimization', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(2, 'Mr. Ahmed Raza', 'ahmed.raza@university.edu', '0300-2222222', 'Assistant Professor', 'Information Technology', 'Web Development, Databases', 'Cloud Applications, APIs', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(3, 'Dr. Sana Malik', 'sana.malik@university.edu', '0300-3333333', 'Associate Professor', 'Software Engineering', 'Agile, SDLC, DevOps', 'Software Design Patterns', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(4, 'Prof. Bilal Haider', 'bilal.haider@university.edu', '0300-4444444', 'Professor', 'Cyber Security', 'Network Security, Ethical Hacking', 'Cryptography, Blockchain Security', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(5, 'Ms. Hira Shah', 'hira.shah@university.edu', '0300-5555555', 'Lecturer', 'Computer Science', 'Data Science, Python', 'Big Data, Visualization', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(6, 'Dr. Farhan Ali', 'farhan.ali@university.edu', '0300-6666666', 'Professor', 'Artificial Intelligence', 'Neural Networks, ML Systems', 'AI Ethics, Automation', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(7, 'Mr. Imran Tariq', 'imran.tariq@university.edu', '0300-7777777', 'Assistant Professor', 'Information Systems', 'Database Systems, SQL', 'Data Warehousing, ERP Systems', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(8, 'Dr. Mehwish Zafar', 'mehwish.zafar@university.edu', '0300-8888888', 'Associate Professor', 'Software Engineering', 'Web Frameworks, PHP, Django', 'Scalable Web Apps', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(9, 'Dr. Asad Nadeem', 'asad.nadeem@university.edu', '0300-9999999', 'Professor', 'Computer Networks', 'Network Protocols, IoT', 'Edge Computing, 5G Networks', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46'),
(10, 'Ms. Nadia Khalid', 'nadia.khalid@university.edu', '0310-1010101', 'Lecturer', 'Information Technology', 'Cloud Platforms, AWS, Azure', 'Serverless Architecture', NULL, 1, '2025-10-26 12:33:46', '2025-10-26 12:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_courses`
--

CREATE TABLE `faculty_courses` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_courses`
--

INSERT INTO `faculty_courses` (`id`, `faculty_id`, `course_id`) VALUES
(1, 1, 6),
(2, 1, 7),
(3, 2, 4),
(4, 2, 3),
(5, 3, 5),
(6, 3, 2),
(7, 4, 9),
(8, 4, 8),
(9, 5, 1),
(10, 5, 7),
(11, 6, 6),
(12, 6, 10),
(13, 7, 3),
(14, 7, 9),
(15, 8, 4),
(16, 8, 5),
(17, 9, 8),
(18, 9, 10),
(19, 10, 10),
(20, 10, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD CONSTRAINT `faculty_courses_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `faculty_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
