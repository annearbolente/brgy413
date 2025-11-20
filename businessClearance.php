<?php
// Include the connection file
include("connect.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$message = "";
$success = false;

// 1. Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if essential fields are set
    if (isset($_POST['appDate'], $_POST['appType'], $_POST['busName'], $_POST['busAdd'], 
              $_POST['natureOfBus'], $_POST['busOwnType'], $_POST['LocStatus'], 
              $_POST['parkingLot'], $_POST['capitalization'], $_POST['salesRcpts'], 
              $_POST['opeFullName'], $_POST['contactNo'], $_POST['email']) 
              && isset($_FILES['nameSig'])) {
        
        // Sanitize and get form data
        $appDate = $conn->real_escape_string($_POST['appDate']);
        $appType = $conn->real_escape_string($_POST['appType']);
        $busName = $conn->real_escape_string($_POST['busName']);
        $busAdd = $conn->real_escape_string($_POST['busAdd']);
        $natureOfBus = $conn->real_escape_string($_POST['natureOfBus']);
        $busOwnType = $conn->real_escape_string($_POST['busOwnType']);
        $LocStatus = $conn->real_escape_string($_POST['LocStatus']);
        $parkingLot = $conn->real_escape_string($_POST['parkingLot']);
        $capitalization = $conn->real_escape_string($_POST['capitalization']);
        $salesRcpts = $conn->real_escape_string($_POST['salesRcpts']);
        $opeFullName = $conn->real_escape_string($_POST['opeFullName']);
        $contactNo = $conn->real_escape_string($_POST['contactNo']);
        $email = $conn->real_escape_string($_POST['email']);
        
        // Handle signature image upload
        $nameSig = "";
        $uploadDir = "uploads/signatures/";
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Check if file was uploaded without errors
        if ($_FILES['nameSig']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $fileType = $_FILES['nameSig']['type'];
            $fileSize = $_FILES['nameSig']['size'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            // Validate file type
            if (in_array($fileType, $allowedTypes)) {
                // Validate file size
                if ($fileSize <= $maxSize) {
                    // Generate unique filename
                    $fileExtension = pathinfo($_FILES['nameSig']['name'], PATHINFO_EXTENSION);
                    $uniqueFileName = uniqid('sig_', true) . '.' . $fileExtension;
                    $targetFile = $uploadDir . $uniqueFileName;
                    
                    // Move uploaded file
                    if (move_uploaded_file($_FILES['nameSig']['tmp_name'], $targetFile)) {
                        $nameSig = $targetFile;
                    } else {
                        $message = "Error: Failed to upload signature image.";
                    }
                } else {
                    $message = "Error: Signature image must be less than 5MB.";
                }
            } else {
                $message = "Error: Only JPG, JPEG, PNG, and GIF images are allowed for signature.";
            }
        } else {
            $message = "Error: Please upload a signature image.";
        }
        
        // Only proceed with database insert if signature upload was successful
        if (empty($message)) {
        
            // date_requested will use default current_timestamp()
            // status will use default 'Pending'
            // Get user_id from session
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
            // SQL Injection-safe query using prepared statements
            $sql = "INSERT INTO busclearance (appDate, appType, busName, busAdd, natureOfBus, busOwnType, LocStatus, parkingLot, capitalization, salesRcpts, opeFullName, contactNo, email, nameSig, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            if ($stmt = $conn->prepare($sql)) {
                // Binding parameters: s=string, i=integer
                $stmt->bind_param("sssssssiiisissi", $appDate, $appType, $busName, $busAdd, $natureOfBus, $busOwnType, $LocStatus, $parkingLot, $capitalization, $salesRcpts, $opeFullName, $contactNo, $email, $nameSig, $user_id);
                
                if ($stmt->execute()) {
                    $success = true;
                    $message = "Business Clearance request submitted successfully.";
                } else {
                    $message = "Error: Could not execute query. " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Error: Could not prepare query. " . $conn->error;
            }
        }
    } else {
        $message = "Error: All form fields are required.";
    }

    // Output the message and stop script execution
    if ($success) {
        echo $message;
        exit; 
    }
}

// -----------------------------------------------------------
// HTML FORM SECTION - FIXED: Removed nested form-body container
?>

<div class="form-header">
    <h2>Business Clearance</h2>
    <span class="close-btn">&times;</span>
</div>

<?php 
// Display the error message if one occurred
if (!empty($message) && !$success) { 
    echo "<div class='alert alert-danger'>" . htmlspecialchars($message) . "</div>";
}
?>

<form method="POST" action="businessClearance.php" id="busclearance-form" enctype="multipart/form-data" style="padding: 20px 25px;">
    <div class="form-group">
        <label>Application Date:</label>
        <div class="input-wrapper">
            <input type="date" id="appDate" name="appDate" required>
        </div>
    </div>

    <div class="form-group">
        <label>Application Type:</label>
        <div class="input-wrapper">
            <select id="appType" name="appType" required>
                <option value="" disabled selected>Select Application Type</option>
                <option value="New">New</option>
                <option value="Renewal">Renewal</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Business Name:</label>
        <div class="input-wrapper">
            <input type="text" id="busName" name="busName" placeholder="Business Name" required>
            <i class="fa-solid fa-building"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Business Address:</label>
        <div class="input-wrapper">
            <input type="text" id="busAdd" name="busAdd" placeholder="Business Address" required>
            <i class="fa-solid fa-location-dot"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Nature of Business:</label>
        <div class="input-wrapper">
            <input type="text" id="natureOfBus" name="natureOfBus" placeholder="Nature of Business" required>
            <i class="fa-solid fa-briefcase"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Business Ownership Type:</label>
        <div class="input-wrapper">
            <select id="busOwnType" name="busOwnType" required>
                <option value="" disabled selected>Select Ownership Type</option>
                <option value="Sole Proprietorship">Sole Proprietorship</option>
                <option value="Partnership">Partnership</option>
                <option value="Corporation">Corporation</option>
                <option value="Cooperative">Cooperative</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Location Status:</label>
        <div class="input-wrapper">
            <select id="LocStatus" name="LocStatus" required>
                <option value="" disabled selected>Select Location Status</option>
                <option value="Owned">Owned</option>
                <option value="Rented">Rented</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Parking Lot (sq. meters):</label>
        <div class="input-wrapper">
            <input type="number" id="parkingLot" name="parkingLot" placeholder="Parking Lot Area" required>
            <i class="fa-solid fa-square-parking"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Capitalization (₱):</label>
        <div class="input-wrapper">
            <input type="number" id="capitalization" name="capitalization" placeholder="Capitalization Amount" required>
            <i class="fa-solid fa-peso-sign"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Sales Receipts (₱):</label>
        <div class="input-wrapper">
            <input type="number" id="salesRcpts" name="salesRcpts" placeholder="Sales Receipts Amount" required>
            <i class="fa-solid fa-receipt"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Owner/Operator Full Name:</label>
        <div class="input-wrapper">
            <input type="text" id="opeFullName" name="opeFullName" placeholder="Full Name" required>
            <i class="fa-solid fa-user"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Contact Number:</label>
        <div class="input-wrapper">
            <input type="number" id="contactNo" name="contactNo" placeholder="Contact Number" required>
            <i class="fa-solid fa-phone"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Email:</label>
        <div class="input-wrapper">
            <input type="email" id="email" name="email" placeholder="Email Address" required>
            <i class="fa-solid fa-envelope"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Signature Image:</label>
        <div class="input-wrapper">
            <input type="file" id="nameSig" name="nameSig" accept="image/jpeg,image/jpg,image/png,image/gif" required>
            <i class="fa-solid fa-image"></i>
        </div>
        <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">
            Upload your signature image (JPG, PNG, GIF - Max 5MB)
        </small>
    </div>

    <div class="btn-group">
        <button type="reset" class="reset-btn"><i class="fa-solid fa-rotate-left"></i> Reset</button>
        <button type="submit" class="submit-btn"><i class="fa-solid fa-paper-plane"></i> Submit</button>
    </div>
</form>