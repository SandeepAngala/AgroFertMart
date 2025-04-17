<?php
session_start();
require_once "db.php";

// Check admin login
if (!isset($_SESSION['role']) || !$_SESSION['role']) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            max-width: 600px;
            margin: auto;
            margin-top: 100px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 16px;
            background-color: white;
        }
        .btn-lg {
            width: 100%;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dashboard-card text-center">
        <h2 class="mb-4 text-primary">👨‍💼 Admin Dashboard</h2>
        <p class="text-muted">Welcome, you can manage products and orders from here.</p>
        <div class="d-grid gap-3 mt-4">
            <a href="admin-products.php" class="btn btn-success btn-lg">
                🛒 Manage Products
            </a>
            <a href="admin-orders.php" class="btn btn-primary btn-lg">
                📦 Manage Orders
            </a>
        </div>
    </div>
</div>

</body>
</html>
