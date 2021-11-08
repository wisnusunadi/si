-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2021 at 10:29 AM
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
  `id_provinsi` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `telp` bigint(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `npwp` varchar(25) DEFAULT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `id_provinsi`, `nama`, `telp`, `email`, `alamat`, `npwp`, `ket`, `created_at`, `updated_at`) VALUES
(1, 11, 'CV. Cipta Jaya Medika', 0, NULL, 'JL. Biliton No. 15 Gubeng - Surabaya', '12.454.456.1-606.000', NULL, NULL, '2021-10-28 23:10:44'),
(2, 6, 'Gunung Bayan Pratama Coal', 0, NULL, 'Graha Irama Lt. 12, JL. HR. Rasuna said Blok X-1 Kav. 1-2, Kuningan Timur-Setia Budi-Jakarta Selatan 12950', '01.467.051.7-091.000', NULL, '0000-00-00 00:00:00', '2021-10-27 02:59:22'),
(3, 6, 'Firman Ketaun Perkasa', 0, NULL, 'Graha Irama Lt 12, JL. HR. Rasuna Said Blok X-1 Kav. 1 No. 2, Kuningan Timur-Setia Budi-Jakarta selatan-DKI Jakarta Raya', '01.856.455.9-063.000', NULL, '0000-00-00 00:00:00', '2021-10-27 18:59:23'),
(4, 11, 'CV. Golden Elite Technology', 0, '', 'JL. Kalianyar 17 E, Surabaya', '01.922.786.7-611.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 6, 'PT. Indevco Karya Sakti', 0, '', 'JL. P. Jayakarta 131 Blok A No. 31 Mangga Dua selatan, Sawah Besar Jakarta Pusat, DKI Jakarta raya 10730', '01.109.118.8-026.001', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 11, 'PT. Indevco Karya Sakti', 0, '', 'JL. Rajawali No. 86, Surabaya', '01.109.118.8-605.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 6, 'PT. Firman Ketaun Perkasa', 0, '', 'Graha Irama Lt. 12, JL. HR. Rasuna Said Blok X-1 Kav. 1 No. 2, Kuningan Timur-Setia Budi-Jakarta selatan-DKI Jakarta Raya', '01.856.455.9-603.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 6, 'PT. Mas Eltrajaya', 0, '', 'Komplek Grogol Permai, JL. Prof DR. Latumenten Blok C No. 21, Petambunan Jakarta Barat-DKI Jakarta Raya 11460', '02.691.647.8-036.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 18, 'PT. Sarana Sahabat Maju', 0, '', 'JL. Kartini No. 43 C / D-Tanjung Karang Tanjun Karang Pusat-Bandar Lampung 35111', '02.523.217.4-322.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 1, 'Tri Kartono Andries', 0, '', 'JL. Teuku Umar No. 24, Denpasar', '06.336.343.6-901.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 6, 'PT. Wahana Barata Mining', 0, '', 'Graha Irama Lt. 12, JL. HR. Rasuna Said Blok X-1 Kav. 1-2, Kuningan Timur-Setia Budi-Jakarta selatan', '01.711.061.0-062.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 6, 'PT. Pradana Estiara Medical', 0, '', 'Kapuk Kamal Raya No. 20 A Blok A/3 Kamal Muara-Penjaringan-Jakarta Utara', '02.296.542.0-047.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 11, 'PT. Jatim Watkoraya', 0, '', 'JL. Kalianyar 15D, Surabaya', '01.469.421.0-611.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 10, 'CV. Sanidata', 0, '', 'JL. Dr. Cipto No. 174, Semarang', '01.469.421.0-611.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 11, 'PT. Cipta Jaya Medindo', 0, '', 'JL. Jawa 47 A RT 002 RW 009, Gubeng, Gubeng Surabaya - Jawa Timur', '02.822.869.0-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 6, 'PT. Teja Sekawan', 0, '', 'JL. Pangeran Jayakarta 131 A No. 36 RT 07 RW 07, Kel. Mangga Dua Selatan, Kec. Sawah Besar, Jakarta Pusat', '01.148.458.1-026.001', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 11, 'PT. Rama Mulia Cosmetic Industry', 0, '', 'Ds. Tenaru RT. 011 RW 004, Tenaru-Driyorejo, Gresik-61177', '01.677.559.5-642.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 9, 'PT. Asta Kurnia Abadi', 0, '', 'JL. Jupiter Utama No. 12, Sekejati Buah Batu, Kota Bandung, Jawa Barat', '02.497.186.3-441.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 11, 'CV. Jaya Abadi', 0, '', 'Rungkut Alang-Alang 121 RT. 009 RW. 005, Kali Rungkut-Rungkut, Surabaya-60293', '02.993.662.2-615.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 11, 'PT. Novapharin', 0, '', 'JL. Kepatihan No. 112, Kepatihan-Menganti, Gresik-Jawa Timur', '01.479.850.8-641.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 3, 'PT. Bersama Tiga Jaya Sukses', 0, '', 'Ruko Golden Road C 28 No. 23 A, Kel. Lengkung Wetan, Kec. Serpong, Tangerang Selatan-Banten', '66.601.464.2-411.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 11, 'PT. Mega Antar Nusa', 0, '', 'JL. Achmad Yani Ruko Central Square C-20, RT 005 RW 002 Gedangan, Sidoarjo-61254', '31.695.034.4-643.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 6, 'PT. Tirta Sukses Perkasa', 0, '', 'The City Tower Lt. 15, JL. MH Thamrin No. 81, Menteng-Menteng, Jakarta Pusat-DKI Jakarta', '03.322.819.8-071.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 11, 'PT. Surya Artha Medica', 0, '', 'JL. Abdul Wahab Siamin Blok RC-31, Dukuh Pakis-Surabaya', '02.175.719.0-618.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 11, 'PT. Sorini Agro Asia Corporindo Tbk', 0, '', 'JL. Raya Surabaya - Malang KM 43 Ngerong Gempol Kab. Pasuruan Jawa Timur', '01.211.173.8-054.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 9, 'PT. Tri Sumber Makmur Indah', 0, '', 'JL. Raya Pangalengan No. 391 A, Kamasan Banjaran Kab. Bandung, Jawa Barat', '02.267.177.0-441.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 6, 'BUT GS. Engineering & Construction Corporation', 0, '', 'Gedung BRI II Lt. 17 Suite 1703, JL. Jend. Sudirman Kav. 44-46, Bendungan Hilir, Tanah Abang, Jakarta Pusat-10210', '02.979.446.8-077.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 6, 'PT. PLN (Persero) Kantor Pusat', 0, '', 'JL. Trunojoyo Blok M1 / 135 Melawai, Kebayoran Baru-Jakarta Selatan', '01.001.629.3-051.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 11, 'PT. Duta Inti sarana Utama', 0, '', 'Perum Wisma Indah Blok F No. 2, RT 004 RW 006, Kepanjenior, Kepanjen Kidul, Blitar', '31.497.856.0-653.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 6, 'PT. Dimas Surya Indonesia', 0, '', 'JL. Raya Penggilingan Komp. PIK Blok E No. 269, Kel. Penggilingan, Kec. Cakung, Jakarta Timur', '31.683.396.1-004.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 11, 'PT. Sumber Bahagia Sejahtera Abadi', 0, '', 'JL. Raya Darmo 131-133, Surabaya', '02.377.239.5-609.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 6, 'PT. AMPM Health Care Indonesia', 0, '', 'JL. Cengkeh Kav. XVI No. 29/30 RT 007 RW 007, Pinangsia-Taman Sari, Jakarta Barat', '03.040.550.0-037.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 10, 'CV. Karya Hidup Sentosa', 0, '', 'JL. Magelang No. 144, Kel. Karangwaru Kec. Tegalrejo, Yogyakarta, 55241 (DIY)', '01.132.866.3-541.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 11, 'Maria Linggiarti', 0, '', 'JL. Biliton No. 81, Surabaya', '04.211.424.9-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 10, 'PT. Aries Indo Global', 0, '', 'JL. RE Martadinata Komp. Ruko Muitiara Marina No. 37, Tawangsari, Semarang Barat, Semarang', '02.914.666.9-511.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 13, 'PT. Delta Surya Alkesindo', 0, '', 'JL. Veteran No. 63 B RT 011, Melayu-Banjarmasin Tengah, Banjarmasin-Kalimantan Selatan', '02.172.532.0-731.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 6, 'PT. Yamhatevy Paran Mandiri', 0, '', 'JL. Raya Gatot Subroto No. 70 RT 031 Kuripan, Banjarmasin Timur, Banjarmasin-70236', '01.879.157.4-731.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 6, 'PT. Elektro Medika Internasional Indonesia', 0, '', 'Komp. Pertokoan Pulomas IX No. 6, Pulo Gadung, Pulo Gadung, Jakarta Timur', '01.314.381.3-003.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 9, 'CV. Banyu Adhi Utama', 0, '', 'Perumahan Permata Archadia Blok C4/24 No. 23 RT 003 RW 023, Sukabumi - Tapos, Depok-Jawa Barat', '31.819.491.7-412.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 11, 'PT. Amadea Devina Farma', 0, '', 'JL. Rungkut Mejoyo Selatan IV Blok U-3, Kalirungkut, Rungkut, Surabaya-60293', '01.976.398.6-615.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 6, 'PT. Mitra Tirta Sukses', 0, '', 'JL. Rajawali Blok BB/07, Komp. Cipinang Indah II RT 015 RW 003, Kel. Pondok Bambu, Kec. Duren Sawit, Jakarta Timur, DKI Jakarta', '31.255.283.9-008.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 6, 'PT. Cipta Agung Manis', 0, '', 'JL. Brigjen Katamso No. 10, Kota Bambu Selatan, Palmerah Jakarta Barat-11420', '02.379.872.1-031.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 9, 'CV. Athallah Putra', 0, '', 'Komp. Graha Pesona Blok C No. 118 RT 004 RW 002, Kel. Cisaranten Wetan Kec. Cinambo, Kota Bandung, Jawa Barat', '72.688.945.4-429.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 6, 'PT. Ultra Prima Abadi', 0, '', 'Daan Mogot Km 16 Semanan Kalideres, Jakarta Barat', '01.300.822.2-038.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 3, 'PT. CS2 Pola Sehat', 0, '', 'Yos Sudarso No. 143, Kebon Besar-Batu Ceper, Tangerang', '02.426.679.3-415.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 1, 'PT. Sanidata Indonesia', 0, '', 'JL. Teuku Umar No. 22, Dauh Puri Klod-Denpasar Barat, Denpasar-Bali, 80114', '31.786.666.3-901.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 11, 'PT. Berkah Karya Thuba', 0, '', 'Perum Gebang Raya AG No. 010 RT 022 RW 006, Kel. Gebang Kec. Sidoarjo, Sidoarjo-Jawa Timur', '71.852.756.7-617.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 10, 'CV. Daya Prima', 0, '', 'JL. Kaligarang No. 1A, Petemon, Gajah Mungkur, Semarang', '01.120.976.4-511.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 10, 'PT. Sanidata Putri Medika', 0, '', 'JL. Dr. Cipto 174 Karang Tempel, Semarang Timur, Semarang, Jawa Tengah', '01.985.712.7-511.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 10, 'PT. Daya Prima Kartika Multi Sarana', 0, '', 'JL. Kumudasmoro Utara No. 19D RT. 001 RW. 007 Bangsari Semarang Barat, Kota Semarang Jawa Tengah', '01.551.769.1-503.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 31, 'Bendahara Dinas Kesehatan Kabupaten Kolaka', 0, '', 'JL. Pancasila No. 12, Kelurahan Latambaga', '00.130.680.2-815.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 11, 'PT. Prasasti Dwimitra', 0, '', 'Jl. Wisma Tengger XVII No. 05 RT 002 RW 006, Kandangan - Benowo, Surabaya', '31.481.739.6-604.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 11, 'PT. Bima Putra Adirajada Surabaya', 0, '', 'JL. Demak No. 67 RT 001 RW 009, Tembok Dukuh, Bubutan Surabaya', '76.222.798.1-614.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 13, 'PT. Samudera Jaya Listrik', 0, '', 'JL. H. Djok Mentaya No. 29 RT 001 RW 001, Kertak Baru Ilir, Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan', '76.302.308.2-731.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 28, 'Bend. Pengeluaran Dinas Kesehatan Kab. Toraja Utara', 0, '', 'JL. Taman Makam Pahlawan, Karassik, Rantepao, Toraja Utara', '00.693.798.1-803.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 12, 'Pemegang Kas RSU H. Daman Huri', 0, '', 'JL. Murakata No. 04 Bukat, Barabai, Hulu Sungai Tengah, Kalimantan Selatan 71351', '00.368.901.5-733.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 28, 'PT. Ridho Agung Utama', 0, '', 'JL. Botol Empangan No. 5, Mangkura-Ujung Pandang, Makasar-Sulawesi Selatan', '01.250.395.9-812.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 19, 'Bend. Kantor Kesehatan Pelabuhan', 0, '', 'Kompleks Pelabuhan Ambon Honipopu-Sirimau-Ambon', '00.014.087.1-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 11, 'PT. Panasea', 0, '', 'JL. Veteran No. 39 A RT 022 RW 003, Melayu-Banjarmasin Tengah, Banjarmasin', '03.062.885.3-731.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 15, 'PT. Buana Medistra Pharma', 0, '', 'JL. Cendrawasih No. 56, Pontianak-Kalimantan Barat', '01.667.282.6-701.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 24, 'PT. Karya Sindo Papua', 0, '', 'JL. Kapal Depan Perumahan Polda Dok. VIII RT 004 RW 004 Kelimbi Kec. Jayapura Utara Kota Jayapura, Papua', '72.095.180.5-952.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 11, 'Bend. Puskesmas Balongbendo', 0, '', 'Mayjen Bambang Yuwono 2 Seduri Balongbendo, Sidoarjo', '00.841.088.8-603.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 12, 'PT. Mitra Alkesindo Utama', 0, '', 'JL. Delima No. 27 RT. 049 Sidodadi Samarinda Ulu Samarinda Kalimantan Timur', '02.196.253.5-722.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 11, 'PT. Rizki Anugerah Multikarya', 0, '', 'Raya Wiyung Baru MM-II RT 003 RW 006, Jajar Tunggal, Wiyung, Surabaya, Jawa Timur', '31.664.088.7-618.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 11, 'Bend. Rumah Sakit Umum Malang', 0, '', 'JL. Jagung Suprapto Samaan, Klojen, Kota Malang-65112', '00.007.579.6-623.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 11, 'Rutin Rumah Sakit Umum Daerah Genteng', 0, '', 'JL. Hasanudin No. 98, Genteng, Genteng, Banyuwangi', '00.036.186.5-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 31, 'PT. Mitra Medika Sejahtera Bersama', 0, '', 'JL. N. Yos Sudarso Paal Dua Manado, Sulawesi Utara', '02.701.118.8-821.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 34, 'PT. Multiplus Medilab', 0, '', 'JL. Danau Marsabut No. 4. Kel. Sel. Agung, Kec. Medan Barat, Medan-Sumatera Utara', '02.716.684.2-111.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 11, 'Bend. Pengeluaran RSUD Dr. Soegiri Lamongan', 0, '', 'JL. Kusuma Bangsa No. 7 RT 004 RW 005, Tumenggungan-Lamongan 62214', '00.308.068.6-645.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 11, 'Bend. Pengeluaran Dinkes Kab. Mojokerto', 0, '', 'JL. RA Basumi No. 4 RT 05 RW 03 Jampirogo Sooko Kab. Mojokerto-Jawa Timur', '00.401.793.5-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 11, 'Bend. P.G.A.N. 4TH/MTSN', 0, '', 'JL. H. A. Samanhudi No. 15 Pacitan, Pacitan, Pacitan', '00.007.079.7-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 11, 'Bend. Pengeluaran Pembantu Puskesmas Sidoarjo', 0, '', 'JL. Dr. Soetomo No. 14 RT/RW Magersari Sidoarjo, Siodarjo', '00.560.822.9-617.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 12, 'Bend. RSUD DR. A. Diponegoro Putussibau', 0, '', 'JL. Kom. Yos Sudarso, Kel. Putusibau kota, Kec. Putussibau Utara, Kab. Kapuas Hulu', '00.329.767.8-706.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 11, 'PT. Permata Hati Lamongan', 0, '', 'JL. Raya Ofandels RT 0 / RW 0, Paciran, Paciran, Lamongan, Jawa Timur', '31.664.357.6-645.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 11, 'Dinas Kesehatan Kota Surabaya', 0, '', 'JL. Jemursari 197, Margorejo, Wonocolo, Surabaya', '00.137.508.8-609.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 11, 'PT. Pilarindo Bakti Pertiwi', 0, '', 'JL. Gubeng Kertajaya 9 C / 42 A, Airlangga-Gubeng, Surabaya-Jawa Timur', '31.727.008.0-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 6, 'PT. Tamron Akuatik Produk', 0, '', 'JL. P. Jayakarta 24 No. 23, Mangga Dua Selatan, Sawah Besar, Jakarta Pusat, DKI Jakarta', '74.634.394.6-026.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 11, 'Bend. Pengeluaran SKPD Dinas Kesehatan Kab. Jember', 0, '', 'JL. Srikoyo 1/3 Bintoro Patrang, Jember, Jawa Timur-Indonesia', '00.318.672.3-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 22, 'Bend. Pemegang Kas Rumah Sakit Umum Daerah Praya', 0, '', 'JL. Basuki Rahmat No. 11, Praya-Praya Lombok Tengah 83511', '00.334.059.3-915.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 4, 'Dinas Kesehatan Benteng', 0, '', 'JL. Raya Bengkulu Curup, Karang Tinggi, Bengkulu Tengah-Bengkulu', '00.815.324.9-328.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 11, 'Donny Lesmana', 0, '', 'JL. Perum Kenanga Jaya Kav. II/22, Pandanwangi Blimbing-Malang', '25.903.583.0-652.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 11, 'Bend. Operasional Puskesmas Sempu', 0, '', 'JL. Kalisetail No. 170, Kel. Sempu, Kec. Sempu, Banyuwangi, Jawa Timur', '00.219.934.7-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 11, 'Pemegang Kas Dinas Kesehatan Banyuwangi', 0, '', 'JL. Letkol Istiqlah No. 42, Singonegaran, Banyuwangi', '00.036.224.4-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 6, 'PT. Sentrasarana Tirtabening', 0, '', 'JL. Kemukus No. 32 Blok A. Nomor 14-15 RT 004 RW 006 Pinangsia Tamansari, Jakarta Barat-DKI Jakarta', '01.560.371.5-032.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 10, 'PT. Mitra Buana Sejahtera', 0, '', 'JL. Kaliputih No. 15 RT 001 RW 005, Purwokerto wetan, Purwokerto Timur, Banyumas - Jawa Tengah', '02.625.631.3-521.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 30, 'Bendahara Puskesmas  Watubangga', 0, '', 'JL. Cendrawasih No. 17, Watubangga, Kolaka, Sulawesi Tenggara', '80.204.787.8-815.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 11, 'Bend. Biddokkes', 0, '', 'JL. Ahmad Yani 116, Sby', '00.343.745.6-609.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 24, 'PT. Sinar Medika Papua', 0, '', 'Jl. Ampibhi HM No. 664, RT 003 RW 009, Kel. Hamadi Kec. Jayapura Selatan, Kota Jayapura, Papua', '66.531.683.2-952.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 11, 'Pemegang Kas Dinas Kesehatan Kabupaten Blitar', 0, '', 'JL. Raya Kediri No. 18, Kel. Sanankulon, Kab. Blitar', '00.434.220.2-653.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 11, 'Bend. Pengeluaran RSD Dr. Haryoto', 0, '', 'JL. Basuki Rahmat No. 5, Tompokersan, Lumajang, Lumajang', '00.388.905.2-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 5, 'PT. Diva Sumber Berkat', 0, '', 'JL. Ahmad Zazuli No. 15, Kota Baru-Gondokusuman Yogyakarta', '31.811.842.9-541.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 19, 'PT. Mediska Kencana', 0, '', 'JL. WR. Soepratman SK 3/4 -20 Ambon', '01.528.473.0-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 11, 'Bendahara Puskesmas Wates', 0, '', 'JL. Raya Pare No. 74, Wates, Kel. Wates, Kec. Wates, Kab. Kediri, Jawa Timur', '20.011.075.7-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 11, 'Bendahara Puskesmas Kras', 0, '', 'JL. Satya Bhakti No. 222, Kel. Kras, Kec. Kras, Kab. Kediri-Jawa Timur', '20.011.071.6-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 11, 'Rutin Rumah Sakit Umum Wlingi', 0, '', 'JL. Dr. Sucipto, Wlingi, Wlingi, Kab. Blitar-86134', '00.034.633.8-653.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 23, 'Bend. Dinas Kesehatan Kab. Manggarai', 0, '', 'Ruteng, Lowir, Langke Rembong-Manggarai', '00.009.885.5-924.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 11, 'PT. Alkesmart Indonesia', 0, '', 'Ruko Taman Jenggala Mas RTB-9, JL. Sunandar PS RT 018 RW 004, Sidokare, Sidoarjo, Jawa Timur', '31.589.440.2-617.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 11, 'Bendahara Penetapan Rekening Dana Kapitasi JKN UPTD Puskesmas Perak', 0, '', 'JL. Raya Perak No. 109 RT 002 RW 002, Pagerwojo, Perak Jombang, Jawa Timur', '75.442.939.7-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 11, 'Bendahara Pengeluaran Dinas Kesehatan Kab. Lumajang', 0, '', 'JL. S. Parman No. 13 Rogotrunan Lumajang Kab. Lumajang Jawa timur', '00.166.477.0-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 11, 'Bendahara Pengeluaran RSUD Prof Dr. Soekandar', 0, '', 'JL. Hayam Wuruk No. 25 Kel. Mojosari, Kec. Mojosari, Kab. Mojokerto, Jawa Timur', '00.205.433.6-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 9, 'PT. Global Biomedilab', 0, '', 'JL. Bogor B9 No. 5 RT/RW 008/015 Karyamulya-Kesambi kota Cirebon -Jawa Barat 45135', '31.402.239.3-426.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 11, 'Bend. Dana Kapitasi JKN Gunungsari', 0, '', 'JL. Raya Babad-Bojonegoro Km 03 Kel. Gunungsari Kec. Baureno Bojonegoro-Jawa Timur', '71.285.326.6-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 11, 'Bend. Pemegang Kas Pembantu Puskesmas Munjungan', 0, '', 'Dsn. Munjungan RT 08 Rw 02 Munjungan, Munjungan, Munjungan, Kab. Trenggalek-Jawa Timur', '00.465.676.5-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 11, 'Bend. Kapitasi JKN Kedung Adem', 0, '', 'JL. Gajah Mada No. 1328, Kel. Kedungadem, Kec. Kedungadem Bojonegoro, Jawa Timur', '71.291.819.2-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 12, 'Kantor Dinas Kesehatan Kab. K. Hulu ', 0, '', 'JL. Diponegoro No. 9 Kel. Putussibau Kota, Kec. Putussibau Utara Kab. Kapuas Hulu', '00.125.152.9-706.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 12, 'PT. Sinarindo Multi Medika', 0, '', 'JL. Karna Sosial No. 8 C, RT 001 RW 011 Kel. Akcaya Kec. Pontianak Selatan Pontianak, Kalimantan Barat', '71.902.807.8-701.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 11, 'Bendahara Pengeluaran RSUD Bhakti Dharma Husada', 0, '', 'JL. Raya Kendung No. 110-117 Sememi, Benowo, Surabaya', '00.273.596.7-604.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 11, 'PT. Sentrum Dental Sentosa', 0, '', 'JL. Sumatera No. 129 Gubeng, Gubeng, Gubeng-Surabaya-Jawa Timur', '01.995.267.0-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 11, 'Bend. Pengeluaran Dinas Kesehatan Kota Malang', 0, '', 'JL. Simpang LA Sucipto 45 Pandanwangi / Blimbing Kota Madya Malang-Jawa Timur', '00.376.724.1-652.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Kalidawir', 0, '', 'JL. Melati No. 02 RT 001 RW 001, Kalidawir, Kalidawir, Kab. Tulungagung Jawa Timur', '73.137.178.7-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Bandung', 0, '', 'JL. Panglima Sudirman No. 18 Bandung, Bandung, Kab. Tulungagung, Jawa Timur', '30.137.142.3-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Tlogosari', 0, '', 'Raya Pakisan No. RT. RW. Kel. Pakisan, Kec. Tlogoasri, Bondowoso, Jawa Timur', '70.860.616.5-656.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Maesan', 0, '', 'Raya Pakisan No. RT. RW. Kel. Maesan Kec. Maesan Bondowoso Jawa Timur', '70.808.245.8-656.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 11, 'Bendahara BLUD Puskesmas Watulimo', 0, '', 'JL. Raya Pantai Prigi Prigi Watulimo Kab. Trenggalek Jawa Timur', '71.126.486.1-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 11, 'Rumah Sakit Umum Daerah Ploso', 0, '', 'JLn. Darmo Sugondo No. 83 RT 001 RW 001 Kelurahan Rejoagung Kecamatan Ploso, Jombang, Jawa Timur', '30.126.327.3-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 11, 'Bendahara Pengeluaran RSUD Pasirian ', 0, '', 'Jalan Raya Pasirian Pasirian, Pasirian Kab. Lumajang Jawa Timur', '76.815.082.3-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 11, 'Bendahara dan Penetapan Rekening Dana Kapitasi JKN UPTD Puskesmas Plumbon Gambang', 0, '', 'JL. Raya Plumbon Gambang No. 49 RT 002 RW 003 Plumbon Gambang, Gudo Kab. Jombang Jawa Timur', '75.513.658.7-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 10, 'PT. Evercoss Technology Indonesia', 0, '', 'JL. RE Martadinata No. 37 Tawangsari, Semarang Barat Kota Semarang, Jawa Tengah', '74.428.910.9-503.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 11, 'PT. Dakota Sarana Indah ', 0, '', 'Perumahan Citra Pesona Buduran E1 No. 05 RT 037 RW 007 Kel. Sindokepung Kec. Buduran Sidoarjo, Jawa Timur', '70.895.618.0-643.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 11, 'Bend. Kapitasi JKN Purwosari', 0, '', 'JL. Raya Ngambon No. 523 RT RW Kel. Purwosari Kec. Purwosari  Bojonegoro, Jawa Timur', '71.293.296.1-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 23, 'Bend. Pengeluaran Rumah Sakit Umum Daerah Umbu Rara Meha Waingapu', 0, '', 'JL. Adam Malik RT 032 / RW 006 Kambajawa Kota Waingapu, Sumba Timur 87117', '00.134.678.2-926000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 23, 'Rutin Dinas Kesehatan Kab. Nagekeo', 0, '', 'Danga RT 001 RW 001 Danga, Aesesa Nagekeo', '00.618.119.2-923.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 14, 'Bend. Puskesmas Swadana Menteng', 0, '', 'JL. Temanggung Tilung No. 59 Menteng, Menteng Jekan Raya Kota Palangkaraya, Kalimantan Tengah', '00.399.467.0-711.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 14, 'Bendahara Dinas Kesehatan Kab. Kota Waringin Barat', 0, '', 'Jalan Cilik Riwut II No. 210 Madurejo Arut Selatan Kota Waringin Barat Kalimantan Tengah 74112', '00.363.184.3-713.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 14, 'Bendahara Rumah Sakit Jiwa Kalawa Atei', 0, '', 'JL. Trans Palangkaraya -Kuala Kurun km 16 RT 004 Bukit Rawi, Kahayan Tengah Pulau Pisau Kalimantan Tengah', '74.073.718.4-711.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 11, 'Rumah Sakit Bantuan 05.08.05 Surabaya', 0, '', 'JL. Gubeng Pojok No. 21, Pacar Keling Pacar Keling Tambaksari kota Surabaya Jawa Timur', '02.607.309.8-619.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 11, 'Bend. Peng. Dinas Kesehatan Kab. Kediri', 0, '', 'JL. Pemenang No. 1 C Kel. Sukorejo Kec. Ngasem Kab. Kediri Jawa timur ', '00.629.755.0-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 6, 'PT. Fajar Surya Swadaya', 0, '', 'JL. Aipda Ks Tubun Raya No. 66 C RT 001 RW 001 Kel. Slipi Kec. Palmerah Jakarta Barat, DKI Jakarta Raya', '01.601.258.5-031.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 6, 'PT. Silva Rimba Lestari', 0, '', 'JL. Aipda Kstubun Raya No. 66 C RT 001 RW 001 Kel. Slipi Kec. Palmerah Jakarta Barat, DKI Jakarta Raya', '02.679.951.0-031.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 4, 'PT. Tiara Anugrah Lestari ', 0, '', 'JL. Kapuas Raya No. 17 Padang Harapan, Gading Cempaka Kota Bengkulu', '02.040.778.9-311.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 11, 'Bendahara JKN UPT Puskesmas Janti', 0, '', 'JL. Janti Barat No. 88 RT RW Kel. Sukun Kec. Sukun Kota Malang, Jawa Timur', '70.974.752.1-623.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 11, 'Bend. Rutin Dinas Kesehatan Kota Madiun', 0, '', 'JL. Trunojoyo No. 120 Nambangan Kidul-Mangun Harjo Madiun - 63128 ', '00.436.763.7-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 11, 'Dinas Pengendalian Pemberdayaa n Perempuan dan Perlindungan Anak Kota Surabaya', 0, '', 'JL. Kedungsari No. 18', '00.005.896.6-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 11, 'Bend. Pengeluaran Kantor Dinas Kesehatan Kab. Magetan ', 0, '', 'JL. Imam Bonjol No. 04 Magetan, Magetan, Jawa Timur', '00.034.846.6-646.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 11, 'Bend. JKN Puskesmas Pilangkenceng', 0, '', 'Raya Kenongorejo No. 774 B Kenongorejo Pilang Kenceng Madiun Jawa Timur ', '71.038.258.1-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 30, 'PT. Triafi Pratama Medika', 0, '', 'JL. Haluoleo Kom. BTN Wahana E No. 01 RT 000 RW 000 Kel. Mokoau Kec. Kambu Kota Kendari, Sulawesi Tenggara', '66.124.269.3-811.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 11, 'Bendahara Pengeluaran dan Penerimaan Puskesmas Curah Tulis', 0, '', 'Jalan Balai Desa Curah Tulis No. 182 Kab. Probolinggo', '72.778.622.0-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 11, 'Bend. Dana Penunjang Pendidikan Universitas Jember', 0, '', 'JL. Kalimantan No. 37 RT RW Sumbersari-Sumbersari Jember', '00.035.929.9-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 11, 'Bendahara Pengeluaran Puskesmas Besuk', 0, '', 'JL. Raya Besuk No. RT 005 RW 005 Kel. Besuk Agung Kec. Besuk Kab. Probolinggo, Jawa Timur', '72.878.527.0-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 11, 'Bend. Puskesmas Krejengan ', 0, '', 'JL. Raya Krejengan No. 82 RT 001 RW 001 Krejengan Krejengan Kab. Probolinggo', '20.042.217.8-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 10, 'PT. Triangle Motorindo', 0, '', 'Taman Industri BSB Blok AS No. 9 Jatibarang, Mijen, Semarang', '01.967.295.5-511.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 19, 'Bend. Kap. Jamkesmas Warujayeng', 0, '', 'JL. A. Yani No. 25 Kel. Warujayeng Kec. Tanjung Anom Kab. Nganjuk', '72.602.737.8-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 11, 'Bend. Puskesmas Panekan ', 0, '', 'Panekan, Panekan, Panekan Magetan', '00.324.576.8-646.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 11, 'Bend. Puskesmas Kalibaru Kulon', 0, '', 'JL. Jember No. 39, Kalibaru Kulon, Kalibaru Banyuwangi', '00.166.706.2-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 11, 'Bend. Puskesmas Mojopanggung', 0, '', 'JL. Brawijaya 21 Mojopanggung, Giri Banyuwangi', '00.219.921.4-527.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 11, 'Bend. Puskesmas Gitik', 0, '', 'JL. Diponegoro No. 24 RT 000 RW 000 Gitik, Rogojampi, Banyuwangi', '00.836.705.4-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 14, 'Bend. Rutin Dinas Kesehatan Kab. Seruyan', 0, '', 'JL. Jend. A Yani RT 008 RW 003 Kuala Pembuang Satu-Seruyan Hilir Kab. Seruyan - Kalimantan Tengah', '00.322.218.9-712.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 11, 'Bend. Pemegang Kas Dinas Kesehatan Kab. Loteng', 0, '', 'JL. Sukarno Hatta (KTR DNS Kesehatan) Praya-Praya Lombok Tengah', '00.334.056-9-915.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 11, 'Bend. Pengeluaran B.P.T.P.', 0, '', 'JL. Raya Karang Ploso Km. 4 Ngijo Karang Ploso, Kab. Malang', '00.152.586.4-657.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 10, 'PT. Glorya Medica Abadi', 0, '', 'JL. Pramuka RT. 001 RW 002 Sudagaran Banyumas, Banyumas, Jawa Tengah 53192', '74.587.956.9-521.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 11, 'Bendahara Pengeluaran Dinas Kesehatan Kab. Pacitan', 0, '', 'JL. Letjen Suprapto No. 42 Sidoharjo Pacitan Pacitan', '00.034.897.9-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 11, 'Bendahara JKN Puskesmas Tegalsiwalan', 0, '', 'JL. Raya Tegalsiwalan No. RT 000 RW 000 Kel. Tegalsiwalan Kec. Tegal Siwalan Kab. Probolinggo, Jawa Timur', '72.870.327.3-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 11, 'CV. Intantirta Jaya Medika', 0, '', 'Permata Wiyung Regency  Kav. 59 RT/RW 000/005 Wiyung, Wiyung Surabaya, Jawa Timur 60226', '31.354.856.2-618.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 9, 'PT. Zeva Valindo Jaya', 0, '', 'Ruko Harapan Indah Blok EC No. 11 RT 008 RW 017 Pejuang Medan Satria Kotamadya Bekasi', '01.917.805.2-407.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 6, 'PT. Marco Sekawan ', 0, '', 'JL. Agung Utara I Blok A2/33 RT 005 RW 008 Kel. Sunter Agung Kec. Tanjung Priok Jakarta Utara, DKI Jakarta Raya', '03.319.195.8-048.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 22, 'PT. Bintang Mandiri Medica', 0, '', 'JL. Diponegoro RT 012 Majidi Pancor, Selong, Lombok Timur', '75.432.140.4-915.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 6, 'Hendi Setyowati', 0, '', 'JL. Rekreasi No. RT 008 RW 004 Kel. Cilincing Kec. Ciclincing Jakarta Utara, DKI Jakarta', '70.434.673.3-045.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 22, 'Bend. Peng KTR Kesehatan Pelabuhan Kls II Mataram', 0, '', 'JL. Adi Sucipto No. 13 B Rembiga-Selaparang Kota Mataram', '00.370.896.3-911.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 6, 'Hexagon Metrology Asia Pacific PTE. LTD', 0, '', 'Kindo Square Unit C-2, JL. Raya Duren Tiga No. 101 Duren Tiga Pancoran,  Jakarta Selatan, DKI-Jakarta 12760', '03.215.133.4-053.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 2, 'YAY. Bhakti Wara', 0, '', 'JL. Sungai Selan km 4 Rangkui-Rangkui Pangkal Pinang', '01.329.098.5-034.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 28, 'PT. Siaga Medika Abadi Karya', 0, '', 'JL. Abdul Kadir No. 9-D RT 001 RW 008 Balang Baru-Tamalate Makassar', '31.708.360.8-804.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 22, 'RSUD Kabupaten Lombok Utara', 0, '', 'Raya Tanjung Bayan Tanjung Tanjung Kab. Lombok Utara Tenggara Barat', '00.919.071.1-915.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 14, 'Bendahara Kantor Kesehatan Pelabuhan Sampit', 0, '', 'JL. MT. Haryono No. 46, Mentawa Baru Hulu Mentawa Baru Ketapang Kab. Kotawaringin Timur - Kalimantan Tengah', '00.008.289.1.712.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 15, 'PT. Mulawarman Mitra Medika', 0, '', 'JL. M. Yamin No. 2 RT 027 Gunung Kelua, Samarinda Ulu Samarinda-Kaltim', '83.311.490.3-722.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 11, 'Bend. Pengeluaran Pembantu UPT Puskesmas Blooto', 0, '', 'JL. Raya Cindo No. 3 RT RW Kel. Blooto Kec. Prajurit Kulon Kota Mojokerto, Jatim', '71.993.151.1-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 22, 'Bend. Pengeluaran BPK RI Perwak. Prov. NTB', 0, '', 'JL. Udayana No. 22 Karang Baru-Selaparang Kota Mataram', '00.642.829.6-911.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 11, 'Bend. Pengeluaran Pembantu Puskesmas Gondang', 0, '', 'JL. Raya Gondang RT 0001 RW 0001 Gondang Tulungagung, Jawa Timur', '75.484.335.7-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 11, 'Bend. RSU Sumberejo', 0, '', 'JL. Raya Sumberrejo 0231 RT 006 RW 002 Sumuragung, Sumberrejo, Mojokerto', '00.641.305.8-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 30, 'PT. Sultratuna Samudra', 0, '', 'JL. Samudra Komp. Pelabuhan Perikanan Samudra Kendari No. 1 Puday Abeli, Kota Kendari Sulawesi Tenggara', '01.539.633.6-811.001', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 11, 'Bendahara Pengeluaran RSUD Kota Malang ', 0, '', 'JL. Rajoisa Bumiayu Kedung Kandang Kota Malang Jawa Timur', '72.099.225.4-623.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 22, 'Bend. RSUD Sumbawa Barat', 0, '', 'JLn. Undru No. 06 RT 04 RW 06 Kel. Kuang Taliwang-Sumbawa Barat', '00.857.607.6-913.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 11, 'Bend. Pengeluaran RSU Haji Surabaya', 0, '', 'JL. Manyar Kertoadi Klampis Ngasem-Sukolilo, Surabaya-Jawa Timur', '00.587.760.0-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 22, 'Bend. Rutin Dinkes Kodya Mataram', 0, '', 'JL. Sultan Hasanudin No. 34 Cakranegara Timur-Cakranegara Kota Mataram', '00.183.956.2-914.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 19, 'Rumah Sakit Umum Ambon', 0, '', 'JL. DR Kayade, Kudamati, Kudamati Nusaniwe, Ambon Maluku', '00.013.803.2-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 11, 'BND. RSUD RA Basoeni Kab. Mojokerto', 0, '', 'JL. Raya Gedeg No. 17 Gedek, Gedek, Kab. Mojokerto', '20.018.829.0-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 11, 'Pemegang Kas Dinas Kesehatan Kab. Blitar', 0, '', 'Raya Kediri 18, Sanan Kulon Sanan Kulon, Sanan kulon, Kab. Blitar, Jawa Timur', '00.434.220.0-653.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 11, 'Bend. RSUD Ngimbang', 0, '', 'JL. Mayangkara No. 227 RT 000 RW 000 Sendangrejo - Ngimbang  Lamongan', '30.081.743.4-645.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 11, 'Bend. Puskesmas Wongsorejo', 0, '', 'JL. Raya Situbondo No. 04 Wonorejo, Wongsorejo Banyuwangi', '00.219.916.4-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 11, 'Bend. Kapitasi JKN Sukosewu', 0, '', 'JL. Raya Klepek No. RT. 000 RW. 000, Kel. Klepek Kec. Sukosewu, Bojonegoro, Jawa Timur', '71.285.648.3-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 11, 'Bendahara BLUD Puskesmas Slawe', 0, '', 'JL. Raya Prigi RT. 010 RW. 010, Slawe Watulimo, Kab. Trenggalek Jawa Timur', '20.001.767.1-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 11, 'Bend. Dana Kapitasi JKN Mejuwet', 0, '', 'JL. Dr. Sutomo No. 206 RT 001 RW 001 Kel. Mejuwet Kec. Sumberejo, Bojonegoro, Jawa Timur', '71.298.264.4-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 11, 'Bend. Kapitasi JKN Kasiman', 0, '', 'JL. Ronggolawe No. 03 RT RW, Kel. Batokan Kec. Kasiman, Bojonegoro, Jawa Timur', '71.293.446.2-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 11, 'Bendahara Umum BLUD Puskesmas Dongko Kab. Trenggalek', 0, '', 'JL. Raya Dongko Panggul RT. 069 RW. 004 Dongko, Dongko Trenggalek Jawa Timur', '71.598.847.3-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 11, 'Bendahara Kapitasi Jaminan Kesehatan Nasional UPTD Puskesmas Sumobito', 0, '', 'Jalan Raya Sumobito No. 568 Sumobito Jombang Jawa Timur', '75.463.131.5-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 11, 'Pemegang Kas RSD Kab. Madiun', 0, '', 'JL. A. Yani Km. 5 Caruban, Bangunsari, Mejayan, Madiun', '00.192.878.7-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 12, 'Bendahara Dinas Kesehatan Sanggau', 0, '', 'JL. Nenas No. 24 Tanjung Sekayan Kapuas Kab. Sanggau Kalimantan Barat', '00.032.821.1-705.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 11, 'Bend. Puskesmas Maron', 0, '', 'JL. Asmali 604 RT 010 RW 002 Maron Wetan Maron Kab. Probolinggo', '30.081.655.0-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 11, 'Bendahara Umum BLUD Puskesmas Tugu', 0, '', 'JL. Raya Ponorogo KM. 07 RT. 000 RW. 000 Gondang, Tugu Kab. Trenggalek Jawa Timur', '71.547.872.3-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 11, 'Bend. Pengeluaran Dinas Pendidikan Kota Malang', 0, '', 'JL. Veteran No. 19 Sumbersari Lowok Waru Kotamadya Malang', '00.376.713.4-652.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 23, 'PT. Mahkota Anugrah Karya', 0, '', 'JL. Bogarpung (Komp. Perum. Puri Kimbul Permai No. 14) RT. 007 RW. 001, Kel. Wolomarang Kec. Alok Barat Sikka Maumere - Flores - NTT', '01.768.340.0-921.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 11, 'Bend. Pengeluaran RSU DR. Soetomo', 0, '', 'JL. Mayjen Prof. DR. Moestopo 6-8 Airlangga - Gubeng Surabaya - Jawa Timur', '00.281.411.9-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 22, 'Bendahara Pengeluaran Rumah Sakit H. L. Manambai Abdul Kadir ', 0, '', 'Lintas Sumbawa - Bima KM. 5 Brang Bawa Sumbawa Sumbawa Nusa Tenggara Barat', '00.644.429.3-391.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 11, 'Bend. Rutin RSUD DR. Harjono Ponorogo', 0, '', 'JL. Raya Ponorogo Pacitan Pakunden  Ponorogo, Ponorogo', '00.035.073.6-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 3, 'PT. Pradana Sirona Persada', 0, '', 'JL. Gatot Subroto Km 7, Jateng, Jatiuwung, KOTA TANGERANG BANTEN', '80.447.610.9-402.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 11, 'Bend. Puskesmas Kabat', 0, '', 'JL. Raya Kabat No. 08 RT 00 RW 00 Dadapan, Kabat Banyuwangi', '00.386.707.0-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 11, 'Bend. Puskesmas Songgon', 0, '', 'JL. A. Yani No 65, RT 001 RW 002 Sumberbulu, Songgon Banyuwangi', '00.836.750.0-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 11, 'Bend. Pengeluaran RSUD Asembagus', 0, '', 'JL. Raya Banyuwangi - Asembagus No. RT. RW. Kel. Wringin Kec. Asembagus Situbondo - Jawa Timur  ', '66.778.036.5-656.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 11, 'PT. Tirta Jaya Berdikari', 0, '', 'Kom. PIK Blok B No. 70 RT. 004 RW 010 Kel. Penggilingan Kec. Cakung Kab Jaktim ', '02.998.708.8-004.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 19, 'Bagian Proyek OPRS Rumah Sakit Umum Tulehu', 0, '', 'Desa Tulehu Kel. Tulehu Kec. Salahutu Maluku Tengah', '00.229.439.5-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 14, 'RSUD DR H Soemarno Sosroatmodjo Kuala Kapuas', 0, '', 'Jl. Tambun Bunga No. 16  Rt.000 RW.000 Selat Tengah, Selat Kapuas, Kalimantan Tengah', '00.126.118.9-711.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Arjasa', 0, '', 'Jl. Diponegoro No. 1 15 RT.004 RW.001, Kel. Candijati, Kec. Arjasa, Jember, Jawa Timur', '70.906.431.5-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, 18, 'PT. Saburai Perdana Barokah', 0, '', 'Jl. Purnawirawan GG. Swadaya 7 Lorong Ceria No. 100 RT.002, Gunung terang, Tanjungkarang Barat, Bandar Lampung, Lampung', '02.523.298-4.322.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, 1, 'PT. Surya bali Makmur', 0, '', 'Jl. Diponegoro No. 135-137 Blok B/24 Daun Puri Klod, Denpasar Barat, Bali', '02.252.265.0-904.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, 1, 'Bend. Dinkes Kabupaten Jembrana', 0, '', 'Jl. Surapati No. 1 Dauh Waru, Jembrana, Jembrana, Bali', '00.008.904.5-908.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, 11, 'RSUD Dokter Moh. Saleh Kota Probolinggo', 0, '', 'Jl. Mayjend Panjaitan No. 65 Kel. Sukabumi Kec. Mayangan Kota Probolinggo-67219', '00.166.637-9.625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, 19, 'Dinas Kesehatan Kab Seram Bagian Timur', 0, '', 'Jl. Wailola Bula, Seram Bagian Timur, Maluku', '00.479.133.1-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 20, 'Bendaharawan Rutin RSU Tobelo', 0, '', 'Jl. Lanbouw Desa Gamsungi, Kec. Tobelo, Kab. Halmahera Utara, Maluku Utara', '00.431.205.4-943.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, 15, 'PT. Multi Sarana Alkesindo', 0, '', 'Jl. KH. Harun Nafsi GG. Karya Bersama Blok A No. 7 RT.16 Rapak Dalam, LOA Janan Ilir, Samarinda, kalimantan Timur', '03.034.334.7-722.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, 20, 'Bendahara DinasKesehatan Halmahera Selatan', 0, '', 'Mandawong, Mandaong, Bacan Selatan, Kab. Halmahera Selatan', '00.644.852.6-942.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, 22, 'Pemegang Kas Kantor Dinas Kesehatan', 0, '', 'Jl. Kesehatan 02, Kel. Pentaoi, Kec. Mpunda, Kota Bima, Nusa Tenggara Barat', '00.133.479.6-912.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, 11, 'Bend. JKN Puskesmas Tembokrejo', 0, '', 'Jl. PB. Sudirman No. 44 RT. 001 RW.003, Kel. Tembokrejo, Kec. Gumukmas, Jember, Jawa Timur', '70.906.286.3-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Rowotengah', 0, '', 'Jl. Gajah Mada No. 77, Kel. Sumber Agung, Kec. Sumber Baru, Jember, Jawa Timur', '70.900.313.1-926.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, 6, 'PT. EMIINDO Jaya Bersama', 0, '', 'Komplek Perkantoran Pulomas Jalan Perintis Kemerdekaan 10 No. 8, pulo Gadung, Jakarta Timur, DKI Jakarta', '84.607.176.9-003.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, 1, 'BEND.PENGELUARAN DNS.KESEHATAN KAB.BANGLI (161)', 0, '', 'JL. BRIGJEN NGURAH RAI (DINAS KESEHATAN), KAWAN KAWAN BANGLI BANGLI BALI', '00.295.329.7-907.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, 11, 'BEND. PUSKESMAS TEGALDLIMO', 0, '', 'JL. KOPTU RUSWANDI TEGALDLIMO, TEGALDLIMO BANYUWANGI', '00.836.704.7-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, 12, 'PT DWICENTRA CAHAYA WIGUNA', 0, '', 'JL. PERDANA KOMP RUKAN PERDANA SQUARE B NO 19, PONTIANAK SELATAN, PONTIANAK, KALIMANTAN BARAT', '02.373.576.4-701.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, 11, 'BEND. RUTIN PUSK SAWOO', 0, '', 'JL. RAYA SAWOO RT.03 RW.05, SAWOO SAWOO SAWOO KAB. PONOROGO JAWA TIMUR', '00.324.703.8-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, 11, 'BENDAHARAWAN PUSKESMAS PAGU', 0, '', 'JL. SUPRIADI RT.00 RW.00, KEL. PAGU KEC.PAGU KAB. KEDIRI JAWA TIMUR', '00.744.988.7-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, 11, 'BENDAHARA PENGELUARAN RUMAH SAKIT KUSTA SUMBERGLAGAH', 0, '', 'DSN. SUMBERGLAGAH, TANJUNGKENONGO PACET KAB. MOJOKERTO', '00.401.939.4-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, 19, 'BEND. Rumah Sakit Umum Daerah (RSUD) Kab. MTB', 0, '', 'Jl. IR Soekarno Saumlaki-Tanimbar Selatan- MTB', '00.904.236.7-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, 11, 'BEND. PENGELUARAN DINAS KESEHATAN KAB. TUBAN', 0, '', 'JL. BRAWIJAYA NO. 3 KEBONSARI, TUBAN, TUBAN, JAWA TIMUR', '00.035.620.4-648.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, 30, 'BEND. RUTIN DINAS KESEHATAN KOTA PALU', 0, '', 'JL. BALAIKOTA SELATAN BLOK C NO. 1 TANAMODINDI-PALU SELATAN PALU-SULAWESI TENGAH', '00.412.806.2-831.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, 29, 'BEND. RUTIN PADA RSU POSO', 0, '', 'JL. JENDRAL SUDIRMAN NO. 33 KEL. KASINTUWU, KEC. POSO KOTA UTARA POSO 94611', '00.139.096.2-833.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, 20, 'Bend. Politeknik Kesehatan Ternate', 0, '', 'Jl. Tanah Tinggi Kel. Maliaro, Kec. Ternate Selatan, Ternate', '00.136.195.5-942.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Patrang', 0, '', 'Jl. Kaca Piring No. 05 RT. 004 RW. 001, Kel. Gebang, Kec. Patrang, Jember, Jawa Timur', '70.918.681.1-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, 11, 'Bendahara Umum BLUD Puskesmas Karangan', 0, '', 'Jl. Raya Karangan RT. 005 RW. 002, Karangan, Karangan, Trenggalek, Jawa Timur', '71.463.010.0-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, 13, 'Dinas Kesehatan ', 0, '', 'Jl. Yetro Sinseng No. 23 Lanjas, Teweh Tengah, Barito Utara', '00.470.675.0-714.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, 14, 'Pemegang Kas Dinkes Kab. Sukamara', 0, '', 'Jl. Tjilik Riwut KM. 7.5 RT.14 RW.04 Natai Sedawak, Sukamara, Sukamara, Kalimanatan Tengah', '00.363.117.3-713.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, 11, 'Bendahara RS Bhayangkara TK III Bondowoso', 0, '', 'Jl. Jenpol Sucipto Judodiharjo 12 Kel. Blindungan, Kec. Bondowoso, Kab. Bodowoso, Jawa Timur', '00.671.567.6-656.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, 11, 'Bendahara Pengeluaran dan Penerimaan Puskesmas Banyuanyar', 0, '', 'Jl. Raya Banyuanyar No. 14 Kel. Liprak, Kec. Banyuanyar, Kab. Probolinggo, Jawa Timur', '72.816.817.0-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, 11, 'Bend. Puskesmas Kraksaan', 0, '', 'Jl. Raya Panglima Sudirman Patokan, Kraksaan, Kab. Probolinggo', '30.051.811.5-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, 11, 'Pemegang Kas Dinas Kesehatan Pemkab Bojonegoro', 0, '', 'Jl. Panglima Sudirman No. 30 Kel. Kepatihan, Kec. Bojonegoro, Kab. Bojonegoro, Jawa Timur', '00.307.759.1-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, 11, 'Rumkit Bhayangkara Polda Jatim Di Surabaya', 0, '', 'Jl. Akhmad Yani Kel. Wonocolo, Kec. Wonocolo, Kab. Surabaya, Jawa Timur', '00.252.302.5-609.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, 11, 'Bend. Satker Bid Kedokteran & Kesehatan Polda Jatim', 0, '', 'Jl. Akhmad Yani 116 Kel. Wonocolo, Kec. Wonocolo, Kab. Surabaya, Jawa Timur', '00.343.745.6-609.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, 11, 'PT. Rizky Putra Kasih', 0, '', 'Perum Singhasari Residence A12 No. 25 RT. 01 RW. 09, Kel. Purwoasri, Kec. Singosari, Malang, Jawa Timur', '73.254.798.9-657.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, 14, 'PT. Fresh Water Hillyuda', 0, '', 'Jl. Delima No. 37 RT. 08, Madurejo, Arut Selatan, Kota Waringin Barat, Kalimantan Tengah', '02.225.153.2-713.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, 11, 'Bend. PPK Puskesmas Gempol', 0, '', 'Jl. Raya Bandulan, Kejapanan, Gempol, Pasuruan', '00.503.571.2-624.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, 22, 'Politeknik Kesehatan Mataram', 0, '', 'Jl. Praburangkasari Dasan Cermen Sandubaya, Kota Mataram, Nusa Tenggara Barat', '00.009.241.1-911.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Jelbuk', 0, '', 'Jl. RA. Kartini No. 26 Kel. Jelbuk, Kec. Jelbuk, Jember, Jawa Timur', '70.903.849.1-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, 11, 'PUMC Puskesmas Panarukan', 0, '', 'Jl. Wringinanom No. 29, Wringinanom, Kel. Wringinanom, Kec. Panarukan, Situbondo, Jawa Timur', '00.151.332.4-656.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, 11, 'Bend. Pengeluaran Politeknik Negeri Banyuwangi', 0, '', 'Jl. Raya Jember KM. 13, Labanasem Kabat, Banyuwangi, Jawa Timur', '30.150.228.2-627.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, 11, 'Bendahara Pengeluaran Pembantu UPTD Puskesmas Ngantru', 0, '', 'Jl. Raya Ngantru RT. 005 RW. 001, Ngantru, Ngantru, Kab. Tulungagung, Jawa Timur', '30.080.097.6-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, 11, 'Bend. Pembantu Pengeluaran Puskesmas Pandaan', 0, '', 'Jl. Raya No. 11 Patungasri, Pandaan, Pasuruan', '00.503.554.8-624.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, 15, 'PT. Sumber Rejeki Medika Jaya', 0, '', 'JL. Adam Malik No. 33 RT 004, Kel. Karang Asam Ulu, Kec. Sungai Kunjang Kota Samarinda, Kalimantan Timur', '03.210.594.2-722.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, 15, 'PT. Murindo Multi Sarana', 0, '', 'Jl. AW Syahrani Ruko Pondok Alam Indah No. 5 RT. 26, Sempaja Selatan, Samarinda Utara, Samarinda', '03.034.116.8-722.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, 11, 'Kapitasi JKN Puskesmas Sidayu', 0, '', 'Jl. Raya Sidayu Kel. Ngawen, Kec. Sidayu, Gresik, Jawa Timur', '71.509.684.8-612.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `customer` (`id`, `id_provinsi`, `nama`, `telp`, `email`, `alamat`, `npwp`, `ket`, `created_at`, `updated_at`) VALUES
(247, 11, 'Rumah Sakit Umum Mohammad Noer Pamekasan', 0, '', 'Jl. Bonorogo No. 17 Barurambat Timur, Pademawu, Kab. Pamekasan, Jawa Timur', '00.172.311.3-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(248, 11, 'Bendahara Dana Kapitasi JKN Puskesmas Andongsari', 0, '', 'Jl. Kotta Blater No. 12 Andongsari, Ambulu, Kab. Jember, Jawa Timur', '70.883.693.7-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(249, 11, 'Bendahara Dana kapitasi Puskesmas Kaliwates', 0, '', 'Jl. Jend. Basuki Rahmat No. 199, Kel. Tegal Besar, Kec. Kaliwates, Jember, Jawa Timur', '70.907.154.2-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(250, 11, 'Pengeluaran dan Penerimaan Puskesmas Sukapura', 0, '', 'Jl. Raya bromo RT. 005, RW. 001, Kel. Sukapura, Kec. Sukapura, Kab. Probolinggo, Jawa Timur', '72.773.062.4-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(251, 11, 'Bend Kapitasi Jkn Temayang', 0, '', 'Jl. Basuki Rahmad No.308 Rt.000 Rw.000 Kel.Temayang Kec.Temayang Bojonegoro, Jawa Timur', '71.291.643.6-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(252, 11, 'Bend Puskesmas Ambal-Ambil', 0, '', 'Jl Raya Kabupaten Rt.12 Rw.24 Wrati Kejayan Pasuruan', '00.953.286.2-624.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(253, 11, 'Bend Rutin Dinas Kesehatan Kota Madiun', 0, '', 'Jl Trunojoyo No.120 Mambangan Kidul Mangun Harjo Madiun 63128', '00.436.763.7-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(254, 15, 'Pt. Murindo Multi Sarana', 0, '', 'Jl Aw Syahrani Ruko Pondok Alam Indah No.5 Rt.26 Sempaja Selatan Samarinda Utara ', '03.034.116.8-722.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(255, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Kasiyan', 0, '', 'Jl Raya Simpang Tiga No. Rt. Rw. Kel.Kasiyan Kec.Puger Jember Jawa Timur', '70.858.498.2-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(256, 10, 'Pt. Glorya Medica Abadi', 0, '', 'Jl Pramuka Rt.001 Rw.002 Sudagaran Banyumas Jawa Tengah 53192', '74.587.956.9-521.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(257, 9, 'Pt. Sam Jaya Perkasa', 0, '', 'Jl. Pajajaran No.123 A Rt.06 Rw.02 Kel.Arjuna Kec.Cicendo Kota Bandung Jawa Barat', '70.895.848.3-428.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(258, 11, 'Bendahara Dan Kapitasi Jkn Puskesmas Panti ', 0, '', 'Jl Pb Sudirman No.85 Rt.003 Rw.005 Kel.Panti Kec.Panti Jember Jawa Timur', '71.030.308.2-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(259, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Kanigaran', 0, '', 'Jl. Cokroaminoto No.29 Rt.001 Rw.002 Kel.Kanigaran Kec.Kanigaran Kota Probolinggo Jawa Timur', '70.574.659.2-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(260, 11, 'Bendahara Pengeluaran Dan Penerimaan Puskesmas Bantaran', 0, '', 'Jl Raya Bantaran No.43 Rt. Rw. Kel.Bantaran Kec.Bantaran Kab. Probolinggo Jawa Timur', '72.816.640.6-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(261, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Wuluhan', 0, '', 'Jl Kartini No.4 Rt. Rw. Kel.Dukuh Dempok Kec.Wuluhan Jember Jawa Timur', '70.851.763.6-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(262, 11, 'Bend Dana Kapitasi Jkn Balen', 0, '', 'Jl Raya Balen No.50 Rt.000 Rw.000 Kel.Balenrejo Kec.Balen Bojonegoro Jawa Timur', '71.285.573.3-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(263, 11, 'Bendahara Pengeluaran Dan Penerimaan Puskesmas Sumber', 0, '', 'Jl Raya Pandansari Pojok No.137 Rt. Rw. Kel.Pandansari Kec.Sumber Kab.Probolinggo Jawa Timur', '72.897.456.9-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(264, 11, 'Bend Puskesmas Condong', 0, '', 'Jl Raya Condong Gading Kab.Probolinggo', '30.044.028.6-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(265, 11, 'Bendahara Pengeluaran Dan Penerimaan Puskesmas Jorongan', 0, '', 'Jl Raya Lumajang No. Rt. Rw. Kel.Jorongan Kec.Leces Kab.Probolinggo Jawa Timur', '72.862.700.1-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(266, 11, 'Bendahara Dana Blud Puskesmas Pule', 0, '', 'Jl Raya Pule Trenggalek Rt.001 Rw.001 Pule, Pule Trenggalek Jawa Timur ', '72.039.140.8-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(267, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Pakusari', 0, '', 'Jl Pb Sudirman No.87 No. Rt. Rw. Kel. Pakusari Kec.Pakusari Jember Jawa Timur', '70.892.760.3-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(268, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Bangsalsari', 0, '', 'Jl A. Yani No. 3 Rt. Rw. Kel.Bangsalsari Jember Jawa Timur', '70.904.018.2-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(269, 11, 'Bendahara Dana Kapitasi Jkn Fktp Puskesmas Rengel', 0, '', 'Jl Raya Timur No.447 Rt.003 Rw.003 Kel.Sumberejo Kec.Rengel Tuban Jawa Timur', '70.675.615.2-648.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(270, 11, 'Bendahara Dana Kapitasi Jkn Fktp Puskesmas Merakurak ', 0, '', 'Jl Pemuda No.42 Rt.006 Rw.001 Kel.Mandirejo Kec.Merakurak Tuban Jawa Timur', '70.665.866.3-648.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(271, 11, 'Bendahara Dana Kapitasi Jkn Fktp Puskesmas Prambon Tergayang', 0, '', 'Jl Raya Prambon Tergayang No.637 Rt.0 Rw.0 Kel.Prambon Tergayang Kec.Soko Tuban Jawa Timur', '70.675.482.7-648.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(272, 11, 'Bend Kapitasi Jkn Kesongo', 0, '', 'Jl Raya Kesongo No.395 Rt. Rw. Kel.Kesongo Kec.Kedungadem Bojonegoro Jawa Timur', '71.307.613.1-601.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(273, 11, 'Pemegang Kas Dinas Kesehatan Kab Blitar', 0, '', 'Raya Kediri 18, Sanan Kulon Sanan Kulon, Sanan Kulon, Kab. Blitar, Jawa Timur', '00.434.220.0-653.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(274, 11, 'Bend Puskesmas Wonomerto', 0, '', 'Jl. Bantaran Dsn Krajan No.853 Patalan Wonomerto Kab. Probolinggo', '20.041.126.2-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(275, 11, 'Bendahara Puskesmas Kanigoro', 0, '', 'Jl Kusuma Bangsa No.001 Rt. Rw. Kel. Kanigoro Kec. Kanigoro Blitar Jawa Timur', '71.348.504.3-653.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(276, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Umbulsari', 0, '', 'Jl. Kh Agus Salim No.052 Rt. Rw. Kel.Umbul Sari Kec.Umbulsari Jember Jawa Timur', '70.899.746.5-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(277, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Ledokombo', 0, '', 'Jl Cumedak No.124 Rt. Rw. Kel.Sumber Lesung Kec.Ledok Ombo Jember Jawa Timur', '70.881.634.3-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(278, 11, 'Bendahara Puskesmas Wonodadi', 0, '', 'Jl Raya Wonodadi No.4 Rt. Rw. Kel.Wonodadi Kec.Wonodadi Blitar Jawa Timur', '71.348.251.1-653.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(279, 11, 'Bendahara Dana Kapitasi Jaminan Kesehatan Nasional Upt. Kecematan Raas', 0, '', 'Jl. Pelabuhan Panggung No.09 Rt. Rw. Kel.Bbkas Kec.Baas Sumenep Jawa Timur', '72.897.917.0-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(280, 11, 'Pemegang Kas Dinas Kesehatan Kab ', 0, '', 'Jalan Trunojoyo No.147 Rt.002 Rw.002 Kauman Ponorogo Ponorogo', '00.324.697.2-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(281, 11, 'Bendahara Pengeluaran Rsud Dr.Soedomo Trenggalek', 0, '', 'Jl. Dr.Sutomo No.2 Trenggalek Rt.000 Rw.000 Kelurahan Tamanan Kecamatan Trenggalek Trenggalek Jawa Timur', '30.049.851.6-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(282, 11, 'Bendahara Upt Puskesmas Lenteng', 0, '', 'Jl Raya Lenteng  No. Rt.003 Rw.004 Kel.Lenteng Timur Kec.Lenteng Sumenep Jawa Timur', '71.862.339.0-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(283, 11, 'Bend Dana Kap. Jkn Puskesmas Sapeken', 0, '', 'Jl Raya Sapeken No.49 Sapeken Sumenep', '76.302.222.5.608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(284, 11, 'Bendahara Dana Kapitasi Jaminan Kesehatan Nasional Upt Puskesmas Bluto', 0, '', 'Jl Raya Bluto No.13 Rt. Rw. Kel.Bluto Kec.Bluto Sumenep Jawa Timur', '72.947.349.6-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(285, 11, 'Bendahara Dana Kapitasi Jkn Upt Puskesmas Ganding', 0, '', 'Jl Raya Guluk-Guluk No.8 Rt. Rw. Kel.Ketawang Larangan Kec.Ganding Sumenep Jawa Timur', '72.606.035.3-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(286, 11, 'Bendahara Rutin Dana Kapitasi Jkn Upt Puskesmas Batuputih', 0, '', 'Jl Arya Wiraraja Batu Putih Laok Batuputih Kab.Sumenep Jawa Timur', '75.864.991.7-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(287, 11, 'Bendahara Dana Kapitasi Jaminan Kesehatan Nasional Upt Puskesmas Saronggi', 0, '', 'Jl Raya Saronggi No. Rt. Rw Kel.Saronggi Kec.Saronggi Sumenep Jawa Timur', '72.665.257.1-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(288, 11, 'Bendahara Dana Kapitasi Jkn Fktp Ramolokan', 0, '', 'Jl. Kh Agus Salim No.25 Rt. Rw. Kel.Pamolokan Kec.Kota Sumenep Sumenep Jawa Timur', '72.677.154.6-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(289, 11, 'Bendahara Dana Kapitasi Jkn Fktp Utp Puskesmas Ambunten', 0, '', 'Jl Raya Ambunten No. Rt. Rw. Kel.Ambunten Timur Kec.  Ambunten Sumenep Jawa Timur', '73.111.727.1-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(290, 11, 'Bendahara Dana Kapitasi Jaminan Kesehatan Nasional Puskesmas', 0, '', 'Jl Raya Guluk-Guluk No. Rt.003 Rw.004 Kel.Guluk-Guluk Kec.Guluk-Guluk Sumenep Jawa Timur', '72.876.129.7-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(291, 11, 'Puskesmas Wonoayu', 0, '', 'Raya Wonoayu Rt.000 Rw.000 Jimbarankulon Jimbarankulon Wonoayu Sidoarjo Jawa Timur', '00.841.091.2-603.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(292, 11, 'Bend Pengeluaran Pembantu Puskesmas Sidoarjo', 0, '', 'Jl Dr Soetomo No.14 Rt. Rw. Magersari Sidoarjo Sidoarjo', '00.560.822.9-617.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(293, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Lojejer', 0, '', 'Jl Teuku Umar No.2 Lojejer Wuluhan Kab. Jember Jawa Timur', '70.891.617.6-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(294, 11, 'Bendahara Pengeluaran Blud Puskesmas Wungu', 0, '', 'Jl Raya Kare No.113 Wungu Wungu Kab.Madiun Jawa Timur', '70.793.186.1-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(295, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Ambulu', 0, '', 'Jl A. Yani No.60 Ambulu Ambulu Kab. Jember Jawa Timur', '70.931.183.1-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(296, 11, 'Bendahara Jkn Puskesmas Banjarsengon', 0, '', 'Jl Kasuari No.48 Rt.001 Rw.001 Banjar Sengon Patrang Kab.Jember Jawa Timur', '76.786.411.9-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(297, 11, 'Bendahara Dana Kapitasi Jkn Upt Puskesmas Arjasa', 0, '', 'Jl Raya Arjasa No. Rt. Rw. Kel.Arjasa Kec.Arjasa Sumenep Jawa Timur 69491', '73.142.215.0-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(298, 11, 'Bendahara Dana Kapitasi Jaminan Kesehatan Nasional Puskesmas', 0, '', 'Jl Raya Pragaan No. Rt Rw. Kel.Pragaanlaok Kec.Pragaan Sumenep Jawa Timur', '72.876.522.3-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(299, 11, 'Bendahara Dana Kapitasi Jaminan Kesehatan Nasional Upt Puskesmas Batang-Batang', 0, '', 'Jl Cemara Udang No. Rt. Rw Kel. Batangbatang Daya Kec.Batang Batang Sumenep Jawa Timur', '72.908.586.0-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(300, 11, 'Bendahara Dana Kapitasi Jkn Upt Puskesmas Rubaru', 0, '', 'Jl Raya Rubaru Rubaru Rubaru Sumenep Jawa Timur', '73.371.288.9-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(301, 11, 'Bendahara Dana Kapitasi Jaminan Kesehatan Nasional Upt Puskesmas Moncek', 0, '', 'Jl Raya Guluk Guluk No. Rt. Rw. Kel. Moncek Tengah Kec. Lenteng Sumenep Jawa Timur', '72.684.926.8-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(302, 11, 'Bend Jkn Puskesmas Tegalombo', 0, '', 'Jln Pacitan Ponorogo Km 34 No. Rt.013 Rw.003 Kel.Tegalombo Kec. Tegalombo Pacitan Jawa Timur', '70.691.827.3-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(303, 11, 'Bend Puskesmas Prambon', 0, '', 'Raya Prambon Rt.000 Rw.000 Prambon Prambon Sidoarjo', '00.841.093.8-603.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(304, 11, 'Bend Pumk Puskesmas Kec.Karangrejo', 0, '', 'Jl Raya Ngawi No.42 Karangrejo Karangrejo Magetan', '00.324.551.1-646.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(305, 11, 'Bendahara Dana Kapitasi Jkn Puskesmas Puger', 0, '', 'Jl A. Yani No.32 Rt. Rw. Kel.Puger Kulon Kec.Puger Jember Jawa Timur', '70.892.753.8-626.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(306, 11, 'Bend Jkn Puskesmas Tulakan', 0, '', 'Dsn Krajan No. Rt.004 Rw.001 Ds Bungur Kec.Tulakan Pacitan Jawa Timur', '70.614.392.2-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(307, 11, 'Bend Pemb Puskesmas Pucanglaban', 0, '', 'Sarangangin 01 Rt.000 Rw.000 Sumberdadap Pucang Laban Tulungagung', '30.050.340.6-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(308, 11, 'Pt Karsa Semangat Sejahtera', 0, '', 'Jl Kendalsari Selatan No.82 Rt.001 Rw.003 Penjaringan Sari Rungkut Surabaya 60297', '03.238.291.3-615.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(309, 10, 'Pt Makmur Technology Indonesia', 0, '', 'Kawasan Taman Industri Bsb Blok A.5 No.09 Jati Barang Mijen Kota Semarang Jawa Tengah', '80.472.256.9-503.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(310, 11, 'Pt. Bumi Menara Internusa', 0, '', 'Jl Margomulyo No.4E Tandes Lor - Tandes Surabaya Jawa Timur', '01.454.019.9-631.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(311, 14, 'Pt. Fresh Water Hillyuda', 0, '', 'Jalan Delima No.37 Rt.06 Madurejo Arut Selatan Kotawaringin Barat Kalimantan Tengah', '02.225.153.2-713.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(312, 11, 'Pt. Rejeki Wira Bersama', 0, '', 'Manyar Kertoarjo No.56 Rt.001 Rw.006 Manyar Sabrangan Mulyorejo Kota Surabaya Jawa Timur', '75.770.214.7-619.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(313, 11, 'Siti Nuraini Rinangsih', 0, '', 'Jl. Gumelar Jogaran Dusun Wetan Kali Rt.004/0013 Desa.Balung Lor', '36.091.068.0-196.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(314, 14, 'Yosi Faisal', 0, '', 'Jl. Kecubung  Iv No 08 Rt.002 Rw.001, Bukit Tunggal - Jekan Raya, Palangkaraya, Kalimantan Tengah', '64.135.154.9-711.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(315, 11, 'Pt. Sam Elemen Indonesia', 0, '', 'Ruko 21 Klampis D-8, Klampis Ngasem, Sukolilo, Surabaya, Jawa Timur', '54.528.045.2-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(316, 11, 'Pt. Bumi Menara Internusa (Cabang Malang)', 0, '', 'Jl. Pahlawan No.1 Dampit-Dampit Malang-Jawa Timur', '01.454.019.9-654.002', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(317, 12, 'PT Kapuas Permata Medifarma', 0, '', 'Jl. Budi Karya Ruko C5 Rt 004 Rw 023, Pontianak, Kalimantan Barat', '03.352.338.2-701.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(318, 11, 'Rutine Kantor Kesehatan Pelabuhan Probolinggo', 0, '', 'Jl. Tanjung Tembaga Baru', '00.036.522.1-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(319, 11, 'Joenry Panggawean.Dr', 0, '', 'Raya Darmo Permai Selatan 109-111 Rt 003 Rw 011, Lontar-Sambikerep, Surabaya', '06.464.373.7-604.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(320, 11, 'PT. Diasindo Karya Ristrady', 0, '', 'Jl. Papa Merah 2A Rt/Rw 005/015, Tulusrejo, Lowokwaru, Kotamadya Malang', '03.321.710.0-652.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(321, 11, 'Bend.Rumkit Bhayangkara Polda Jatim Nganjuk', 0, '', 'Jl. Abdul Rahman Saleh No 52, Kel.Kauman, Kec.Nganjuk, Kab.Nganjuk, Jawa Timur', '00.230.543.1-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(322, 11, 'PT. Rama Emerald Multi Sukses', 0, '', 'Desa Tenaru, Tenaru Driyorejo, Kab.Gresik, Jawa Timur', '01.438.620.5-641.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(323, 34, 'PT. Sumber Utama Medicalindo', 0, '', 'Jl. Prof Hm Yamin Sh No 241, Sei Kera Hilir I, Medan Perjuangan, Medan', '31.653.635.8-113.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(324, 1, 'PT. Cahaya Murni Cemerlang', 0, '', 'Jl. Gatot Subroto Ii D No.1, Dangin Puri Kaja, Denpasar Utara, Bali', '02.217.510.3-901.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(325, 11, 'Bendahara Rsud Slg', 0, '', 'Jl. Galuh Candra Kirana, Tugurejo, Ngasem, Kab. Kediri, Jawa Timur', '85.972.311.6-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(326, 11, 'PT. Langgeng Jaya Sempurna Abadi', 0, '', 'Taman Puspa Sari Blok E No 5 Rt 32 Rw 07, Klurak, Candi, Sidoarjo, Jawa Timur', '31.329.701.2-617.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(327, 11, 'Dinas Kesehatan Daerah Kabupaten Sumenep', 0, '', 'Sumenep', '00.006.186.1-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(328, 11, 'Bend. Umum Blud Puskesmas Suruh', 0, '', 'Jalan Routine Jend.Sudirman Rt015, Rw006, Suruh, Suruh, Kab.Trenggalek, Jawa Timur', '71.454.273.5-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(329, 11, 'Rutin Dinas Kesehatan Daerah Tk.Ii Bondowoso', 0, '', 'Jl.Imam Bonjol No 13, Kademangan, Bondowoso, Jawa Timur', '00.035.993.5-656.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(330, 11, 'BENDAHARA DANA KAPITASI JKN UPT PUSKESMAS SEKARGADUNG', 0, '', 'TERUSAN SEKARSONO NO 01 RT.RW. KEL.SEKARGADUNG KEC.PURWOREJO, KOTA PASURUAN, JAWA TIMUR', '71.982.024.3-624.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(331, 19, 'Bend.Rutin Politeknik Kesehatan Ambon', 0, '', 'Jl. Dewi Sartika, Kel.Amantelu, Kec.Sirimau, Ambon, Maluku', '00.328.032.8-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(332, 10, 'SRI SURYATI', 0, '', 'DSN KRAJAN RT. 005 RW. 001 NGABENREJO, GROBOGAN, JAWA TENGAH - 58152', '36.025.620.0-514.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(333, 11, 'Kapitasi JKN Puskesmas Bungah', 0, '', 'Jl. Raya Bungah No. 15 Kel. Bungah, Kec. Bungah, Gresik, Jawa Timur', '71.514.834.2-612.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(334, 19, 'Bend. Rutin RSUD Namlea', 0, '', 'Namlea, Kel. Namlea, Kec. Buru Utara Timur, Buru', '00.162.935.1-941.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(335, 10, 'Puskesmas Wangon I', 0, '', 'Jl. Raya Barat No. 59 Wangon, Wangon Wangon, Banyumas, jawa tengah', '00.321.193.5-521.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(336, 3, 'PT. Autochem Industry', 0, '', 'JL. Gatot Subroto Km 7, Jateng, Jatiuwung, KOTA TANGERANG BANTEN', '01.368.928.6-415.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(337, 11, 'Bend. JKN UOT Puskesmas Bandar', 0, '', 'Jl. Raya Bandar RT. 001 RW. 004, Bandar, Bandar, Pacitan, Jawa Timur', '70.602.917.0-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(338, 29, 'Bend. Pengeluaran Dinas Kesehatan Kab. Banggai', 0, '', 'Jl. Ahmad Yani No. 02 Luwuk, Luwuk, Banggai, Sulawesi Tengah', '00.138.388.4-832.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(339, 11, 'Bend. Dinkes Kab. Ngada', 0, '', 'Jl. Gajah mada No. 2', '00.260.663.0-923.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(340, 6, 'PT. Sarana Bakti Utama', 0, '', 'SMA 14 No. 47 B RT. 002 RW. 004, Kel. Cililitan, Kec. Kramat Jati, Jakarta Timur, DKI Jakarta', '72.012.168.0-005.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(341, 11, 'Bend. Peng. Pembantu RS Kusta Kediri', 0, '', 'Jl. Veteran No. 48 Mojoroto, Mojoroto, Kediri', '00.034.641.1-622.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(342, 11, 'Rutin RSU Kab. Magetan', 0, '', 'Jl. Pahlawan No. 2 Tambran, Tambran, magetan, Magetan, Jawa Timur', '00.034.855.7-646.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(343, 30, 'Bend. Pemegang Kas Unit Dinas Kesehatan Kab. Konawe', 0, '', 'Jl. Inolobunggadue No. 323, Puunaha', '00.412.387.3-811.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(344, 11, 'Pemegang Kas RSUD Pamekasan', 0, '', 'Jl. Kesehatan 3-5 , Barurambat Kota, Pamekasan', '00.454.665.1-608.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(345, 11, 'PT. Swabina Gatra', 0, '', 'Jl. RA. Katini No. 21-A Sidomoro, Kebomas, Gresik, Jawa Timur - 61122', '01.480.112.0-641.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(346, 11, 'Bendahara Pengeluaran Pembantu pada Kelurahan Taman', 0, '', 'Jl. Asahan No. 48, Taman, Taman, Madiun, jawa Timur', '00.664.206.0-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(347, 1, 'I Nyoman Budiasa', 0, '', 'Jl. P. Bangka GG IV No. 9 Kel. Pedungan, Kec. Denpasar Selatan', '5.17103E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(348, 9, 'Heri Prasetia. AMK', 0, '', 'KP Telebud RT. 01 RW.02 Jampang Tengah, Jampang Tengah, Sukabumi, Jawa Barat', '47.830.871.1-405.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(349, 12, 'Kantor Dinas Kesehatan Kab. Kubu Raya', 0, '', 'Jl. Arteri Supadio Sungai Raya, Sungai Raya, Pontianak', '00.602.550.9-701.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(350, 20, 'Bend. Pengeluaran RSUD Jailolo', 0, '', 'Jl. IR. Soekarno Acango, Kec. Jailolo, Kab. Halmahera Barat', '00.829.751.7-943.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(351, 24, 'PT. Parasi Roha Papua', 0, '', 'Blok J No. 8 KPR BTN Tanah Hitam Asano, Abepura, Jayapura', '02.016.861.3-952.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(352, 11, 'Bendahara Puskesmas Wonorejo', 0, '', 'Ds. Kolak, Wonorejo, Ngadi Luwih, Kab. Kediri, Jawa Timur', '20.011.211.8-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(353, 15, 'Dinas Kesehatan Kab. Nunukan', 0, '', 'Jl. RA. Bessing, Komp. Perkantoran Gadis 2 Nunukan, Kalimantan Timur', '15.433.176.3-723.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(354, 11, 'Bend. Pengeluaran Pembantu Pada Kelurahan Kuncen', 0, '', 'Jl. Masjid Raya No. 16 Madiun', '00.664.205.2-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(355, 11, 'Bend. Pengeluaran Pembantu Pada Kelurahan Josenan', 0, '', 'Jl. Cokrobasonto RT. 022 Josenan, Taman, Madiun', '00.664.209.4-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(356, 11, 'Bend. Pengeluaran Pembantu Pada Kelurahan Mojorejo', 0, '', 'Jl. Setia Budi No. 42 Madiun', '00.664.204.5-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(357, 11, 'Bendahara Pengeluaran Blud Puskesmas Klecorejo', 0, '', 'Jl. Wates Klecorejo, Mejayan, Kab. Madiun, Jawa Timur', '66.320.414.7-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(358, 11, 'Bend. JKN Puskesmas Pakis Baru', 0, '', 'Dsn. Bulu RT. 003 RW. 006, Kel. Ngromo, Kec. Nawangan, Pacitan, Jawa Timur', '70.651.963.4-647.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(359, 11, 'Bend. Puskesmas Trosobo', 0, '', 'Raya Trosobo, Trosobo, Taman Sidoarjo', '00.841.078.9-603.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(360, 14, 'Bend. RSUD DR. Doris Sylvanus', 0, '', 'Jl. Tambun Bungai No. 04 Pahandut, Pahandut, Palangkaraya', '00.008.073.9-711.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(361, 9, 'Torang Panyusunan', 0, '', 'Jl. Perum Pemda BLC 16 No. 5 RT. 005 RW. 011, Jatiasih, Kotamadya- Bekasi', '59.407.128.4.432.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(362, 11, 'Bend. Pengeluaran Dinas Kesehatan', 0, '', 'Jl. Dr. Soetomo No. 04 RT/RW Trenggalek, Trenggalek, Jawa Timur', '00.491.855.3-629.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(363, 11, 'Kelurahan Manisrejo', 0, '', 'Jl. Tanjung Raya No. 44 Kota Madiun', '00.664.207.8-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(364, 11, 'Bendahara Pengeluaran Pembantu UPTD Puskesmas Sukorame Kota Kediri', 0, '', 'Jl. Veteran No. 50A Sulorame, Mojoroto, Kota Kediri', '83.633.837.6-622.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(365, 11, 'Bendahara Pengeluaran Pembantu UPT Puskesmas Gedongan', 0, '', 'Jl. Gajah Mada No. 54 Kel. Gedongan, Kec. Magersari, Kota Mojokerto, Jawa Timur', '71.986.462.1-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(366, 9, 'CV. Multi Usaha Jaya', 0, '', 'Taman Cikas Blok A4 No. 11 RT. 002 R. 025 Pekayon Jaya, Bekasi Selatan, Kota Bekasi Jawa Barat', '75.828.743.7-432.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(367, 12, 'Windhu Muhammad Ridha', 0, '', 'Jl. Adi Sucipto RT. 003 RW. 008 Arang Limbung, Sungai Raya, Pontianak', '58.810.563.5-704.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(368, 11, 'Nur Arif Rahmatullah', 0, '', 'Jl. Cempaka No. 1B RT. 036 RW. 008 Oro-oro Ombo, Kartoharjo, Madiun, Jawa Timur', '76.646.070.3-621.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(369, 11, 'PT. Daya Matahari Utama', 0, '', 'Jl. Kertomenanggal III No. 3, Dukuh Menanggal, Gayungan, Surabaya,  Jawa Timur', '02.700.144.5-609.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(370, 11, 'PT. Kasuma', 0, '', 'Jl. Jojoran 1 Perintis 1/39 Surabaya', '03.177.972.1-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(371, 1, 'PT. Bali Agung Waters', 0, '', 'Jl. Besakih No. 4 Menanga, Rendang, Karangasem', '31.275.605.9-907.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(372, 11, 'Bendahara BLUD RSUD DR Abdoer Rahem', 0, '', 'Jl. Anggrek No. 68 Patokan, Situbondo', '00.745.935.7-656.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(373, 11, 'Mulyo Hadi Soesilo', 0, '', 'Jl. Krembangan Bhakti 1A RT. 005 RW. 002 Surabaya', '3.57815E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(374, 11, 'Aditya Yoga Satria', 0, '', 'Dsn. Banjarejo RT 001 RW 001, Banjarejo, Ngadiluwih, Kab. Kediri', '93.816.014.0-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(375, 10, 'PT. Bhineka karya Elektrindo', 0, '', 'RE Martadinata Ruko Mutiara Marina lantai 2 No. 7 Tawangsari, Semarang Barat, Semarang, Jawa Tengah', '84.361.204.5-503.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(376, 31, 'Harold Immanuel Marcelliano', 0, '', 'Perumahan BTN Wale Nusantara Blok B No. 51 Lingk. IV Paniki Bawah, Mapanget, Kota Manado, Sulawesi Utara', '83.705.445.1-821.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(377, 11, 'Nikmah Ernawati', 0, '', 'Dusun Bogo RT. 002 RW. 008 Bulu Semen, Kab. Kediri Jawa Timur', '74.811.896.5-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(378, 11, 'Septian Akhmad Sugianto', 0, '', 'Dusun Krajan Tengah RT. 012 RW. 002 Sumberjati, Kecamatan Tempeh', '3.50805E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(379, 28, 'PT. Bumi Menara Internusa', 0, '', 'Kima 15 Kav. R No. 4 C RT. 004 RW. 002 Kel. Daya Kec. Biringkanaya Kota Makasar, Sulawesi Selatan', '01.454.019.9-801.001', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(380, 18, 'PT. Bumi Menara Internusa', 0, '', 'IR. Sutami KM 12 Lematang, Tanjung Bintang, Lampung Selatan', '01.454.019.9-325.001', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(381, 11, 'Bambang Arianto', 0, '', 'Pandugo II Blok P. II P/6 RT. 003 RW. 009 Penjaringan sari, Rungkut, Surabaya', '09.748.390.3-615.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(382, 11, 'Rahmatullah', 0, '', 'Singorejo RT. 001 RW. 004 Dahan rejo, Kebomas', '3.52514E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(383, 1, 'PT. Wahyu Artha Medika', 0, '', 'Jl. Tukad Barito No. 8B, Panjer-Denpasar Selatan, Denpasar - Bali ', '31.342.385.7-903.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(384, 5, 'CV. Krisma Jaya Mandiri', 0, '', 'Jl. Babarsari No. 26 Caturtunggal, Depok, Sleman-DI Yogyakarta 55281', '31.328.963.9-542.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(385, 10, 'Nugroho Widi Utomo', 0, '', 'Jl. Medoho Selecta No. 17 RT. 002 RW. 005 Sambirejo, Gayamsari Kota Semarang Jawa Tengah', '85.511.671.1-518.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(386, 10, 'Hanifah Githa Ariani', 0, '', 'Jl. A. yani D4 RT. 005 RW. 002 Potrobangsan, Magelang Utara, Kota Magelang, Jawa Tengah', '93.610.186.4-524.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(387, 11, 'Rumah Sakit Muhammadiyah Gresik', 0, '', 'Jl. KH. Kholil No. 88 RT. 005 RW. 001 Kemuteran Gresik', '01.233.476.9-612.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(388, 11, 'Cahyo Nugroho, SH', 0, '', 'Jl. Gubeng Kertajaya IX C No. 35 RT. 006 RW. 005 Airlangga, Gubeng, Surabaya', '59.833.768.1-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(389, 11, 'Henry Sudigdo', 0, '', 'Jl. Babatan Pratama 28/XX 100 RT. 008 RW. 008 Babatan, Witung, Surabaya', '3.57812E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(390, 11, 'Bend. Pengeluaran Dinas Kesehatan Kab. Sidoarjo', 0, '', 'Jl. Mayjen Sungkono No. 46 Pucang, Sidoarjo                                                                                                                                                                                                                    ', '00.034.196.6-617.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(391, 34, 'PT. Tiga Darma Abadi', 0, '', 'Jl. Sersan Muslim RT. 07 Paal Merah, Paal Merah Kota Jambi', '81.447.791.5-331.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(392, 6, 'PT. Aneka Industri Gas Medik', 0, '', 'Komplek Green Sedayu Bizpark Blok GS 5 No. 122 Cakung Timur, Cakung, Jakarta Timur, DKI Jakarta', '92.499.219.1-006.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(393, 11, 'H. Abdul Muin', 0, '', 'Jl. B. Katamso Gg Salak No. 251A Sukaharja-Delta Pawan Kab. Ketapang', '79.095.676.7-703.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(394, 11, 'PT. Global Surya Kemala', 0, '', 'Jl. Inspeksi Brantas No. 14 RT. 021 RW. 007 Mojoroto, Kec. Mojoroto Kota Kediri', '02.665.876.5-622.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(395, 11, 'Anggi Setiawan', 0, '', 'Jl. Mastrip Kr. Pilang 12 Surabaya', '3.57801E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(396, 11, 'J. Nugroho Eko Putranto/ dr. Ursula', 0, '', 'Jl. Ploso 9B/11 RT. 009 RW. 005 Ploso, Surabaya', '08.609.781.3-619.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(397, 11, 'PT. Adiguna Label Indonesia', 0, '', 'Jl. Raya Osowilangun No. 61 Blok D-15 Surabaya', '02.337.908.4-604.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(398, 6, 'PT. Surya Darma Perkasa', 0, '', 'Jl. Daan Mogot KM 1 No. 99 RT. 006 RW. 005 Kebon Jeruk Jakarta Barat', '01.567.987.1-038.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(399, 11, 'PT. Hartono Raya Motor', 0, '', 'Jl. Demak 156-170 Gundih-Bubutan Surabaya', '01.108.186.6-631.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(400, 31, 'Ekawati Hartono', 0, '', 'Perum Labuan Indah Blok E8, Kel. Manembo Nembo, Kec. Matuari Kota Bitung 95545', '77.987.627.5-823.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(401, 11, 'PT. Abadi Hutan Tropis', 0, '', 'Jl. Dharmahusada Utara I/50 Surabaya', '01.720.929.2-606.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(402, 10, 'Herje Joenianto, DR, SPM', 0, '', 'Jl. Dr. Susanto No 113 RT. 01 RW. 01 Kutoharjo, Pati', '08.850.767.6-507.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(403, 11, 'Ilvana Ardiwirastuti', 0, '', 'Jl. Joyoboyo GG Dahlia RT. 24 RW. 03 Karangrejo, Ngasem, Kediri', '84.952.412.9-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(404, 1, 'PT. Hotel Ramapalace Cottage', 0, '', 'Grand Istana Rama Hotel Jl. Pantai Kuta, Kuta, Badung - Bali - 80361', '01.460.192.6-904.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(405, 11, 'Dinas Kesehatan Kabupaten Tuban', 0, '', 'JALAN BRAWIJAYA NOMOR 3 KEBONSARI, TUBAN, TUBAN, JAWA TIMUR', '00.279.607.6-648.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(406, 10, 'CV. Sani Putri Medika', 0, '', 'Jl. Soekarno-Hatta No. 75 Palebon, Pedurungan, Semarang', '03.077.227.1-518.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(407, 10, 'CV. Sani Retailindo', 0, '', 'Jl. DR. Cipto No. 174 A Karang Tempel, Semarang timur, Semarang', '84.263.204.4-504.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(408, 11, 'CV. Hawaii Group', 0, '', 'Jl. Dupak Mutiara 63 F No. 20 RT. 004 RW. 005 Surabaya', '82.470.050.4-614.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(409, 11, 'Suryanto', 0, '', 'Jl. Manukan mukti VIII Blok 12A/ No. 15 Surabaya', '3.57819E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(410, 11, 'PT. Esa Sampoerna', 0, '', 'Eka Sampoerna Center Lt. 5 Jl. DR. Ir. H. Soekarno No. 195 RT. 002 RW. 009 Surabaya', '01.979.278.7-631.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(411, 10, 'PT. Ares Pratama Medika', 0, '', 'Jl. S. Parman No. 239 A-B RT. 005 RW. 003 PWT Kulon-PWT Selatan - Banyumas', '02.006.778.1-521.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(412, 11, 'PT. Berkah Pro Medika', 0, '', 'Jl. Kebraon Indah Permai 3 L No. 19 RT.007 RW.013 Kebraon karang Pilang Kota Surabaya Jawa Timur 60222', '66.251.103.9-618.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(413, 6, 'Bambang Supardi', 0, '', 'Taman Cipulir O.2/1 Rt.002/Rw.008 Kel.Cipadu Jaya Kec.Larangan Kota Tangerang', '3.67113E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(414, 11, 'Stevanus Budianto', 0, '', 'Puri Lidah Kulon Indah Blok T2 RT/RW 006/007 Kel. Lidah Kulon Kec. Lakar Santri Kota Surabaya', '3.57818E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(415, 6, 'Nurdin', 0, '', 'Jl. Kalianyar VI No. 34 RT.009 RW.002 Kel. Kalianyar Kec.Tambora Jakarta Barat DKI jakarta', '70.048.242.5-033.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(416, 1, 'PT. Borneo EtaM Mandiri', 0, '', 'Komplek Villa Damai Permai Blok D-4 No. 01 Rt. 032 Gunung Bahagia Balikpapan Selatan Kota Balikpapan Kalimantan Timur 76114', '83.167.930.3-721.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(417, 11, 'PRIYADI KUNCORO, TJIO', 0, '', 'Virgo No. 40 RT.002 RW.006 Kel. Ploso Kec. Tambak Sari', '3.57807E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(418, 28, 'PT. GHINA HUSADA NUSANTARA', 0, '', 'Puri Taman Sari Blok L2/8 RT.006 RW.002 Borong, Manggala Makassar - Sulawesi Selatan', '03.289.018.8-805.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(419, 1, 'PT. JAYA CENTRA MEDIKA', 0, '', 'JL. Gatot Subroto Barat Pemecutan Kaja Denpasar Utara Kota Denpasar Bali', '91.065.609.9-901.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(420, 11, 'CV. ANEKA', 0, '', 'Jalan Nginden 6F No. 9A RT.008 RW.005 Nginden Jangkungan Sukolilo', '71.881.685.3-806.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(421, 11, 'RS KUSTA SUMBERGLAGAH MOJOKERTO SEKRETARIAT JENDERAL KEMENTERIAN', 0, '', 'DSN SUMBERGLAGAH TANJUNGKENONGO, PACET', '95.302.731.5-602.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(422, 15, 'PT. HIDUP BAHAGIA MEDICA', 0, '', 'JL. P. HIDAYATULLAH NO.46 RT.013 PELABUHAN , SAMARINDA ILIR SAMARINDA', '03.278.558.6-722.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(423, 11, 'PUSAT KESEHATAN MASYARAKAT BESUK KABUPATEN PROBOLINGGO', 0, '', 'Jalan Raya Besuk No. 08 RT.005 RW.005 Alaskandang Besuk', '96.416.335.6-625.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(424, 11, 'DINAS KESEHATAN KABUPATEN PASURUAN', 0, '', 'Jl. Raya Raci KM 9 Bangil Raci Bangil', '00.140.432.6-624.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(425, 10, 'PT. GLOBAL DIGITAL NIAGA', 0, '', 'Jl. Jend. A Yani No. 34 Panjunan, Kota Kudus - Jawa Tengah', '03.000.644.9-506.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(426, 11, 'YAP THJOEN LIEM', 0, '', 'JL. Dempo 7-8 Rt.003 Rw.006 KeL. Petemon Kec. Sawahan Kota Surabaya', '3.57806E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(427, 10, 'INTAN FAINZAH HANDAYANI', 0, '', 'Ds. Pendosawalan Blok A No. 10 RT.022 RW.008 Pendosawalan, KalinyaMatan Kab. Jepara Jawa Tengah', '83.815.592.7-516.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(428, 6, 'HARIO BASKORO', 0, '', 'Jl. Pulo Asem V No. 34 Rt.004/Rw.001 KeL. Jati Kec. Pulo Gadung Jakarta Timur', '3.27601E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(429, 11, 'PT. IKAN KAKAP', 0, '', 'Jl. Ikan Kakap No. 23 Perak Barat - Krembangan Surabaya - Jawa Timur', '01.232.274.9-605.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(430, 11, 'PT. IKAN DORANG', 0, '', 'Jl. Ikan Dorang No. 2 Perak Barat - Krembangan Surabaya - Jawa Timur', '01.108.190.8-605.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(431, 11, 'HELDY WILLIAM SUGIHARTO', 0, '', 'Jl. Wonorejo I/35 Rt. 002 Rw. 003 Kel. Wonorejo Kec. Tegalsari Surabaya', '3.57805E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(432, 11, 'PT. ENDO INDONESIA', 0, '', 'Raya Meganti 14 RT/RW 003/001 Kedurus, Karang Pilang Surabaya - Jawa Timur 60223', '02.114.186.6.618.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(433, 10, 'CV. CENTRALINDO MEGAH', 0, '', 'Desa Yamansari RT.003 RW.001 Yamansari Lebaksiu Kab. Tegal Jawa Tengah', '85.227.010.7-501.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(434, 9, 'PT. ZEFA VALINDO JAYA', 0, '', 'Kawasan Pergudangan Dan Perdagangan Sentra Niaga 5 Blok SN 5 1 No. 12 Pusaka Rakyat Tarumajaya Kab. Bekasi, Jawa Barat', '01.917.805.2-407.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(435, 34, 'ASEP SUNARYA', 0, '', 'JL. Karya Bakti LK VIII No. 147 Indra Kasih, Medan Tembung Kota Medan Sumatera Utara', '81.910.184.1-113.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(436, 31, 'PT. BANGUN MEDIKA SEJAHTERA', 0, '', 'JL. TNI Raya No. 91 Tikala Baru, Tikala', '81.929.845.6-821.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(437, 11, 'PT. WIYUNG SEJAHTERA', 0, '', 'Karangan PDAM/03 RT RW Babatan Wiyung Surabaya', '02.443.023.3-618.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(438, 18, 'KISWAN RAHARSO', 0, '', 'Perum bukit kemiling permai Z No. 101 RT.010 RW-Kel.kemiling permai kec.kemiling bandar lampung lampung', '66.676.428.7-322.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(439, 9, 'IRVAN FATHUROHMAN', 0, '', 'Jl. Ciwastra gg.H.Adnan RT/RW 006/016 KeL. Margasari Kec. Buah Batu Kota Bandung', '3.27322E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(440, 9, 'PATRIA SAPUTRA', 0, '', 'Jalan Bandar Agung Village 3 C No. 10 RT.006 RW.004 Jati Sari, Jatiasih Kota Bekasi Jawa Barat', '91.136.311.7-447.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(441, 6, 'WELLY GOZALI', 0, '', 'JL. Arteri Pondok Indah No. 31 HK Kebayoran Lama Selatan - Kebayoran Lama Jakarta Selatan - DKI Jakarta', '06.968.146.8-013.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(442, 18, 'FINA YUSRIY YANA', 0, '', 'Mulyasari RT.013/RW.001 Kel.Mulyo Asri Kec. Tulang Bawang Tengah Tulang Bawang Barat', '3.50617E+15', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(443, 11, 'RUMAH SAKIT UMUM DAERAH SIMPANG LIMA GUMUL KABUPATEN KEDIRI', 0, '', 'Jalan Galuh Candra Kirana Tugurejo Tugurejo Ngasem', '00.300.670.7-655.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(444, 11, 'CV. HANJAYA', 0, '', 'PENGADAIAN NO. 86 RT.001 RW.001 PURWOSARI , PURWOSARI PASURUAN', '31.744.230.9-624.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(445, 11, 'PT. BINTANG NUSANTARA MEDIKA', 0, '', 'PERUM ISTANA CANDI MAS REGENCY G4 NO.21 RT.001 RW.006 NGAMPELSARI, CANDI KAB. SIDOARJO JAWA TIMUR', '81.929.527.0-617.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(446, 11, 'YAY. PONDOK KASIH', 0, '', 'JL. KENDANGSARI II/82 KENDANGSARI - TENGGILIS MEJOYO SURABAYA', '01.823.144.9-615.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(447, 11, 'PT. GUNAWAN DIANJAYA STEEL TBK', 0, '', 'JL. MARGOMULYO NO. 29 A RT.001 RW.001 TAMBAK SARIOSO ASEMROWO SURABAYA JAWA TIMUR', '01.481.535.1-092.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(448, 22, 'PT. BIBIT UNGGUL', 0, '', 'JL. RAYA BAYAN DUSUN MONTONG PAL REMPEK GANGA LOMBOK UTARA NUSA TENGGARA BARAT', '02.178.440.0-915.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(449, 11, 'PT. TIRTA CATUR TUNGGAL', 0, '', 'Dsn Tembero RT.02 RW.03 Tanggulangin, Kejayan Kabupaten Pasuruan 67172', '03.158.998.9-624.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(450, 10, 'PT. ADI KARYA OKANE', 0, '', 'DK TASIKMADU RT.025 RW.007 KEPUTRAN KEMALANG KAB. KLATEN JAWA TENGAH', '92.550.707.1-525.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(451, 6, 'PT. TAWADA HEALTHCARE', 0, '', 'Jl. Tentara Pelajar Permata Senayan Blok A 18-19 Grogol Utara - Kebayoran Lama Jakarta Selatan - DKI Jakarta', '01.903.521.1-062.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(452, 6, 'GLORIA AMANDA PUTRI', 0, '', 'JL. Pejaten Barat No. 18 RT.001 RW.010 Ragunan Pasar Minggu Jakarta Selatan DKI Jakarta 12550', '85.387.309.9-017.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(453, 10, 'ANDY TJEE', 0, '', 'Begalon RT.002 RW.003 Penularan, Laweyan Kota Surakarta Jawa Tengah', '81.269.473.5-526.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(454, 34, 'RAYMOND WAROUW', 0, '', 'Jl. Sun Yat Sen No. 24-26 Sei Rengas I,Medan Kota Kota Medan Sumatera Utara', '91.318.446.1-122.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(455, 11, 'CV. MULTI SARANA JAYA', 0, '', 'Jl. Kalijaten Gang I-B/47 RT.09 RW.02 Kalijaten Taman Sidoarjo', '02.102.706.5-603.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(456, 31, 'PT. ASEGAR MURNI JAYA', 0, '', 'Kel. Tumaluntung Jaga IV, Tumaluntung Tumaluntung Kauditan Kab. Minahasa Utara Sulawesi Utara', '02.700.687.3-823.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(457, 11, 'CV. ROM POER SEJAHTERA', 0, '', 'Jl. Kebonsari V No. 20 RT.006 RW.002 Kebonsari Jambangan Kota Surabaya Jawa Timur 60233', '70.251.870.5-609.000', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `detail_ekatalog`
--

CREATE TABLE `detail_ekatalog` (
  `id` int(11) NOT NULL,
  `ekatalog_id` int(11) NOT NULL,
  `penjualan_produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `ongkir` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_ekatalog`
--

INSERT INTO `detail_ekatalog` (`id`, `ekatalog_id`, `penjualan_produk_id`, `jumlah`, `harga`, `ongkir`, `created_at`, `updated_at`) VALUES
(59, 20, 10, 1, 66550000, 0, '2021-11-07 20:44:19', '2021-11-07 20:44:19'),
(60, 20, 52, 2, 298100000, 0, '2021-11-07 20:44:19', '2021-11-07 20:44:19'),
(61, 21, 20, 2, 125950000, 0, '2021-11-07 21:01:01', '2021-11-07 21:01:01'),
(62, 21, 49, 1, 284790000, 0, '2021-11-07 21:01:01', '2021-11-07 21:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan_produk`
--

CREATE TABLE `detail_penjualan_produk` (
  `produk_id` int(11) NOT NULL,
  `penjualan_produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_penjualan_produk`
--

INSERT INTO `detail_penjualan_produk` (`produk_id`, `penjualan_produk_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(3, 3, 2, '0000-00-00 00:00:00', '2021-11-03 02:49:27'),
(5, 5, 2, '0000-00-00 00:00:00', '2021-11-03 00:19:46'),
(6, 6, 2, '0000-00-00 00:00:00', '2021-11-03 02:18:16'),
(9, 7, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 9, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 11, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 12, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 12, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 13, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 14, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 15, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 16, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 17, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 18, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 19, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 19, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 20, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 20, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 21, 2, '0000-00-00 00:00:00', '2021-11-03 02:36:45'),
(98, 21, 2, '0000-00-00 00:00:00', '2021-11-03 02:36:45'),
(21, 22, 1, '0000-00-00 00:00:00', '2021-11-03 00:49:03'),
(98, 22, 2, '0000-00-00 00:00:00', '2021-11-03 00:49:03'),
(66, 22, 1, '0000-00-00 00:00:00', '2021-11-03 00:49:03'),
(137, 22, 2, '0000-00-00 00:00:00', '2021-11-03 00:49:03'),
(143, 22, 1, '0000-00-00 00:00:00', '2021-11-03 00:49:03'),
(21, 23, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 23, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 23, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 24, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 24, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 24, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 24, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 26, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 26, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 27, 2, '0000-00-00 00:00:00', '2021-11-03 02:19:53'),
(24, 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 33, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 34, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 35, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 36, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 36, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 37, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 38, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 38, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 39, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 39, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 39, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 40, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 40, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 41, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 42, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 42, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 43, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 43, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 43, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 44, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 44, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 45, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 46, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 46, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 47, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 47, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 47, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 48, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 48, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 49, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 50, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 50, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 51, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 51, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 51, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 52, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 52, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 53, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 54, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 54, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 55, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 55, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 55, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 56, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 56, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 57, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 58, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 58, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 59, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 59, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 60, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 60, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 60, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 61, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 62, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 62, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 63, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 63, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 64, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 64, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 64, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 65, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 66, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 66, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 67, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 64, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 68, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 68, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 68, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 69, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 70, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 71, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 72, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 73, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 74, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 74, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 75, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 75, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 75, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 76, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 76, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 77, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 78, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 78, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 79, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 80, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 80, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 81, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 82, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 83, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 83, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 84, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 85, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 85, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 86, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 87, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 87, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 88, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 88, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 89, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 89, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 89, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 90, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 90, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 91, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 92, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 92, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 93, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 93, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 94, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 95, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 95, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 96, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 96, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 96, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 97, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 97, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 98, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 99, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 99, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 100, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 100, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 101, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 102, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 103, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 104, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 104, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 105, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 106, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 106, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 106, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 107, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 107, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 107, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 107, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 108, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 108, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 109, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 110, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 110, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 111, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 112, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 113, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 115, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 116, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 117, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 117, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 118, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 118, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 118, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 119, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 119, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 120, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 121, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 121, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 122, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 122, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 122, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 123, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 123, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 124, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 125, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 125, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 126, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 126, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 126, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 127, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 127, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 128, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 129, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 130, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 130, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 131, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 131, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 131, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 132, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 132, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 133, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 134, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 134, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 135, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 135, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 136, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 136, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 137, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 137, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 137, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 137, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 137, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 138, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 138, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 138, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 139, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 139, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 139, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 139, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 139, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 140, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 140, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 140, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 140, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 140, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 140, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 140, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 141, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 141, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 141, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 141, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 141, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 141, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 142, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 142, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 142, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 142, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 142, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 143, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 143, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 143, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 144, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 145, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 146, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 147, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 148, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 148, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 148, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 149, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 149, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 149, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 150, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 150, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 150, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 151, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 151, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 151, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 151, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 152, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 152, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 153, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 153, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 153, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 153, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 153, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 153, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 154, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 154, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 154, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 154, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 155, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 155, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 155, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 156, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 157, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 158, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 159, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 159, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 160, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 160, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 161, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 161, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 161, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 162, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 163, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 164, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 165, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 166, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 167, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 168, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 168, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 169, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 169, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 169, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 170, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 170, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 171, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 172, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 172, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 173, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 173, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 173, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 174, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 174, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 175, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 176, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 176, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 177, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 177, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 177, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 178, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 178, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 179, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 180, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 181, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 182, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 183, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 183, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 183, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 184, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 185, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 186, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 186, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 187, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 188, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 188, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 189, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 190, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 190, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 191, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 192, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 192, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 193, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 193, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 194, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 194, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 194, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 195, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 196, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 196, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 197, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 197, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 197, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 198, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 199, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 199, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 200, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 201, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 201, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 209, 1, '2021-10-25 20:34:57', '2021-10-25 20:34:57'),
(6, 209, 1, '2021-10-25 20:34:57', '2021-10-25 20:34:57'),
(1, 210, 1, '2021-10-26 02:27:45', '2021-10-26 02:27:45'),
(129, 210, 1, '2021-10-26 02:27:45', '2021-10-26 02:27:45'),
(3, 212, 6, '2021-11-02 23:59:21', '2021-11-02 23:59:21'),
(4, 213, 6, '2021-11-03 00:00:03', '2021-11-03 00:00:03'),
(6, 213, 6, '2021-11-03 00:00:03', '2021-11-03 00:00:03'),
(137, 25, 2, '2021-11-03 02:19:33', '2021-11-03 02:19:33'),
(129, 29, 1, '2021-11-03 02:20:05', '2021-11-03 02:20:05'),
(67, 21, 2, '2021-11-03 02:36:45', '2021-11-03 02:36:45'),
(14, 215, 2, '2021-11-03 03:03:58', '2021-11-03 03:04:48'),
(8, 215, 1, '2021-11-03 03:04:48', '2021-11-03 03:04:48'),
(1, 1, 1, '2021-11-03 18:02:37', '2021-11-03 18:56:36'),
(1, 216, 2, '2021-11-04 00:01:50', '2021-11-04 00:01:50'),
(4, 216, 2, '2021-11-04 00:01:50', '2021-11-04 00:01:50'),
(2, 217, 1, '2021-11-04 00:03:57', '2021-11-04 00:03:57'),
(5, 217, 2, '2021-11-04 00:03:57', '2021-11-04 00:03:57'),
(2, 218, 2, '2021-11-04 00:06:47', '2021-11-04 00:06:47'),
(2, 219, 1, '2021-11-04 00:10:42', '2021-11-04 00:10:42'),
(5, 220, 1, '2021-11-04 00:17:07', '2021-11-04 00:17:07'),
(3, 221, 12, '2021-11-04 00:18:43', '2021-11-04 00:18:43'),
(4, 222, 23, '2021-11-04 00:20:37', '2021-11-04 00:20:37'),
(1, 223, 1, '2021-11-04 00:28:01', '2021-11-04 00:28:01'),
(1, 224, 1, '2021-11-04 00:28:28', '2021-11-04 00:28:28'),
(2, 225, 1, '2021-11-04 00:45:41', '2021-11-04 00:45:41'),
(2, 226, 1, '2021-11-05 00:41:35', '2021-11-05 00:41:35');

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

--
-- Dumping data for table `detail_spa`
--

INSERT INTO `detail_spa` (`id`, `spa_id`, `penjualan_produk_id`, `jumlah`, `harga`, `ongkir`, `created_at`, `updated_at`) VALUES
(34, 31, 83, 2, 81400000, 0, '2021-11-07 20:55:52', '2021-11-07 20:55:52'),
(35, 31, 32, 1, 1100000, 0, '2021-11-07 20:55:52', '2021-11-07 20:55:52'),
(36, 32, 70, 10, 836000, 0, '2021-11-07 20:58:25', '2021-11-07 20:58:25');

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

--
-- Dumping data for table `detail_spb`
--

INSERT INTO `detail_spb` (`id`, `spb_id`, `penjualan_produk_id`, `jumlah`, `harga`, `ongkir`, `created_at`, `updated_at`) VALUES
(7, 4, 13, 5, 1760000, 0, '2021-11-07 20:57:19', '2021-11-07 20:57:19'),
(8, 4, 80, 1, 31900000, 0, '2021-11-07 20:57:19', '2021-11-07 20:57:19');

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
  `provinsi_id` int(11) NOT NULL,
  `pesanan_id` int(11) DEFAULT NULL,
  `no_paket` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `satuan` varchar(255) NOT NULL,
  `status` enum('sepakat','negosiasi','batal','draft') NOT NULL,
  `tgl_kontrak` date NOT NULL,
  `tgl_buat` date NOT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `log` enum('penjualan','po','qc','gudang','logistik','selesai') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ekatalog`
--

INSERT INTO `ekatalog` (`id`, `customer_id`, `provinsi_id`, `pesanan_id`, `no_paket`, `deskripsi`, `instansi`, `alamat`, `satuan`, `status`, `tgl_kontrak`, `tgl_buat`, `ket`, `log`, `created_at`, `updated_at`) VALUES
(20, 213, 11, 54, 'AK1-P0001', 'Kebutuhan Alat  kesehatan', 'Pemerintah Daerah Kabupaten Jawa Timur', 'Jl GentengKali', 'Satuan Kerja', 'sepakat', '2022-01-01', '2021-11-08', NULL, 'penjualan', '2021-11-07 20:44:19', '2021-11-07 20:53:18'),
(21, 63, 12, NULL, 'AK1-P989898', 'Deskripsi itulah yang aku mau', 'Pemerintah Daerah Kabupaten Tanjung Jabung Barat', 'Jl Kemana Aja Bla Bla', 'Satuan Kerja Sebenarnya', 'negosiasi', '2022-02-16', '2021-11-03', NULL, 'penjualan', '2021-11-07 21:01:01', '2021-11-07 21:01:01');

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

--
-- Dumping data for table `gdg_barang_jadi`
--

INSERT INTO `gdg_barang_jadi` (`id`, `produk_id`, `variasi`, `stok`, `created_at`, `updated_at`) VALUES
(1, 1, '', 38, NULL, NULL),
(2, 2, '', 7, NULL, NULL),
(3, 3, 'PUTIH SUSU & CREAM', 46, NULL, NULL),
(4, 3, 'TRANSPARAN', 2, NULL, NULL),
(5, 4, '', 22, NULL, NULL),
(6, 5, '', 258, NULL, NULL),
(7, 6, '', 20, NULL, NULL),
(8, 7, '', 0, NULL, NULL),
(9, 8, '', 29, NULL, NULL),
(10, 9, '', 3, NULL, NULL),
(11, 10, '', 2, NULL, NULL),
(12, 11, '', 0, NULL, NULL),
(13, 12, '', 0, NULL, NULL),
(14, 13, '', 0, NULL, NULL),
(15, 14, '', 335, NULL, NULL),
(16, 15, '', 4, NULL, NULL),
(17, 16, '', 68, NULL, NULL),
(18, 16, '', 0, NULL, NULL),
(19, 17, '', 1, NULL, NULL),
(20, 18, '', 1, NULL, NULL),
(21, 19, '', 2, NULL, NULL),
(22, 20, '', 0, NULL, NULL),
(23, 21, '', 23, NULL, NULL),
(24, 22, '', 0, NULL, NULL),
(25, 23, 'ABU', 70, NULL, NULL),
(26, 23, 'HIJAU', 119, NULL, NULL),
(27, 23, 'HITAM', 0, NULL, NULL),
(28, 23, 'KUNING', 93, NULL, NULL),
(29, 23, 'PINK', 95, NULL, NULL),
(30, 23, 'PUTIH', 85, NULL, NULL),
(31, 23, 'POLOS', 2, NULL, NULL),
(32, 23, 'NICKEY ABU', 6, NULL, NULL),
(33, 23, 'NICKEY HIJAU', 1, NULL, NULL),
(34, 23, 'NICKEY HITAM', 8, NULL, NULL),
(35, 23, 'NICKEY KUNING', 1, NULL, NULL),
(36, 23, 'TIMBANGAN DIGIT ONE NICKEY PUTIH', 1, NULL, NULL),
(37, 23, 'TIMBANGAN DIGIT ONE NICKEY PINK', 0, NULL, NULL),
(38, 23, 'TIMBANGAN DIGIT ONE NICKEY PUTIH KC LAMA', 0, NULL, NULL),
(39, 23, 'BACKLIGHT ABU', 95, NULL, NULL),
(40, 23, 'BACKLIGHT HIJAU', 169, NULL, NULL),
(41, 23, 'BACKLIGHT HITAM', 16, NULL, NULL),
(42, 23, 'BACKLIGHT KUNING', 104, NULL, NULL),
(43, 23, 'BACKLIGHT PINK', 122, NULL, NULL),
(44, 23, 'BACKLIGHT PUTIH', 43, NULL, NULL),
(45, 24, '', 22, NULL, NULL),
(46, 25, 'COKLAT', 0, NULL, NULL),
(47, 25, 'HIJAU', 9, NULL, NULL),
(48, 25, 'PINK', 2, NULL, NULL),
(49, 25, 'PUTIH ', 4, NULL, NULL),
(50, 25, 'UNGU', 2, NULL, NULL),
(51, 25, 'NEW \"COKLAT\"', 11, NULL, NULL),
(52, 25, 'NEW \"HIJAU\"', 26, NULL, NULL),
(53, 25, 'NEW \"PUTIH\"', 22, NULL, NULL),
(54, 25, 'NEW \"UNGU\"', 71, NULL, NULL),
(55, 26, 'COKLAT', 86, NULL, NULL),
(56, 26, 'HIJAU', 94, NULL, NULL),
(57, 26, 'PUTIH', 62, NULL, NULL),
(58, 26, 'UNGU', 48, NULL, NULL),
(59, 26, 'BLUETOOTH \"COKLAT\"', 0, NULL, NULL),
(60, 26, 'BLUETOOTH \"HIJAU\"', 3, NULL, NULL),
(61, 26, 'BLUETOOTH \"PUTIH\"', 0, NULL, NULL),
(62, 26, 'BLUETOOTH \"UNGU\"', 0, NULL, NULL),
(63, 27, 'COKLAT', 170, NULL, NULL),
(64, 27, 'HIJAU', 77, NULL, NULL),
(65, 27, 'PUTIH', 54, NULL, NULL),
(66, 27, 'UNGU', 65, NULL, NULL),
(67, 28, '', 41, NULL, NULL),
(68, 29, '', 36, NULL, NULL),
(69, 30, '', 2, NULL, NULL),
(70, 31, '', 16, NULL, NULL),
(71, 32, '', 1, NULL, NULL),
(72, 33, '', 31, NULL, NULL),
(73, 34, '', 0, NULL, NULL),
(74, 35, '', 0, NULL, NULL),
(75, 36, '', 6, NULL, NULL),
(76, 37, '', 3, NULL, NULL),
(77, 38, '', 4, NULL, NULL),
(78, 39, '', 1, NULL, NULL),
(79, 40, '', 0, NULL, NULL),
(80, 41, '', 0, NULL, NULL),
(81, 42, '', 1713, NULL, NULL),
(82, 43, '', 386, NULL, NULL),
(83, 44, '', 1978, NULL, NULL),
(84, 45, '', 0, NULL, NULL),
(85, 46, '', 456, NULL, NULL),
(86, 47, '', 3, NULL, NULL),
(87, 48, '', 1, NULL, NULL),
(88, 49, 'BIRU MUDA', 10, NULL, NULL),
(89, 49, 'BIRU TUA', 10, NULL, NULL),
(90, 49, 'KUNING', 23, NULL, NULL),
(91, 49, 'PINK', 21, NULL, NULL),
(92, 49, 'LAVENDER', 3, NULL, NULL),
(93, 49, 'LIME GREEN', 0, NULL, NULL),
(94, 49, 'ORANGE', 14, NULL, NULL),
(95, 49, 'HITAM', 163, NULL, NULL),
(96, 50, 'BIRU', 17, NULL, NULL),
(97, 50, 'PUTIH', 30, NULL, NULL),
(98, 51, '', 16, NULL, NULL),
(99, 52, 'BIRU MUDA', 56, NULL, NULL),
(100, 52, 'KUNING', 30, NULL, NULL),
(101, 52, 'PINK', 26, NULL, NULL),
(102, 53, '', 0, NULL, NULL),
(103, 54, '', 10, NULL, NULL),
(104, 55, '', 295, NULL, NULL),
(105, 56, '', 7, NULL, NULL),
(106, 57, '', 0, NULL, NULL),
(107, 58, '', 76, NULL, NULL),
(108, 59, '', 1663, NULL, NULL),
(109, 60, '', 980, NULL, NULL),
(110, 61, '', 0, NULL, NULL),
(111, 62, '', 23, NULL, NULL),
(112, 63, '', 192, NULL, NULL),
(113, 64, '', 50, NULL, NULL),
(114, 65, '', 0, NULL, NULL),
(115, 66, '', 0, NULL, NULL),
(116, 67, '', 0, NULL, NULL),
(117, 68, '', 56, NULL, NULL),
(118, 69, '', 400, NULL, NULL),
(119, 70, '', 0, NULL, NULL),
(120, 71, '', 35, NULL, NULL),
(121, 72, '', 5, NULL, NULL),
(122, 73, '', 50, NULL, NULL),
(123, 74, '', 48, NULL, NULL),
(124, 75, '', 45, NULL, NULL),
(125, 76, '', 0, NULL, NULL),
(126, 77, '', 2706, NULL, NULL),
(127, 78, '', 9, NULL, NULL),
(128, 79, '', 6, NULL, NULL),
(129, 80, '', 4, NULL, NULL),
(130, 81, '', 2, NULL, NULL),
(131, 82, '', 0, NULL, NULL),
(132, 83, '', 203, NULL, NULL),
(133, 84, '', 524, NULL, NULL),
(134, 85, '', 0, NULL, NULL),
(135, 86, '', 0, NULL, NULL),
(136, 87, '', 0, NULL, NULL),
(137, 88, '', 0, NULL, NULL),
(138, 89, '', 1, NULL, NULL),
(139, 90, '', 1, NULL, NULL),
(140, 91, '', 0, NULL, NULL),
(141, 92, '', 21, NULL, NULL),
(142, 93, '', 32, NULL, NULL),
(143, 94, '', 24, NULL, NULL),
(144, 95, '', 0, NULL, NULL),
(145, 96, '', 0, NULL, NULL),
(146, 97, '', 2, NULL, NULL),
(147, 98, 'HP LASER JET PRO M12W', 1, NULL, NULL),
(148, 98, 'HP M 252 n', 0, NULL, NULL),
(149, 98, 'SONY UP-D25 MD', 5, NULL, NULL),
(150, 98, 'SONY UP-X898 MD', 8, NULL, NULL),
(151, 98, 'HP LASER JET PRO 254 NW', 0, NULL, NULL),
(152, 98, 'SONY UP-X898 MD', 0, NULL, NULL),
(153, 98, 'EPSON INKJET L-120', 12, NULL, NULL),
(154, 98, 'HP DESKJET 1112', 0, NULL, NULL),
(155, 98, 'HP 1216', 1, NULL, NULL),
(156, 99, '', 0, NULL, NULL),
(157, 100, '', 0, NULL, NULL),
(158, 101, '', 0, NULL, NULL),
(159, 102, '', 8, NULL, NULL),
(160, 103, '', 1776, NULL, NULL),
(161, 104, '', 44, NULL, NULL),
(162, 105, '', 188, NULL, NULL),
(163, 106, '', 287, NULL, NULL),
(164, 107, '', 0, NULL, NULL),
(165, 108, '', 695, NULL, NULL),
(166, 109, '', 481, NULL, NULL),
(167, 110, '', 9, NULL, NULL),
(168, 111, '', 19, NULL, NULL),
(169, 112, '', 31, NULL, NULL),
(170, 113, '', 40, NULL, NULL),
(171, 114, '', 11, NULL, NULL),
(172, 115, '', 1725, NULL, NULL),
(173, 116, '', 52, NULL, NULL),
(174, 117, '', 1, NULL, NULL),
(175, 118, '', 1984, NULL, NULL),
(176, 119, '', 1497, NULL, NULL),
(177, 120, '', 0, NULL, NULL),
(178, 121, '', 3, NULL, NULL),
(179, 122, '', 1, NULL, NULL),
(180, 123, '', 0, NULL, NULL),
(181, 124, '', 25, NULL, NULL),
(182, 125, '', 26, NULL, NULL),
(183, 126, '', 353, NULL, NULL),
(184, 127, '', 32, NULL, NULL),
(185, 128, '', 0, NULL, NULL),
(186, 129, '', 0, NULL, NULL),
(187, 130, '', 28, NULL, NULL),
(188, 131, '', 46, NULL, NULL),
(189, 132, '', 0, NULL, NULL),
(190, 133, '', 0, NULL, NULL),
(191, 134, '', 0, NULL, NULL),
(192, 135, '', 25, NULL, NULL),
(193, 136, '', 24, NULL, NULL),
(194, 137, '', 11, NULL, NULL),
(195, 138, '', 3, NULL, NULL),
(196, 139, '', 4, NULL, NULL),
(197, 140, '', 0, NULL, NULL),
(198, 141, '', 0, NULL, NULL),
(199, 142, '', 15, NULL, NULL),
(200, 143, 'UPS 1000 + ', 1, NULL, NULL),
(201, 143, 'UPS 3000 + ', 3, NULL, NULL),
(202, 144, '', 9, NULL, NULL),
(203, 145, '', 0, NULL, NULL),
(204, 146, '', 0, NULL, NULL),
(205, 147, '', 0, NULL, NULL),
(206, 148, '', 0, NULL, NULL),
(207, 149, '', 1, NULL, NULL),
(208, 150, '', 0, NULL, NULL),
(209, 151, '', 0, NULL, NULL),
(210, 152, '', 12, NULL, NULL),
(211, 153, '', 0, NULL, NULL),
(212, 154, '', 0, NULL, NULL),
(213, 155, '', 0, NULL, NULL),
(214, 156, '', 5, NULL, NULL),
(215, 157, '', 3, NULL, NULL),
(216, 158, '', 616, NULL, NULL);

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
(3, '2019_08_19_000000_create_failed_jobs_table', 2);

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
(1, 'ABPM500', 10780000, NULL, '2021-11-02 23:39:00'),
(2, 'APRON (Full) Per 10pcss', 3245000, '0000-00-00 00:00:00', '2021-11-02 23:56:37'),
(3, 'APRON (Half) Per 10pcs', 583000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'BABY DIGIT-ONE', 1551000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'BABY DIGIT-ONE + TAS', 1760000, '0000-00-00 00:00:00', '2021-11-02 21:53:20'),
(6, 'BABY ONE', 11330000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'BB-200', 191400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'BB-200 + UPS', 186450000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'BL-50B', 51700000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'BL-50B + UPS', 66550000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'BN-100', 152900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'BN-100 + UPS', 141130000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'BODY FAT PRO', 1760000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'BR-100', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'BT-100 (BIG TROLLEY)', 231000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'BT-100 (SMALL TROLLEY)', 193600000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'CENTRAL MONITOR PM-9000+ + PC + INSTALASI', 25740000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'CMS-600 PLUS', 106590000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'CMS-600 PLUS + LINEAR PROBE', 145200000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'CMS-600 PLUS + PRINTER', 125950000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'CMS-600 PLUS + PRINTER + LINEAR PROBE', 164780000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'CMS-600 PLUS + PRINTER + LINEAR PROBE + TROLLEY + UPS', 193600000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'CMS-600 PLUS + PRINTER + TROLLEY', 135740000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'CMS-600 PLUS + PRINTER + TROLLEY + UPS', 155100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'CMS-600 PLUS + TROLLEY', 116270000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'CMS-600 PLUS + UPS', 119900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'DIGIT ONE', 379500, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'DIGIT-ONE BABY', 2904000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'DIGIT-ONE BABY + TAS', 3619000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'DIGIT-PRO', 671000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'DIGIT-PRO BMI + BODY FAT', 1012000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'DIGIT-PRO IDA', 1100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'DP1', 3993000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'DP1 + TELE', 6600000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'DS-PRO100', 43626000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'DS-PRO100 + TROLLEY', 64779000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'ECG-100G', 11550000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'ECG-100G + TROLLEY', 19250000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'ECG-100G + TROLLEY + UPS', 36080000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'ECG-100G + UPS', 28050000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'ECG-1200 MED', 231990000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'ECG-1200 MED + TROLLEY', 239800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'ECG-1200 MED + TROLLEY + UPS', 256300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'ECG-1200 MED + UPS', 248270000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'ECG-1200G', 39380000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'ECG-1200G + TROLLEY', 46750000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'ECG-1200G + TROLLEY + UPS', 69740000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'ECG-1200G + UPS', 50600000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'ECG-1800 MED', 284790000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'ECG-1800 MED + TROLLEY', 293040000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'ECG-1800 MED + TROLLEY + UPS', 308000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'ECG-1800 MED + UPS', 298100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'ECG-300G', 31834000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'ECG-300G + TROLLEY', 40095000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'ECG-300G + TROLLEY + UPS', 57420000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'ECG-300G + UPS', 44330000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'END-1 (DUA FUNGSI)', 9020000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'END-1 (DUA FUNGSI) + BACKUP POWER', 26400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'END-1 (DUA FUNGSI) + TROLLEY', 17050000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'END-1 (DUA FUNGSI) + TROLLEY + BACKUP POWER', 34760000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'END-1 (SATU FUNGSI)', 7799000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'END-1 (SATU FUNGSI) + BACKUP POWER', 24640000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'END-1 (SATU FUNGSI) + TROLLEY', 15620000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'END-1 (SATU FUNGSI) + TROLLEY + BACKUP POWER', 31900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'END-1 (TIGA FUNGSI)', 11660000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'END-1 (TIGA FUNGSI) + BACKUP POWER', 26620000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'END-1 (TIGA FUNGSI) +TROLLEY', 18260000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'END-1 (TIGA FUNGSI) +TROLLEY + BACKUP POWER', 35200000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'FOX PRO', 20460000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'FOX-2', 836000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'FOX-3', 3300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'FOX-BABY', 3300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'GET 338 UO', 3454000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'GET 338 UO + TROLLEY', 11550000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'GET 338 UO + TROLLEY + UPS', 20350000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'GET 338 UO + UPS', 11550000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'GET-160', 86900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'GET-160 + UPS', 110000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'GET-80C', 16720000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'GET-80C + UPS', 31900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'ISOLATION GOWN-01', 4235000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'KJF-B100', 64900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'KJF-B100 + UPS', 81400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'KJF-Y100', 80960000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'KJF-Y100 + UPS', 99000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'MAP 380', 15070000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'MAP 380 + UPS', 28160000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'MATERNAL MED-02 + TROLLEY', 180400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'MATERNAL MED-02 + TROLLEY + UPS', 196900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'MATERNAL MED-02 + UPS', 188100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'MED-S100', 43626000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'MED-S100 + TROLLEY MEJA', 65340000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'MED-S100 + TROLLEY TIANG', 59510000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'MED-S200', 79420000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'MED-S200 + TROLLEY', 101530000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'MED-S200 + TROLLEY + UPS', 118800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'MED-S200 + UPS', 110000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'MED-S400', 31020000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'MED-S400 + TROLLEY MEJA', 50600000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'MED-S400 + TROLLEY TIANG', 44880000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'MEL-02', 7557000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'MFT-01', 1804000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'MFV-01', 9680000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'MFV-01 + BACKUP POWER', 20350000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'MOC-A', 27841000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'MOC-A + PIPING + OUTLET', 74415000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'MOC-A + PIPING + OUTLET + UPS', 90970000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'MOC-A + UPS', 46783000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'MOL-01', 106656000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'MOL-01 + UPS', 112970000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'MOL-02', 123200000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'MTB-2MTR', 105600, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'MTR-BABY 001', 990000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'ONE STATION + TAS', 4103000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'PA-DC001', 260700, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'PM PRO-1', 267960000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'PM PRO-1 + TROLLEY', 276980000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'PM PRO-1 + TROLLEY + UPS', 294800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'PM PRO-1 + UPS', 287100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'PM PRO-2', 110220000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'PM PRO-2 + TROLLEY', 116600000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'PM PRO-2 + TROLLEY + UPS', 134200000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'PM PRO-2 + UPS', 125400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'PM PRO-3', 89100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'PM PRO-3 + TROLLEY', 95260000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'PM PRO-3 + TROLLEY + UPS', 110000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'PM PRO-3 + UPS', 102300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'PM50', 7788000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'PM-9000+', 30250000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'PM-9000+ + TROLLEY', 40964000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'PM-9000+ + TROLLEY + UPS', 52140000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'PM-9000+ + UPS', 58850000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'PRA-ONE', 152900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'PRA-ONE + PRINTER COLOR', 155650000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'PRA-ONE + THERMAL PRINTER', 166100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'PRA-ONE + TROLLEY', 178200000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'PRA-ONE + TROLLEY +  THERMAL & PRINTER COLOR + LINEAR PROBE', 269500000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 'PRA-ONE + TROLLEY + PRINTER COLOR', 201300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'PRA-ONE + TROLLEY + PRINTER COLOR + LINEAR & TRANSVAGINAL PROBE', 316800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 'PRA-ONE + TROLLEY + THERMAL &  PRINTER COLOR + LINEAR & TRANSVAGINAL PROBE + UPS', 294800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 'PRA-ONE + TROLLEY + THERMAL & PRINTER COLOR + LINEAR PROBE + UPS', 256960000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 'PRA-ONE + TROLLEY + THERMAL & PRINTER COLOR + UPS', 216700000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'PRA-ONE + TROLLEY + UPS', 173800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'PRO SCANNER CONVEX ARRAY', 79200000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'PRO SCANNER LINEAR ARRAY', 88000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'PRO SCANNER PHASED ARRAY', 99000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'PROMAX', 544500000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'PROMAX + CONVEX & LINEAR PROBE', 608300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'PROMAX + CONVEX & PHASED ARRAY PROBE', 619300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'PROMAX + CONVEX & TRANSVAGINAL PROBE', 619300000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'PROMAX + CONVEX, LINEAR & VOLUME PROBE', 782100000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'PROMAX + LINEAR PROBE', 561000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'PROMAX + TROLLEY + PRINTER COLOR + LINEAR, TRANSVAGINAL & VOLUME PROBE', 1199000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'PROMAX + TROLLEY + THERMAL & PRINTER COLOR', 587400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'PROMAX + TROLLEY + THERMAL COLOR PRINTER', 609400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'PROMIST 1', 1232000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'PROMIST 2', 798600, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'PROMIST 3', 4356000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 'PROMIST 3 + BACKUP POWER', 9119000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 'PROMIST 3 + TROLLEY', 12848000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 'PROMIST 3 + TROLLEY + BACKUP POWER', 17600000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 'PROTECTIVE SUIT-01', 6490000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 'PTB-2MTR', 1210000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'ROLL PAPER FOR ECG-1200G', 1738000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 'ROLL PAPER FOR ECG-300G', 775500, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 'SAFETY GOGGLE-01', 297000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 'SHOE COVER', 759000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 'SONOTRAX MED-01 + TROLLEY', 96800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 'SONOTRAX MED-01 + TROLLEY + UPS', 117700000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 'SONOTRAX MED-01 + UPS', 108900000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 'SONOTRAX PRO', 11330000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 'SONOTRAX PRO + TROLLEY', 20570000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 'SONOTRAX PRO + TROLLEY + UPS', 39050000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 'SONOTRAX PRO + UPS', 30360000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 'SONOTRAX PRO2', 22990000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 'SONOTRAX PRO2 + TROLLEY', 32780000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 'SONOTRAX PRO2 + TROLLEY + UPS', 50875000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 'SONOTRAX PRO2 + UPS', 41800000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 'SONOTRAX-B', 2475000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 'SONOTRAX-C', 6787000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 'SP10', 8965000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 'TENSIONE', 4290000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 'TENSIONE + TROLLEY + POWER ADAPTOR', 8910000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 'THERM ONE', 1397000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 'TOP-308', 334400000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 'TOP-308 + UPS', 339350000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 'TS-5830', 189200000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 'TS-5830 + UPS', 213290000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 'TS-8830', 247390000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 'TS-8830 + UPS', 264000000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 'ULTRA MIST', 2904000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 'ULTRA MIST + BACKUP POWER', 7700000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 'ULTRA MIST + TROLLEY', 11968000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 'ULTRA MIST + TROLLEY + BACKUP POWER', 16753000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 'UV-40W', 5236000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 'UV-40W + WATER FILTER', 7557000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 'UV-40W + WATER FILTER + WASTAFEL', 21450000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 'ZTP 300', 44550000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 'ZTP 300 + UPS', 57640000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 'ZTP80AS-UPGRADE', 9680000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 'ZTP80AS-UPGRADE + UPS', 25960000, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 'asd', 33333, '2021-10-25 20:31:15', '2021-10-25 20:31:15'),
(209, 'FOX -1 + BABY ONE', 2500000, '2021-10-25 20:34:57', '2021-10-25 20:34:57'),
(210, 'ABPM50 + TAS', 111111111, '2021-10-26 02:27:45', '2021-10-26 02:27:45'),
(211, '42424', 424242, '2021-10-28 02:59:21', '2021-10-28 02:59:21'),
(212, 'Aku', 12300, '2021-11-02 23:59:21', '2021-11-02 23:59:21'),
(213, 'Anyar', 123, '2021-11-03 00:00:03', '2021-11-03 00:00:03'),
(214, 'Aku99', 1, '2021-11-03 00:05:45', '2021-11-03 00:19:07'),
(215, 'Tesku', 456, '2021-11-03 03:03:58', '2021-11-03 03:03:58'),
(216, 'Dela Tes 1', 4103000, '2021-11-04 00:01:50', '2021-11-04 00:01:50'),
(217, 'Dela Tes 2', 4103000, '2021-11-04 00:03:57', '2021-11-04 00:03:57'),
(218, 'Dela Tes 3', 4103000, '2021-11-04 00:06:47', '2021-11-04 00:06:47'),
(219, 'Dela Tes 4', 2313131, '2021-11-04 00:10:42', '2021-11-04 00:10:42'),
(220, 'Dela Tes 5', 4103000, '2021-11-04 00:17:07', '2021-11-04 00:17:07'),
(221, 'Dela Tes 6', 4103000, '2021-11-04 00:18:43', '2021-11-04 00:18:43'),
(222, 'Dela Tes 7', 4242424, '2021-11-04 00:20:37', '2021-11-04 00:20:37'),
(223, 'rewew', 34242, '2021-11-04 00:28:01', '2021-11-04 00:28:01'),
(224, 'rewew', 34242, '2021-11-04 00:28:28', '2021-11-04 00:28:28'),
(225, 'dela1', 1397000, '2021-11-04 00:45:41', '2021-11-04 00:45:41'),
(226, 'aaa', 858585858, '2021-11-05 00:41:35', '2021-11-05 00:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `so` varchar(30) DEFAULT NULL,
  `no_po` varchar(255) NOT NULL,
  `tgl_po` date NOT NULL,
  `no_do` varchar(255) DEFAULT NULL,
  `tgl_do` date DEFAULT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `so`, `no_po`, `tgl_po`, `no_do`, `tgl_do`, `ket`, `created_at`, `updated_at`) VALUES
(54, 'SO/EKAT/XI/2021/1', 'EMIIINDO123/XXI', '2021-07-01', NULL, NULL, NULL, '2021-11-07 20:53:18', '2021-11-07 20:53:18'),
(56, 'SO/SPA/XI/2021/1', 'PO1230444', '2021-09-09', NULL, NULL, NULL, '2021-11-07 20:55:52', '2021-11-07 20:55:52'),
(57, 'SO/SPB/XI/2021/1', 'PO8889', '2021-11-17', NULL, NULL, NULL, '2021-11-07 20:57:19', '2021-11-07 20:57:19'),
(58, 'SO/SPA/XI/2021/2', 'PO5555', '2021-11-08', 'DO5555', '2021-11-18', NULL, '2021-11-07 20:58:25', '2021-11-07 20:58:25');

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
(2, 1, 'ELITECH ', 'APRON (Full)', 'MEDICAL APRON', 'MEDICAL APRON', 'BOX', '11603021706', '', '1', NULL, NULL),
(3, 1, 'ELITECH ', 'APRON (Half)', 'MEDICAL APRON', 'MEDICAL APRON', 'BOX', '11603021706', '', '1', NULL, NULL),
(4, 1, 'ELITECH ', 'ASL300', 'AIR STERILIZER AND PURIFIER', 'AIR STERILIZER AND PURIFIER', 'Unit', '', '', '1', NULL, NULL),
(5, 1, 'ELITECH ', 'BABY DIGIT-ONE', 'TIMBANGAN BAYI MEKANIK', 'TIMBANGAN BAYI MEKANIK', 'Unit', '10901410295', '', '1', NULL, NULL),
(6, 1, 'ELITECH ', 'BABY ONE', 'BABY SCALE', 'BABY SCALE', 'Unit', '10901318002', '', '1', NULL, NULL),
(7, 1, 'ELITECH ', 'BACKUP POWER', 'BACKUP POWER', 'BACKUP POWER', 'Unit', '', '', '1', NULL, NULL),
(8, 1, 'ELITECH ', 'BATERAI MEL-02', 'BATERAI', 'BATERAI', 'Unit', '', '', '1', NULL, NULL),
(9, 1, 'ELITECH ', 'BB-200', 'INFANT INCUBATOR', 'INFANT INCUBATOR', 'Unit', '20903900076', '', '1', NULL, NULL),
(10, 1, 'ELITECH ', 'BL-50', 'INFANT PHOTOTHERAPY UNIT', 'INFANT PHOTOTHERAPY UNIT', 'Unit', '20903900073', '', '1', NULL, NULL),
(11, 1, 'ELITECH ', 'BL-50B', 'INFANT PHOTOTHERAPY UNIT', 'INFANT PHOTOTHERAPY UNIT', 'Unit', '20903900073', '', '1', NULL, NULL),
(12, 1, 'ELITECH ', 'BN-100', 'INFANT WARMER', 'INFANT WARMER', 'Unit', '20903900074', '', '1', NULL, NULL),
(13, 1, 'ELITECH ', 'BODY FAT', 'BODY FAT', 'BODY FAT', 'Unit', '', '', '1', NULL, NULL),
(14, 1, 'ELITECH ', 'BODY FAT PRO', 'DIGITAL SCALE / TIMBANGAN DIGITAL BMI ', 'DIGITAL SCALE / TIMBANGAN DIGITAL BMI ', 'Unit', '10901911085', '', '1', NULL, NULL),
(15, 1, 'RGB', 'BPM001 ', 'BLOOD PRESSURE MONITOR', 'BLOOD PRESSURE MONITOR', 'Unit', '20501910739', '', '1', NULL, NULL),
(16, 1, 'RGB', 'BPM002 ', 'BLOOD PRESSURE MONITOR', 'BLOOD PRESSURE MONITOR', 'Unit', '20501910740', '', '1', NULL, NULL),
(17, 1, 'ELITECH ', 'BR-100', 'INFANT RESUSCITATOR', 'INFANT RESUSCITATOR', 'Unit', '20403022754', '', '1', NULL, NULL),
(18, 1, 'ELITECH ', 'BT-100 (Big)', 'INFANT INCUBATOR TRANSPORT', 'INFANT INCUBATOR TRANSPORT', 'Unit', '20902710901', '', '1', NULL, NULL),
(19, 1, 'ELITECH ', 'BT-100 (Small)', 'INFANT INCUBATOR TRANSPORT', 'INFANT INCUBATOR TRANSPORT', 'Unit', '20902710901', '', '1', NULL, NULL),
(20, 1, 'ELITECH ', 'CENTRAL MONITOR PM-9000+ + PC + INSTALASI', 'CENTRAL MONITOR', 'CENTRAL MONITOR', 'Unit', '20903900075', '', '1', NULL, NULL),
(21, 1, 'ELITECH ', 'CMS-600 PLUS', 'B-ULTRASOUND DIAGNOSTIC SYSTEM', 'B-ULTRASOUND DIAGNOSTIC SYSTEM', 'Unit', '21102900256', '', '1', NULL, NULL),
(22, 1, 'ELITECH ', 'CONVEX PROBE', 'CONVEX PROBE', 'CONVEX PROBE', 'Unit', '', '', '1', NULL, NULL),
(23, 1, 'ELITECH ', 'DIGIT ONE', 'PATIENT SCALE', 'PATIENT SCALE', 'Unit', '10901318000', '', '1', NULL, NULL),
(24, 1, 'ELITECH ', 'DIGIT-ONE BABY', 'TIMBANGAN BAYI DIGITAL', 'TIMBANGAN BAYI DIGITAL', 'Unit', '10901410291', '', '1', NULL, NULL),
(25, 1, 'ELITECH ', 'DIGIT-PRO', 'PATIENT SCALE', 'PATIENT SCALE', 'Unit', '10901318001', '', '1', NULL, NULL),
(26, 1, 'ELITECH ', 'DIGIT-PRO BMI', 'PATIENT SCALE', 'PATIENT SCALE', 'Unit', '10901910723', '', '1', NULL, NULL),
(27, 1, 'ELITECH ', 'DIGIT-PRO IDA', 'DIGITAL SCALE / TIMBANGAN DIGITAL IBU & ANAK', 'DIGITAL SCALE / TIMBANGAN DIGITAL IBU & ANAK', 'Unit', '10901910529', '', '1', NULL, NULL),
(28, 1, 'ELITECH ', 'DP1', 'ULTRASONIC POCKET DOPPLER', 'ULTRASONIC POCKET DOPPLER', 'Unit', '21101810460', '', '1', NULL, NULL),
(29, 1, 'ELITECH ', 'DP1 + TELE', 'ULTRASONIC POCKET DOPPLER', 'ULTRASONIC POCKET DOPPLER', 'Unit', '21101810460', '', '1', NULL, NULL),
(30, 1, 'ELITECH ', 'DS-PRO100', 'PORTABLE SPIROMETER', 'PORTABLE SPIROMETER', 'Unit', '20401710665', '', '1', NULL, NULL),
(31, 1, 'ELITECH ', 'ECG-100G', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502900072', '', '1', NULL, NULL),
(32, 1, 'ELITECH ', 'ECG-1200 MED', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502810371', '', '1', NULL, NULL),
(33, 1, 'ELITECH ', 'ECG-1200G', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502310189', '', '1', NULL, NULL),
(34, 1, 'ELITECH ', 'ECG-1800 MED', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '20502810372', '', '1', NULL, NULL),
(35, 1, 'ELITECH ', 'ECG-300G', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '21102900255', '', '1', NULL, NULL),
(36, 1, 'ELITECH ', 'ECG-300G TFT', 'ELECTROCARDIOGRAPH', 'ELECTROCARDIOGRAPH', 'Unit', '21102900255', '', '1', NULL, NULL),
(37, 1, 'ELITECH ', 'END-1 (Dua Fungsi)', 'MEDICAL DESTROYER', 'MEDICAL DESTROYER', 'Unit', '20902210075', '', '1', NULL, NULL),
(38, 1, 'ELITECH ', 'END-1 (Satu Fungsi)', 'MEDICAL DESTROYER', 'MEDICAL DESTROYER', 'Unit', '20902210075', '', '1', NULL, NULL),
(39, 1, 'ELITECH ', 'END-1 (Tiga Fungsi)', 'MEDICAL DESTROYER', 'MEDICAL DESTROYER', 'Unit', '20902210075', '', '1', NULL, NULL),
(40, 1, 'ELITECH ', 'ESA 2000W', 'PROGRAMMABLE AUTO SAFETY TESTER FOR MEDICAL APPARATUS', 'PROGRAMMABLE AUTO SAFETY TESTER FOR MEDICAL APPARATUS', 'Unit', 'FR.03.02/VA/2190/2020', '', '1', NULL, NULL),
(41, 1, 'ELITECH ', 'ESA 500W', 'PROGRAMMABLE AUTO SAFETY TESTER FOR MEDICAL APPARATUS', 'PROGRAMMABLE AUTO SAFETY TESTER FOR MEDICAL APPARATUS', 'Unit', 'FR.03.02/VA/2191/2020', '', '1', NULL, NULL),
(42, 1, 'ELITECH ', 'FACE SHIELD ELITECH + 5 Visor', 'MEDICAL FACE SHIELD', 'MEDICAL FACE SHIELD', 'Unit', '', '', '1', NULL, NULL),
(43, 1, 'ELITECH ', 'FACE SHIELD HELM KUNING', 'MEDICAL FACE SHIELD', 'MEDICAL FACE SHIELD', 'Unit', '', '', '1', NULL, NULL),
(44, 1, 'ELITECH ', 'FACE SHIELD KACA MATA (Nagita)', 'MEDICAL FACE SHIELD', 'MEDICAL FACE SHIELD', 'Unit', '', '', '1', NULL, NULL),
(45, 1, 'ELITECH ', 'FACE SHIELD MODERN', 'MEDICAL FACE SHIELD', 'MEDICAL FACE SHIELD', 'Unit', '', '', '1', NULL, NULL),
(46, 1, 'ELITECH ', 'FACE SHIELD SWING', 'MEDICAL FACE SHIELD', 'MEDICAL FACE SHIELD', 'Unit', '', '', '1', NULL, NULL),
(47, 1, 'ELITECH ', 'FILTER AIR 3 MEDIA', 'FILTER AIR', 'FILTER AIR', 'Unit', '', '', '1', NULL, NULL),
(48, 1, 'ELITECH ', 'FOX PRO', 'HANDHELD PULSE OXIMETER', 'HANDHELD PULSE OXIMETER', 'Unit', '20502910952', '', '1', NULL, NULL),
(49, 1, 'ELITECH ', 'FOX-1', 'PULSE OXIMETER', 'PULSE OXIMETER', 'Unit', '', '', '1', NULL, NULL),
(50, 1, 'ELITECH ', 'FOX-2', 'PULSE OXIMETER', 'PULSE OXIMETER', 'Unit', '20502210102', '', '1', NULL, NULL),
(51, 1, 'ELITECH ', 'FOX-3', 'PULSE OXIMETER', 'PULSE OXIMETER', 'Unit', '20502210101', '', '1', NULL, NULL),
(52, 1, 'ELITECH ', 'FOX-BABY', 'PULSE OXIMETER', 'PULSE OXIMETER', 'Unit', '20502318005', '', '1', NULL, NULL),
(53, 1, 'ELITECH ', 'FRAME FACE SHIELD ELITECH (Tanpa Visor)', 'FRAME MEDICAL FACE SHIELD ELITECH (Tanpa Visor)', 'FRAME MEDICAL FACE SHIELD ELITECH (Tanpa Visor)', 'Unit', '', '', '1', NULL, NULL),
(54, 1, 'ELITECH ', 'GET 100E UV', 'STERILISATOR ELITECH GET 100E UV', 'STERILISATOR ELITECH GET 100E UV', 'Unit', '', '', '1', NULL, NULL),
(55, 1, 'ELITECH ', 'GET 338 UO', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800291', '', '1', NULL, NULL),
(56, 1, 'ELITECH ', 'GET 338 UV', 'MEDICAL UV STERILIZER ', 'MEDICAL UV STERILIZER ', 'Unit', '', '', '1', NULL, NULL),
(57, 1, 'ELITECH ', 'GET-160', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800287', '', '1', NULL, NULL),
(58, 1, 'ELITECH ', 'GET-80C', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800282', '', '1', NULL, NULL),
(59, 1, 'ELITECH ', 'HAZMAT COVER ALL', 'MEDICAL ISOLATION', 'MEDICAL ISOLATION', 'Unit', '', '', '1', NULL, NULL),
(60, 1, 'ELITECH ', 'ISOLATION GOWN-01', 'MEDICAL ISOLATION GOWN', 'MEDICAL ISOLATION GOWN', 'Box', '21603020315', '', '1', NULL, NULL),
(61, 1, 'ELITECH ', 'KJF-B100', 'MEDICAL PLASMA AIR STERILIZER', 'MEDICAL PLASMA AIR STERILIZER', 'Unit', '20903020466', '', '1', NULL, NULL),
(62, 1, 'ELITECH ', 'KJF-Y100', 'MEDICAL PLASMA AIR STERILIZER', 'MEDICAL PLASMA AIR STERILIZER', 'Unit', '20903020450', '', '1', NULL, NULL),
(63, 1, 'ELITECH ', 'KJG200', 'AIR STERILIZER AND PURIFIER', 'AIR STERILIZER AND PURIFIER', 'Unit', '', '', '1', NULL, NULL),
(64, 1, 'ELITECH ', 'LAMPU LED MEL-01', 'LAMPU LED ', 'LAMPU LED ', 'Unit', '', '', '1', NULL, NULL),
(65, 1, 'ELITECH ', 'LAMPU LED MOL-02 (2 LAMPU)', 'LAMPU LED ', 'LAMPU LED ', 'Unit', '', '', '1', NULL, NULL),
(66, 1, 'ELITECH ', 'LINEAR PROBE', 'LINEAR PROBE', 'LINEAR PROBE', 'Unit', '', '', '1', NULL, NULL),
(67, 1, 'ELITECH ', 'LINEAR PROBE', 'LINEAR PROBE', 'LINEAR PROBE', 'Unit', '', '', '1', NULL, NULL),
(68, 1, 'ELITECH ', 'MAP 380', 'MEDICAL AIR PURIFIER', 'MEDICAL AIR PURIFIER', 'Unit', '20902020924', '', '1', NULL, NULL),
(69, 1, 'ELITECH ', 'MASKER DISPOSABLE 3 PLY', 'DISPOSABLE MASK', 'DISPOSABLE MASK', 'Unit', '', '', '1', NULL, NULL),
(70, 1, 'ELITECH ', 'MASKER KN-95', 'PARTICULATE RESPIRATOR MASK', 'PARTICULATE RESPIRATOR MASK', 'Unit', '', '', '1', NULL, NULL),
(71, 1, 'ELITECH ', 'MASKER N-95', 'SURGICAL MASK', 'SURGICAL MASK', 'Unit', '', '', '1', NULL, NULL),
(72, 1, 'ELITECH ', 'MATERNAL MED-02', 'FETAL MONITOR', 'FETAL MONITOR', 'Unit', '21101710864', '', '1', NULL, NULL),
(73, 1, 'ELITECH ', 'MED-S100', 'SPO2 SIMULATOR', 'SPO2 SIMULATOR', 'Unit', '20401710856', '', '1', NULL, NULL),
(74, 1, 'ELITECH ', 'MED-S200', 'NIBP SIMULATOR', 'NIBP SIMULATOR', 'Unit', '20501710666', '', '1', NULL, NULL),
(75, 1, 'ELITECH ', 'MED-S400', 'PATIENT SIMULATOR', 'PATIENT SIMULATOR', 'Unit', '20502710662', '', '1', NULL, NULL),
(76, 1, 'ELITECH ', 'MEL-02', 'LAMPU PERIKSA LED', 'LAMPU PERIKSA LED', 'Unit', '10903710660', '', '1', NULL, NULL),
(77, 1, 'ELITECH ', 'MFT-01', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'Unit', '20901020234', '', '1', NULL, NULL),
(78, 1, 'ELITECH ', 'MFV-01', 'X-RAY FILM VIEWER', 'X-RAY FILM VIEWER', 'Unit', '21501810001', '', '1', NULL, NULL),
(79, 1, 'ELITECH ', 'MOC-A', 'OXYGEN CONCENTRATOR', 'OXYGEN CONCENTRATOR', 'Unit', '20403510582', '', '1', NULL, NULL),
(80, 1, 'ELITECH ', 'MOC-D', 'OXYGEN CONCENTRATOR', 'OXYGEN CONCENTRATOR', 'Unit', '20403121586', '', '1', NULL, NULL),
(81, 1, 'ELITECH ', 'MOL-01', 'LAMPU OPERASI LED', 'LAMPU OPERASI LED', 'Unit', '21603710667', '', '1', NULL, NULL),
(82, 1, 'ELITECH ', 'MOL-02', 'LAMPU OPERASI LED', 'LAMPU OPERASI LED', 'Unit', '21603710788', '', '1', NULL, NULL),
(83, 1, 'ELITECH ', 'MTB-2MTR', 'METERAN PENGUKUR TINGGI BADAN', 'METERAN PENGUKUR TINGGI BADAN', 'Unit', '10901410291', '', '1', NULL, NULL),
(84, 1, 'ELITECH ', 'MTR-BABY 001', 'PENGUKUR PANJANG BAYI', 'PENGUKUR PANJANG BAYI', 'Unit', '10901410295', '', '1', NULL, NULL),
(85, 1, 'ELITECH ', 'OUTLET', 'OUTLET', 'BACKUP POWER', 'Unit', '', '', '1', NULL, NULL),
(86, 1, 'ELITECH ', 'PA-DC001', 'POWER ADAPTOR', 'POWER ADAPTOR', 'Unit', '10901410291', '', '1', NULL, NULL),
(87, 1, 'ELITECH ', 'PHASED ARRAY PROBE', 'PHASED ARRAY PROBE', 'PHASED ARRAY PROBE', 'Unit', '', '', '1', NULL, NULL),
(88, 1, 'ELITECH ', 'PIPING', 'PIPING', 'PIPING', 'Unit', '', '', '1', NULL, NULL),
(89, 1, 'ELITECH ', 'PM PRO-1', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20502810355', '', '1', NULL, NULL),
(90, 1, 'ELITECH ', 'PM PRO-2', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20502810356', '', '1', NULL, NULL),
(91, 1, 'ELITECH ', 'PM PRO-3', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20502020925', '', '1', NULL, NULL),
(92, 1, 'ELITECH ', 'PM50', 'SPO2 MONITOR', 'SPO2 MONITOR', 'Unit', '20502510583', '', '1', NULL, NULL),
(93, 1, 'MENTOR', 'PM-6500', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20502310188', '', '1', NULL, NULL),
(94, 1, 'ELITECH ', 'PM-9000+', 'PATIENT MONITOR', 'PATIENT MONITOR', 'Unit', '20903900075', '', '1', NULL, NULL),
(95, 1, 'MENTOR', 'PM-VS5000', 'VITAL SIGN MONITOR', 'VITAL SIGN MONITOR', 'Unit', '20501310187', '', '1', NULL, NULL),
(96, 1, 'ELITECH ', 'POWER ADAPTOR', 'POWER ADAPTOR', 'POWER ADAPTOR', 'Unit', '', '', '1', NULL, NULL),
(97, 1, 'ELITECH ', 'PRA-ONE', 'DIGITAL USG MONITOR', 'DIGITAL USG MONITOR', 'Unit', '21102410010', '', '1', NULL, NULL),
(98, 1, 'ELITECH ', 'PRINTER', 'PRINTER', 'PRINTER', 'Unit', '', '', '1', NULL, NULL),
(99, 1, 'ELITECH ', 'PRO SCANNER CONVEX ARRAY', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'Unit', '21101020853', '', '1', NULL, NULL),
(100, 1, 'ELITECH ', 'PRO SCANNER LINEAR ARRAY', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'Unit', '21101020853', '', '1', NULL, NULL),
(101, 1, 'ELITECH ', 'PRO SCANNER PHASED ARRAY', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'HANDHELD USG PROBE SCANNER / ULTRASONOGRAPH', 'Unit', '21101020853', '', '1', NULL, NULL),
(102, 1, 'ELITECH ', 'PROMAX', 'USG 3D/4D COLOR DOPPLER ULTRASOUND', 'USG 3D/4D COLOR DOPPLER ULTRASOUND', 'Unit', '21102410011', '', '1', NULL, NULL),
(103, 1, 'ELITECH ', 'PROMIST 1', 'MINI COMPRESSOR NEBULIZER', 'MINI COMPRESSOR NEBULIZER', 'Unit', '20403318003', '', '1', NULL, NULL),
(104, 1, 'ELITECH ', 'PROMIST 2', 'MEDICAL NEBULIZER', 'MEDICAL NEBULIZER', 'Unit', '20403710512', '', '1', NULL, NULL),
(105, 1, 'ELITECH ', 'PROMIST 3', 'MEDICAL NEBULIZER', 'MEDICAL NEBULIZER', 'Unit', '20403710661', '', '1', NULL, NULL),
(106, 1, 'ELITECH ', 'PROTECTIVE SUIT-01', 'MEDICAL PROTECTIVE SUIT FOR OPERATING ROOM', 'MEDICAL PROTECTIVE SUIT FOR OPERATING ROOM', 'Box', '21603020348', '', '1', NULL, NULL),
(107, 1, 'ELITECH ', 'PTB-2in1 ', 'PENGUKUR PANJANG BAYI DAN PENGUKUR TINGGI BADAN DEWASA', 'PENGUKUR PANJANG BAYI DAN PENGUKUR TINGGI BADAN DEWASA', 'Unit', '10901121350', '', '1', NULL, NULL),
(108, 1, 'ELITECH ', 'PTB-2MTR', 'PENGGARIS PENGUKUR TINGGI BADAN', 'PENGGARIS PENGUKUR TINGGI BADAN', 'Unit', '10901410291', '', '1', NULL, NULL),
(109, 1, 'ELITECH ', 'RGB MEDICAL Electronic Thermometer', 'THERMOMETER', 'THERMOMETER', 'Unit', '', '', '1', NULL, NULL),
(110, 1, 'ELITECH ', 'RLD 68-C  (Big)', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '', '', '1', NULL, NULL),
(111, 1, 'ELITECH ', 'RLD 68-C (Small)', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '', '', '1', NULL, NULL),
(112, 1, 'ELITECH ', 'ROLL PAPER ECG-100G ', 'ROLL PAPER', 'ROLL PAPER', 'BOX', '', '', '1', NULL, NULL),
(113, 1, 'ELITECH ', 'ROLL PAPER ECG-1200G', 'KERTAS ECG / ROLL PAPER', 'KERTAS ECG / ROLL PAPER', 'Pack', '20502310189', '', '1', NULL, NULL),
(114, 1, 'ELITECH ', 'ROLL PAPER ECG-300G', 'KERTAS ECG / ROLL PAPER', 'KERTAS ECG / ROLL PAPER', 'Pack', '21102900255', '', '1', NULL, NULL),
(115, 1, 'ELITECH ', 'SAFETY GOGGLE-01', 'MEDICAL SAFETY GOGGLE', 'MEDICAL SAFETY GOGGLE', 'Unit', '11603020313', '', '1', NULL, NULL),
(116, 1, 'ELITECH ', 'SEPATU BOOT', 'MEDICAL ISOLATION ', 'MEDICAL ISOLATION ', 'Unit', '', '', '1', NULL, NULL),
(117, 1, 'ELITECH ', 'SET ANTROPOMETRI KIT ', 'TAS PENYIMPANAN ALAT ANTROPOMETRI', 'TAS PENYIMPANAN ALAT ANTROPOMETRI', 'Unit', 'FR.03.02/VA/4189/2019', '', '1', NULL, NULL),
(118, 1, 'ELITECH ', 'SHOE COVER', 'MEDICAL SHOE COVER', 'MEDICAL SHOE COVER', 'Box', '11603021451', '', '1', NULL, NULL),
(119, 1, 'ELITECH ', 'SHOE COVER PANJANG', 'MEDICAL SHOE COVER', 'MEDICAL SHOE COVER', 'Unit', '', '', '1', NULL, NULL),
(120, 1, 'ELITECH ', 'SONOTRAX MED-01', 'FETAL MONITOR', 'FETAL MONITOR', 'Unit', '21101710857', '', '1', NULL, NULL),
(121, 1, 'ELITECH ', 'SONOTRAX PRO', 'DESKTOP FETAL DOPPLER / FETAL MONITOR', 'DESKTOP FETAL DOPPLER / FETAL MONITOR', 'Unit', '21101318006', '', '1', NULL, NULL),
(122, 1, 'ELITECH ', 'SONOTRAX PRO2', 'ULTRASONIC TABLE DOPPLER', 'ULTRASONIC TABLE DOPPLER', 'Unit', '21101810461', '', '1', NULL, NULL),
(123, 1, 'ELITECH ', 'SONOTRAX-A', 'POCKET FETAL DOPPLER', 'POCKET FETAL DOPPLER', 'Unit', '21102800003', '', '1', NULL, NULL),
(124, 1, 'ELITECH ', 'SONOTRAX-B', 'POCKET FETAL DOPPLER', 'POCKET FETAL DOPPLER', 'Unit', '21102800003', '', '1', NULL, NULL),
(125, 1, 'ELITECH ', 'SONOTRAX-C', 'POCKET FETAL DOPPLER', 'POCKET FETAL DOPPLER', 'Unit', '21101710077', '', '1', NULL, NULL),
(126, 1, 'ELITECH ', 'SP10', 'DIGITAL SPIROMETER DS-PRO', 'DIGITAL SPIROMETER DS-PRO', 'Unit', '20401610237', '', '1', NULL, NULL),
(127, 1, 'ELITECH ', 'SURGICAL GOWN  (Cotton Water Repellen)', 'MEDICAL SURGICAL GOWN', 'MEDICAL SURGICAL GOWN', 'Unit', '', '', '1', NULL, NULL),
(128, 1, 'ELITECH ', 'SURGICAL GOWN (100% Cotton)', 'MEDICAL SURGICAL GOWN', 'MEDICAL SURGICAL GOWN', 'Unit', '', '', '1', NULL, NULL),
(129, 1, 'ELITECH ', 'TAS ANTROPOMETRI KIT ', 'TAS', 'TAS', 'Unit', '', '', '1', NULL, NULL),
(130, 1, 'ELITECH ', 'TENSIONE', 'BLOOD PRESSURE MONITOR', 'BLOOD PRESSURE MONITOR', 'Unit', '20501318004', '', '1', NULL, NULL),
(131, 1, 'ELITECH ', 'THERM ONE', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'MEDICAL NON CONTACT FOREHEAD THERMOMETER', 'Unit', '20901020251', '', '1', NULL, NULL),
(132, 1, 'ELITECH ', 'THERMAL PRINTER', 'THERMAL PRINTER', 'THERMAL PRINTER', 'Unit', '', '', '1', NULL, NULL),
(133, 1, 'ELITECH ', 'TOP-308', 'DENTAL UNIT', 'DENTAL UNIT', 'Unit', '10605900070', '', '1', NULL, NULL),
(134, 1, 'ELITECH ', 'TRANSVAGINAL PROBE', 'TRANSVAGINAL PROBE', 'TRANSVAGINAL PROBE', 'Unit', '', '', '1', NULL, NULL),
(135, 1, 'ELITECH ', 'TROLLEY MEJA', 'TROLLEY MEJA', 'TROLLEY MEJA', 'Unit', '', '', '1', NULL, NULL),
(136, 1, 'ELITECH ', 'TROLLEY TIANG', 'TROLLEY TIANG', 'TROLLEY TIANG', 'Unit', '', '', '1', NULL, NULL),
(137, 1, 'ELITECH ', 'TROLLEY USG CMS-600', 'TROLLEY', 'TROLLEY', 'Unit', '', '', '1', NULL, NULL),
(138, 1, 'ELITECH ', 'TROLLEY USG PRAONE', 'TROLLEY', 'TROLLEY', 'Unit', '', '', '1', NULL, NULL),
(139, 1, 'ELITECH ', 'TROLLEY USG PROMAX', 'TROLLEY', 'TROLLEY', 'Unit', '', '', '1', NULL, NULL),
(140, 1, 'ELITECH ', 'TS-5830', 'DENTAL UNIT', 'DENTAL UNIT', 'Unit', '10605900071', '', '1', NULL, NULL),
(141, 1, 'ELITECH ', 'TS-8830', 'DENTAL UNIT', 'DENTAL UNIT', 'Unit', '10605810069', '', '1', NULL, NULL),
(142, 1, 'ELITECH ', 'ULTRA MIST', 'ULTRASONIC NEBULIZER', 'ULTRASONIC NEBULIZER', 'Unit', '20403710663', '', '1', NULL, NULL),
(143, 1, 'ELITECH ', 'UPS', 'UNINTERRUPTIBLE POWER SUPPLY', 'UNINTERRUPTIBLE POWER SUPPLY', 'Unit', '', '', '1', NULL, NULL),
(144, 1, 'ELITECH ', 'UV-40W', 'UV WATER STERILIZER', 'UV WATER STERILIZER', 'Unit', '20903410009', '', '1', NULL, NULL),
(145, 1, 'ELITECH ', 'VOLUME PROBE', 'VOLUME PROBE', 'VOLUME PROBE', 'Unit', '', '', '1', NULL, NULL),
(146, 1, 'ELITECH ', 'WASTAFEL', 'WASTAFEL', 'WASTAFEL', 'Unit', '', '', '1', NULL, NULL),
(147, 1, 'ELITECH ', 'WATER FILTER', 'WATER FILTER', 'WATER FILTER', 'Unit', '', '', '1', NULL, NULL),
(148, 1, 'ELITECH ', 'WATERFILTER', 'WATERFILTER', 'WATERFILTER', 'Unit', '', '', '1', NULL, NULL),
(149, 1, 'ELITECH ', 'ZTD 108C-S', 'STERILISATOR KERING ', 'STERILISATOR KERING ', 'Unit', '', '', '1', NULL, NULL),
(150, 1, 'ELITECH ', 'ZTD 88-16', 'LEMARI STERIL  ', 'LEMARI STERIL  ', 'Unit', '', '', '1', NULL, NULL),
(151, 1, 'ELITECH ', 'ZTD 88-28 ', 'LEMARI STERIL', 'LEMARI STERIL', 'Unit', '', '', '1', NULL, NULL),
(152, 1, 'ELITECH ', 'ZTP 300', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903800288', '', '1', NULL, NULL),
(153, 1, 'ELITECH ', 'ZTP 368 AS', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '', '', '1', NULL, NULL),
(154, 1, 'ELITECH ', 'ZTP 80 - ECO SS', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '', '', '1', NULL, NULL),
(155, 1, 'ELITECH ', 'ZTP-80 ECO SS UV', 'STERILISATOR KERING ', 'STERILISATOR KERING ', 'Unit', '', '', '1', NULL, NULL),
(156, 1, 'ELITECH ', 'ZTP-80 ECO UV', 'STERILISATOR KERING ', 'STERILISATOR KERING ', 'Unit', '', '', '1', NULL, NULL),
(157, 1, 'ELITECH ', 'ZTP-80A', 'LEMARI STERIL  ', 'LEMARI STERIL  ', 'Unit', '', '', '1', NULL, NULL),
(158, 1, 'ELITECH ', 'ZTP80AS-UPGRADE', 'STERILISATOR KERING', 'STERILISATOR KERING', 'Unit', '20903700359', '', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provinsi`
--

CREATE TABLE `provinsi` (
  `id` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `status` enum('1','2') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `provinsi`
--

INSERT INTO `provinsi` (`id`, `nama`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bali', '1', NULL, NULL),
(2, 'Bangka Belitung', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Banten', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Bengkulu', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'DI Yogyakarta', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'DKI Jakarta', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Gorontalo', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Jambi', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Jawa Barat', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Jawa Tengah', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Jawa Timur', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Kalimantan Barat', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Kalimantan Selatan', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Kalimantan Tengah', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Kalimantan Timur', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Kalimantan Utara', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Kepulauan Riau', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Lampung', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Maluku', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Maluku Utara', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Nanggroe Aceh Darussalam (NAD)', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Nusa Tenggara Barat (NTB)', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Nusa Tenggara Timur (NTT)', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Papua', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Papua Barat', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Riau', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Sulawesi Barat', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Sulawesi Selatan', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Sulawesi Tengah', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Sulawesi Tenggara', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Sulawesi Utara', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Sumatera Barat', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'Sumatera Selatan', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'Sumatera Utara', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `spa`
--

CREATE TABLE `spa` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `pesanan_id` int(11) DEFAULT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `log` enum('penjualan','po','qc','gudang','logistik','selesai') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `spa`
--

INSERT INTO `spa` (`id`, `customer_id`, `pesanan_id`, `ket`, `log`, `created_at`, `updated_at`) VALUES
(31, 395, 56, NULL, 'penjualan', '2021-11-07 20:55:52', '2021-11-07 20:55:52'),
(32, 38, 58, NULL, 'penjualan', '2021-11-07 20:58:25', '2021-11-07 20:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `spb`
--

CREATE TABLE `spb` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `pesanan_id` int(11) DEFAULT NULL,
  `status` enum('sepakat','negosiasi','batal') NOT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `log` enum('penjualan','po','qc','logistik','gudang','selesai') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `spb`
--

INSERT INTO `spb` (`id`, `customer_id`, `pesanan_id`, `status`, `ket`, `log`, `created_at`, `updated_at`) VALUES
(4, 413, 57, 'sepakat', NULL, 'penjualan', '2021-11-07 20:57:19', '2021-11-07 20:57:19');

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
(22, 13, 'Nur Kholidah', 'idagbj02', 'ida@gmail.com', '$2y$10$YSEicx/W7euW/3GRGI7vmeAM/Aj.bEfn.k7C5Bzddf8FR9dfe0o9W', NULL, 'online', NULL, '2021-08-18 03:09:09', '2021-08-18 03:09:09'),
(25, 15, 'Erna Cantika', 'ernalog01', 'erna@gmail.com', '$2y$10$HT.EOM9fRDb/I2EJ0YPNTeuhtsxCgXZ99Mpjep1suDE.xRcXUcGNm', NULL, NULL, NULL, '2021-11-04 21:23:57', '2021-11-04 21:23:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_provinsi` (`id_provinsi`);

--
-- Indexes for table `detail_ekatalog`
--
ALTER TABLE `detail_ekatalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualan_id` (`ekatalog_id`,`penjualan_produk_id`),
  ADD KEY `penjualan_produk_id` (`penjualan_produk_id`);

--
-- Indexes for table `detail_penjualan_produk`
--
ALTER TABLE `detail_penjualan_produk`
  ADD KEY `produk_id` (`produk_id`,`penjualan_produk_id`),
  ADD KEY `penjualan_produk_id` (`penjualan_produk_id`);

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
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `provinsi_id` (`provinsi_id`);

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
-- Indexes for table `provinsi`
--
ALTER TABLE `provinsi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spa`
--
ALTER TABLE `spa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pesanan_id` (`pesanan_id`);

--
-- Indexes for table `spb`
--
ALTER TABLE `spb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pesanan_id` (`pesanan_id`);

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
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=476;

--
-- AUTO_INCREMENT for table `detail_ekatalog`
--
ALTER TABLE `detail_ekatalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `detail_spa`
--
ALTER TABLE `detail_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `detail_spb`
--
ALTER TABLE `detail_spb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ekatalog`
--
ALTER TABLE `ekatalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gdg_barang_jadi`
--
ALTER TABLE `gdg_barang_jadi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `kelompok_produk`
--
ALTER TABLE `kelompok_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `noseri_barang_jadi`
--
ALTER TABLE `noseri_barang_jadi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penjualan_produk`
--
ALTER TABLE `penjualan_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `provinsi`
--
ALTER TABLE `provinsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `spa`
--
ALTER TABLE `spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `spb`
--
ALTER TABLE `spb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`id_provinsi`) REFERENCES `provinsi` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detail_ekatalog`
--
ALTER TABLE `detail_ekatalog`
  ADD CONSTRAINT `detail_ekatalog_ibfk_1` FOREIGN KEY (`ekatalog_id`) REFERENCES `ekatalog` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_ekatalog_ibfk_2` FOREIGN KEY (`penjualan_produk_id`) REFERENCES `penjualan_produk` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detail_penjualan_produk`
--
ALTER TABLE `detail_penjualan_produk`
  ADD CONSTRAINT `detail_penjualan_produk_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penjualan_produk_ibfk_3` FOREIGN KEY (`penjualan_produk_id`) REFERENCES `penjualan_produk` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detail_spa`
--
ALTER TABLE `detail_spa`
  ADD CONSTRAINT `detail_spa_ibfk_2` FOREIGN KEY (`penjualan_produk_id`) REFERENCES `penjualan_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_spa_ibfk_3` FOREIGN KEY (`spa_id`) REFERENCES `spa` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `ekatalog_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ekatalog_ibfk_2` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ekatalog_ibfk_3` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `spa_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `spa_ibfk_2` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `spb`
--
ALTER TABLE `spb`
  ADD CONSTRAINT `spb_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `spb_ibfk_2` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
