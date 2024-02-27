<?php
session_start(); // Start the session
require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST["account_number"];
    $query = "UPDATE clients SET";
    $params = array();

    if (!empty($_POST["last_name"])) {
        $query .= " last_name = ?,";
        $params[] = $_POST["last_name"];
    }
    if (!empty($_POST["first_name"])) {
        $query .= " first_name = ?,";
        $params[] = $_POST["first_name"];
    }
    if (!empty($_POST["middle_name"])) {
        $query .= " middle_name = ?,";
        $params[] = $_POST["middle_name"];
    }
    if (!empty($_POST["citizenship"])) {
        $query .= " citizenship = ?,";
        $params[] = $_POST["citizenship"];
    }
    if (!empty($_POST["civil_status"])) {
        $query .= " civil_status = ?,";
        $params[] = $_POST["civil_status"];
    }
    if (!empty($_POST["provincial_address"])) {
        $query .= " provincial_address = ?,";
        $params[] = $_POST["provincial_address"];
    }
    if (!empty($_POST["mailing_address"])) {
        $query .= " mailing_address = ?,";
        $params[] = $_POST["mailing_address"];
    }
    if (!empty($_POST["birth_place"])) {
        $query .= " birth_place = ?,";
        $params[] = $_POST["birth_place"];
    }
    if (!empty($_POST["spouse_name"])) {
        $query .= " spouse_name = ?,";
        $params[] = $_POST["spouse_name"];
    }
    if (!empty($_POST["taxID_num"])) {
        $query .= " taxID_num = ?,";
        $params[] = $_POST["taxID_num"];
    }
    if (!empty($_POST["birth_date"])) {
        $query .= " birth_date = ?,";
        $params[] = $_POST["birth_date"];
    }
    if (!empty($_POST["city_address"])) {
        $query .= " city_address = ?,";
        $params[] = $_POST["city_address"];
    }
    if (!empty($_POST["position"])) {
        $query .= " position = ?,";
        $params[] = $_POST["position"];
    }
    if (!empty($_POST["natureOf_work"])) {
        $query .= " natureOf_work = ?,";
        $params[] = $_POST["natureOf_work"];
    }
    if (!empty($_POST["account_status"])) {
        $query .= " account_status = ?,";
        $params[] = $_POST["account_status"];
    }
    if (!empty($_POST["date_employed"])) {
        $query .= " date_employed = ?,";
        $params[] = $_POST["date_employed"];
    }
    if (!empty($_POST["amountOf_share"])) {
        $query .= " amountOf_share = ?,";
        $params[] = $_POST["amountOf_share"];
    }

    // Remove the trailing comma from the query
    $query = rtrim($query, ",");

    $query .= " WHERE account_number = ?";
    $params[] = $account_number;

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: ../edit/edit.php?account_number=' . $account_number);
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>