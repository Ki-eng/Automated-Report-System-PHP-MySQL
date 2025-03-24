<?php
$conn = mysqli_connect("localhost", "root", "", "report") or die("Connection failed");

$query = isset($_POST['query']) ? $_POST['query'] : '';

$sql = "SELECT * FROM clients WHERE Name LIKE ?";
$stmt = mysqli_prepare($conn, $sql);
$param = $query . '%';
mysqli_stmt_bind_param($stmt, 's', $param);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$names = array();
while ($row = mysqli_fetch_assoc($result)) {
    $names[] = $row;
}

echo json_encode($names);

mysqli_close($conn);
?>
