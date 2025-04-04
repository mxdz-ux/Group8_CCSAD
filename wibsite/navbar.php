<nav class="navbar">
    <div class="logotext">
        <a href="home.php"><img class="logo" src="assets/img/Logo5.png" alt="Reading Club 2000 logo"></a>
        <h1>Reading Club 2000</h1>
    </div>
    <ul class="nav-links">
        <li><a href="home.php" <?php echo basename($_SERVER['PHP_SELF']) === 'home.php' ? 'class="active"' : ''; ?>>HOME</a></li>
        <li><a href="catalog.php" <?php echo basename($_SERVER['PHP_SELF']) === 'catalog.php' ? 'class="active"' : ''; ?>>CATALOG</a></li>
        <li><a href="services.php" <?php echo basename($_SERVER['PHP_SELF']) === 'services.php' ? 'class="active"' : ''; ?>>SERVICES</a></li>
        <li><a href="about.php" <?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? 'class="active"' : ''; ?>>ABOUT US</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="my_books.php" <?php echo basename($_SERVER['PHP_SELF']) === 'my_books.php' ? 'class="active"' : ''; ?>>MY BOOKS</a></li>
            <li><a href="notifications.php" <?php echo basename($_SERVER['PHP_SELF']) === 'notifications.php' ? 'class="active"' : ''; ?>>
                NOTIFICATIONS
                <?php
                // Show unread count
                if (isset($_SESSION['user_id'])) {
                    $stmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
                    $stmt->execute([$_SESSION['user_id']]);
                    $unread_count = $stmt->fetchColumn();
                    
                    if ($unread_count > 0) {
                        echo '<span class="badge">' . $unread_count . '</span>';
                    }
                }
                ?>
            </a></li>
            <li><a href="logout.php">LOGOUT (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        <?php else: ?>
            <li><a href="login.php" <?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'class="active"' : ''; ?>>LOGIN</a></li>
            <li><a href="signup.php" <?php echo basename($_SERVER['PHP_SELF']) === 'signup.php' ? 'class="active"' : ''; ?>>SIGN UP</a></li>
        <?php endif; ?>
    </ul>
    <div class="Hamburger">
        <span class="Bar"></span>
        <span class="Bar"></span>
        <span class="Bar"></span>
    </div>
</nav>