<?php
// Ensure a session is started so $_SESSION is available
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only include connect.php if $conn doesn't exist yet
// This prevents double-inclusion issues with AJAX requests
if (!isset($conn)) {
    include 'connect.php';
}

// Get session values
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
// Normalize role to lowercase so comparisons are case-insensitive
$userRole = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : 'user';


// Default name fallback
$userFullName = 'User';

if (!empty($email) && isset($conn) && !$conn->connect_error) {
    $query = "SELECT firstName, lastName FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $userFullName = ucwords($row['firstName'] . ' ' . $row['lastName']);
        }
        $stmt->close();
    }
}

// Get the current page filename
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="plugins/sidebar.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet" />
<link rel="icon" href="website_icon/webicon.png" type="image/png" sizes="64x64">

<nav class="sidebar close" style="z-index: 9999;">
    <header>
        <div class="image-text">
            <span class="image profile-image">
                <img src="images/default-profile.jpg" alt="User Profile">
            </span>

            <span class="image brgy-logo">
                <img src="images/brgyLogo.png" alt="Brgy Logo">
            </span>
            <div class="text logo-text brgy-info">
                <span class="name"><?php echo htmlspecialchars($userFullName); ?></span><br>
                <span class="user-role"><?php echo htmlspecialchars(ucfirst(trim($userRole))); ?></span>
            </div>
        </div>
        <i class="bx bx-chevron-right toggle"></i>
    </header>

    <hr class="hr w-100 my-3" />

    <ul class="menu-links">
        <?php if ($userRole === 'admin'): ?>
            <li class="nav-link">
                <a href="adminView.php" class="<?php echo $currentPage == 'adminView.php' ? 'active' : ''; ?>">
                    <i class="bx bx-home-alt icon"></i>
                    <span class="text nav-text">Dashboard</span>
                </a>
            </li>


        <?php elseif ($userRole === 'captain'): ?> 
            <li class="nav-link">
                <a href="adminHomepage.php" class="<?php echo $currentPage == 'adminHomepage.php' ? 'active' : ''; ?>">
                    <i class="bx bx-home-alt icon"></i>
                    <span class="text nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="adminComplaints.php" class="<?php echo $currentPage == 'adminComplaints.php' ? 'active' : ''; ?> long-text">
                    <i class="bx bx-chat icon"></i>
                    <span class="text nav-text">Complaints</span>
                </a>
            </li>

        <?php elseif ($userRole === 'secretary'): ?> 
            <li class="nav-link">
                <a href="adminHomepage.php" class="<?php echo $currentPage == 'adminHomepage.php' ? 'active' : ''; ?>">
                    <i class="bx bx-home-alt icon"></i>
                    <span class="text nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="news.php" class="<?php echo $currentPage == 'news.php' ? 'active' : ''; ?>">
                    <i class="bx bx-edit icon"></i>
                    <span class="text nav-text">Post News & Updates</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="adminOfficials.php" class="<?php echo $currentPage == 'adminOfficials.php' ? 'active' : ''; ?>">
                    <i class="bx bx-group icon"></i>
                    <span class="text nav-text">Barangay Officials</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="adminComplaints.php" class="<?php echo $currentPage == 'adminComplaints.php' ? 'active' : ''; ?> long-text">
                    <i class="bx bx-chat icon"></i>
                    <span class="text nav-text">Complaints</span>
                </a>
            </li>
            
        <?php elseif ($userRole === 'kagawad'): ?>
            <li class="nav-link">
                <a href="adminComplaints.php" class="<?php echo $currentPage == 'adminComplaints.php' ? 'active' : ''; ?> long-text">
                    <i class="bx bx-chat icon"></i>
                    <span class="text nav-text">Isumbong mo kay Kap</span>
                </a>
            </li>


        <?php elseif ($userRole === 'user'): ?>
            <li class="nav-link">
                <a href="userHomepage.php" class="<?php echo $currentPage == 'userHomepage.php' ? 'active' : ''; ?>">
                    <i class="bx bx-home-alt icon"></i>
                    <span class="text nav-text">Homepage</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="request_documents.php" class="<?php echo $currentPage == 'request_documents.php' ? 'active' : ''; ?>">
                    <i class="bx bx-file icon"></i>
                    <span class="text nav-text">Request Documents</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="userOfficials.php" class="<?php echo $currentPage == 'userOfficials.php' ? 'active' : ''; ?>">
                    <i class="bx bx-group icon"></i>
                    <span class="text nav-text">Barangay Officials</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="userComplaints.php" class="<?php echo $currentPage == 'userComplaints.php' ? 'active' : ''; ?>">
                    <i class="bx bx-chat icon"></i>
                    <span class="text nav-text">Isumbong mo kay Kap</span>
                </a>
            </li>
            <li class="nav-link">
                <a href="contact.php" class="<?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>">
                    <i class="bx bx-envelope icon"></i>
                    <span class="text nav-text">Contact Us</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="bottom-content">
        <li class="nav-link">
            <a href="#" data-bs-toggle="modal" data-bs-target="#logoutBootstrapModal"> 
                <i class="bx bx-log-out icon"></i>
                <span class="text nav-text">Logout</span>
            </a>
        </li>
    </div>
</nav>

<div id="logoutBootstrapModal" class="modal fade" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel" style="color: #182052; font-weight: bold;">Brgy 413 Says:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to log out of your account?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                
                <a href="logout.php" class="btn btn-primary">Confirm Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="source/sidebar.js"></script>