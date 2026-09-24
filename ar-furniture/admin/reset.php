<?php
require_once '../includes/db.php';

$username = 'admin';
$password = 'password123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Check if admin exists, if not insert, else update
$stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    $update = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE username = ?");
    $update->execute([$hash, $username]);
    echo "Password successfully updated for username: <strong>admin</strong><br>";
} else {
    $insert = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
    $insert->execute([$username, $hash]);
    echo "Admin user created successfully!<br>";
}
echo "You can now login with:<br>Username: <strong>admin</strong><br>Password: <strong>password123</strong><br><br>";
echo "<a href='login.php'>Go to Login Page</a>";