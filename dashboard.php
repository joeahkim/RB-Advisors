<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: untitled.php");
    exit();
}

// Retrieve user name from database based on user ID ($_SESSION['user_id'])
$name = $_SESSION['name']; // Retrieve user name from the database, e.g., using $_SESSION['user_id']

// Sanitize user name to prevent XSS attacks
$sanitized_user_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

// Redirect to dashboard.html with sanitized user name as a query parameter
header("Location: dashboard.html?name=" . urlencode($sanitized_user_name));
exit();
?>
