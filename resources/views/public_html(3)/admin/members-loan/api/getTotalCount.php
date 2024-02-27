<?php
require_once __DIR__ . "/connection.php";

// Prepare the SQL query
$query = "SELECT COUNT(appointment_status) AS all FROM clients";

// Execute the query
$result = mysqli_query($conn, $query);

if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);

    // Access the count of total entries
    $totalEntry = $row['all'];

    // Output the count
    echo $totalEntry;
} else {
    // Handle the query error
    echo "Error: " . mysqli_error($conn);
}
?>
