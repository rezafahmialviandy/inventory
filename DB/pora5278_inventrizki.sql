-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pora5278_inventrizki
CREATE DATABASE IF NOT EXISTS `pora5278_inventrizki` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `pora5278_inventrizki`;

-- Dumping structure for table pora5278_inventrizki.barang_keluar
CREATE TABLE IF NOT EXISTS `barang_keluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaksi` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `kode_barang` varchar(50) NOT NULL DEFAULT '0',
  `nama_barang` varchar(50) NOT NULL DEFAULT '0',
  `jumlah` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.barang_keluar: ~1 rows (approximately)
INSERT INTO `barang_keluar` (`id`, `id_transaksi`, `tanggal`, `kode_barang`, `nama_barang`, `jumlah`) VALUES
	(1, 'TRK-0725001', '2025-07-22', 'BAR-0725001', 'Ayam', 123);

-- Dumping structure for table pora5278_inventrizki.barang_masuk
CREATE TABLE IF NOT EXISTS `barang_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaksi` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.barang_masuk: ~3 rows (approximately)
INSERT INTO `barang_masuk` (`id`, `id_transaksi`, `tanggal`, `kode_barang`, `nama_barang`, `jumlah`) VALUES
	(1, 'TRM-0725001', '2025-07-22', 'BAR-0725001', 'Ayam', 125),
	(2, 'TRM-0725002', '2026-07-22', 'BAR-0725001', 'Ayam', 12),
	(3, 'TRM-0725003', '2025-01-22', 'BAR-0725001', 'Ayam', 123);

-- Dumping structure for table pora5278_inventrizki.gudang
CREATE TABLE IF NOT EXISTS `gudang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(100) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jenis_barang` varchar(100) NOT NULL,
  `jumlah` int(100) NOT NULL DEFAULT 0,
  `satuan` varchar(10) NOT NULL,
  `supplier` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.gudang: ~1 rows (approximately)
INSERT INTO `gudang` (`id`, `kode_barang`, `nama_barang`, `jenis_barang`, `jumlah`, `satuan`, `supplier`) VALUES
	(3, 'BAR-0725001', 'Ayam', 'Barang Basah', 137, 'PCS', 'PT. Raja Rasa Kuliner');

-- Dumping structure for table pora5278_inventrizki.jenis_barang
CREATE TABLE IF NOT EXISTS `jenis_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_barang` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.jenis_barang: ~2 rows (approximately)
INSERT INTO `jenis_barang` (`id`, `jenis_barang`) VALUES
	(1, 'Barang Basah'),
	(2, 'Barang Kering');

-- Dumping structure for table pora5278_inventrizki.request_barang
CREATE TABLE IF NOT EXISTS `request_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_request` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `supplier` varchar(50) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.request_barang: ~3 rows (approximately)
INSERT INTO `request_barang` (`id`, `id_request`, `tanggal`, `kode_barang`, `nama_barang`, `supplier`, `jumlah`) VALUES
	(16, 'REQ-0725-001', '2025-07-22', 'BAR-0725001', 'Ayam', 'PT. Raja Rasa Kuliner', 100),
	(17, 'REQ-0725-002', '2025-06-22', 'BAR-0725001', 'Ayam', 'PT. Raja Rasa Kuliner', 1000),
	(18, 'REQ-0725-003', '2026-07-22', 'BAR-0725001', 'Ayam', 'PT. Raja Rasa Kuliner', 125);

-- Dumping structure for table pora5278_inventrizki.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `satuan` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.satuan: ~4 rows (approximately)
INSERT INTO `satuan` (`id`, `satuan`) VALUES
	(5, 'Unit'),
	(7, 'PCS'),
	(8, 'Pack'),
	(9, 'Kg');

-- Dumping structure for table pora5278_inventrizki.tb_supplier
CREATE TABLE IF NOT EXISTS `tb_supplier` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `kode_supplier` varchar(100) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.tb_supplier: ~4 rows (approximately)
INSERT INTO `tb_supplier` (`id`, `kode_supplier`, `nama_supplier`, `alamat`, `telepon`) VALUES
	(10, 'SUP-1219001', 'PT Sahabat Utama', 'Jakarta Barat', '085546982020'),
	(11, 'SUP-1219002', 'PT Surya Makmur', 'Tangerang', '081986700103'),
	(12, 'SUP-1219003', 'PT Gading Murni', 'Bandung', '082146982011'),
	(13, 'SUP-0725004', 'PT. Raja Rasa Kuliner', 'Bogor', '085810101020');

-- Dumping structure for table pora5278_inventrizki.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `telepon` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` varchar(25) NOT NULL DEFAULT 'member',
  `foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table pora5278_inventrizki.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `nik`, `nama`, `alamat`, `telepon`, `username`, `password`, `level`, `foto`) VALUES
	(1, '1900120001', 'Rizki', '', '0811228890', 'rizki', '$2y$10$nUIglaVvTwg/hm75F2mwAO6qrI7J1IOobNLdSESEdJJ6iRIblG/vK', 'admin', 'Desktop - 48.png'),
	(2, '1900126005', 'aryamurti', '', '085546982011', 'arya', '5882985c8b1e2dce2763072d56a1d6e5', 'petugas', 'Desktop - 48.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
