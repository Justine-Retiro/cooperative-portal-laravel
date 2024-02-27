<?php
// Include config file
require_once "connection.php";

// Initialize user_id to null
$user_id = null;

// Start a new session or resume the existing session
session_start();

// Account side
// Processing form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the account number and password from the form data
    $account_number = $_POST["account_number"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $acc_type = $_POST["role"];

    // Inserting data into the "users" table to store the default password
    $sql = "INSERT INTO users (account_number, passwords, role) VALUES ('$account_number', '$hashed_password', '$acc_type')";
    if ($stmt = $conn->prepare($sql)) {
        if ($stmt->execute()) {
            // Successfully inserted user data into the 'users' table
            $user_id = mysqli_insert_id($conn); // Get the user_id

            // Close the first statement
            $stmt->close();

            // Save the user data into the session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['account_number'] = $account_number;

            // Now, proceed to insert data into the "clients" table
            $sql = 'INSERT INTO clients (user_id, account_number, last_name, middle_name, first_name, citizenship, civil_status, 
            city_address, provincial_address, 
            mailing_address, account_status, phone_num, taxID_num, spouse_name, birth_date, birth_place, date_employed, position, natureOf_work, amountOf_share) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

            if ($stmt = $conn->prepare($sql)) { 
                // Set parameters for client data
                $param_account_number = $_POST['account_number'];
                $param_lastName = $_POST["last_name"];
                $param_middleName = $_POST["middle_name"];
                $param_firstName = $_POST["first_name"];
                $param_citizenship = $_POST["citizenship"];
                $param_civil_status = $_POST["civil_status"];
                $param_cityAddress = $_POST["city_address"];
                $param_provincialAddress = $_POST["provincial_address"];
                $param_mailingAddress = $_POST["mailing_address"];
                $param_account_status = $_POST["account_status"];
                $param_phone_num = $_POST["phone_num"];
                $param_taxID_num = $_POST["taxID_num"];
                $param_spouseName = $_POST["spouse_name"];
                $param_birth_date = $_POST["birth_date"];
                $param_birth_place = $_POST["birth_place"];
                $param_date_employed = $_POST["date_employed"];
                $param_position = $_POST["position"];
                $param_natureOf_work = $_POST["natureOf_work"];
                $param_amountOf_share = $_POST["amountOf_share"];


                $stmt->bind_param('iisssssssssisssssssi', $user_id, $param_account_number, $param_lastName, $param_middleName, $param_firstName, $param_citizenship, $param_civil_status, $param_cityAddress, $param_provincialAddress, $param_mailingAddress, $param_account_status, $param_phone_num, 
                $param_taxID_num, $param_spouseName, $param_birth_date, $param_birth_place, $param_date_employed, $param_position, $param_natureOf_work, $param_amountOf_share);                
            if ($stmt->execute()) {
                // Records created successfully. Redirect to the landing page
                header("location: /admin/repositories/repositories.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later. " . $stmt->error;
            }

            $stmt->close();
        }

        // Close connection
        $conn->close();
    }
    }
}
?>