<?php
session_start();
include '../config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission to add subscriber
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Check if the email already exists
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM subscribers WHERE email = :email");
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    $count = $check_stmt->fetchColumn();

    if ($count > 0) {
        // Email exists, show error message
        $error_message = "This email is already subscribed!";
    } else {
        // Insert new subscriber if email is not taken
        $insert_stmt = $pdo->prepare("INSERT INTO subscribers (name, email) VALUES (:name, :email)");
        $insert_stmt->bindParam(':name', $name);
        $insert_stmt->bindParam(':email', $email);
        $insert_stmt->execute();
        
        header("Location: manage_subscribers.php"); // Redirect after adding
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subscriber</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="add_user.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container mt-4">
        <h1>Add New Subscriber</h1>
        
        <!-- Display error message if email already exists -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-success">Add Subscriber</button>
        </form>
    </div>
</body>
</html>
