<?php
// Updated school data with YOUR exact numbers
$school_info = [
    'name' => 'R. C. SCHOOL',
    'students' => '700',        // Changed to 700
    'staff' => '25',           // Changed to 25 (staff)
    'address' => 'Periya Annaikarai Patty, Vaiyampatty',
    'phone' => '9344861510',
    'email' => 'rcschool@gmail.com'
];

$about_sections = [
    [
        'icon' => '🏫',
        'title' => 'Who We Are',
        'content' => 'Our school is a nurturing and vibrant learning community dedicated to academic excellence and character building. We focus on holistic student development through quality education and values.'
    ],
    [
        'icon' => '🎯',
        'title' => 'Our Mission',
        'content' => 'To inspire students to achieve their highest potential, embrace lifelong learning, and become responsible global citizens through innovative teaching and strong values.'
    ],
    [
        'icon' => '🌟',
        'title' => 'Our Vision',
        'content' => 'We envision students who are confident, knowledgeable, compassionate, and ready to contribute to a changing world.'
    ],
    // [
    //     'icon' => '💡',
    //     'title' => 'Why Choose Us?',
    //     'features' => [
    //         'Modern Teaching & Smart Classrooms',
    //         'Experienced and Caring Faculty',
    //         'Safe & Inclusive Environment',
    //         'Strong Extracurricular Programs',
    //         'Focus on Leadership & Discipline'
    //     ]
    // ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About | <?= htmlspecialchars($school_info['name']) ?></title>
<style>
    /* Same CSS - no changes needed */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background: #e9f2ff;  }
    header { background: rgb(58, 150, 207); color: #fff; display: flex; justify-content: space-between; align-items: center; height: 12vh; padding: 0 30px; position: sticky; top: 0; z-index: 100; box-shadow: 0 3px 15px rgba(0,0,0,0.2); }
    .logo { font-size: 1.8em; font-weight: bold; letter-spacing: 1px; animation: slideDown 1s ease; }
    nav ul { display: flex; list-style: none; }
    nav ul li { margin-left: 25px; position: relative; }
    nav ul li a { color: white; font-size: 18px; text-decoration: none; font-weight: 500; transition: 0.3s; }
    nav ul li a:hover, nav ul li a.active { color: #002233; font-weight: bold; }
    .dropdown-menu { display: none; position: absolute; background: #ffffff; border-radius: 10px; min-width: 180px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
    .dropdown:hover .dropdown-menu { display: block; }
    .dropdown-menu li a { display: block; padding: 12px; color: #0056b3; }
    .dropdown-menu li a:hover { background: #e3f2fd; padding-left: 20px; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    
    .hero { width: 100%; height: 350px; background: url("img/5.jpg") center/cover no-repeat; display: flex; justify-content: center; align-items: center; position: relative; }
    .hero::after { content: ""; position: absolute; width: 100%; height: 100%; background: rgba(0,0,0,0.45); }
    .hero h1 { color: white; font-size: 48px; z-index: 10; letter-spacing: 2px; text-shadow: 3px 3px 20px black; animation: fadeUp 1.5s ease; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
    
    .container { width: 85%; margin: 50px auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
    .box { background: rgba(255,255,255,0.6); backdrop-filter: blur(10px); border-radius: 10px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); transition: 0.3s; animation: fadeIn 1s ease; }
    .box:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .box h2 { color: #3a96cf; margin-bottom: 10px; font-size: 28px; }
    .box p, .box li { line-height: 1.7; color: #333; }
    ul li { margin-left: 20px; list-style-position: inside; }
    .icon { font-size: 40px; color: rgb(58,150,207); margin-bottom: 15px; text-align: center; }
    
    /* Updated Stats Bar - 2 columns only */
    .stats-bar { background: rgba(255,255,255,0.9); margin: 40px auto; padding: 30px; border-radius: 15px; text-align: center; max-width: 85%; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
    .stat-item { padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; }
    .stat-number { font-size: 2.5em; font-weight: bold; display: block; }
    .stat-label { font-size: 1.1em; opacity: 0.9; }
    
    footer { margin-top: 60px; background: rgb(58, 150, 207); color: white; text-align: center; padding: 20px; font-size: 18px; }
    
    @media (max-width: 768px) { 
        .hero h1 { font-size: 32px; } 
        .container { width: 95%; } 
        header { padding: 0 15px; flex-direction: column; height: auto; text-align: center; } 
        nav ul { flex-wrap: wrap; justify-content: center; } 
    }
</style>
</head>
<body>
<?php
$activePage = 'about';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Hero -->
<div class="hero">
    <h1>About Our School</h1>
</div>

<!-- School Stats - UPDATED WITH YOUR NUMBERS -->
<div class="stats-bar">
    <h2 style="color: #3a96cf; margin-bottom: 10px;">📊 At a Glance</h2>
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-number"><?= number_format($school_info['students']) ?></span>
            <span class="stat-label">Students</span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?= number_format($school_info['staff']) ?></span>
            <span class="stat-label">Staff</span>
        </div>
    </div>
    <p style="margin-top: 15px; color: #666; font-size: 1.1em;">
        <?= htmlspecialchars($school_info['address']) ?>
    </p>
</div>

<!-- Dynamic Content -->
<div class="container">
    <?php foreach($about_sections as $section): ?>
        <div class="box">
            <div class="icon"><?= htmlspecialchars($section['icon']) ?></div>
            <h2><?= htmlspecialchars($section['title']) ?></h2>
            <?php if(isset($section['content'])): ?>
                <p><?= htmlspecialchars($section['content']) ?></p>
            <?php else: ?>
                <ul>
                    <?php foreach($section['features'] as $feature): ?>
                        <li><?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- Footer -->
<footer>
    © <?= date('Y') ?> <?= htmlspecialchars($school_info['name']) ?> | All Rights Reserved | 
    <?= htmlspecialchars($school_info['phone']) ?> | <?= htmlspecialchars($school_info['email']) ?>
</footer>
</body>
</html>
