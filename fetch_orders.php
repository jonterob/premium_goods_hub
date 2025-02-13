<?php
include 'db_connect.php';

$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['customer_name']; ?></td>
    <td><?= $row['email']; ?></td>
    <td>$<?= number_format($row['total'], 2); ?></td>
    <td>
        <select class="order-status" data-id="<?= $row['id']; ?>">
            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
            <option value="Shipped" <?= $row['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
            <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>
    </td>
    <td><?= $row['status']; ?></td> <!-- New column for Current Status -->
    <td>
        <a href="delete_order.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
