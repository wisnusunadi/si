-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2021 at 11:43 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `jenis` enum('distributor','pelanggan','vendor') NOT NULL,
  `nama` varchar(255) NOT NULL,
  `telp` bigint(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_ekatalog`
--

CREATE TABLE `detail_ekatalog` (
  `id` int(11) NOT NULL,
  `penjualan_id` int(11) NOT NULL,
  `penjualan_produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `ongkir` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan_produk`
--

CREATE TABLE `detail_penjualan_produk` (
  `id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `penjualan_produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_penjualan_produk`
--

INSERT INTO `detail_penjualan_produk` (`id`, `produk_id`, `penjualan_produk_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, NULL),
(2, 2, 2, 1, NULL, NULL),
(3, 2, 3, 1, NULL, NULL),
(4, 3, 4, 1, NULL, NULL),
(5, 3, 5, 1, NULL, NULL),
(6, 100, 5, 1, NULL, NULL),
(7, 4, 6, 1, NULL, NULL),
(8, 5, 7, 1, NULL, NULL),
(9, 5, 8, 1, NULL, NULL),
(10, 89, 8, 1, NULL, NULL),
(11, 6, 9, 1, NULL, NULL),
(12, 6, 10, 1, NULL, NULL),
(13, 89, 10, 1, NULL, NULL),
(14, 7, 11, 1, NULL, NULL),
(15, 7, 12, 1, NULL, NULL),
(16, 89, 12, 1, NULL, NULL),
(17, 8, 13, 1, NULL, NULL),
(18, 9, 14, 1, NULL, NULL),
(19, 10, 15, 1, NULL, NULL),
(20, 10, 16, 1, NULL, NULL),
(21, 11, 17, 1, NULL, NULL),
(22, 12, 18, 1, NULL, NULL),
(23, 12, 19, 1, NULL, NULL),
(24, 91, 19, 1, NULL, NULL),
(25, 12, 20, 1, NULL, NULL),
(26, 92, 20, 1, NULL, NULL),
(27, 12, 21, 1, NULL, NULL),
(28, 92, 21, 1, NULL, NULL),
(29, 91, 21, 1, NULL, NULL),
(30, 12, 22, 1, NULL, NULL),
(31, 92, 22, 1, NULL, NULL),
(32, 91, 22, 1, NULL, NULL),
(33, 84, 22, 1, NULL, NULL),
(34, 89, 22, 1, NULL, NULL),
(35, 12, 23, 1, NULL, NULL),
(36, 92, 23, 1, NULL, NULL),
(37, 84, 23, 1, NULL, NULL),
(38, 12, 24, 1, NULL, NULL),
(39, 92, 24, 1, NULL, NULL),
(40, 84, 24, 1, NULL, NULL),
(41, 89, 24, 1, NULL, NULL),
(42, 12, 25, 1, NULL, NULL),
(43, 84, 25, 1, NULL, NULL),
(44, 12, 26, 1, NULL, NULL),
(45, 89, 26, 1, NULL, NULL),
(46, 13, 27, 1, NULL, NULL),
(47, 14, 28, 1, NULL, NULL),
(48, 14, 29, 1, NULL, NULL),
(49, 100, 29, 1, NULL, NULL),
(50, 15, 30, 1, NULL, NULL),
(51, 16, 31, 1, NULL, NULL),
(52, 101, 31, 1, NULL, NULL),
(53, 17, 32, 1, NULL, NULL),
(54, 18, 33, 1, NULL, NULL),
(55, 18, 34, 1, NULL, NULL),
(56, 102, 34, 1, NULL, NULL),
(57, 19, 35, 1, NULL, NULL),
(58, 19, 36, 1, NULL, NULL),
(59, 84, 36, 1, NULL, NULL),
(60, 20, 37, 1, NULL, NULL),
(61, 20, 38, 1, NULL, NULL),
(62, 84, 38, 1, NULL, NULL),
(63, 20, 39, 1, NULL, NULL),
(64, 84, 39, 1, NULL, NULL),
(65, 89, 39, 1, NULL, NULL),
(66, 20, 40, 1, NULL, NULL),
(67, 89, 40, 1, NULL, NULL),
(68, 21, 41, 1, NULL, NULL),
(69, 21, 42, 1, NULL, NULL),
(70, 84, 42, 1, NULL, NULL),
(71, 21, 43, 1, NULL, NULL),
(72, 84, 43, 1, NULL, NULL),
(73, 89, 43, 1, NULL, NULL),
(74, 21, 44, 1, NULL, NULL),
(75, 89, 44, 1, NULL, NULL),
(76, 22, 45, 1, NULL, NULL),
(77, 22, 46, 1, NULL, NULL),
(78, 84, 46, 1, NULL, NULL),
(79, 22, 47, 1, NULL, NULL),
(80, 84, 47, 1, NULL, NULL),
(81, 89, 47, 1, NULL, NULL),
(82, 22, 48, 1, NULL, NULL),
(83, 89, 48, 1, NULL, NULL),
(84, 23, 49, 1, NULL, NULL),
(85, 23, 50, 1, NULL, NULL),
(86, 84, 50, 1, NULL, NULL),
(87, 23, 51, 1, NULL, NULL),
(88, 84, 51, 1, NULL, NULL),
(89, 89, 51, 1, NULL, NULL),
(90, 23, 52, 1, NULL, NULL),
(91, 89, 52, 1, NULL, NULL),
(92, 24, 53, 1, NULL, NULL),
(93, 24, 54, 1, NULL, NULL),
(94, 84, 54, 1, NULL, NULL),
(95, 24, 55, 1, NULL, NULL),
(96, 84, 55, 1, NULL, NULL),
(97, 89, 55, 1, NULL, NULL),
(98, 24, 56, 1, NULL, NULL),
(99, 89, 56, 1, NULL, NULL),
(100, 25, 57, 1, NULL, NULL),
(101, 25, 58, 1, NULL, NULL),
(102, 87, 58, 1, NULL, NULL),
(103, 25, 59, 1, NULL, NULL),
(104, 84, 59, 1, NULL, NULL),
(105, 25, 60, 1, NULL, NULL),
(106, 84, 60, 1, NULL, NULL),
(107, 87, 60, 1, NULL, NULL),
(108, 25, 61, 1, NULL, NULL),
(109, 25, 62, 1, NULL, NULL),
(110, 87, 62, 1, NULL, NULL),
(111, 25, 63, 1, NULL, NULL),
(112, 84, 63, 1, NULL, NULL),
(113, 25, 64, 1, NULL, NULL),
(114, 84, 64, 1, NULL, NULL),
(115, 87, 64, 1, NULL, NULL),
(116, 25, 65, 1, NULL, NULL),
(117, 25, 66, 1, NULL, NULL),
(118, 87, 66, 1, NULL, NULL),
(119, 25, 67, 1, NULL, NULL),
(120, 84, 67, 1, NULL, NULL),
(121, 25, 68, 1, NULL, NULL),
(122, 84, 68, 1, NULL, NULL),
(123, 87, 68, 1, NULL, NULL),
(124, 26, 69, 1, NULL, NULL),
(125, 27, 70, 1, NULL, NULL),
(126, 28, 71, 1, NULL, NULL),
(127, 29, 72, 1, NULL, NULL),
(128, 30, 73, 1, NULL, NULL),
(129, 30, 74, 1, NULL, NULL),
(130, 84, 74, 1, NULL, NULL),
(131, 30, 75, 1, NULL, NULL),
(132, 84, 75, 1, NULL, NULL),
(133, 89, 75, 1, NULL, NULL),
(134, 30, 76, 1, NULL, NULL),
(135, 89, 76, 1, NULL, NULL),
(136, 31, 77, 1, NULL, NULL),
(137, 31, 78, 1, NULL, NULL),
(138, 89, 78, 1, NULL, NULL),
(139, 32, 79, 1, NULL, NULL),
(140, 32, 80, 1, NULL, NULL),
(141, 89, 80, 1, NULL, NULL),
(142, 33, 81, 1, NULL, NULL),
(143, 34, 82, 1, NULL, NULL),
(144, 34, 83, 1, NULL, NULL),
(145, 89, 83, 1, NULL, NULL),
(146, 35, 84, 1, NULL, NULL),
(147, 35, 85, 1, NULL, NULL),
(148, 89, 85, 1, NULL, NULL),
(149, 36, 86, 1, NULL, NULL),
(150, 36, 87, 1, NULL, NULL),
(151, 89, 87, 1, NULL, NULL),
(152, 37, 88, 1, NULL, NULL),
(153, 84, 88, 1, NULL, NULL),
(154, 37, 89, 1, NULL, NULL),
(155, 84, 89, 1, NULL, NULL),
(156, 89, 89, 1, NULL, NULL),
(157, 37, 90, 1, NULL, NULL),
(158, 89, 90, 1, NULL, NULL),
(159, 39, 91, 1, NULL, NULL),
(160, 39, 92, 1, NULL, NULL),
(161, 86, 92, 1, NULL, NULL),
(162, 39, 93, 1, NULL, NULL),
(163, 85, 93, 1, NULL, NULL),
(164, 40, 94, 1, NULL, NULL),
(165, 40, 95, 1, NULL, NULL),
(166, 84, 95, 1, NULL, NULL),
(167, 40, 96, 1, NULL, NULL),
(168, 84, 96, 1, NULL, NULL),
(169, 89, 96, 1, NULL, NULL),
(170, 40, 97, 1, NULL, NULL),
(171, 89, 97, 1, NULL, NULL),
(172, 41, 98, 1, NULL, NULL),
(173, 41, 99, 1, NULL, NULL),
(174, 86, 99, 1, NULL, NULL),
(175, 41, 100, 1, NULL, NULL),
(176, 85, 100, 1, NULL, NULL),
(177, 42, 101, 1, NULL, NULL),
(178, 43, 102, 1, NULL, NULL),
(179, 44, 103, 1, NULL, NULL),
(180, 44, 104, 1, NULL, NULL),
(181, 87, 104, 1, NULL, NULL),
(182, 45, 105, 1, NULL, NULL),
(183, 45, 106, 1, NULL, NULL),
(184, 104, 106, 1, NULL, NULL),
(185, 88, 106, 1, NULL, NULL),
(186, 45, 107, 1, NULL, NULL),
(187, 104, 107, 1, NULL, NULL),
(188, 88, 107, 1, NULL, NULL),
(189, 89, 107, 1, NULL, NULL),
(190, 45, 108, 1, NULL, NULL),
(191, 89, 108, 1, NULL, NULL),
(192, 46, 109, 1, NULL, NULL),
(193, 46, 110, 1, NULL, NULL),
(194, 89, 110, 1, NULL, NULL),
(195, 47, 111, 1, NULL, NULL),
(196, 48, 112, 1, NULL, NULL),
(197, 49, 113, 1, NULL, NULL),
(198, 50, 115, 1, NULL, NULL),
(199, 51, 116, 1, NULL, NULL),
(200, 51, 117, 1, NULL, NULL),
(201, 84, 117, 1, NULL, NULL),
(202, 51, 118, 1, NULL, NULL),
(203, 84, 118, 1, NULL, NULL),
(204, 89, 118, 1, NULL, NULL),
(205, 51, 119, 1, NULL, NULL),
(206, 89, 119, 1, NULL, NULL),
(207, 52, 120, 1, NULL, NULL),
(208, 52, 121, 1, NULL, NULL),
(209, 84, 121, 1, NULL, NULL),
(210, 52, 122, 1, NULL, NULL),
(211, 84, 122, 1, NULL, NULL),
(212, 89, 122, 1, NULL, NULL),
(213, 52, 123, 1, NULL, NULL),
(214, 89, 123, 1, NULL, NULL),
(215, 53, 124, 1, NULL, NULL),
(216, 53, 125, 1, NULL, NULL),
(217, 84, 125, 1, NULL, NULL),
(218, 53, 126, 1, NULL, NULL),
(219, 84, 126, 1, NULL, NULL),
(220, 89, 126, 1, NULL, NULL),
(221, 53, 127, 1, NULL, NULL),
(222, 53, 127, 1, NULL, NULL),
(223, 54, 128, 1, NULL, NULL),
(224, 55, 129, 1, NULL, NULL),
(225, 55, 130, 1, NULL, NULL),
(226, 84, 130, 1, NULL, NULL),
(227, 55, 131, 1, NULL, NULL),
(228, 84, 131, 1, NULL, NULL),
(229, 89, 131, 1, NULL, NULL),
(230, 55, 132, 1, NULL, NULL),
(231, 89, 132, 1, NULL, NULL),
(232, 56, 133, 1, NULL, NULL),
(233, 56, 134, 1, NULL, NULL),
(234, 92, 134, 1, NULL, NULL),
(235, 56, 135, 1, NULL, NULL),
(236, 93, 135, 1, NULL, NULL),
(237, 56, 136, 1, NULL, NULL),
(238, 84, 136, 1, NULL, NULL),
(239, 56, 137, 1, NULL, NULL),
(240, 93, 137, 1, NULL, NULL),
(241, 92, 137, 1, NULL, NULL),
(242, 91, 137, 1, NULL, NULL),
(243, 84, 137, 1, NULL, NULL),
(244, 56, 138, 1, NULL, NULL),
(245, 84, 138, 1, NULL, NULL),
(246, 92, 138, 1, NULL, NULL),
(247, 56, 139, 1, NULL, NULL),
(248, 84, 139, 1, NULL, NULL),
(249, 92, 139, 1, NULL, NULL),
(250, 91, 139, 1, NULL, NULL),
(251, 97, 139, 1, NULL, NULL),
(252, 56, 140, 1, NULL, NULL),
(253, 97, 140, 1, NULL, NULL),
(254, 89, 140, 1, NULL, NULL),
(255, 91, 140, 1, NULL, NULL),
(256, 92, 140, 1, NULL, NULL),
(257, 93, 140, 1, NULL, NULL),
(258, 84, 140, 1, NULL, NULL),
(259, 56, 141, 1, NULL, NULL),
(260, 84, 141, 1, NULL, NULL),
(261, 93, 141, 1, NULL, NULL),
(262, 92, 141, 1, NULL, NULL),
(263, 91, 141, 1, NULL, NULL),
(264, 89, 141, 1, NULL, NULL),
(265, 56, 142, 1, NULL, NULL),
(266, 84, 142, 1, NULL, NULL),
(267, 93, 142, 1, NULL, NULL),
(268, 92, 142, 1, NULL, NULL),
(269, 89, 142, 1, NULL, NULL),
(270, 56, 143, 1, NULL, NULL),
(271, 84, 143, 1, NULL, NULL),
(272, 89, 143, 1, NULL, NULL),
(273, 57, 144, 1, NULL, NULL),
(274, 58, 145, 1, NULL, NULL),
(275, 59, 146, 1, NULL, NULL),
(276, 60, 147, 1, NULL, NULL),
(277, 60, 148, 1, NULL, NULL),
(278, 96, 148, 1, NULL, NULL),
(279, 91, 148, 1, NULL, NULL),
(280, 60, 149, 1, NULL, NULL),
(281, 96, 149, 1, NULL, NULL),
(282, 105, 149, 1, NULL, NULL),
(283, 60, 150, 1, NULL, NULL),
(284, 96, 150, 1, NULL, NULL),
(285, 97, 150, 1, NULL, NULL),
(286, 60, 151, 1, NULL, NULL),
(287, 96, 151, 1, NULL, NULL),
(288, 91, 151, 1, NULL, NULL),
(289, 95, 151, 1, NULL, NULL),
(290, 60, 152, 1, NULL, NULL),
(291, 91, 152, 1, NULL, NULL),
(292, 60, 153, 1, NULL, NULL),
(293, 84, 153, 1, NULL, NULL),
(294, 92, 153, 1, NULL, NULL),
(295, 91, 153, 1, NULL, NULL),
(296, 97, 153, 1, NULL, NULL),
(297, 95, 153, 1, NULL, NULL),
(298, 60, 154, 1, NULL, NULL),
(299, 84, 154, 1, NULL, NULL),
(300, 93, 154, 1, NULL, NULL),
(301, 92, 154, 1, NULL, NULL),
(302, 60, 155, 1, NULL, NULL),
(303, 84, 155, 1, NULL, NULL),
(304, 93, 155, 1, NULL, NULL),
(305, 61, 156, 1, NULL, NULL),
(306, 62, 157, 1, NULL, NULL),
(307, 63, 158, 1, NULL, NULL),
(308, 63, 159, 1, NULL, NULL),
(309, 87, 159, 1, NULL, NULL),
(310, 63, 160, 1, NULL, NULL),
(311, 84, 160, 1, NULL, NULL),
(312, 63, 161, 1, NULL, NULL),
(313, 84, 161, 1, NULL, NULL),
(314, 87, 161, 1, NULL, NULL),
(315, 64, 162, 1, NULL, NULL),
(316, 65, 163, 1, NULL, NULL),
(317, 66, 164, 1, NULL, NULL),
(318, 67, 165, 1, NULL, NULL),
(319, 68, 166, 1, NULL, NULL),
(320, 69, 167, 1, NULL, NULL),
(321, 38, 168, 1, NULL, NULL),
(322, 84, 168, 1, NULL, NULL),
(323, 38, 169, 1, NULL, NULL),
(324, 84, 169, 1, NULL, NULL),
(325, 89, 169, 1, NULL, NULL),
(326, 38, 170, 1, NULL, NULL),
(327, 89, 170, 1, NULL, NULL),
(328, 70, 171, 1, NULL, NULL),
(329, 70, 172, 1, NULL, NULL),
(330, 84, 172, 1, NULL, NULL),
(331, 70, 173, 1, NULL, NULL),
(332, 84, 173, 1, NULL, NULL),
(333, 89, 173, 1, NULL, NULL),
(334, 70, 174, 1, NULL, NULL),
(335, 89, 174, 1, NULL, NULL),
(336, 71, 175, 1, NULL, NULL),
(337, 71, 176, 1, NULL, NULL),
(338, 84, 176, 1, NULL, NULL),
(339, 71, 177, 1, NULL, NULL),
(340, 84, 177, 1, NULL, NULL),
(341, 89, 177, 1, NULL, NULL),
(342, 71, 178, 1, NULL, NULL),
(343, 89, 178, 1, NULL, NULL),
(344, 72, 179, 1, NULL, NULL),
(345, 73, 180, 1, NULL, NULL),
(346, 74, 181, 1, NULL, NULL),
(347, 75, 182, 1, NULL, NULL),
(348, 75, 183, 1, NULL, NULL),
(349, 84, 183, 1, NULL, NULL),
(350, 90, 183, 1, NULL, NULL),
(351, 76, 184, 1, NULL, NULL),
(352, 77, 185, 1, NULL, NULL),
(353, 77, 186, 1, NULL, NULL),
(354, 89, 186, 1, NULL, NULL),
(355, 78, 187, 1, NULL, NULL),
(356, 78, 188, 1, NULL, NULL),
(357, 89, 188, 1, NULL, NULL),
(358, 79, 189, 1, NULL, NULL),
(359, 79, 190, 1, NULL, NULL),
(360, 89, 190, 1, NULL, NULL),
(361, 80, 191, 1, NULL, NULL),
(362, 80, 192, 1, NULL, NULL),
(363, 87, 192, 1, NULL, NULL),
(364, 80, 193, 1, NULL, NULL),
(365, 84, 193, 1, NULL, NULL),
(366, 80, 194, 1, NULL, NULL),
(367, 84, 194, 1, NULL, NULL),
(368, 87, 194, 1, NULL, NULL),
(369, 81, 195, 1, NULL, NULL),
(370, 81, 196, 1, NULL, NULL),
(371, 103, 196, 1, NULL, NULL),
(372, 81, 197, 1, NULL, NULL),
(373, 103, 197, 1, NULL, NULL),
(374, 99, 197, 1, NULL, NULL),
(375, 82, 198, 1, NULL, NULL),
(376, 82, 199, 1, NULL, NULL),
(377, 89, 199, 1, NULL, NULL),
(378, 83, 200, 1, NULL, NULL),
(379, 83, 201, 1, NULL, NULL),
(380, 89, 201, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan_ekatalog`
--

CREATE TABLE `detail_pesanan_ekatalog` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `ekatalog_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan_spa`
--

CREATE TABLE `detail_pesanan_spa` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan_spb`
--

CREATE TABLE `detail_pesanan_spb` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `spb_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_spa`
--

CREATE TABLE `detail_spa` (
  `id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `penjualan_produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `ongkir` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_spb`
--

CREATE TABLE `detail_spb` (
  `id` int(11) NOT NULL,
  `spb_id` int(11) NOT NULL,
  `penjualan_produk_id` int(11) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `ongkir` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `nama`, `kode`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'sa', NULL, NULL),
(2, 'Direktur Utama', 'dirut', NULL, NULL),
(3, 'Direktur Teknik', 'dirtek', NULL, NULL),
(4, 'Direktur Keuangan', 'dirkeu', NULL, NULL),
(5, 'General Manager', 'gm', NULL, NULL),
(7, 'Administrasi', 'adm', NULL, NULL),
(8, 'After Sales Perbaikan', 'asp', NULL, NULL),
(9, 'Document Control', 'dc', NULL, NULL),
(10, 'Engineering', 'eng', NULL, NULL),
(11, 'Gudang Bahan Material', 'gbmp', NULL, NULL),
(12, 'Gudang Karantina', 'gk', NULL, NULL),
(13, 'Gudang Barang Jadi', 'gbj', NULL, NULL),
(14, 'IT', 'it', NULL, NULL),
(15, 'Logistik', 'log', NULL, NULL),
(16, 'Maintenance', 'mtc', NULL, NULL),
(17, 'Produksi', 'prd', NULL, NULL),
(18, 'Rumah Tangga', 'rt', NULL, NULL),
(19, 'Sarana Lingkungan', 'sarling', NULL, NULL),
(20, 'Sarana Kesehatan', 'sarkes', NULL, NULL),
(21, 'Research Development', 'rnd', NULL, NULL),
(22, 'Laboratorium', 'lab', NULL, NULL),
(23, 'Quality Control', 'qc', NULL, NULL),
(24, 'PPIC', 'ppic', NULL, NULL),
(25, 'K3', 'k3', NULL, NULL),
(26, 'Penjualan', 'jual', NULL, NULL),
(27, 'Pembelian', 'beli', NULL, NULL),
(28, 'Kesehatan', 'kes', NULL, NULL),
(29, 'Sarana Produksi', 'sarprod', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ekatalog`
--

CREATE TABLE `ekatalog` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `no_paket` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `status` enum('sepakat','negosiasi','batal') NOT NULL,
  `tgl_kontrak` date NOT NULL,
  `tgl_buat` date NOT NULL,
  `ket` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gdg_barang_jadi`
--

CREATE TABLE `gdg_barang_jadi` (
  `id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `variasi` varchar(100) DEFAULT NULL,
  `stok` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_produk`
--

CREATE TABLE `kelompok_produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelompok_produk`
--

INSERT INTO `kelompok_produk` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Alat Kesehatan', NULL, NULL),
(2, 'Water Treatment', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 2),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `noseri_barang_jadi`
--

CREATE TABLE `noseri_barang_jadi` (
  `id` int(11) NOT NULL,
  `gdg_barang_jadi_id` int(11) NOT NULL,
  `noseri` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_produk`
--

CREATE TABLE `penjualan_produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penjualan_produk`
--

INSERT INTO `penjualan_produk` (`id`, `nama`, `harga`, `created_at`, `updated_at`) VALUES
(1, 'ABPM50', 10780000, NULL, NULL),
(2, 'APRON (Full) Per 10pcs', 3245000, NULL, NULL),
(3, 'APRON (Half) Per 10pcs', 583000, NULL, NULL),
(4, 'BABY DIGIT-ONE', 1551000, NULL, NULL),
(5, 'BABY DIGIT-ONE + TAS', 1760000, NULL, NULL),
(6, 'BABY ONE', 11330000, NULL, NULL),
(7, 'BB-200', 191400000, NULL, NULL),
(8, 'BB-200 + UPS', 186450000, NULL, NULL),
(9, 'BL-50B', 51700000, NULL, NULL),
(10, 'BL-50B + UPS', 66550000, NULL, NULL),
(11, 'BN-100', 152900000, NULL, NULL),
(12, 'BN-100 + UPS', 141130000, NULL, NULL),
(13, 'BODY FAT PRO', 1760000, NULL, NULL),
(14, 'BR-100', 0, NULL, NULL),
(15, 'BT-100 (BIG TROLLEY)', 231000000, NULL, NULL),
(16, 'BT-100 (SMALL TROLLEY)', 193600000, NULL, NULL),
(17, 'CENTRAL MONITOR PM-9000+ + PC + INSTALASI', 25740000, NULL, NULL),
(18, 'CMS-600 PLUS', 106590000, NULL, NULL),
(19, 'CMS-600 PLUS + LINEAR PROBE', 145200000, NULL, NULL),
(20, 'CMS-600 PLUS + PRINTER', 125950000, NULL, NULL),
(21, 'CMS-600 PLUS + PRINTER + LINEAR PROBE', 164780000, NULL, NULL),
(22, 'CMS-600 PLUS + PRINTER + LINEAR PROBE + TROLLEY + UPS', 193600000, NULL, NULL),
(23, 'CMS-600 PLUS + PRINTER + TROLLEY', 135740000, NULL, NULL),
(24, 'CMS-600 PLUS + PRINTER + TROLLEY + UPS', 155100000, NULL, NULL),
(25, 'CMS-600 PLUS + TROLLEY', 116270000, NULL, NULL),
(26, 'CMS-600 PLUS + UPS', 119900000, NULL, NULL),
(27, 'DIGIT ONE', 379500, NULL, NULL),
(28, 'DIGIT-ONE BABY', 2904000, NULL, NULL),
(29, 'DIGIT-ONE BABY + TAS', 3619000, NULL, NULL),
(30, 'DIGIT-PRO', 671000, NULL, NULL),
(31, 'DIGIT-PRO BMI + BODY FAT', 1012000, NULL, NULL),
(32, 'DIGIT-PRO IDA', 1100000, NULL, NULL),
(33, 'DP1', 3993000, NULL, NULL),
(34, 'DP1 + TELE', 6600000, NULL, NULL),
(35, 'DS-PRO100', 43626000, NULL, NULL),
(36, 'DS-PRO100 + TROLLEY', 64779000, NULL, NULL),
(37, 'ECG-100G', 11550000, NULL, NULL),
(38, 'ECG-100G + TROLLEY', 19250000, NULL, NULL),
(39, 'ECG-100G + TROLLEY + UPS', 36080000, NULL, NULL),
(40, 'ECG-100G + UPS', 28050000, NULL, NULL),
(41, 'ECG-1200 MED', 231990000, NULL, NULL),
(42, 'ECG-1200 MED + TROLLEY', 239800000, NULL, NULL),
(43, 'ECG-1200 MED + TROLLEY + UPS', 256300000, NULL, NULL),
(44, 'ECG-1200 MED + UPS', 248270000, NULL, NULL),
(45, 'ECG-1200G', 39380000, NULL, NULL),
(46, 'ECG-1200G + TROLLEY', 46750000, NULL, NULL),
(47, 'ECG-1200G + TROLLEY + UPS', 69740000, NULL, NULL),
(48, 'ECG-1200G + UPS', 50600000, NULL, NULL),
(49, 'ECG-1800 MED', 284790000, NULL, NULL),
(50, 'ECG-1800 MED + TROLLEY', 293040000, NULL, NULL),
(51, 'ECG-1800 MED + TROLLEY + UPS', 308000000, NULL, NULL),
(52, 'ECG-1800 MED + UPS', 298100000, NULL, NULL),
(53, 'ECG-300G', 31834000, NULL, NULL),
(54, 'ECG-300G + TROLLEY', 40095000, NULL, NULL),
(55, 'ECG-300G + TROLLEY + UPS', 57420000, NULL, NULL),
(56, 'ECG-300G + UPS', 44330000, NULL, NULL),
(57, 'END-1 (DUA FUNGSI)', 9020000, NULL, NULL),
(58, 'END-1 (DUA FUNGSI) + BACKUP POWER', 26400000, NULL, NULL),
(59, 'END-1 (DUA FUNGSI) + TROLLEY', 17050000, NULL, NULL),
(60, 'END-1 (DUA FUNGSI) + TROLLEY + BACKUP POWER', 34760000, NULL, NULL),
(61, 'END-1 (SATU FUNGSI)', 7799000, NULL, NULL),
(62, 'END-1 (SATU FUNGSI) + BACKUP POWER', 24640000, NULL, NULL),
(63, 'END-1 (SATU FUNGSI) + TROLLEY', 15620000, NULL, NULL),
(64, 'END-1 (SATU FUNGSI) + TROLLEY + BACKUP POWER', 31900000, NULL, NULL),
(65, 'END-1 (TIGA FUNGSI)', 11660000, NULL, NULL),
(66, 'END-1 (TIGA FUNGSI) + BACKUP POWER', 26620000, NULL, NULL),
(67, 'END-1 (TIGA FUNGSI) +TROLLEY', 18260000, NULL, NULL),
(68, 'END-1 (TIGA FUNGSI) +TROLLEY + BACKUP POWER', 35200000, NULL, NULL),
(69, 'FOX PRO', 20460000, NULL, NULL),
(70, 'FOX-2', 836000, NULL, NULL),
(71, 'FOX-3', 3300000, NULL, NULL),
(72, 'FOX-BABY', 3300000, NULL, NULL),
(73, 'GET 338 UO', 3454000, NULL, NULL),
(74, 'GET 338 UO + TROLLEY', 11550000, NULL, NULL),
(75, 'GET 338 UO + TROLLEY + UPS', 20350000, NULL, NULL),
(76, 'GET 338 UO + UPS', 11550000, NULL, NULL),
(77, 'GET-160', 86900000, NULL, NULL),
(78, 'GET-160 + UPS', 110000000, NULL, NULL),
(79, 'GET-80C', 16720000, NULL, NULL),
(80, 'GET-80C + UPS', 31900000, NULL, NULL),
(81, 'ISOLATION GOWN-01', 4235000, NULL, NULL),
(82, 'KJF-B100', 64900000, NULL, NULL),
(83, 'KJF-B100 + UPS', 81400000, NULL, NULL),
(84, 'KJF-Y100', 80960000, NULL, NULL),
(85, 'KJF-Y100 + UPS', 99000000, NULL, NULL),
(86, 'MAP 380', 15070000, NULL, NULL),
(87, 'MAP 380 + UPS', 28160000, NULL, NULL),
(88, 'MATERNAL MED-02 + TROLLEY', 180400000, NULL, NULL),
(89, 'MATERNAL MED-02 + TROLLEY + UPS', 196900000, NULL, NULL),
(90, 'MATERNAL MED-02 + UPS', 188100000, NULL, NULL),
(91, 'MED-S100', 43626000, NULL, NULL),
(92, 'MED-S100 + TROLLEY MEJA', 65340000, NULL, NULL),
(93, 'MED-S100 + TROLLEY TIANG', 59510000, NULL, NULL),
(94, 'MED-S200', 79420000, NULL, NULL),
(95, 'MED-S200 + TROLLEY', 101530000, NULL, NULL),
(96, 'MED-S200 + TROLLEY + UPS', 118800000, NULL, NULL),
(97, 'MED-S200 + UPS', 110000000, NULL, NULL),
(98, 'MED-S400', 31020000, NULL, NULL),
(99, 'MED-S400 + TROLLEY MEJA', 50600000, NULL, NULL),
(100, 'MED-S400 + TROLLEY TIANG', 44880000, NULL, NULL),
(101, 'MEL-02', 7557000, NULL, NULL),
(102, 'MFT-01', 1804000, NULL, NULL),
(103, 'MFV-01', 9680000, NULL, NULL),
(104, 'MFV-01 + BACKUP POWER', 20350000, NULL, NULL),
(105, 'MOC-A', 27841000, NULL, NULL),
(106, 'MOC-A + PIPING + OUTLET', 74415000, NULL, NULL),
(107, 'MOC-A + PIPING + OUTLET + UPS', 90970000, NULL, NULL),
(108, 'MOC-A + UPS', 46783000, NULL, NULL),
(109, 'MOL-01', 106656000, NULL, NULL),
(110, 'MOL-01 + UPS', 112970000, NULL, NULL),
(111, 'MOL-02', 123200000, NULL, NULL),
(112, 'MTB-2MTR', 105600, NULL, NULL),
(113, 'MTR-BABY 001', 990000, NULL, NULL),
(114, 'ONE STATION + TAS', 4103000, NULL, NULL),
(115, 'PA-DC001', 260700, NULL, NULL),
(116, 'PM PRO-1', 267960000, NULL, NULL),
(117, 'PM PRO-1 + TROLLEY', 276980000, NULL, NULL),
(118, 'PM PRO-1 + TROLLEY + UPS', 294800000, NULL, NULL),
(119, 'PM PRO-1 + UPS', 287100000, NULL, NULL),
(120, 'PM PRO-2', 110220000, NULL, NULL),
(121, 'PM PRO-2 + TROLLEY', 116600000, NULL, NULL),
(122, 'PM PRO-2 + TROLLEY + UPS', 134200000, NULL, NULL),
(123, 'PM PRO-2 + UPS', 125400000, NULL, NULL),
(124, 'PM PRO-3', 89100000, NULL, NULL),
(125, 'PM PRO-3 + TROLLEY', 95260000, NULL, NULL),
(126, 'PM PRO-3 + TROLLEY + UPS', 110000000, NULL, NULL),
(127, 'PM PRO-3 + UPS', 102300000, NULL, NULL),
(128, 'PM50', 7788000, NULL, NULL),
(129, 'PM-9000+', 30250000, NULL, NULL),
(130, 'PM-9000+ + TROLLEY', 40964000, NULL, NULL),
(131, 'PM-9000+ + TROLLEY + UPS', 52140000, NULL, NULL),
(132, 'PM-9000+ + UPS', 58850000, NULL, NULL),
(133, 'PRA-ONE', 152900000, NULL, NULL),
(134, 'PRA-ONE + PRINTER COLOR', 155650000, NULL, NULL),
(135, 'PRA-ONE + THERMAL PRINTER', 166100000, NULL, NULL),
(136, 'PRA-ONE + TROLLEY', 178200000, NULL, NULL),
(137, 'PRA-ONE + TROLLEY +  THERMAL & PRINTER COLOR + LINEAR PROBE', 269500000, NULL, NULL),
(138, 'PRA-ONE + TROLLEY + PRINTER COLOR', 201300000, NULL, NULL),
(139, 'PRA-ONE + TROLLEY + PRINTER COLOR + LINEAR & TRANSVAGINAL PROBE', 316800000, NULL, NULL),
(140, 'PRA-ONE + TROLLEY + THERMAL &  PRINTER COLOR + LINEAR & TRANSVAGINAL PROBE + UPS', 294800000, NULL, NULL),
(141, 'PRA-ONE + TROLLEY + THERMAL & PRINTER COLOR + LINEAR PROBE + UPS', 256960000, NULL, NULL),
(142, 'PRA-ONE + TROLLEY + THERMAL & PRINTER COLOR + UPS', 216700000, NULL, NULL),
(143, 'PRA-ONE + TROLLEY + UPS', 173800000, NULL, NULL),
(144, 'PRO SCANNER CONVEX ARRAY', 79200000, NULL, NULL),
(145, 'PRO SCANNER LINEAR ARRAY', 88000000, NULL, NULL),
(146, 'PRO SCANNER PHASED ARRAY', 99000000, NULL, NULL),
(147, 'PROMAX', 544500000, NULL, NULL),
(148, 'PROMAX + CONVEX & LINEAR PROBE', 608300000, NULL, NULL),
(149, 'PROMAX + CONVEX & PHASED ARRAY PROBE', 619300000, NULL, NULL),
(150, 'PROMAX + CONVEX & TRANSVAGINAL PROBE', 619300000, NULL, NULL),
(151, 'PROMAX + CONVEX, LINEAR & VOLUME PROBE', 782100000, NULL, NULL),
(152, 'PROMAX + LINEAR PROBE', 561000000, NULL, NULL),
(153, 'PROMAX + TROLLEY + PRINTER COLOR + LINEAR, TRANSVAGINAL & VOLUME PROBE', 1199000000, NULL, NULL),
(154, 'PROMAX + TROLLEY + THERMAL & PRINTER COLOR', 587400000, NULL, NULL),
(155, 'PROMAX + TROLLEY + THERMAL COLOR PRINTER', 609400000, NULL, NULL),
(156, 'PROMIST 1', 1232000, NULL, NULL),
(157, 'PROMIST 2', 798600, NULL, NULL),
(158, 'PROMIST 3', 4356000, NULL, NULL),
(159, 'PROMIST 3 + BACKUP POWER', 9119000, NULL, NULL),
(160, 'PROMIST 3 + TROLLEY', 12848000, NULL, NULL),
(161, 'PROMIST 3 + TROLLEY + BACKUP POWER', 17600000, NULL, NULL),
(162, 'PROTECTIVE SUIT-01', 6490000, NULL, NULL),
(163, 'PTB-2MTR', 1210000, NULL, NULL),
(164, 'ROLL PAPER FOR ECG-1200G', 1738000, NULL, NULL),
(165, 'ROLL PAPER FOR ECG-300G', 775500, NULL, NULL),
(166, 'SAFETY GOGGLE-01', 297000, NULL, NULL),
(167, 'SHOE COVER', 759000, NULL, NULL),
(168, 'SONOTRAX MED-01 + TROLLEY', 96800000, NULL, NULL),
(169, 'SONOTRAX MED-01 + TROLLEY + UPS', 117700000, NULL, NULL),
(170, 'SONOTRAX MED-01 + UPS', 108900000, NULL, NULL),
(171, 'SONOTRAX PRO', 11330000, NULL, NULL),
(172, 'SONOTRAX PRO + TROLLEY', 20570000, NULL, NULL),
(173, 'SONOTRAX PRO + TROLLEY + UPS', 39050000, NULL, NULL),
(174, 'SONOTRAX PRO + UPS', 30360000, NULL, NULL),
(175, 'SONOTRAX PRO2', 22990000, NULL, NULL),
(176, 'SONOTRAX PRO2 + TROLLEY', 32780000, NULL, NULL),
(177, 'SONOTRAX PRO2 + TROLLEY + UPS', 50875000, NULL, NULL),
(178, 'SONOTRAX PRO2 + UPS', 41800000, NULL, NULL),
(179, 'SONOTRAX-B', 2475000, NULL, NULL),
(180, 'SONOTRAX-C', 6787000, NULL, NULL),
(181, 'SP10', 8965000, NULL, NULL),
(182, 'TENSIONE', 4290000, NULL, NULL),
(183, 'TENSIONE + TROLLEY + POWER ADAPTOR', 8910000, NULL, NULL),
(184, 'THERM ONE', 1397000, NULL, NULL),
(185, 'TOP-308', 334400000, NULL, NULL),
(186, 'TOP-308 + UPS', 339350000, NULL, NULL),
(187, 'TS-5830', 189200000, NULL, NULL),
(188, 'TS-5830 + UPS', 213290000, NULL, NULL),
(189, 'TS-8830', 247390000, NULL, NULL),
(190, 'TS-8830 + UPS', 264000000, NULL, NULL),
(191, 'ULTRA MIST', 2904000, NULL, NULL),
(192, 'ULTRA MIST + BACKUP POWER', 7700000, NULL, NULL),
(193, 'ULTRA MIST + TROLLEY', 11968000, NULL, NULL),
(194, 'ULTRA MIST + TROLLEY + BACKUP POWER', 16753000, NULL, NULL),
(195, 'UV-40W', 5236000, NULL, NULL),
(196, 'UV-40W + WATER FILTER', 7557000, NULL, NULL),
(197, 'UV-40W + WATER FILTER + WASTAFEL', 21450000, NULL, NULL),
(198, 'ZTP 300', 44550000, NULL, NULL),
(199, 'ZTP 300 + UPS', 57640000, NULL, NULL),
(200, 'ZTP80AS-UPGRADE', 9680000, NULL, NULL),
(201, 'ZTP80AS-UPGRADE + UPS', 25960000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `no_po` varchar(255) NOT NULL,
  `tgl_po` date NOT NULL,
  `no_do` varchar(255) NOT NULL,
  `tgl_do` date NOT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `kelompok_produk_id` int(11) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nama_coo` varchar(255) DEFAULT NULL,
  `satuan` varchar(255) NOT NULL,
  `no_akd` varchar(255) DEFAULT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kelompok_produk_id`, `merk`, `tipe`, `nama`, `nama_coo`, `satuan`, `no_akd`, `ket`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'ELITECH ', 'ABPM50', 'AMBULATORY BLOOD PRESSURE MONITOR', 'AMBULATORY BLOOD PRESSURE MONITOR', 'Unit', '20501510581', '', '1', NULL, NULL),
(2, 1, 'ELITECH ', 'APRON', 'MEDICAL APRON', 'MEDICAL APRON', 'Box', '11603021706', '', '1', NULL, NULL),
(3, 1, 'ELITECH ', 'BABY DIGIT-ONE', 'TIMBANGAN BAYI MEKANIK', 'TIMBANGAN BAYI MEKANIK', 'Unit', '10901410295', '', '1', NULL, NULL),
(4, 1, 'ELITECH ', 'BABY ONE', 'BABY SCALE', 'BABY SCALE', 'Unit', '10901318002', '', '1', NULL, NULL),
(5, 1, 'ELITECH ', 'BB-200', 'INFANT INCUBATOR', 'INFANT INCUBATOR', 'Unit', '20903900076', '', '1', NULL, NULL),
(6, 1, 'ELITECH ', 'BL-50B', 'INFANT PHOTOTHERAPY UNIT', 'INFANT PHOTOTHERAPY UNIT', 'Unit', '20903900073', '', '1', NULL, NULL),
(7, 1, 'ELITECH ', 'BN-100', 'INFANT WARMER', 'INFANT WARMER', 'Unit', '20903900074', '', '1', NULL, NULL),
(8, 1, 'ELITECH ', 'BODY FAT PRO', 'DIGITAL SCALE / TIMBANGAN DIGITAL BMI ', 'DIGITAL SCALE / TIMBANGAN DIGITAL BMI ', 'Unit', '10901911085', '', '1', NULL, NULL),
(9, 1, 'ELITECH ', 'BR-100', 'INFANT RESUSCITATOR', 'INFANT RESUSCITATOR', 'Unit', '20403022754', '', '1', NULL, NULL),
(10, 1, 'ELITECH ', 'BT-100', 'INFANT INCUBATOR TRANSPORT', 'INFANT INCUBATOR TRANSPORT', 'Unit', '20902710901', '', '1', NULL, NULL),
(11, 1, 'ELITECH ', 'CENTRAL MONITOR PM-9000+ + PC + INSTALASI', 'CENTRAL MONITOR', 'CENTRAL MONITOR', 'Unit', '20903900075', '', '1', NULL, NULL),
(12, 1, 'ELITECH ', 'CMS-600 PLUS', 'B-ULTRASOUND DIAGNOSTIC SYSTEM', 'B-ULTRASOUND DIAGNOSTIC SYSTEM', 'Unit', '21102900256', '', '1', NULL, NULL),
(13, 1, 'ELITECH ', 'DIGIT ONE', 'PATIENT SCALE', 'PATIENT SCALE', 'Unit', '10901318000', '', '1', NULL, NULL),
(14, 1, 'ELITECH ', 'DIGIT-ONE BABY', 'TIMBANGAN BAYI DIGITAL', 'TIMBANGAN BAYI DIGITAL', 'Unit', '10901410291', '', '1', NULL, NULL),
(15, 1, 'ELITECH ', 'DIGIT-PRO', 'PATIENT SCALE', 'PATIENT SCALE', 'Unit', '10901318001', '', '1', NULL, NULL),
(16, 1, 'ELITECH ', 'DIGIT-PRO BMI', 'PATIENT SCALE', 'PATIENT SCALE', 'Unit', '10901910723', '', '1', NULL, NULL),
(17, 1, 'ELITECH ', 'DIGIT-PRO IDA', 'DIGITAL SCALE / TIMBANGAN DIGITAL IBU & ANAK', 'DIGITAL SCALE / TIMBANGAN DIGITAL IBU & ANAK', 'Unit', '10901910529', '', '1', NULL, NULL),
(18, 1, 'ELITECH ', 'DP1', 'ULTRASONIC POCKET DOPPLER', 'ULTRASONIC POCKET DOPPLER', 'Unit', '21101810460', '', '1', NULL, NULL),
(19, 1, 'ELITECH ', 'DS-PRO100', 'PORTABLE SPIROMETER', 'PORTABLE SPIROMETER', 'Unit', '20401710665', '', '1', NULL, NULL),
(20, 1, 'ELITECH ', 'ECG-100G', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502900072', '', '1', NULL, NULL),
(21, 1, 'ELITECH ', 'ECG-1200 MED', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502810371', '', '1', NULL, NULL),
(22, 1, 'ELITECH ', 'ECG-1200G', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502310189', '', '1', NULL, NULL),
(23, 1, 'ELITECH ', 'ECG-1800 MED', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502810372', '', '1', NULL, NULL),
(24, 1, 'ELITECH ', 'ECG-300G', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '21102900255', '', '1', NULL, NULL),
(25, 1, 'ELITECH ', 'END-1', 'MEDICAL DESTROYER', 'MEDICAL DESTROYER', 'Unit', '20902210075', '', '1', NULL, NULL),
(26, 1, 'ELITECH ', 'FOX PRO', 'HANDHELD PULSE OXIMETER', 'HANDHELD PULSE OXIMETER', 'Unit', '20502910952', '', '1', NULL, NULL),
(27, 1, 'ELITECH ', 'FOX-2', 'PULSE OXIMETER', 'PULSE OXIMETER', 'Unit', '20502210102', '', '1', NULL, NULL),
(28, 1, 'ELITECH ', 'FOX-3', 'PULSE OXIMETER', 'PULSE OXIMETER', 'Unit', '20502210101', '', '1', NULL, NULL),
(29, 1, 'ELITECH ', 'FOX-BABY', 'PULSE OXIMETER', 'PULSE OXIMETER', 'Unit', '20502318005', '', '1', NULL, NULL),
(30, 1, 'ELITECH ', 'GET 338 UO', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800291', '', '1', NULL, NULL),
(31, 1, 'ELITECH ', 'GET-160', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800287', '', '1', NULL, NULL),
(32, 1, 'ELITECH ', 'GET-80C', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800282', '', '1', NULL, NULL),
(33, 1, 'ELITECH ', 'ISOLATION GOWN-01', 'MEDICAL ISOLATION GOWN', 'MEDICAL ISOLATION GOWN', 'Box', '21603020315', '', '1', NULL, NULL),
(34, 1, 'ELITECH ', 'KJF-B100', 'MEDICAL PLASMA AIR STERILIZER', 'MEDICAL PLASMA AIR STERILIZER', 'Unit', '20903020466', '', '1', NULL, NULL),
(35, 1, 'ELITECH ', 'KJF-Y100', 'MEDICAL PLASMA AIR STERILIZER', 'MEDICAL PLASMA AIR STERILIZER', 'Unit', '20903020450', '', '1', NULL, NULL),
(36, 1, 'ELITECH ', 'MAP 380', 'MEDICAL AIR PURIFIER', 'MEDICAL AIR PURIFIER', 'Unit', '20902020924', '', '1', NULL, NULL),
(37, 1, 'ELITECH ', 'MATERNAL MED-02', 'FETAL MONITOR', 'FETAL MONITOR', 'Unit', '21101710864', '', '1', NULL, NULL),
(38, 1, 'ELITECH ', 'SONOTRAX MED-01', 'FETAL MONITOR', 'FETAL MONITOR', 'Unit', '21101710857', '', '1', NULL, NULL),
(39, 1, 'ELITECH ', 'MED-S100', 'SPO2 SIMULATOR', 'SPO2 SIMULATOR', 'Unit', '20401710856', '', '1', NULL, NULL),
(40, 1, 'ELITECH ', 'MED-S200', 'NIBP SIMULATOR', 'NIBP SIMULATOR', 'Unit', '20501710666', '', '1', NULL, NULL),
(41, 1, 'ELITECH ', 'MED-S400', 'PATIENT SIMULATOR', 'PATIENT SIMULATOR', 'Unit', '20502710662', '', '1', NULL, NULL),
(42, 1, 'ELITECH ', 'MEL-02', 'LAMPU PERIKSA LED', 'LAMPU PERIKSA LED', 'Unit', '10903710660', '', '1', NULL, NULL),
(43, 1, 'ELITECH ', 'MFT-01', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'Unit', '20901020234', '', '1', NULL, NULL),
(44, 1, 'ELITECH ', 'MFV-01', 'X-RAY FILM VIEWER', 'X-RAY FILM VIEWER', 'Unit', '21501810001', '', '1', NULL, NULL),
(45, 1, 'ELITECH ', 'MOC-A', 'OXYGEN CONCENTRATOR', 'OXYGEN CONCENTRATOR', 'Unit', '20403510582', '', '1', NULL, NULL),
(46, 1, 'ELITECH ', 'MOL-01', 'LAMPU OPERASI LED', 'LAMPU OPERASI LED', 'Unit', '21603710667', '', '1', NULL, NULL),
(47, 1, 'ELITECH ', 'MOL-02', 'LAMPU OPERASI LED', 'LAMPU OPERASI LED', 'Unit', '21603710788', '', '1', NULL, NULL),
(48, 1, 'ELITECH ', 'MTB-2MTR', 'METERAN PENGUKUR TINGGI BADAN', 'METERAN PENGUKUR TINGGI BADAN', 'Unit', '10901410291', '', '1', NULL, NULL),
(49, 1, 'ELITECH ', 'MTR-BABY 001', 'PENGUKUR PANJANG BAYI', 'PENGUKUR PANJANG BAYI', 'Unit', '10901410295', '', '1', NULL, NULL),
(50, 1, 'ELITECH ', 'PA-DC001', 'POWER ADAPTOR', 'POWER ADAPTOR', 'Unit', '10901410291', '', '1', NULL, NULL),
(51, 1, 'ELITECH ', 'PM PRO-1', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20502810355', '', '1', NULL, NULL),
(52, 1, 'ELITECH ', 'PM PRO-2', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20502810356', '', '1', NULL, NULL),
(53, 1, 'ELITECH ', 'PM PRO-3', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20502020925', '', '1', NULL, NULL),
(54, 1, 'ELITECH ', 'PM50', 'SPO2 MONITOR', 'SPO2 MONITOR', 'Unit', '20502510583', '', '1', NULL, NULL),
(55, 1, 'ELITECH ', 'PM-9000+', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20903900075', '', '1', NULL, NULL),
(56, 1, 'ELITECH ', 'PRA-ONE', 'DIGITAL USG MONITOR', 'DIGITAL USG MONITOR', 'Unit', '21102410010', '', '1', NULL, NULL),
(57, 1, 'ELITECH ', 'PRO SCANNER CONVEX ARRAY', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'Unit', '21101020853', '', '1', NULL, NULL),
(58, 1, 'ELITECH ', 'PRO SCANNER LINEAR ARRAY', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'Unit', '21101020853', '', '1', NULL, NULL),
(59, 1, 'ELITECH ', 'PRO SCANNER PHASED ARRAY', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'Unit', '21101020853', '', '1', NULL, NULL),
(60, 1, 'ELITECH ', 'PROMAX', 'USG 3D/4D COLOR DOPPLER ULTRASOUND', 'USG 3D/4D COLOR DOPPLER ULTRASOUND', 'Unit', '21102410011', '', '1', NULL, NULL),
(61, 1, 'ELITECH ', 'PROMIST 1', 'MINI COMPRESSOR NEBULIZER', 'MINI COMPRESSOR NEBULIZER', 'Unit', '20403318003', '', '1', NULL, NULL),
(62, 1, 'ELITECH ', 'PROMIST 2', 'MEDICAL NEBULIZER', 'MEDICAL NEBULIZER', 'Unit', '20403710512', '', '1', NULL, NULL),
(63, 1, 'ELITECH ', 'PROMIST 3', 'MEDICAL NEBULIZER', 'MEDICAL NEBULIZER', 'Unit', '20403710661', '', '1', NULL, NULL),
(64, 1, 'ELITECH ', 'PROTECTIVE SUIT-01', 'MEDICAL PROTECTIVE SUIT FOR OPERATING ROOM', 'MEDICAL PROTECTIVE SUIT FOR OPERATING ROOM', 'Box', '21603020348', '', '1', NULL, NULL),
(65, 1, 'ELITECH ', 'PTB-2MTR', 'PENGGARIS PENGUKUR TINGGI BADAN', 'PENGGARIS PENGUKUR TINGGI BADAN', 'Unit', '10901410291', '', '1', NULL, NULL),
(66, 1, 'ELITECH ', 'ROLL PAPER FOR ECG-1200G', 'KERTAS ECG / ROLL PAPER', 'KERTAS ECG / ROLL PAPER', 'Pack', '20502310189', '', '1', NULL, NULL),
(67, 1, 'ELITECH ', 'ROLL PAPER FOR ECG-300G', 'KERTAS ECG / ROLL PAPER', 'KERTAS ECG / ROLL PAPER', 'Pack', '21102900255', '', '1', NULL, NULL),
(68, 1, 'ELITECH ', 'SAFETY GOGGLE-01', 'MEDICAL SAFETY GOGGLE', 'MEDICAL SAFETY GOGGLE', 'Unit', '11603020313', '', '1', NULL, NULL),
(69, 1, 'ELITECH ', 'SHOE COVER', 'MEDICAL SHOE COVER', 'MEDICAL SHOE COVER', 'Box', '11603021451', '', '1', NULL, NULL),
(70, 1, 'ELITECH ', 'SONOTRAX PRO', 'DESKTOP FETAL DOPPLER / FETAL MONITOR', 'DESKTOP FETAL DOPPLER / FETAL MONITOR', 'Unit', '21101318006', '', '1', NULL, NULL),
(71, 1, 'ELITECH ', 'SONOTRAX PRO2', 'ULTRASONIC TABLE DOPPLER', 'ULTRASONIC TABLE DOPPLER', 'Unit', '21101810461', '', '1', NULL, NULL),
(72, 1, 'ELITECH ', 'SONOTRAX-B', 'POCKET FETAL DOPPLER', 'POCKET FETAL DOPPLER', 'Unit', '21102800003', '', '1', NULL, NULL),
(73, 1, 'ELITECH ', 'SONOTRAX-C', 'POCKET FETAL DOPPLER', 'POCKET FETAL DOPPLER', 'Unit', '21101710077', '', '1', NULL, NULL),
(74, 1, 'ELITECH ', 'SP10', 'DIGITAL SPIROMETER DS-PRO', 'DIGITAL SPIROMETER DS-PRO', 'Unit', '20401610237', '', '1', NULL, NULL),
(75, 1, 'ELITECH ', 'TENSIONE', 'BLOOD PRESSURE MONITOR', 'BLOOD PRESSURE MONITOR', 'Unit', '20501318004', '', '1', NULL, NULL),
(76, 1, 'ELITECH ', 'THERM ONE', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'Unit', '20901020251', '', '1', NULL, NULL),
(77, 1, 'ELITECH ', 'TOP-308', 'DENTAL UNIT', 'DENTAL UNIT', 'Unit', '10605900070', '', '1', NULL, NULL),
(78, 1, 'ELITECH ', 'TS-5830', 'DENTAL UNIT', 'DENTAL UNIT', 'Unit', '10605900071', '', '1', NULL, NULL),
(79, 1, 'ELITECH ', 'TS-8830', 'DENTAL UNIT', 'DENTAL UNIT', 'Unit', '10605810069', '', '1', NULL, NULL),
(80, 1, 'ELITECH ', 'ULTRA MIST', 'ULTRASONIC NEBULIZER', 'ULTRASONIC NEBULIZER', 'Unit', '20403710663', '', '1', NULL, NULL),
(81, 1, 'ELITECH ', 'UV-40W', 'UV WATER STERILIZER', 'UV WATER STERILIZER', 'Unit', '20903410009', '', '1', NULL, NULL),
(82, 1, 'ELITECH ', 'ZTP 300', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800288', '', '1', NULL, NULL),
(83, 1, 'ELITECH ', 'ZTP80AS-UPGRADE', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903700359', '', '1', NULL, NULL),
(84, 1, 'ELITECH ', 'TROLLEY', 'TROLLEY ', '', 'Unit', '', '', '1', NULL, NULL),
(85, 1, 'ELITECH ', 'TROLLEY TIANG', 'TROLLEY TIANG', '', 'Unit', '', '', '1', NULL, NULL),
(86, 1, 'ELITECH ', 'TROLLEY MEJA', 'TROLLEY MEJA', '', 'Unit', '', '', '1', NULL, NULL),
(87, 1, 'ELITECH ', 'BACKUP POWER', 'BACKUP POWER', '', 'Unit', '', '', '1', NULL, NULL),
(88, 1, 'ELITECH ', 'OUTLET', 'OUTLET', '', 'Unit', '', '', '1', NULL, NULL),
(89, 1, 'ELITECH ', 'UPS', 'UPS', '', 'Unit', '', '', '1', NULL, NULL),
(90, 1, 'ELITECH ', 'POWER ADAPTOR', 'POWER ADAPTOR', '', 'Unit', '', '', '1', NULL, NULL),
(91, 1, 'ELITECH ', 'LINEAR PROBE', 'LINEAR PROBE', '', 'Unit', '', '', '1', NULL, NULL),
(92, 1, 'ELITECH ', 'PRINTER', 'PRINTER', '', 'Unit', '', '', '1', NULL, NULL),
(93, 1, 'ELITECH ', 'THERMAL PRINTER', 'THERMAL PRINTER', '', 'Unit', '', '', '1', NULL, NULL),
(94, 1, 'ELITECH ', 'LINEAR PROBE', 'LINEAR PROBE', '', 'Unit', '', '', '1', NULL, NULL),
(95, 1, 'ELITECH ', 'VOLUME PROBE', 'VOLUME PROBE', '', 'Unit', '', '', '1', NULL, NULL),
(96, 1, 'ELITECH ', 'CONVEX PROBE', 'CONVEX PROBE', '', 'Unit', '', '', '1', NULL, NULL),
(97, 1, 'ELITECH ', 'TRANSVAGINAL PROBE', 'TRANSVAGINAL PROBE', '', 'Unit', '', '', '1', NULL, NULL),
(98, 1, 'ELITECH ', 'WATERFILTER', 'WATERFILTER', '', 'Unit', '', '', '1', NULL, NULL),
(99, 1, 'ELITECH ', 'WASTAFEL', 'WASTAFEL', '', 'Unit', '', '', '1', NULL, NULL),
(100, 1, 'ELITECH ', 'TAS', 'TAS', '', 'Unit', '', '', '1', NULL, NULL),
(101, 1, 'ELITECH ', 'BODY FAT', 'BODY FAT', '', 'Unit', '', '', '1', NULL, NULL),
(102, 1, 'ELITECH ', 'TELE', 'TELE', '', 'Unit', '', '', '1', NULL, NULL),
(103, 1, 'ELITECH ', 'WATER FILTER', 'WATER FILTER', '', 'Unit', '', '', '1', NULL, NULL),
(104, 1, 'ELITECH ', 'PIPING', 'PIPING', '', 'Unit', '', '', '1', NULL, NULL),
(105, 1, 'ELITECH ', 'PHASED ARRAY PROBE', 'PHASED ARRAY PROBE', '', 'Unit', '', '', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `spa`
--

CREATE TABLE `spa` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` enum('sepakat','negosiasi','batal') NOT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `spb`
--

CREATE TABLE `spb` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` enum('sepakat','negosiasi','batal') NOT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `divisi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('online','offline') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `divisi_id`, `nama`, `username`, `email`, `password`, `foto`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 14, 'Dela', 'delait04', 'dela@gmail.com', '$2y$10$iAoWIipcduQIPvYIYxVEs.wDxAMV3dJt.hTh2znjdBtDhY5tWKclO', '', 'offline', NULL, '2021-02-15 23:25:09', '2021-02-15 23:25:09'),
(2, 17, 'Uci Puspita', 'uciprd02', 'uci@gmail.com', '$2y$10$8ge5gLaiS5mKF5Lz6DwhsOUVFMm19ZvW4B0eVAArADBrnSOaGH402', 'Uci Puspita Sari.jpg', 'offline', NULL, '2021-02-15 23:28:06', '2021-02-15 23:28:06'),
(3, 24, 'Farah Diska B', 'farahppic01', 'farah@gmail.com', '$2y$10$p1pBKg1kGFn88H8YwvKPDe7De/RQP4C/szz6tWYcXzZEW4pUm5jMu', NULL, 'offline', NULL, '2021-02-15 23:30:16', '2021-02-15 23:30:16'),
(5, 7, 'Muzdalifah', 'ifaadm05', 'ifa@gmail.com', '$2y$10$97vPP5Zm7sXD0S5BmVxqC.DK/Tzv3/dlZN7V9PF1AactBbNOMompe', NULL, 'online', NULL, '2021-02-24 23:53:37', '2021-02-24 23:53:37'),
(6, 1, 'Ari Wijaya', 'ari_wijaya82', 'ariwijaya.its@gmail.com', '$2y$10$SFitjt0T0Al2UZlq6dslUOP/jZs/E.ZliliZxsmHp8KHtWfgYtar.', NULL, 'online', NULL, '2021-02-25 01:55:17', '2021-02-25 01:55:17'),
(7, 23, 'Septian Achmad S', 'septianqc01', 'septian@gmail.com', '$2y$10$Aut6kCa8dvxbC80bMgJ7GujPhcXW0F0I6/Xrxgg6/78h.84QOVhCm', NULL, 'offline', NULL, '2021-03-02 08:50:35', '2021-03-02 08:50:35'),
(8, 26, 'Nora Novitasari', 'norapenj01', 'nora@gmail.com', '$2y$10$Q9osQ1rCEGIDYasUz6e0s.7IH7AY55bLF361/ZhsdMGYv4wkwwXWa', NULL, 'offline', NULL, '2021-03-17 04:43:06', '2021-03-17 04:43:06'),
(9, 14, 'Wisnu', 'wisnuit03', 'wisnu@gmail.com', '$2y$10$xXSO6ak0QmYLTpIo1IDoq.fROzQu9WhdAEjG80Ki3jHk83POopxz2', NULL, 'offline', NULL, '2021-03-30 04:09:40', '2021-03-30 04:09:40'),
(10, 28, 'Hana', 'hana', 'hana@gmail.com', '$2y$10$EhyPPPA/HT9foUwbRXKraeGmA51hns1i7VUfCKZIbkV6p62C9lS4K', 'hana.png', 'online', NULL, '2021-05-10 07:11:40', '2021-05-10 07:11:40'),
(11, 10, 'Elvina Ambarwati', 'elvinaeng11', 'elvinaeng11@gmail.com', '$2y$10$E1hK8WNsA8LUm8xTkQeYaeYA8VcbllRubzjL9bPZI0nsBMbW/bXfa', NULL, 'online', NULL, '2021-05-24 01:08:13', '2021-05-24 01:08:13'),
(12, 10, 'Ardhiefa R', 'ardhiefaeng12', 'ardhiefaeng12@gmail.com', '$2y$10$yJ3f/jQDXhw/As8Bo0iiuOJibrZFwCBVjgil6IdPfEGtTRYaS4b2q', NULL, 'online', NULL, '2021-05-24 01:09:25', '2021-05-24 01:09:25'),
(13, 16, 'Adi Putra Firmantika', 'adimtc02', 'adi@gmail.com', '$2y$10$oj9N1CE89n5hFFVYSEhZxeB252OI09aKtu0bxj5hIPUxqn5UDmAw2', NULL, 'online', NULL, '2021-06-11 03:56:36', '2021-06-11 03:56:36'),
(14, 11, 'wiwin', 'wiwin', 'wiwin@gmail.com', '$2y$10$xoiAzaqOFkveK.YODKPjleZuDtEK3UCQ5xJhWbunblFJmXwVijSvK', NULL, 'online', NULL, '2021-06-29 03:47:35', '2021-06-29 03:47:35'),
(17, 23, 'Suci Intan Pravity', 'suciqc03', 'suci@gmail.com', '$2y$10$s2unK8p6oAhFQTzdqaDx7OtbISk4.7kKscTSZvsV9ciGNdERSVbca', NULL, 'online', NULL, '2021-07-12 04:17:34', '2021-07-12 04:17:34'),
(18, 3, 'Kusnardiana Rahayu', 'anna', 'anna@gmail.com', '$2y$10$uj/8E40i4jrsP9dKE.S.IO0WwYDp7TlPDA/FQUbCnomytcxyky81S', NULL, 'online', NULL, '2021-07-14 22:22:53', '2021-07-14 22:22:53'),
(19, 22, 'Dinda Trisakti', 'dindalab01', 'dinda@gmail.com', '$2y$10$LaA8ogkan.qv5HcZxiARwOLMANoT/6mtQ/Nt6feAdlsZ62WPfkCcm', NULL, 'online', NULL, '2021-07-19 02:37:31', '2021-07-19 02:37:31'),
(21, 11, 'Ali Sukoco', 'aligbj01', 'ali@gmail.com', '$2y$10$3OTTWQBqTTBt6b4WsUFmQevnLsAo7LuPFwRRhGEPCTRc0.MC.YE82', NULL, 'online', NULL, '2021-08-13 01:46:57', '2021-08-13 01:46:57'),
(22, 13, 'Nur Kholidah', 'idagbj02', 'ida@gmail.com', '$2y$10$YSEicx/W7euW/3GRGI7vmeAM/Aj.bEfn.k7C5Bzddf8FR9dfe0o9W', NULL, 'online', NULL, '2021-08-18 03:09:09', '2021-08-18 03:09:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_ekatalog`
--
ALTER TABLE `detail_ekatalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualan_id` (`penjualan_id`,`penjualan_produk_id`);

--
-- Indexes for table `detail_penjualan_produk`
--
ALTER TABLE `detail_penjualan_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_id` (`produk_id`,`penjualan_produk_id`),
  ADD KEY `penjualan_produk_id` (`penjualan_produk_id`);

--
-- Indexes for table `detail_pesanan_ekatalog`
--
ALTER TABLE `detail_pesanan_ekatalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`,`ekatalog_id`);

--
-- Indexes for table `detail_pesanan_spa`
--
ALTER TABLE `detail_pesanan_spa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`,`spa_id`);

--
-- Indexes for table `detail_pesanan_spb`
--
ALTER TABLE `detail_pesanan_spb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`,`spb_id`);

--
-- Indexes for table `detail_spa`
--
ALTER TABLE `detail_spa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spa_id` (`spa_id`,`penjualan_produk_id`),
  ADD KEY `penjualan_produk_id` (`penjualan_produk_id`);

--
-- Indexes for table `detail_spb`
--
ALTER TABLE `detail_spb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spb_id` (`spb_id`,`penjualan_produk_id`),
  ADD KEY `penjualan_produk_id` (`penjualan_produk_id`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ekatalog`
--
ALTER TABLE `ekatalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gdg_barang_jadi`
--
ALTER TABLE `gdg_barang_jadi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `kelompok_produk`
--
ALTER TABLE `kelompok_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `noseri_barang_jadi`
--
ALTER TABLE `noseri_barang_jadi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gdg_barang_jadi` (`gdg_barang_jadi_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `penjualan_produk`
--
ALTER TABLE `penjualan_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelompok_produk_id` (`kelompok_produk_id`);

--
-- Indexes for table `spa`
--
ALTER TABLE `spa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `spb`
--
ALTER TABLE `spb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_divisi_id_foreign` (`divisi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_ekatalog`
--
ALTER TABLE `detail_ekatalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_penjualan_produk`
--
ALTER TABLE `detail_penjualan_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;

--
-- AUTO_INCREMENT for table `detail_pesanan_ekatalog`
--
ALTER TABLE `detail_pesanan_ekatalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pesanan_spa`
--
ALTER TABLE `detail_pesanan_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pesanan_spb`
--
ALTER TABLE `detail_pesanan_spb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_spa`
--
ALTER TABLE `detail_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_spb`
--
ALTER TABLE `detail_spb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ekatalog`
--
ALTER TABLE `ekatalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gdg_barang_jadi`
--
ALTER TABLE `gdg_barang_jadi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelompok_produk`
--
ALTER TABLE `kelompok_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `noseri_barang_jadi`
--
ALTER TABLE `noseri_barang_jadi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penjualan_produk`
--
ALTER TABLE `penjualan_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `spa`
--
ALTER TABLE `spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spb`
--
ALTER TABLE `spb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_ekatalog`
--
ALTER TABLE `detail_ekatalog`
  ADD CONSTRAINT `detail_ekatalog_ibfk_1` FOREIGN KEY (`penjualan_id`) REFERENCES `ekatalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_penjualan_produk`
--
ALTER TABLE `detail_penjualan_produk`
  ADD CONSTRAINT `detail_penjualan_produk_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penjualan_produk_ibfk_3` FOREIGN KEY (`penjualan_produk_id`) REFERENCES `penjualan_produk` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detail_pesanan_spa`
--
ALTER TABLE `detail_pesanan_spa`
  ADD CONSTRAINT `detail_pesanan_spa_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_spa`
--
ALTER TABLE `detail_spa`
  ADD CONSTRAINT `detail_spa_ibfk_1` FOREIGN KEY (`spa_id`) REFERENCES `spa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_spa_ibfk_2` FOREIGN KEY (`penjualan_produk_id`) REFERENCES `penjualan_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_spb`
--
ALTER TABLE `detail_spb`
  ADD CONSTRAINT `detail_spb_ibfk_1` FOREIGN KEY (`spb_id`) REFERENCES `spb` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_spb_ibfk_2` FOREIGN KEY (`penjualan_produk_id`) REFERENCES `penjualan_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ekatalog`
--
ALTER TABLE `ekatalog`
  ADD CONSTRAINT `ekatalog_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gdg_barang_jadi`
--
ALTER TABLE `gdg_barang_jadi`
  ADD CONSTRAINT `gdg_barang_jadi_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `noseri_barang_jadi`
--
ALTER TABLE `noseri_barang_jadi`
  ADD CONSTRAINT `noseri_barang_jadi_ibfk_1` FOREIGN KEY (`gdg_barang_jadi_id`) REFERENCES `gdg_barang_jadi` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kelompok_produk_id`) REFERENCES `kelompok_produk` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `spa`
--
ALTER TABLE `spa`
  ADD CONSTRAINT `spa_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spb`
--
ALTER TABLE `spb`
  ADD CONSTRAINT `spb_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
