-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2026-03-09 09:13:11
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `assignment`
--

-- --------------------------------------------------------

--
-- 資料表結構 `member`
--

CREATE TABLE `member` (
  `Member_id` int(10) UNSIGNED NOT NULL,
  `loginname` varchar(50) NOT NULL COMMENT '登入帳號，註冊後不可更改',
  `pwd` varchar(255) NOT NULL COMMENT '密碼（使用雜湊）',
  `member_name` varchar(100) NOT NULL COMMENT '顯示名稱，可重複',
  `member_telno` varchar(20) DEFAULT NULL COMMENT '電話號碼，可為空',
  `member_addr` text DEFAULT NULL COMMENT '地址，可為空',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='會員資料表';

--
-- 傾印資料表的資料 `member`
--

INSERT INTO `member` (`Member_id`, `loginname`, `pwd`, `member_name`, `member_telno`, `member_addr`, `created_at`, `updated_at`) VALUES
(1, 'aeoja', '$2y$10$qwhCEAPj5wE30a7GpIXqMeH6hNs1xhB.dqJG9JFK2Xddiq4yxEMWG', 'aeoja', NULL, NULL, '2026-03-06 21:28:44', '2026-03-06 21:28:44'),
(2, 'jackyhk', 'jackyhk99', 'Jacky HK', NULL, NULL, '2026-03-06 15:47:54', '2026-03-06 15:47:54'),
(3, 'kenny123', 'kenny1234', 'Kenny Wong', '91234567', 'Room 101, Block A', '2026-03-06 15:47:54', '2026-03-06 15:47:54'),
(4, 'lily_ho', 'lilyho2023', 'Lily Ho', '98765432', NULL, '2026-03-06 15:47:54', '2026-03-06 15:47:54'),
(5, 'mary_cheung', 'maryc123', 'Mary Cheung', '91288899', '88 Example St', '2026-03-06 15:47:54', '2026-03-06 15:47:54'),
(6, 'sophia_lam', 'sophia2026', 'Sophia Lam', '60123456', '168 Sample Rd', '2026-03-06 15:47:54', '2026-03-06 15:47:54'),
(7, 'tommychan', 'tommy888', 'Tommy Chan', NULL, 'Flat 8, Tower B', '2026-03-06 15:47:54', '2026-03-06 15:47:54');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`loginname`),
  ADD UNIQUE KEY `mem_id` (`Member_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `member`
--
ALTER TABLE `member`
  MODIFY `Member_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
