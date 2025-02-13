<?php
session_start();
include '../config.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch all subscribers from the database
$query = "SELECT * FROM subscribers"; // Adjust according to your `subscribers` table structure
$stmt = $pdo->query($query);
$subscribers = $stmt->fetchAll();

// Handle subscriber deletion
if (isset($_GET['delete'])) {
    $subscriber_id = (int)$_GET['delete'];
    $delete_query = "DELETE FROM subscribers WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->bindParam(':id', $subscriber_id, PDO::PARAM_INT);
    $delete_stmt->execute();
    header("Location: manage_subscribers.php"); // Redirect after deletion
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscribers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="manage_subscribers.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container mt-4">
        <h1>Subscribers</h1>
        <a href="add_subscriber.php" class="btn btn-success mb-3">Add New Subscriber</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscribers as $subscriber): ?>
                    <tr>
                        <td><?= $subscriber['id'] ?></td>
                        <td><?= $subscriber['email'] ?></td>
                        <td>
                            <a href="edit_subscriber.php?id=<?= $subscriber['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="?delete=<?= $subscriber['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subscriber?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
