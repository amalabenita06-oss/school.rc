<?php
require_once '../config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$studentId = $_SESSION['student_id'];

// Fetch marks
$mStmt = $pdo->prepare("SELECT subject, marks FROM marks WHERE student_id = ?");
$mStmt->execute([$studentId]);
$marks = $mStmt->fetchAll();

// Fetch attendance
$aStmt = $pdo->prepare("SELECT attendance_date, status FROM attendance WHERE student_id = ?");
$aStmt->execute([$studentId]);
$attendance = $aStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .info-box { background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <h2>Student Panel</h2>
        <a href="?logout=true">Logout</a>
    </div>
    
    <div class="container">
        <div class="card info-box">
            <h3 style="margin-top: 0; color: #007bff;">👤 Welcome</h3>
            <p style="margin: 5px 0;"><strong>Name:</strong> <?= htmlspecialchars($_SESSION['student_name']) ?></p>
            <p style="margin: 5px 0;"><strong>Student ID:</strong> <?= htmlspecialchars($studentId) ?></p>
            <p style="margin: 5px 0;"><strong>Class:</strong> <?= htmlspecialchars($_SESSION['student_class']) ?></p>
        </div>
        
        <div class="card">
            <h4>📚 My Marks</h4>
                <?php if (count($marks) > 0): ?>
                <table>
                    <thead>
                        <tr><th>Subject</th><th>Marks</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($marks as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['subject']) ?></td>
                            <td><?= htmlspecialchars($m['marks']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p style="color: #666; font-style: italic;">No marks recorded yet. Staff will add your marks soon.</p>
                <?php endif; ?>
        </div>

        <!-- <div class="card">
            <h4>📋 My Attendance</h4>
                <?php if (count($attendance) > 0): ?>
                <table>
                    <thead>
                        <tr><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($attendance as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['attendance_date']) ?></td>
                            <td>
                                <?php 
                                    if ($a['status'] == 'Present') echo '<span style="color: #28a745; font-weight: bold;">'.$a['status'].'</span>';
                                    elseif ($a['status'] == 'Absent') echo '<span style="color: #dc3545; font-weight: bold;">'.$a['status'].'</span>';
                                    elseif ($a['status'] == 'Late') echo '<span style="color: #ffc107; font-weight: bold;">'.$a['status'].'</span>';
                                    else echo htmlspecialchars($a['status']);
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p style="color: #666; font-style: italic;">No attendance recorded yet.</p>
                <?php endif; ?>
        </div> -->
    </div>
</body>
</html>
