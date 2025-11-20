<?php
// Absolutely NO output before this
ob_start();
session_start();

// Handle AJAX requests FIRST - before any includes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Clear buffer and disable all output
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Disable error display - errors will break JSON
    ini_set('display_errors', '0');
    error_reporting(0);
    
    // Set JSON header
    header('Content-Type: application/json; charset=utf-8');
    
    // Include database connection
    require_once "connect.php";
    
    // Check connection
    if (!isset($conn) || $conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit();
    }
    
    try {
        if ($_POST['action'] === 'create') {
            $name = trim($_POST['name'] ?? '');
            $position = trim($_POST['position'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $contact = trim($_POST['contact'] ?? '');

            if (empty($name) || empty($position) || empty($description) || empty($email) || empty($contact)) {
                echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
                exit();
            }

            // Handle file upload
            $picture = '';
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['picture']['tmp_name']);
                finfo_close($finfo);
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                
                if (!in_array($mimeType, $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
                    exit();
                }
                
                if ($_FILES['picture']['size'] > 5242880) {
                    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB.']);
                    exit();
                }
                
                $uploadDir = 'uploads/officials/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $safe_filename = uniqid('official_', true) . '.' . strtolower($extension);
                $targetFile = $uploadDir . $safe_filename;
                
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFile)) {
                    $picture = $targetFile;
                } else {
                    echo json_encode(['success' => false, 'message' => 'File upload failed.']);
                    exit();
                }
            }

            $query = "INSERT INTO brgyofficials (name, position, description, email, contact, picture) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            
            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            
            $stmt->bind_param("ssssss", $name, $position, $description, $email, $contact, $picture);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Official added successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save: ' . $stmt->error]);
            }
            
            $stmt->close();
            $conn->close();
            exit();
        }
        
        if ($_POST['action'] === 'update') {
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $position = trim($_POST['position'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $contact = trim($_POST['contact'] ?? '');

            if ($id <= 0 || empty($name) || empty($position) || empty($description) || empty($email) || empty($contact)) {
                echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
                exit();
            }

            // Get existing picture
            $existing_picture = '';
            $stmt = $conn->prepare("SELECT picture FROM brgyofficials WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $existing_picture = $row['picture'];
            }
            $stmt->close();

            // Handle new file upload
            $picture = $existing_picture;
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['picture']['tmp_name']);
                finfo_close($finfo);
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                
                if (!in_array($mimeType, $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
                    exit();
                }
                
                if ($_FILES['picture']['size'] > 5242880) {
                    echo json_encode(['success' => false, 'message' => 'File too large.']);
                    exit();
                }
                
                $uploadDir = 'uploads/officials/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $safe_filename = uniqid('official_', true) . '.' . strtolower($extension);
                $targetFile = $uploadDir . $safe_filename;
                
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFile)) {
                    if (!empty($existing_picture) && file_exists($existing_picture)) {
                        @unlink($existing_picture);
                    }
                    $picture = $targetFile;
                }
            }

            $query = "UPDATE brgyofficials SET name=?, position=?, description=?, email=?, contact=?, picture=? WHERE id=?";
            $stmt = $conn->prepare($query);
            
            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            
            $stmt->bind_param("ssssssi", $name, $position, $description, $email, $contact, $picture, $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Official updated successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update: ' . $stmt->error]);
            }
            
            $stmt->close();
            $conn->close();
            exit();
        }
        
        if ($_POST['action'] === 'delete') {
            $id = intval($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
                exit();
            }

            // Get picture path
            $stmt = $conn->prepare("SELECT picture FROM brgyofficials WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $picture = $row['picture'];
                if (!empty($picture) && file_exists($picture)) {
                    @unlink($picture);
                }
            }
            $stmt->close();

            $query = "DELETE FROM brgyofficials WHERE id = ?";
            $stmt = $conn->prepare($query);
            
            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Official deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete: ' . $stmt->error]);
            }
            
            $stmt->close();
            $conn->close();
            exit();
        }
        
        // Unknown action
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        exit();
    }
}

// Normal page load
require_once "connect.php";
require_once "sidebar.php";

// Fetch officials
$officials = [];
if (isset($conn) && !$conn->connect_error) {
    $query = "SELECT id, name, position, description, email, contact, picture FROM brgyofficials ORDER BY id DESC";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $officials[] = $row;
        }
        $result->free();
    }
}

