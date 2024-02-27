<?php
require_once __DIR__ . "/connection.php";


$page = $_GET['page'] ?? 1;
$items_per_page = 10; 
$offset = ($page - 1) * $items_per_page; 

$sql = "SELECT clients.*, users.* FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE users.role = 'mem' LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $items_per_page, $offset);
$stmt->execute();

$result = $stmt->get_result();

echo "<tr>";
echo "<th class='fw-medium'>#</th>";
echo "<th class='fw-medium'>Account Number</th>";
echo "<th class='fw-medium'>Name</th>";
echo "<th class='fw-medium'>Birth Date</th>";
echo "<th class='fw-medium'>Nature of Work</th>";
echo "<th class='fw-medium'>Status</th>";
echo "<th class='fw-medium'>Amount of share</th>";
echo "<th class='fw-medium'>Actions</th>";
echo "</tr>";

$counter = 1;

if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $counter . "</td>";
    echo "<td> <a class='text-decoration-none text-primary' href='/admin/repositories/edit/edit.php?account_number=" . $row["account_number"] . "'>" . $row["account_number"] . "</td>";
    echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
    $date = date_create($row["birth_date"]);
    echo "<td>" . date_format($date,"M d Y") . "</td>";
    
    // -- Role
    echo "<td>" . $row["natureOf_work"] . "</td>";
    if ($row["account_status"] === "Active") {
        echo "<td class='text-success fw-medium'>" . $row["account_status"] . "</td>";
    } elseif ($row["account_status"] === "Inactive") {
        echo "<td class='text-danger fw-medium'>" . "Inactive" . "</td>";
    }
    echo "<td>" . $row["amountOf_share"] . "</td>";

    echo "<td>";
    echo '<a href="/admin/repositories/edit/edit.php?account_number=' . $row["account_number"] . '"><button class="btn btn-success me-1">Edit</button></a>';
    // echo '<a href="/Admin/Repositories/api/delete.php?account_number=' . $row["account_number"] . '"><button class="btn btn-danger">Delete</button></a>';                                        
    echo "</td>";
    echo "</tr>";
    $counter++;
}
} else {
echo "<tr><td colspan='5'>No records found.</td></tr>";
}

?>