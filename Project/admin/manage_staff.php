<?php
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM staff ORDER BY created_at DESC");
$staffList = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
    </style>
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
        <a href="manage_staff.php" class="active">Manage Staff</a>
        <a href="register_staff.php">Register Staff</a>
        <a href="manage_students.php">Manage Students</a>
    </div>
    
    <div class="container">
        <h3>Manage Staff</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($staffList) > 0): ?>
                    <?php foreach ($staffList as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center;">No staff members found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
