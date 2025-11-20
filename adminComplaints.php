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
        if ($_POST['action'] === 'update_status') {
            $id = intval($_POST['id'] ?? 0);
            $status = strtolower(trim($_POST['status'] ?? '')); // Force lowercase
            
            if ($id <= 0 || empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
                exit();
            }
            
            // Validate status values match database ENUM
            if (!in_array($status, ['pending', 'processing', 'resolved'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
                exit();
            }
            
            $query = "UPDATE complaints SET status = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            
            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            
            $stmt->bind_param("si", $status, $id);
            
            if ($stmt->execute()) {
                // Verify the update was successful
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Status updated successfully!', 
                        'new_status' => $status
                    ]);
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'No changes made. Status may already be set to this value.'
                    ]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update: ' . $stmt->error]);
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

// Get filter parameters
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build the SQL query - Use COALESCE to handle NULL status values
$sql = "SELECT id, user_id, name, email, contact, date, issue, location, message, picture, 
        COALESCE(status, 'pending') as status, date_requested FROM complaints WHERE 1=1";
$params = [];
$types = "";

if ($statusFilter !== 'all') {
    $sql .= " AND COALESCE(status, 'pending') = ?";
    $params[] = $statusFilter;
    $types .= "s";
}

if ($searchQuery !== '') {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR issue LIKE ? OR location LIKE ?)";
    $searchParam = "%{$searchQuery}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ssss";
}

$sql .= " ORDER BY date_requested DESC, id DESC";

