<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $due_date = date('Y-m-d', strtotime('+14 days')); // 2 weeks from now
    
    try {
        $pdo->beginTransaction();
        
        // Check availability
        $stmt = $pdo->prepare("SELECT available FROM books WHERE id = ? FOR UPDATE");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();
        
        if ($book && $book['available'] > 0) {
            // Create borrow record
            $stmt = $pdo->prepare("
                INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status)
                VALUES (?, ?, NOW(), ?, 'borrowed')
            ");
            $stmt->execute([$user_id, $book_id, $due_date]);
            
            // Update availability
            $stmt = $pdo->prepare("UPDATE books SET available = available - 1 WHERE id = ?");
            $stmt->execute([$book_id]);
            
            $pdo->commit();
            $_SESSION['success'] = "Book borrowed successfully! Due date: " . date('M j, Y', strtotime($due_date));
        } else {
            $_SESSION['error'] = "Book is no longer available";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error borrowing book: " . $e->getMessage();
    }
    
    header("Location: catalog.php");
    exit;
}
?>