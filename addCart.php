<?php
// Set the content type to application/json
header('Content-Type: application/json');
session_start();

// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'egrocery'; // Replace with your actual database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

// Get the request data
$data = json_decode(file_get_contents('php://input'), true);

$email = $_COOKIE['email'];

// Validate email
if (empty($email)) {
    echo json_encode(["error" => "Login first to add item to cart."]);
    exit;
}

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO cart (Email, Name, Image, Price, Quantity) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $email, $data['name'], $data['image'], $data['price'], $data['quantity']);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["success" => "Item added to cart."]);
} else {
    echo json_encode(["error" => "Error adding item to cart: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
mysqli_close($conn);
?>
