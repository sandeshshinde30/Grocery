<?php
// send_password.php

// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'egrocery';

// Create connection
$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to send email
function sendEmail($to, $password) {
    $subject = "Your Password Request";
    $message = "Hello,\n\nYour password is: $password\n\nPlease keep it safe.";
    $headers = "From: sandeshshinde7047@gmail.com"; // Replace with your sender email

    // Use the mail function to send email
    return mail($to, $subject, $message, $headers);
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email from the request body
    $input = json_decode(file_get_contents('php://input'), true);
    $email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email address.']);
        exit;
    }

    // Query the database for the user
    $sql = "SELECT Password FROM login WHERE Email = ?"; // Adjust table and column names
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Database query preparation failed.']);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Email not found.']);
        exit;
    }

    // Fetch the password
    $row = $result->fetch_assoc();
    $password = $row['password']; // This is not secure; consider using password reset instead

    // Send the password email
    if (sendEmail($email, $password)) {
        echo json_encode(['success' => true, 'message' => 'Your password has been sent to your email.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to send email.']);
    }

    $stmt->close();
}

$conn->close();
?>
