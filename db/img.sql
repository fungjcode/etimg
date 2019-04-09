-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019-04-09 22:32:15
-- 服务器版本： 5.5.56-log
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `img`
--

-- --------------------------------------------------------

--
-- 表的结构 `img_imginfo`
--

CREATE TABLE `img_imginfo` (
  `id` int(11) NOT NULL,
  `imgurl` varchar(255) NOT NULL COMMENT '图片URL',
  `useruid` varchar(255) DEFAULT NULL COMMENT '用户注册ID',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `userlave` int(10) NOT NULL COMMENT '用户等级',
  `userip` varchar(255) NOT NULL COMMENT '用户IP',
  `filename` varchar(255) NOT NULL,
  `ctime` int(255) NOT NULL COMMENT '创建时间',
  `loaclpath` varchar(999) NOT NULL COMMENT '大图本地路径',
  `imglave` int(10) DEFAULT '0' COMMENT '图片等级',
  `thumbpath` varchar(999) NOT NULL COMMENT '缩略图本地位置',
  `width` int(120) NOT NULL COMMENT '图片宽度',
  `height` int(120) NOT NULL COMMENT '图片高度',
  `type` varchar(255) NOT NULL COMMENT '图片类型',
  `mime` varchar(255) NOT NULL COMMENT 'mime',
  `stat` int(10) DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `img_notice`
--

CREATE TABLE `img_notice` (
  `id` int(99) NOT NULL,
  `info` text NOT NULL COMMENT '公告内容',
  `ctime` varchar(255) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `img_notice`
--

INSERT INTO `img_notice` (`id`, `info`, `ctime`) VALUES
(1, '欢迎使用ET图床，如果您觉得不错，请记得告诉其他人，本图床终身免费哦', '1525679869');

-- --------------------------------------------------------

--
-- 表的结构 `img_sysadmin`
--

CREATE TABLE `img_sysadmin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `img_sysadmin`
--

INSERT INTO `img_sysadmin` (`id`, `username`, `password`) VALUES
(1, 'sysadmin', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- 表的结构 `img_webinfo`
--

CREATE TABLE `img_webinfo` (
  `id` int(11) NOT NULL,
  `webname` varchar(255) NOT NULL COMMENT '站点名称',
  `weburl` varchar(255) NOT NULL COMMENT '站点URL',
  `webstate` int(5) NOT NULL DEFAULT '0' COMMENT '站点状态',
  `description` varchar(255) NOT NULL COMMENT '网站描述',
  `keywords` varchar(255) NOT NULL COMMENT '网站关键字'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `img_webinfo`
--

INSERT INTO `img_webinfo` (`id`, `webname`, `weburl`, `webstate`, `description`, `keywords`) VALUES
(1, 'ET图床', 'https://www.etimg.net', 1, '外星人图床,最稳定的免费图床', 'ET图床,外星人图床,图床,图片外链,免费图床,免费');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `img_imginfo`
--
ALTER TABLE `img_imginfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `img_notice`
--
ALTER TABLE `img_notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `img_sysadmin`
--
ALTER TABLE `img_sysadmin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `img_webinfo`
--
ALTER TABLE `img_webinfo`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `img_imginfo`
--
ALTER TABLE `img_imginfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `img_notice`
--
ALTER TABLE `img_notice`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `img_sysadmin`
--
ALTER TABLE `img_sysadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `img_webinfo`
--
ALTER TABLE `img_webinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
