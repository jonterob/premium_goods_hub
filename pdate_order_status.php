<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    exit;
}

$orderId = $_POST['order_id'];
$status = $_POST['status'];

$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->execute([$status, $orderId]);

echo 'Status updated';