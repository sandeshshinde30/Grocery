<?php
// deleteCart.php

// Connect to your database
include 'db_connection.php'; // Adjust this as per your DB connection method

 // Database connection settings
 $host = 'localhost';
 $user = 'root';
 $password = '';
 $dbname = 'egrocery'; // Replace with your actual database name

 // Create connection
 $conn = mysqli_connect($host, $user, $password, $dbname);

 $email = $_COOKIE['email'];

 // Check connection
 if (!$conn) {
     die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
 }


$request = json_decode(file_get_contents('php://input'), true);
$itemName = $request['name'];

if (!$itemName) {
    echo json_encode(['success' => false, 'error' => 'Item name is required']);
    exit;
}

// Prepare and execute delete query
$sql = "DELETE FROM cart WHERE name = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $itemName, $email);// Use "s" for string binding

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete item']);
}

$stmt->close();
$conn->close();
?>
