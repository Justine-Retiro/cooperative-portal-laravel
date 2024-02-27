<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}

// Include config file
require_once "connection.php";

// Initialize user_id to null
$user_id = null;

// Account side
// Processing form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the account number and password from the form data
    $account_number = $_POST["account_number"];
    $password = $_POST["password"];
    $acc_type = $_POST["role"];

    // Hash the password just before inserting into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserting data into the "users" table to store the default password
    $sql = "INSERT INTO users (account_number, passwords, role) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $account_number, $hashed_password, $acc_type);
        if ($stmt->execute()) {
            // Successfully inserted user data into the 'users' table
            $user_id = mysqli_insert_id($conn); // Get the user_id

            // Close the first statement
            $stmt->close();

            // Save the user data into the session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['account_number'] = $account_number;

            // Now, proceed to insert data into the "clients" table
            $sql = 'INSERT INTO clients (user_id, account_number, last_name, middle_name, first_name, birth_date, account_status, position) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

            if ($stmt = $conn->prepare($sql)) { 
                // Set parameters for client data
                $account_number = $_POST['account_number'];
                $lastName = $_POST["last_name"];
                $middleName = $_POST["middle_name"];
                $firstName = $_POST["first_name"];
                $birth_date = $_POST["birth_date"];
                $account_status = $_POST["account_status"];
                $position = $_POST["position"];

                $stmt->bind_param('iissssss', $user_id, $account_number, $lastName, $middleName, $firstName, $birth_date, $account_status, $position);                
                if ($stmt->execute()) {
                    $response["status"] = 'success';
                    $response["message"] = 'Success';
                } else {
                    $response["status"] = 'fail';
                    $response["message"] = 'Fail to execute';
                }

                $stmt->close();
            }
        } else {
            $response["status"] = 'fail';
            $response["message"] = 'Fail to execute';
        }
    } else {
        $response["status"] = 'fail';
        $response["message"] = 'Fail to prepare statement';
    }

    // Close connection
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>