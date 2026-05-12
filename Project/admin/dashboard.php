<?php
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Fetch basic statistics
$alumniCount = $pdo->query("SELECT COUNT(*) FROM alumni")->fetchColumn();
$admissionCount = $pdo->query("SELECT COUNT(*) FROM admissions")->fetchColumn();
$studentCount = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-header">
        <h2>Admin Panel</h2>
        <a href="../index.php">Logout</a>
    </div>
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_alumni.php">Manage Alumni</a>
        <a href="view_admissions.php">View Admissions</a>
        <a href="manage_staff.php">Manage Staff</a>
        <a href="manage_students.php">Manage Students</a>
    </div>
    
    <div class="container">
        <div class="card">
            <h3>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h3>
            <p>Select an option from the navigation menu above to manage different sections of the system.</p>
        </div>
        
        <div style="display: flex; gap: 20px;">
            <div class="card" style="flex: 1; text-align: center;">
                <h4>Total Students</h4>
                <h1><?= $studentCount ?></h1>
            </div>
            <div class="card" style="flex: 1; text-align: center;">
                <h4>Pending Admissions</h4>
                <h1><?= $admissionCount ?></h1>
            </div>
            <div class="card" style="flex: 1; text-align: center;">
                <h4>Registered Alumni</h4>
                <h1><?= $alumniCount ?></h1>
            </div>
        </div>
    </div>
</body>
</html>
