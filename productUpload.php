<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; // Composer autoloader

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Cloudinary configuration
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dldrjl92a', // Replace with your Cloudinary cloud name
        'api_key'    => '915729848166444', // Replace with your Cloudinary API key
        'api_secret' => 'qM9Fj2ms158op1W1MCwdfD9VgxY', // Replace with your Cloudinary API secret
    ],
    'url' => ['secure' => true]
]);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'egrocery');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'] ?? '';
    $productPrice = $_POST['product_price'] ?? '';
    $productRating = $_POST['product_rating'] ?? '';

    if (empty($productName) || empty($productPrice) || empty($productRating)) {
        echo json_encode(['success' => false, 'error' => 'All product fields are required.']);
        exit();
    }

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $imagePath = $_FILES['product_image']['tmp_name'];

        try {
            // Upload to Cloudinary
            $uploadResult = (new UploadApi())->upload($imagePath, ['folder' => 'e_grocery/products']);
            $imageUrl = $uploadResult['secure_url'];

            // Insert product data into the database
            $stmt = $conn->prepare("INSERT INTO `products` (`Name`, `Image`, `Price`, `Rating`) VALUES (?, ?, ?, ?);");
            $stmt->bind_param('ssdd', $productName, $imageUrl, $productPrice, $productRating); // Correct parameter types

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'image_url' => $imageUrl]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database insertion failed: ' . $stmt->error]);
            }

            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Cloudinary upload failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid image upload.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}

$conn->close();
?>
