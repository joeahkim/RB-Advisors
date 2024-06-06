<?php

session_start(); 
// Redirect logged-in users to dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
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

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$idNumber = $_POST['idNumber'];
$address = $_POST['address'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt the password

// Insert data into database
$sql = "INSERT INTO users (name, email, id_number, address, password) VALUES ('$name', '$email', '$idNumber', '$address', '$password')";

if ($conn->query($sql) === TRUE) {
    // Redirect to another page
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
