<?php
require_once '../config.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Handle mark submission for 5 subjects
$markSuccess = '';
$markError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_marks'])) {
    $studentId = trim($_POST['student_id']);
    $addedCount = 0;
    $errorCount = 0;
    
    // Process 5 subject-mark pairs
    for ($i = 1; $i <= 5; $i++) {
        $subject = trim($_POST['subject_' . $i] ?? '');
        $marks = trim($_POST['marks_' . $i] ?? '');
        
        // Skip empty fields
        if ($subject === '' && $marks === '') {
            continue;
        }
        
        // Validate if one is filled, both must be filled
        if ($subject === '' || $marks === '') {
            $markError = "If you enter marks, both subject and marks are required for all fields.";
            $errorCount++;
            continue;
        }
        
        if (!is_numeric($marks) || $marks < 0 || $marks > 100) {
            $markError = "Marks must be a number between 0 and 100 for each subject.";
            $errorCount++;
            continue;
        }
        
        // Check if this subject already exists for the student
        $checkStmt = $pdo->prepare("SELECT id FROM marks WHERE student_id = ? AND subject = ?");
        $checkStmt->execute([$studentId, $subject]);
        if ($checkStmt->fetch()) {
            // Update existing mark
            $updateStmt = $pdo->prepare("UPDATE marks SET marks = ? WHERE student_id = ? AND subject = ?");
            $updateStmt->execute([$marks, $studentId, $subject]);
            $addedCount++;
        } else {
            // Insert new mark
            $insertStmt = $pdo->prepare("INSERT INTO marks (student_id, subject, marks) VALUES (?, ?, ?)");
            $insertStmt->execute([$studentId, $subject, $marks]);
            $addedCount++;
        }
    }
    
    if ($addedCount > 0 && $errorCount === 0) {
        $markSuccess = "Successfully saved $addedCount mark(s)!";
    } elseif ($addedCount > 0) {
        $markSuccess = "Saved $addedCount mark(s). Please check errors for the rest.";
    }
    
    // Trigger search refresh by keeping the search_id in query
    if (!isset($_GET['search_id'])) {
        $_GET['search_id'] = $studentId;
    }
}

// Fetch staff details
$staffStmt = $pdo->prepare("SELECT id, username, full_name, created_at FROM staff WHERE id = ?");
$staffStmt->execute([$_SESSION['staff_id']]);
$staffInfo = $staffStmt->fetch();

