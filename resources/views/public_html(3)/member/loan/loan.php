<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../api/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loan overview</title>
    <!-- CDN's -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/172203/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Style -->
    <link rel="stylesheet" href="style.css" />
</head>
  <body>
    <nav class="navbar navbar-default no-margin">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header fixed-brand px-4">
        
        <a class="navbar-brand" href="#">NEUST Credit Cooperative Partners</a>
        <button
          type="button"
          class="btn navbar-toggle collapsed"
          data-toggle="collapse"
          id="menu-toggle">
          <i class="bi bi-list"></i>
        </button>
      </div>
      <!-- navbar-header-->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li>
            <button
              class="navbar-toggle collapse in"
              data-toggle="collapse"
              id="menu-toggle-2"
            >
              <span
                class="glyphicon glyphicon-th-large"
                aria-hidden="true"
              ></span>
            </button>
          </li>
        </ul>
      </div>
      <!-- bs-example-navbar-collapse-1 -->
    </nav>
    <div id="wrapper">
      <!-- Sidebar -->
        <?php
          include '../components/sidebar.php';
        ?>
        <!-- /#sidebar-wrapper -->

        <!-- Content -->

        <div class="page-content-wrapper">
            <div class="container-fluid xyz p-4">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-9 py-3">
                        <h1 id="user-greet">Loan Overview</h1>
                    </div>
                </div>
                <div class="row d-flex">
                    <div class="row d-flex ps-4">
                        <div class="col-lg-4 border w-auto  me-2 " style="border-radius: 10px;">
                            <div class="row d-flex py-1  align-items-center">
                              <div class="col-md-3 w-auto" >
                                <button class="btn text-primary-emphasis fw-medium" onclick="filterLoans('pending')">Pending <span><?php
                                    require_once __DIR__ ."/api/connection.php";
                                
                                    // Get the user_id of the currently logged in user
                                    $user_id = $_SESSION['user_id'];
                                
                                    // Prepare the SQL query
                                    $query = "SELECT COUNT(application_status) AS pending FROM loan_applications WHERE application_status = 'Pending' AND account_number IN (SELECT account_number FROM clients WHERE user_id = ?)";
                                
                                    // Prepare the statement
                                    $stmt = $conn->prepare($query);
                                
                                    // Bind the user_id to the statement
                                    $stmt->bind_param("i", $user_id);
                                
                                    // Execute the statement
                                    $stmt->execute();
                                
                                    // Get the result
                                    $result = $stmt->get_result();
                                
                                    // Check if the query was successful
                                    if ($result) {
                                        // Fetch the result as an associative array
                                        $row = $result->fetch_assoc();
                                
                                        // Access the count of pending applications
                                        $totalPending = $row['pending'];
                                
                                        // Output the count
                                        echo $totalPending;
                                    } else {
                                        // Handle the query error
                                        echo "Error: " . $conn->error;
                                    }
                                ?>
                            </span></button>
                              </div>
                              <div class="col-md-3 w-auto" >
                                <button class="btn text-success fw-medium" onclick="filterLoans('accepted')">Accepted <span><?php
                                    // Include the connection.php file
                                    require_once __DIR__ ."/api/connection.php";
                                
                                    // Get the user_id of the currently logged in user
                                    $user_id = $_SESSION['user_id'];
                                
                                    // Prepare the SQL query
                                    $query = "SELECT COUNT(application_status) AS accepted FROM loan_applications WHERE application_status = 'Accepted' AND account_number IN (SELECT account_number FROM clients WHERE user_id = ?)";
                                
                                    // Prepare the statement
                                    $stmt = $conn->prepare($query);
                                
                                    // Bind the user_id to the statement
                                    $stmt->bind_param("i", $user_id);
                                
                                    // Execute the statement
                                    $stmt->execute();
                                
                                    // Get the result
                                    $result = $stmt->get_result();
                                
                                    // Check if the query was successful
                                    if ($result) {
                                        // Fetch the result as an associative array
                                        $row = $result->fetch_assoc();
                                
                                        // Access the count of accepted applications
                                        $totalAccepted = $row['accepted'];
                                
                                        // Output the count
                                        echo $totalAccepted;
                                    } else {
                                        // Handle the query error
                                        echo "Error: " . $conn->error;
                                    }
                                ?></span></button>
                              </div>
                              <div class="col-md-3 w-auto" >
                                <button class="btn text-danger fw-medium" onclick="filterLoans('rejected')">Rejected <span><?php
                                        // Assuming you have established a database connection
                                        // Include the connection.php file
                                        require_once __DIR__ ."/api/connection.php";
                                    
                                        // Get the user_id of the currently logged in user
                                        $user_id = $_SESSION['user_id'];
                                    
                                        // Prepare the SQL query
                                        $query = "SELECT COUNT(application_status) AS rejected FROM loan_applications WHERE application_status = 'Rejected' AND account_number IN (SELECT account_number FROM clients WHERE user_id = ?)";
                                    
                                        // Prepare the statement
                                        $stmt = $conn->prepare($query);
                                    
                                        // Bind the user_id to the statement
                                        $stmt->bind_param("i", $user_id);
                                    
                                        // Execute the statement
                                        $stmt->execute();
                                    
                                        // Get the result
                                        $result = $stmt->get_result();
                                    
                                        // Check if the query was successful
                                        if ($result) {
                                            // Fetch the result as an associative array
                                            $row = $result->fetch_assoc();
                                    
                                            // Access the count of rejected applications
                                            $totalRejected = $row['rejected'];
                                    
                                            // Output the count
                                            echo $totalRejected;
                                        } else {
                                            // Handle the query error
                                            echo "Error: " . $conn->error;
                                        }
                                    ?></span></button>
                              </div>
                              
                            </div>
                      </div>
                      <!--Apply btn-->
                      <div class="col-lg-3 ps-0 d-flex align-items-center float-end">
                        <a class="btn btn-primary my-2 " href="/member/loan/application/application.php" role="button">
                            <i class="bi bi-credit-card-2-front"></i> &nbsp;Apply loan
                        </a>
                    </div>
                </div>
                
                <!--Main Content-->
                <div class="col-md-12 mt-md-3">
                    <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h3 class="pt-2">Loan Trails</h3>
                          <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle p-0" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                              <li><button class="dropdown-item" onclick="location.reload();">Refresh</button></li>
                            </ul>
                          </div>
                        </div>

                        <div class="table-responsive">
                        <table class="table table-hover table-fixed table-lock-height">
                          <thead class="table-primary">
                            <tr>
                              <th>Loan No.</th>
                              <th>Loan Type</th>
                              <th>Date</th>
                              <th>Loan Amount</th>
                              <th>Amount pay</th>
                              <!-- <th>Due Date</th> -->
                              <th >Loan status</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php
                                include 'api/fetchLoanTable.php';
                              ?>
                          </tbody>
                        </table>
                      </div>
                  </div>
                </div>

                <!-- Modal -->
                  <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="noteModalLabel">View note</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="noteModalBody">
                          <!-- Note will be inserted here by JavaScript -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- End modal -->
                </div>
            </div>
        </div>

<!-- CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn2.hubspot.net/hubfs/476360/utils.js"></script>
<!-- Sidebar Script -->
<script src="script.js"></script>
<script>
$(document).ready(function() {
    $('#noteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var note = button.data('note'); // Extract note from data-* attributes
        var modal = $(this);
        modal.find('.modal-body').text(note);
    });
});
</script>
</body>
</html>