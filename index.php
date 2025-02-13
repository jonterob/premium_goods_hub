<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Goods Hub | Quality Products Delivered</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Premium Goods Hub.css">
</head>
<body>

<header class="header"> 
    <nav class="nav-container">
       <div class="logo">
         <a href="index.php"><video src="images/Discover Premium Quality Products.mp4" autoplay muted loop style="width: 80px; height: auto; border-radius: 60px; animation: rotate360 10s linear infinite;"></video><span>PremiumGoods</span></a>
       </div>
        <div class="nav-links">
            <a href="admin/dashboard.php" title="Seller Center"><i class="fas fa-tachometer-alt"></i></a>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="orders.php" title="Orders"><i class="fas fa-box"></i></a>
                <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
            <?php else : ?>
                <a href="login.php" title="Login"><i class="fas fa-sign-in-alt"></i></a>
                <a href="signup.php" title="Sign Up"><i class="fas fa-user-plus"></i></a>
            <?php endif; ?>
            <a href="#products" title="Products"><i class="fas fa-cogs"></i></a>
            <a href="#about" title="About"><i class="fas fa-info-circle"></i></a>
            <a href="#contact" title="Contact"><i class="fas fa-envelope"></i></a>
            <a href="cart.php" title="Cart"><i class="fas fa-shopping-cart"></i><span class="cart-count">(<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)</span></a>
        </div>
    </nav>
</header>

<section class="hero">
    <h1>Discover Premium Quality Products</h1>
    <a href="#products" class="btn">Shop Now</a>
</section>

<section class="products" id="products">
    <div class="products-grid">
        <?php
        $stmt = $pdo->query("SELECT * FROM products");
        while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) :
            $productId = $product['id'];
            $quantity = $_SESSION['cart'][$productId] ?? 0;
        ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                    <p class="product-price">Ksh<?= htmlspecialchars($product['price']) ?></p>
                </div>
                <div class="product-actions">
                    <a href="add_to_cart.php?id=<?= $productId ?>" class="btn <?= $quantity > 0 ? 'added' : '' ?>">
                        <?= $quantity > 0 ? "Added ($quantity)" : "Add to Cart" ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="newsletter">
    <h2>Join Our Newsletter</h2>
    <p>Get exclusive offers and updates directly in your inbox</p>
    <div id="toast" class="subscription">
        <form class="newsletter-form" action="subscribe.php" method="POST">
            <input type="email" name="email" class="newsletter-input" placeholder="Enter your email" required>
            <button type="submit" class="newsletter-btn">Subscribe</button>
        </form>
    </div>
</section>

<section class="feedback">
    <h2>Customer Feedback</h2>
    <form action="submit_feedback.php" method="post">
        <input type="text" name="name" required placeholder="Your Name">
        <input type="email" name="email" required placeholder="Your Email">
        <textarea name="message" required placeholder="Your Feedback"></textarea>
        <button type="submit">Submit Feedback</button>
    </form>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About Us</h3>
            <p>PremiumGoods is committed to delivering quality products with exceptional customer service.</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#faq">FAQ</a></li>
                <li><a href="#shipping">Shipping Policy</a></li>
                <li><a href="#returns">Returns</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Connect With Us</h3>
            <div class="social-icons">
                <a href="https://facebook.com/jonte.roberto"><i class="fab fa-facebook"></i></a>
                <a href="https://instagram.com/jonte.rob"><i class="fab fa-instagram"></i></a>
                <a href="https://twitter.com/@JonteRob"><i class="fab fa-twitter"></i></a>
                <a href="https://www.linkedin.com/in/john-gitau-834063254/"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://t.me/+254111218873"><i class="fab fa-telegram"></i></a>
                <a href="https://wa.me/+254111218873"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="Premium Goods Hub.js"></script>

<script>
const SUCCESS_MESSAGES = {
    feedback: "Feedback submitted successfully!",
    added: "Product added to cart successfully!",
    subscribed: "You have successfully subscribed! Please check your email for verification."
};

const ERROR_MESSAGES = {
    empty_fields: "Error: All fields are required!",
    invalid_product: "Error: Invalid product selection!",
    db_error: "Error: Database issue, please try again!",
    already_subscribed: "Error: This email is already subscribed!"
};

document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);

    let alertMessage = SUCCESS_MESSAGES[urlParams.get('success')] || ERROR_MESSAGES[urlParams.get('error')] || "";

    if (alertMessage) {
        alert(alertMessage);
        history.replaceState(null, "", window.location.pathname);
    }
});
</script>

</body>
</html>
