-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2024 at 12:33 AM
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
-- Database: `dbkasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `detailpenjualan`
--

CREATE TABLE `detailpenjualan` (
  `id_detail` int(11) NOT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `jumlah_produk` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `total_pembelian` decimal(10,2) DEFAULT NULL,
  `jumlah_dibayar` decimal(10,2) DEFAULT NULL,
  `kembalian` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` enum('tunai','kartu','e-wallet') DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailpenjualan`
--

INSERT INTO `detailpenjualan` (`id_detail`, `id_penjualan`, `nama_produk`, `jumlah_produk`, `subtotal`, `total_pembelian`, `jumlah_dibayar`, `kembalian`, `metode_pembayaran`, `barcode`, `harga`) VALUES
(62, 61, 'Teh Pucuk Harum 500ml', 1, 6700.00, NULL, NULL, NULL, NULL, NULL, 6700.00),
(63, 62, 'Teh Pucuk Harum 500ml', 1, 6700.00, NULL, NULL, NULL, NULL, NULL, 6700.00),
(64, 63, 'SIlverQueen Chocolate Greentea 22gr', 1, 12500.00, NULL, NULL, NULL, NULL, NULL, 12500.00),
(65, 64, 'SIlverQueen Chocolate Greentea 22gr', 1, 12500.00, NULL, NULL, NULL, NULL, NULL, 12500.00),
(66, 64, 'Cimory Fresh Milk UHT Choco Mint 250mL', 1, 5900.00, NULL, NULL, NULL, NULL, NULL, 5900.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(255) DEFAULT NULL,
  `status_member` enum('reguler','member') DEFAULT 'reguler'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat`, `no_telepon`, `status_member`) VALUES
(2, 'Pelanggan Umum', 'Tidak Tersedia', 'Tidak Tersedia', 'reguler'),
(3, 'Hana Kirana Atmaja', 'Citraland, Taman Puspa Raya, Made, Kec. Sambikerep, Surabaya, Jawa Timur 60217', '089876654331', 'member'),
(4, 'Gema Mahatma Atmaja', 'Jl. Ketintang Madya No.144, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231', '085789075687', 'member');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `tanggal_penjualan` date DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `jumlah_dibayar` decimal(10,2) DEFAULT NULL,
  `total_pembelian` decimal(10,2) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `tanggal_penjualan`, `total_harga`, `id_pelanggan`, `metode_pembayaran`, `jumlah_dibayar`, `total_pembelian`, `kode_transaksi`) VALUES
(64, '2024-09-23', NULL, 2, 'cash', 20000.00, 18400.00, 'TRX-1727043879');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `satuan` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `terjual` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `barcode`, `nama_produk`, `satuan`, `harga`, `stok`, `terjual`) VALUES
(1, 'TEH1097', 'Teh Pucuk Harum 500ml', 1, 6700.00, 328, 49),
(2, 'SIL8782', 'SIlverQueen Chocolate Greentea 22gr', 1, 12500.00, 313, 37),
(7, 'CIM2702', 'Cimory Fresh Milk UHT Choco Mint 250mL', 1, 5900.00, 341, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` enum('admin','petugas') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `level`) VALUES
(1, 'Zahra Salsabila Yasmin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'Kanaya Rayindra', 'petugas', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'petugas'),
(5, 'Mahika Darmawangsa', 'hikawangsa', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `idx_id_penjualan` (`id_penjualan`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `fk_harga` (`harga`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD KEY `idx_nama_pelanggan` (`nama_pelanggan`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `satuan` (`satuan`),
  ADD KEY `idx_barcode` (`barcode`),
  ADD KEY `harga` (`harga`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  ADD CONSTRAINT `fk_harga` FOREIGN KEY (`harga`) REFERENCES `produk` (`harga`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
