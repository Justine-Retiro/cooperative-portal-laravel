<?php
require_once "connection.php";

if (isset($_GET["account_number"])) {
  $account_number = $_GET["account_number"];
  
  // Fetch the loanNo values associated with the account_number from the loan_applications table
  $query = "SELECT loanNo FROM loan_applications WHERE account_number = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $loanNo = $row['loanNo'];

    // Prepare the delete query for loan_payments table
    $query = "DELETE FROM loan_payments WHERE loanNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $loanNo);
    $stmt->execute();
  }

  // Prepare the delete query for loan_applications table
  $query = "DELETE FROM loan_applications WHERE account_number = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();

  // Prepare the delete query for transaction_history table
  $query = "DELETE FROM transaction_history WHERE account_number = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();

  // Prepare the delete query for clients table
  $query = "DELETE FROM clients WHERE account_number = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();

  // Prepare the delete query for users table
  $query = "DELETE FROM users WHERE account_number = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();
  
  // Execute the delete query
  if ($stmt->execute()) {
    // Redirect back to the repositories.php page after successful deletion
    header("Location: /master/repositories/repositories.php");
    exit();
  } else {
    // Handle the error if the deletion fails
    echo "Error deleting record: " . $conn->error;
  }
}
?>