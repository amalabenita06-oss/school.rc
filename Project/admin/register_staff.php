<?php
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $full_name = trim($_POST['full_name']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($username) || empty($password) || empty($full_name) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $checkStmt = $pdo->prepare("SELECT id FROM staff WHERE username = ?");
        $checkStmt->execute([$username]);
        if ($checkStmt->fetch()) {
            $error = "Username already exists. Please choose a different one.";
        } else {
            // Hash the password
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert into database
            try {
                $stmt = $pdo->prepare("INSERT INTO staff (username, password_hash, full_name) VALUES (?, ?, ?)");
                $stmt->execute([$username, $password_hash, $full_name]);
                $success = "Staff member registered successfully! Staff ID: " . $pdo->lastInsertId();
                // Clear form
                $_POST = [];
            } catch (PDOException $e) {
                $error = "Error registering staff: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Staff - School Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .register-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <h2>Admin Panel - Register Staff</h2>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_alumni.php">Manage Alumni</a>
        <a href="view_admissions.php">View Admissions</a>
        <a href="manage_staff.php">Manage Staff</a>
        <a href="manage_students.php">Manage Students</a>
    </div>
    
    <div class="container">
        <div class="register-container">
            <div class="card">
                <h3>Register New Staff Member</h3>
                
                <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success" style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                        ✓ <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        <small>Must be unique</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                        <small>Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn">Register Staff</button>
                </form>
            </div>
            
            <div class="card" style="margin-top: 30px;">
                <h4>Staff Registration Guidelines</h4>
                <ul>
                    <li>Choose a unique username for each staff member</li>
                    <li>Password must be at least 6 characters long</li>
                    <li>Staff can use their login credentials to access the staff portal</li>
                    <li>Staff can search for student records and view their marks and attendance</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
