<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
session_start();
require_once "connection.php";

$query = $_GET['query'];

$sql = "SELECT clients.*, users.* FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE (clients.account_number LIKE ? OR first_name LIKE ? OR middle_name LIKE ? OR last_name LIKE ? OR remarks LIKE ?) AND users.role = 'mem'";
$stmt = $conn->prepare($sql);

$searchTerm = '%' . $query . '%';
$stmt->bind_param('sssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm ,$searchTerm);

$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table' style='font-size: large;'>";
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

// Output the records as HTML
$counter = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $counter . "</td>";
        echo "<td> <a class='text-decoration-none text-primary' href='/Admin/Repositories/Edit/edit.php?account_number=" . $row["account_number"] . "'>" . $row["account_number"] . "</td>";
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
    echo "<tr><td colspan='6'>No records have been found.</td></tr>";
}
echo "</table>";
?>