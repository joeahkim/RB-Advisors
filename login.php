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

// Query to fetch username and password from the database
$sql = "SELECT username, password FROM users";
$result = $conn->query($sql);


    // Output data of each row{
        if ($conn->query($sql) === TRUE) {
            header("Location: dashboard.html"); // Redirect to login page after successful signup
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error; 
        }
 else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
