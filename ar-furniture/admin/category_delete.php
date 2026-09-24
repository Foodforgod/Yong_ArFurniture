<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Prevent breaking if products are still attached via foreign key constraint
    }
}
header('Location: categories.php');
exit;