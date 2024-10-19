<?php
require 'vendor/autoload.php';  // Include Composer autoloader

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Configure Cloudinary
$config = Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dldrjl92a',  // Replace with your Cloudinary cloud name
        'api_key'    => '915729848166444',     // Replace with your Cloudinary API key
        'api_secret' => 'qM9Fj2ms158op1W1MCwdfD9VgxY',  // Replace with your Cloudinary API secret
    ],
    'url' => [
        'secure' => true   // Use HTTPS
    ]
]);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $imagePath = $_FILES['product_image']['tmp_name'];  // Get temporary file path

        try {
            // Upload image to Cloudinary
            $result = (new UploadApi())->upload($imagePath, [
                'folder' => 'uploads/'  // Optional: Store images in a specific folder
            ]);

            // Return JSON response with the secure URL
            echo json_encode([
                'success' => true,
                'secure_url' => $result['secure_url']
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'No image uploaded or an error occurred.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method.'
    ]);
}
?>
