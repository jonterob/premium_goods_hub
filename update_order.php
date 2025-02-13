<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST["order_id"];
    $status = $_POST["status"];

    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        echo "Order status updated successfully!";
    } else {
        echo "Error updating order status.";
    }

    $stmt->close();
}
?>
