<?php
require_once '../config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_alumni'])) {
    $name = trim($_POST['name']);
    $batch = trim($_POST['batch']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = trim($_POST['department']);
    $current_job = trim($_POST['current_job']);
    
    if ($name && $batch && $email && $phone && $department && $current_job) {
        try {
            $stmt = $pdo->prepare("INSERT INTO alumni (name, batch, email, phone, department, current_job) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $batch, $email, $phone, $department, $current_job]);
            $success = "Registration successful!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation (likely duplicate email)
                $error = "An alumni with this email is already registered.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

// Fetch all alumni
$alumniList = $pdo->query("SELECT name, batch, department, current_job FROM alumni ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Portal - School Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* ADDED: Times New Roman font family for entire page */
        * { font-family: 'Times New Roman', Times, serif !important; }
        
        .alumni-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
        .alumni-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-left: 4px solid #0056b3; }
        .success { color: #28a745; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>
<?php
$activePage = 'alumni';
$basePath = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>
    <div class="dashboard-header">
        <h2>Alumni Portal</h2>
        <a href="../home.php">Back to Home</a>
    </div>
    
    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto 30px auto;">
            <h3>Alumni Registration</h3>
            <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="register_alumni" value="1">
                <div class="form-group"><label>Full Name</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Batch (e.g. 2020)</label><input type="text" name="batch" required></div>
                <div class="form-group"><label>Email Address</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Phone Number</label><input type="text" name="phone" required></div>
                <div class="form-group"><label>Department/Course</label><input type="text" name="department" required></div>
                <div class="form-group"><label>Current Job / Company</label><input type="text" name="current_job" required></div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>

        <hr style="border:0; height:1px; background:#ddd; margin: 30px 0;">

        <h3>Our Alumni</h3>
        <?php if (count($alumniList) > 0): ?>
            <div class="alumni-grid">
                <?php foreach($alumniList as $alumnus): ?>
                <div class="alumni-card">
                    <h4 style="margin: 0 0 10px 0; color: #0056b3;"><?= htmlspecialchars($alumnus['name']) ?></h4>
                    <p style="margin: 5px 0; font-size: 0.9em;"><strong>Batch:</strong> <?= htmlspecialchars($alumnus['batch']) ?></p>
                    <p style="margin: 5px 0; font-size: 0.9em;"><strong>Department:</strong> <?= htmlspecialchars($alumnus['department']) ?></p>
                    <p style="margin: 5px 0; font-size: 0.9em;"><strong>Current Job:</strong> <?= htmlspecialchars($alumnus['current_job']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No alumni registered yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
