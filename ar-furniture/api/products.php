<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../includes/db.php';
require_once '../includes/products.php';

try {
    $products = getActiveProducts();
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error'
    ]);
}