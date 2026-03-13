<?php
session_start();
require_once 'connect.php';

// 檢查是否已登入
if (empty($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['member_id'];

// 取得使用者的問卷答案
$sql_answers = "SELECT a.*, q.question_text, q.question_order, p.page_title 
                FROM answers a 
                JOIN questions q ON a.question_id = q.question_id 
                JOIN survey_pages p ON q.page_id = p.page_id 
                WHERE a.user_id = ? 
                ORDER BY p.page_number, q.question_order";
$stmt = $conn->prepare($sql_answers);
$stmt->bind_param("i", $userId);
$stmt->execute();
$answers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 按頁面分組答案
$answers_by_page = [];
foreach ($answers as $answer) {
    $page_title = $answer['page_title'];
    if (!isset($answers_by_page[$page_title])) {
        $answers_by_page[$page_title] = [];
    }
    $answers_by_page[$page_title][] = $answer;
}


?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>我的問卷結果</title>
    <link rel="stylesheet" href="form_default.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .page-title {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        .question-item {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .question-text {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .answer-text {
            color: #666;
            padding: 10px;
            background: white;
            border-left: 3px solid #007bff;
            border-radius: 4px;
        }
        .empty-answer {
            color: #999;
            font-style: italic;
        }
        .back-button {
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .back-button:hover {
            background: #5a6268;
        }
        .no-answers {
            text-align: center;
            padding: 50px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-button">← 返回首頁</a>

        <h1 style="text-align: center; margin-bottom: 40px;">我的問卷結果</h1>

        <?php if (empty($answers_by_page)): ?>
            <div class="card">
                <div class="no-answers">
                    <p>您尚未填寫任何問卷。</p>
                    <a href="survey.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">開始填寫問卷</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($answers_by_page as $page_title => $questions): ?>
                <div class="card">
                    <h2 class="page-title"><?php echo htmlspecialchars($page_title); ?></h2>
                    
                    <?php foreach ($questions as $q): ?>
                        <div class="question-item">
                            <div class="question-text">
                                <?php echo htmlspecialchars($q['question_order'] . '. ' . $q['question_text']); ?>
                            </div>
                            <div class="answer-text">
                                <?php 
                                if (empty(trim($q['answer_text']))) {
                                    echo '<span class="empty-answer">(未填寫)</span>';
                                } else {
                                    echo nl2br(htmlspecialchars($q['answer_text']));
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>