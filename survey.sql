-- 重新設計的問卷系統資料庫結構
CREATE TABLE IF NOT EXISTS `survey_pages` (
  `page_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_number` int(10) UNSIGNED NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_description` text,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `question_types` (
  `type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `type_description` text,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id` int(10) UNSIGNED NOT NULL,
  `type_id` int(10) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `question_order` int(10) UNSIGNED NOT NULL,
  `is_required` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`question_id`),
  KEY `page_id` (`page_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `survey_pages` (`page_id`) ON DELETE CASCADE,
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `question_types` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `options` (
  `option_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int(10) UNSIGNED NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `option_order` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`option_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `answers` (
  `answer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `answer_text` text,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`answer_id`),
  KEY `user_id` (`user_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `member` (`Member_id`) ON DELETE SET NULL,
  CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 插入問題類型資料
INSERT INTO `question_types` (`type_name`, `type_description`) VALUES
('radio', '單選按鈕'),
('checkbox', '複選框'),
('select', '下拉選單'),
('text', '單行文字'),
('textarea', '多行文字'),
('rating', '評分'),
('date', '日期選擇'),
('number', '數字輸入'),
('email', '電子郵件'),
('range', '範圍滑桿');

-- 插入問卷頁面資料（10頁）
INSERT INTO `survey_pages` (`page_number`, `page_title`, `page_description`) VALUES
(1, '個人基本資料', '請填寫您的個人基本資料'),
(2, '教育背景', '請填寫您的教育背景'),
(3, '工作經驗', '請填寫您的工作經驗'),
(4, '技能評估', '請評估您的技能水平'),
(5, '興趣偏好', '請告訴我們您的興趣偏好'),
(6, '意見反饋', '請給我們一些意見反饋'),
(7, '未來規劃', '請分享您的未來規劃'),
(8, '額外資訊', '請提供任何額外資訊'),
(9, '聯絡方式', '請填寫您的聯絡方式'),
(10, '完成問卷', '問卷即將完成，請確認您的答案');

-- 插入問題資料
INSERT INTO `questions` (`page_id`, `type_id`, `question_text`, `question_order`, `is_required`) VALUES
(1, 1, '您的性別是？', 1, 1),
(1, 2, '您會說哪些語言？', 2, 1),
(1, 3, '您偏好的聯絡方式？', 3, 1),
(1, 4, '您的年齡是？', 4, 1),
(1, 5, '請簡單描述您的背景', 5, 0),
(2, 3, '最高學歷？', 1, 1),
(2, 4, '就讀學校名稱', 2, 1),
(2, 4, '就讀科系', 3, 1),
(2, 6, '對學業滿意度', 4, 1),
(2, 7, '畢業年份', 5, 1),
(3, 4, '目前職稱', 1, 1),
(3, 4, '公司名稱', 2, 1),
(3, 4, '工作年資', 3, 1),
(3, 2, '擅長技能', 4, 1),
(3, 5, '工作內容描述', 5, 0),
(4, 6, '程式設計能力', 1, 1),
(4, 6, '團隊合作能力', 2, 1),
(4, 6, '溝通表達能力', 3, 1),
(4, 6, '問題解決能力', 4, 1),
(4, 6, '學習能力', 5, 1),
(5, 2, '喜歡的運動', 1, 1),
(5, 2, '喜歡的音樂類型', 2, 1),
(5, 2, '喜歡的書籍類型', 3, 1),
(5, 2, '喜歡的電影類型', 4, 1),
(5, 2, '喜歡的旅遊類型', 5, 1),
(6, 5, '對本問卷的建議', 1, 0),
(6, 4, '改進方向', 2, 0),
(6, 3, '滿意度評分', 3, 1),
(7, 4, '未來5年目標', 1, 1),
(7, 4, '職業規劃', 2, 1),
(7, 4, '學習計畫', 3, 1),
(7, 5, '對未來的看法', 4, 0),
(8, 4, '其他技能', 1, 0),
(8, 4, '特殊成就', 2, 0),
(8, 4, '參與活動', 3, 0),
(8, 5, '其他資訊', 4, 0),
(9, 9, '電子郵件', 1, 1),
(9, 4, '電話號碼', 2, 1),
(9, 4, '通訊地址', 3, 0),
(9, 3, '偏好聯絡時間', 4, 0),
(10, 5, '對問卷的總結意見', 1, 0);

-- 插入選項資料
INSERT INTO `options` (`question_id`, `option_text`, `option_order`) VALUES
(1, '男', 1),
(1, '女', 2),
(1, '其他', 3),
(1, '不願透露', 4),
(2, '中文', 1),
(2, '英文', 2),
(2, '日文', 3),
(2, '法文', 4),
(2, '西班牙文', 5),
(2, '其他', 6),
(3, '電話', 1),
(3, '電子郵件', 2),
(3, '簡訊', 3),
(3, '面對面', 4),
(7, '高中', 1),
(7, '專科', 2),
(7, '學士', 3),
(7, '碩士', 4),
(7, '博士', 5),
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
(27, '非常滿意', 1),
(27, '滿意', 2),
(27, '普通', 3),
(27, '不滿意', 4),
(27, '非常不滿意', 5);