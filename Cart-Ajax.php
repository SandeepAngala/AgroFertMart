<?php
session_start();
require 'cart-functions.php';

header('Content-Type: application/json');

$response = ['success' => false];

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['action'], $input['product_id'])) {
        throw new Exception('Invalid request');
    }

    $productId = (int)$input['product_id'];
    $response['success'] = false;

    switch ($input['action']) {
        case 'update':
            $quantity = isset($input['quantity']) ? max(1, (int)$input['quantity']) : 1;
            $response['success'] = updateCartQuantity($productId, $quantity);
            break;
        case 'remove':
            $response['success'] = removeFromCart($productId);
            break;
        default:
            throw new Exception('Invalid action');
    }

    updateCartTotal();
    $response['total'] = $_SESSION['cart']['total'];
    $response['count'] = getCartCount();

    if (isset($_SESSION['cart']['items'][$productId])) {
        $item = $_SESSION['cart']['items'][$productId];
        $response['subtotal'] = $item['price'] * $item['quantity'];
    }

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);