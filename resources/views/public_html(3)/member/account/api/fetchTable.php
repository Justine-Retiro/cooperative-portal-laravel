<?php
$sql = "SELECT * FROM loan_payments WHERE account_number IN (SELECT account_number FROM clients WHERE user_id = " . $_SESSION['user_id'] . ") ORDER BY payment_id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['audit_description'] . "</td>";
    echo "<td>" . date("M d, Y", strtotime($row['payment_date'])) . "</td>";
    echo "<td>" . $row['remarks'] . "</td>";
    
    echo "<td>" . $row['amount_paid'] . "</td>";
    
    if (!empty($row['note'])) {
        echo "<td><button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#noteModal' data-note='" . $row['note'] . "'>View Note</button></td>";
    } else {
      echo "<td></td>";
    }
    echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No records have been found.</td></tr>";
}
?>