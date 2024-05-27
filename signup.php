<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rb_financial_advisors"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $id_number = $_POST['id_number'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert user into database
    $sql = "INSERT INTO users (username, email, id_number, address, password) VALUES ('$username', '$email', '$id_number', '$address', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.html"); // Redirect to login page after successful signup
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Display error if any
    }
}

// Close connection
$conn->close();
?>
