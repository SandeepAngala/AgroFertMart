<?php
session_start();
$conn = new mysqli("localhost", "root", "", "agri");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products 
        ORDER BY FIELD(category, 'Fertilizer', 'Pesticide', 'Equipment'), name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products | AgroFertMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

<!-- Navbar -->
<nav class="bg-green-700 p-4 shadow">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a href="index.php" class="text-white text-2xl font-bold">🌾 AgroFertMart</a>
        <div class="space-x-6">
            <a href="index.php" class="text-white hover:text-yellow-300">Home</a>
            <a href="products.php" class="text-white hover:text-yellow-300">Products</a>
            <a href="cart.php" class="text-white hover:text-yellow-300">🛒 Cart (<span id="cart-count"><?= $_SESSION['cart']['total'] ?? 0 ?></span>)</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-4xl font-extrabold text-center text-green-800 mb-10">🌿 Shop Agro Products</h1>

    <?php
    $currentCategory = null;
    if ($result->num_rows > 0):
    ?>
    <div class="space-y-16">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            if ($currentCategory !== $row['category']) {
                if ($currentCategory !== null) echo '</div>';
                $currentCategory = $row['category'];
                echo "<h2 class='text-3xl font-bold text-green-700 mb-4 border-b border-green-300 pb-1'>{$currentCategory}s</h2><div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6'>";
            }

            // Keep original image logic
            $image = (!empty($row['image_url']) && file_exists($row['image_url'])) ? $row['image_url'] : 'images/default.jpg';
            ?>
            <div class="bg-white rounded-xl shadow-md p-4 transition transform hover:scale-105 hover:shadow-xl">
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-40 object-cover rounded">
                <h3 class="text-lg font-semibold mt-3"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="text-green-600 font-bold mt-1">₹<?= number_format($row['price'], 2) ?></p>
                <button onclick="addToCart(<?= $row['id'] ?>)" class="mt-3 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg w-full">
                    ➕ Add to Cart
                </button>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
    <?php else: ?>
        <p class="text-center text-red-600">No products available.</p>
    <?php endif; ?>
</div>

<!-- JS for AJAX Cart -->
<script>
function addToCart(productId) {
    fetch('add-to-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.count;
            alert('✅ Added to cart!');
        } else {
            alert('❌ Failed to add product!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Something went wrong!');
    });
}
</script>

</body>
</html>
