<?php
// Include your database connection file
require_once "globalApi/connection.php";

// Define your SQL query
$sql = "SELECT * FROM clients WHERE client_id = ?"; // Replace with your actual SQL query

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind the client_id parameter to the SQL statement
$stmt->bind_param("i", $param_client_id);

// Set the client_id parameter
$param_client_id = $_GET["client_id"]; // Replace with the actual client_id

// Execute the SQL statement
$stmt->execute();

// Fetch the result
$result = $stmt->get_result();

// Fetch the data as an associative array
$data = $result->fetch_assoc();

// Encode the data as a JSON object and print it
echo json_encode($data);
?>