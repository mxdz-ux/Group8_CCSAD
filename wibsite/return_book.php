<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if borrow_id is provided
if (!isset($_POST['borrow_id'])) {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: my_books.php');
    exit;
}

$borrow_id = $_POST['borrow_id'];
$user_id = $_SESSION['user_id'];

// Verify the borrow record belongs to the user
$stmt = $pdo->prepare("SELECT * FROM borrows br JOIN books b ON br.book_id = b.id WHERE br.id = ? AND br.user_id = ?");
$stmt->execute([$borrow_id, $user_id]);
$borrow = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$borrow) {
    $_SESSION['error'] = 'Book not found in your collection.';
    header('Location: my_books.php');
    exit;
}

// Update the borrow record
$return_date = date('Y-m-d');
$stmt = $pdo->prepare("UPDATE borrows SET status = 'returned', return_date = ? WHERE id = ?");
$result = $stmt->execute([$return_date, $borrow_id]);

if ($result) {
    // Update book available copies
    $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
    $stmt->execute([$borrow['book_id']]);
    
    $_SESSION['success'] = 'Book has been returned successfully.';
} else {
    $_SESSION['error'] = 'Failed to return book. Please try again.';
}

header('Location: my_books.php');
exit;
?>