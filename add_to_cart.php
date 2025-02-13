<?php
// Include database configuration file to establish PDO connection
require 'config.php';

// Start session if not already started (to store cart data)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart session variable if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Create an empty array for cart items
}

// Get and validate product ID from URL parameter to ensure it's an integer
$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Proceed only if we received a valid product ID
if ($product_id) {
    // Prepare a SQL statement to check if the product exists in the database
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    
    // Execute query with the provided product ID (helps prevent SQL injection)
    $stmt->execute([$product_id]);
    
    // If the product exists in the database, add it to the cart
    if ($stmt->fetch()) {
        // Increment quantity if product already exists in cart, otherwise start with 1
        $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
        
        // Redirect with success message in URL
        header("Location: index.php?success=added");
        exit();
    } else {
        // Redirect with an error message if the product doesn't exist
        header("Location: index.php?error=invalid_product");
        exit();
    }
}

// If no valid product ID was received, redirect with an error message
header("Location: index.php?error=invalid_request");
exit();
?>
