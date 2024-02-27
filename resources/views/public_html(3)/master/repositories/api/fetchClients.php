<?php
require_once __DIR__ . "/connection.php";

$sql = "SELECT clients.*, users.role FROM clients JOIN users ON clients.user_id = users.user_id";
$result = $conn->query($sql);

$counter = 1;

if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $counter . "</td>";
    echo "<td> <a class='text-decoration-none text-primary' href='/admin/repositories/edit/edit.php?account_number=" . $row["account_number"] . "'>" . $row["account_number"] . "</td>";
    echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
    $date = date_create($row["birth_date"]);
    echo "<td>" . date_format($date,"M d Y") . "</td>";
    echo "<td>" . $row["role"] . "</td>";

    echo "<td>" . $row["account_status"] . "</td>";

    echo "<td>";
    echo '<a href="/admin/repositories/edit/edit.php?account_number=' . $row["account_number"] . '"><button class="btn btn-success me-1">Edit</button></a>';
    echo '<a href="/admin/repositories/api/delete.php?account_number=' . $row["account_number"] . '"><button class="btn btn-danger">Delete</button></a>';                                        
    echo "</td>";
    echo "</tr>";
    $counter++;
}
} else {
echo "<tr><td colspan='5'>No records found.</td></tr>";
}

?>