<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'egrocery';

$conn = new mysqli($host, $user, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// else {
//     echo "connection success";
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit;
    }


    // Insert data into `register` table
    $registerQuery = "INSERT INTO `register` (`ID`, `Name`, `Email`, `Password`) VALUES (NULL, '$name', '$email', '$password')";

    $res = mysqli_query($conn, $registerQuery);

    if ($res) {
        // Insert data into `login` table
        $loginQuery = "INSERT INTO `login` (`ID`, `Email`, `Password`) VALUES (NULL, '$email', '$password');";

        if (mysqli_query($conn, $loginQuery)) {
            echo 'success'; 
        } else {
            echo "Error inserting into login table: " . mysqli_error($conn);
        }
    } else {
        echo "Error inserting into register table: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
