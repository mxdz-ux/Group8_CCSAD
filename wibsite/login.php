<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        // Check user credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            
            // Store admin status in session
            $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
            
            // Redirect admin users to admin dashboard, regular users to index
            if ($_SESSION['is_admin'] == 1) {
                redirect('admin.php');
            } else {
                redirect('index.php');
            }
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Reading Club 2000</title>
    <link rel="stylesheet" href="assets/css/loginn.css">
</head>
<body>

<div class="fullscreen-wrapper">
        <h1>Reading Club 2000</h1>
        <div class="auth-container">
            <h2>Login</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <p><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="········" required>
                </div>
                
                <button type="submit" class="btn">LOGIN</button>
            </form>
            <br>
            <div class="divider"></div>
            
            <div class="auth-footer">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </div>
        </div>
    </div>
</body>
</html>