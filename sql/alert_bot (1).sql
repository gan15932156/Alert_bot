-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 11 มี.ค. 2020 เมื่อ 10:05 AM
-- เวอร์ชันของเซิร์ฟเวอร์: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alert_bot`
--

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `alert`
--

CREATE TABLE `alert` (
  `alert_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `alert_date` date NOT NULL,
  `alert_time` time NOT NULL,
  `line_group_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_alert_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `record_count` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `empolyee`
--

CREATE TABLE `empolyee` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `leveltest` int(1) NOT NULL,
  `img_em` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- dump ตาราง `empolyee`
--

INSERT INTO `empolyee` (`id_user`, `username`, `password`, `name`, `leveltest`, `img_em`) VALUES
(1, 'user', '25f9e794323b453885f5181f1b624d0b', 'ประทาน โพธิ์ภู่', 0, '08bd1.png'),
(2, 'admin', '25f9e794323b453885f5181f1b624d0b', 'แกน', 1, 'maxresdefault.jpg'),
(3, '505972', 'fa717c1cae7a7b36f7518a5dfbb4b214', 'สุภัทรา', 0, ''),
(4, '505972', 'fa717c1cae7a7b36f7518a5dfbb4b214', 'สุภัทรา', 0, ''),
(5, '505972', 'fa717c1cae7a7b36f7518a5dfbb4b214', 'สุภัทรา', 0, ''),
(6, '497036', 'f5ffa4aa6db399bb0838088fbc4cdf18', 'นันทศักดิ์', 0, '');

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `task_user`
--

CREATE TABLE `task_user` (
  `task_user_id` int(11) NOT NULL COMMENT 'PK',
  `user_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ไอดีผู้ใช้',
  `task_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ตารางเก็บความสัมพันระหว่างงานกับผู้ใช้';

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `template_tb`
--

CREATE TABLE `template_tb` (
  `template_id` int(11) NOT NULL,
  `task_user_id` int(11) NOT NULL,
  `colum_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `datatype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `token_line`
--

CREATE TABLE `token_line` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `task_id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `namegroup_line` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- dump ตาราง `token_line`
--

INSERT INTO `token_line` (`id`, `token`, `task_id`, `id_user`, `namegroup_line`) VALUES
(1, 'GDFJDFDFgdfg44fg', 1, 3, 'groupline1'),
(2, 'ggsdgsdfasfasd', 2, 3, 'testgrouppliuneee'),
(3, 'hfhfhf', 3, 3, 'hfhfg'),
(4, 'fdkgodfpgfg', 4, 3, 'groupline'),
(5, 'fdsdgdgdfg', 5, 1, 'grouptask'),
(6, 'DTHFFDDF6453terhafadDgsg', 6, 3, 'groupline'),
(7, 'ssdgsdDSD434tgfge4t', 7, 3, 'groupline_#2'),
(8, 'dfjgfogj5o4okfgdj', 8, 3, 'groupline01'),
(9, 'oJmiWUqsVyRwl3oezOE7amMGF0nuviLjUgHNNl6rWhQ', 9, 2, 'peaฝึกงาน');

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `userpea`
--

CREATE TABLE `userpea` (
  `id_pea` int(11) NOT NULL,
  `username` int(10) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Department` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Position` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `levelpea` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- dump ตาราง `userpea`
--

INSERT INTO `userpea` (`id_pea`, `username`, `name`, `lastname`, `Department`, `Position`, `levelpea`) VALUES
(1, 505972, 'สุภัทรา', 'มณีโชติ', 'ผสบ./กรท./ฝบพ.(ก1)/กฟก.1/รผก.(ภ3)/ผวก.', 'นรค.', 'ระดับ 5'),
(2, 505972, 'สุภัทรา', 'มณีโชติ', 'ผสบ./กรท./ฝบพ.(ก1)/กฟก.1/รผก.(ภ3)/ผวก.', 'นรค.', 'ระดับ 5'),
(3, 505972, 'สุภัทรา', 'มณีโชติ', 'ผสบ./กรท./ฝบพ.(ก1)/กฟก.1/รผก.(ภ3)/ผวก.', 'นรค.', 'ระดับ 5'),
(4, 505972, 'สุภัทรา', 'มณีโชติ', 'ผสบ./กรท./ฝบพ.(ก1)/กฟก.1/รผก.(ภ3)/ผวก.', 'นรค.', 'ระดับ 5'),
(5, 505972, 'สุภัทรา', 'มณีโชติ', 'ผสบ./กรท./ฝบพ.(ก1)/กฟก.1/รผก.(ภ3)/ผวก.', 'นรค.', 'ระดับ 5'),
(6, 497036, 'นันทศักดิ์', 'ศิลามาศ', 'ผสบ./กรท./ฝบพ.(ก1)/กฟก.1/รผก.(ภ3)/ผวก.', 'พคค.', 'ระดับ 6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`alert_id`);

--
-- Indexes for table `empolyee`
--
ALTER TABLE `empolyee`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `task_user`
--
ALTER TABLE `task_user`
  ADD PRIMARY KEY (`task_user_id`);

--
-- Indexes for table `template_tb`
--
ALTER TABLE `template_tb`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `token_line`
--
ALTER TABLE `token_line`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userpea`
--
ALTER TABLE `userpea`
  ADD PRIMARY KEY (`id_pea`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alert`
--
ALTER TABLE `alert`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empolyee`
--
ALTER TABLE `empolyee`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `task_user`
--
ALTER TABLE `task_user`
  MODIFY `task_user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `template_tb`
--
ALTER TABLE `template_tb`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `token_line`
--
ALTER TABLE `token_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `userpea`
--
ALTER TABLE `userpea`
  MODIFY `id_pea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
