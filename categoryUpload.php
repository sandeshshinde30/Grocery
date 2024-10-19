<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; // Composer autoloader

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Cloudinary configuration
$config = Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dldrjl92a', // Replace with your Cloudinary cloud name
        'api_key'    => '915729848166444',     // Replace with your Cloudinary API key
        'api_secret' => 'qM9Fj2ms158op1W1MCwdfD9VgxY',  // Replace with your Cloudinary API secret
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
    $categoryName = $_POST['category_name'] ?? '';
    $categoryDiscount = $_POST['category_discount'] ?? '';

    if (empty($categoryName)) {
        echo json_encode(['success' => false, 'error' => 'Category name is required.']);
        exit();
    }

    if (empty($categoryDiscount)) {
        echo json_encode(['success' => false, 'error' => 'Discount percentage is required.']);
        exit();
    }

    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === 0) {
        $imagePath = $_FILES['category_image']['tmp_name'];

        try {
            // Upload to Cloudinary
            $uploadResult = (new UploadApi())->upload($imagePath, ['folder' => 'e_grocery/categories']);
            $imageUrl = $uploadResult['secure_url'];

            // Debugging: Check the URL before inserting
            error_log("Image URL before insertion: $imageUrl");

            // Insert category data into the database
            $stmt = $conn->prepare("INSERT INTO `Category` (`Name`, `Discount`, `Image`) VALUES (?, ?, ?);");
            $stmt->bind_param('sds', $categoryName, $categoryDiscount, $imageUrl);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'image_url' => $imageUrl]);
            } else {
                error_log('Database insertion error: ' . $stmt->error);
                echo json_encode(['success' => false, 'error' => 'Database insertion failed: ' . $stmt->error]);
            }

            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Cloudinary upload failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid image upload.']);
    }
}

$conn->close();
?>
