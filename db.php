<?php
$host = 'localhost'; // Change if different
$dbname = 'premium_goods';
$username = 'root'; // Default for XAMPP
$password = ''; // Default for XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
