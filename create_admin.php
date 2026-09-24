<?php
require_once 'includes/db.php';

$username = 'superadmin'; // Change to your desired username
$raw_password = 'YourSecurePassword123'; // Change to your desired password
$password_hash = password_hash($raw_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $password_hash]);
    echo "New admin account created successfully! You can now log in with username: <strong>{$username}</strong>";
} catch (PDOException $e) {
    echo "Error: Username might already exist or database error occurred.";
}