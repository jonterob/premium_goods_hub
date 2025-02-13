<?php
session_start();
include '../config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Check if an ID is provided for editing
if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];

    // Fetch user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    // Handle form submission to update user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $update_stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $update_stmt->bindParam(':name', $name);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $update_stmt->execute();
        header("Location: manage_users.php"); // Redirect after update
        exit;
    }
} else {
    header("Location: manage_users.php"); // Redirect if no user ID is set
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="manage_users.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>
    
    <div class="container mt-4">
        <h1>Edit User</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</body>
</html>
