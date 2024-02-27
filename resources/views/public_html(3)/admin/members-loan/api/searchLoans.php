<?php
require_once "connection.php";

$query = $_GET['query'];

$sql = "SELECT * FROM loan_applications WHERE account_number LIKE ? OR customer_name LIKE ? OR college LIKE ? OR loan_type LIKE ? 
OR application_status LIKE ? OR loanNo LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();

$counter = 1;

echo "<tr>";
    echo "<th class='fw-medium' >#</th>";
    echo "<th class='fw-medium' >Account Number</th>";
    echo "<th class='fw-medium' >Loan Ref</th>";
    echo "<th class='fw-medium' >Name</th>";
    echo "<th class='fw-medium' >College/Dept</th>";
    echo "<th class='fw-medium' >Type</th>";
    echo "<th class='fw-medium' >Date of applying</th>";
    echo "<th class='fw-medium' >Amount</th>";
    echo "<th class='fw-medium' >Status</th>";
    // echo "<th>Actions</th>";
echo "</tr>";

// Output the loan applications as HTML
if ($result->num_rows > 0){
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $counter ."</td>";
        echo "<td><a class='text-decoration-none text-primary' href='/admin/memberLoan/application/application.php?account_number=" . $row["account_number"] . "&loan_id=" . $row["loanNo"] . "'>" . $row["account_number"] . "</a></td>";        
        echo "<td>" . $row["loanNo"] . "</td>";
        echo "<td>" . $row["customer_name"] . "</td>";
        echo "<td>" . $row["college"] . "</td>";
        echo "<td>" . $row["loan_type"] . "</td>";
        $date = date("F d, Y", strtotime($row["application_date"]));
        echo "<td>" . $date . "</td>";
        echo "<td>" . $row["amount_before"] . "</td>";
        // Add color coding based on application status
        if ($row["application_status"] === "Accepted") {
            echo "<td class='text-success fw-medium'>" . $row["application_status"] . "</td>";
        } elseif ($row["application_status"] === "Rejected") {
            echo "<td class='text-danger fw-medium'>" . $row["application_status"] . "</td>";
        } else {
            echo "<td class='text-primary-emphasis fw-medium'>" . $row["application_status"] . "</td>";
        }
        
        // echo "<td>";
        // if ($row["action_taken"]) {
        //     // If an action has been taken, display the action
        //     echo "<span>" . " " . "</span>";
        // } else {
        //     // If no action has been taken, display the buttons
        //     echo "<button class='accept-btn btn btn-success me-2' data-loan-no='" . $row["loanNo"] . "'>Accept</button>";
        //     echo "<button class='reject-btn btn btn-danger' data-loan-no='" . $row["loanNo"] . "'>Reject</button>";
        // }
        // echo "</td>";
        echo "</tr>";
        $counter++;
    }
} else {
    if ($status === 'all') {
        echo "<tr><td colspan='9'>No borrowers found.</td></tr>";
    } else {
        echo "<tr><td colspan='9'>No loan applications found.</td></tr>";
    }
}

?>