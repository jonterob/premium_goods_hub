<?php
include 'config.php';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Calculate total FIRST and validate products
$total = 0;
$validCart = true;
foreach ($_SESSION['cart'] as $id => $quantity) {
    $product = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $product->execute([$id]);
    
    // Verify product exists
    if (!$product->rowCount()) {
        $validCart = false;
        break;
    }
    
    $price = $product->fetchColumn();
    $total += $price * $quantity;
}

// Validate cart contents and total
if (!$validCart || $total <= 0) {
    $_SESSION['error'] = "Your cart contains invalid items";
    header("Location: cart.php");
    exit();
}

// Initialize variables
$errors = [];
$success = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_STRING);
    
    // Validate inputs
    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($city)) $errors[] = "City is required";
    if (!preg_match("/^[A-Z0-9\- ]+$/i", $postal_code)) $errors[] = "Invalid postal code";

    // If no errors, process order
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Insert order with validated total
            $stmt = $pdo->prepare("INSERT INTO orders (customer_name, email, address, city, postal_code, total) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $address, $city, $postal_code, $total]);
            $orderId = $pdo->lastInsertId();

            // Insert order items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
                                  VALUES (?, ?, ?, ?)");
            
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                $product->execute([$productId]);
                $price = $product->fetchColumn();
                
                $stmt->execute([$orderId, $productId, $quantity, $price]);
            }

            $pdo->commit();
            
            // Clear cart and show success
            unset($_SESSION['cart']);
            $success = true;

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('Order Error: ' . $e->getMessage());
            $errors[] = "Order processing failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Premium Goods Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkout.css">
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

    <main class="checkout-container">
        <?php if ($success): ?>
            <div class="success-message">
                <h2>Order Placed Successfully!</h2>
                <h4>Your order ID: #<?= $orderId ?></h4>
                <h4>A confirmation email has been sent to <?= htmlspecialchars($email) ?><h4>
                <a href="index.php" class="btn">Continue Shopping</a>
            </div>
        <?php else: ?>
            <h2>Checkout</h2>
            <p>Total Amount: Ksh<?= number_format($total, 2) ?></p>

            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="checkout-form" method="POST">
                <div class="form-group full-width">
                    <h3>Shipping Information</h3>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required 
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="form-group full-width">
                    <label>Address</label>
                    <input type="text" name="address" required
                           value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" required
                           value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code" required pattern="[0-9]{5}"
                           value="<?= htmlspecialchars($_POST['postal_code'] ?? '') ?>">
                </div>

                <div class="form-group full-width">
                    <h3>Payment Information</h3>
                </div>

                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" pattern="[0-9]{13,16}" placeholder="4111 1111 1111 1111" required>
                </div>

                <div class="form-group">
                    <label>Expiration Date</label>
                    <input type="month" required>
                </div>

                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" pattern="[0-9]{3}" placeholder="123" required>
                </div>

                <div class="form-group full-width">
                    <button type="submit" class="btn">Place Order</button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="footer-content" style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:2rem;">
            <div class="footer-section">
                <h3>About Us</h3>
                <h4>PremiumGoods is committed to delivering quality products with exceptional customer service.<h4>
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