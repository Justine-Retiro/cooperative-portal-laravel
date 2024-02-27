<?php
require_once "connection.php";

$status = $_GET['status'] ?? 'all';
$sort = $_GET['sort'] ?? 'ASC'; // Default sort order
$query = $_GET['query'] ?? ''; // Receive the search query
$searchTerm = '%' . $query . '%';

// Validate $sort to prevent SQL injection
$allowedSortValues = ['ASC', 'DESC'];
if (!in_array($sort, $allowedSortValues)) {
    $sort = 'ASC'; // Default to 'ASC' if an invalid value is provided
}

// Adjust the SQL query to include a search condition
if ($status === 'all') {
    $sql = "SELECT COUNT(*) as total FROM clients c INNER JOIN loan_applications la ON c.account_number = la.account_number
            WHERE (la.customer_name LIKE ? OR la.account_number LIKE ? OR la.college LIKE ? OR la.loan_type LIKE ? OR la.application_status LIKE ? OR la.loanNo LIKE ?)
            ORDER BY la.created_at " . $sort;
} else {
    $sql = "SELECT COUNT(*) as total FROM clients c INNER JOIN loan_applications la ON c.account_number = la.account_number WHERE la.application_status = ? AND (la.customer_name LIKE ? OR la.account_number LIKE ? OR la.college LIKE ? OR la.loan_type LIKE ? OR la.application_status LIKE ? OR la.loanNo LIKE ?)
        ORDER BY la.created_at " . $sort;
}

$stmt = $conn->prepare($sql);

// Bind parameters based on whether a status filter is applied
if ($status !== 'all') {
    $stmt->bind_param('sssssss', $status, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_records = $row['total'];

$items_per_page = 20;
$total_pages = ceil($total_records / $items_per_page);

echo $total_pages;
?>