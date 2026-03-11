-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2026-03-11 07:36:29
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET FOREIGN_KEY_CHECKS=0;
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
CREATE DATABASE IF NOT EXISTS `assignment` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `assignment`;

-- --------------------------------------------------------

--
-- 資料表結構 `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `answer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `answer_text` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表新增資料前，先清除舊資料 `answers`
--

TRUNCATE TABLE `answers`;
-- --------------------------------------------------------

--
-- 資料表結構 `member`
--

DROP TABLE IF EXISTS `member`;
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
-- 資料表新增資料前，先清除舊資料 `member`
--

TRUNCATE TABLE `member`;
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

-- --------------------------------------------------------

--
-- 資料表結構 `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `option_order` int(10) UNSIGNED NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_other` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表新增資料前，先清除舊資料 `options`
--

TRUNCATE TABLE `options`;
--
-- 傾印資料表的資料 `options`
--

INSERT INTO `options` (`question_id`, `option_order`, `option_text`, `is_other`) VALUES
(1, 1, '男', 0),
(1, 2, '女', 0),
(1, 3, '其他', 0),
(1, 4, '不願透露', 0),
(2, 1, '中文(廣東話)', 0),
(2, 2, '中文(普通話)', 0),
(2, 3, '英文', 0),
(2, 4, '日文', 0),
(2, 5, '法文', 0),
(2, 6, '西班牙文', 0),
(2, 7, '其他', 1),
(3, 1, '電話', 0),
(3, 2, '電子郵件', 0),
(3, 3, '簡訊', 0),
(3, 4, '即時通訊軟體（如 Line、WhatsApp、Discord）', 0),
(3, 5, '面對面', 0),
(4, 1, '18-24 歲', 0),
(4, 2, '25-34 歲', 0),
(4, 3, '35-44 歲', 0),
(4, 4, '45-54 歲', 0),
(4, 5, '55-64 歲', 0),
(4, 6, '65 歲以上', 0),
(4, 7, '不願透露', 0),
(6, 1, '高中', 0),
(6, 2, '大專, 副學士, 高級文憑', 0),
(6, 3, '學士', 0),
(6, 4, '碩士', 0),
(6, 5, '博士', 0),
(14, 1, '程式設計 / 開發', 0),
(14, 2, '數據分析 / 大數據', 0),
(14, 3, '市場營銷 / 數碼營銷', 0),
(14, 4, '平面設計 / UI/UX', 0),
(14, 5, '影片剪接 / 後期製作', 0),
(14, 6, '語言能力（英文 / 普通話 / 日文等）', 0),
(14, 7, '項目管理', 0),
(14, 8, '財務 / 會計', 0),
(14, 9, '銷售 / 客戶服務', 0),
(14, 10, '人力資源管理', 0),
(14, 11, '寫作 / 內容創作', 0),
(14, 12, '領導 / 團隊管理', 0),
(14, 13, '研究 / 學術分析', 0),
(14, 14, '其他（請說明）', 1),
(16, 1, '非常好', 0),
(16, 2, '好', 0),
(16, 3, '普通', 0),
(16, 4, '差', 0),
(16, 5, '非常差', 0),
(17, 1, '非常好', 0),
(17, 2, '好', 0),
(17, 3, '普通', 0),
(17, 4, '差', 0),
(17, 5, '非常差', 0),
(18, 1, '非常好', 0),
(18, 2, '好', 0),
(18, 3, '普通', 0),
(18, 4, '差', 0),
(18, 5, '非常差', 0),
(19, 1, '非常好', 0),
(19, 2, '好', 0),
(19, 3, '普通', 0),
(19, 4, '差', 0),
(19, 5, '非常差', 0),
(20, 1, '非常好', 0),
(20, 2, '好', 0),
(20, 3, '普通', 0),
(20, 4, '差', 0),
(20, 5, '非常差', 0),
(21, 1, '足球 / 籃球', 0),
(21, 2, '游泳', 0),
(21, 3, '跑步 / 健身', 0),
(21, 4, '瑜伽 / 普拉提', 0),
(21, 5, '羽毛球 / 乒乓球', 0),
(21, 6, '行山 / 遠足', 0),
(21, 7, '單車 / 滑板', 0),
(21, 8, '其他運動（請說明）', 1),
(22, 1, '流行 / K-pop', 0),
(22, 2, '搖滾 / 獨立音樂', 0),
(22, 3, '古典 / 器樂', 0),
(22, 4, '嘻哈 / Rap', 0),
(22, 5, '電子 / EDM', 0),
(22, 6, '粵語 / 國語老歌', 0),
(22, 7, '爵士 / 藍調', 0),
(22, 8, '其他音樂類型（請說明）', 1),
(23, 1, '小說 / 文學', 0),
(23, 2, '漫畫 / 輕小說', 0),
(23, 3, '商業 / 理財', 0),
(23, 4, '心理學 / 自我成長', 0),
(23, 5, '科幻 / 奇幻', 0),
(23, 6, '歷史 / 傳記', 0),
(23, 7, '旅遊 / 生活風格', 0),
(23, 8, '其他書籍類型（請說明）', 1),
(24, 1, '動作 / 冒險', 0),
(24, 2, '動畫 / 動漫 / 二次元', 0),
(24, 3, '喜劇', 0),
(24, 4, '愛情 / 浪漫', 0),
(24, 5, '科幻 / 奇幻', 0),
(24, 6, '懸疑 / 驚悚', 0),
(24, 7, '劇情 / 文藝', 0),
(24, 8, '家庭', 0),
(24, 9, '紀錄片 / 真人秀', 0),
(24, 10, '其他電影類型（請說明）', 1),
(25, 1, '購物', 0),
(25, 2, '自然風景', 0),
(25, 3, '海灘度假', 0),
(25, 4, '文化 / 歷史遺跡', 0),
(25, 5, '美食之旅', 0),
(25, 6, '冒險 / 戶外活動', 0),
(25, 7, '溫泉', 0),
(25, 8, '休閒', 0),
(25, 9, '其他旅遊類型（請說明）', 1),
(27, 1, '非常滿意', 0),
(27, 2, '滿意', 0),
(27, 3, '普通', 0),
(27, 4, '不滿意', 0),
(27, 5, '非常不滿意', 0),
(40, 1, '早上 9:00 - 12:00', 0),
(40, 2, '中午 12:00 - 14:00', 0),
(40, 3, '下午 14:00 - 18:00', 0),
(40, 4, '晚上 18:00 - 21:00', 0),
(40, 5, '深夜 21:00 - 00:00', 0),
(40, 6, '任何時間都可以', 0),
(40, 7, '不願透露', 0),
(40, 8, '其他時間（請說明）', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL,
  `type_id` int(10) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `question_order` int(10) UNSIGNED NOT NULL,
  `is_required` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表新增資料前，先清除舊資料 `questions`
--

TRUNCATE TABLE `questions`;
--
-- 傾印資料表的資料 `questions`
--

INSERT INTO `questions` (`question_id`, `page_id`, `type_id`, `question_text`, `question_order`, `is_required`, `created_at`) VALUES
(1, 1, 1, '您的性別是？', 1, 1, '2026-03-09 20:26:45'),
(2, 1, 2, '您會說哪些語言？', 2, 1, '2026-03-09 20:26:45'),
(3, 1, 3, '您偏好的聯絡方式？', 3, 1, '2026-03-09 20:26:45'),
(4, 1, 3, '您的年齡範圍是？', 4, 1, '2026-03-09 20:26:45'),
(5, 1, 5, '請簡單描述您的背景', 5, 0, '2026-03-09 20:26:45'),
(6, 2, 3, '最高學歷？', 1, 1, '2026-03-09 20:26:45'),
(7, 2, 4, '就讀學校名稱', 2, 1, '2026-03-09 20:26:45'),
(8, 2, 4, '就讀科系', 3, 1, '2026-03-09 20:26:45'),
(9, 2, 6, '對學業滿意度', 4, 1, '2026-03-09 20:26:45'),
(10, 2, 7, '畢業年份', 5, 1, '2026-03-09 20:26:45'),
(11, 3, 4, '目前職稱', 1, 1, '2026-03-09 20:26:45'),
(12, 3, 4, '公司名稱', 2, 1, '2026-03-09 20:26:45'),
(13, 3, 4, '工作年資', 3, 1, '2026-03-09 20:26:45'),
(14, 3, 2, '擅長技能', 4, 1, '2026-03-09 20:26:45'),
(15, 3, 5, '工作內容描述', 5, 0, '2026-03-09 20:26:45'),
(16, 4, 6, '程式設計能力', 1, 1, '2026-03-09 20:26:45'),
(17, 4, 6, '團隊合作能力', 2, 1, '2026-03-09 20:26:45'),
(18, 4, 6, '溝通表達能力', 3, 1, '2026-03-09 20:26:45'),
(19, 4, 6, '問題解決能力', 4, 1, '2026-03-09 20:26:45'),
(20, 4, 6, '學習能力', 5, 1, '2026-03-09 20:26:45'),
(21, 5, 2, '喜歡的運動', 1, 1, '2026-03-09 20:26:45'),
(22, 5, 2, '喜歡的音樂類型', 2, 1, '2026-03-09 20:26:45'),
(23, 5, 2, '喜歡的書籍類型', 3, 1, '2026-03-09 20:26:45'),
(24, 5, 2, '喜歡的電影類型', 4, 1, '2026-03-09 20:26:45'),
(25, 5, 2, '喜歡的旅遊類型', 5, 1, '2026-03-09 20:26:45'),
(26, 6, 5, '對本問卷的建議', 1, 0, '2026-03-09 20:26:45'),
(27, 6, 4, '改進方向', 2, 0, '2026-03-09 20:26:45'),
(28, 6, 6, '滿意度評分', 3, 1, '2026-03-09 20:26:45'),
(29, 7, 4, '未來5年目標', 1, 1, '2026-03-09 20:26:45'),
(30, 7, 4, '職業規劃', 2, 1, '2026-03-09 20:26:45'),
(31, 7, 4, '學習計畫', 3, 1, '2026-03-09 20:26:45'),
(32, 7, 5, '對未來的看法', 4, 0, '2026-03-09 20:26:45'),
(33, 8, 4, '其他技能', 1, 0, '2026-03-09 20:26:45'),
(34, 8, 4, '特殊成就', 2, 0, '2026-03-09 20:26:45'),
(35, 8, 4, '參與活動', 3, 0, '2026-03-09 20:26:45'),
(36, 8, 5, '其他資訊', 4, 0, '2026-03-09 20:26:45'),
(37, 9, 9, '電子郵件', 1, 1, '2026-03-09 20:26:45'),
(38, 9, 11, '電話號碼', 2, 1, '2026-03-09 20:26:45'),
(40, 9, 1, '偏好聯絡時間', 4, 0, '2026-03-09 20:26:45'),
(41, 10, 5, '對問卷的總結意見', 1, 0, '2026-03-09 20:26:45');

-- --------------------------------------------------------

--
-- 資料表結構 `question_attributes`
--

DROP TABLE IF EXISTS `question_attributes`;
CREATE TABLE `question_attributes` (
  `id` int(11) NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `attr_key` varchar(50) NOT NULL,
  `attr_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 資料表新增資料前，先清除舊資料 `question_attributes`
--

TRUNCATE TABLE `question_attributes`;
-- --------------------------------------------------------

--
-- 資料表結構 `question_types`
--

DROP TABLE IF EXISTS `question_types`;
CREATE TABLE `question_types` (
  `type_id` int(10) UNSIGNED NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `type_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表新增資料前，先清除舊資料 `question_types`
--

TRUNCATE TABLE `question_types`;
--
-- 傾印資料表的資料 `question_types`
--

INSERT INTO `question_types` (`type_id`, `type_name`, `type_description`) VALUES
(1, 'radio', '單選按鈕'),
(2, 'checkbox', '複選框'),
(3, 'select', '下拉選單'),
(4, 'text', '單行文字'),
(5, 'textarea', '多行文字'),
(6, 'rating', '評分'),
(7, 'date', '日期選擇'),
(8, 'number', '數字輸入'),
(9, 'email', '電子郵件'),
(10, 'range', '範圍滑桿'),
(11, 'tel', 'phone No.');

-- --------------------------------------------------------

--
-- 資料表結構 `survey_pages`
--

DROP TABLE IF EXISTS `survey_pages`;
CREATE TABLE `survey_pages` (
  `page_id` int(10) UNSIGNED NOT NULL,
  `page_number` int(10) UNSIGNED NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表新增資料前，先清除舊資料 `survey_pages`
--

TRUNCATE TABLE `survey_pages`;
--
-- 傾印資料表的資料 `survey_pages`
--

INSERT INTO `survey_pages` (`page_id`, `page_number`, `page_title`, `page_description`, `created_at`) VALUES
(1, 1, '個人基本資料', '請填寫您的個人基本資料', '2026-03-09 20:26:45'),
(2, 2, '教育背景', '請填寫您的教育背景', '2026-03-09 20:26:45'),
(3, 3, '工作經驗', '請填寫您的工作經驗', '2026-03-09 20:26:45'),
(4, 4, '技能評估', '請評估您的技能水平', '2026-03-09 20:26:45'),
(5, 5, '興趣偏好', '請告訴我們您的興趣偏好', '2026-03-09 20:26:45'),
(6, 6, '意見反饋', '請給我們一些意見反饋', '2026-03-09 20:26:45'),
(7, 7, '未來規劃', '請分享您的未來規劃', '2026-03-09 20:26:45'),
(8, 8, '額外資訊', '請提供任何額外資訊', '2026-03-09 20:26:45'),
(9, 9, '聯絡方式', '請填寫您的聯絡方式', '2026-03-09 20:26:45'),
(10, 10, '完成問卷', '問卷即將完成，請確認您的答案', '2026-03-09 20:26:45');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`);

--
-- 資料表索引 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`loginname`),
  ADD UNIQUE KEY `mem_id` (`Member_id`);

--
-- 資料表索引 `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`question_id`,`option_order`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `idx_question_order` (`question_id`,`option_order`);

--
-- 資料表索引 `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `page_id` (`page_id`),
  ADD KEY `type_id` (`type_id`);

--
-- 資料表索引 `question_attributes`
--
ALTER TABLE `question_attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question_id` (`question_id`,`attr_key`);

--
-- 資料表索引 `question_types`
--
ALTER TABLE `question_types`
  ADD PRIMARY KEY (`type_id`);

--
-- 資料表索引 `survey_pages`
--
ALTER TABLE `survey_pages`
  ADD PRIMARY KEY (`page_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `member`
--
ALTER TABLE `member`
  MODIFY `Member_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `question_attributes`
--
ALTER TABLE `question_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `question_types`
--
ALTER TABLE `question_types`
  MODIFY `type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `survey_pages`
--
ALTER TABLE `survey_pages`
  MODIFY `page_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `member` (`Member_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `survey_pages` (`page_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `question_types` (`type_id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
