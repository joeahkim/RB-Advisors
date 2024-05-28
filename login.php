<?php
session_start();
include 'config.php'; 

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rb_advisors";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Fetch user from database
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Start session and store user info
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    // Redirect to another page
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid password";
                }
            } else {
                $_SESSION['error'] = "No user found with this email";
            }
        } else {
            $_SESSION['error'] = "SQL query error: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Please provide both email and password.";
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
}

$conn->close();
header("Location: untitled.php");
exit();
?>
