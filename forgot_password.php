<?php
require 'db.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate reset token
        $token = bin2hex(random_bytes(50));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

        // Store token in database
        $stmt = $pdo->prepare("UPDATE admin_users SET reset_token = ?, token_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        // Send password reset email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jonterob756@gmail.com'; // Your email
            $mail->Password = 'your-app-password'; // App Password (Not your actual password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jonterob756@gmail.com', 'Premium Goods Hub');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click <a href='http://yourwebsite.com/reset_password.php?token=$token'>here</a> to reset your password.";

            $mail->send();
            echo "<p class='success-message'>A password reset link has been sent to your email.</p>";
        } catch (Exception $e) {
            echo "<p class='error-message'>Email sending failed: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p class='error-message'>No account found with this email.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot_password.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
