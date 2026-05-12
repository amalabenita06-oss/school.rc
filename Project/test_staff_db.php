<?php
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

// Check staff table
echo "<h3>Checking Staff Table:</h3>";
$staffStmt = $pdo->query("SELECT * FROM staff");
$staffList = $staffStmt->fetchAll();

if (count($staffList) > 0) {
    echo "<p>✅ Found " . count($staffList) . " staff member(s):</p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Full Name</th><th>Created At</th></tr>";
    foreach ($staffList as $staff) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($staff['id']) . "</td>";
        echo "<td>" . htmlspecialchars($staff['username']) . "</td>";
        echo "<td>" . htmlspecialchars($staff['full_name']) . "</td>";
        echo "<td>" . htmlspecialchars($staff['created_at']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>❌ No staff members found in database. You need to register staff first.</p>";
    echo "<p><a href='admin/dashboard.php'>Go to Admin Panel</a> → <strong>Register Staff</strong></p>";
}

echo "<h3>How to Register Staff:</h3>";
echo "<ol>";
echo "<li>Login to Admin Panel</li>";
echo "<li>Click 'Register Staff' in navigation</li>";
echo "<li>Fill in: Full Name, Username, Password</li>";
echo "<li>Click 'Register Staff'</li>";
echo "</ol>";

echo "<h3>Test Staff Login:</h3>";
echo "<p>Once staff are registered, use their username and password to login at: <a href='staff/login.php'>/staff/login.php</a></p>";
?>
