<?php
// Set the content type to application/json
header('Content-Type: application/json');

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

// Query to select categories
$query = "SELECT * FROM Category";
$res = mysqli_query($conn, $query);

// Check for errors in query execution
if (!$res) {
    die("Query failed: " . mysqli_error($conn));
}

// Initialize an empty array to hold category data
$categories = [];

// Fetch results and populate the categories array
while ($row = mysqli_fetch_assoc($res)) {
    $categories[] = [
        "id" => $row['ID'],
        "name" => $row['Name'],
        "image" => $row['Image'],
        "discount" => $row['Discount']
    ];
}

// Close the database connection
mysqli_close($conn);

// Send JSON response
echo json_encode($categories);
?>
