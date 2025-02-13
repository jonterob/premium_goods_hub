<?php
include 'config.php'; // Database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin user
        $sql = "INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$username, $email, $hashed_password]);
            $message = "✅ Admin added successfully!";
        } catch (PDOException $e) {
            $message = "❌ Error: " . $e->getMessage();
        }
    } else {
        $message = "❌ All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
</nav>
<div class="container">
    <h2>Add New Admin</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-custom w-100">Add Admin</button>
    </form>
    <div class="text-center mt-3">
        <a href="manage_admins.php">⬅ Back to Admin List</a>
    </div>
</div>

</body>
</html>