if (isset($conn)) {
    $conn->close();
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Barangay Officials</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="plugins/adminHomepage.css">
</head>
<body>

<div class="home">
    <div class="homepage-wrapper">
        <div class="page-header">
            <div>
                <h1 class="greeting">Barangay Officials</h1>
                <h4>Manage officials information</h4>
            </div>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Add Official
            </button>
        </div>

        <?php if (empty($officials)): ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>No Officials Yet</h3>
                <p>Click "Add Official" to create your first barangay official entry.</p>
            </div>
        <?php else: ?>
            <div class="officials-grid">
                <?php foreach ($officials as $official): ?>
                    <div class="official-card">
                        <div class="official-card-header">
                            <div class="official-avatar">
                                <?php if (!empty($official['picture']) && file_exists($official['picture'])): ?>
                                    <img src="<?php echo htmlspecialchars($official['picture']); ?>" alt="<?php echo htmlspecialchars($official['name']); ?>">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <?php echo strtoupper(substr($official['name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="official-actions">
                                <button class="icon-btn edit-official-btn" onclick='openEditModal(<?php echo json_encode($official); ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="icon-btn delete-official-btn" onclick='openDeleteModal(<?php echo $official["id"]; ?>, "<?php echo htmlspecialchars($official["name"], ENT_QUOTES); ?>")'>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="official-card-body">
                            <span class="official-position"><?php echo htmlspecialchars($official['position']); ?></span>
                            <h3 class="official-name"><?php echo htmlspecialchars($official['name']); ?></h3>
                            <p class="official-description"><?php echo htmlspecialchars($official['description']); ?></p>
                            <div class="official-contact">
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo htmlspecialchars($official['email']); ?></span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($official['contact']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for Add/Edit -->
<div id="officialModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add Official</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="officialForm" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="officialId" name="id">
                <input type="hidden" id="formAction" name="action" value="create">
                
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="position">Position *</label>
                    <input type="text" id="position" name="position" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="contact">Contact Number *</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
                
                <div class="form-group">
                    <label for="picture">Profile Picture</label>
                    <input type="file" id="picture" name="picture" accept="image/jpeg,image/jpg,image/png,image/gif">
                    <small style="color: #666; display: block; margin-top: 5px;">JPG, PNG, or GIF (Max 5MB)</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-primary" id="submitBtn">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header modal-header-danger">
            <h2>Delete Official</h2>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body" style="text-align: center !important;">
            <div class="delete-confirm" style="text-align: center !important; width: 100% !important;">
                <i class="fas fa-exclamation-triangle" style="text-align: center !important; display: block !important; width: 100% !important;"></i>
                <p style="text-align: center !important; width: 100% !important;">Are you sure you want to delete this official?</p>
                <p class="delete-item-name" id="deleteOfficialName" style="text-align: center !important; width: 100% !important; display: block !important;"></p>
                <p class="delete-warning" style="text-align: center !important; width: 100% !important;">This action cannot be undone.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="modal-btn modal-btn-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
let deleteOfficialId = null;

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add Official';
    document.getElementById('formAction').value = 'create';
    document.getElementById('officialForm').reset();
    document.getElementById('officialId').value = '';
    document.getElementById('officialModal').style.display = 'block';
}

function openEditModal(official) {
    document.getElementById('modalTitle').textContent = 'Edit Official';
    document.getElementById('formAction').value = 'update';
    document.getElementById('officialId').value = official.id;
    document.getElementById('name').value = official.name;
    document.getElementById('position').value = official.position;
    document.getElementById('description').value = official.description;
    document.getElementById('email').value = official.email;
    document.getElementById('contact').value = official.contact;
    document.getElementById('officialModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('officialModal').style.display = 'none';
    document.getElementById('officialForm').reset();
}

function openDeleteModal(id, name) {
    deleteOfficialId = id;
    document.getElementById('deleteOfficialName').textContent = name;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    deleteOfficialId = null;
}

function confirmDelete() {
    if (!deleteOfficialId) return;
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', deleteOfficialId);
    
    fetch('adminOfficials.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                showToast(data.message, 'success');
                closeDeleteModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        } catch (e) {
            console.error('Server response:', text);
            showToast('Server error. Check console for details.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error occurred.', 'error');
    });
}

document.getElementById('officialForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    const formData = new FormData(this);
    
    fetch('adminOfficials.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                showToast(data.message, 'success');
                closeModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Operation failed', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save';
            }
        } catch (e) {
            console.error('Server response:', text);
            showToast('Server error. Check console for details.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error occurred.', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save';
    });
});

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast show ' + (type === 'error' ? 'error' : '');
    setTimeout(() => {
        toast.className = 'toast';
    }, 3000);
}

window.onclick = function(event) {
    const officialModal = document.getElementById('officialModal');
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === officialModal) {
        closeModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>


</body>
</html>