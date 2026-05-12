<?php
require_once 'config.php';
$tables = ['alumni', 'admissions', 'staff', 'students'];
foreach ($tables as $table) {
    echo "--- $table ---\n";
    $stmt = $pdo->query("DESCRIBE $table");
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    echo "\n";
}
?>
