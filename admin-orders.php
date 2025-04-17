<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['role']) || !$_SESSION['role']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);

    $message = "Order #$orderId updated to '$newStatus'.";
}

$stmt = $pdo->prepare("SELECT o.id AS order_id, o.total, o.status, o.created_at, u.username AS user_name, u.email
                       FROM orders o
                       JOIN users u ON o.user_id = u.id
                       ORDER BY o.created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';

?>


<div class="container mt-5">
    <div class="card shadow-sm border-0 p-4">
        <h2 class="mb-4 text-center text-primary">🧾 Admin - Orders Management</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="text-end mb-3">
            <a href="export-orders.php" class="btn btn-outline-success">
                📥 Export to Excel
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Total (₹)</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['order_id'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['email']) ?></td>
                        <td class="text-success fw-bold"><?= number_format($order['total'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $order['status'] === 'pending' ? 'warning text-dark' :
                                ($order['status'] === 'cancelled' ? 'danger' : 'success') ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td><?= date("d M Y, h:i A", strtotime($order['created_at'])) ?></td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <select name="status" class="form-select form-select-sm me-2">
                                    <?php
                                    $statuses = ['pending', 'shipped', 'delivered', 'cancelled'];
                                    foreach ($statuses as $status):
                                    ?>
                                    <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                        <?= ucfirst($status) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
