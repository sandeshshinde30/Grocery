<?php
// Start session and destroy it (if applicable)
session_start();
session_destroy();

// Delete the username cookie
setcookie("username", "", time() - 3600, "/");
setcookie("email", "", time() - 3600, "/");


// Redirect to index.html
header("Location: index.html");
exit;
?>
