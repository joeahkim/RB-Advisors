<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rb_financial_advisors";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$date = $_POST['date'];
$description = $_POST['description'];
$transaction_type = $_POST['transaction_type'];
$amount = $_POST['amount'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO transactions (date, description, transaction_type, amount) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $date, $description, $transaction_type, $amount);

// Execute the statement
if ($stmt->execute()) {
    echo "New transaction added successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
