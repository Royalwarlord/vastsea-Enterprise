<?php
session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "user_auth";

// Create a connection
$conn = new mysqli($server, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$id_no = $_POST['id_no'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Password validation
if ($password !== $confirm_password) {
    die("Passwords do not match!");
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Generate verification token
$token = md5(uniqid(mt_rand(), true));

// Insert into database
$sql = "INSERT INTO users (firstname, lastname, id_no, email, password, token) 
        VALUES ('$firstname', '$lastname', '$id_no', '$email', '$hashed_password', '$token')";

if ($conn->query($sql) === TRUE) {
    // Send verification email
    $subject = "Verify Your Email";
    $message = "Click the link below to verify your email:\n\n";
    $message .= "http://localhost/verify.php?token=" . $token;
    $headers = "From: Vast_sea@yahoo.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "Registration successful! Check your email to verify your account.";
    } else {
        echo "Error sending email.";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
