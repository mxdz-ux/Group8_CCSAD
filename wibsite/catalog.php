<?php
// Start session for user tracking
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - Reading Club 2000</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/catalog.css"> <!-- Separate CSS file -->
</head>
<body class="catalog">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logotext">
            <img class="logo" src="assets/img/Logo5.png" alt="MCPL logo">
            <h1>Reading Club 2000</h1>
        </div>
        <ul class="nav-links">
            <li><a href="home.html">HOME</a></li>
            <li><a href="catalog.html" class="active">CATALOG</a></li>
            <li><a href="services.html">SERVICES</a></li>
            <li><a href="about.html">ABOUT US</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="my_books.php">MY BOOKS</a></li>
                <li><a href="logout.php">LOGOUT</a></li>
            <?php else: ?>
                <li><a href="login.php">LOGIN</a></li>
            <?php endif; ?>
        </ul>
        <div class="Hamburger">
            <span class="Bar"></span>
            <span class="Bar"></span>
            <span class="Bar"></span>
        </div>
    </nav>

    <!-- Catalog Content -->
    <div class="contain">
        <div class="slides">
            <div class="items" style="background-image: url('assets/img/children.jpg');">
                <div class="content">
                    <div class="name">Children's Books</div>
                    <div class="des">Fun, colorful stories that teach and entertain young readers!</div>
                    <button onclick="window.location.href='children_books.php'">See More</button>
                </div>
            </div>
            
            <div class="items" style="background-image: url('assets/img/YA.png');">
                <div class="content">
                    <div class="name">YA Books</div>
                    <div class="des">Teen-focused books on growth, love, and adventure!</div>
                    <button onclick="window.location.href='ya_books.php'">See More</button>
                </div>
            </div>
            
            <div class="items" style="background-image: url('assets/img/educ.jpg');">
                <div class="content">
                    <div class="name">Educational Books</div>
                    <div class="des">Books for learning, research, and study!</div>
                    <button onclick="window.location.href='educational_books.php'">See More</button>
                </div>
            </div>
            
            <div class="items" style="background-image: url('assets/img/coding.jpg');">
                <div class="content">
                    <div class="name">Programming Books</div>
                    <div class="des">Guides on coding, algorithms, and software development!</div>
                    <button onclick="window.location.href='programming_books.php'">See More</button>
                </div>
            </div>
            
            <div class="items" style="background-image: url('assets/img/cook.jpg');">
                <div class="content">
                    <div class="name">Cook Books</div>
                    <div class="des">Recipes, cooking techniques, and culinary tips!</div>
                    <button onclick="window.location.href='cook_books.php'">See More</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Reading Club 2000</h3>
                <p>Promoting literacy and a love for reading since 2000.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="catalog.php">Catalog</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: info@readingclub2000.com</p>
                <p>Phone: (123) 456-7890</p>
                <p>Address: 123 Book Street, Makati City, Philippines</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Reading Club 2000. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- External JavaScript Files -->
    <script src="script2.js"></script>
</body>
</html>