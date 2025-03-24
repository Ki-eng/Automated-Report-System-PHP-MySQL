<?php
include 'db_connection.php';

$report_no = $_GET['report_no'];

$sql = "SELECT * FROM clients WHERE TestReportNo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $report_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode(["error" => "No data found"]);
}

$stmt->close();
$conn->close();
?>
