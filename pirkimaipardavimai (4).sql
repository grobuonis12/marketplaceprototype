-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2023 at 11:03 PM
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
-- Database: `pirkimaipardavimai`
--

-- --------------------------------------------------------

--
-- Table structure for table `gautuprekiu`
--

CREATE TABLE `gautuprekiu` (
  `GautuPrekiuID` int(11) NOT NULL,
  `PrekesID` int(11) DEFAULT NULL,
  `TiekejoID` int(11) DEFAULT NULL,
  `Kiekis` int(11) DEFAULT NULL,
  `GautuData` date DEFAULT NULL,
  `KlientoID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klientai`
--

CREATE TABLE `klientai` (
  `KlientoID` int(11) NOT NULL,
  `Vardas` varchar(50) DEFAULT NULL,
  `Pavarde` varchar(50) DEFAULT NULL,
  `Kontaktas` varchar(100) DEFAULT NULL,
  `KlientoPaskyrosID` int(11) DEFAULT NULL,
  `Adresas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klientu_paskyros`
--

CREATE TABLE `klientu_paskyros` (
  `KlientoPaskyrosID` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `slaptazodis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prekes`
--

CREATE TABLE `prekes` (
  `PrekesID` int(11) NOT NULL,
  `Pavadinimas` varchar(100) DEFAULT NULL,
  `Kategorija` varchar(50) DEFAULT NULL,
  `Kiekis` int(11) DEFAULT NULL,
  `Kaina` decimal(10,2) DEFAULT NULL,
  `TiekejoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiekejai`
--

CREATE TABLE `tiekejai` (
  `TiekejoID` int(11) NOT NULL,
  `Pavadinimas` varchar(100) DEFAULT NULL,
  `Kontaktas` varchar(100) DEFAULT NULL,
  `TiekejoPaskyrosID` int(11) DEFAULT NULL,
  `Adresas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiekeju_paskyros`
--

CREATE TABLE `tiekeju_paskyros` (
  `TiekejoPaskyrosID` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `slaptazodis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uzsakymai`
--

CREATE TABLE `uzsakymai` (
  `UzsakymoID` int(11) NOT NULL,
  `KlientoID` int(11) NOT NULL,
  `PrekesID` int(11) NOT NULL,
  `Kiekis` int(11) DEFAULT NULL,
  `UzsakymoData` date DEFAULT NULL,
  `TiekejoID` int(11) NOT NULL,
  `Kaina` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gautuprekiu`
--
ALTER TABLE `gautuprekiu`
  ADD PRIMARY KEY (`GautuPrekiuID`),
  ADD KEY `PrekėsID` (`PrekesID`),
  ADD KEY `TiekėjoID` (`TiekejoID`),
  ADD KEY `KlientoID` (`KlientoID`);

--
-- Indexes for table `klientai`
--
ALTER TABLE `klientai`
  ADD PRIMARY KEY (`KlientoID`),
  ADD KEY `KlientoPaskyrosID` (`KlientoPaskyrosID`);

--
-- Indexes for table `klientu_paskyros`
--
ALTER TABLE `klientu_paskyros`
  ADD PRIMARY KEY (`KlientoPaskyrosID`);

--
-- Indexes for table `prekes`
--
ALTER TABLE `prekes`
  ADD PRIMARY KEY (`PrekesID`),
  ADD KEY `TiekėjoID` (`TiekejoID`);

--
-- Indexes for table `tiekejai`
--
ALTER TABLE `tiekejai`
  ADD PRIMARY KEY (`TiekejoID`),
  ADD KEY `TiekėjoPaskyrosID` (`TiekejoPaskyrosID`);

--
-- Indexes for table `tiekeju_paskyros`
--
ALTER TABLE `tiekeju_paskyros`
  ADD PRIMARY KEY (`TiekejoPaskyrosID`);

--
-- Indexes for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD PRIMARY KEY (`UzsakymoID`),
  ADD KEY `PrekėsID` (`PrekesID`),
  ADD KEY `KlientoID` (`KlientoID`),
  ADD KEY `fk_užsakymai_tiekėjai` (`TiekejoID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gautuprekiu`
--
ALTER TABLE `gautuprekiu`
  MODIFY `GautuPrekiuID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `klientu_paskyros`
--
ALTER TABLE `klientu_paskyros`
  MODIFY `KlientoPaskyrosID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prekes`
--
ALTER TABLE `prekes`
  MODIFY `PrekesID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiekeju_paskyros`
--
ALTER TABLE `tiekeju_paskyros`
  MODIFY `TiekejoPaskyrosID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  MODIFY `UzsakymoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gautuprekiu`
--
ALTER TABLE `gautuprekiu`
  ADD CONSTRAINT `gautuprekiu_ibfk_1` FOREIGN KEY (`PrekesID`) REFERENCES `prekes` (`PrekesID`),
  ADD CONSTRAINT `gautuprekiu_ibfk_2` FOREIGN KEY (`TiekejoID`) REFERENCES `tiekejai` (`TiekejoID`),
  ADD CONSTRAINT `gautuprekiu_ibfk_3` FOREIGN KEY (`KlientoID`) REFERENCES `klientai` (`KlientoID`);

--
-- Constraints for table `klientai`
--
ALTER TABLE `klientai`
  ADD CONSTRAINT `klientai_ibfk_1` FOREIGN KEY (`KlientoPaskyrosID`) REFERENCES `klientu_paskyros` (`KlientoPaskyrosID`);

--
-- Constraints for table `prekes`
--
ALTER TABLE `prekes`
  ADD CONSTRAINT `prekes_ibfk_1` FOREIGN KEY (`TiekejoID`) REFERENCES `tiekejai` (`TiekejoID`);

--
-- Constraints for table `tiekejai`
--
ALTER TABLE `tiekejai`
  ADD CONSTRAINT `tiekejai_ibfk_1` FOREIGN KEY (`TiekejoPaskyrosID`) REFERENCES `tiekeju_paskyros` (`TiekejoPaskyrosID`);

--
-- Constraints for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD CONSTRAINT `fk_užsakymai_tiekėjai` FOREIGN KEY (`TiekejoID`) REFERENCES `tiekejai` (`TiekejoID`),
  ADD CONSTRAINT `uzsakymai_ibfk_1` FOREIGN KEY (`KlientoID`) REFERENCES `klientai` (`KlientoID`),
  ADD CONSTRAINT `uzsakymai_ibfk_2` FOREIGN KEY (`PrekesID`) REFERENCES `prekes` (`PrekesID`),
  ADD CONSTRAINT `uzsakymai_ibfk_3` FOREIGN KEY (`KlientoID`) REFERENCES `klientai` (`KlientoID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
