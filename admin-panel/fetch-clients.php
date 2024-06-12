<?php
include "../config/config.php";

if (isset($_GET['term'])) {
    $term = $_GET['term'] . '%';
    $stmt = $conn->prepare("SELECT name FROM clients WHERE name LIKE ?");
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
    $names = [];
    while ($row = $result->fetch_assoc()) {
        $names[] = $row['name'];
    }
    echo json_encode($names);
}
?>
