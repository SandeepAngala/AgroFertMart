<?php
session_start();
require_once "db.php";

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="orders.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Order ID', 'Username', 'Total Amount', 'Status', 'Created At']);

$stmt = $pdo->prepare("SELECT orders.id, users.username, orders.total_amount, orders.status, orders.created_at
                       FROM orders
                       JOIN users ON orders.user_id = users.id
                       ORDER BY orders.created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($orders as $order) {
    fputcsv($output, [
        $order['id'],
        $order['username'],
        $order['total_amount'],
        $order['status'],
        $order['created_at']
    ]);
}

fclose($output);
exit();
