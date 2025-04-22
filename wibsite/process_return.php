<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Update borrow record
        $stmt = $pdo->prepare("
            UPDATE borrows 
            SET return_date = NOW(), status = 'returned' 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$_POST['borrow_id'], $_SESSION['user_id']]);
        
        // Update book availability
        $stmt = $pdo->prepare("UPDATE books SET available = available + 1 WHERE id = ?");
        $stmt->execute([$_POST['book_id']]);
        
        $pdo->commit();
        $_SESSION['success'] = "Book returned successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error returning book";
    }
    
    header("Location: my_books.php");
    exit;
}
?>