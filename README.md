# 問卷系統 - CIS223 作業

## 專案概述

這是一個完整的線上問卷調查系統，符合CIS223/CIS226作業要求。系統提供會員註冊、登入、多頁問卷填寫、答案儲存等功能。

## 功能特點

### 1. 會員系統
- 會員註冊（包含密碼確認）
- 會員登入（使用CSRF token保護）
- 修改個人資料
- 刪除帳號
- 密碼雜湊儲存

### 2. 多頁問卷系統
- **10頁問卷**，每頁有不同的主題：
  1. 個人基本資料
  2. 教育背景
  3. 工作經驗
  4. 技能評估
  5. 興趣偏好
  6. 意見反饋
  7. 未來規劃
  8. 額外資訊
  9. 聯絡方式
  10. 完成問卷

### 3. 多種問題控制方式
系統支援10種不同的問題類型：
- **單選按鈕 (radio)** - 如性別選擇
- **複選框 (checkbox)** - 如語言選擇
- **下拉選單 (select)** - 如學歷選擇
- **單行文字 (text)** - 如年齡輸入
- **多行文字 (textarea)** - 如背景描述
- **評分 (rating)** - 1-5星評分
- **日期選擇 (date)** - 日期選擇器
- **數字輸入 (number)** - 數字輸入
- **電子郵件 (email)** - 郵件輸入
- **範圍滑桿 (range)** - 1-10分滑桿

### 4. 安全性功能
- CSRF token 驗證
- SQL 注入防護（使用預備語句）
- XSS 防護（htmlspecialchars）
- 密碼雜湊儲存
- Session 管理

### 5. 使用者體驗
- 進度條顯示
- 表單驗證
- 響應式設計
- 錯誤提示
- 完成統計頁面

## 檔案結構

```
Assignment/
├── assignment.sql          # 會員資料表結構
├── survey.sql              # 問卷系統資料表結構
├── connect.php             # 資料庫連線設定
├── index.php               # 首頁
├── login.php               # 登入頁面
├── search.php              # 登入處理
├── member_reg.php          # 註冊頁面
├── insert.php              # 註冊處理
├── editAccount.php         # 修改資料頁面
├── update.php              # 修改資料處理
├── cancellation.php        # 刪除帳號頁面
├── delete.php              # 刪除帳號處理
├── logout.php              # 登出處理
├── survey.php              # 問卷主頁面
├── process_survey.php      # 問卷提交處理
├── survey_completed.php    # 問卷完成頁面
├── view_results.php        # 查看結果頁面
├── nav.php                 # 導航列
├── style.css               # 主要樣式
├── form_default.css        # 表單樣式
└── overlay-scrollbar.js    # 自定義滾動條
```

## 資料庫結構

### 1. member 資料表
會員基本資料表，包含：
- Member_id (主鍵)
- loginname (登入帳號)
- pwd (密碼雜湊)
- member_name (顯示名稱)
- member_telno (電話)
- member_addr (地址)
- created_at (建立時間)
- updated_at (更新時間)

### 2. survey_pages 資料表
問卷頁面定義：
- page_id (主鍵)
- page_number (頁面編號)
- page_title (頁面標題)
- page_description (頁面描述)

### 3. questions 資料表
問題定義：
- question_id (主鍵)
- page_id (所屬頁面)
- type_id (問題類型)
- question_text (問題文字)
- question_order (問題順序)
- is_required (是否必填)

### 4. question_types 資料表
問題類型：
- type_id (主鍵)
- type_name (類型名稱)
- type_description (類型描述)

### 5. options 資料表
選項資料（用於單選、複選、下拉）：
- option_id (主鍵)
- question_id (所屬問題)
- option_text (選項文字)
- option_order (選項順序)

### 6. answers 資料表
答案儲存：
- answer_id (主鍵)
- user_id (使用者ID)
- question_id (問題ID)
- answer_text (答案內容)
- created_at (提交時間)

## 安裝步驟

### 1. 環境需求
- PHP 7.4 或以上
- MySQL/MariaDB 10.0 或以上
- Web Server (Apache/Nginx/XAMPP)

### 2. 資料庫設定
1. 建立資料庫：
   ```sql
   CREATE DATABASE assignment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. 匯入資料表結構：
   ```bash
   mysql -u root -p assignment < assignment.sql
   mysql -u root -p assignment < survey.sql
   ```

3. 修改資料庫連線設定：
   編輯 `connect.php` 檔案，修改以下內容：
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "your_password";
   $db_name = "assignment";
   ```

### 3. 部署
1. 將所有檔案複製到 Web Server 根目錄
2. 確保 PHP 有權限寫入 session
3. 存取 `index.php` 開始使用系統

## 使用說明

### 一般使用者
1. 點擊「註冊」建立帳號
2. 使用帳號密碼登入
3. 點擊「填寫問卷」開始填寫
4. 依序填寫10頁問卷
5. 提交後查看完成頁面
6. 可點擊「查看結果」查看已填寫的答案

### 管理員
- 可透過 phpMyAdmin 或 MySQL 命令列查看所有答案
- 查詢語法：
  ```sql
  SELECT * FROM answers ORDER BY created_at DESC;
  ```

## 安全性說明

1. **CSRF 保護**：所有表單都包含 CSRF token 驗證
2. **SQL 注入防護**：使用 PDO 預備語句
3. **XSS 防護**：所有輸出都經過 htmlspecialchars 處理
4. **密碼安全**：使用 password_hash() 雜湊儲存
5. **Session 管理**：正確啟動和銷毀 session

## 測試帳號

資料庫中已預先建立以下測試帳號：

| 帳號 | 密碼 | 名稱 |
|------|------|------|
| aeoja | (已雜湊) | aeoja |
| jackyhk | jackyhk99 | Jacky HK |
| kenny123 | kenny1234 | Kenny Wong |
| lily_ho | lilyho2023 | Lily Ho |
| mary_cheung | maryc123 | Mary Cheung |
| sophia_lam | sophia2026 | Sophia Lam |
| tommychan | tommy888 | Tommy Chan |

## 注意事項

1. 本系統僅供本地端使用
2. 問卷一旦提交無法修改
3. 每個帳號只能填寫一次問卷
4. 建議定期備份資料庫

## 技術棧

- **後端**：PHP 8.x
- **資料庫**：MySQL/MariaDB
- **前端**：HTML5, CSS3, JavaScript
- **安全性**：CSRF token, SQL 預備語句, 密碼雜湊

## 授權

本專案為學術作業用途，請勿用於商業用途。

## 更新日誌

### 2024-03-09
- 初始版本
- 建立完整的會員系統
- 實作10頁問卷系統
- 支援10種問題控制方式
- 完成答案儲存和查看功能