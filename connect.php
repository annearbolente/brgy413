<?php
// connect.php - Fixed to not output anything

// --- mysqli Connection ---
$host = "localhost";
$user = "root";
$pass = "";
$db = "brgy413";

// Suppress warnings during connection
$conn = @new mysqli($host, $user, $pass, $db);

// Check connection but DON'T output anything
if ($conn->connect_error) {
    // Store error for later use, but don't die/echo
    $connection_error = "Failed to connect DB: " . $conn->connect_error;
    // Don't use die() here as it outputs HTML
}

// --- PDO Connection ---
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // Store error but don't output
    $pdo_error = "PDO Connection Failed: " . $e->getMessage();
}
?>