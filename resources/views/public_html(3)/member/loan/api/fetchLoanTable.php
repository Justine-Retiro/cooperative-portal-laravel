<?php
$sql = "SELECT * FROM loan_applications WHERE account_number IN (SELECT account_number FROM clients WHERE user_id = " . $_SESSION['user_id'] . ") ORDER BY loan_id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['loanNo'] . "</td>";
        echo "<td>" . $row['loan_type'] . "</td>";
        echo "<td>" . date("M d, Y", strtotime($row['application_date'])) . "</td>";
        echo "<td>" . $row['amount_before'] . "</td>";
        echo "<td>" . $row['amount_after'] . "</td>";
        // echo "<td>" . $row['dueDate'] . "</td>";
        echo "<td>" . $row['application_status'] . "</td>";
        if (!empty($row['note'])) {
            echo "<td><button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#noteModal' data-note='" . $row['note'] . "'>View Note</button></td>";
        } else {
            echo "<td></td>";
        }
        echo "</tr>";
    }
} else {
    echo "<tr>";
    echo "<td colspan='8'>No loan applications found.</td>";
    echo "</tr>";
}
?>