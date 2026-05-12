<?php
// navbar.php - shared navigation bar for all public pages
// Usage: set $activePage to one of: home, admission, alumni, events, contact, about, login before including

if (!isset($activePage)) {
    $activePage = '';
}
// basePath allows adjusting links when this file is included from a subdirectory.
// set $basePath = '../'; in pages inside a folder (admission, alumni, staff, etc.)
if (!isset($basePath)) {
    $basePath = '';
}

function navLink($name, $href, $key) {
    global $activePage, $basePath;
    $class = $activePage === $key ? 'class="active"' : '';
    // prepend basePath to href unless href already looks absolute
    $finalHref = preg_match('#^(https?://|/)#', $href) ? $href : $basePath . $href;
    echo "<li><a href=\"$finalHref\" $class>$name</a></li>\n";
}
?>
<style>
    /* common navbar styles */
    header { background-color: rgb(58, 150, 207); color: #fff; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; height: 10vh; }
    .logo { font-size: 1.5em; font-weight: bold; }
    nav ul { list-style: none; display: flex; }
    nav ul li { margin-left: 25px; position: relative; }
    nav ul li a { color: white; text-decoration: none; font-size: 18px; transition: 0.3s; }
    nav ul li a:hover, nav ul li a.active { color: #002233; font-weight: bold; }
</style>
<header>
    <div class="logo"><h3>R. C. SCHOOL</h3></div>
    <nav>
        <ul>
            <?php
            navLink('HOME', 'home.php', 'home');
            navLink('ADMISSION', 'admission/index.php', 'admission');
            navLink('ALUMNI', 'alumni/index.php', 'alumni');
            navLink('EVENTS', 'events.php', 'events');
            navLink('CONTACT', 'contact.php', 'contact');
            navLink('ABOUT', 'about.php', 'about');
            navLink('LOGIN', 'index.php', 'login');
            ?>
        </ul>
    </nav>
</header>
