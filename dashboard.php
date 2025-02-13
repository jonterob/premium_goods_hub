<?php
session_start();
include '../config.php'; 

// Redirect if admin is not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Auto logout after inactivity (10 minutes)
$timeout_duration = 600;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); 

// Fetch dashboard statistics
$orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$subscribers_count = $pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();
$products_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$feedback_count = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();

// Fetch recent notifications
$notifications = $pdo->query("SELECT name, message, created_at FROM feedback ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function fetchNotifications() {
            $.ajax({
                url: "fetch_notifications.php",
                method: "GET",
                success: function(data) {
                    $("#notifications").html(data);
                }
            });
        }
        setInterval(fetchNotifications, 5000);
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="manage_orders.php" class="list-group-item list-group-item-action">Manage Orders</a>
                    <a href="manage_products.php" class="list-group-item list-group-item-action">Manage Products</a>
                    <a href="manage_admins.php" class="list-group-item list-group-item-action">Manage Admin Users</a>
                    <a href="manage_users.php" class="list-group-item list-group-item-action">Manage Users</a>
                    <a href="manage_subscribers.php" class="list-group-item list-group-item-action">Manage Subscribers</a>
                    <a href="manage_feedback.php" class="list-group-item list-group-item-action">Feedbacks</a>
                </div>
            </div>
            
            <div class="col-md-9">
                <h1 class="mb-4">Dashboard Overview</h1>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white p-4">
                            <h3>Orders</h3>
                            <p class="fs-4"><?= $orders_count ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white p-4">
                            <h3>Subscribers</h3>
                            <p class="fs-4"><?= $subscribers_count ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark p-4">
                            <h3>Products</h3>
                            <p class="fs-4"><?= $products_count ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white p-4">
                            <h3>Users</h3>
                            <p class="fs-4"><?= $users_count ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h3><i class="fas fa-comments"></i> Recent Feedbacks</h3>
                    <ul id="notifications" class="list-group">
                        <?php if (count($notifications) > 0): ?>
                            <?php foreach ($notifications as $feedback): ?>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                    <div class="flex-grow-1">
                                        <strong class="d-block"><?= htmlspecialchars($feedback['name']) ?></strong>
                                        <p class="mb-1 text-secondary"><?= htmlspecialchars($feedback['message']) ?></p>
                                        <span class="text-muted small"><?= htmlspecialchars($feedback['created_at']) ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted text-center">
                                <i class="fas fa-info-circle"></i> No recent feedbacks.
                            </li>
                        <?php endif; ?>
                    </ul>

                    <a href="manage_feedback.php" class="btn btn-primary btn-sm mt-2">
                        <i class="fas fa-eye"></i> View All Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
