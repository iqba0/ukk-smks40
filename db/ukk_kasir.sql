-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Mar 2024 pada 07.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ukk_kasir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga_produk` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jumlah` int(11) NOT NULL,
  `kode_unik` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `harga_produk`, `created_at`, `updated_at`, `jumlah`, `kode_unik`) VALUES
(1, 'ASUS A43J', 6000000.00, '2024-02-16 03:30:22', '2024-03-06 04:02:04', 86, 'LT001'),
(4, 'ASUS A43JSA', 6000000.00, '2024-02-16 04:11:29', '2024-02-19 04:57:50', 91, 'LT003'),
(5, 'Axioo', 5000000.00, '2024-03-05 03:46:21', '2024-03-06 05:56:20', 40, 'LT004');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `kode_unik` varchar(255) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `uang_pelanggan` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL,
  `tanggal_pembuatan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_modifikasi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `nama_produk`, `harga_produk`, `jumlah`, `kode_unik`, `total_harga`, `uang_pelanggan`, `kembalian`, `tanggal_pembuatan`, `tanggal_modifikasi`) VALUES
(2, 'ASUS A43J', 6000000, 1, 'LT001', 16000000, 0, 0, '2024-02-16 10:33:02', '2024-02-16 10:33:02'),
(3, 'ASUS A43JSA', 10000000, 1, 'LT003', 16000000, 0, 0, '2024-02-16 10:33:02', '2024-02-16 10:33:02'),
(4, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 0, 0, '2024-02-16 10:41:51', '2024-02-16 10:41:51'),
(5, 'ASUS A43J', 6000000, 1, 'LT001', 16000000, 0, 0, '2024-02-16 10:47:47', '2024-02-16 10:47:47'),
(6, 'ASUS A43JSA', 10000000, 1, 'LT003', 16000000, 0, 0, '2024-02-16 10:47:47', '2024-02-16 10:47:47'),
(7, 'ASUS A43J', 6000000, 1, 'LT001', 16000000, 0, 0, '2024-02-16 10:48:38', '2024-02-16 10:48:38'),
(8, 'ASUS A43JSA', 10000000, 1, 'LT003', 16000000, 0, 0, '2024-02-16 10:48:38', '2024-02-16 10:48:38'),
(9, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 0, 0, '2024-02-16 10:48:56', '2024-02-16 10:48:56'),
(10, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 10:53:53', '2024-02-16 10:53:53'),
(11, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 10:53:53', '2024-02-16 10:53:53'),
(12, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 10:54:26', '2024-02-16 10:54:26'),
(13, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 10:58:51', '2024-02-16 10:58:51'),
(14, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 10:59:12', '2024-02-16 10:59:12'),
(15, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 10:59:12', '2024-02-16 10:59:12'),
(16, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:00:19', '2024-02-16 11:00:19'),
(17, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 11:00:19', '2024-02-16 11:00:19'),
(18, 'ASUS A43J', 6000000, 2, 'LT001', 0, 0, 0, '2024-02-16 11:04:22', '2024-02-16 11:04:22'),
(19, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:05:52', '2024-02-16 11:05:52'),
(20, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 11:05:52', '2024-02-16 11:05:52'),
(21, 'ASUS A43J', 6000000, 3, 'LT001', 0, 0, 0, '2024-02-16 11:08:02', '2024-02-16 11:08:02'),
(22, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:08:54', '2024-02-16 11:08:54'),
(23, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:12:23', '2024-02-16 11:12:23'),
(24, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 11:12:23', '2024-02-16 11:12:23'),
(25, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:13:59', '2024-02-16 11:13:59'),
(26, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 11:13:59', '2024-02-16 11:13:59'),
(27, 'ASUS A43JSA', 10000000, 2, 'LT003', 0, 0, 0, '2024-02-16 11:14:20', '2024-02-16 11:14:20'),
(28, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:16:48', '2024-02-16 11:16:48'),
(29, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 11:16:48', '2024-02-16 11:16:48'),
(30, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:18:56', '2024-02-16 11:18:56'),
(31, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:22:18', '2024-02-16 11:22:18'),
(32, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 11:22:18', '2024-02-16 11:22:18'),
(33, 'ASUS A43J', 6000000, 2, 'LT001', 0, 0, 0, '2024-02-16 11:23:48', '2024-02-16 11:23:48'),
(34, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:25:33', '2024-02-16 11:25:33'),
(35, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 0, 0, '2024-02-16 11:25:59', '2024-02-16 11:25:59'),
(36, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:25:59', '2024-02-16 11:25:59'),
(37, 'ASUS A43J', 6000000, 2, 'LT001', 0, 0, 0, '2024-02-16 11:27:28', '2024-02-16 11:27:28'),
(38, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:30:42', '2024-02-16 11:30:42'),
(39, 'ASUS A43J', 6000000, 1, 'LT001', 0, 0, 0, '2024-02-16 11:32:38', '2024-02-16 11:32:38'),
(40, 'ASUS A43JSA', 10000000, 2, 'LT003', 0, 1000000000, 980000000, '2024-02-16 11:41:09', '2024-02-16 11:41:09'),
(41, 'ASUS A43J', 6000000, 2, 'LT001', 0, 100000000, 88000000, '2024-02-16 11:50:32', '2024-02-16 11:50:32'),
(42, 'ASUS A43JSA', 10000000, 2, 'LT003', 0, 1000000000, 980000000, '2024-02-16 11:55:58', '2024-02-16 11:55:58'),
(43, 'ASUS A43JSA', 10000000, 1, 'LT003', 0, 100000000, 84000000, '2024-02-16 11:56:55', '2024-02-16 11:56:55'),
(44, 'ASUS A43J', 6000000, 1, 'LT001', 0, 100000000, 84000000, '2024-02-16 11:56:55', '2024-02-16 11:56:55'),
(45, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 100000000, 84000000, '2024-02-16 12:02:02', '2024-02-16 12:02:02'),
(46, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 100000000, 84000000, '2024-02-16 12:02:02', '2024-02-16 12:02:02'),
(47, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 100000000, 84000000, '2024-02-16 12:27:56', '2024-02-16 12:27:56'),
(48, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 100000000, 84000000, '2024-02-16 12:27:56', '2024-02-16 12:27:56'),
(49, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 100000000, 84000000, '2024-02-16 12:31:45', '2024-02-16 12:31:45'),
(50, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 100000000, 84000000, '2024-02-16 12:31:45', '2024-02-16 12:31:45'),
(51, 'ASUS A43J', 6000000, 3, 'LT001', 18000000, 100000000, 82000000, '2024-02-16 12:35:03', '2024-02-16 12:35:03'),
(52, 'ASUS A43J', 6000000, 2, 'LT001', 12000000, 100000000, 88000000, '2024-02-16 12:39:25', '2024-02-16 12:39:25'),
(53, 'ASUS A43JSA', 10000000, 2, 'LT003', 20000000, 100000000, 80000000, '2024-02-16 12:40:45', '2024-02-16 12:40:45'),
(54, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 100000000, 90000000, '2024-02-16 12:42:22', '2024-02-16 12:42:22'),
(55, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 100000000, 94000000, '2024-02-16 12:48:47', '2024-02-16 12:48:47'),
(56, 'ASUS A43JSA', 10000000, 2, 'LT003', 20000000, 1000000000, 980000000, '2024-02-16 12:50:43', '2024-02-16 12:50:43'),
(57, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 1000000000, 990000000, '2024-02-16 12:53:24', '2024-02-16 12:53:24'),
(58, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 1000000000, 984000000, '2024-02-16 12:59:11', '2024-02-16 12:59:11'),
(59, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 1000000000, 984000000, '2024-02-16 12:59:11', '2024-02-16 12:59:11'),
(60, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 100000000, 84000000, '2024-02-16 12:59:49', '2024-02-16 12:59:49'),
(61, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 100000000, 84000000, '2024-02-16 12:59:49', '2024-02-16 12:59:49'),
(62, '', 0, 0, '', 0, 100000000, 84000000, '2024-02-16 13:08:26', '2024-02-16 13:08:26'),
(63, '', 0, 0, '', 0, 100000000, 84000000, '2024-02-16 13:10:56', '2024-02-16 13:10:56'),
(64, '', 0, 0, '', 0, 100000000, 84000000, '2024-02-16 13:12:45', '2024-02-16 13:12:45'),
(65, '', 0, 0, '', 16000000, 100000000, 84000000, '2024-02-16 13:24:35', '2024-02-16 13:24:35'),
(66, '', 0, 0, '', 0, 100000000, 84000000, '2024-02-16 13:27:17', '2024-02-16 13:27:17'),
(67, '', 0, 0, '', 0, 100000000, 78000000, '2024-02-16 13:27:53', '2024-02-16 13:27:53'),
(68, '', 0, 0, '', 0, 100000009, 72000009, '2024-02-16 13:37:17', '2024-02-16 13:37:17'),
(69, '', 0, 0, '', 0, 100000008, 84000008, '2024-02-16 13:43:03', '2024-02-16 13:43:03'),
(70, '', 0, 0, '', 0, 1000000088, 984000088, '2024-02-16 13:55:01', '2024-02-16 13:55:01'),
(71, '', 0, 0, '', 0, 100000007, 84000007, '2024-02-16 13:58:01', '2024-02-16 13:58:01'),
(72, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 0, 0, '2024-02-16 13:58:01', '2024-02-16 13:58:01'),
(73, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 0, 0, '2024-02-16 13:58:01', '2024-02-16 13:58:01'),
(74, '', 0, 0, '', 0, 1000000088, 984000088, '2024-02-19 02:09:56', '2024-02-19 02:09:56'),
(75, 'ASUS A43J', 6000000, 1, 'LT001', 6000000, 0, 0, '2024-02-19 02:09:56', '2024-02-19 02:09:56'),
(76, 'ASUS A43JSA', 10000000, 1, 'LT003', 10000000, 0, 0, '2024-02-19 02:09:56', '2024-02-19 02:09:56'),
(81, '', 0, 0, '', 0, 100000007, 90000007, '2024-02-19 02:46:27', '2024-02-19 02:46:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_log`
--

CREATE TABLE `transaksi_log` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `barang_dijual` text DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_log`
--

INSERT INTO `transaksi_log` (`id`, `id_transaksi`, `barang_dijual`, `aktivitas`, `waktu`) VALUES
(1, 94, NULL, 'Insert', '2024-03-06 02:38:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_produk`
--

CREATE TABLE `transaksi_produk` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga_produk` decimal(10,2) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `kode_unik` varchar(50) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','owner') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '123', 'admin'),
(9, 'kasir', '123', 'kasir'),
(10, 'owner', '123', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi_log`
--
ALTER TABLE `transaksi_log`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT untuk tabel `transaksi_log`
--
ALTER TABLE `transaksi_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  ADD CONSTRAINT `transaksi_produk_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
