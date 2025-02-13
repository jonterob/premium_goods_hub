<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Check if token is valid
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE reset_token = ? AND token_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Update password
        $stmt = $pdo->prepare("UPDATE admin_users SET password = ?, reset_token = NULL, token_expires = NULL WHERE reset_token = ?");
        $stmt->execute([$new_password, $token]);

        echo "Password reset successful! <a href='login.php'>Login</a>";
    } else {
        echo "Invalid or expired token!";
    }
}

// Get token from URL
$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
