<?php
// track_order.php

$servername = "localhost";
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "courier_service";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get AWB number from GET request
$awb_number = $_GET['awb_number'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM shipments WHERE awb_number = ?");
$stmt->bind_param("s", $awb_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $shipment = $result->fetch_assoc();
    $response = [
        'status' => 'success',
        'data' => $shipment
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Shipment not found.'
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>