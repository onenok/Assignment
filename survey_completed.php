<?php
session_start();
require_once 'connect.php';

// 檢查是否已登入
if (empty($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

// 檢查是否已完成問卷
if (empty($_SESSION['survey_completed'])) {
    header('Location: survey.php');
    exit;
}

// 取得使用者資料
$userId = $_SESSION['member_id'];
$sql_user = "SELECT member_name FROM member WHERE Member_id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 計算完成進度
$sql_progress = "SELECT COUNT(*) as total_questions FROM questions";
$result_progress = safeQuery($sql_progress);
$total_questions = $result_progress->result->fetch_assoc()['total_questions'];

$sql_answered = "SELECT COUNT(*) as answered_questions FROM answers WHERE user_id = ?";
$stmt = $conn->prepare($sql_answered);
$stmt->bind_param("i", $userId);
$stmt->execute();
$answered = $stmt->get_result()->fetch_assoc();
$stmt->close();

$progress = $total_questions > 0 ? round(($answered['answered_questions'] / $total_questions) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>問卷完成</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
    <link rel="stylesheet" href="form_default.css?v=<?php echo filemtime('form_default.css'); ?>">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .progress-circle {
            width: 120px;
            height: 120px;
            border: 10px solid #e9ecef;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 20px;
            position: relative;
        }
        <?php 
            $clipToX = $progress;
            $clipToY = $progress;
        ?>
        .progress-fill {
            width: 100px;
            height: 100px;
            border: 10px solid #28a745;
            border-radius: 50%;
            position: absolute;
            clip-path: path("M 50 0 A 50,50 0 1,1 49,0 L 50 50 z");
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        .action-buttons {
            margin-top: 30px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
        }
        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="success-icon">
                🎉
            </div>
            <h1 style="margin-bottom: 20px;">問卷完成！</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">
                感謝您花費寶貴時間填寫問卷
            </p>

            <div class="progress-circle">
                <div class="progress-fill"></div>
                <div class="progress-text">
                    <?php echo $progress; ?>%
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">
                        <?php echo $answered['answered_questions']; ?>
                    </div>
                    <div class="stat-label">
                        已回答問題
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <?php echo $total_questions; ?>
                    </div>
                    <div class="stat-label">
                        總問題數
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <?php echo $progress; ?>%
                    </div>
                    <div class="stat-label">
                        完成進度
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="index.php" class="btn-primary">返回首頁</a>
                <a href="view_results.php" class="btn-secondary">查看結果</a>
            </div>
        </div>
    </div>
</body>
</html>
