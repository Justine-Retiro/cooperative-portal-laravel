<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";
require '../../api/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['loanNo']) && isset($_POST['action'])) {
        $loanNo = $_POST['loanNo'];
        $note = $_POST['note'];
        $action = $_POST['action'];
        $dueDate = $_POST['dueDate'];
        
        

        // Get the loan amount requested
        list($loanAmount, $loanAfter) = getLoanAmount($loanNo);
        
        updateTransaction($loanNo, $action, $dueDate);
        
        // Update the loan status
        if (updateLoanStatus($loanNo, $loanAfter, $note, $action, $_POST['dueDate'])) {
            // Update the client's balance and remarks
            if (updateClientBalanceAndRemarks($loanNo, $loanAfter, $action)) {
                // header('/admin/memberLoan/loan.php');
                exit();
            } 

        } else {
            echo "Error updating loan status";
        }

    } else {
        echo "Invalid request";
    }
} else {
    echo "Invalid request method";
}

// Function to get the loan amount requested
function getLoanAmount($loanNo) {
    global $conn;
    $sql = "SELECT amount_before, amount_after FROM loan_applications WHERE loanNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $loanNo);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $loanAmount = $row['amount_before'];
        $loanAfter = $row['amount_after'];
        $stmt->close();
        return array($loanAmount, $loanAfter);
    } else {
        echo "Error in getLoanAmount query: " . $stmt->error;
        return false;
    }
}

// Function to update the loan status
function updateLoanStatus($loanNo, $loanAfter, $note, $action, $dueDate = null) {
    global $conn;
    
    $sql = "UPDATE loan_applications SET application_status = ?, action_taken = ?, remarks = ?, note = ? WHERE loanNo = ?";
    $remarks = $action;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $action, $action, $remarks, $note, $loanNo);
    
    if (!$stmt->execute()) {
        echo "Error in updateLoanStatus query: " . $stmt->error;
        return false;
    }
    
    if ($action === 'Accepted') {
    $accepted_at = date('Y-m-d H:i:s'); // Get the current date and time

    $sql = "UPDATE loan_applications la SET la.balance = ?, la.note = ?, la.dueDate = ?, la.accepted_at = ? WHERE loanNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('dssss', $loanAfter, $note, $dueDate, $accepted_at, $loanNo); // Corrected order
    if (!$stmt->execute()) {
        echo "Error in update dueDate query: " . $stmt->error;
        return false;
    }
    } elseif ($action === 'Rejected'){ 
        $rejected_at = date('Y-m-d H:i:s'); // Get the current date and time
    
        $sql = "UPDATE loan_applications la SET la.note = ? , la.dueDate = ? , la.rejected_at = ? WHERE loanNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $note, $dueDate, $rejected_at, $loanNo); // Corrected order
        if (!$stmt->execute()) {
            echo "Error in update dueDate query: " . $stmt->error;
            return false;
        }
    }
    return true;
}

function updateTransaction($loanNo, $action, $account_number){
    global $conn;

    $sql = "SELECT account_number FROM transaction_history WHERE loanNo = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error in prepare: " . $conn->error;
        return false;
    }
    $stmt->bind_param("s", $loanNo);
    if (!$stmt->execute()) {
        echo "Error in execute: " . $stmt->error;
        return false;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "No rows returned from query";
        return false;
    }

    $account_number = $row['account_number'];

    if ($action === 'Accepted') {
        // Update transaction_status in transaction_history for accepted loans
        $sql2 = "UPDATE transaction_history SET transaction_status = 'Accepted' WHERE loanNo = ? AND account_number = ?";
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            echo "Error in prepare: " . $conn->error;
            return false;
        }
        $stmt2->bind_param('ss', $loanNo, $account_number);
        if (!$stmt2->execute()) {
            echo "Error in execute: " . $stmt2->error;
            return false;
        }
    } elseif ($action === "Rejected") {
        // Update transaction_status in transaction_history
        $sql3 = "UPDATE transaction_history SET transaction_status = 'Rejected' WHERE loanNo = ? AND account_number = ?";
        $stmt3 = $conn->prepare($sql3);
        if (!$stmt3) {
            echo "Error in prepare: " . $conn->error;
            return false;
        }
        $stmt3->bind_param('ss', $loanNo, $account_number);
        if (!$stmt3->execute()) {
            echo "Error in execute: " . $stmt3->error;
            return false;
        }
    }
    return true;
}

// Function to update client's balance and remarks
function updateClientBalanceAndRemarks($loanNo, $loanAfter, $action) {
    global $conn;
    error_log("Updating client balance and remarks for loan number $loanNo, loan amount $loanAfter, action $action");

    if ($action === 'Accepted') {
        // Check if there is a balance available
        $balanceCheckSql = "SELECT c.balance FROM clients c INNER JOIN loan_applications la ON c.account_number = la.account_number WHERE la.loanNo = ?";
        $balanceCheckStmt = $conn->prepare($balanceCheckSql);
        if (!$balanceCheckStmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        $balanceCheckStmt->bind_param('i', $loanNo);
        if (!$balanceCheckStmt->execute()) {
            error_log("Execute failed: " . $balanceCheckStmt->error);
            return false;
        }
        $balanceResult = $balanceCheckStmt->get_result();
        $balanceRow = $balanceResult->fetch_assoc();
        $balanceCheckStmt->close();

        $currentBalance = isset($balanceRow['balance']) ? $balanceRow['balance'] : 0.00;
        $newBalance = $currentBalance + $loanAfter;

        // Update the balance
        $sql = "UPDATE clients c INNER JOIN loan_applications la ON c.account_number = la.account_number SET c.balance = ? WHERE la.loanNo = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        $stmt->bind_param('di', $newBalance, $loanNo);
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
        $stmt->close();

        // Update the remarks
        $remarksSql = "UPDATE clients c INNER JOIN loan_applications la ON c.account_number = la.account_number SET c.remarks = 'Unpaid' WHERE la.loanNo = ?";
        $remarksStmt = $conn->prepare($remarksSql);
        if (!$remarksStmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        $remarksStmt->bind_param('i', $loanNo);
        if (!$remarksStmt->execute()) {
            error_log("Execute failed: " . $remarksStmt->error);
            return false;
        }
        $remarksStmt->close();
    }
    // Handle the 'Rejected' case if necessary
    return true;
}
?>