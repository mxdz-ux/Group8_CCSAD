<?php
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure the user is an admin when processing approval/rejection
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = sanitizeInput($_GET['action']);
    $borrow_id = (int) $_GET['id']; // Ensure the ID is an integer

    if ($_SESSION['is_admin'] != 1) {
        $_SESSION['error'] = "You do not have permission to perform this action.";
        header("Location: admin_requests.php");
        exit;
    }

    try {
        $pdo->beginTransaction();

        switch ($action) {
            case 'approve':
                // Update the borrow request status to approved
                $stmt = $pdo->prepare("UPDATE borrows SET status = 'approved' WHERE id = ?");
                $stmt->execute([$borrow_id]);
                break;

            case 'reject':
                // Update the borrow request status to rejected
                $stmt = $pdo->prepare("UPDATE borrows SET status = 'rejected' WHERE id = ?");
                $stmt->execute([$borrow_id]);
                break;

            case 'return':
                // Mark the book as returned
                $return_date = date('Y-m-d'); // Get the current date as the return date
                $stmt = $pdo->prepare("UPDATE borrows SET status = 'returned', return_date = ? WHERE id = ?");
                $stmt->execute([$return_date, $borrow_id]);
                break;

            default:
                throw new Exception("Invalid action.");
        }

        // Commit the transaction if successful
        $pdo->commit();
        $_SESSION['success'] = ucfirst($action) . " action was successful!";
        header("Location: admin_requests.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error processing request: " . $e->getMessage();
        header("Location: admin_requests.php");
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle borrow request submission for users (normal users)
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];

    try {
        $pdo->beginTransaction();

        // Check if the user has already requested the same book and the request is pending or approved
        $check = $pdo->prepare("SELECT COUNT(*) FROM borrows WHERE user_id = ? AND book_id = ? AND status IN ('pending', 'approved')");
        $check->execute([$user_id, $book_id]);
        if ($check->fetchColumn() > 0) {
            $_SESSION['error'] = "You already have a request for this book.";
            $pdo->rollBack();
            header("Location: catalog.php");
            exit;
        }

        // Create a borrow request with status "pending"
        $stmt = $pdo->prepare("
            INSERT INTO borrows(user_id, book_id, borrow_date, status)
            VALUES (?, ?, NOW(), 'pending')
        ");
        $stmt->execute([$user_id, $book_id]);

        $pdo->commit();
        $_SESSION['success'] = "Borrow request submitted! Please wait for admin approval.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error submitting request: " . $e->getMessage();
    }

    header("Location: catalog.php");
    exit;
}
?>
