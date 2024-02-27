<?php
if (isset($_GET["account_number"])) {
  $account_number = $_GET["account_number"];
  
  // Retrieve the account details from the database and display them
  require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/repositories/api/connection.php";

  $query = "SELECT * FROM clients WHERE account_number = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc(); // Fetch the data into an associative array

  // Fetch the latest loan for the account_number
  $query = "SELECT * FROM loan_applications WHERE account_number = ? AND remarks = 'unpaid' ORDER BY loan_id DESC LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();
  $result = $stmt->get_result();
  $loanData = $result->fetch_assoc(); // Fetch the loan data into a separate associative array

  // Set the header to indicate a JSON response
  header('Content-Type: application/json');

  // Combine the client data and loan data into one array
  $responseData = array_merge($data, $loanData);

  // Output the data as a JSON response
  echo json_encode($responseData);
}
?>