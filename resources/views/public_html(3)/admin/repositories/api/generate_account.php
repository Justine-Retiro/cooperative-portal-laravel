<?php
require_once "connection.php";

// Generate a random account number and password
$account_number = rand(1000000000, 9999999999);
$password = bin2hex(random_bytes(5)); // This will generate a 10-character password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare an insert statement
$sql = "INSERT INTO clients (account_number, password) VALUES (?, ?)";
if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("ss", $account_number, $hashed_password);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Account created successfully. Return the account number and password
        echo json_encode(array("account_number" => $account_number, "password" => $password));
    } else {
        echo "Something went wrong. Please try again later.";
    }
}

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>