<?php
require_once 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "You don't have permission to access this page.";
    redirect('index.php');
    exit;
}

// Handle book search/filter
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';

// Get all categories for filter dropdown
$stmt = $pdo->query("SELECT DISTINCT category FROM books ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Build query based on search/filter
$query = "SELECT * FROM books WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR author LIKE ? OR isbn LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
}

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

$query .= " ORDER BY title ASC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Reading Club 2000</title>
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
            <li><a href="admin_books.php" class="active"><i class="fas fa-book"></i> Manage Books</a></li>
            <li><a href="admin_requests.php"><i class="fas fa-clipboard-list"></i> Borrow Requests</a></li>
            <li><a href="admin_users.php"><i class="fas fa-users"></i> Users</a></li>
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
            <h2>Manage Books</h2>
            <button id="addBookBtn" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Book</button>
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
            <form action="admin_books.php" method="GET">
                <div class="search-bar">
                    <input type="text" name="search" placeholder="Search by title, author, or ISBN..." value="<?php echo $search; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
                
                <div class="filter-options">
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo ($category == $cat) ? 'selected' : ''; ?>>
                                <?php echo $cat; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit" class="btn filter-btn">Filter</button>
                    <a href="admin_books.php" class="btn reset-btn">Reset</a>
                </div>
            </form>
        </div>
        
        <!-- Books Table -->
        <div class="table-responsive">
            <table class="admin-table books-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Total Copies</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($books) > 0): ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($book['cover_image'])): ?>
                                        <img src="assets/img/Logo.png" <?php echo $book['cover_image']; ?> alt="<?php echo $book['title']; ?>" class="book-cover-thumb">
                                    <?php else: ?>
                                        <img src="assets/img/Logo.png" alt="No Cover" class="book-cover-thumb">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $book['title']; ?></td>
                                <td><?php echo $book['author']; ?></td>
                                <td><?php echo $book['category']; ?></td>
                                <td><?php echo $book['available_copies']; ?></td>
                                <td><?php echo $book['available']; ?></td>
                                <td class="action-buttons">
                                    <a href="admin_edit_book.php?id=<?php echo $book['id']; ?>" class="btn-small btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="#" class="btn-small btn-view" data-id="<?php echo $book['id']; ?>"><i class="fas fa-eye"></i> View</a>
                                    <a href="#" class="btn-small btn-delete" data-id="<?php echo $book['id']; ?>" data-title="<?php echo $book['title']; ?>"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-results">No books found matching your criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Add Book Modal -->
    <div id="addBookModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Book</h2>
            <form action="process_add_book.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title*</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="author">Author*</label>
                    <input type="text" id="author" name="author" required>
                </div>
                
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn">
                </div>
                
                <div class="form-group">
                    <label for="category">Category*</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Children's Books">Children's Books</option>
                        <option value="Young Adult">Young Adult</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Educational">Educational</option>
                        <option value="Programming">Programming</option>
                        <option value="Cooking">Cooking</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="total_copies">Total Copies*</label>
                        <input type="number" id="total_copies" name="total_copies" min="1" value="1" required>
                    </div>
                    
                    <div class="form-group half">
                        <label for="available">Available Copies*</label>
                        <input type="number" id="available" name="available" min="0" value="1" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="cover_image">Cover Image</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*">
                    <p class="help-text">Recommended size: 300x450 pixels. Max size: 2MB</p>
                </div>
                
                <div class="form-buttons">
                    <button type="button" class="btn btn-cancel" id="cancelAddBook">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Book</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete "<span id="deleteBookTitle"></span>"?</p>
            <p class="warning">This action cannot be undone.</p>
            
            <form action="process_delete_book.php" method="POST">
                <input type="hidden" id="delete_book_id" name="book_id">
                <div class="form-buttons">
                    <button type="button" class="btn btn-cancel" id="cancelDelete">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Book</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const addBookBtn = document.getElementById('addBookBtn');
        const addBookModal = document.getElementById('addBookModal');
        const deleteModal = document.getElementById('deleteModal');
        const closeButtons = document.getElementsByClassName('close');
        const cancelAddBook = document.getElementById('cancelAddBook');
        const cancelDelete = document.getElementById('cancelDelete');
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        // Open Add Book modal
        addBookBtn.onclick = function() {
            addBookModal.style.display = 'block';
        }
        
        // Close modals when clicking X
        for (let i = 0; i < closeButtons.length; i++) {
            closeButtons[i].onclick = function() {
                addBookModal.style.display = 'none';
                deleteModal.style.display = 'none';
            }
        }
        
        // Cancel buttons
        cancelAddBook.onclick = function() {
            addBookModal.style.display = 'none';
        }
        
        cancelDelete.onclick = function() {
            deleteModal.style.display = 'none';
        }
        
        // Delete book confirmation
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const bookId = this.getAttribute('data-id');
                const bookTitle = this.getAttribute('data-title');
                
                document.getElementById('deleteBookTitle').textContent = bookTitle;
                document.getElementById('delete_book_id').value = bookId;
                deleteModal.style.display = 'block';
            });
        });
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == addBookModal) {
                addBookModal.style.display = 'none';
            }
            if (event.target == deleteModal) {
                deleteModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>