<?php
// borrow.php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'] ?? 'catalog.php';
    header('Location: login.php');
    exit;
}

// Get book_id from POST or GET
$book_id = $_POST['book_id'] ?? $_GET['book_id'] ?? null;

if (!$book_id || !is_numeric($book_id)) {
    $_SESSION['error'] = 'No valid book selected.';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'catalog.php'));
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if book exists and is available (with transaction)
try {
    $pdo->beginTransaction();

    // Check book availability with row lock
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ? AND available > 0 FOR UPDATE");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        $_SESSION['error'] = 'Book is not available for borrowing.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'catalog.php'));
        exit;
    }

    // Check if user already has this book
    $stmt = $pdo->prepare("SELECT id FROM borrows WHERE user_id = ? AND book_id = ? AND status IN ('pending', 'approved')");
    $stmt->execute([$user_id, $book_id]);
    
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'You already have this book in your collection.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'catalog.php'));
        exit;
    }

    // Set borrow details
    $borrow_date = date('Y-m-d H:i:s');
    $due_date = date('Y-m-d H:i:s', strtotime('+14 days'));
    $status = 'pending';

    // Insert borrow record
    $stmt = $pdo->prepare("INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $borrow_date, $due_date, $status]);

    // Update book available copies
    $stmt = $pdo->prepare("UPDATE books SET available = available - 1 WHERE id = ?");
    $stmt->execute([$book_id]);

    $pdo->commit();

    $_SESSION['success'] = 'Book "' . htmlspecialchars($book['title']) . '" has been added to your collection! Due by ' . date('M j, Y', strtotime($due_date));
    header('Location: my_books.php');
    
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'catalog.php'));
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'catalog.php'));
}
exit;
?>