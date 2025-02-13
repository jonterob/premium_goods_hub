<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input to prevent empty submissions
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        // Redirect with an error if any field is empty
        header("Location: index.php?error=empty_fields");
        exit();
    }

    try {
        // Prepare and execute the feedback insertion query
        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);

        // Redirect with success message
        header("Location: index.php?success=feedback");
        exit();
    } catch (PDOException $e) {
        // Handle any database errors
        header("Location: index.php?error=db_error");
        exit();
    }
}
?>