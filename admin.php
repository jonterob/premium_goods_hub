<?php 
session_start();
include 'config.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .sidebar { height: 100vh; background-color: #f8f9fa; }
        .notification-badge { position: relative; }
        .badge { position: absolute; top: -5px; right: -10px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <h4 class="p-3">Admin Dashboard</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" data-target="dashboard">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item notification-badge">
                            <a class="nav-link" href="#orders" data-target="orders">
                                Orders
                                <span class="badge bg-danger" id="order-notification">0</span>
                            </a>
                        </li>
                        <li class="nav-item notification-badge">
                            <a class="nav-link" href="#subscriptions" data-target="subscriptions">
                                Subscriptions
                                <span class="badge bg-danger" id="subscription-notification">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#products" data-target="products">
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#users" data-target="users">
                                Users
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div id="content-area">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Load initial dashboard
        loadContent('dashboard');

        // Navigation click handler
        $('.nav-link').click(function(e) {
            e.preventDefault();
            const target = $(this).data('target');
            loadContent(target);
        });

        // Function to load content
        function loadContent(target) {
            $.ajax({
                url: `admin_${target}.php`,
                success: function(data) {
                    $('#content-area').html(data);
                }
            });
        }

        // Check for new notifications every 30 seconds
        setInterval(checkNotifications, 30000);

        function checkNotifications() {
            $.ajax({
                url: 'check_notifications.php',
                success: function(data) {
                    const notifications = JSON.parse(data);
                    $('#order-notification').text(notifications.new_orders);
                    $('#subscription-notification').text(notifications.new_subscriptions);
                }
            });
        }

        // Initial check
        checkNotifications();
    </script>
</body>
</html>