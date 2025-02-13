<?php
session_start();
require 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if the form is submitted
    $username = $_POST['username']; // Get the entered username
    $password = $_POST['password']; // Get the entered password

    // Prepare an SQL statement to fetch the user details based on the entered username
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]); // Execute the query with the provided username
    $user = $stmt->fetch(); // Fetch the user details from the database

    // Check if the user exists and verify the entered password against the hashed password in the database
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true; // Set a session variable to indicate the admin is logged in
        $_SESSION['admin_username'] = $user['username']; // Store the admin's username in session
        header("Location: dashboard.php"); // Redirect to the admin dashboard
        exit; // Ensure no further code executes after redirection
    } else {
        $error = "Invalid credentials!"; // Display an error message if login fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if(isset($error)) echo "<p class='error-message'>$error</p>"; // Display error message ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required> <!-- Username input -->
            <input type="password" name="password" placeholder="Password" required> <!-- Password input -->
            <button type="submit">Login</button> <!-- Submit button -->
        </form>
        <p><a href="forgot_password.php">Forgot Password?</a></p>
    </div>
</body>
</html>
