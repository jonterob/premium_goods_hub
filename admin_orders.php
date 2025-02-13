<?php
include 'config.php';

// Get orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mt-4">
    <h3>Order Management</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['customer_name'] ?></td>
                <td>Ksh<?= number_format($order['total_amount'], 2) ?></td>
                <td>
                    <select class="form-select order-status" data-order-id="<?= $order['id'] ?>">
                        <option <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option <?= $order['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
                        <option <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                        <option <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </td>
                <td><?= date('M j, Y', strtotime($order['order_date'])) ?></td>
                <td>
                    <button class="btn btn-sm btn-danger delete-order" data-id="<?= $order['id'] ?>">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Update order status
    $('.order-status').change(function() {
        const orderId = $(this).data('order-id');
        const newStatus = $(this).val();
        
        $.post('update_order_status.php', {
            order_id: orderId,
            status: newStatus
        }, function(response) {
            alert('Status updated successfully');
        });
    });

    // Delete order
    $('.delete-order').click(function() {
        if (confirm('Are you sure you want to delete this order?')) {
            const orderId = $(this).data('id');
            $.post('delete_order.php', { order_id: orderId }, function() {
                location.reload();
            });
        }
    });
});
</script>