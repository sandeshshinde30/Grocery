<?php
session_start();

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

// Get posted values
$email = $_POST['email'];
$password = $_POST['password'];

// Query to find the user
$query = "SELECT * FROM register WHERE Email = '$email'";
$res = mysqli_query($conn, $query);

// Check if any row returned
if ($res) {
    if (mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);

        // Verify the password
        if ($user['Email'] == $email && $user['Password'] == $password) {
            // Set cookies for user session
            setcookie("username", $user['Name'], time() + 3600, "/");
            setcookie("email", $user['Email'], time() + 3600, "/");

            // Check if the user is an admin
            if ($user['Email'] == "sandeshshinde7047@gmail.com") {
                echo "admin"; // Send "admin" response
            } else {
                echo "success"; // Send "success" response for regular users
            }
        } else {
            echo "Invalid email or password."; // Incorrect password
        }
    } else {
        echo "Invalid email or password."; // No user found
    }
} else {
    die("Query failed: " . mysqli_error($conn));
}

// Close the connection
mysqli_close($conn);
?>
