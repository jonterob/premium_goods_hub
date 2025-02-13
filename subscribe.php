<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Check existing subscriptions
            $stmt = $pdo->prepare("SELECT email FROM subscribers WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                header("Location: index.php?error=already_subscribed");
                exit();
            } else {
                // Generate verification token
                $token = bin2hex(random_bytes(32));
                
                $stmt = $pdo->prepare("INSERT INTO subscribers (email, verification_token) VALUES (?, ?)");
                $stmt->execute([$email, $token]);
                
                // Send verification email
                $subject = "Verify your newsletter subscription";
                $message = "Click to verify: http://yourdomain.com/verify.php?token=$token";
                $headers = "From: newsletter@premiumgoodshub.com";
                
                mail($email, $subject, $message, $headers);
                
                header("Location: index.php?success=subscribed");
                exit();
            }
        } catch (PDOException $e) {
            header("Location: index.php?error=db_error");
            exit();
        }
    } else {
        header("Location: index.php?error=empty_fields");
        exit();
    }
}
?>