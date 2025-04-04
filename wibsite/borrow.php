<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

// Get book details
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    $_SESSION['error'] = "Book not found";
    redirect('catalog.php');
}

// Check if book is available
if ($book['available'] <= 0) {
    $_SESSION['error'] = "This book is currently not available";
    redirect('catalog.php');
}

// Handle borrow request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $borrow_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+14 days')); // 2 weeks borrowing period
    
    try {
        $pdo->beginTransaction();
        
        // Create borrow record
        $stmt = $pdo->prepare("INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status) VALUES (?, ?, ?, ?, 'approved')");
        $stmt->execute([$user_id, $book_id, $borrow_date, $due_date]);
        
        // Update book availability
        $stmt = $pdo->prepare("UPDATE books SET available = available - 1 WHERE id = ?");
        $stmt->execute([$book_id]);
        
        // Create notification
        $message = "You have successfully borrowed '{$book['title']}'. Due date: $due_date";
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user_id, $message]);
        
        $pdo->commit();
        
        // Send email notification
        $user_stmt = $pdo->prepare("SELECT email, first_name FROM users WHERE id = ?");
        $user_stmt->execute([$user_id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
        
        $subject = "Book Borrowed: {$book['title']}";
        $email_message = "Dear {$user['first_name']},\n\n";
        $email_message .= "You have successfully borrowed '{$book['title']}' from Reading Club 2000.\n";
        $email_message .= "Borrow Date: $borrow_date\n";
        $email_message .= "Due Date: $due_date\n\n";
        $email_message .= "Please return the book on or before the due date to avoid penalties.\n\n";
        $email_message .= "Happy reading!\n\n";
        $email_message .= "The Reading Club 2000 Team";
        
        sendEmail($user['email'], $subject, $email_message);
        
        $_SESSION['success'] = "Book borrowed successfully!";
        redirect('my_books.php');
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "An error occurred while processing your request";
        redirect('catalog.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book - Reading Club 2000</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>Borrow Book</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <p><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="book-details">
            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-cover">
            
            <div class="book-info">
                <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($book['category']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($book['description']); ?></p>
                <p><strong>Available:</strong> <?php echo $book['available']; ?></p>
            </div>
        </div>
        
        <form action="borrow.php?id=<?php echo $book_id; ?>" method="POST">
            <p>By clicking "Confirm Borrow", you agree to return the book within 14 days.</p>
            <button type="submit" class="btn">Confirm Borrow</button>
            <a href="catalog.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>