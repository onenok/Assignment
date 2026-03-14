<?php
session_start();
require_once 'connect.php';

// 檢查是否已登入
if (empty($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['member_id'];

// 檢查是否已completed survey
$sql_answered = "SELECT COUNT(*) as answered_questions FROM answers WHERE user_id = ?";
$stmt = $conn->prepare($sql_answered);
$stmt->bind_param("i", $userId);
$stmt->execute();
$answered = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($answered['answered_questions'] <= 0) {
    header('location: survey.php');
    exit;
}

// 計算完成進度
$sql_progress = "SELECT COUNT(*) as total_questions FROM questions";
$result_progress = safeQuery($sql_progress);
$total_questions = $result_progress->result->fetch_assoc()['total_questions'];


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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }

        @property --progress {
            syntax: '<angle>';
            inherits: false;
            initial-value: 0deg;
        }

        .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#007bff 0deg var(--progress), #e9ecef var(--progress) 360deg);
            position: relative;
            margin: 0 auto 20px;
            animation: fillProgress 2s ease-out forwards;
            transition: --progress 0s ease-out;
        }

        /* 動畫從 0 開始填滿 */
        @keyframes fillProgress {
            from {
                --progress: 0deg;
            }

            to {
                --progress: <?php echo $progress * 3.6; ?>deg;
            }
        }

        /* 中間數字 */
        .progress-circle::after {
            content: attr(data-progress) "%";
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            z-index: 2;
        }

        /* 確保 mask 相容 */
        @-webkit-keyframes fillProgress {
            from {
                --progress: 0deg;
            }

            to {
                --progress: <?php echo $progress * 3.6; ?>deg;
            }
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
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-content: center;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>
    <?php require_once 'nav.php'; ?>
    <div class="container">
        <div class="card">
            <div class="success-icon">
                🎉
            </div>
            <h1 style="margin-bottom: 20px;">問卷完成！</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">
                感謝您花費寶貴時間填寫問卷
            </p>

            <div class="progress-circle"
                style="--progress: <?php echo $progress * 3.6; ?>deg;"
                data-progress="<?php echo $progress; ?>">
                <svg>
                    <circle r="47" cx="60" cy="60" fill="#fff"></circle>
                </svg>
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