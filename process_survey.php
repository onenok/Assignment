<?php
session_start();
require_once 'connect.php';

// 檢查是否已登入
if (empty($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['member_id'];

// CSRF token 驗證
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: survey.php?msg=invalid_token');
    exit;
}

// 開始
$conn->begin_transaction();

try {
    // 取得所有問題ID
    $sql_questions = "SELECT question_id, is_required FROM questions";
    $result_questions = safeQuery($sql_questions);
    $all_questions = $result_questions->result->fetch_all(MYSQLI_ASSOC);
    
    // 驗證所有必填問題
    foreach ($all_questions as $question) {
        if ($question['is_required']) {
            $field_name = 'question_' . $question['question_id'];
            if (!isset($_POST[$field_name]) || (is_array($_POST[$field_name]) && empty($_POST[$field_name])) || (!is_array($_POST[$field_name]) && trim($_POST[$field_name]) === '')) {
                throw new Exception('請填寫所有必填問題');
            }
        }
    }
    
    // 處理每個答案
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_id = intval(str_replace('question_', '', $key));
            
            // 檢查問題是否存在
            $question_exists = false;
            foreach ($all_questions as $q) {
                if ($q['question_id'] == $question_id) {
                    $question_exists = true;
                    break;
                }
            }
            
            if (!$question_exists) {
                continue;
            }
            
            // 處理多選值
            if (is_array($value)) {
                $value = implode(', ', $value);
            } else {
                $value = trim($value);
            }
            
            // 插入答案
            $sql_insert = "INSERT INTO answers (user_id, question_id, answer_text) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("iis", $userId, $question_id, $value);
            
            if (!$stmt->execute()) {
                throw new Exception('儲存答案時發生錯誤');
            }
            
            $stmt->close();
        }
    }
    
    // 提交
    $conn->commit();
    
    // 清除問卷session標記
    $_SESSION['survey_completed'] = true;
    
    header('Location: survey.php?success=1');
    exit;
    
} catch (Exception $e) {
    // 回滾
    $conn->rollback();
    
    // 記錄錯誤
    error_log('問卷提交錯誤: ' . $e->getMessage());
    
    // 返回錯誤訊息
    header('Location: survey.php?error=' . urlencode($e->getMessage()));
    exit;
}

$conn->close();
?>