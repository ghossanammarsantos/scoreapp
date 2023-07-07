-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2023 at 05:30 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_scoreapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE `clubs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clubs`
--

INSERT INTO `clubs` (`id`, `name`, `city`) VALUES
(1, 'PakBoy FC', 'Padang'),
(2, 'Persikatim FC', 'Pariaman'),
(3, 'Persekon FC', 'Sicincin'),
(4, 'Koja FC', 'Padang Panjang'),
(5, 'Kosino FC', 'Padang'),
(7, 'Paris FC', 'Solok'),
(10, 'Nomaden FC', 'Solok');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `home_club_id` int(11) NOT NULL,
  `away_club_id` int(11) NOT NULL,
  `home_goals` int(11) NOT NULL,
  `away_goals` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `home_club_id`, `away_club_id`, `home_goals`, `away_goals`) VALUES
(1, 1, 2, 1, 0),
(2, 1, 2, 1, 3),
(3, 2, 3, 0, 1),
(4, 4, 5, 3, 0),
(5, 10, 7, 1, 1),
(6, 4, 3, 3, 0),
(7, 7, 5, 3, 0),
(8, 10, 1, 2, 4),
(9, 3, 7, 2, 2),
(10, 4, 10, 1, 0),
(11, 5, 2, 2, 1),
(12, 4, 10, 1, 0),
(13, 5, 2, 2, 1),
(14, 4, 10, 1, 0),
(15, 5, 2, 2, 1),
(16, 3, 4, 3, 0),
(17, 1, 10, 1, 0),
(18, 1, 4, 5, 0),
(19, 3, 7, 2, 1),
(20, 5, 1, 2, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `home_club_id` (`home_club_id`),
  ADD KEY `away_club_id` (`away_club_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`home_club_id`) REFERENCES `clubs` (`id`),
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`away_club_id`) REFERENCES `clubs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
