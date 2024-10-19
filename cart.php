<?php
// Set the content type to application/json
header('Content-Type: application/json');
session_start();

$email = "";

// Check if the user is logged in
if (isset($_COOKIE['username'])) {
    $email = $_COOKIE['email'];
    
    // Database connection settings
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'egrocery'; // Replace with your actual database name

    // Create connection
    $conn = mysqli_connect($host, $user, $password, $dbname);

    // Check connection
    if (!$conn) {
        die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
    }

    // Query to select cart items
    $query = "SELECT * FROM cart where Email = '$email'"; // Make sure your table name and columns match
    $res = mysqli_query($conn, $query);

    // Check for errors in query execution
    if (!$res) {
        die(json_encode(["error" => "Query failed: " . mysqli_error($conn)]));
    }

    // Initialize an empty array to hold cart data
    $cartItems = [];

    // Fetch results and populate the cart items array
    while ($row = mysqli_fetch_assoc($res)) {
        $cartItems[] = [
            "name" => $row['Name'],
            "image" => $row['Image'],
            "price" => $row['Price'],
            "quantity" => (int)$row['Quantity'] // Ensure the quantity is an integer
        ];
    }

    // Close the database connection
    mysqli_close($conn);

    // Send JSON response with cart items
    echo json_encode($cartItems);
} else {
    // User is not logged in; return a message
    echo json_encode(["error" => "You are not logged in. Please log in to view your cart."]);
}
?>
