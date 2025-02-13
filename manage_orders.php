<?php
include 'db_connect.php'; // Ensure this file connects to your database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="manage_orders.css"> <!-- Add your CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <h2>Manage Orders</h2>

    <table border="1">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Total Price</th>
        <th>Update Status</th>
        <th>Current Status</th>
        <th>Actions</th>
    </tr>
    <tbody id="orders-table"> <!-- Orders will be dynamically loaded here -->
    </tbody>
</table>

    <script>
        // Function to fetch orders dynamically
        function fetchOrders() {
            $.ajax({
                url: "fetch_orders.php", 
                method: "GET",
                success: function(data) {
                    $("#orders-table").html(data); // Update table with new data
                }
            });
        }

        // Auto-fetch orders every 5 seconds
        setInterval(fetchOrders, 5000);
        fetchOrders(); // Load orders initially

        // Update order status dynamically
        $(document).on("change", ".order-status", function() {
            var order_id = $(this).data("id");
            var status = $(this).val();

            $.ajax({
                url: "update_order.php",
                method: "POST",
                data: { order_id: order_id, status: status },
                success: function(response) {
                    alert("Order status updated successfully!");
                    fetchOrders(); // Refresh the table after update
                }
            });
        });
    </script>
</body>
</html>
