<?php
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, name, price, category, image_url FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #5a5a5a;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th {
            background-color: #343a40;
            color: white;
            text-align: center;
        }
        td {
            text-align: center;
            vertical-align: middle;
        }
        .btn {
            margin: 0 5px;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        img {
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            margin-top: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>All Products</h2>
    <div class="mt-4 mb-3">
        <a href="add-product.php" class="btn btn-success">Add New Product</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>₹<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td>
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="uploads/<?= htmlspecialchars($product['image_url']) ?>" width="50" alt="Product Image">
                            <?php else: ?>
                                <span>No image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-warning">Edit</a>
                            <a href="delete-product.php?product_id=<?= $product['id'] ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
