<?php
// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'reading_club_2000');
define('DB_CHARSET', 'utf8mb4');
// Email configuration
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USERNAME', 'your_email@example.com');
define('SMTP_PASSWORD', 'your_email_password');
define('SMTP_PORT', 587);
define('FROM_EMAIL', 'noreply@readingclub2000.com');
define('FROM_NAME', 'Reading Club 2000');

// application

define('BASE_URL', 'http://localhost/reading-club-2000');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session
session_start();

// Helper functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>