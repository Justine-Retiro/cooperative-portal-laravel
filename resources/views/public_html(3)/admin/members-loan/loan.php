<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
require_once __DIR__ ."/api/connection.php";

  $sql_user = "SELECT * FROM clients";
  $stmt_user = $conn->prepare($sql_user);
  $stmt_user->execute();
  $result_user = $stmt_user->get_result();

  // Fetch the loan applications and the action taken
  $sql = "SELECT c.account_number, la.loanNo, la.customer_name, la.college, la.loan_type, 
          la.application_date, la.application_status, la.amount_before, la.amount_after, la.action_taken
          FROM clients c
          INNER JOIN loan_applications la ON c.account_number = la.account_number";

  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Member's loan</title>
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

    <link rel="stylesheet" href="loan.css">
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

      <!-- Page Content -->
        <div id="page-content-wrapper">
          <div class="container-fluid xyz">
            <div class="row">
              <div class="col-lg-12">
                <h1>
                  Members Loan Requests
                </h1>
                <div class="row" style="margin-top: 2em;">
                  <!-- Table -->
                  <div class="row">
                    
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="row">
                          <div class="col-lg-11">
                            <!-- Whole top bar -->
                            <div class="row d-flex justify-content-between">
                              <div class="col-lg-4 border w-auto pe-4 " style="border-radius: 10px;">
                                <div class="row py-1 d-flex align-items-center">
                                  <div class="col-md-3  w-auto" >
                                    <button class="btn text-primary filter-btn fw-medium" data-status="all">All </button>
                                  </div>
                                  <div class="col-md-3 w-auto" >
                                    <button class="btn text-primary-emphasis filter-btn fw-medium" data-status="pending">Pending <span><?php
                                    // Assuming you have established a database connection

                                    // Include the connection.php file
                                    require_once __DIR__ ."/api/connection.php";
                                    // Prepare the SQL query
                                    $query = "SELECT COUNT(application_status) AS pending FROM loan_applications WHERE application_status = 'Pending' ";

                                    // Execute the query
                                    $result = mysqli_query($conn, $query);

                                    // Check if the query was successful
                                    if ($result) {
                                        // Fetch the result as an associative array
                                        $row = mysqli_fetch_assoc($result);

                                        // Access the count of total members
                                        $totalPending = $row['pending'];

                                        // Output the count
                                        echo $totalPending . "" ;
                                    } else {
                                        // Handle the query error
                                        echo "Error: " . mysqli_error($conn);
                                    }
                                    ?>
                                </span></button>
                                  </div>
                                  <div class="col-md-3 w-auto" >
                                    <button class="btn text-success filter-btn fw-medium" data-status="accepted">Accepted <span><?php
                                    // Assuming you have established a database connection

                                    // Include the connection.php file
                                    require_once __DIR__ ."/api/connection.php";
                                    // Prepare the SQL query
                                    $query = "SELECT COUNT(application_status) AS accepted FROM loan_applications WHERE application_status = 'Accepted' ";

                                    // Execute the query
                                    $result = mysqli_query($conn, $query);

                                    // Check if the query was successful
                                    if ($result) {
                                        // Fetch the result as an associative array
                                        $row = mysqli_fetch_assoc($result);

                                        // Access the count of total members
                                        $totalAccepted = $row['accepted'];

                                        // Output the count
                                        echo $totalAccepted . "" ;
                                    } else {
                                        // Handle the query error
                                        echo "Error: " . mysqli_error($conn);
                                    }
                                    ?></span></button>
                                  </div>
                                  <div class="col-md-3 w-auto" >
                                    <button class="btn text-danger filter-btn fw-medium" data-status="rejected">Rejected <span><?php
                                    // Assuming you have established a database connection

                                    // Include the connection.php file
                                    require_once __DIR__ ."/api/connection.php";
                                    // Prepare the SQL query
                                    $query = "SELECT COUNT(application_status) AS pending FROM loan_applications WHERE application_status = 'Rejected' ";

                                    // Execute the query
                                    $result = mysqli_query($conn, $query);

                                    // Check if the query was successful
                                    if ($result) {
                                        // Fetch the result as an associative array
                                        $row = mysqli_fetch_assoc($result);

                                        // Access the count of total members
                                        $totalPending = $row['pending'];

                                        // Output the count
                                        echo $totalPending . "" ;
                                    } else {
                                        // Handle the query error
                                        echo "Error: " . mysqli_error($conn);
                                    }
                                    ?></span></button>
                                  </div>
                                  
                                </div>
                                
                              </div>
                               <!-- Search bar -->
                              <div class="col-lg-3" id="search-top-bar">
                                <div class="input-group" >
                                  <input class="form-control border rounded" type="text" placeholder="Search" id="search-input">
                                </div>
                              </div>
                               <!-- /Search bar -->
                            </div>
                          </div>
                          <!-- /Whole top bar -->
                          <div class="col-md-2 mt-3">
                              <label for="sort">Sort by:</label>
                          &nbsp;<select id="sort" name="sort" class="form-control">
                                    <option value="desc" selected>Date (DESC)</option>
                                    <option value="asc">Date (ASC)</option>
                                </select>
                              </select>
                          </div>
                          </div>
                        </div>
                        
                      </div>

                      <div class="mt-0 table table-responsive">
                          <table id="loan-applications" class="table" style="font-size: large;">
                          <!-- Reserved -->
                          </table>  
                          
                      </div>
                      <div id="pagination" class="pagination">
                        </div>
                          
                    </div>
                </div>
                  <!-- /Table -->
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>

<script>
$(document).ready(function() {
    var currentStatus = 'all'; 
    var currentSort = 'ASC'; 

    function loadLoans(status = currentStatus, page = 1, query = '', sort = currentSort) {
        currentStatus = status; // Update current status
        currentSort = sort; // Update current sort order
        $.ajax({
            url: '/admin/members-loan/api/fetchLoans.php',
            type: 'GET',
            data: { status: status, page: page, query: query, sort: sort },
            success: function(data) {
                $('#loan-applications').html(data);
                updatePagination(status, page, query, sort);
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', error);
            }
        });
    }


    function updatePagination(status, currentPage, query, sort) {
        $.ajax({
            url: '/admin/members-loan/api/getTotalPages.php',
            type: 'GET',
            data: { status: status, query: query, sort: sort },
            success: function(totalPages) {
                let paginationHtml = '';
                for (let i = 1; i <= totalPages; i++) {
                    let activeClass = (i == currentPage) ? 'active' : '';
                    paginationHtml += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}" data-status="${status}" data-query="${query}" data-sort="${sort}">${i}</a></li>`;
                }
                $('#pagination').html(paginationHtml);
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', error);
            }
        });
    }
    
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        const status = $(this).data('status');
        const query = $(this).data('query');
        const sort = $(this).data('sort');
        loadLoans(status, page, query, sort);
    });

    $('.filter-btn').on('click', function() {
        const status = $(this).data('status'); 
        loadLoans(status, 1, $('#search-input').val(), currentSort); 
    });

    $('#search-input').on('keyup', function() {
        const query = $(this).val().trim();
        loadLoans(currentStatus, 1, query, currentSort); 
    });

$('#sort').on('change', function() {
    const sort = $(this).val();
    loadLoans(currentStatus, 1, $('#search-input').val(), sort); 
});

    // Initial load
    loadLoans(); // Load with default parameters on initial page load
});
</script>
</body>
</html>
