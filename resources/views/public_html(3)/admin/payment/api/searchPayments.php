<?php 
require_once __DIR__ . "/connection.php";

$query = $_GET['query'];

$sql = "SELECT * FROM clients WHERE account_number LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR remarks LIKE ? OR balance LIKE ? OR account_status LIKE ?";
$stmt = $conn->prepare($sql);

$searchTerm = '%' . $query . '%';
$stmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table' style='font-size: large;'>";
echo "<tr>";
echo "<th class='fw-medium'>#</th>";
echo "<th class='fw-medium'>Account Number</th>";
echo "<th class='fw-medium'>Name</th>";
echo "<th class='fw-medium'>Balance</th>";
echo "<th class='fw-medium'>Remarks</th>";
echo "<th class='fw-medium'>Status</th>";
echo "<th class='fw-medium'>Actions</th>";
echo "</tr>";

$counter = 1;
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $counter . "</td>";
    echo "<td> <a class='text-decoration-none text-primary' href='/admin/payment/edit/edit.php?account_number=" . $row["account_number"] . "'>" . $row["account_number"] . "</td>";
    echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
    echo "<td>" . $row["balance"] . "</td>";
    echo "<td>" . $row["remarks"] . "</td>";
    echo "<td>" . $row["account_status"] . "</td>";
    echo "<td>";
    echo '<a href="/admin/payment/edit/edit.php?account_number=' . $row["account_number"] . '"><button  class="btn btn-success me-1">Edit</button></a>';
    echo "</td>";
    echo "</tr>";
    $counter++;
}
} else {
echo "<tr><td colspan='5'>No records found.</td></tr>";
}
echo "</table>";
?>