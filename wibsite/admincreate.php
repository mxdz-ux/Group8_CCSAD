<?php
require_once 'config.php';

// This script should only be run once and then removed or secured
// Alternatively, password protect it or only allow it to be run locally

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert admin user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, is_admin) VALUES (?, ?, ?, ?, ?, 1)");
    $result = $stmt->execute([$username, $email, $hashed_password, $first_name, $last_name]);
    
    if ($result) {
        echo "Admin account created successfully!";
        redirect('admin.php');
    } else {
        echo "Error creating admin account.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/loginn.css">
</head>
<body>
    <div class="fullscreen-wrapper">
        <h1>Reading Club 2000</h1>
        <div class="auth-container">
            <h2>Create Admin Account</h2>
            
            <form action="admin_create.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                
                <button type="submit" class="btn"> Admin</button>
            </form>
        </div>
    </div>
</body>
</html>