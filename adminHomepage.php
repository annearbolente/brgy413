<?php
ob_start();
session_start();
include("connect.php");
include_once 'sidebar.php';

if (!isset($_SESSION['email'])) {
    header("Location: RegLog.php");
    exit();
}

// ✅ Handle Approve/Reject actions for requests
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action'], $_POST['request_id'], $_POST['request_type'])
) {
    $action = $_POST['action'];
    $requestId = intval($_POST['request_id']);
    $requestType = $_POST['request_type'];

    switch ($requestType) {
        case 'Barangay Clearance':
            $table = 'brgyclearance';
            break;
        case 'Barangay ID':
            $table = 'brgyid';
            break;
        case 'Barangay Indigency':
            $table = 'brgyindigency';
            break;
        case 'Business Clearance':
            $table = 'busclearance';
            break;
        default:
            $table = '';
            break;
    }

    if ($table) {
        $status = ($action === 'approve') ? 'Approved' : 'Rejected';
        $stmt = $conn->prepare("UPDATE $table SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $requestId);
        $stmt->execute();
        $stmt->close();
        if ($action === 'approve') {
            $_SESSION['success'] = "Request Approved successfully!";
        } else {
            $_SESSION['error'] = "Request Rejected successfully!";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
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
    <title>413 Central - Dashboard</title>
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

            <!-- ================= USER REQUEST STATUS ================= -->
            <div class="dashboard-container">
                <h5 class="section-title">User Request Status</h5>

                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Requested On</th>
                            <th>Email</th>
                            <th>Type of Document</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>View Form</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ✅ Load all pending requests (with email)
                        $sql = "
                        SELECT bc.id, bc.date_requested, 'Barangay Clearance' AS type, bc.clearPur AS purpose, bc.status, u.email
                        FROM brgyclearance bc LEFT JOIN users u ON bc.user_id = u.id WHERE bc.status='Pending'
                        UNION ALL
                        SELECT bi.id, bi.date_requested, 'Barangay ID' AS type, 'Not Applicable' AS purpose, bi.status, u.email
                        FROM brgyid bi LEFT JOIN users u ON bi.user_id = u.id WHERE bi.status='Pending'
                        UNION ALL
                        SELECT bg.id, bg.date_requested, 'Barangay Indigency' AS type, bg.purpose AS purpose, bg.status, u.email
                        FROM brgyindigency bg LEFT JOIN users u ON bg.user_id = u.id WHERE bg.status='Pending'
                        UNION ALL
                        SELECT bs.id, bs.date_requested, 'Business Clearance' AS type, 'Not Applicable' AS purpose, bs.status, u.email
                        FROM busclearance bs LEFT JOIN users u ON bs.user_id = u.id WHERE bs.status='Pending'
                        ORDER BY date_requested DESC
                        ";

                        $resultReq = $conn->query($sql);
                        if ($resultReq && $resultReq->num_rows > 0) {
                            while ($row = $resultReq->fetch_assoc()) {
                                $statusClass = strtolower($row['status']);
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['date_requested']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo '<td style="word-wrap: break-word; max-width: 300px;">' . htmlspecialchars($row['purpose']) . '</td>';
                                echo "<td><span class='status-badge status-$statusClass'>" . htmlspecialchars($row['status']) . "</span></td>";

                                // View Form Column
                                echo "<td style='text-align:left; padding-left:0; padding-right:0;'>";
                                echo "<button class='view-btn viewBtn' data-request-id='" . htmlspecialchars($row['id']) . "' data-type='" . htmlspecialchars($row['type']) . "' style='background:#667eea;color:white;border:none;padding:8px 14px;border-radius:6px;cursor:pointer;font-size:16px;transition:background 0.2s ease;display:inline-flex;align-items:center;justify-content:center;margin:0;' onmouseover=\"this.style.background='#5568d3';\" onmouseout=\"this.style.background='#667eea';\">";
                                echo "<i class='bx bx-show'></i>";
                                echo "</button>";
                                echo "</td>";

                                // Actions Column
                                echo "<td style='text-align:left; padding-left:0; padding-right:0;'>";
                                echo "<div style='display:flex; align-items:center; gap:6px; margin:0;'>";
                                echo "<form method='POST' style='margin:0; display:inline;'>";
                                echo "<input type='hidden' name='request_id' value='" . htmlspecialchars($row['id']) . "'>";
                                echo "<input type='hidden' name='request_type' value='" . htmlspecialchars($row['type']) . "'>";
                                echo "<button type='submit' name='action' value='approve' style='display:inline-flex;align-items:center;justify-content:center;gap:5px;background:#28a745;border:none;color:#fff;height:45px;width:95px;font-size:13px;font-weight:600;padding:6px 10px;border-radius:6px;cursor:pointer;transition:0.2s;margin:0;' onmouseover=\"this.style.background='#218838';\" onmouseout=\"this.style.background='#28a745';\"><i class='bx bx-check'></i> Approve</button>";
                                echo "</form>";

                                echo "<form method='POST' style='margin:0; display:inline;'>";
                                echo "<input type='hidden' name='request_id' value='" . htmlspecialchars($row['id']) . "'>";
                                echo "<input type='hidden' name='request_type' value='" . htmlspecialchars($row['type']) . "'>";
                                echo "<button type='submit' name='action' value='reject' style='display:inline-flex;align-items:center;justify-content:center;gap:5px;background:#dc3545;border:none;color:#fff;height:45px;width:95px;font-size:13px;font-weight:600;padding:6px 10px;border-radius:6px;cursor:pointer;transition:0.2s;margin:0;' onmouseover=\"this.style.background='#c82333';\" onmouseout=\"this.style.background='#dc3545';\"><i class='bx bx-x'></i> Reject</button>";
                                echo "</form>";

                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted py-4'>No pending requests found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

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

    <!-- 🔹 View Request Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Request Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="requestDetails">
                    <p class="text-center text-muted">Loading details...</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        

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

        $(document).ready(function() {
            $('.viewBtn').click(function() {
                const requestId = $(this).data('request-id');
                const requestType = $(this).data('type');

                $.ajax({
                    url: 'view_request.php',
                    type: 'POST',
                    data: { request_id: requestId, type: requestType },
                    beforeSend: function() {
                        $('#requestDetails').html("<p class='text-center text-muted'>Loading details...</p>");
                    },
                    success: function(response) {
                        $('#requestDetails').html(response);
                        $('#viewModal').modal('show');
                    },
                    error: function() {
                        $('#requestDetails').html("<p class='text-danger text-center'>Failed to load details.</p>");
                    }
                });
            });
        });
    </script>

    
</body>
</html>
