<?php

if (isset($_GET["account_number"])) {
    $account_number = $_GET["account_number"];
    $query = "SELECT * FROM clients WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
} else {
    // No search query, retrieve all records
    $sql = "SELECT * FROM clients";
    $result = $conn->query($sql);
}
?>