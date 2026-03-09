<?php
session_start();
require_once 'connect.php';

// Check if user is logged in
$isLoggedIn = !empty($_SESSION['login']);
if (!$isLoggedIn) {
    header('Location: login.php');
    exit;
}

// Get CSRF token from session
$csrfToken = $_SESSION['csrf_token'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $csrfToken) {
        // Sanitize and validate input
        $question1 = htmlspecialchars(trim($_POST['question1']));
        $question2 = htmlspecialchars(trim($_POST['question2']));
        $question3 = htmlspecialchars(trim($_POST['question3']));
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO survey (user_id, question1, question2, question3) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $_SESSION['login'], $question1, $question2, $question3);
        
        if ($stmt->execute()) {
            header('Location: survey.php?success=1');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid CSRF token";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>問卷</title>
</head>
<body>
    <h1>請填寫問卷</h1>
    <form method="POST" action="process_survey.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        
        <label>問題1:</label><br>
        <textarea name="question1" rows="4" cols="50"></textarea><br><br>
        
        <label>問題2:</label><br>
        <textarea name="question2" rows="4" cols="50"></textarea><br><br>
        
        <label>問題3:</label><br>
        <textarea name="question3" rows="4" cols="50"></textarea><br><br>
        
        <input type="submit" value="提交">
    </form>
    
    <?php if (isset($_GET['success'])): ?>
        <p>感謝您完成問卷！</p>
    <?php endif; ?>
</body>
</html>