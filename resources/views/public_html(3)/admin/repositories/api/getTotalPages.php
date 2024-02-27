<?php
require_once "connection.php";


$sql = "SELECT COUNT(*) as total FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE users.role = 'mem'";
$stmt = $conn->prepare($sql);

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_records = $row['total'];

$items_per_page = 10;
$total_pages = ceil($total_records / $items_per_page);

echo json_encode(['total_pages' => $total_pages]);
?>