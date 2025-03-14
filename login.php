<?php
session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "user_auth";

// Create connection
$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$email = $_POST['email'];
$password = $_POST['password'];

// Check if user exists and is verified
$sql = "SELECT * FROM users WHERE email='$email' AND is_verified=1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['firstname'];
        echo "Login successful! Welcome, " . $_SESSION['user'];
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "User not found or not verified!";
}

$conn->close();
?>
