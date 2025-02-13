<?php
include '../config.php';

// Check if a session is already started before calling session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle deletion if requested
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM feedback WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['message'] = "Feedback deleted successfully!";
        header("Location: manage_feedback.php");
        exit();
    }
}

// Fetch all feedback records
$stmt = $pdo->query("SELECT * FROM feedback ORDER BY created_at DESC");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../admin_styles.css"> <!-- Custom Styles -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
</nav>
<div class="container mt-4">
    <h2 class="text-center mb-4"><i class="fas fa-comments"></i> Customer Feedback</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?= htmlspecialchars($feedback['id']); ?></td>
                        <td><?= htmlspecialchars($feedback['name']); ?></td>
                        <td><?= htmlspecialchars($feedback['email']); ?></td>
                        <td><?= htmlspecialchars($feedback['message']); ?></td>
                        <td><?= htmlspecialchars($feedback['created_at']); ?></td>
                        <td>
                            <a href="manage_feedback.php?delete=<?= $feedback['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this feedback?');">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php" class="btn btn-primary mt-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
