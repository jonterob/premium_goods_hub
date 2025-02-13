<?php
include 'config.php'; // Include database connection

// Fetch admin users
$sql = "SELECT * FROM admin_users";
$stmt = $pdo->query($sql);
$admins = $stmt->fetchAll(); // Fetch all admins

// Delete admin user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_admins.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 8px 15px;
            text-decoration: none;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            color:blue;
            text-decoration: none;
        }
        .delete-btn:hover {
            text-decoration: underline;
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
    <h2>Manage Admin Users</h2>
    <div class="table-container">
        <div class="mb-3">
            <a href="add_admin.php" class="btn btn-custom">➕ Add New Admin</a>
        </div>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['last_login'] ? htmlspecialchars($row['last_login']) : 'Never' ?></td>
                    <td>
                        <a href="edit_admin.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="manage_admins.php?delete=<?= $row['id'] ?>" class="delete-btn btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
