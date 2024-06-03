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

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        if (isset($_POST['date']) && isset($_POST['description']) && isset($_POST['transaction_type']) && isset($_POST['amount'])) {
            $date = $_POST['date'];
            $description = $_POST['description'];
            $transaction_type = $_POST['transaction_type'];
            $amount = $_POST['amount'];

            // Insert data into transactions table
            $sql = "INSERT INTO transactions (user_id, date, description, transaction_type, amount) VALUES ('$user_id', '$date', '$description', '$transaction_type', '$amount')";

            if ($conn->query($sql) === TRUE) {
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $_SESSION['error'] = "Please fill in all fields.";
        }
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$conn->close();
?>
