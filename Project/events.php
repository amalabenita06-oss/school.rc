<?php
// Sample events data (replace with database later)
$events = [
    [
        'title' => 'Annual Science Exhibition',
        'date' => 'November 20, 2025',
        'image' => 'https://images.unsplash.com/photo-1588072432836-e10032774350',
        'description' => 'Students from all grades will showcase their innovative science projects. Parents and guests are welcome to explore and encourage young inventors!'
    ],
    [
        'title' => 'Sports Day',
        'date' => 'December 5, 2025',
        'image' => 'https://images.unsplash.com/photo-1600880292089-90a7e086ee0c',
        'description' => 'Join us for a fun-filled day of athletic events, teamwork, and school spirit. Let\'s celebrate our champions!'
    ],
    [
        'title' => 'Republic Day Celebration',
        'date' => 'January 15, 2026',
        'image' => 'https://images.unsplash.com/photo-1551434678-e076c223a692',
        'description' => 'Patriotic performances, speeches, and cultural activities to honor our nation. Parents are invited to attend.'
    ]
];

// **FIXED**: Define $today before using it in array_filter
$today = date('Y-m-d');
$upcoming_events = array_filter($events, function($event) use ($today) {
    return strtotime($event['date']) >= strtotime($today);
});

// Fallback if no upcoming events - show all events
if (empty($upcoming_events)) {
    $upcoming_events = $events;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | R. C. School</title>
    <style>
        /* Exact same CSS from your file */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        header { background-color: rgb(58, 150, 207); color: #fff; display: flex; justify-content: space-between; align-items: center; height: 10vh; width: 100%; padding: 0 20px; }
        .logo h3 { font-size: 1.5em; font-weight: bold; }
        nav ul { list-style: none; display: flex; gap: 25px; }
        nav ul li { position: relative; }
        nav ul li a { color: white; text-decoration: none; font-size: 18px; transition: 0.3s; }
        nav ul li a:hover { color: #002233; }
        nav ul li a.active { color: #002233; font-weight: bold; }
        .dropdown-menu { display: none; position: absolute; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); min-width: 180px; z-index: 100; }
        .dropdown-menu li a { color: #0056b3; padding: 12px 15px; display: block; transition: 0.3s; }
        .dropdown-menu li a:hover { background: #e3f2fd; padding-left: 20px; }
        nav ul li:hover .dropdown-menu { display: block; }
        .page-title { text-align: center; padding: 40px 0 30px; font-size: 32px; font-weight: bold; color: #005c99; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); margin-bottom: 0; }
        .events-container { max-width: 1000px; margin: 0 auto 40px; padding: 0 20px; }
        .no-events { text-align: center; padding: 50px; color: #666; font-size: 1.2em; }
        .past-events { background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .event-card { background-color: white; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 30px; overflow: hidden; display: flex; flex-wrap: wrap; transition: transform 0.2s ease; }
        .event-card:hover { transform: scale(1.02); }
        .event-image { flex: 1 1 300px; height: 220px; background-size: cover; background-position: center; }
        .event-info { flex: 2 1 400px; padding: 20px; }
        .event-info h2 { color: #004aad; margin-top: 0; }
        .event-info p { color: #555; line-height: 1.6; }
        .event-date { font-weight: bold; color: #e63946; margin-bottom: 10px; }
        .footer { height: 10vh; width: 100%; background-color: rgb(58, 150, 207); color: white; font-size: 30px; display: flex; align-items: center; justify-content: center; }
        @media (max-width: 768px) { 
            .event-card { flex-direction: column; } 
            .event-image { height: 200px; flex: 1 1 auto; } 
            nav ul { flex-direction: column; gap: 10px; position: absolute; top: 100%; right: 20px; background: rgb(58, 150, 207); flex-wrap: wrap; } 
        }
    </style>
</head>
<body>
<?php
$activePage = 'events';
require_once __DIR__ . '/includes/navbar.php';
?>

    <!-- Page Title -->
    <h1 class="page-title">📅 School Events</h1>

    <!-- Events Grid -->
    <div class="events-container">
        <?php if (!empty($upcoming_events)): ?>
            <?php foreach ($upcoming_events as $event): ?>
                <div class="event-card">
                    <div class="event-image" style="background-image: url('<?php echo htmlspecialchars($event['image']); ?>');"></div>
                    <div class="event-info">
                        <div class="event-date">📅 <?php echo htmlspecialchars($event['date']); ?></div>
                        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-events">
                No upcoming events at the moment. Check back soon!
                <br><br>
                <small>Past events are shown below:</small>
            </div>
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div class="event-image" style="background-image: url('<?php echo htmlspecialchars($event['image']); ?>');"></div>
                    <div class="event-info">
                        <div class="event-date past-events">📅 <?php echo htmlspecialchars($event['date']); ?> (Past)</div>
                        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <marquee>&copy; <?= date('Y') ?> R. C. SCHOOL - Events Page</marquee>
    </div>
</body>
</html>