// Execute query
$complaints = [];
if (isset($conn) && !$conn->connect_error) {
    if ($stmt = $conn->prepare($sql)) {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $complaints = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

// Get statistics - Handle NULL status values
$stats = [
    'total' => 0,
    'pending' => 0,
    'processing' => 0,
    'resolved' => 0
];

if (isset($conn) && !$conn->connect_error) {
    $statsQuery = "SELECT COALESCE(status, 'pending') as status, COUNT(*) as count FROM complaints GROUP BY COALESCE(status, 'pending')";
    $statsResult = $conn->query($statsQuery);
    if ($statsResult) {
        while ($row = $statsResult->fetch_assoc()) {
            $stats[$row['status']] = $row['count'];
            $stats['total'] += $row['count'];
        }
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Complaints - Admin</title>
    <link rel="icon" href="website_icon/webicon.png" type="image/png" sizes="64x64">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="plugins/adminHomepage.css">
    <style>
        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .stat-card.pending { border-left-color: #f59e0b; }
        .stat-card.processing { border-left-color: #3b82f6; }
        .stat-card.resolved { border-left-color: #10b981; }

        .stat-card h3 {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Filters */
        .filters-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
            padding: 0.625rem 1.25rem;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            color: #6b7280;
            text-decoration: none;
        }

        .filter-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .filter-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        /* Table */
        .complaints-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
        }

        .complaints-table thead {
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }

        .complaints-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .complaints-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.875rem;
        }

        .complaints-table tbody tr:hover {
            background: #f9fafb;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.resolved {
            background: #d1fae5;
            color: #065f46;
        }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .icon-btn {
            padding: 0.5rem;
            border: none;
            background: #f3f4f6;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            color: #6b7280;
        }

        .icon-btn:hover {
            background: #3b82f6;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #9ca3af;
        }

        /* Status Update Form in Modal Footer */
        .status-update-form {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-right: auto;
        }

        .status-update-form label {
            font-weight: 500;
            color: #374151;
            font-size: 0.875rem;
        }

        .status-update-form select {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
            color: #374151;
            cursor: pointer;
            min-width: 150px;
        }

        .status-update-form select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Attachment Preview */
        .attachment-preview {
            margin-top: 0.5rem;
            border: 2px dashed #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }

        .attachment-preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
        }

        .attachment-preview a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .attachment-preview a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .complaints-table-container {
                overflow-x: auto;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .modal-footer {
                flex-direction: column;
            }

            .status-update-form {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>

<div class="home">
    <div class="homepage-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="greeting">Complaints Management</h1>
                <h4>View and manage resident complaints</h4>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Complaints</h3>
                <div class="stat-value"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card pending">
                <h3>Pending</h3>
                <div class="stat-value"><?php echo $stats['pending']; ?></div>
            </div>
            <div class="stat-card processing">
                <h3>Processing</h3>
                <div class="stat-value"><?php echo $stats['processing']; ?></div>
            </div>
            <div class="stat-card resolved">
                <h3>Resolved</h3>
                <div class="stat-value"><?php echo $stats['resolved']; ?></div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="filters-bar">
            <div class="filter-group">
                <a href="?status=all&search=<?php echo urlencode($searchQuery); ?>" 
                   class="filter-btn <?php echo $statusFilter === 'all' ? 'active' : ''; ?>">
                    All
                </a>
                <a href="?status=pending&search=<?php echo urlencode($searchQuery); ?>" 
                   class="filter-btn <?php echo $statusFilter === 'pending' ? 'active' : ''; ?>">
                    Pending
                </a>
                <a href="?status=processing&search=<?php echo urlencode($searchQuery); ?>" 
                   class="filter-btn <?php echo $statusFilter === 'processing' ? 'active' : ''; ?>">
                    Processing
                </a>
                <a href="?status=resolved&search=<?php echo urlencode($searchQuery); ?>" 
                   class="filter-btn <?php echo $statusFilter === 'resolved' ? 'active' : ''; ?>">
                    Resolved
                </a>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <form method="GET" action="">
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($statusFilter); ?>">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by name, email, issue, or location..."
                        value="<?php echo htmlspecialchars($searchQuery); ?>"
                    />
                </form>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="complaints-table-container">
            <?php if (count($complaints) > 0): ?>
            <table class="complaints-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Issue</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($complaints as $complaint): ?>
                    <tr data-complaint-id="<?php echo $complaint['id']; ?>">
                        <td><?php echo date('M d, Y', strtotime($complaint['date'])); ?></td>
                        <td><?php echo htmlspecialchars($complaint['name']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['contact']); ?></td>
                        <td><?php echo htmlspecialchars(substr($complaint['issue'], 0, 50)) . (strlen($complaint['issue']) > 50 ? '...' : ''); ?></td>
                        <td><?php echo htmlspecialchars($complaint['location']); ?></td>
                        <td>
                            <span class="status-badge <?php echo $complaint['status']; ?>">
                                <?php if ($complaint['status'] === 'pending'): ?>
                                    <i class="fas fa-clock"></i>
                                <?php elseif ($complaint['status'] === 'processing'): ?>
                                    <i class="fas fa-spinner"></i>
                                <?php else: ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php endif; ?>
                                <?php echo ucfirst($complaint['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="icon-btn" onclick='openViewModal(<?php echo json_encode($complaint); ?>)' title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No complaints found</h3>
                <p>
                    <?php if ($statusFilter !== 'all' || $searchQuery !== ''): ?>
                        Try adjusting your filters or search terms.
                    <?php else: ?>
                        No complaints have been submitted yet.
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- View Complaint Modal -->
<div id="complaintModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Complaint Details</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="complaintForm">
            <div class="modal-body" id="modalBody">
                <!-- Content will be inserted by JavaScript -->
            </div>
            <div class="modal-footer">
                <input type="hidden" id="complaintId" name="id">
                <input type="hidden" name="action" value="update_status">
                
                <div class="status-update-form">
                    <label for="complaintStatus">Update Status:</label>
                    <select name="status" id="complaintStatus" required>
                        <option value="processing">Processing</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
                
                <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-primary" id="submitBtn">Update Status</button>
            </div>
        </form>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
function openViewModal(complaint) {
    const modal = document.getElementById('complaintModal');
    const modalBody = document.getElementById('modalBody');
    const complaintId = document.getElementById('complaintId');
    const complaintStatus = document.getElementById('complaintStatus');
    
    // Set complaint ID
    complaintId.value = complaint.id;
    
    // Set current status in dropdown
    complaintStatus.value = complaint.status;
    
    // Build modal content
    let attachmentHtml = '';
    if (complaint.picture) {
        const extension = complaint.picture.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
            attachmentHtml = `
                <div class="form-group">
                    <label>Attachment</label>
                    <div class="attachment-preview">
                        <img src="${complaint.picture}" alt="Complaint attachment">
                    </div>
                </div>
            `;
        } else {
            attachmentHtml = `
                <div class="form-group">
                    <label>Attachment</label>
                    <div class="attachment-preview">
                        <i class="fas fa-file" style="font-size: 2rem; color: #9ca3af;"></i>
                        <br><br>
                        <a href="${complaint.picture}" target="_blank">
                            <i class="fas fa-download"></i> Download Document
                        </a>
                    </div>
                </div>
            `;
        }
    }
    
    const formattedDate = new Date(complaint.date).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // Get status badge HTML
    let statusBadgeClass = 'pending';
    let statusIcon = '<i class="fas fa-clock"></i>';
    let statusText = 'Pending';
    
    if (complaint.status === 'processing') {
        statusBadgeClass = 'processing';
        statusIcon = '<i class="fas fa-spinner"></i>';
        statusText = 'Processing';
    } else if (complaint.status === 'resolved') {
        statusBadgeClass = 'resolved';
        statusIcon = '<i class="fas fa-check-circle"></i>';
        statusText = 'Resolved';
    }
    
    modalBody.innerHTML = `
        <div class="form-group">
            <label>Current Status</label>
            <div>
                <span class="status-badge ${statusBadgeClass}">
                    ${statusIcon} ${statusText}
                </span>
            </div>
        </div>
        
        <div class="form-group">
            <label>Date of Incident</label>
            <input type="text" value="${formattedDate}" readonly>
        </div>
        
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" value="${complaint.name}" readonly>
        </div>
        
        <div class="form-group">
            <label>Email Address</label>
            <input type="text" value="${complaint.email}" readonly>
        </div>
        
        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" value="${complaint.contact}" readonly>
        </div>
        
        <div class="form-group">
            <label>Issue Summary</label>
            <input type="text" value="${complaint.issue}" readonly>
        </div>
        
        <div class="form-group">
            <label>Location of Incident</label>
            <input type="text" value="${complaint.location}" readonly>
        </div>
        
        <div class="form-group">
            <label>Detailed Description</label>
            <textarea rows="5" readonly style="resize: vertical;">${complaint.message}</textarea>
        </div>
        
        ${attachmentHtml}
    `;
    
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('complaintModal').style.display = 'none';
}

document.getElementById('complaintForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    const formData = new FormData(this);
    const complaintId = formData.get('id');
    const newStatus = formData.get('status');
    
    // Validate status is selected
    if (!newStatus) {
        showToast('Please select a status', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        return;
    }
    
    fetch('adminComplaints.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log('Server response:', text); // Debug log
        try {
            const data = JSON.parse(text);
            if (data.success) {
                showToast(data.message, 'success');
                
                // Update the table row immediately
                updateTableRow(complaintId, newStatus);
                
                closeModal();
                
                // Reload after 1 second to update statistics
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Operation failed', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        } catch (e) {
            console.error('JSON Parse Error:', e);
            console.error('Server response:', text);
            showToast('Server error. Check console for details.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error occurred.', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

function updateTableRow(complaintId, newStatus) {
    // Find the row by data attribute
    const row = document.querySelector(`tr[data-complaint-id="${complaintId}"]`);
    if (!row) return;
    
    // Find and update the status badge
    const statusBadge = row.querySelector('.status-badge');
    if (!statusBadge) return;
    
    // Remove old status class
    statusBadge.classList.remove('pending', 'processing', 'resolved');
    // Add new status class
    statusBadge.classList.add(newStatus);
    
    // Update icon and text
    let icon = '';
    if (newStatus === 'pending') {
        icon = '<i class="fas fa-clock"></i>';
    } else if (newStatus === 'processing') {
        icon = '<i class="fas fa-spinner"></i>';
    } else if (newStatus === 'resolved') {
        icon = '<i class="fas fa-check-circle"></i>';
    }
    
    const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    statusBadge.innerHTML = icon + ' ' + statusText;
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast show ' + (type === 'error' ? 'error' : '');
    setTimeout(() => {
        toast.className = 'toast';
    }, 3000);
}

// Auto-submit search on input (with debounce)
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-box input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('complaintModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

</body>
</html>