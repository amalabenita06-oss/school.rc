<?php
require_once '../config.php';

if (isset($_SESSION['student_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = trim($_POST['student_id']);
    $password = $_POST['password'];

    if (!empty($student_id) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, full_name, password_hash, class FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['student_db_id'] = $user['id'];
            $_SESSION['student_id'] = $student_id;
            $_SESSION['student_name'] = $user['full_name'];
            $_SESSION['student_class'] = $user['class'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid Student ID or password.";
        }
    } else {
        $error = "Please enter both fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login - School Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-box">
        <h2>Student Login</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Student ID</label>
                <input type="text" name="student_id" placeholder="e.g. STU001" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
