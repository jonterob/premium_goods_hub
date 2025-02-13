<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    if ($product && !empty($product['image_path'])) {
        $image_file = "../" . $product['image_path'];
        if (file_exists($image_file)) {
            unlink($image_file);
        }
    }
    
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    header("Location: manage_products.php?success=Product deleted");
    exit;
}

$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="manage_orders.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Manage Products</h2>
        <a href="add_product.php" class="btn btn-success mb-3">Add New Product</a>
        
        <?php if (!empty($_GET['success'])): ?>
            <p class='text-success'><?= htmlspecialchars($_GET['success']) ?></p>
        <?php endif; ?>

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td>
                    <?php $image_path = !empty($product['image_path']) ? "http://localhost/premium_goods_hub/" . $product['image_path'] : "http://localhost/premium_goods_hub/uploads/default.png"; ?>
                    <img src="<?= $image_path ?>" width="80" height="80" style="object-fit:cover;">
                </td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>$<?= htmlspecialchars($product['price']) ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="manage_products.php?delete=<?= $product['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
