<?php
session_start();
$page_title = "Young Adult Books";
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
<?php include 'navbar.php'; ?>
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
    <h1 style="text-align: center; margin-bottom: 30px;">Young Adult Books Collection</h1>
    <div class="book-grid">
          <!-- Book 1 -->
          <div class="book">
                <img src="assets/img/book1.jpg" alt="Book Cover">
                
                <div class="details">
                    <p>Java Programming 8E</p>
                    <p style="font-size: 12px;">A novel by F. Scott Fitzgerald.</p>
                    <!-- Inside your book card HTML, add this form/button -->
<form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="1">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="2">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <!-- Book 3 -->
            <div class="book">
                <img src="assets/img/book3.jpg" alt="Book Cover">
                <div class="details">
                    <p>SharePoint 2010 Branding and User Interface Design</p>
                    <p style="font-size: 12px;">A novel by Herman Melville.</p>
                    
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="3">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <!-- Book 1 -->
            <div class="book">
                <img src="assets/img/book1.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java Programming 8E</p>
                    <p style="font-size: 12px;">A novel by F. Scott Fitzgerald.</p>
                    
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="4">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="5">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
       <!-- Book 1 -->
       <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="5">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="5">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <!-- Book 3 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="8">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="7">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="book">
                <img src="assets/img/book2.jpg" alt="Book Cover">
                <div class="details">
                    <p>Java for Dummies</p>
                    <p style="font-size: 12px;">A fantasy novel by J.K. Rowling.</p>
                    <form action="borrow.php" method="POST">
    <input type="hidden" name="book_id" value="6">
    <button type="submit" class="borrow-btn">Borrow</button>
</form>
                </div>
            </div>
        </div>
    </div>

<script src="script.js"></script>
</body>
</html>