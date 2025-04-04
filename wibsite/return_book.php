<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = (int)$_POST['borrow_id'];
    $return_date = date('Y-m-d');
    
    try {
        $pdo->beginTransaction();
        
        // Get borrow details
        $stmt = $pdo->prepare("SELECT * FROM borrows WHERE id = ? AND user_id = ?");
        $stmt->execute([$borrow_id, $_SESSION['user_id']]);
        $borrow = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$borrow) {
            throw new Exception("Borrow record not found");
        }
        
        // Update borrow record
        $stmt = $pdo->prepare("UPDATE borrows SET return_date = ?, status = 'returned' WHERE id = ?");
        $stmt->execute([$return_date, $borrow_id]);
        
        // Update book availability
        $stmt = $pdo->prepare("UPDATE books SET available = available + 1 WHERE id = ?");
        $stmt->execute([$borrow['book_id']]);
        
        // Create notification
        $book_stmt = $pdo->prepare("SELECT title FROM books WHERE id = ?");
        $book_stmt->execute([$borrow['book_id']]);
        $book = $book_stmt->fetch(PDO::FETCH_ASSOC);
        
        $message = "You have returned '{$book['title']}' on $return_date. Thank you!";
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $message]);
        
        $pdo->commit();
        
        // Send email notification
        $user_stmt = $pdo->prepare("SELECT email, first_name FROM users WHERE id = ?");
        $user_stmt->execute([$_SESSION['user_id']]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
        
        $subject = "Book Returned: {$book['title']}";
        $email_message = "Dear {$user['first_name']},\n\n";
        $email_message .= "You have successfully returned '{$book['title']}' to Reading Club 2000.\n";
        $email_message .= "Return Date: $return_date\n\n";
        $email_message .= "We hope you enjoyed reading the book. You can borrow more books from our collection.\n\n";
        $email_message .= "The Reading Club 2000 Team";
        
        sendEmail($user['email'], $subject, $email_message);
        
        $_SESSION['success'] = "Book returned successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "An error occurred while processing your request";
    }
}

redirect('my_books.php');
?>