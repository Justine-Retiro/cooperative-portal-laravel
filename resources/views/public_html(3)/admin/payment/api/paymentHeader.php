<?php
if (isset($_GET["account_number"])) {
    $account_number = $_GET["account_number"];
    $query = "SELECT clients.*, users.* FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE users.role = 'mem'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    }
?>