<?php
session_start();
require_once 'connect.php';
require_once 'array_any.php';
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
    // CSRF token 驗證
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        header('Location: survey.php?msg=invalid_token');
        exit;
    }

    $page_id = intval($_POST['page_id']);

    // 取得當前頁面的所有問題
    $sql_questions = "SELECT question_id, is_required FROM questions WHERE page_id = ? ORDER BY question_order";
    $stmt = $conn->prepare($sql_questions);
    $stmt->bind_param("i", $page_id);
    $stmt->execute();
    $currPageQuestions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // 驗證必填問題
    $errors = [];
    $questionsIdOfCurrPage = [];
    foreach ($currPageQuestions as $question) {
        $questionsIdOfCurrPage[] = $question['question_id'];
        if ($question['is_required']) {
            $field_name = 'question_' . $question['question_id'];
            if (!isset($_POST[$field_name]) || (is_array($_POST[$field_name]) && empty($_POST[$field_name])) || (!is_array($_POST[$field_name]) && trim($_POST[$field_name]) === '')) {
                $errors[] = '請填寫所有必填問題';
                break;
            }
        }
    }

    if (empty($errors)) {
        //var_dump(print_r($questionsIdOfCurrPage, true));
        // 儲存答案到session
        foreach ($questionsIdOfCurrPage as $question_id) {
            $field_name = 'question_' . $question_id;
            if (isset($_POST[$field_name])) {
                $value = $_POST[$field_name];
                $key = $field_name;
                $hasOther = isset($_POST[$field_name . '_has_other']) && $_POST[$field_name . '_has_other'] === '1';
                $otherValue = $hasOther ? $_POST[$field_name . '_other'] ?? '' : null;
                $saved_answers[$question_id] = [
                    'main' => is_array($value) ? implode(', ', $value) : trim($value),
                    'other' => $otherValue != null ? trim($otherValue) : null,
                    'hasOther' => $hasOther,
                ];
            }
        }
        $_SESSION['survey_answers'] = $saved_answers;
        //var_dump($questionsIdOfCurrPage);
        //echo "<br><br>";
        //var_dump($saved_answers);
        //echo "<br><br>";
        //var_dump($_SESSION['survey_answers']);
        // 導航到下一頁
        if ($_POST['save_page'] == 1) {
            if ($current_page < count($pages)) {
                header('Location: survey.php?page=' . ($current_page + 1));
                exit;
            } else {
                // 最後一頁時導向雙重檢查頁面
                header('Location: survey.php?check=1');
                exit;
            }
        } elseif ($_POST['save_page'] == 2) {
            if ($current_page > 0) {
                header('Location: survey.php?page=' . ($current_page - 1));
            }
            exit;
        }
    }
}
if (isset($_GET['error'])) {
    $errors[] = $_GET['error']; // 從URL參數中獲取錯誤消息
};
$checking = false;
if (isset($_GET['check'])) {
    $checking = true;
}
// 取得當前頁面的問題
$sql_questions = "SELECT q.*, t.type_name 
                  FROM questions q 
                  JOIN question_types t ON q.type_id = t.type_id 
                  ORDER BY q.page_id ASC, q.question_order ASC";
