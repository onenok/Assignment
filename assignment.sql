-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2026-03-09 16:38:11
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
-- 資料表結構 `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `answer_text` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `answers`
--

INSERT INTO `answers` (`answer_id`, `user_id`, `question_id`, `answer_text`, `created_at`) VALUES
(1, 1, 1, '男', '2026-03-09 22:47:12'),
(2, 1, 21, '游泳, 跑步 / 健身', '2026-03-09 22:47:12'),
(3, 1, 6, '大專, 副學士, 高級文憑', '2026-03-09 22:47:12'),
(4, 1, 11, 'student', '2026-03-09 22:47:12'),
(5, 1, 29, 'a ba a ba', '2026-03-09 22:47:12'),
(6, 1, 33, 'idk', '2026-03-09 22:47:12'),
(7, 1, 26, 'shit', '2026-03-09 22:47:12'),
(8, 1, 41, 'shit', '2026-03-09 22:47:12'),
(9, 1, 16, '4', '2026-03-09 22:47:12'),
(10, 1, 37, 'honokhei20061122@gmail.com', '2026-03-09 22:47:12'),
(11, 1, 2, '中文(廣東話), 中文(普通話), 英文, 日文', '2026-03-09 22:47:12'),
(12, 1, 22, '流行 / K-pop, 搖滾 / 獨立音樂, 古典 / 器樂, 電子 / EDM, 爵士 / 藍調', '2026-03-09 22:47:12'),
(13, 1, 7, 'SFU', '2026-03-09 22:47:12'),
(14, 1, 12, 'none', '2026-03-09 22:47:12'),
(15, 1, 27, 'go eat urself', '2026-03-09 22:47:12'),
(16, 1, 30, 'a ba a ba', '2026-03-09 22:47:12'),
(17, 1, 34, 'still living', '2026-03-09 22:47:12'),
(18, 1, 17, '2', '2026-03-09 22:47:12'),
(19, 1, 38, '60151380', '2026-03-09 22:47:12'),
(20, 1, 23, '漫畫 / 輕小說, 科幻 / 奇幻', '2026-03-09 22:47:12'),
(21, 1, 3, '即時通訊軟體（如 Line、WhatsApp、Discord）', '2026-03-09 22:47:12'),
(22, 1, 8, 'HDAI-ICT', '2026-03-09 22:47:12'),
(23, 1, 13, 'none', '2026-03-09 22:47:12'),
(24, 1, 31, 'a ba a ba', '2026-03-09 22:47:12'),
(25, 1, 35, 'earth online', '2026-03-09 22:47:12'),
(26, 1, 18, '1', '2026-03-09 22:47:12'),
(27, 1, 28, '1', '2026-03-09 22:47:12'),
(28, 1, 14, '程式設計 / 開發, 平面設計 / UI/UX', '2026-03-09 22:47:12'),
(29, 1, 24, '動作 / 冒險, 愛情 / 浪漫, 科幻 / 奇幻', '2026-03-09 22:47:12'),
(30, 1, 4, '18-24 歲', '2026-03-09 22:47:12'),
(31, 1, 40, '不願透露 / 其他時間（請說明）', '2026-03-09 22:47:12'),
(32, 1, 32, 'a ba a ba', '2026-03-09 22:47:12'),
(33, 1, 36, 'u\'re ugly', '2026-03-09 22:47:12'),
(34, 1, 9, '4', '2026-03-09 22:47:12'),
(35, 1, 19, '4', '2026-03-09 22:47:12'),
(36, 1, 25, '自然 / 風景, 海灘 / 度假, 美食之旅, 溫泉 / 休閒', '2026-03-09 22:47:12'),
(37, 1, 5, 'none of ur business', '2026-03-09 22:47:12'),
(38, 1, 15, 'none', '2026-03-09 22:47:12'),
(39, 1, 20, '4', '2026-03-09 22:47:12'),
(40, 1, 10, '2026-06-12', '2026-03-09 22:47:12');

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

-- --------------------------------------------------------

--
-- 資料表結構 `options`
--

CREATE TABLE `options` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `option_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `options`
--

