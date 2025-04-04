<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get user's borrowed books
$stmt = $pdo->prepare("
    SELECT b.*, br.borrow_date, br.due_date, br.return_date, br.status 
    FROM borrows br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = ?
    ORDER BY br.status, br.due_date
");
$stmt->execute([$user_id]);
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books - Reading Club 2000</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>My Borrowed Books</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <p><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (empty($borrowed_books)): ?>
            <p>You haven't borrowed any books yet.</p>
            <br>
            <a href="catalog.php" class="btn">Browse Catalog</a>
        <?php else: ?>
            <div class="book-grid">
                <?php foreach ($borrowed_books as $book): ?>
                    <div class="book-card <?php echo $book['status']; ?>">
                        <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        
                        <div class="book-info">
                            <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                            <p><strong>Borrowed:</strong> <?php echo date('M d, Y', strtotime($book['borrow_date'])); ?></p>
                            <p><strong>Due:</strong> <?php echo date('M d, Y', strtotime($book['due_date'])); ?></p>
                            <p class="status <?php echo $book['status']; ?>"><?php echo ucfirst($book['status']); ?></p>
                            
                            <?php if ($book['status'] === 'approved'): ?>
                                <form action="return_book.php" method="POST" class="return-form">
                                    <input type="hidden" name="borrow_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="btn btn-sm">Return Book</button>
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