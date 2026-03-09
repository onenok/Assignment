<?php
session_start();
require_once 'connect.php';

// 檢查是否已登入
if (empty($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['member_id'];

// 取得所有問卷頁面
$sql_pages = "SELECT * FROM survey_pages ORDER BY page_number";
$result_pages = safeQuery($sql_pages);
$pages = $result_pages->result->fetch_all(MYSQLI_ASSOC);

// 檢查是否已完成問卷
$sql_completed = "SELECT COUNT(*) as count FROM answers WHERE user_id = ?";
$stmt = $conn->prepare($sql_completed);
$stmt->bind_param("i", $userId);
$stmt->execute();
$completed = $stmt->get_result()->fetch_assoc()['count'] > 0;
$stmt->close();

if ($completed) {
    header('Location: survey_completed.php');
    exit;
}

// 取得當前頁面
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($current_page < 1 || $current_page > count($pages)) {
    $current_page = 1;
}

// 從session載入已儲存的答案
$saved_answers = $_SESSION['survey_answers'] ?? [];

// 處理表單提交（儲存當前頁面）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_page'])) {
    $page_id = intval($_POST['page_id']);

    // 取得當前頁面的所有問題
    $sql_questions = "SELECT question_id, is_required FROM questions WHERE page_id = ? ORDER BY question_order";
    $stmt = $conn->prepare($sql_questions);
    $stmt->bind_param("i", $page_id);
    $stmt->execute();
    $questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // 驗證必填問題
    $errors = [];
    foreach ($questions as $question) {
        if ($question['is_required']) {
            $field_name = 'question_' . $question['question_id'];
            if (!isset($_POST[$field_name]) || (is_array($_POST[$field_name]) && empty($_POST[$field_name])) || (!is_array($_POST[$field_name]) && trim($_POST[$field_name]) === '')) {
                $errors[] = '請填寫所有必填問題';
                break;
            }
        }
    }

    if (empty($errors)) {
        // 儲存答案到session
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $question_id = intval(str_replace('question_', '', $key));
                if (is_array($value)) {
                    $saved_answers[$question_id] = implode(', ', $value);
                } else {
                    $saved_answers[$question_id] = trim($value);
                }
            }
        }
        $_SESSION['survey_answers'] = $saved_answers;

        // 導航到下一頁
        if ($current_page < count($pages)) {
            header('Location: survey.php?page=' . ($current_page + 1));
            exit;
        }
    }
}

// 取得當前頁面的問題
$sql_questions = "SELECT q.*, t.type_name 
                  FROM questions q 
                  JOIN question_types t ON q.type_id = t.type_id 
                  ORDER BY q.question_order";