INSERT INTO `options` (`question_id`, `option_text`, `option_order`) VALUES
(1, '男', 1),
(1, '女', 2),
(1, '其他', 3),
(1, '不願透露', 4),
(2, '中文(廣東話)', 1),
(2, '中文(普通話)', 2),
(2, '英文', 3),
(2, '日文', 4),
(2, '法文', 5),
(2, '西班牙文', 6),
(2, '其他', 7),
(3, '電話', 1),
(3, '電子郵件', 2),
(3, '簡訊', 3),
(3, '即時通訊軟體（如 Line、WhatsApp、Discord）', 4),
(3, '面對面', 5),
(4, '18-24 歲', 1),
(4, '25-34 歲', 2),
(4, '35-44 歲', 3),
(4, '45-54 歲', 4),
(4, '55-64 歲', 5),
(4, '65 歲以上', 6),
(4, '不願透露', 7),
(6, '高中', 1),
(6, '大專, 副學士, 高級文憑', 2),
(6, '學士', 3),
(6, '碩士', 4),
(6, '博士', 5),
(14, '程式設計 / 開發', 1),
(14, '數據分析 / 大數據', 2),
(14, '市場營銷 / 數碼營銷', 3),
(14, '平面設計 / UI/UX', 4),
(14, '影片剪接 / 後期製作', 5),
(14, '語言能力（英文 / 普通話 / 日文等）', 6),
(14, '項目管理', 7),
(14, '財務 / 會計', 8),
(14, '銷售 / 客戶服務', 9),
(14, '人力資源管理', 10),
(14, '寫作 / 內容創作', 11),
(14, '領導 / 團隊管理', 12),
(14, '研究 / 學術分析', 13),
(14, '其他（請說明）', 14),
(16, '非常好', 1),
(16, '好', 2),
(16, '普通', 3),
(16, '差', 4),
(16, '非常差', 5),
(17, '非常好', 1),
(17, '好', 2),
(17, '普通', 3),
(17, '差', 4),
(17, '非常差', 5),
(18, '非常好', 1),
(18, '好', 2),
(18, '普通', 3),
(18, '差', 4),
(18, '非常差', 5),
(19, '非常好', 1),
(19, '好', 2),
(19, '普通', 3),
(19, '差', 4),
(19, '非常差', 5),
(20, '非常好', 1),
(20, '好', 2),
(20, '普通', 3),
(20, '差', 4),
(20, '非常差', 5),
(21, '足球 / 籃球', 1),
(21, '游泳', 2),
(21, '跑步 / 健身', 3),
(21, '瑜伽 / 普拉提', 4),
(21, '羽毛球 / 乒乓球', 5),
(21, '行山 / 遠足', 6),
(21, '單車 / 滑板', 7),
(21, '其他運動（請說明）', 8),
(22, '流行 / K-pop', 1),
(22, '搖滾 / 獨立音樂', 2),
(22, '古典 / 器樂', 3),
(22, '嘻哈 / Rap', 4),
(22, '電子 / EDM', 5),
(22, '粵語 / 國語老歌', 6),
(22, '爵士 / 藍調', 7),
(22, '其他音樂類型（請說明）', 8),
(23, '小說 / 文學', 1),
(23, '漫畫 / 輕小說', 2),
(23, '商業 / 理財', 3),
(23, '心理學 / 自我成長', 4),
(23, '科幻 / 奇幻', 5),
(23, '歷史 / 傳記', 6),
(23, '旅遊 / 生活風格', 7),
(23, '其他書籍類型（請說明）', 8),
(24, '動作 / 冒險', 1),
(24, '喜劇', 2),
(24, '愛情 / 浪漫', 3),
(24, '科幻 / 奇幻', 4),
(24, '懸疑 / 驚悚', 5),
(24, '劇情 / 文藝', 6),
(24, '動畫 / 家庭', 7),
(24, '紀錄片 / 真人秀', 8),
(24, '其他電影類型（請說明）', 9),
(25, '城市觀光 / 購物', 1),
(25, '自然 / 風景', 2),
(25, '海灘 / 度假', 3),
(25, '文化 / 歷史遺跡', 4),
(25, '美食之旅', 5),
(25, '冒險 / 戶外活動', 6),
(25, '溫泉 / 休閒', 7),
(25, '其他旅遊類型（請說明）', 8),
(27, '非常滿意', 1),
(27, '滿意', 2),
(27, '普通', 3),
(27, '不滿意', 4),
(27, '非常不滿意', 5),
(40, '早上 9:00 - 12:00', 1),
(40, '中午 12:00 - 14:00', 2),
(40, '下午 14:00 - 18:00', 3),
(40, '晚上 18:00 - 21:00', 4),
(40, '深夜 21:00 - 00:00', 5),
(40, '任何時間都可以', 6),
(40, '不願透露 / 其他時間（請說明）', 7);

-- --------------------------------------------------------

--
-- 資料表結構 `questions`
--

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
(40, 9, 3, '偏好聯絡時間', 4, 0, '2026-03-09 20:26:45'),
(41, 10, 5, '對問卷的總結意見', 1, 0, '2026-03-09 20:26:45');

-- --------------------------------------------------------

--
-- 資料表結構 `question_attributes`
--

CREATE TABLE `question_attributes` (
  `id` int(11) NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `attr_key` varchar(50) NOT NULL,
  `attr_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `question_types`
--

CREATE TABLE `question_types` (
  `type_id` int(10) UNSIGNED NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `type_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `survey_pages` (
  `page_id` int(10) UNSIGNED NOT NULL,
  `page_number` int(10) UNSIGNED NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
