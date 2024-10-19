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
    $reviewName = $_POST['review_name'] ?? '';
    $reviewText = $_POST['review_text'] ?? '';
    $reviewRating = $_POST['review_rating'] ?? '';

    if (empty($reviewName)) {
        echo json_encode(['success' => false, 'error' => 'Name is required.']);
        exit();
    }

    if (empty($reviewText)) {
        echo json_encode(['success' => false, 'error' => 'Review text is required.']);
        exit();
    }

    if (empty($reviewRating)) {
        echo json_encode(['success' => false, 'error' => 'Rating is required.']);
        exit();
    }

    if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === 0) {
        $imagePath = $_FILES['review_image']['tmp_name'];

        try {
            // Upload to Cloudinary
            $uploadResult = (new UploadApi())->upload($imagePath, ['folder' => 'e_grocery/reviews']);
            $imageUrl = $uploadResult['secure_url'];

            // Debugging: Check the URL before inserting
            error_log("Image URL before insertion: $imageUrl");

            // Insert review data into the database
            $stmt = $conn->prepare("INSERT INTO `reviews` (`Name`, `Image`, `Text`, `Rating`) VALUES (?, ?, ?, ?);");
            $stmt->bind_param('sssd', $reviewName, $imageUrl, $reviewText, $reviewRating); // Adjusted to 'sssd'

            // Debugging: Log the values to be inserted
            error_log("Inserting into database: Name: $reviewName, Text: $reviewText, Rating: $reviewRating, Image: $imageUrl");

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
