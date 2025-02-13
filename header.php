<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header bg-light shadow-sm fixed-top">
    <nav class="container d-flex justify-content-between align-items-center py-3">
        <div class="logo">
            <a href="index.php" class="text-dark fw-bold text-decoration-none fs-4">Premium Goods</a>
        </div>
        <div class="nav-links d-flex gap-4">
            <a href="admin/dashboard.php" title="Seller Center"><i class="fas fa-tachometer-alt fa-lg"></i></a>

            <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="orders.php" title="Orders"><i class="fas fa-box fa-lg"></i></a>
                <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt fa-lg"></i></a>
            <?php else : ?>
                <a href="login.php" title="Login"><i class="fas fa-sign-in-alt fa-lg"></i></a>
                <a href="signup.php" title="Sign Up"><i class="fas fa-user-plus fa-lg"></i></a>
            <?php endif; ?>

            <a href="#products" title="Products"><i class="fas fa-cogs fa-lg"></i></a>
            <a href="#about" title="About"><i class="fas fa-info-circle fa-lg"></i></a>
            <a href="#contact" title="Contact"><i class="fas fa-envelope fa-lg"></i></a>
            <a href="#blog" title="Blog"><i class="fas fa-pencil-alt fa-lg"></i></a>
            <a href="cart.php" title="Cart">
                <i class="fas fa-shopping-cart fa-lg"></i>
                <span class="cart-count badge bg-danger">
                    <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
                </span>
            </a>
        </div>
    </nav>
</header>

<!-- FontAwesome & Bootstrap (Ensure they're included in your project) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        padding-top: 70px; /* Prevent content from being hidden under the fixed header */
    }
    .header {
        height: 80px; /* Larger header */
    }
    .nav-links a {
        text-decoration: none;
        color: blue;
        transition: 0.3s;
    }
    .nav-links a:hover {
        color: #f39c12; /* Hover effect */
    }
    .cart-count {
        font-size: 14px;
        padding: 2px 6px;
        border-radius: 50%;
        position: relative;
        top: -5px;
        left: -3px;
    }
</style>
