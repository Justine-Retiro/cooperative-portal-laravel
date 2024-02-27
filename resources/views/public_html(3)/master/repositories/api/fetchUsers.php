<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
require_once "connection.php";

$role = $_GET['role'] ?? 'all';
$query = $_GET['query'] ?? '';
$page = $_GET['page'] ?? 1;
$itemsPerPage = 5;
$offset = ($page - 1) * $itemsPerPage;
$likeQuery = '%' . $query . '%';

if ($role === 'all') {
    $sql = "SELECT clients.*, users.* FROM clients 
            INNER JOIN users ON clients.user_id = users.user_id 
            WHERE (clients.first_name LIKE ? OR clients.middle_name LIKE ? OR clients.last_name LIKE ? OR users.account_number LIKE ? OR users.role LIKE ?) 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssii', $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $itemsPerPage, $offset);
} else {
    $sql = "SELECT clients.*, users.* FROM clients 
            INNER JOIN users ON clients.user_id = users.user_id 
            WHERE users.role = ? AND (clients.first_name LIKE ? OR clients.middle_name LIKE ? OR clients.last_name LIKE ? OR users.account_number LIKE ? OR users.role LIKE ?) 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssii', $role, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery,$itemsPerPage, $offset);
}

$stmt->execute();

if ($stmt->error) {
    die('Error: ' . $stmt->error);
}

$result = $stmt->get_result();

echo "<tr>";
echo "<th class='fw-medium'>#</th>";
echo "<th class='fw-medium'>Account Number</th>";
echo "<th class='fw-medium'>Name</th>";
echo "<th class='fw-medium'>Birth Date</th>";
echo "<th class='fw-medium'>Role</th>";
echo "<th class='fw-medium'>Status</th>";
echo "<th class='fw-medium'>Actions</th>";
echo "</tr>";

// Output the loan applications as HTML
$counter = 1; // Initialize a counter

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $counter . "</td>";
        
        echo "<td>";
        if ($row["role"] === 'admin' || $row["role"] === 'master') {
            echo '<a class="text-decoration-none text-primary" href="/master/repositories/edit/editAdmin.php?account_number=' . $row["account_number"] . '&role=' . $row["role"] . '">' . $row["account_number"] . '</a>';
        } elseif ($row["role"] === 'mem') {
            echo '<a class="text-decoration-none text-primary" href="/master/repositories/edit/edit.php?account_number=' . $row["account_number"] . '">' . $row["account_number"] . '</a>';
        }
        echo "</td>";
        
        
                // echo "<td> <a class='text-decoration-none text-primary' href='/master/repositories/edit/edit.php?account_number=" . $row["account_number"] . "'>     " . $row["account_number"] . "</td>";

        
        
        
        
        echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
        $date = date_create($row["birth_date"]);
        echo "<td>" . date_format($date,"M d Y") . "</td>";
        // Role
        if ($row["role"] === "admin") {
            $role = $row["role"];
            echo "<td class='text-success fw-medium'>" . "Admin" . "</td>";
        } elseif ($row["role"] === "mem") {
            $role = $row["role"];
            echo "<td class='text-primary-emphasis fw-medium'>" . "Member" . "</td>";
        } elseif ($row["role"] === "master") {
            $role = $row["role"];
            echo "<td class='text-success fw-medium'>" . "Master" . "</td>";
        } 
        // -- Role

        if ($row["account_status"] === "Active") {
            echo "<td class='text-success fw-medium'>" . $row["account_status"] . "</td>";
        } elseif ($row["account_status"] === "Inactive" || $row["account_status"] === "Closed" || $row["account_status"] === "Suspended" ) {
            echo "<td class='text-danger fw-medium'>" . "Inactive" . "</td>";
        }
        echo "<td>";
            if($row["role"] === 'admin' || $row["role"] === 'master'){
                echo '<a href="/master/repositories/edit/editAdmin.php?account_number=' . $row["account_number"] . '&role=' . $row["role"] . '"><button class="btn btn-success me-1">Edit</button></a>';
            } else if($row["role"] === 'mem') {
                echo '<a href="/master/repositories/edit/edit.php?account_number=' . $row["account_number"] . '"><button class="btn btn-success me-1">Edit</button></a>';
            }
            echo '<a href="/master/repositories/api/delete.php?account_number=' . $row["account_number"] . '"><button class="btn btn-danger">Delete</button></a>';                                        
        echo "</td>";
        echo "</tr>";

        // Add color coding based on application status
        

        echo "</tr>";

        
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

        $counter++; // Increment the counter for the next row
    }
} else {
    if ($status === 'all') {
        echo "<tr><td colspan='9'>No Repositories found.</td></tr>";
    } else {
        echo "<tr><td colspan='9'>No " . $status . " repository found.</td></tr>";
    }
}


?>