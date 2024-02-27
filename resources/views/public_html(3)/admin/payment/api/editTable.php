<?php
include 'editHeader.php';
          
$sql = "SELECT c.account_number, la.loanNo, la.customer_name, la.college, la.loan_type, 
la.application_date, la.application_status, la.amount_before, la.amount_after, la.remarks, la.balance
FROM clients c
INNER JOIN loan_applications la ON la.account_number = c.account_number
WHERE c.account_number = ? AND la.application_status NOT IN ('Pending', 'Rejected')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $account_number);
$stmt->execute();
$result = $stmt->get_result();
$counter = 1;
if ($result->num_rows > 0){
while ($row = $result->fetch_assoc()) {
    echo "<tr class='loan-row' data-loan-no='{$row["loanNo"]}'>";
    echo "<td>" . $counter . "</td>";
    echo "<td>" . $row["loanNo"] . "</td>";
    echo "<td>" . $row["customer_name"] . "</td>";
    echo "<td>" . $row["loan_type"] . "</td>";
    $date = date("F d, Y", strtotime($row["application_date"]));
    echo "<td>" . $date . "</td>";
    echo "<td>" . $row["amount_before"] . "</td>";
    echo "<td>" . $row["amount_after"] . "</td>";
    echo "<td>" . $row["balance"] . "</td>";
    echo "<td>" . $row["remarks"] . "</td>";
    echo "</tr>";
    $counter++;
}
} else {
echo "<tr><td colspan='9'> No loan history found </td></tr>";
}
?>