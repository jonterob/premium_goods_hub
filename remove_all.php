<?php
session_start();
include 'config.php';

if(isset($_GET['id'])) {
    $productId = $_GET['id'];
    if(isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}
header("Location: cart.php");
exit();
?>