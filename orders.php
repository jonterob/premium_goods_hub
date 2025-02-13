<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$orders = $pdo->prepare("
    SELECT o.id, o.total, o.created_at, 
           GROUP_CONCAT(p.name SEPARATOR ', ') AS products,
           GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$orders->execute([$userId]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="orders-container">
        <h1>My Orders</h1>
        <?php if ($orders->rowCount() > 0) : ?>
            <?php while ($order = $orders->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="order-card">
                    <div class="order-header">
                        <span>Order #<?= $order['id'] ?></span>
                        <span><?= date('M d, Y', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="order-body">
                        <p>Products: <?= $order['products'] ?></p>
                        <p>Quantities: <?= $order['quantities'] ?></p>
                        <p class="total">Total: Ksh <?= number_format($order['total'], 2) ?></p>
                    </div>
                    <a href="receipt.php?order_id=<?= $order['id'] ?>" class="btn">View Full Receipt</a>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>