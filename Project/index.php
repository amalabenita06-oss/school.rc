<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ADDED: Times New Roman font family for entire page */
        * { font-family: 'Times New Roman', Times, serif !important; }
        
        .hero { background: #004494; color: white; padding: 60px 20px; text-align: center; border-radius: 8px; margin-bottom: 40px; }
        .hero h1 { margin: 0 0 10px 0; font-size: 2.5em; }
        .hero p { margin: 0; font-size: 1.2em; opacity: 0.9; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .portal-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s; text-decoration: none; color: inherit; display: block;}
        .portal-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .portal-card h3 { color: #0056b3; margin-top: 0; }
        .portal-card p { color: #666; font-size: 0.9em; margin-bottom:0; }
    </style>
</head>
<body>
<?php
$activePage = 'login';
require_once __DIR__ . '/includes/navbar.php';
?>
    <div class="container">
        <div class="hero">
            <h1>Welcome to the School Management System</h1>
            <p>Select your portal to continue</p>
        </div>

        <div class="grid">
            <a href="admin/login.php" class="portal-card">
                <h3>Admin Portal</h3>
                <p>Manage entire system</p>
            </a>
            
            <a href="staff/login.php" class="portal-card">
                <h3>Staff Portal</h3>
                <p>View student records</p>
            </a>
            
            <a href="student/login.php" class="portal-card">
                <h3>Student Portal</h3>
                <p>View marks & attendance</p>
            </a>
        </div>
    </div>
</body>
</html>
