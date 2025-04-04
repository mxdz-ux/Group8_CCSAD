<?php
// Start session for user tracking
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Club 2000 - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/catalog.css">
</head>
<body style="background-image: url('assets/img/back.png');">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logotext">
            <img class="logo" src="assets/img/Logo5.png" alt="Reading Club Logo">
            <h1>Reading Club 2000</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">HOME</a></li>
            <li><a href="catalog.php">CATALOG</a></li>
            <li><a href="services.php">SERVICES</a></li>
            <li><a href="about.php">ABOUT US</a></li>
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

    <!-- Catalog Section (from your catalog.html) -->
    <div class="slider-container">
        <div class="slider-wrapper">
            <div class="slider-item">
                <div class="slide-content">
                    <h3 class="slide-subtitle">Featured</h3>
                    <p class="slide-description">Browse our extensive collection of books</p>
                    <a href="catalog.php" class="slide-button">View All Books</a>
                </div>
                <img src="assets/img/featured.png" alt="Featured Books" class="slider-image">
            </div>
        </div>
    </div>
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

    <!-- Latest News Section -->
    <section id="news" class="news-section">
        <h2>Donating Books</h2>
        <hr>
        <div class="news-grid">
            <div class="news-card">
                <img src="assets/img/news1.PNG" alt="Event 1">
                <div class="news-content">
                    <h3>New Canipo National High School</h3>
                    <p>In collaboration with JASON AND ROMELISA BERNARDO on our Community Engagement...</p>
                    <button><a href="https://www.facebook.com/ReadingClub2000/posts/free-book-giving-mission-in-palawan-province-to-reach-the-indegenous-reading-com/997898182373127/" target="_blank">Read More</a></button>
                </div>
            </div>
            <div class="news-card">
                <img src="assets/img/news2.PNG" alt="Event 2">
                <div class="news-content">
                    <h3>Reader's of Barrio</h3>
                    <p>Our free book giving mission now includes K-12 textbooks, old toys, and school supplies...</p>
                    <button><a href="https://www.facebook.com/ReadingClub2000/posts/bookdonors-and-book-missionariesmaraming-salamat-po-sa-inyong-supporta-sa-readin/981332400696372/" target="_blank">Read More</a></button>
                </div>
            </div>
            <div class="news-card">
                <img src="assets/img/news4.jpg" alt="Event 3">
                <div class="news-content">
                    <h3>Project Hara - Help Albay Rise Again</h3>
                    <p>Youth-led relief efforts in Libon, Albay with our free books through the initiative...</p>
                    <button><a href="https://www.facebook.com/share/p/15oGsHGfwc/" target="_blank">Read More</a></button>
                </div>
            </div>
        </div>
    </section>
    <!-- JavaScript Files -->
    <script src="script.js"></script>
</body>
</html>