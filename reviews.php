<?php
// Set the content type to application/json
header('Content-Type: application/json');

// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'egrocery'; // Replace with your actual database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to select reviews
$query = "SELECT * FROM reviews"; // Make sure your table name and columns match
$res = mysqli_query($conn, $query);

// Check for errors in query execution
if (!$res) {
    die("Query failed: " . mysqli_error($conn));
}

// Initialize an empty array to hold review data
$reviews = [];

// Fetch results and populate the reviews array
while ($row = mysqli_fetch_assoc($res)) {
    $reviews[] = [
        "name" => $row['Name'],
        "image" => $row['Image'],
        "text" => $row['Text'],
        "rating" => (float)$row['Rating'] // Ensure the rating is a float
    ];
}

// Close the database connection
mysqli_close($conn);

// Send JSON response
echo json_encode($reviews);
?>
