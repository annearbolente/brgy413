<?php
ob_start();
session_start();
include("connect.php");
include_once 'sidebar.php';

if (!isset($_SESSION['email'])) {
    header("Location: RegLog.php");
    exit();
}


// Handle Delete User
if (isset($_POST['delete_user'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);
    $deleteQuery = "DELETE FROM users WHERE id = '$userId'";

    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting user: " . mysqli_error($conn);
    }

    header("Location: adminHomepage.php");
    exit();
}

// Handle Update User
if (isset($_POST['update_user'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $updateQuery = "UPDATE users SET
                    firstName = '$firstName',
                    lastName = '$lastName',
                    email = '$email',
                    contact = '$contact',
                    role = '$role',
                    status = '$status'
                    WHERE id = '$userId'";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "User updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating user: " . mysqli_error($conn);
    }

    header("Location: adminHomepage.php");
    exit();
}

// Get filter parameters for users
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$roleFilter = isset($_GET['role']) ? mysqli_real_escape_string($conn, $_GET['role']) : '';
$statusFilter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Build query with filters for users
$query = "SELECT * FROM users WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR email LIKE '%$search%')";
}

if (!empty($roleFilter)) {
    $query .= " AND role = '$roleFilter'";
}

if (!empty($statusFilter)) {
    $query .= " AND status = '$statusFilter'";
}

$query .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$totalUsers = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>413 Central - Admin Dashboard</title>
    <link rel="stylesheet" href="plugins/adminHomepage.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* small inline styles preserved where used */
        .user-name-cell { display: flex; align-items: center; }
        /* Align labels and form controls in edit modal */
        .modal .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }

        .modal .form-group .form-control,
        .modal .form-group input[type="text"],
        .modal .form-group input[type="email"] {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
            height: 40px;
        }
        /* Fix for action buttons visibility */
.user-table {
    overflow: visible !important;
}

.user-table tbody tr {
    overflow: visible !important;
}

.user-table td:last-child {
    overflow: visible !important;
    text-align: center;
    width: 100px;
}

.action-buttons {
    display: flex !important;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.action-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f8f9fa;
    cursor: pointer;
    padding: 0 !important;
}

.action-btn.edit {
    color: #007bff;
}

.action-btn.edit:hover {
    background-color: #e7f1ff;
}

.action-btn.delete {
    color: #dc3545;
}

.action-btn.delete:hover {
    background-color: #ffe7e7;
}

.action-btn i {
    font-size: 16px;
}
    </style>
</head>

