<?php
require 'config.php';

if (isset($_GET['token'])) {
    $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
    
    try {
        $stmt = $pdo->prepare("UPDATE subscribers SET is_verified = 1 WHERE verification_token = ?");
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Email verified successfully!";
        } else {
            $_SESSION['error'] = "Invalid verification token";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Verification failed: " . $e->getMessage();
    }
}

header("Location: index.php");
exit();
?>