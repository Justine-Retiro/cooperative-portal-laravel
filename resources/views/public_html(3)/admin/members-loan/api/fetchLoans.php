<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "connection.php";

$status = $_GET['status'];
$order = $_GET['sort'] == 'desc' ? 'desc' : 'asc'; // Default to ASC if not DESC
$query = $_GET['query'] ?? ''; // Receive the search query
$page = $_GET['page'] ?? 1;
$items_per_page = 20;
$offset = ($page - 1) * $items_per_page;

$searchTerm = '%' . $query . '%';

// Adjust the SQL query to include a search condition
if ($status === 'all') {
    $sql = "SELECT c.account_number, la.loanNo, la.customer_name, la.college, la.loan_type, 
            la.application_date, la.application_status, la.amount_before, la.amount_after, la.action_taken
            FROM clients c
            INNER JOIN loan_applications la ON c.account_number = la.account_number
            WHERE (la.customer_name LIKE ? OR la.account_number LIKE ? OR la.college LIKE ? OR la.loan_type LIKE ? OR la.application_status LIKE ? OR la.loanNo LIKE ?)
            ORDER BY la.created_at " . $order . " 
            LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $items_per_page);
} else {
    $sql = "SELECT c.account_number, la.loanNo, la.customer_name, la.college, la.loan_type, 
            la.application_date, la.application_status, la.amount_before, la.amount_after, la.action_taken
            FROM clients c
            INNER JOIN loan_applications la ON c.account_number = la.account_number
            WHERE la.application_status = ? AND (la.customer_name LIKE ? OR la.account_number LIKE ? OR la.college LIKE ? OR la.loan_type LIKE ? OR la.application_status LIKE ? OR la.loanNo LIKE ?)
            ORDER BY la.created_at " . $order . " 
            LIMIT ?, ?";
    $stmt = $conn->prepare($sql);  
    $stmt->bind_param('sssssssii', $status, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $items_per_page);
}

// $stmt = $conn->prepare($sql);

// // Bind parameters based on whether a status filter is applied
// if ($status !== 'all') {
//     $stmt->bind_param('sssssssii', $status, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $items_per_page);
// } else {
//     $stmt->bind_param('ssssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $items_per_page);
// }

$stmt->execute();
$result = $stmt->get_result();



echo "<tr>";
echo "<th class='fw-medium'>#</th>";
echo "<th class='fw-medium'>Account Number</th>";
echo "<th class='fw-medium'>Loan Ref</th>";
echo "<th class='fw-medium'>Name</th>";
echo "<th class='fw-medium'>College/Dept</th>";
echo "<th class='fw-medium'>Type</th>";
echo "<th class='fw-medium'>Date of applying</th>";
echo "<th class='fw-medium'>Amount</th>";
echo "<th class='fw-medium'>Status</th>";
// echo "<th class='fw-medium'>Actions</th>";
echo "</tr>";

// Output the loan applications as HTML
$counter = 1; // Initialize a counter

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $counter ."</td>";
        echo "<td><a class='text-decoration-none text-primary' href='/admin/members-loan/application/application.php?account_number=" . $row["account_number"] . "&loan_id=" . $row["loanNo"] . "'>" . $row["account_number"] . "</a></td>";        
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

        $counter++; // Increment the counter for the next row
    }
} else {
    if ($status === 'all') {
        echo "<tr><td colspan='9'>No borrowers found.</td></tr>";
    } else {
        echo "<tr><td colspan='9'>No " . $status . " loan applications found.</td></tr>";
    }
}


?>