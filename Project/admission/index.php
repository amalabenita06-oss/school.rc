<?php
require_once '../config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_admission'])) {
    $name = trim($_POST['name']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $parent_name = trim($_POST['parent_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $course = trim($_POST['course']);
    
    if ($name && $dob && $gender && $parent_name && $phone && $address && $course) {
        try {
            $stmt = $pdo->prepare("INSERT INTO admissions (name, dob, gender, parent_name, phone, address, course) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $dob, $gender, $parent_name, $phone, $address, $course]);
            $success = "Admission application submitted successfully. Please wait for the administration's approval.";
        } catch (PDOException $e) {
            $error = "Submission failed. Please try again later.";
        }
    } else {
        $error = "Please fill in all the required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Application - School Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* ADDED: Times New Roman font family for entire page */
        * { font-family: 'Times New Roman', Times, serif !important; }
        
        .success { color: #28a745; background:#e2f2e8; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; border: 1px solid #28a745;}
        textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; box-sizing: border-box; resize: vertical; }
        select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;}
    </style>
</head>
<body>
<?php
$activePage = 'admission';
$basePath = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>
    <div class="dashboard-header">
        <h2>Admission Portal</h2>
        <a href="../home.php">Back to Home</a>
    </div>
    
    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto 30px auto;">
            <h3 style="text-align: center;">New Student Admission Form</h3>
            <p style="text-align: center; color: #666; font-size: 0.9em; margin-bottom: 25px;">Please fill out all the details accurately.</p>

            <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="error" style="background:#fbeae9; padding:10px; border: 1px solid #d9534f; border-radius: 4px;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="apply_admission" value="1">
                
                <div class="form-group"><label>Student Full Name</label><input type="text" name="name" required></div>
                
                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;"><label>Date of Birth</label><input type="date" name="dob" required></div>
                    <div class="form-group" style="flex: 1;"><label>Gender</label>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group"><label>Parent/Guardian Name</label><input type="text" name="parent_name" required></div>
                <div class="form-group"><label>Contact Phone</label><input type="text" name="phone" required></div>
                
                <div class="form-group"><label>Full Address</label>
                    <textarea name="address" rows="3" required></textarea>
                </div>
                
                <div class="form-group"><label>Course Applied For</label>
                    <select name="course" required>
                        <option value="">Select Class/Course</option>
                        <option value="Primary School">Primary School</option>
                        <option value="Middle School">Middle School</option>
                        <option value="High School - Science">High School - Science</option>
                        <option value="High School - Commerce">High School - Commerce</option>
                        <option value="High School - Arts">High School - Arts</option>
                    </select>
                </div>

                <button type="submit" class="btn" style="margin-top: 10px;">Submit Application</button>
            </form>
        </div>
    </div>
</body>
</html>
