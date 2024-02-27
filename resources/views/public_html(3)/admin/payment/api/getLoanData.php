<?php
require_once __DIR__ . "/connection.php";
include 'editHeader.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$loanNo = $_GET['loanNo']; // Get the loan number from the GET parameters

$sql = "SELECT * FROM loan_applications WHERE loanNo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loanNo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0){
    $row = $result->fetch_assoc();
    echo json_encode($row); // Return the data as a JSON string
} else {
    echo json_encode(array('error' => 'No loan found with this number'));
}
?>