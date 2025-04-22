<?php
require_once 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "You don't have permission to access this page.";
    redirect('index.php');
    exit;
}

// Handle user search/filter
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$role = isset($_GET['role']) ? sanitizeInput($_GET['role']) : '';

// Get all users
$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
}

if (!empty($role)) {
    $query .= " AND is_admin = ?";
    $params[] = ($role == 'admin') ? 1 : 0;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Reading Club 2000</title>
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
            <li><a href="admin_requests.php"><i class="fas fa-clipboard-list"></i> Borrow Requests</a></li>
            <li><a href="admin_users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="index.php"><i class="fas fa-home"></i> Main Site</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>

    <div class="admin-container">
        <div class="admin-header">
            <h2>Manage Users</h2>
            <button id="addUserBtn" class="btn btn-primary"><i class="fas fa-plus"></i> Add New User</button>
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
        
        <!-- Search and Filter Section -->
        <div class="search-filter">
            <form action="admin_users.php" method="GET">
                <div class="search-bar">
                    <input type="text" name="search" placeholder="Search by name, username or email..." value="<?php echo $search; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
                
                <div class="filter-options">
                    <select name="role">
                        <option value="">All Users</option>
                        <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admins</option>
                        <option value="member" <?php echo ($role == 'member') ? 'selected' : ''; ?>>Members</option>
                    </select>
                    
                    <button type="submit" class="btn filter-btn">Filter</button>
                    <a href="admin_users.php" class="btn reset-btn">Reset</a>
                </div>
            </form>
        </div>
        
        <!-- Users Table -->
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo ($user['is_admin'] ? 'admin' : 'member'); ?>">
                                        <?php echo ($user['is_admin'] ? 'Admin' : 'Member'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td class="action-buttons">
                                    <a href="admin_edit_user.php?id=<?php echo $user['id']; ?>" class="btn-small btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="#" class="btn-small btn-delete" data-id="<?php echo $user['id']; ?>" data-username="<?php echo $user['username']; ?>"><i class="fas fa-trash"></i> Delete</a>
                                    <?php endif; ?>
                                    <a href="#" class="btn-small btn-view" data-id="<?php echo $user['id']; ?>"><i class="fas fa-eye"></i> View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-results">No users found matching your criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New User</h2>
            <form action="process_add_user.php" method="POST">
                <div class="form-group">
                    <label for="username">Username*</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="first_name">First Name*</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    
                    <div class="form-group half">
                        <label for="last_name">Last Name*</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="password">Password*</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group half">
                        <label for="confirm_password">Confirm Password*</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="is_admin">Role</label>
                    <select id="is_admin" name="is_admin">
                        <option value="0">Member</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                
                <div class="form-buttons">
                    <button type="button" class="btn btn-cancel" id="cancelAddUser">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete user "<span id="deleteUsername"></span>"?</p>
            <p class="warning">This action cannot be undone. All associated data will be removed.</p>
            
            <form action="process_delete_user.php" method="POST">
                <input type="hidden" id="delete_user_id" name="user_id">
                <div class="form-buttons">
                    <button type="button" class="btn btn-cancel" id="cancelDelete">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const addUserBtn = document.getElementById('addUserBtn');
        const addUserModal = document.getElementById('addUserModal');
        const deleteModal = document.getElementById('deleteModal');
        const closeButtons = document.getElementsByClassName('close');
        const cancelAddUser = document.getElementById('cancelAddUser');
        const cancelDelete = document.getElementById('cancelDelete');
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        // Open Add User modal
        addUserBtn.onclick = function() {
            addUserModal.style.display = 'block';
        }
        
        // Close modals when clicking X
        for (let i = 0; i < closeButtons.length; i++) {
            closeButtons[i].onclick = function() {
                addUserModal.style.display = 'none';
                deleteModal.style.display = 'none';
            }
        }
        
        // Cancel buttons
        cancelAddUser.onclick = function() {
            addUserModal.style.display = 'none';
        }
        
        cancelDelete.onclick = function() {
            deleteModal.style.display = 'none';
        }
        
        // Delete user confirmation
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                
                document.getElementById('deleteUsername').textContent = username;
                document.getElementById('delete_user_id').value = userId;
                deleteModal.style.display = 'block';
            });
        });
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == addUserModal) {
                addUserModal.style.display = 'none';
            }
            if (event.target == deleteModal) {
                deleteModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>