<?php
// Form submission handling
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($subject)) $errors[] = 'Subject is required';
    if (empty($message)) $errors[] = 'Message is required';
    
    if (empty($errors)) {
        // Email sending (replace with your email)
        $to = 'rcschool@gmail.com';
        $email_subject = "Contact Form: $subject";
        $email_body = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email\r\n";
        
        if (mail($to, $email_subject, $email_body, $headers)) {
            $message_sent = true;
        } else {
            $error_message = 'Failed to send message. Try again later.';
        }
    } else {
        $error_message = implode(', ', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | R. C. SCHOOL</title>
    <style>
        /* Same CSS as your HTML - copied exactly */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        header { background-color: rgb(58, 150, 207); color: #fff; display: flex; justify-content: space-between; align-items: center; height: 10%; width: 100%; padding: 20px; position: fixed; top: 0; z-index: 1000; }
        .logo { font-size: 1.5em; font-weight: bold; text-align: left; }
        nav ul { list-style: none; display: flex; }
        nav ul li { margin-left: 25px; }
        nav ul li a { color: white; text-decoration: none; font-size: 18px; transition: 0.3s; }
        nav ul li a:hover { color: #002233; }
        /* .banner { text-align: center; color: white; padding: 150px 20px 100px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('contact-banner.jpg') center/cover no-repeat; margin-top: 10%; } */
        /* .banner h1 { font-size: 2.5em; margin-bottom: 10px; } */
        /* .banner p { font-size: 1.1em; } */
        .contact-section { background-color: #f5f8ff; padding: 60px 20px; display: flex; justify-content: center; }
        .contact-container { display: flex; gap: 40px; max-width: 1100px; width: 100%; flex-wrap: wrap; }
        .contact-info, .contact-form { background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); flex: 1 1 400px; }
        .contact-info h2, .contact-form h2 { color: #004aad; margin-bottom: 15px; }
        .contact-info ul { list-style: none; margin-top: 10px; }
        .contact-info li { margin-bottom: 10px; color: #333; line-height: 1.6; }
        label { display: block; margin-top: 10px; font-weight: 500; }
        input, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em; }
        button { margin-top: 20px; width: 100%; padding: 12px; border: none; background-color: #004aad; color: white; font-size: 1em; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        button:hover { background-color: #003080; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .map-section { text-align: center; padding: 50px 20px; }
        .map-section h2 { margin-bottom: 20px; color: #004aad; }
        iframe { border: 0; border-radius: 10px; }
        footer { text-align: center; background-color: rgb(58, 150, 207); color: white; padding: 15px; margin-top: 40px; }
        .dropdown-menu { display: none; position: absolute; background: white; border-radius: 10px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); min-width: 180px; z-index: 100; list-style: none; padding: 10px 0; }
        .dropdown-menu li a { color: #0056b3; padding: 12px 15px; display: block; transition: 0.3s; }
        .dropdown-menu li a:hover { background: #e3f2fd; padding-left: 20px; }
        nav ul li:hover .dropdown-menu { display: block; }
        @media (max-width: 768px) { header { flex-direction: column; text-align: center; } nav ul { flex-direction: column; margin-top: 10px; } nav ul li { margin: 10px 0; } .banner { padding: 100px 15px 70px; } .contact-container { flex-direction: column; } }
    </style>
</head>
<body>
<?php
$activePage = 'contact';
require_once __DIR__ . '/includes/navbar.php';
?>

    <!-- Banner -->
    
    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>Have a question about admissions, programs, or events? We'd love to hear from you!</p>
                <ul>
                    <li><strong>📍 Address:</strong> R. C. School, Periya Annaikarai Patty, Vaiyampatty</li>
                    <li><strong>📞 Phone:</strong> 9344861510</li>
                    <li><strong>✉️ Email:</strong> rcschool@gmail.com</li>
                    <li><strong>🕒 Hours:</strong> Mon – Fri, 8:00 AM – 4:30 PM</li>
                </ul>
            </div>

            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <?php if ($message_sent): ?>
                    <div class="success">Thank you! Your message has been sent successfully.</div>
                <?php elseif ($error_message): ?>
                    <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                
                <div class="contact-box reveal">

<form action="https://api.web3forms.com/submit" method="POST">

<input type="hidden" name="access_key" value="bf04d3fa-0bc0-4304-8f21-133bc95ca695">
<label for="name">Full Name</label>
<input type="text" id="name" name="name" required>

<label for="email">Email Address</label>
<input type="email" id="email" name="email" required>

<label for="subject">Subject</label>
<input type="text" id="subject" name="subject" required>

<label for="message">Message</label>
<textarea id="message" name="message" rows="4" required></textarea>

<button type="submit">Send Message</button>

</form>

</div>
        </div>
    </section>

    <!-- Map -->
    <section class="map-section">
        <h2>Find Us on the Map</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d980.4771484795033!2d78.29572047504152!3d10.586319415943239!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3baa6d5385d8e9a5%3A0x96bf85dedfea8405!2sR.c%20high%20School!5e0!3m2!1sen!2sin!4v1764845134102!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    <!-- Footer -->
    <footer>&copy; R. C. SCHOOL</footer>
</body>
</html>
