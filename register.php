<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

$server = "localhost";
$username = "root";
$password = "";
$database = "user_auth";

// Connect to MySQL
$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$id_no = $_POST['id_no'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    die("Passwords do not match!");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Generate a verification code
$verification_code = md5(rand());

// Insert user into database
$sql = "INSERT INTO users (firstname, lastname, id_no, email, password, verification_code) 
        VALUES ('$firstname', '$lastname', '$id_no', '$email', '$hashed_password', '$verification_code')";

if ($conn->query($sql) === TRUE) {
    // Send verification email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Your email
        $mail->Password = 'your_email_password'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 3306;

        $mail->setFrom('vast_sea@yahoo.com', 'Admin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email';
        $mail->Body = "Click this link to verify your email: <a href='http://localhost/verify.php?code=$verification_code'>Verify</a>";

        $mail->send();
        echo "Registration successful! Check your email to verify your account.";
    } catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
