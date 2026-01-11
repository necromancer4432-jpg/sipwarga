-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Des 2025 pada 14.17
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
-- Database: `asiap_warga`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_pribadi`
--

CREATE TABLE `data_pribadi` (
  `nik` varchar(16) NOT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `ttl` varchar(50) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ketua_rt_rw`
--

CREATE TABLE `ketua_rt_rw` (
  `nik` varchar(16) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_rekap_data`
--

CREATE TABLE `laporan_rekap_data` (
  `id_laporan` int(11) NOT NULL,
  `nik_warga` varchar(16) NOT NULL,
  `nik_ketua` varchar(16) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_administrasi`
--

CREATE TABLE `riwayat_administrasi` (
  `nik` varchar(16) NOT NULL,
  `surat_domisili` varchar(50) DEFAULT NULL,
  `surat_kelahiran` varchar(50) DEFAULT NULL,
  `surat_kematian` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `warga`
--

CREATE TABLE `warga` (
  `id_user` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `umur` int(11) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `riwayat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `warga`
--

INSERT INTO `warga` (`id_user`, `nik`, `username`, `password`, `alamat`, `umur`, `role`, `riwayat`) VALUES
(7, '4342511040', 'fikri', '$2y$10$k29F4ulWEnXttAHtyYxnwuLtUsGOjQ4MwJDkfZGWhIaq8UGybViB6', 'Batam', 0, 'user', '2025-12-29 13:20:14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_pribadi`
--
ALTER TABLE `data_pribadi`
  ADD PRIMARY KEY (`nik`);

--
-- Indeks untuk tabel `ketua_rt_rw`
--
ALTER TABLE `ketua_rt_rw`
  ADD PRIMARY KEY (`nik`);

--
-- Indeks untuk tabel `laporan_rekap_data`
--
ALTER TABLE `laporan_rekap_data`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `fk_lrd_warga` (`nik_warga`),
  ADD KEY `fk_lrd_ketua` (`nik_ketua`);

--
-- Indeks untuk tabel `riwayat_administrasi`
--
ALTER TABLE `riwayat_administrasi`
  ADD PRIMARY KEY (`nik`);

--
-- Indeks untuk tabel `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uk_warga_nik` (`nik`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `laporan_rekap_data`
--
ALTER TABLE `laporan_rekap_data`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `warga`
--
ALTER TABLE `warga`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_pribadi`
--
ALTER TABLE `data_pribadi`
  ADD CONSTRAINT `fk_dp_warga` FOREIGN KEY (`nik`) REFERENCES `warga` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporan_rekap_data`
--
ALTER TABLE `laporan_rekap_data`
  ADD CONSTRAINT `fk_lrd_ketua` FOREIGN KEY (`nik_ketua`) REFERENCES `ketua_rt_rw` (`nik`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lrd_warga` FOREIGN KEY (`nik_warga`) REFERENCES `warga` (`nik`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_administrasi`
--
ALTER TABLE `riwayat_administrasi`
  ADD CONSTRAINT `fk_ra_warga` FOREIGN KEY (`nik`) REFERENCES `warga` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
