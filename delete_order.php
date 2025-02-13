<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Delete related order items
        $stmt1 = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt1->bind_param("i", $order_id);
        $stmt1->execute();
        $stmt1->close();

        // Step 2: Delete the order itself
        $stmt2 = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt2->bind_param("i", $order_id);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaction
        $conn->commit();

        header("Location: manage_orders.php?message=Order deleted");
        exit();
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Error deleting order: " . $e->getMessage();
    }

    $conn->close();
}
?>
