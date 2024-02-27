<?php
require_once "connection.php";

$role = $_GET['role'] ?? 'all';
$query = $_GET['query'] ?? '';
$itemsPerPage = 5; // Adjust as needed
$likeQuery = '%' . $query . '%';

// Adjusted SQL for counting total matching records
if ($role === 'all') {
    $sql = "SELECT COUNT(*) AS total FROM clients 
            INNER JOIN users ON clients.user_id = users.user_id 
            WHERE (clients.first_name LIKE ? OR clients.middle_name LIKE ? OR clients.last_name LIKE ? OR users.account_number LIKE ? OR users.role LIKE ?)";
} else {
    $sql = "SELECT COUNT(*) AS total FROM clients 
            INNER JOIN users ON clients.user_id = users.user_id 
            WHERE users.role = ? AND (clients.first_name LIKE ? OR clients.middle_name LIKE ? OR clients.last_name LIKE ? OR users.account_number LIKE ? OR users.role LIKE ?)";
}

$stmt = $conn->prepare($sql);

if ($role === 'all') {
    $stmt->bind_param('sssss', $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
} else {
    $stmt->bind_param('ssssss', $role, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalItems = $row['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

echo json_encode(['total_pages' => $totalPages]);

$stmt->close();
$conn->close();
?>