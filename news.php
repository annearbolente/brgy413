<?php
ob_start();
session_start();
include("connect.php");
include_once 'sidebar.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'secretary') {
    header("Location: RegLog.php");
    exit();
}

// Get user's full name
$userFullName = 'Secretary';
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT firstName, lastName FROM users WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($query)) {
        $userFullName = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
    }
}

// Handle Create News
if (isset($_POST['create_news'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $posted_by = mysqli_real_escape_string($conn, $_POST['posted_by']);

    $sql = "INSERT INTO news_updates (title, content, posted_by) VALUES ('$title', '$content', '$posted_by')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "News posted successfully!";
    } else {
        $_SESSION['error'] = "Error posting news: " . mysqli_error($conn);
    }
    header("Location: news.php");
    exit();
}

// Handle Update News
if (isset($_POST['update_news'])) {
    $id = mysqli_real_escape_string($conn, $_POST['news_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $posted_by = mysqli_real_escape_string($conn, $_POST['posted_by']);

    $sql = "UPDATE news_updates SET title='$title', content='$content', posted_by='$posted_by' WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "News updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating news: " . mysqli_error($conn);
    }
    header("Location: news.php");
    exit();
}

// Handle Delete News
if (isset($_POST['delete_news'])) {
    $id = mysqli_real_escape_string($conn, $_POST['news_id']);
    
    $sql = "DELETE FROM news_updates WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "News deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting news: " . mysqli_error($conn);
    }
    header("Location: news.php");
    exit();
}

// Fetch all news
$newsQuery = "SELECT * FROM news_updates ORDER BY date_posted DESC";
$newsResult = mysqli_query($conn, $newsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>News & Updates Management</title>
    <link rel="stylesheet" href="plugins/adminHomepage.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
<div class="home">
    <div class="homepage-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <p class="greeting">News & Updates</p>
                <h4>Manage and publish news for your community</h4>
            </div>
            <button type="button" class="btn btn-primary" id="createNewsBtn">
                <i class='bx bx-plus-circle'></i> Create News
            </button>
        </div>

        <!-- News List -->
        <div class="news-list-container">
            <?php if (mysqli_num_rows($newsResult) > 0): ?>
                <div class="news-grid">
                    <?php while ($news = mysqli_fetch_assoc($newsResult)): ?>
                        <div class="news-card">
                            <div class="news-card-header">
                                <h3 class="news-title"><?php echo htmlspecialchars($news['title']); ?></h3>
                                <div class="news-actions">
                                    <button type="button" 
                                            class="icon-btn edit-news-btn" 
                                            data-id="<?php echo htmlspecialchars($news['id']); ?>"
                                            data-title="<?php echo htmlspecialchars($news['title']); ?>"
                                            data-content="<?php echo htmlspecialchars($news['content']); ?>"
                                            data-postedby="<?php echo htmlspecialchars($news['posted_by']); ?>"
                                            title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button type="button" 
                                            class="icon-btn delete-news-btn" 
                                            data-id="<?php echo htmlspecialchars($news['id']); ?>"
                                            data-title="<?php echo htmlspecialchars($news['title']); ?>"
                                            title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </div>
                            <div class="news-card-body">
                                <p class="news-content"><?php echo nl2br(htmlspecialchars($news['content'])); ?></p>
                            </div>
                            <div class="news-card-footer">
                                <div class="news-meta">
                                    <i class='bx bx-user'></i>
                                    <span><?php echo htmlspecialchars($news['posted_by']); ?></span>
                                </div>
                                <div class="news-meta">
                                    <i class='bx bx-calendar'></i>
                                    <span><?php echo date("M d, Y", strtotime($news['date_posted'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class='bx bx-news'></i>
                    <h3>No news posted yet</h3>
                    <p>Create your first news article to get started</p>
                
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Create/Edit News Modal -->
<div id="newsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Create News</h2>
            <span class="close" id="closeModalBtn">&times;</span>
        </div>
        <form method="POST" action="" id="newsForm">
            <div class="modal-body">
                <input type="hidden" name="news_id" id="news_id">
                
                <div class="form-group">
                    <label for="news_title">News Title *</label>
                    <input type="text" name="title" id="news_title" placeholder="Enter a compelling title" required>
                </div>
                
                <div class="form-group">
                    <label for="news_content">Content *</label>
                    <textarea name="content" id="news_content" rows="8" placeholder="Write your news content here..." required></textarea>
                    <small class="char-count"><span id="charCount">0</span> characters</small>
                </div>
                
                <div class="form-group">
                    <label for="news_posted_by">Posted By</label>
                    <input type="text" name="posted_by" id="news_posted_by" value="<?php echo $userFullName; ?>" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                <button type="submit" name="create_news" id="submitBtn" class="btn btn-primary">
                    <i class='bx bx-check'></i> Post News
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header modal-header-danger">
            <h2>Delete News</h2>
            <span class="close" id="closeDeleteModalBtn">&times;</span>
        </div>
        <form method="POST" action="">
            <div class="modal-body">
                <div class="delete-confirm">
                    <i class='bx bx-error-circle'></i>
                    <p>Are you sure you want to delete this news?</p>
                    <p class="delete-item-name" id="delete_news_title"></p>
                    <p class="delete-warning">This action cannot be undone!</p>
                </div>
                <input type="hidden" name="news_id" id="delete_news_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
                <button type="submit" name="delete_news" class="btn btn-danger">
                    <i class='bx bx-trash'></i> Delete News
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast">
    <i class='bx bx-check-circle'></i>
    <span id="toastMessage"></span>
</div>

<script>
// INLINE JAVASCRIPT - Everything in one place
console.log('News script starting...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Get elements
    var newsModal = document.getElementById('newsModal');
    var deleteModal = document.getElementById('deleteModal');
    var createNewsBtn = document.getElementById('createNewsBtn');
    var createNewsBtn2 = document.getElementById('createNewsBtn2');
    var closeModalBtn = document.getElementById('closeModalBtn');
    var cancelBtn = document.getElementById('cancelBtn');
    var closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
    var cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    var newsForm = document.getElementById('newsForm');
    var modalTitle = document.getElementById('modalTitle');
    var submitBtn = document.getElementById('submitBtn');
    var newsContent = document.getElementById('news_content');
    var charCount = document.getElementById('charCount');
    
    console.log('Elements loaded:', {
        newsModal: !!newsModal,
        createNewsBtn: !!createNewsBtn
    });
    
    // Create News Button Click
    if (createNewsBtn) {
        createNewsBtn.onclick = function() {
            console.log('Create button clicked!');
            openCreateModal();
        };
    }
    
    if (createNewsBtn2) {
        createNewsBtn2.onclick = function() {
            console.log('Create button 2 clicked!');
            openCreateModal();
        };
    }
    
    // Close buttons
    if (closeModalBtn) closeModalBtn.onclick = closeNewsModal;
    if (cancelBtn) cancelBtn.onclick = closeNewsModal;
    if (closeDeleteModalBtn) closeDeleteModalBtn.onclick = closeDeleteModal;
    if (cancelDeleteBtn) cancelDeleteBtn.onclick = closeDeleteModal;
    
    // Edit buttons
    var editButtons = document.querySelectorAll('.edit-news-btn');
    console.log('Edit buttons found:', editButtons.length);
    editButtons.forEach(function(btn) {
        btn.onclick = function() {
            var id = this.getAttribute('data-id');
            var title = this.getAttribute('data-title');
            var content = this.getAttribute('data-content');
            var postedBy = this.getAttribute('data-postedby');
            console.log('Edit clicked:', id);
            openEditModal(id, title, content, postedBy);
        };
    });
    
    // Delete buttons
    var deleteButtons = document.querySelectorAll('.delete-news-btn');
    console.log('Delete buttons found:', deleteButtons.length);
    deleteButtons.forEach(function(btn) {
        btn.onclick = function() {
            var id = this.getAttribute('data-id');
            var title = this.getAttribute('data-title');
            console.log('Delete clicked:', id);
            openDeleteModal(id, title);
        };
    });
    
    // Character counter
    if (newsContent && charCount) {
        newsContent.oninput = function() {
            var count = newsContent.value.length;
            charCount.textContent = count;
            if (count > 500) {
                charCount.style.color = '#28a745';
            } else if (count > 200) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#999';
            }
        };
    }
    
    // Click outside to close
    window.onclick = function(event) {
        if (event.target === newsModal) closeNewsModal();
        if (event.target === deleteModal) closeDeleteModal();
    };
    
    // ESC key to close
    document.onkeydown = function(event) {
        if (event.key === 'Escape') {
            if (newsModal && newsModal.style.display === 'block') closeNewsModal();
            if (deleteModal && deleteModal.style.display === 'block') closeDeleteModal();
        }
    };
    
    // Form validation
    if (newsForm) {
        newsForm.onsubmit = function(e) {
            var title = document.getElementById('news_title').value.trim();
            var content = document.getElementById('news_content').value.trim();
            
            if (!title || !content) {
                e.preventDefault();
                showToast('Please fill in all required fields', 'error');
                return false;
            }
            if (title.length < 5) {
                e.preventDefault();
                showToast('Title must be at least 5 characters', 'error');
                return false;
            }
            if (content.length < 20) {
                e.preventDefault();
                showToast('Content must be at least 20 characters', 'error');
                return false;
            }
        };
    }
    
    // PHP toast
    <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        showToast('<?php echo isset($_SESSION['success']) ? $_SESSION['success'] : $_SESSION['error']; ?>', '<?php echo isset($_SESSION['success']) ? 'success' : 'error'; ?>');
        <?php unset($_SESSION['success']); unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    console.log('Script ready!');
});

// Functions
function openCreateModal() {
    console.log('openCreateModal');
    var newsModal = document.getElementById('newsModal');
    var modalTitle = document.getElementById('modalTitle');
    var submitBtn = document.getElementById('submitBtn');
    var newsForm = document.getElementById('newsForm');
    
    modalTitle.textContent = 'Create News';
    submitBtn.innerHTML = '<i class="bx bx-check"></i> Post News';
    submitBtn.name = 'create_news';
    newsForm.reset();
    document.getElementById('news_id').value = '';
    newsModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function openEditModal(id, title, content, postedBy) {
    console.log('openEditModal:', id);
    var newsModal = document.getElementById('newsModal');
    var modalTitle = document.getElementById('modalTitle');
    var submitBtn = document.getElementById('submitBtn');
    
    modalTitle.textContent = 'Edit News';
    submitBtn.innerHTML = '<i class="bx bx-check"></i> Update News';
    submitBtn.name = 'update_news';
    document.getElementById('news_id').value = id;
    document.getElementById('news_title').value = title;
    document.getElementById('news_content').value = content;
    document.getElementById('news_posted_by').value = postedBy;
    
    var charCount = document.getElementById('charCount');
    if (charCount) charCount.textContent = content.length;
    
    newsModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeNewsModal() {
    console.log('closeNewsModal');
    var newsModal = document.getElementById('newsModal');
    var newsForm = document.getElementById('newsForm');
    newsModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    newsForm.reset();
}

function openDeleteModal(newsId, newsTitle) {
    console.log('openDeleteModal:', newsId);
    var deleteModal = document.getElementById('deleteModal');
    document.getElementById('delete_news_id').value = newsId;
    document.getElementById('delete_news_title').textContent = newsTitle;
    deleteModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    console.log('closeDeleteModal');
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showToast(message, type) {
    console.log('showToast:', message, type);
    var toast = document.getElementById('toast');
    var toastMessage = document.getElementById('toastMessage');
    var toastIcon = toast.querySelector('i');
    
    toastMessage.textContent = message;
    
    if (type === 'error') {
        toast.classList.remove('success');
        toast.classList.add('error');
        toastIcon.className = 'bx bx-error-circle';
    } else {
        toast.classList.remove('error');
        toast.classList.add('success');
        toastIcon.className = 'bx bx-check-circle';
    }
    
    toast.classList.add('show');
    setTimeout(function() {
        toast.classList.remove('show');
        toast.classList.remove('success');
        toast.classList.remove('error');
    }, 3000);
}
</script>

</body>
</html>