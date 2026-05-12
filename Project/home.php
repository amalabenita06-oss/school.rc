<?php
// Dynamic slider images configuration
$slider_images = [
    ['src' => 'img/2.jpg', 'alt' => 'School Main Building', 'effect' => 'curtain'],
    ['src' => 'img/2.jpg', 'alt' => 'School Main Building', 'effect' => 'curtain'],
    ['src' => 'img/3.jpg', 'alt' => 'Classroom Session', 'effect' => 'zoomIn'],
    ['src' => 'img/4.jpg', 'alt' => 'Sports Day', 'effect' => 'slideLeft'],
    ['src' => 'img/5.jpg', 'alt' => 'Science Exhibition', 'effect' => 'blurReveal']
];

// Admin preview mode (optional)
$is_admin_preview = isset($_GET['preview']) && $_GET['preview'] == 'admin';
$current_time = date('H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R. C. SCHOOL </title>
    <style>
        /* Your exact CSS - no changes */
        * { margin: 0; }
        header { background-color: rgb(58, 150, 207); color: #fff; display: flex; justify-content: space-between; align-items: center; height: 10vh; width: 100%; padding: 0 20px; }
        .logo { font-size: 1.5em; font-weight: bold; text-align: left; }
        nav ul { list-style: none; display: flex; }
        nav ul li { margin-left: 25px; position: relative; }
        nav ul li a { color: white; text-decoration: none; font-size: 18px; transition: 0.3s; }
        nav ul li a:hover { color: #002233; }
        .footer { height: 10vh; width: 100%; background-color: rgb(58, 150, 207); color: white; font-size: 30px; display: flex; align-items: center; justify-content: center; }
        .dropdown-menu { display: none; position: absolute; background: white; border-radius: 10px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); min-width: 180px; z-index: 100; }
        .dropdown-menu li a { color: #0056b3; padding: 12px 15px; display: block; transition: 0.3s; }
        .dropdown-menu li a:hover { background: #e3f2fd; padding-left: 20px; }
        nav ul li:hover .dropdown-menu { display: block; }
        
        /* Slider styles - same as yours */
        .slideshow-container { width: 100%; height: 590px; position: relative; overflow: hidden; z-index: -1000; }
        .mySlides { display: none; height: 590px; }
        .mySlides img { width: 100%; height: 590px; object-fit: cover; background-size: auto; }
        .curtain { animation: curtainEffect 2.5s ease; }
        @keyframes curtainEffect { 0% { clip-path: polygon(50% 0, 50% 0, 50% 100%, 50% 100%); opacity: 0; } 100% { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); opacity: 1; } }
        .zoomIn { animation: zoomInEffect 2.5s ease; }
        @keyframes zoomInEffect { from { transform: scale(1.2); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .slideLeft { animation: slideLeftEffect 2.5s ease; }
        @keyframes slideLeftEffect { from { transform: translateX(80px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .blurReveal { animation: blurRevealEffect 2.5s ease; }
        @keyframes blurRevealEffect { from { filter: blur(20px); opacity: 0; } to { filter: blur(0px); opacity: 1; } }
        .dot-container { text-align: center; margin-top: -40px; position: relative; }
        .dot { height: 12px; width: 12px; margin: 0 5px; background-color: #bbb; border-radius: 50%; display: inline-block; transition: background-color 0.3s; cursor: pointer; }
        .dot:hover { background-color: #717171; }
        .active { background-color: #ffffff; }
        
        /* Admin preview styles */
        <?php if($is_admin_preview): ?>
        .admin-indicator { position: fixed; top: 10px; right: 10px; background: #ff4444; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; z-index: 9999; }
        <?php endif; ?>
        
        @media (max-width: 768px) { .slideshow-container, .mySlides, .mySlides img { height: 300px; } nav ul { flex-direction: column; position: absolute; top: 100%; right: 0; background: rgb(58, 150, 207); } }
    </style>
</head>
<body>
<?php
$activePage = 'home';
require_once __DIR__ . '/includes/navbar.php';
?>

    <?php if($is_admin_preview): ?>
    <div class="admin-indicator">ADMIN PREVIEW - <?= $current_time ?></div>
    <?php endif; ?>

    <!-- Dynamic Image Slider -->
    <div class="slideshow-container">
        <?php foreach($slider_images as $index => $slide): ?>
            <div class="mySlides <?= htmlspecialchars($slide['effect']) ?>">
                <img src="<?= htmlspecialchars($slide['src']) ?>" alt="<?= htmlspecialchars($slide['alt']) ?>" loading="lazy">
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Dynamic Dots -->
    <div class="dot-container">
        <?php foreach($slider_images as $index => $slide): ?>
            <span class="dot" onclick="currentSlide(<?= $index + 1 ?>)"></span>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <marquee> 📢🎄 23 CHRISTMAS CELEBRATION 🎄📢 </marquee>
    </div>

    <!-- Enhanced Slider Script -->
    <script>
        let slideIndex = 0;
        const totalSlides = <?= count($slider_images) ?>;
        
        // Auto slideshow
        function showSlides(n = null) {
            let i, slides, dots;
            
            slides = document.getElementsByClassName("mySlides");
            dots = document.getElementsByClassName("dot");
            
            // Hide all slides
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            
            // Reset dots
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            
            // Set current slide
            if (n) {
                slideIndex = n - 1;
            } else {
                slideIndex++;
                if (slideIndex > totalSlides) slideIndex = 1;
            }
            
            slides[slideIndex].style.display = "block";
            dots[slideIndex].className += " active";
            
            // Next slide after 3 seconds
            setTimeout(() => showSlides(), 3000);
        }
        
        // Manual dot click
        function currentSlide(n) {
            showSlides(n);
        }
        
        // Start slideshow
        showSlides();
    </script>
</body>
</html>
