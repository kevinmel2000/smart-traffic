-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 04 Sep 2017 pada 16.28
-- Versi Server: 10.1.9-MariaDB
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cctv`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cctv_detail`
--

CREATE TABLE `cctv_detail` (
  `id` int(11) NOT NULL,
  `cctv_group_id` int(11) NOT NULL,
  `cctv_source_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `cctv_detail`
--

INSERT INTO `cctv_detail` (`id`, `cctv_group_id`, `cctv_source_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cctv_group`
--

CREATE TABLE `cctv_group` (
  `id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `cctv_group`
--

INSERT INTO `cctv_group` (`id`, `group_name`) VALUES
(1, 'Sample Group');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cctv_source`
--

CREATE TABLE `cctv_source` (
  `id` int(11) NOT NULL,
  `nama_lokasi` text NOT NULL,
  `server` varchar(6) NOT NULL,
  `ip_address` text NOT NULL,
  `port` int(11) NOT NULL,
  `gateway` text NOT NULL,
  `tipe_camera` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `rtsp` text NOT NULL,
  `oneshotimage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `cctv_source`
--

INSERT INTO `cctv_source` (`id`, `nama_lokasi`, `server`, `ip_address`, `port`, `gateway`, `tipe_camera`, `username`, `password`, `rtsp`, `oneshotimage`) VALUES
(1, 'Sample1', 'http', '127.0.0.1', 6147, '127.0.0.1', 'AVTECT', 'admin', 'spasi2017', 'rtsp://184.72.239.149/vod/mp4:BigBuckBunny_175k.mov', ''),
(2, 'Sample2', 'http', '127.0.0.1', 6147, '127.0.0.1', 'AVTECT', 'admin', 'spasi2017', 'https://r5---sn-npoe7n76.googlevideo.com/videoplayback?id=80e3eb977da02f8b&itag=18&source=blogger&pl=24&ei=TBCtWbfsONSVqQWjzL24CQ&mime=video/mp4&lmt=1375376317017923&ip=182.253.37.52&ipbits=0&expire=1504542924&sparams=ei,expire,id,ip,ipbits,itag,lmt,mime,mm,mn,ms,mv,pl,source&signature=0CC16D93AF127905199FF03AED1AC4DC56DBC3C5.323038D63E0F7A535FC5F19B10A686F11035862F&key=cms1&cpn=4riQU_oipopJIm7A&c=WEB&cver=1.20170830%22&redirect_counter=1&cm2rm=sn-cp1oxu-jb3l7e&req_id=60426f482da1a3ee&cms_redirect=yes&mm=30&mn=sn-npoe7n76&ms=nxu&mt=1504514556&mv=m', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cctv_detail`
--
ALTER TABLE `cctv_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cctv_group`
--
ALTER TABLE `cctv_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cctv_source`
--
ALTER TABLE `cctv_source`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cctv_detail`
--
ALTER TABLE `cctv_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `cctv_group`
--
ALTER TABLE `cctv_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `cctv_source`
--
ALTER TABLE `cctv_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
