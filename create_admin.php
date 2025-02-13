<?php
require 'db.php'; // Include database connection

$username = 'admin'; // Your username
$password = 'admin002'; // Plain text password
$email = 'jonterob756@gmail.com'; // Admin email

// Check if the username already exists
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user) {
    echo "The username 'admin' already exists. Please delete the existing user first.";
} else {
    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new admin user with email
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);

    echo "Admin user created with username: $username and hashed password!";
}
?>