$stmt = $conn->prepare($sql_questions);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title>問卷調查 - 第<?php echo $current_page; ?>頁</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
    <link rel="stylesheet" href="form_default.css?v=<?php echo filemtime('form_default.css'); ?>">
    <style>
        .progress-bar {
            background-color: #f3f3f3;
            border-radius: 20px;
            padding: 5px;
            margin-bottom: 20px;
        }

        .progress-fill {
            background-color: #007bff;
            height: 20px;
            border-radius: 20px;
            transition: width 0.3s ease;
        }

        .page-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .page-nav button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .page-nav button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .question-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .page-description {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .form-input-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .required::after {
            content: " *";
            color: red;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .navigation-buttons button {
            padding: 12px 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .navigation-buttons button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .page-indicator {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            color: #666;
        }

        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <?php require_once 'nav.php'; ?>
    <div class="form-container">
        <h1 style="text-align: center; margin-bottom: 30px;">問卷調查</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                感謝您完成問卷！您的答案已成功提交。
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php echo implode('<br>', $errors); ?>
            </div>
        <?php endif; ?>

        <div class="progress-bar">
            <div class="progress-fill" style="width: <?php echo ($current_page / count($pages)) * 100; ?>%"></div>
        </div>

        <div class="page-indicator">
            第 <?php echo $current_page; ?> 頁 / 共 <?php echo count($pages); ?> 頁
        </div>

        <form id="surveyForm" method="POST" action="">
            <?php $page_id = $pages[$current_page - 1]['page_id']; ?>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
            <input type="hidden" name="save_page" value="1">

            <div class="question-container">
                <h2 class="page-title"><?php echo htmlspecialchars($pages[$current_page - 1]['page_title']); ?></h2>
                <?php if (!empty($pages[$current_page - 1]['page_description'])): ?>
                    <p class="page-description"><?php echo htmlspecialchars($pages[$current_page - 1]['page_description']); ?></p>
                <?php endif; ?>

                <?php foreach ($questions as $question):
                    $isRequired = $question['is_required'] ? 'required' : '';
                    $requiredClass = $question['is_required'] ? 'required' : '';
                    $saved_value = $saved_answers[$question['question_id']] ?? '';
                ?>
                    <div class="form-input-group" 
                    <?php if ($question['page_id'] != $page_id): ?>
                    style="display:none;"
                    <?php endif; ?>
                    >
                        <label class="form-label <?php echo $requiredClass; ?>">
                            <?php echo htmlspecialchars($question['question_text']); ?>
                        </label>
                        <div class="debug-info" style="display:none;">
                            <pre><?php echo htmlspecialchars(print_r($question, true)); ?></pre>
                        </div>
                        <?php
                        $sql_options = "SELECT * FROM options WHERE question_id = ? ORDER BY option_order";
                        $stmt = $conn->prepare($sql_options);
                        ?>
                        <?php if ($question['type_name'] == 'radio'): ?>
                            <div class="form-radio-group-container">
                                <?php
                                $stmt->bind_param("i", $question['question_id']);
                                $stmt->execute();
                                $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                foreach ($options as $option):
                                ?>
                                    <label class="form-radio-label">
                                        <input type="radio" name="question_<?php echo $question['question_id']; ?>"
                                            value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                            class="form-radio-input"
                                            <?php echo ($saved_value == $option['option_text']) ? 'checked' : ''; ?>>
                                        <span class="radio-checkmark"></span>
                                        <?php echo htmlspecialchars($option['option_text']); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($question['type_name'] == 'checkbox'): ?>
                            <div class="form-radio-group-container">
                                <?php
                                $stmt->bind_param("i", $question['question_id']);
                                $stmt->execute();
                                $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                $saved_values = is_array($saved_value) ? $saved_value : explode(', ', $saved_value);
                                foreach ($options as $option):
                                ?>
                                    <label class="form-radio-label">
                                        <input type="checkbox" name="question_<?php echo $question['question_id']; ?>[]"
                                            value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                            class="form-radio-input"
                                            <?php echo in_array($option['option_text'], $saved_values) ? 'checked' : ''; ?>>
                                        <span class="checkbox-checkmark"></span>
                                        <?php echo htmlspecialchars($option['option_text']); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($question['type_name'] == 'select'): ?>
                            <select name="question_<?php echo $question['question_id']; ?>"
                                class="form-select" <?php echo $isRequired; ?>>
                                <option value="">請選擇</option>
                                <?php
                                $stmt->bind_param("i", $question['question_id']);
                                $stmt->execute();
                                $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                foreach ($options as $option):
                                ?>
                                    <option value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                        <?php echo ($saved_value == $option['option_text']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($option['option_text']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        <?php elseif ($question['type_name'] == 'text'): ?>
                            <input type="text" name="question_<?php echo $question['question_id']; ?>"
                                class="form-input" <?php echo $isRequired; ?>
                                value="<?php echo htmlspecialchars($saved_value); ?>">

                        <?php elseif ($question['type_name'] == 'textarea'): ?>
                            <textarea name="question_<?php echo $question['question_id']; ?>"
                                class="form-textarea" <?php echo $isRequired; ?>><?php echo htmlspecialchars($saved_value); ?></textarea>

                        <?php elseif ($question['type_name'] == 'rating'): ?>
                            <div class="form-radio-group-container">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label class="form-radio-label">
                                        <input type="radio" name="question_<?php echo $question['question_id']; ?>"
                                            value="<?php echo $i; ?>" class="form-radio-input"
                                            <?php echo ($saved_value == $i) ? 'checked' : ''; ?>>
                                        <span class="radio-checkmark"></span>
                                        <?php echo $i; ?>
                                    </label>
                                <?php endfor; ?>
                            </div>

                        <?php elseif ($question['type_name'] == 'date'): ?>
                            <input type="date" name="question_<?php echo $question['question_id']; ?>"
                                class="form-input" <?php echo $isRequired; ?>
                                value="<?php echo htmlspecialchars($saved_value); ?>">

                        <?php elseif ($question['type_name'] == 'number'): ?>
                            <?php
                            $stmt->bind_param("i", $question['question_id']);
                            $stmt->execute();
                            $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                            ?>
                            <input type="number" name="question_<?php echo $question['question_id']; ?>"
                                class="form-input" <?php echo $isRequired; ?>
                                value="<?php echo htmlspecialchars($saved_value); ?>"
                                min="<?php echo !empty($options[0]['option_text']) ? htmlspecialchars($options[0]['option_text']) : ''; ?>">

                        <?php elseif ($question['type_name'] == 'email'): ?>
                            <input type="email" name="question_<?php echo $question['question_id']; ?>"
                                class="form-input" <?php echo $isRequired; ?>
                                value="<?php echo htmlspecialchars($saved_value); ?>">

                        <?php elseif ($question['type_name'] == 'tel'): ?>
                            <input type="tel" name="question_<?php echo $question['question_id']; ?>"
                                class="form-input" <?php echo $isRequired; ?>
                                value="<?php echo htmlspecialchars($saved_value); ?>">

                        <?php elseif ($question['type_name'] == 'range'): ?>
                            <input type="range" name="question_<?php echo $question['question_id']; ?>"
                                class="form-input" min="1" max="10" value="<?php echo !empty($saved_value) ? $saved_value : 5; ?>">
                            <span class="range-value"><?php echo !empty($saved_value) ? $saved_value : 5; ?></span>
                            <script>
                                document.querySelector('input[name="question_<?php echo $question['question_id']; ?>"]').oninput = function() {
                                    this.nextElementSibling.textContent = this.value;
                                }
                            </script>
                        <?php endif; ?>

                        <div class="error-message" id="error_<?php echo $question['question_id']; ?>">
                            此為必填問題
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="navigation-buttons">
                <?php if ($current_page > 1): ?>
                    <button type="button" onclick="location.href='survey.php?page=<?php echo $current_page - 1; ?>'">
                        上一頁
                    </button>
                <?php else: ?>
                    <button type="button" disabled>上一頁</button>
                <?php endif; ?>

                <?php if ($current_page < count($pages)): ?>
                    <button type="submit" name="save_page" value="1">
                        下一頁
                    </button>
                <?php else: ?>
                    <button type="button" onclick="submitFinal()">
                        提交問卷
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script>
        function submitFinal() {
            if (validateForm()) {
                if (confirm('確定要提交問卷嗎？提交後將無法修改。')) {
                    document.getElementById('surveyForm').action = 'process_survey.php';
                    document.getElementById('surveyForm').submit();
                }
            }
        }

        function validateForm() {
            let isValid = true;
            <?php foreach ($questions as $question): ?>
                if (<?php echo $question['is_required'] ? 'true' : 'false'; ?>) {
                    let questionId = <?php echo $question['question_id']; ?>;
                    let questionName = 'question_' + questionId;
                    let element = document.querySelector('input[name="' + questionName + '"], select[name="' + questionName + '"], textarea[name="' + questionName + '"]');

                    if (element) {
                        if (element.type === 'radio' || element.type === 'checkbox') {
                            if (!document.querySelector('input[name="' + questionName + '"]:checked')) {
                                showError(questionId);
                                isValid = false;
                            } else {
                                hideError(questionId);
                            }
                        } else if (element.type === 'select-one') {
                            if (element.value === '') {
                                showError(questionId);
                                isValid = false;
                            } else {
                                hideError(questionId);
                            }
                        } else {
                            if (element.value.trim() === '') {
                                showError(questionId);
                                isValid = false;
                            } else {
                                hideError(questionId);
                            }
                        }
                    }
                }
            <?php endforeach; ?>

            return isValid;
        }

        function showError(questionId) {
            document.getElementById('error_' + questionId).style.display = 'block';
        }

        function hideError(questionId) {
            document.getElementById('error_' + questionId).style.display = 'none';
        }
    </script>
</body>

</html>