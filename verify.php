<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "user_auth";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    // Check if the code exists
    $sql = "SELECT * FROM users WHERE verification_code = '$verification_code' AND is_verified = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update user as verified
        $update = "UPDATE users SET is_verified = 1 WHERE verification_code = '$verification_code'";
        if ($conn->query($update) === TRUE) {
            echo "Email verified successfully! You can now <a href='login.html'>Login</a>";
        }
    } else {
        echo "Invalid or expired verification link.";
    }
}

$conn->close();
?>
