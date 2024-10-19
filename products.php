<?php

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

// Query to select products
$query = "SELECT name, image, price, rating FROM products";
$res = mysqli_query($conn, $query);

// Check for errors in query execution
if (!$res) {
    die("Query failed: " . mysqli_error($conn));
}

// Initialize an empty array to hold product data
$products = [];

// Fetch results and populate the products array
while ($row = mysqli_fetch_assoc($res)) {
    $products[] = [
        "name" => $row['name'],
        "image" => $row['image'],
        "price" => $row['price'],
        "rating" => (float)$row['rating'] // Cast to float if needed
    ];
}

// Close the database connection
mysqli_close($conn);

// Set the header to indicate JSON response
header('Content-Type: application/json');

// Return the products as a JSON response
echo json_encode($products);
?>


