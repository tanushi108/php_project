<?php
// shipment_api.php

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

// Get shipment details from POST request
$data = json_decode(file_get_contents("php://input"), true);
$customer_name = $data['customer_name'];
$address = $data['address'];
$city = $data['city'];
$state = $data['state'];
$pincode = $data['pincode'];
$phone = $data['phone'];
$weight = $data['weight'];

// Generate a fake AWB number
$awb_number = strtoupper(uniqid('AWB'));

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO shipments (customer_name, address, city, state, pincode, phone, weight, awb_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $customer_name, $address, $city, $state, $pincode, $phone, $weight, $awb_number);

if ($stmt->execute()) {
    $response = [
        'status' => 'success',
        'awb_number' => $awb_number,
        'message' => 'Shipment booked successfully.'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Failed to book shipment.'
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>