<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Goods Hub | Quality Products Delivered</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="Premium Goods Hub.css">
</head>
<body>

    <header class="header">
        <nav class="nav-container">
            <div class="logo"><a href="index.php">Premium Goods</a></div>
            <div class="nav-links">
            <a href="admin/dashboard.php" title="Seller Center"><i class="fas fa-tachometer-alt"></i></a> <!-- Dashboard icon -->
                <a href="#products" title="Products"><i class="fas fa-cogs"></i></a> <!-- Products icon -->
                <a href="#about" title="About"><i class="fas fa-info-circle"></i></a> <!-- About icon -->
                <a href="#contact" title="Contact"><i class="fas fa-envelope"></i></a> <!-- Contact icon -->
                <a href="#blog" title="Blog"><i class="fas fa-pencil-alt"></i></a> <!-- Blog icon -->
            </div>
        </nav>
    </header>
    
    <main class="cart-container">
    <h2>My Shopping Cart</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <p class="cart-alert">Your cart is empty!</p>
    <?php else: ?>
        <div class="cart-items">
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $quantity):
                $product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $product->execute([$id]);
                $product = $product->fetch();
                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
            ?>
            <div class="cart-item">
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>Price: Ksh<?= htmlspecialchars($product['price']) ?></p>
                    <p>Quantity: <?= $quantity ?></p>
                    <p>Subtotal: Ksh<?= $subtotal ?></p>
                    <a href="remove_all.php?id=<?= $id ?>" class="remove-btn"><i class="fas fa-trash"></i></a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-total">
            <h3>Total: Ksh<?= $total ?></h3>
            <div class="cart-actions">
                <a href="clear_cart.php" class="btn btn-clear">Clear Entire Cart</a>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</main>
    
    <footer class="footer">
        <div class="footer-content" style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:2rem;">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>PremiumGoods is committed to delivering quality products with exceptional customer service.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul style="list-style:none;">
                    <li><a href="#faq" style="color:inherit; text-decoration:none;">FAQ</a></li>
                    <li><a href="#shipping" style="color:inherit; text-decoration:none;">Shipping Policy</a></li>
                    <li><a href="#returns" style="color:inherit; text-decoration:none;">Returns</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-icons" style="display:flex; gap:1rem; font-size:1.5rem;">
                    <a href="https://facebook.com/jonte.roberto" style="color:blue;"><i class="fab fa-facebook"></i></a>
                    <a href="https://instagram.com/jonte.rob" style="color:blue;"><i class="fab fa-instagram"></i></a>
                    <a href="https://twitter.com/@JonteRob" style="color:blue;"><i class="fab fa-twitter"></i></a>
                    <a href="https://linkedin.com/in/john-njoroge" style="color:blue;"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://t.me/+254111218873" style="color:blue;"><i class="fab fa-telegram"></i></a>
                    <a href="https://wa.me/+254111218873" style="color:blue;"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>