<?php
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM alumni ORDER BY created_at DESC");
$alumni = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Alumni - Admin</title>
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
        <a href="manage_alumni.php" class="active">Manage Alumni</a>
        <a href="view_admissions.php">View Admissions</a>
        <a href="manage_staff.php">Manage Staff</a>
        <a href="manage_students.php">Manage Students</a>
    </div>
    
    <div class="container">
        <h3>Manage Alumni</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Batch</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Current Job</th>
                    <!-- <th>Status</th> -->
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($alumni) > 0): ?>
                    <?php foreach ($alumni as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['batch'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['department'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['current_job'] ?? '') ?></td>
                        <!-- <td><?= htmlspecialchars($row['status'] ?? '') ?></td> -->
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align:center;">No alumni found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
