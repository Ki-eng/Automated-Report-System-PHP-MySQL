<?php
$servername = "localhost";
$username = "root";
$password = ""; // Add your database password
$dbname = "report";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['client_id'])) {
    $client_id = intval($_GET['client_id']);
    $sql = "SELECT * FROM clients WHERE id = $client_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        echo json_encode($client);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>
