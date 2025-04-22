<?php
require_once 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "You don't have permission to access this page.";
    redirect('index.php');
    exit;
}

// Handle request status filter
$status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';

// Get all borrow requests
$query = "SELECT br.*, b.title as book_title, u.username as user_name 
          FROM borrows br
          JOIN books b ON br.book_id = b.id
          JOIN users u ON br.user_id = u.id
          WHERE 1=1";  // This allows dynamic filtering

$params = [];

if (!empty($status)) {
    $query .= " AND br.status = ?";
    $params[] = $status;
}

$query .= " ORDER BY br.borrow_date DESC"; // Ordering by borrow date

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Requests - Reading Club 2000</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="admin-navbar">
        <div class="logo">
            <img src="assets/img/Logo5.png" alt="Reading Club Logo">
            <h1>Admin Dashboard</h1>
        </div>
        <ul class="nav-links">
            <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="admin_books.php"><i class="fas fa-book"></i> Manage Books</a></li>
            <li><a href="admin_requests.php" class="active"><i class="fas fa-clipboard-list"></i> Borrow Requests</a></li>
            <li><a href="admin_users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="index.php"><i class="fas fa-home"></i> Main Site</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="admin-container">
        <div class="admin-header">
            <h2>Manage Borrow Requests</h2>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="search-filter">
            <form action="admin_requests.php" method="GET">
                <div class="filter-options">
                    <select name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo ($status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo ($status == 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo ($status == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                        <option value="returned" <?php echo ($status == 'returned') ? 'selected' : ''; ?>>Returned</option>
                    </select>

                    <button type="submit" class="btn filter-btn">Filter</button>
                    <a href="admin_requests.php" class="btn reset-btn">Reset</a>
                </div>
            </form>
        </div>

        <!-- Requests Table -->
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Book</th>
                        <th>User</th>
                        <th>Request Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($requests) > 0): ?>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><?php echo $request['book_title']; ?></td>
                                <td><?php echo $request['user_name']; ?></td>
                                <td><?php echo ($request['borrow_date']) ? date('M d, Y', strtotime($request['borrow_date'])) : 'No date available'; ?></td>
                                <td><?php echo ($request['due_date']) ? date('M d, Y', strtotime($request['due_date'])) : 'N/A'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($request['status']); ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <?php if ($request['status'] == 'pending'): ?>
                                        <a href="process_borrow.php?action=approve&id=<?php echo $request['id']; ?>" class="btn-small btn-approve"><i class="fas fa-check"></i> Approve</a>
                                        <a href="process_borrow.php?action=reject&id=<?php echo $request['id']; ?>" class="btn-small btn-reject"><i class="fas fa-times"></i> Reject</a>
                                    <?php elseif ($request['status'] == 'approved' && empty($request['return_date'])): ?>
                                        <a href="process_borrow.php?action=return&id=<?php echo $request['id']; ?>" class="btn-small btn-return"><i class="fas fa-undo"></i> Mark Returned</a>
                                    <?php endif; ?>
                                    <a href="#" class="btn-small btn-view" data-id="<?php echo $request['id']; ?>"><i class="fas fa-eye"></i> Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-results">No borrow requests found matching your criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
