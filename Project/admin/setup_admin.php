<?php
// Run this file once to set up the default admin user with a securely hashed password.
require_once '../config.php';

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if the admin exists
    $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        // Update the password
        $updateStmt = $pdo->prepare("UPDATE admin SET password_hash = ? WHERE username = ?");
        $updateStmt->execute([$hash, $username]);
        echo "Admin password updated successfully!\n";
        echo "Username: $username\n";
        echo "Password: $password\n";
    } else {
        // Insert a new admin
        $insertStmt = $pdo->prepare("INSERT INTO admin (username, password_hash) VALUES (?, ?)");
        $insertStmt->execute([$username, $hash]);
        echo "Admin created successfully!\n";
        echo "Username: $username\n";
        echo "Password: $password\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
