<?php
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// variables for messages
$success = '';
$error = '';

// handle form submission for add/edit
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['save_student'])
) {
    $sid = trim($_POST['student_id']);
    $fullname = trim($_POST['full_name']);
    $class = trim($_POST['class']);
    $password = trim($_POST['password'] ?? '');
    $editId = $_POST['edit_id'] ?? null;

    if (!$sid || !$fullname || !$class) {
        $error = "Student ID, name and class are required.";
    } else {
        // check uniqueness of student_id
        $checkSql = "SELECT id FROM students WHERE student_id = ?";
        $params = [$sid];
        if ($editId) {
            $checkSql .= " AND id != ?";
            $params[] = $editId;
        }
        $stmtChk = $pdo->prepare($checkSql);
        $stmtChk->execute($params);
        if ($stmtChk->fetch()) {
            $error = "Student ID already exists.";
        } else {
            if ($editId) {
                // update existing record
                if ($password !== '') {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $updStmt = $pdo->prepare("UPDATE students SET student_id=?, full_name=?, class=?, password_hash=? WHERE id = ?");
                    $updStmt->execute([$sid, $fullname, $class, $hash, $editId]);
                } else {
                    $updStmt = $pdo->prepare("UPDATE students SET student_id=?, full_name=?, class=? WHERE id = ?");
                    $updStmt->execute([$sid, $fullname, $class, $editId]);
                }
                $success = "Student record updated.";
            } else {
                // new student requires password
                if ($password === '') {
                    $error = "Password is required for new student.";
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $insStmt = $pdo->prepare("INSERT INTO students (student_id, password_hash, full_name, class) VALUES (?, ?, ?, ?)");
                    $insStmt->execute([$sid, $hash, $fullname, $class]);
                    $success = "New student added.";
                }
            }
        }
    }
}

// fetch student for edit if requested
$editStudent = null;
if (isset($_GET['edit_id'])) {
    $eid = intval($_GET['edit_id']);
    $stmtE = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmtE->execute([$eid]);
    $editStudent = $stmtE->fetch();
}

$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students - Admin</title>
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
        <a href="manage_staff.php">Manage Staff</a>
        <a href="manage_students.php" class="active">Manage Students</a>
    </div>
    
    <div class="container">
        <h3>Manage Students</h3>
        <?php if ($success): ?>
            <div class="success" style="background:#d4edda;color:#155724;padding:10px;border-radius:4px;margin-bottom:20px;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error" style="background:#f8d7da;color:#721c24;padding:10px;border-radius:4px;margin-bottom:20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <!-- Add / Edit Form -->
        <div class="card" style="margin-bottom:30px; padding:20px;">
            <h4><?= $editStudent ? 'Edit Student' : 'Add New Student' ?></h4>
            <form method="POST" action="">
                <input type="hidden" name="save_student" value="1">
                <?php if ($editStudent): ?>
                    <input type="hidden" name="edit_id" value="<?= htmlspecialchars($editStudent['id']) ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label>Student ID</label>
                    <input type="text" name="student_id" required value="<?= htmlspecialchars($editStudent['student_id'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required value="<?= htmlspecialchars($editStudent['full_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Class</label>
                    <input type="text" name="class" required value="<?= htmlspecialchars($editStudent['class'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Password <?= $editStudent ? '(leave blank to keep current)' : '' ?></label>
                    <input type="password" name="password" <?= $editStudent ? '' : 'required' ?> >
                </div>
                <button type="submit" class="btn"><?= $editStudent ? 'Update Student' : 'Add Student' ?></button>
                <?php if ($editStudent): ?>
                    <a href="manage_students.php" class="btn" style="margin-left:10px; background:#6c757d;">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Class</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['class']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td><a href="?edit_id=<?= $row['id'] ?>" class="btn" style="padding:5px 10px; font-size:0.9em;">Edit</a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">No students found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
