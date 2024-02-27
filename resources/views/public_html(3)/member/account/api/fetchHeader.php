<?php
$sql = "SELECT * FROM clients WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);

$sql = "SELECT balance FROM clients WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$_SESSION['balance'] = $row['balance'];

$sql = "SELECT remarks FROM clients WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$_SESSION['remarks'] = $row['remarks'];

$sql = "SELECT amountOf_share FROM clients WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$_SESSION['amountOf_share'] = $row['amountOf_share'];

$sql = "SELECT application_status, remarks FROM loan_applications WHERE account_number = " . $_SESSION['account_number'] . " ORDER BY loan_id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['application_status'] == 'Accepted' && $row['remarks'] == 'Paid') {
        $_SESSION['application_status'] = 'No application status';
    } elseif ($row['application_status'] == 'Rejected') {
        $_SESSION['application_status'] = 'No application status';
    } else {
        $_SESSION['application_status'] = $row['application_status'];
    }
} else {
    $_SESSION['application_status'] = 'No application status'; // or any default value
}
?>