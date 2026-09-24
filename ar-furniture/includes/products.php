<?php
require_once __DIR__ . '/db.php';

function getActiveProducts($category_id = null) {
    global $pdo;
    if ($category_id) {
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 AND p.category_id = ? ORDER BY p.sort_order ASC, p.id DESC");
        $stmt->execute([$category_id]);
    } else {
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY p.sort_order ASC, p.id DESC");
    }
    return $stmt->fetchAll();
}

function getProductBySlug($slug) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ? LIMIT 1");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function formatPrice($price, $currency = 'RM') {
    return $currency . ' ' . number_format($price, 2);
}