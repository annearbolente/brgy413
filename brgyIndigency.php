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
    if (isset($_POST['name'], $_POST['address'], $_POST['date'], $_POST['purpose'])) {
        
        // Sanitize and get form data
        $name = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $date = $conn->real_escape_string($_POST['date']);
        $purpose = $conn->real_escape_string($_POST['purpose']);
        
        // date_requested will use default current_timestamp()
        // status will use default 'Pending'
       // Get user_id from session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        // SQL Injection-safe query using prepared statements
        $sql = "INSERT INTO brgyindigency (name, address, date, purpose, user_id) 
                VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Binding 4 string parameters
            $stmt->bind_param("ssssi", $name, $address, $date, $purpose, $user_id);
            
            if ($stmt->execute()) {
                $success = true;
                $message = "Barangay Indigency request submitted successfully.";
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
    <h2>Barangay Indigency</h2>
    <span class="close-btn">&times;</span>
</div>

<?php 
// Display the error message if one occurred
if (!empty($message) && !$success) { 
    echo "<div class='alert alert-danger'>" . htmlspecialchars($message) . "</div>";
}
?>

<form method="POST" action="brgyIndigency.php" id="brgyindigency-form" style="padding: 20px 25px;">
    <div class="form-group">
        <label>Name:</label>
        <div class="input-wrapper">
            <input type="text" id="name" name="name" placeholder="Full Name" required>
            <i class="fa-solid fa-user"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Address:</label>
        <div class="input-wrapper">
            <input type="text" id="address" name="address" placeholder="Address" required>
            <i class="fa-solid fa-location-dot"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Date:</label>
        <div class="input-wrapper">
            <input type="date" id="date" name="date" required>
        </div>
    </div>

    <div class="form-group">
        <label>Purpose:</label>
        <div class="input-wrapper">
            <input type="text" id="purpose" name="purpose" placeholder="Purpose of Indigency Certificate" required>
            <i class="fa-solid fa-bullseye"></i>
        </div>
    </div>

    <div class="btn-group">
        <button type="reset" class="reset-btn"><i class="fa-solid fa-rotate-left"></i> Reset</button>
        <button type="submit" class="submit-btn"><i class="fa-solid fa-paper-plane"></i> Submit</button>
    </div>
</form>