<?php 
require_once "connection.php";
include "../../api/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'mem'){
    header('Location: /globalApi/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation form data
    $account_number = $_SESSION['account_number'];
    $loanNo = $_POST['loanNo'];
    $application_status = $_POST['application_status'];
    $customer_name = $_POST['customer_name'];
    $age = (int)$_POST['age'];
    $date_employed = $_POST['doe'];
    $birth_date = $_POST['dob'];
    $contact_num = $_POST['contact'];
    $college = $_POST['college'];
    $taxID_num = $_POST['taxID_num'];
    $loan_type = $_POST['loan_type'];
    $work_position = $_POST['work_position'];
    $retirement_year = $_POST['retirement'];
    $application_date = $_POST['doa'];
    $amount_before = $_POST['amount_before'];
    $amount_after = $_POST['amount_after'];
    $time_pay = $_POST['time_pay'];
    $loanTerm_type = $_POST['loan_term_Type'];

    // Handling file image for signature
    $signature = uploadSignature($customer_name, $application_date, $college);

    // Handling file for homepay receipt
    $homepay_receipt = uploadHomepayReceipt($customer_name, $application_date, $college);

    // Start transaction
    $conn->begin_transaction();

        try {
        // Prepare the statement
        $query = "INSERT INTO loan_applications (
            loanNo, account_number, customer_name, age, birth_date, date_employed, contact_num, college, 
            taxID_num, loan_type, work_position, retirement_year, application_date, applicant_sign, applicant_receipt,
            application_status, amount_before, amount_after, time_pay, loan_term_Type)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";    
        $stmtQuery = $conn->prepare($query);

        // Bind parameters for the insert query
        $stmtQuery->bind_param('iissssssssssssssssis', $loanNo, $account_number, $customer_name, $age, $birth_date, $date_employed, $contact_num, 
        $college, $taxID_num, $loan_type, $work_position, $retirement_year, $application_date, $signature, $homepay_receipt, $application_status, 
        $amount_before, $amount_after, $time_pay, $loanTerm_type);

        // Execute the statement
        $stmtQuery->execute();

        // Insert record into transaction_history table
        $audit_description = "Loan Request";
        $transaction_type = "Loan";
        $transaction_date = date("Y-m-d");
        $transaction_status = $application_status;

        $query = "INSERT INTO transaction_history (account_number, loanNo, audit_description, transaction_type, transaction_date, transaction_status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sissss", $account_number, $loanNo, $audit_description, $transaction_type, $transaction_date, $transaction_status);
        $stmt->execute();

        // If no errors, commit the transaction
        $conn->commit();

        header("location: /member/loan/loan.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        exit();
    } finally {
        // Close the statements
        $stmtQuery->close();
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

function uploadSignature($clientName, $dateOfApplication, $clientDepartment) {
    return uploadFile('signature', $_SERVER['DOCUMENT_ROOT'] . '/documents/files/signatures/', $clientName, $dateOfApplication, $clientDepartment);
}

function uploadHomepayReceipt($clientName, $dateOfApplication, $clientDepartment) {
    return uploadFile('homepay_receipt', $_SERVER['DOCUMENT_ROOT'] . '/documents/files/receipts/', $clientName, $dateOfApplication, $clientDepartment);
}

function uploadFile($inputName, $uploadDirectory, $clientName, $dateOfApplication, $clientDepartment) {
    if (!isset($_FILES[$inputName])) {
        return "";
    }

    $targetDir = $uploadDirectory;
    $imageFileType = strtolower(pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION));

    // Generate the file name
    $newFileName = "{$clientName} - {$dateOfApplication} - {$clientDepartment}.{$imageFileType}";
    $targetFilePath = $targetDir . $newFileName;

    // Check file size
    if ($_FILES[$inputName]["size"] > 1000000) {
        echo "Sorry, your file is too large. It should be less than 1MB.";
        return "";
    }

    // Allow certain file formats
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        return "";
    }

    // Check if the file is an image
    $check = getimagesize($_FILES[$inputName]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        return "";
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFilePath)) {
        return $newFileName;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    return "";
}

?>