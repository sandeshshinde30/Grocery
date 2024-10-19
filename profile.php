<?php
session_start();
header('Content-Type: application/json');

if (isset($_COOKIE['username'])) {
    echo json_encode([
        'isLoggedIn' => true,
        'username' => $_COOKIE['username']
    ]);
} else {
    echo json_encode(['isLoggedIn' => false]);
}
?>
