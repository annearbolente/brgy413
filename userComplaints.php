<?php
session_start();
include 'connect.php';
include_once 'sidebar.php';

$message = "";
$messageType = "";

if(isset($_POST['complaints'])){
    // FIXED: Get the logged-in user's ID from session
    $user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $date = $_POST['date'];
    $issue = trim($_POST['issue']);
    $location = trim($_POST['location']);
    $messageText = trim($_POST['message']);
        
        // File upload for picture
        $picture = '';
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
            $fileType = $_FILES['picture']['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                if ($_FILES['picture']['size'] <= 5242880) {
                    $uploadDir = 'uploads/complaints/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid('complaint_', true) . '.' . strtolower($extension);
                    $targetFile = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFile)) {
                        $picture = $targetFile;
                    } else {
                        $message = "Failed to upload file. Please try again.";
                        $messageType = "error";
                    }
                } else {
                    $message = "File too large. Maximum size is 5MB.";
                    $messageType = "error";
                }
            } else {
                $message = "Invalid file type. Only JPG, PNG, GIF, and PDF are allowed.";
                $messageType = "error";
            }
        }

    if (!$message) {
        // Get user_id from session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
        // INSERT statement
        $sql = "INSERT INTO complaints (user_id, name, email, contact, date, issue, location, message, picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssssss", $user_id, $name, $email, $contact, $date, $issue, $location, $messageText, $picture);
            if ($stmt->execute()) {
                $message = "Your complaint has been submitted successfully!";
                $messageType = "success";
                $_POST = array();
            } else {
                $message = "Error submitting complaint. Please try again.";
                $messageType = "error";
            }
            $stmt->close();
        } else {
            $message = "System error. Please contact support.";
            $messageType = "error";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Isumbong mo kay Kap!</title>
    <link rel="icon" href="website_icon/webicon.png" type="image/png" sizes="64x64">
    <link rel="stylesheet" href="plugins/adminHomepage.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>

<div class="home">
    <div class="homepage-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <p class="greeting">Isumbong mo kay Kap!</p>
                <h4>Submit complaints and concerns to improve our barangay</h4>
            </div>
        </div>

        <!-- Toast Notification -->
        <?php if ($message): ?>
        <div id="toast" class="toast <?php echo $messageType === 'error' ? 'error' : ''; ?> show">
            <i class='bx <?php echo $messageType === 'error' ? 'bx-error-circle' : 'bx-check-circle'; ?>'></i>
            <span><?php echo htmlspecialchars($message); ?></span>
        </div>
        <?php endif; ?>

        <!-- Complaints Container -->
        <div class="complaints-layout">
            <!-- Info Cards Grid -->
            <div class="complaints-info-grid">
                <div class="complaint-info-card">
                    <div class="complaint-card-icon">
                        <i class='bx bx-shield-alt'></i>
                    </div>
                    <h3>Confidential</h3>
                    <p>Your identity is protected. All complaints handled with confidentiality.</p>
                </div>

                <div class="complaint-info-card">
                    <div class="complaint-card-icon">
                        <i class='bx bx-time-five'></i>
                    </div>
                    <h3>Quick Response</h3>
                    <p>We review and respond to complaints within 24-48 hours.</p>
                </div>

                <div class="complaint-info-card">
                    <div class="complaint-card-icon">
                        <i class='bx bx-check-shield'></i>
                    </div>
                    <h3>Fair Resolution</h3>
                    <p>Every complaint is handled fairly with proper investigation.</p>
                </div>

                <div class="complaint-info-card">
                    <div class="complaint-card-icon">
                        <i class='bx bx-phone'></i>
                    </div>
                    <h3>Need Help?</h3>
                    <p>Call us at <a href="tel:+639667403386">0966-740-3386</a> or email <a href="mailto:brgy413.official@gmail.com">brgy413.official@gmail.com</a></p>
                </div>
            </div>

            <!-- Complaint Form -->
            <div class="complaint-form-container">
                <div class="form-card-header">
                    <i class='bx bx-message-square-edit'></i>
                    <h2>Submit Your Complaint</h2>
                </div>

                <form method="post" action="userComplaints.php" enctype="multipart/form-data" id="complaintForm" class="complaint-form">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="name">
                                <i class='bx bx-user'></i> Full Name *
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                placeholder="Enter your full name" 
                                value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" 
                                required 
                            />
                        </div>

                        <div class="form-group">
                            <label for="contact">
                                <i class='bx bx-phone'></i> Contact Number *
                            </label>
                            <input 
                                type="text" 
                                id="contact"
                                name="contact" 
                                placeholder="09XX XXX XXXX" 
                                value="<?php echo htmlspecialchars($_POST['contact'] ?? ''); ?>" 
                                required 
                            />
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="email">
                                <i class='bx bx-envelope'></i> Email Address *
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                placeholder="your.email@example.com" 
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                                required 
                            />
                        </div>

                        <div class="form-group">
                            <label for="date">
                                <i class='bx bx-calendar'></i> Date of Incident *
                            </label>
                            <input 
                                type="date" 
                                id="date"
                                name="date" 
                                value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>" 
                                required 
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="issue">
                            <i class='bx bx-error'></i> Issue Summary *
                        </label>
                        <input 
                            type="text" 
                            id="issue"
                            name="issue" 
                            placeholder="Brief description of the issue" 
                            value="<?php echo htmlspecialchars($_POST['issue'] ?? ''); ?>" 
                            maxlength="150"
                            required 
                        />
                        <small class="char-count">
                            <span id="issueCount">0</span>/150 characters
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="location">
                            <i class='bx bx-map'></i> Location of Incident *
                        </label>
                        <input 
                            type="text" 
                            id="location"
                            name="location" 
                            placeholder="Street, house number, or specific area" 
                            value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" 
                            required 
                        />
                    </div>

                    <div class="form-group">
                        <label for="message">
                            <i class='bx bx-message-detail'></i> Detailed Description *
                        </label>
                        <textarea 
                            id="message"
                            name="message" 
                            placeholder="Provide detailed information about your complaint..."
                            rows="6"
                            maxlength="1000"
                            required
                        ><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        <small class="char-count">
                            <span id="messageCount">0</span>/1000 characters
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="picture">
                            <i class='bx bx-paperclip'></i> Supporting Document (Optional)
                        </label>
                        <div class="file-upload-box">
                            <input 
                                type="file" 
                                id="picture"
                                name="picture" 
                                accept=".jpg,.jpeg,.png,.gif,.pdf"
                                class="file-input-hidden"
                            />
                            <label for="picture" class="file-upload-btn">
                                <i class='bx bx-cloud-upload'></i>
                                <span id="fileName">Choose file or drag here</span>
                            </label>
                            <small class="file-help">JPG, PNG, GIF, PDF (Max 5MB)</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn btn-secondary">
                            <i class='bx bx-reset'></i> Reset Form
                        </button>
                        <button type="submit" name="complaints" class="btn btn-primary">
                            <i class='bx bx-send'></i> Submit Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   <!-- Include Footer -->
     <?php include_once 'footer.php'; ?>

<script>
// Character counters
document.addEventListener('DOMContentLoaded', function() {
    const issueInput = document.getElementById('issue');
    const messageInput = document.getElementById('message');
    const issueCount = document.getElementById('issueCount');
    const messageCount = document.getElementById('messageCount');
    const fileInput = document.getElementById('picture');
    const fileName = document.getElementById('fileName');

    // Initialize counts
    if (issueInput) {
        issueCount.textContent = issueInput.value.length;
        issueInput.addEventListener('input', function() {
            issueCount.textContent = this.value.length;
        });
    }

    if (messageInput) {
        messageCount.textContent = messageInput.value.length;
        messageInput.addEventListener('input', function() {
            messageCount.textContent = this.value.length;
        });
    }

    // File upload
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'Choose file or drag here';
            }
        });
    }

    // Auto-hide toast
    setTimeout(function() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.remove('show');
        }
    }, 3000);
});
</script>


</body>
</html>