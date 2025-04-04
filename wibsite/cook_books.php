<?php
session_start();
$page_title = "Cook Books";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Reading Club 2000</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background-image: url('assets/img/back.png');">

<nav class="navbar">
    <div class="logotext">
        <a href="catalog.php"><img class="logo" src="assets/img/Logo5.png" alt="Reading Club Logo"></a>
        <h1>Reading Club 2000</h1>
    </div>
    <ul class="nav-links">
        <li><a href="home.php">HOME</a></li>
        <li><a href="catalog.php" class="active">CATALOG</a></li>
        <li><a href="services.php">SERVICES</a></li>
        <li><a href="about.php">ABOUT US</a></li>
    </ul>
    <div class="Hamburger">
        <span class="Bar"></span>
        <span class="Bar"></span>
        <span class="Bar"></span>
    </div>
</nav>
<header>
    <br>
    <br>
    <nav>
      <div class="container">
      <a href="catalog.php" class="back-button">&#8592; Back to Catalog</a> 
      </div>
    </nav>
  </header>

<div class="container">
    <h1 style="text-align: center; margin-bottom: 30px;">Cooking Books Collection</h1>
    <div class="book-grid">
          <!-- Book 1 -->
          <div class="book">
                <img src="assets/img/book1.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java Programming 8E</p>
                    <p style="font-size: 12px;">A novel by F. Scott Fitzgerald.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 3 -->
            <div class="book">
                <img src="assets/img/book3.jpg" alt="Book Cover">
                <div class="details">
                    <p>SharePoint 2010 Branding and User Interface Design</p>
                    <p style="font-size: 12px;">A novel by Herman Melville.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 1 -->
            <div class="book">
                <img src="assets/img/book1.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java Programming 8E</p>
                    <p style="font-size: 12px;">A novel by F. Scott Fitzgerald.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <button>borrow</button>
                </div>
            </div>
       <!-- Book 1 -->
       <div class="book">
                <img src="assets/img/book1.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java Programming 8E</p>
                    <p style="font-size: 12px;">A novel by F. Scott Fitzgerald.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 3 -->
            <div class="book">
                <img src="assets/img/book3.jpg" alt="Book Cover">
                <div class="details">
                    <p>SharePoint 2010 Branding and User Interface Design</p>
                    <p style="font-size: 12px;">A novel by Herman Melville.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 1 -->
            <div class="book">
                <img src="assets/img/book1.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java Programming 8E</p>
                    <p style="font-size: 12px;">A novel by F. Scott Fitzgerald.</p>
                    <button>borrow</button>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <button>borrow</button>
                </div>
            </div>
        </div>
    </div>

<script src="script.js"></script>
</body>
</html>