$searchResult = null;
if (isset($_GET['search_id'])) {
    $searchId = trim($_GET['search_id']);
    
    // Fetch student basic details
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$searchId]);
    $student = $stmt->fetch();
    
    if ($student) {
        $searchResult = ['info' => $student];
        
        // Fetch marks
        $mStmt = $pdo->prepare("SELECT * FROM marks WHERE student_id = ?");
        $mStmt->execute([$searchId]);
        $searchResult['marks'] = $mStmt->fetchAll();
        
        // Fetch attendance
        $aStmt = $pdo->prepare("SELECT * FROM attendance WHERE student_id = ?");
        $aStmt->execute([$searchId]);
        $searchResult['attendance'] = $aStmt->fetchAll();
    } else {
        $errorMsg = "Student not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .staff-info { background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .staff-info h4 { margin-top: 0; color: #007bff; }
        .info-row { display: flex; justify-content: space-between; margin: 8px 0; }
        .info-label { font-weight: bold; color: #333; }
        .info-value { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <h2>Staff Dashboard</h2>
        <a href="?logout=true" style="color: white; text-decoration: none; background-color: #dc3545; padding: 10px 20px; border-radius: 4px;">Logout</a>
    </div>
    
    <div class="container">
        <!-- Staff Details Section -->
        <div class="staff-info">
            <h4>📋 Your Staff Details</h4>
            <div class="info-row">
                <div><span class="info-label">Staff ID:</span></div>
                <div><span class="info-value"><?= htmlspecialchars($staffInfo['id']) ?></span></div>
            </div>
            <div class="info-row">
                <div><span class="info-label">Full Name:</span></div>
                <div><span class="info-value"><?= htmlspecialchars($staffInfo['full_name']) ?></span></div>
            </div>
            <div class="info-row">
                <div><span class="info-label">Username:</span></div>
                <div><span class="info-value"><?= htmlspecialchars($staffInfo['username']) ?></span></div>
            </div>
            <div class="info-row">
                <div><span class="info-label">Registered Since:</span></div>
                <div><span class="info-value"><?= htmlspecialchars($staffInfo['created_at']) ?></span></div>
            </div>
        </div>
        
        <!-- Student Search Section -->
        <div class="card">
            <h4>🔍 Search Student Records</h4>
            <form method="GET" action="">
                <div class="form-group" style="display: flex; gap: 10px; margin-bottom: 0;">
                    <input type="text" name="search_id" placeholder="Enter Student ID (e.g., STU001)" required value="<?= htmlspecialchars($_GET['search_id'] ?? '') ?>" style="flex: 1;">
                    <button type="submit" class="btn" style="width: auto;">Search</button>
                </div>
            </form>
            <?php if (isset($errorMsg)): ?>
                <p class="error" style="margin-top: 10px; color: #dc3545; background-color: #f8d7da; padding: 10px; border-radius: 4px;">⚠️ <?= htmlspecialchars($errorMsg) ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Student Details Section -->
        <?php if ($searchResult): ?>
        <div class="card">
            <h3>📚 Student Information</h3>
            <div class="staff-info" style="background-color: #f0f8ff; border-left-color: #28a745;">
                <div class="info-row">
                    <div><span class="info-label">Student ID:</span></div>
                    <div><span class="info-value"><?= htmlspecialchars($searchResult['info']['student_id']) ?></span></div>
                </div>
                <div class="info-row">
                    <div><span class="info-label">Full Name:</span></div>
                    <div><span class="info-value"><?= htmlspecialchars($searchResult['info']['full_name']) ?></span></div>
                </div>
                <div class="info-row">
                    <div><span class="info-label">Class:</span></div>
                    <div><span class="info-value"><?= htmlspecialchars($searchResult['info']['class']) ?></span></div>
                </div>
                <div class="info-row">
                    <div><span class="info-label">Registered Since:</span></div>
                    <div><span class="info-value"><?= htmlspecialchars($searchResult['info']['created_at']) ?></span></div>
                </div>
            </div>
            
            <h4 style="margin-top: 20px; color: #007bff;">📝 Marks</h4>
            
            <!-- Add Multiple Marks Form -->
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                <h5 style="color: #856404; margin-top: 0;">➕ Add/Update Marks (up to 5 subjects)</h5>
                <?php if ($markSuccess): ?>
                    <p style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px;"><?= htmlspecialchars($markSuccess) ?></p>
                <?php endif; ?>
                <?php if ($markError): ?>
                    <p style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px;"><?= htmlspecialchars($markError) ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="add_marks" value="1">
                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($searchResult['info']['student_id']) ?>">
                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <thead>
                            <tr style="background-color: #e9ecef;">
                                <th style="padding: 10px; border: 1px solid #ccc; text-align: left; width: 50%;">Subject</th>
                                <th style="padding: 10px; border: 1px solid #ccc; text-align: left; width: 50%;">Marks (0-100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <tr>
                                <td style="padding: 10px; border: 1px solid #ccc;">
                                    <input type="text" name="subject_<?= $i ?>" placeholder="Subject <?= $i ?> (e.g., Math)" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </td>
                                <td style="padding: 10px; border: 1px solid #ccc;">
                                    <input type="number" name="marks_<?= $i ?>" placeholder="Marks" min="0" max="100" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                    
                    <button type="submit" class="btn" style="padding: 10px 20px;">Save All Marks</button>
                </form>
            </div>
            
            <h4 style="color: #007bff;">📊 Student Marks</h4>
            <?php if (count($searchResult['marks']) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($searchResult['marks'] as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['subject']) ?></td>
                        <td><?= htmlspecialchars($m['marks']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">No marks recorded yet.</p>
            <?php endif; ?>
            
            <!-- <h4 style="margin-top: 20px; color: #007bff;">📅 Attendance</h4>
            <?php if (count($searchResult['attendance']) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($searchResult['attendance'] as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['attendance_date']) ?></td>
                        <td>
                            <?php 
                                $statusClass = '';
                                if ($a['status'] == 'Present') $statusClass = 'style="color: #28a745; font-weight: bold;"';
                                elseif ($a['status'] == 'Absent') $statusClass = 'style="color: #dc3545; font-weight: bold;"';
                                elseif ($a['status'] == 'Late') $statusClass = 'style="color: #ffc107; font-weight: bold;"';
                            ?>
                            <span <?= $statusClass ?>><?= htmlspecialchars($a['status']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">No attendance recorded yet.</p>
            <?php endif; ?> -->
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
