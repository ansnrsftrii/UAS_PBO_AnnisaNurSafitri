-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2026 at 02:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_trpl1a_annisanursafitri`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_mahasiswa`
--

CREATE TABLE `tabel_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(15) NOT NULL,
  `semester` int NOT NULL,
  `tarif_ukt_nominal` decimal(10,2) NOT NULL,
  `jenis_pembayaran` enum('mandiri','bidikmisi','prestasi') NOT NULL,
  `golongan_ukt` varchar(10) DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `nomor_kip_kuliah` varchar(30) DEFAULT NULL,
  `dana_saku_subsidi` decimal(10,2) DEFAULT NULL,
  `nama_instansi_beasiswa` varchar(100) DEFAULT NULL,
  `minimal_ipk_syarat` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_mahasiswa`
--

INSERT INTO `tabel_mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `semester`, `tarif_ukt_nominal`, `jenis_pembayaran`, `golongan_ukt`, `nama_wali`, `nomor_kip_kuliah`, `dana_saku_subsidi`, `nama_instansi_beasiswa`, `minimal_ipk_syarat`) VALUES
(1, 'Annisa Nur Safitri', '230101001', 3, '5000000.00', 'mandiri', 'Golongan 4', 'Budi Santoso', NULL, NULL, NULL, NULL),
(2, 'Rafi Ahmad', '230101002', 3, '7500000.00', 'mandiri', 'Golongan 5', 'Ahmad Subarjo', NULL, NULL, NULL, NULL),
(3, 'Siti Aminah', '230101003', 5, '4500000.00', 'mandiri', 'Golongan 3', 'Hasan Basri', NULL, NULL, NULL, NULL),
(4, 'Fikri Haikal', '230101004', 1, '5000000.00', 'mandiri', 'Golongan 4', 'Suripto', NULL, NULL, NULL, NULL),
(5, 'Dewi Lestari', '230101005', 7, '7500000.00', 'mandiri', 'Golongan 5', 'Indra Jaya', NULL, NULL, NULL, NULL),
(6, 'Rian Hidayat', '230101006', 3, '3000000.00', 'mandiri', 'Golongan 2', 'Mulyono', NULL, NULL, NULL, NULL),
(7, 'Farhan Yudha', '230101007', 5, '5000000.00', 'mandiri', 'Golongan 4', 'Eko Prasetyo', NULL, NULL, NULL, NULL),
(8, 'Budi Setiawan', '230101008', 3, '0.00', 'bidikmisi', NULL, NULL, 'KIP-2023-99101', '950000.00', NULL, NULL),
(9, 'Citra Kirana', '230101009', 3, '0.00', 'bidikmisi', NULL, NULL, 'KIP-2023-99102', '950000.00', NULL, NULL),
(10, 'Dimas Anggara', '230101010', 5, '0.00', 'bidikmisi', NULL, NULL, 'KIP-2021-88204', '950000.00', NULL, NULL),
(11, 'Eka Putri', '230101011', 1, '0.00', 'bidikmisi', NULL, NULL, 'KIP-2025-11205', '1000000.00', NULL, NULL),
(12, 'Fadel Muhammad', '230101012', 7, '0.00', 'bidikmisi', NULL, NULL, 'KIP-2020-77401', '900000.00', NULL, NULL),
(13, 'Gita Gutawa', '230101013', 3, '0.00', 'bidikmisi', NULL, NULL, 'KIP-2023-99155', '950000.00', NULL, NULL),
(14, 'Hendra Wijaya', '230101014', 5, '0.00', 'bidikmisi', NULL, NULL, 'KIP-2021-88390', '950000.00', NULL, NULL),
(15, 'Intan Permata', '230101015', 3, '1500000.00', 'prestasi', NULL, NULL, NULL, NULL, 'Djarum Foundation', '3.50'),
(16, 'Kevin Sanjaya', '230101016', 3, '2000000.00', 'prestasi', NULL, NULL, NULL, NULL, 'Bank Indonesia', '3.40'),
(17, 'Lesti Kejora', '230101017', 5, '0.00', 'prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Unggulan Kemendikbud', '3.75'),
(18, 'Muhammad Ali', '230101018', 1, '1500000.00', 'prestasi', NULL, NULL, NULL, NULL, 'Djarum Foundation', '3.50'),
(19, 'Nadia Vega', '230101019', 7, '2000000.00', 'prestasi', NULL, NULL, NULL, NULL, 'PT. Gudang Garam Tbk', '3.30'),
(20, 'Oki Setiana', '230101020', 3, '0.00', 'prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Pemprov', '3.50'),
(21, 'Putra Perkasa', '230101021', 5, '1000000.00', 'prestasi', NULL, NULL, NULL, NULL, 'Tanoto Foundation', '3.60');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
