<?php
session_start();
require_once 'connect.php';

// Handle Registration
if (isset($_POST['signUp'])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($firstName) || empty($lastName) || empty($email) || empty($contact) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: RegLog.php");
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: RegLog.php");
        exit();
    }
    
    // Check if email already exists
    $checkEmail = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->execute([$email]);
    
    if ($checkEmail->rowCount() > 0) {
        $_SESSION['error'] = "Email already exists.";
        header("Location: RegLog.php");
        exit();
    }
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    try {
        $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, contact, password, role, status) VALUES (?, ?, ?, ?, ?, 'user', 'Active')");
        $stmt->execute([$firstName, $lastName, $email, $contact, $hashedPassword]);
        
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: RegLog.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: RegLog.php");
        exit();
    }
}

// Handle Login
if (isset($_POST['signIn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: RegLog.php");
        exit();
    }
    
    // Check if user exists
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Check if account is active
            if ($user['status'] !== 'Active') {
                $_SESSION['error'] = "Your account is not active.";
                header("Location: RegLog.php");
                exit();
            }
            
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];
            $_SESSION['email'] = $user['email'];
            // Normalize the stored role to lowercase to make role checks case-insensitive
            $_SESSION['role'] = isset($user['role']) ? strtolower(trim($user['role'])) : 'user';
            
            // Update last_active
            $updateStmt = $pdo->prepare("UPDATE users SET last_active = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            // Store redirect URL in session for JavaScript to use
            // Prefer the normalized session role (we stored it above). Fall back to DB role if needed.
            $roleToCheck = isset($_SESSION['role']) ? $_SESSION['role'] : (isset($user['role']) ? strtolower(trim($user['role'])) : 'user');

            // Treat captain and secretary as admin-level users for redirect purposes
            if ($roleToCheck === 'secretary' || $roleToCheck === 'captain') {
                $_SESSION['redirect_url'] = 'adminHomepage.php';
            } elseif ($roleToCheck === 'kagawad') {
                $_SESSION['redirect_url'] = 'adminComplaints.php';
            } elseif ($roleToCheck === 'admin') {
                $_SESSION['redirect_url'] = 'adminView.php';
            }else {
                $_SESSION['redirect_url'] = 'userHomepage.php';
            }
            
            // Redirect back to RegLog.php with success parameter
            header("Location: RegLog.php?login=success");
            exit();
        } else {
            // Incorrect password or email
            $_SESSION['error'] = "Incorrect password or email.";
            header("Location: RegLog.php");
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Login failed. Please try again.";
        header("Location: RegLog.php");
        exit();
    }
}

// If no POST request, redirect to login page
header("Location: RegLog.php");
exit();
?>
