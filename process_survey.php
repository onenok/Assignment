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