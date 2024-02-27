<?php
session_start(); // Start the session
require_once __DIR__ . "/connection.php";

function updateClientRemarksIfAllLoansPaid($conn, $account_number) {
    // Check if all loan applications are paid for the given account number
    $allLoansPaidQuery = "SELECT COUNT(*) as unpaid_loans FROM loan_applications WHERE account_number = ? AND balance > 0";
    $stmt = $conn->prepare($allLoansPaidQuery);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // If there are no unpaid loans, update the client's remarks
    if ($row['unpaid_loans'] == 0) {
        $updateClientRemarksQuery = "UPDATE clients SET remarks = 'Paid' WHERE account_number = ?";
        $updateStmt = $conn->prepare($updateClientRemarksQuery);
        $updateStmt->bind_param("s", $account_number);
        $updateStmt->execute();
        
        // Check for errors in update
        if ($conn->error) {
            return "Error updating client remarks: " . $conn->error;
        }
    } else {
        // There are still unpaid loans
        return "Not all loans are paid. No update to client remarks.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST["account_number"];
    $loanNo = $_POST["loanNo"]; 
    $note = $_POST["notes"];
    $pay_balance = $_POST["balance"];
    $loan_balance = $_POST["loanBalance"];
    $remarks = $_POST["remarks"];
    $payment_date = date("Y-m-d"); 
    $audit_description = "Loan Payment"; // Set the audit description

    // Fetch the current balance from the database before making any calculations
    $query = "SELECT balance FROM clients WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $clientBal = $result->fetch_assoc();
    $totalBalance = $clientBal['balance']; // Overall balance (Account Balance)

    // Calculate the new balance after payment
    $new_balance = $totalBalance - $pay_balance;

    // Update clients table with the new balance
    $query = "UPDATE clients SET balance = ? WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $new_balance, $account_number);
    $stmt->execute();

    // Update loan_applications table with the payment made
    $loanPaymentQuery = "UPDATE loan_applications SET balance = balance - ? WHERE loanNo = ?";
    $loanPaymentStmt = $conn->prepare($loanPaymentQuery);
    $loanPaymentStmt->bind_param("ss", $pay_balance, $loanNo);
    $loanPaymentStmt->execute();

    // Determine remarks based on the new balance
    $paymentRemarks = ($new_balance <= 0) ? 'Paid Fully' : 'Paid Partially';

    // Call the function to check if all loans are paid and update client remarks accordingly
    $updateResult = updateClientRemarksIfAllLoansPaid($conn, $account_number);
    if (strpos($updateResult, 'Error') !== false) {
        echo $updateResult; 
    }

    // Update remarks
    $loanRemarksQuery = "UPDATE loan_applications SET remarks = ? WHERE loanNo = ?";
    $loanRemarkstStmt = $conn->prepare($loanRemarksQuery);
    $loanRemarkstStmt->bind_param("ss", $remarks, $loanNo);
    $loanRemarkstStmt->execute();


    // Insert record into loan_payments table
    $query = "INSERT INTO loan_payments (loanNo, account_number, remarks, note, amount_paid, payment_date, audit_description) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $loanNo, $account_number, $paymentRemarks, $note, $pay_balance, $payment_date, $audit_description);
    $stmt->execute();

    // Insert record into transaction_history table
    $query = "INSERT INTO transaction_history (account_number, audit_description, transaction_type, transaction_date, transaction_status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $account_number, $audit_description, $audit_description, $payment_date, $paymentRemarks);
    $stmt->execute();

    // Check for errors
    if ($conn->error) {
        echo "Error: " . $conn->error;
    } else {
        $response["status"] = "success";
        $response["message"] = "Successfuly updated";
        header("Location: /admin/payment/edit/edit.php?account_number="  . $account_number); 
        exit();
    }
echo json_encode($response);
}
?>