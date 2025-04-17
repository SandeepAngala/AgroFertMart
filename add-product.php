<?php
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $tempPath = $_FILES['image']['tmp_name'];

        $uploadDir = "uploads/";
        $targetPath = $uploadDir . basename($image);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($tempPath, $targetPath)) {
            // Insert into DB
            $sql = "INSERT INTO products (name, price, category, image_url) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $price, $category, $image]);

            $_SESSION['message'] = "Product added successfully!";
            header("Location: admin-products.php");
            exit();
        } else {
            $_SESSION['message'] = "Image upload failed!";
        }
    } else {
        $_SESSION['message'] = "No image selected or upload error!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Add New Product</h2>
    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price:</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category:</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Image:</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info mt-3">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
</body>
</html>
