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
    if (isset($_POST['name'], $_POST['address'], $_POST['age'], $_POST['birthday'], 
              $_POST['nationality'], $_POST['civilStatus'], $_POST['contact'], 
              $_POST['email'], $_POST['purpose'], $_POST['gender'])) {
        
        // Sanitize and get form data
        $fullname = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $age = $conn->real_escape_string($_POST['age']);
        $birthday = $conn->real_escape_string($_POST['birthday']);
        $nationality = $conn->real_escape_string($_POST['nationality']);
        $civilStat = $conn->real_escape_string($_POST['civilStatus']);
        $contact = $conn->real_escape_string($_POST['contact']);
        $email = $conn->real_escape_string($_POST['email']);
        $clearPur = $conn->real_escape_string($_POST['purpose']);
        $gender = $conn->real_escape_string($_POST['gender']);
        
        // date_requested will use default current_timestamp()
        // status will use default 'pending'
        
        // SQL Injection-safe query using prepared statements
        // Get user_id from session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // SQL Injection-safe query using prepared statements
        $sql = "INSERT INTO brgyclearance (fullname, address, age, birthday, nationality, civilStat, contact, email, clearPur, gender, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Binding parameters: s=string, i=integer
            $stmt->bind_param("ssisssssssi", $fullname, $address, $age, $birthday, $nationality, $civilStat, $contact, $email, $clearPur, $gender, $user_id);
            
            if ($stmt->execute()) {
                $success = true;
                $message = "Barangay Clearance request submitted successfully.";
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
// HTML FORM SECTION - FIXED: Complete container structure
?>

<div class="form-header">
    <h2>Barangay Clearance</h2>
    <span class="close-btn">&times;</span>
</div>

<?php 
// Display the error message if one occurred
if (!empty($message) && !$success) { 
    echo "<div class='alert alert-danger'>" . htmlspecialchars($message) . "</div>";
}
?>

<form method="POST" action="BrgyClearance.php" id="brgyclearance-form" style="padding: 20px 25px;">
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
            <label>Age:</label>
            <div class="input-wrapper">
                <input type="number" id="age" name="age" placeholder="Age" required>
                <i class="fa-solid fa-hashtag"></i>
            </div>
        </div>

        <div class="form-group">
            <label>Birthday:</label>
            <div class="input-wrapper">
                <input type="date" id="birthday" name="birthday" required>
            </div>
        </div>

        <div class="form-group">
            <label>Nationality:</label>
            <div class="input-wrapper">
                <input type="text" id="nationality" name="nationality" placeholder="Nationality" required>
                <i class="fa-solid fa-flag"></i>
            </div>
        </div>

        <div class="form-group">
            <label>Civil Status:</label>
            <div class="input-wrapper">
                <select id="civilStatus" name="civilStatus" required>
                    <option value="" disabled selected>Select Civil Status</option>
                    <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Contact:</label>
        <div class="input-wrapper">
            <input type="text" id="contact" name="contact" placeholder="Contact Number" required>
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
        <label>Clearance Purpose:</label>
        <div class="input-wrapper">
            <input type="text" id="purpose" name="purpose" placeholder="Purpose of Clearance" required>
            <i class="fa-solid fa-bullseye"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Gender:</label>
        <div class="input-wrapper">
            <select id="gender" name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
    </div>

    <div class="btn-group">
        <button type="reset" class="reset-btn"><i class="fa-solid fa-rotate-left"></i> Reset</button>
        <button type="submit" class="submit-btn"><i class="fa-solid fa-paper-plane"></i> Submit</button>
    </div>
</form>
