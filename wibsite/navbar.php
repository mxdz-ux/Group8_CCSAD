<!-- navbar.php -->
<nav class="navbar">
    <div class="logotext">
        <img class="logo" src="assets/img/Logo5.png" alt="MCPL logo">
        <h1>Reading Club 2000</h1>
    </div>
    <ul class="nav-links">
        <li><a href="home.php" <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>HOME</a></li>
        <li><a href="catalog.php" <?php if(basename($_SERVER['PHP_SELF']) == 'catalog.php') echo 'class="active"'; ?>>CATALOG</a></li>
        <li><a href="services.php" <?php if(basename($_SERVER['PHP_SELF']) == 'services.php') echo 'class="active"'; ?>>SERVICES</a></li>
        <li><a href="about.php" <?php if(basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>ABOUT US</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="my_books.php" <?php if(basename($_SERVER['PHP_SELF']) == 'my_books.php') echo 'class="active"'; ?>>MY BOOKS</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        <?php else: ?>
            <li><a href="login.php" <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'class="active"'; ?>>LOGIN</a></li>
        <?php endif; ?>
    </ul>
    <div class="Hamburger">
        <span class="Bar"></span>
        <span class="Bar"></span>
        <span class="Bar"></span>
    </div>
</nav>