<body>
    <div class="home">
        <div class="homepage-wrapper">
            <p class="greeting">
                Kumusta,
                <?php
                if (isset($_SESSION['email'])) {
                    $email = $_SESSION['email'];
                    $query = mysqli_query($conn, "SELECT firstName FROM users WHERE email='$email'");
                    if ($row = mysqli_fetch_assoc($query)) {
                        echo htmlspecialchars($row['firstName']);
                    }
                }
                ?>?
            </p>

            <h4>Dashboard</h4>
            <!-- ================= USER ACCOUNTS ================= -->
            <div class="dashboard-container">
                <h5 class="section-title">User Accounts</h5>
                <!-- Filter Bar -->
                <form method="GET" action="" class="filter-bar">
                    <div class="search-box">
                        <i class='bx bx-search'></i>
                        <input type="text" name="search" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search); ?>">
                    </div>

                    <div class="filter-dropdown">
                        <select name="role" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="admin" <?php echo $roleFilter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="captain" <?php echo $roleFilter === 'captain' ? 'selected' : ''; ?>>Barangay Captain</option>
                            <option value="secretary" <?php echo $roleFilter === 'secretary' ? 'selected' : ''; ?>>Barangay Secretary</option>
                            <option value="kagawad" <?php echo $roleFilter === 'kagawad' ? 'selected' : ''; ?>>Kagawad</option>
                            <option value="user" <?php echo $roleFilter === 'user' ? 'selected' : ''; ?>>User</option>
                        </select>
                        <i class='bx bx-chevron-down'></i>
                    </div>

                    <div class="filter-dropdown">
                        <select name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="Active" <?php echo $statusFilter === 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo $statusFilter === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="Pending" <?php echo $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Banned" <?php echo $statusFilter === 'Banned' ? 'selected' : ''; ?>>Banned</option>
                            <option value="Suspended" <?php echo $statusFilter === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                        </select>
                        <i class='bx bx-chevron-down'></i>
                    </div>

                    <a href="generate_user_report.php" class="pdf-button" target="_blank">
                        <i class='bx bxs-file-pdf'></i>
                        View Users Report (PDF)
                    </a>
                </form>

                <!-- User Table -->
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Joined Date</th>
                            <th>Last Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($user = mysqli_fetch_assoc($result)) {
                                $fullName = htmlspecialchars($user['firstName'] . ' ' . $user['lastName']);
                                $email = htmlspecialchars($user['email']);
                                $role = isset($user['role']) ? $user['role'] : 'user';
                                $roleLower = strtolower(trim($role));
                                if (strpos($roleLower, 'captain') !== false) {
                                    $roleClass = 'captain';
                                } elseif (strpos($roleLower, 'secretary') !== false) {
                                    $roleClass = 'secretary';
                                } elseif (strpos($roleLower, 'kagawad') !== false) {
                                    $roleClass = 'kagawad';
                                } elseif (strpos($roleLower, 'admin') !== false) {
                                    $roleClass = 'admin';
                                } else {
                                    $roleClass = 'user';
                                }
                                $roleLabel = htmlspecialchars(ucfirst($roleClass));
                                $status = isset($user['status']) ? htmlspecialchars($user['status']) : 'Active';
                                $joinedDate = isset($user['created_at']) ? date('F d, Y', strtotime($user['created_at'])) : 'N/A';
                                $lastActive = isset($user['last_active']) ? htmlspecialchars($user['last_active']) : '1 minute ago';

                                $username = explode('@', $email)[0];
                                $initials = strtoupper(substr($user['firstName'], 0, 1) . substr($user['lastName'], 0, 1));

                                echo "<tr>";
                                echo "<td>";
                                echo "<div class='user-name-cell'>";
                                echo "<div style='width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:12px;margin-right:10px;'>" . $initials . "</div>";
                                echo $fullName;
                                echo "</div>";
                                echo "</td>";
                                echo "<td>" . $email . "</td>";
                                echo "<td>" . $username . "</td>";
                                echo "<td><span class='status-badge status-" . strtolower($status) . "'>" . $status . "</span></td>";
                                echo "<td><span class='role-badge role-" . $roleClass . "'>" . $roleLabel . "</span></td>";
                                echo "<td>" . $joinedDate . "</td>";
                                echo "<td>" . $lastActive . "</td>";
                                echo "<td>";
                                echo "<div class='action-buttons'>";
                                echo "<button class='action-btn edit' onclick='openEditModal(" . json_encode($user) . ")' title='Edit'><i class='bx bx-edit'></i></button>";
                                echo "<button class='action-btn delete' onclick='openDeleteModal(" . $user['id'] . ", \"" . addslashes($fullName) . "\")' title='Delete'><i class='bx bx-trash'></i></button>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align:center;padding:40px;color:#999;'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination & Info -->
                <div class="rows-info">
                    Showing <?php echo $totalUsers; ?> of <?php echo $totalUsers; ?> users
                </div>
            </div>
        </div>
    </div>


    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit User</h2>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">

                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstName" id="edit_firstName" required>
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastName" id="edit_lastName" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>

                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact" id="edit_contact" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_role">Role</label>
                        <select name="role" id="edit_role" required class="form-control">
                            <option value="user">User</option>
                            <option value="captain">Captain</option>
                            <option value="secretary">Secretary</option>
                            <option value="kagawad">Kagawad</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Pending">Pending</option>
                            <option value="Banned">Banned</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn modal-btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" name="update_user" class="modal-btn modal-btn-primary">Update User</button>
                </div>
            </form>
        </div>
        

    </div>

    <!-- Delete User Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete User</h2>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="delete-confirm">
                        <i class='bx bx-error-circle'></i>
                        <p>Are you sure you want to delete</p>
                        <p class="user-name" id="delete_user_name"></p>
                        <p style="color:#dc3545;margin-top:15px;">This action cannot be undone!</p>
                    </div>
                    <input type="hidden" name="user_id" id="delete_user_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn modal-btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" name="delete_user" class="modal-btn modal-btn-danger">Delete User</button>
                </div>
            </form>
        </div>

    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        // Edit Modal Functions
        function openEditModal(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_firstName').value = user.firstName;
            document.getElementById('edit_lastName').value = user.lastName;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_contact').value = user.contact || '';
            document.getElementById('edit_role').value = user.role;
            document.getElementById('edit_status').value = user.status || 'Active';
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Delete Modal Functions
        function openDeleteModal(userId, userName) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('delete_user_name').textContent = userName;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == deleteModal) {
                closeDeleteModal();
            }
        }

        // Show toast notification
        <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            <?php if (isset($_SESSION['success'])): ?>
            toast.textContent = '<?php echo $_SESSION['success']; ?>';
            toast.classList.remove('error');
            toast.classList.add('success');
            <?php elseif (isset($_SESSION['error'])): ?>
            toast.textContent = '<?php echo $_SESSION['error']; ?>';
            toast.classList.remove('success');
            toast.classList.add('error');
            <?php endif; ?>
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        });
        <?php unset($_SESSION['success']); unset($_SESSION['error']); ?>
        <?php endif; ?>

    </script>

    
</body>
</html>
