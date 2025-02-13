<?php
include 'config.php'; // Include database connection

if (!isset($_GET['id'])) {
    die("Admin ID is required.");
}

$id = intval($_GET['id']);
$message = "";

// Fetch admin details
$sql = "SELECT * FROM admin_users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$admin = $stmt->fetch();

if (!$admin) {
    die("Admin not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email)) {
        if (!empty($password)) {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE admin_users SET username = ?, email = ?, password = ? WHERE id = ?";
            $params = [$username, $email, $hashed_password, $id];
        } else {
            // Update without changing password
            $update_sql = "UPDATE admin_users SET username = ?, email = ? WHERE id = ?";
            $params = [$username, $email, $id];
        }

        $update_stmt = $pdo->prepare($update_sql);
        try {
            $update_stmt->execute($params);
            $message = "✅ Admin updated successfully!";
        } catch (PDOException $e) {
            $message = "❌ Error: " . $e->getMessage();
        }
    } else {
        $message = "❌ Username and email are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
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
    <h2>Edit Admin</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password (Leave blank to keep current password)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-custom w-100">Update Admin</button>
    </form>
    <div class="text-center mt-3">
        <a href="manage_admins.php">⬅ Back to Admin List</a>
    </div>
</div>

</body>
</html>
