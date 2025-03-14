<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "user_auth";

// Create connection
$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verify user
    $sql = "UPDATE users SET is_verified = 1 WHERE token = '$token'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Email verified successfully! You can now login.";
    } else {
        echo "Verification failed.";
    }
}

$conn->close();
?>
