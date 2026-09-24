<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {
        if (!empty($product['glb_path']) && file_exists('../' . $product['glb_path'])) {
            @unlink('../' . $product['glb_path']);
        }
        if (!empty($product['usdz_path']) && file_exists('../' . $product['usdz_path'])) {
            @unlink('../' . $product['usdz_path']);
        }
        if (!empty($product['thumb_path']) && file_exists('../' . $product['thumb_path'])) {
            @unlink('../' . $product['thumb_path']);
        }

        $del = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $del->execute([$id]);
    }
}
header('Location: index.php');
exit;