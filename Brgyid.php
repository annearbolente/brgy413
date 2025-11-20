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
    if (isset($_POST['barangayId'], $_POST['name'], $_POST['birthday'])) {
        
        // Sanitize and get form data
        $brgyId = $conn->real_escape_string($_POST['barangayId']);
        $name = $conn->real_escape_string($_POST['name']);
        $birthday = $conn->real_escape_string($_POST['birthday']);
        
        // Get user_id from session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        // Get the current date and set default status
        $dateRequested = date("Y-m-d H:i:s"); 
        $status = "Pending";

        // SQL Injection-safe query using prepared statements
        $sql = "INSERT INTO brgyid (brgyId, fname, birthday, date_requested, status, user_id) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Binding 6 parameters (5 strings + 1 integer)
            $stmt->bind_param("sssssi", $brgyId, $name, $birthday, $dateRequested, $status, $user_id);
            
            if ($stmt->execute()) {
                $success = true;
                $message = "Barangay ID request submitted successfully.";
            } else {
                $message = "Error: Could not execute query. " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error: Could not prepare query. " . $conn->error;
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
    <h2>Barangay ID</h2>
    <span class="close-btn">&times;</span>
</div>

<?php 
// Display the error message if one occurred
if (!empty($message) && !$success) { 
    echo "<div class='alert alert-danger'>" . htmlspecialchars($message) . "</div>";
}
?>

<form method="POST" action="Brgyid.php" id="brgyid-form" style="padding: 20px 25px;">
    <div class="form-group">
        <label>Barangay ID:</label>
        <div class="input-wrapper">
            <input type="text" id="barangayId" name="barangayId" placeholder="Barangay ID" required>
            <i class="fa-solid fa-hashtag"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Name:</label>
        <div class="input-wrapper">
            <input type="text" id="name" name="name" placeholder="Name" required>
            <i class="fa-solid fa-user"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Birthday:</label>
        <div class="input-wrapper">
            <input type="date" id="birthday" name="birthday" required> 
        </div>
    </div>

    <div class="btn-group">
        <button type="reset" class="reset-btn"><i class="fa-solid fa-rotate-left"></i> Reset</button>
        <button type="submit" class="submit-btn"><i class="fa-solid fa-paper-plane"></i> Submit</button>
    </div>
</form>