-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2026 at 03:13 PM
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
-- Database: `db_wishvault`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(4, 'Buku'),
(1, 'Elektronik'),
(3, 'Fashion'),
(2, 'Gaming'),
(5, 'Hobi'),
(8, 'Kendaraan'),
(7, 'Lainnya'),
(6, 'Perjalanan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-10-29 13:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nama_item` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_estimasi` decimal(12,2) NOT NULL DEFAULT 0.00,
  `prioritas` enum('Rendah','Sedang','Tinggi') NOT NULL DEFAULT 'Sedang',
  `target_tanggal` date DEFAULT NULL,
  `status` enum('Belum Tercapai','Tercapai') NOT NULL DEFAULT 'Belum Tercapai',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `kategori_id`, `nama_item`, `deskripsi`, `harga_estimasi`, `prioritas`, `target_tanggal`, `status`, `created_at`) VALUES
(11, 1, 2, 'PC Gaming', 'PC gaming dengan anggaran sekitar 20 juta rupiah', 20000000.00, 'Tinggi', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(12, 1, 6, 'Liburan ke Jepang', 'Wishlist perjalanan liburan ke Jepang', 15000000.00, 'Tinggi', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(13, 1, 2, 'Game AAA Steam', 'Dana untuk membeli beberapa game AAA di Steam', 1500000.00, 'Sedang', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(14, 1, 2, 'Controller GameSir G7 Pro', 'Controller untuk bermain game di PC', 1500000.00, 'Sedang', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(15, 1, 1, 'Tangzu Xuan Wu Gate II IEM', 'IEM flagship Tangzu untuk mendengarkan musik', 14799000.00, 'Tinggi', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(16, 1, 1, 'Cooler Laptop', 'Pendingin tambahan untuk membantu menjaga suhu laptop', 500000.00, 'Sedang', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(17, 1, 1, 'Monitor 32 Inch', 'Monitor berukuran 32 inci untuk gaming dan produktivitas', 4000000.00, 'Tinggi', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(18, 1, 8, 'BMW S1000RR', 'Motor sport impian dengan target jangka panjang', 700000000.00, 'Rendah', '2046-10-29', 'Belum Tercapai', '2026-10-29 13:56:13'),
(19, 1, 3, 'Sepatu Jalan', 'Sepatu nyaman untuk berjalan dan aktivitas sehari-hari', 1000000.00, 'Sedang', NULL, 'Belum Tercapai', '2026-10-29 13:56:13'),
(20, 1, 2, 'Mouse Ignix F7', 'Mouse untuk kebutuhan gaming dan penggunaan sehari-hari', 400000.00, 'Sedang', NULL, 'Belum Tercapai', '2026-10-29 13:56:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wishlist_user` (`user_id`),
  ADD KEY `fk_wishlist_kategori` (`kategori_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wishlist_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`),
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
