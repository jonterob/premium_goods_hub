<?php
if(isset($_GET['id'])) {
    $productId = $_GET['id'];
    if(isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]--;
        if($_SESSION['cart'][$productId] <= 0) {
            unset($_SESSION['cart'][$productId]);
        }
    }
}
header("Location: cart.php");
exit();
?>