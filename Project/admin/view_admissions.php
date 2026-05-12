<?php
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['admission_id'])) {
    $action = $_POST['action'] === 'approve' ? 'Approved' : 'Rejected';
    $admissionId = $_POST['admission_id'];
    
    $updateStmt = $pdo->prepare("UPDATE admissions SET status = ? WHERE id = ?");
    $updateStmt->execute([$action, $admissionId]);
    
    // If approved, create student record
    if ($action === 'Approved') {
        // Fetch the admission details
        $admissionStmt = $pdo->prepare("SELECT * FROM admissions WHERE id = ?");
        $admissionStmt->execute([$admissionId]);
        $admission = $admissionStmt->fetch();
        
        if ($admission) {
            // Generate student ID (format: STU + admission id + timestamp)
            $studentId = 'STU' . str_pad($admissionId, 4, '0', STR_PAD_LEFT);
            
            // Generate default password for student
            $defaultPassword = password_hash('student123', PASSWORD_BCRYPT);
            
            // Check if student already exists
            $checkStmt = $pdo->prepare("SELECT id FROM students WHERE student_id = ?");
            $checkStmt->execute([$studentId]);
            
            if (!$checkStmt->fetch()) {
                // Insert student record
                $insertStmt = $pdo->prepare("INSERT INTO students (student_id, full_name, password_hash, class) VALUES (?, ?, ?, ?)");
                $insertStmt->execute([$studentId, $admission['name'], $defaultPassword, $admission['course']]);
            }
        }
    }
}

$stmt = $pdo->query("SELECT * FROM admissions ORDER BY created_at DESC");
$admissions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Admissions - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        .action-form { display: inline-block; }
        .btn-approve { background-color: #28a745; color: white; padding: 5px 10px; border: none; cursor: pointer; border-radius: 3px; }
        .btn-reject { background-color: #dc3545; color: white; padding: 5px 10px; border: none; cursor: pointer; border-radius: 3px; }
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
        <a href="view_admissions.php" class="active">View Admissions</a>
        <a href="manage_staff.php">Manage Staff</a>
        <a href="manage_students.php">Manage Students</a>
    </div>
    
    <div class="container">
        <h3>View Admissions</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <!-- <th>Previous School</th> -->
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($admissions) > 0): ?>
                    <?php foreach ($admissions as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id'] ?? '') ?></td>
                       <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['dob'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['gender'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['address'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                        <!-- <td><?= htmlspecialchars($row['previous_school'] ?? '') ?></td> -->
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending'): ?>
                            <form method="POST" class="action-form">
                                <input type="hidden" name="admission_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn-approve">Approve</button>
                            </form>
                            <form method="POST" class="action-form">
                                <input type="hidden" name="admission_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn-reject">Reject</button>
                            </form>
                            <?php else: ?>
                                <?= htmlspecialchars($row['status']) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="10" style="text-align:center;">No admissions found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
