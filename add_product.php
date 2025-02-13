<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle Image Upload
    $image_path = NULL;
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/"; // Ensure this folder exists and is writable
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            // File uploaded successfully
        } else {
            $error = "Failed to upload image!";
        }
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_path) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $description, $price, $image_path])) {
        $success = "Product added successfully!";
    } else {
        $error = "Failed to add product!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="manage_subscribers.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
</nav>

<div class="container mt-4">
    <h2>Add New Product</h2>
    <?php if (isset($success)) echo "<p class='text-success'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Upload Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>
</body>
</html>
