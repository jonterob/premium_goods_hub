<?php
include 'config.php';

$response = [
    'new_orders' => 0,
    'new_subscriptions' => 0
];

// Check new orders (created within last 24 hours)
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_date >= NOW() - INTERVAL 1 DAY");
$response['new_orders'] = $stmt->fetchColumn();

// Check new subscriptions
$stmt = $pdo->query("SELECT COUNT(*) FROM subscriptions WHERE created_at >= NOW() - INTERVAL 1 DAY");
$response['new_subscriptions'] = $stmt->fetchColumn();

echo json_encode($response);