<?php
// config.php
session_start();

$host = 'localhost';
$dbname = 'school_management';
$user = 'root'; // default XAMPP username
$pass = '';     // default XAMPP password is empty

try {
    // We use PDO to ensure prepared statements and secure database access
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch mode set to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>
