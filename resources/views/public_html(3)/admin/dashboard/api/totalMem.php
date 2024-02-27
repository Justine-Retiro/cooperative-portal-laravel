<?php
// Assuming you have established a database connection
require_once "connection.php";

// Prepare the SQL query
$query = "SELECT COUNT(*) AS total_members FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE users.role = 'mem'";
// SELECT clients.*, users.* FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE users.role = 'mem'
// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);

    // Access the count of total members
    $totalMembers = $row['total_members'];

    // Output the count
    echo "Total Members: " . $totalMembers;
} else {
    // Handle the query error
    echo "Error: " . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);
?>