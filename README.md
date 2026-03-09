# 問卷系統

## 資料表結構

### survey 資料表
```sql
CREATE TABLE IF NOT EXISTS `survey` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `question1` varchar(255) NOT NULL,
  `question2` varchar(255) NOT NULL,
  `question3` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `member` (`Member_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 檔案說明

### survey.php
- 顯示問卷表單
- 檢查使用者是否已登入
- 處理表單提交並寫入資料庫

### process_survey.php
- 處理問卷提交的後端邏輯
- 驗證 CSRF token
- 將資料寫入資料庫

## 使用方式

1. 執行 `survey.sql` 建立資料表
2. 確保 `connect.php` 中的資料庫連線設定正確
3. 開啟 `survey.php` 填寫問卷
4. 資料會自動寫入 `survey` 資料表

## 安全性功能

- CSRF token 驗證
- 輸入資料過濾與清理
- 使用預備語句防止 SQL 注入