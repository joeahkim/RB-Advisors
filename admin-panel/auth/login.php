<?php
session_start(); 

// Enforce HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

// Set session cookie parameters
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path' => $cookieParams['path'],
    'domain' => $cookieParams['domain'],
    'secure' => true, // Ensure cookie is sent over HTTPS
    'httponly' => true, // Prevent JavaScript access
    'samesite' => 'Strict' // Prevent CSRF
]);

// Regenerate session ID to prevent session fixation
session_regenerate_id(true);

// Redirect logged-in users to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

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

        // Fetch user from database using prepared statement
        $stmt = $conn->prepare("SELECT id, adminname, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $user_name, $hashed_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Start session and store user info
                $_SESSION['admin_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                // Redirect to dashboard
                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['error'] = "Invalid password";
            }
        } else {
            $_SESSION['error'] = "No admin found with this email";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Please provide both email and password.";
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
}

$conn->close();
header("Location: ./login-admin.php");
exit();
?>