$stmt = $conn->prepare($sql_questions);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">

    <?php if ($checking): ?>
        <title>問卷調查 - 確認輸入資料</title>
    <?php else: ?>
        <title>問卷調查 - 第<?php echo $current_page; ?>頁</title>
    <?php endif; ?>
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

        .question-check {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .question-check h2 {
            margin-top: 0;
            color: #333;
        }

        .question-check h3 {
            margin-top: 0;
            color: #333;
        }

        .question-check p {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }

        .edit-btn {
            display: inline-block;
            padding: 5px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .edit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <?php require_once 'nav.php'; ?>
    <div class="form-container">
        <?php if ($checking): ?>
            <div class="form-container">
                <h1 style="text-align: center; margin-bottom: 30px;">問卷調查 - 確認輸入資料</h1>

                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%"></div>
                </div>

                <div class="question-container">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <?php
                    $questionsWithGroup = [];
                    foreach ($questions as $question) {
                        $questionsWithGroup[$question['page_id']][] = $question;
                    }
                    ?>
                    <?php foreach ($pages as $page): ?>
                        <div class="question-check">
                            <h2><?php echo htmlspecialchars($page['page_title']); ?></h2>
                            <?php foreach ($questionsWithGroup[$page['page_id']] as $question):
                                $question_id = $question['question_id'];
                                $saved_value = $saved_answers[$question_id]['main'] ?? '';
                                $saved_other = $saved_answers[$question_id]['other'] ?? '';
                                $hasOther = $saved_answers[$question_id]['hasOther'] ?? false;

                                // 格式化答案顯示
                                $displayValue = $saved_value;
                                if ($hasOther && $saved_other) {
                                    $displayValue .= ' (其他: ' . htmlspecialchars($saved_other) . ')';
                                }

                                if (is_array($saved_value)) {
                                    $displayValue = implode(', ', $saved_value);
                                    if ($hasOther && $saved_other) {
                                        $displayValue .= ' (其他: ' . htmlspecialchars($saved_other) . ')';
                                    }
                                }
                            ?>
                                <h3><?php echo htmlspecialchars($question['question_text']); ?></h3>
                                <p><?php echo !empty($displayValue) ? htmlspecialchars($displayValue) : '(未回答)'; ?></p>
                                <a href="survey.php?page=<?php echo $question['page_id']; ?>" class="edit-btn">編輯</a>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else:; ?>
            <h1 style="text-align: center; margin-bottom: 30px;">問卷調查</h1>

            <?php if (!empty($errors) && count($errors) != 0): ?>
                <div class="error-box">
                    <?php echo trim(implode('<br>', $errors)); ?>
                </div>
            <?php endif; ?>

            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo ($current_page / count($pages)) * 100; ?>%"></div>
            </div>

            <div class="page-indicator">
                第 <?php echo $current_page; ?> 頁 / 共 <?php echo count($pages); ?> 頁
            </div>

        <?php endif; ?>
        <form id="surveyForm" method="POST" action="">

            <?php
            $page_id = $pages[$current_page - 1]['page_id'];
            $isAllInputHidden = $checking ? 'style=display:none;' : '';
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
            <input type="hidden" name="save_page" value="1">

            <div class="question-container" <?php echo htmlspecialchars($isAllInputHidden); ?>>
                <h2 class="page-title"><?php echo htmlspecialchars($pages[$current_page - 1]['page_title']); ?></h2>
                <?php if (!empty($pages[$current_page - 1]['page_description'])): ?>
                    <p class="page-description"><?php echo htmlspecialchars($pages[$current_page - 1]['page_description']); ?></p>
                <?php endif; ?>

                <!-- debug -->
                <div style="display:none;">
                    <pre>
                        <?php echo htmlspecialchars(print_r($saved_answers, true)) ?>
                    </pre>
                </div>

                <?php
                foreach ($questions as $question):
                    $question_id = $question['question_id'];
                    $question_name = 'question_' . $question_id;
                    $shouldList = $question['page_id'] == $page_id || $checking;
                    $pre_isRequired = $question['is_required'] ? 'required' : '';
                    $isRequired = $pre_isRequired;
                    $requiredClass = $pre_isRequired;
                    $saved_value = $saved_answers[$question_id]['main'] ?? '';
                    $saved_other = $saved_answers[$question_id]['other'] ?? '';
                ?>
                    <?php if ($shouldList): ?>
                        <div class="form-input-group">
                            <label class="form-label <?php echo $requiredClass; ?>">
                                <?php echo htmlspecialchars($question['question_text']); ?>
                            </label>
                            <div class="debug-info" style="display:none;">
                                <pre><?php echo htmlspecialchars(print_r($question, true)); ?></pre>
                                <pre><?php echo htmlspecialchars(print_r([$saved_value, $saved_other], true)); ?></pre>
                            </div>
                            <?php
                            $sql_options = "SELECT * FROM options WHERE question_id = ? ORDER BY option_order";
                            $stmt = $conn->prepare($sql_options);
                            ?>
                            <?php if ($question['type_name'] == 'radio'): ?>
                                <!-- Radio Type -->
                                <div class="form-radio-group-container">
                                    <?php
                                    $stmt->bind_param("i", $question_id);
                                    $stmt->execute();
                                    $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                    $hasOther = array_any($options, function ($o) {
                                        return $o['is_other'] == true;
                                    });
                                    ?>
                                    <input type="hidden" name="<?php echo $question_name; ?>_has_other" value="<?php echo $hasOther; ?>">
                                    <?php
                                    foreach ($options as $option):
                                        $hasSaved_value = ($saved_value == $option['option_text']);
                                        $isChecked = $hasSaved_value ? 'checked' : '';
                                        $isOtherDisabled = !$hasSaved_value ? 'disabled' : '';
                                    ?>
                                        <label class="form-radio-label">
                                            <?php if (!$hasOther): ?>

                                                <input type="radio" name="<?php echo $question_name; ?>"
                                                    value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                                    class="form-radio-input"
                                                    <?php echo $isChecked ?>>
                                                <span class="radio-checkmark"></span>
                                                <?php echo htmlspecialchars($option['option_text']); ?>

                                            <?php elseif ($hasOther && !$option['is_other']):
                                                $onChangeFunc = "
                                            this.parentNode.parentNode.querySelector('input[data-otherText]') 
                                            && 
                                            (this.parentNode.parentNode.querySelector('input[data-otherText]').disabled = true);
                                            ";
                                            ?>

                                                <input type="radio" name="<?php echo $question_name; ?>"
                                                    value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                                    class="form-radio-input"
                                                    <?php echo $isChecked ?>
                                                    onchange="<?php echo htmlspecialchars($onChangeFunc) ?>">
                                                <span class="radio-checkmark"></span>
                                                <?php echo htmlspecialchars($option['option_text']); ?>

                                            <?php else:
                                                $onChangeFunc = "
                                            this.parentNode.querySelector('input[data-otherText]') 
                                            && 
                                            (this.parentNode.querySelector('input[data-otherText]').disabled = false);
                                            ";
                                            ?>

                                                <input type="radio" name="<?php echo $question_name; ?>"
                                                    value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                                    class="form-radio-input"
                                                    <?php echo $isChecked ?>
                                                    onchange="<?php echo htmlspecialchars($onChangeFunc) ?>">
                                                <span class="radio-checkmark"></span>
                                                <?php echo htmlspecialchars($option['option_text']); ?>
                                                <input type="text" name="<?php echo $question_name; ?>_other"
                                                    value="<?php echo isset($saved_other) ? htmlspecialchars($saved_other) : ''; ?>"
                                                    class="form-other-input" placeholder="請填寫其他選項"
                                                    <?php echo htmlspecialchars($isOtherDisabled) ?>
                                                    data-otherText="true">

                                            <?php endif; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($question['type_name'] == 'checkbox'): ?>
                                <!-- Checkbox Type -->
                                <div class="form-radio-group-container">
                                    <?php
                                    $stmt->bind_param("i", $question_id);
                                    $stmt->execute();
                                    $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                    $hasOther = array_any($options, function ($o) {
                                        return $o['is_other'] == true;
                                    });
                                    $saved_values = is_array($saved_value) ? $saved_value : explode(', ', $saved_value);
                                    ?>
                                    <input type="hidden" name="<?php echo $question_name; ?>_has_other" value="<?php echo $hasOther; ?>">
                                    <?php
                                    foreach ($options as $option):
                                        $hasSaved_value = in_array($option['option_text'], $saved_values);
                                        $isChecked = $hasSaved_value ? 'checked' : '';
                                        $isOtherDisabled = !$hasSaved_value ? 'disabled' : '';
                                    ?>
                                        <label class="form-radio-label">
                                            <?php if (!$option['is_other']): ?>

                                                <input type="checkbox" name="<?php echo $question_name; ?>[]"
                                                    value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                                    class="form-radio-input"
                                                    <?php echo $isChecked ?>>
                                                <span class="checkbox-checkmark"></span>
                                                <?php echo htmlspecialchars($option['option_text']); ?>

                                            <?php else:
                                                $onChangeFunc = "
                                            this.parentNode.querySelector('input[data-otherText]') 
                                            && 
                                            (this.parentNode.querySelector('input[data-otherText]').disabled = !this.checked)
                                            ";
                                            ?>
                                                <input type="checkbox" name="<?php echo $question_name; ?>[]"
                                                    value="<?php echo htmlspecialchars($option['option_text']); ?>"
                                                    class="form-radio-input"
                                                    <?php echo $isChecked ?>
                                                    onchange="<?php echo htmlspecialchars($onChangeFunc) ?>">
                                                <span class="checkbox-checkmark"></span>
                                                <?php echo htmlspecialchars($option['option_text']); ?>
                                                <input type="text" name="<?php echo $question_name; ?>_other"
                                                    value="<?php echo isset($saved_other) ? htmlspecialchars($saved_other) : ''; ?>"
                                                    class="form-other-input" placeholder="請填寫其他選項"
                                                    <?php echo htmlspecialchars($isOtherDisabled) ?>
                                                    data-otherText="true">

                                            <?php endif; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($question['type_name'] == 'select'): ?>
                                <!-- Select Type -->
                                <select name="<?php echo $question_name; ?>"
                                    class="form-select" <?php echo $isRequired; ?>>
                                    <option value="">請選擇</option>
                                    <?php
                                    $stmt->bind_param("i", $question_id);
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
                                <!-- Text Type -->
                                <input type="text" name="<?php echo $question_name; ?>"
                                    class="form-input" <?php echo $isRequired; ?>
                                    value="<?php echo htmlspecialchars($saved_value); ?>">
                            <?php elseif ($question['type_name'] == 'textarea'): ?>
                                <!-- Textarea Type -->
                                <textarea name="<?php echo $question_name; ?>"
                                    class="form-textarea" <?php echo $isRequired; ?>><?php echo htmlspecialchars($saved_value); ?></textarea>
                            <?php elseif ($question['type_name'] == 'rating'): ?>
                                <!-- Rating Type -->
                                <div class="form-radio-group-container">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label class="form-radio-label">
                                            <input type="radio" name="<?php echo $question_name; ?>"
                                                value="<?php echo $i; ?>" class="form-radio-input"
                                                <?php echo ($saved_value == $i) ? 'checked' : ''; ?>>
                                            <span class="radio-checkmark"></span>
                                            <?php echo $i; ?>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            <?php elseif ($question['type_name'] == 'date'): ?>
                                <!-- Date Type -->
                                <input type="date" name="<?php echo $question_name; ?>"
                                    class="form-input" <?php echo $isRequired; ?>
                                    value="<?php echo htmlspecialchars($saved_value); ?>">
                            <?php elseif ($question['type_name'] == 'number'): ?>
                                <!-- Number Type -->
                                <?php
                                $stmt->bind_param("i", $question_id);
                                $stmt->execute();
                                $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                ?>
                                <input type="number" name="<?php echo $question_name; ?>"
                                    class="form-input" <?php echo $isRequired; ?>
                                    value="<?php echo htmlspecialchars($saved_value); ?>"
                                    min="<?php echo !empty($options[0]['option_text']) ? htmlspecialchars($options[0]['option_text']) : ''; ?>">
                            <?php elseif ($question['type_name'] == 'email'): ?>
                                <!-- Email Type -->
                                <input type="email" name="<?php echo $question_name; ?>"
                                    class="form-input" <?php echo $isRequired; ?>
                                    value="<?php echo htmlspecialchars($saved_value); ?>">
                            <?php elseif ($question['type_name'] == 'tel'): ?>
                                <!--Tel Type-->
                                <input type="tel" name="<?php echo $question_name; ?>"
                                    class="form-input" <?php echo $isRequired; ?>
                                    value="<?php echo htmlspecialchars($saved_value); ?>">
                            <?php elseif ($question['type_name'] == 'range'): ?>
                                <!-- Range Type -->
                                <input type="range" name="<?php echo $question_name; ?>"
                                    class="form-input" min="1" max="10" value="<?php echo !empty($saved_value) ? $saved_value : 5; ?>">
                                <span class="range-value"><?php echo !empty($saved_value) ? $saved_value : 5; ?></span>
                                <script>
                                    document.querySelector('input[name="<?php echo $question_name; ?>"]').oninput = function() {
                                        this.nextElementSibling.textContent = this.value;
                                    }
                                </script>
                            <?php endif; ?>

                            <div class="error-message" id="error_<?php echo $question_id; ?>">
                                此為必填問題
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>


            <?php if ($checking): ?>
                <div class="navigation-buttons">
                    <a href="survey.php?page=<?php echo count($pages); ?>" class="navigation-buttons button">
                        上一頁
                    </a>
                    <button type="submit" class="navigation-buttons button" onclick="return submitFinal()">
                        提交問卷
                    </button>
                </div>
            <?php else: ?>
                <div class="navigation-buttons">
                    <?php if ($current_page > 1): ?>
                        <button type="submit" name="save_page" value="2">
                            上一頁
                        </button>
                    <?php else: ?>
                        <button type="button" disabled>上一頁</button>
                    <?php endif; ?>

                    <button type="submit" name="save_page" value="1">
                        <?php if ($current_page < count($pages)): ?>
                            下一頁
                        <?php else: ?>
                            完成問卷
                        <?php endif; ?>
                    </button>
                </div>
            <?php endif; ?>
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
                    let questionId = <?php echo $question_id; ?>;
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