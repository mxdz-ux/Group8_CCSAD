<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's borrowed books with proper error handling
try {
    $stmt = $pdo->prepare("
        SELECT b.id as book_id, b.title, b.author, b.cover_image, 
               br.id as borrow_id, br.borrow_date, br.due_date, 
               br.return_date, br.status 
        FROM borrows br
        JOIN books b ON br.book_id = b.id
        WHERE br.user_id = ?
        ORDER BY 
            CASE WHEN br.status = 'approved' THEN 0 
                 WHEN br.status = 'pending' THEN 1
                 ELSE 2 END,
            br.due_date
    ");
    $stmt->execute([$user_id]);
    $borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $borrowed_books = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Books</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/mybooks.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>My Borrowed Books</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($borrowed_books)): ?>
            <div class="empty-state">
                <p>You haven't borrowed any books yet.</p>
                <a href="catalog.php" class="btn">Browse Catalog</a>
            </div>
        <?php else: ?>
            <div class="book-grid">
                <?php foreach ($borrowed_books as $book): ?>
                    <div class="book-card">
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($book['title']); ?>" 
                                 class="book-cover">
                        <?php endif; ?>
                        
                        <div class="book-details">
                            <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                            <p><strong>Status:</strong> 
                                <span class="status-<?php echo $book['status']; ?>">
                                    <?php echo ucfirst($book['status']); ?>
                                </span>
                            </p>
                            <p><strong>Due Date:</strong> <?php echo date('M j, Y', strtotime($book['due_date'])); ?></p>
                            
                            <?php if ($book['status'] === 'approved'): ?>
                                <form action="return_book.php" method="post">
                                    <input type="hidden" name="borrow_id" value="<?php echo $book['borrow_id']; ?>">
                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                    <button type="submit" class="btn return-btn">Return Book</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>