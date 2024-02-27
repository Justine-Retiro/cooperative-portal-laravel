<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
require_once __DIR__ . "/api/connection.php";
include 'api/repositoriesHeader.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Repositories</title>
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

    <link rel="stylesheet" href="repositories.css">
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
                  Members Repositories
                </h1>
                <div class="row" style="margin-top: 2em;">
                  <!-- Table -->
                  <div class="row">
                    
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="row">
                          <div class="col-lg-11">
                            <div class="col-lg-3" id="search-top-bar">
                            <div class="row d-flex justify-content-between">
                            <div class="input-group" >
                                  <input class="form-control border rounded" type="text" placeholder="Search" id="search-input">
                                </div>
                              <!-- Search bar -->
                              <div class="col-lg-3 d-flex" id="search-top-bar">
                                  <div class="row py-1 mb-2 d-flex align-items-center">
                                    <div class="col-md-3  w-auto" >
                                      <button class="btn text-primary fw-medium filter-btn" data-role="all">All <span>
                                        <?php
                                      // Assuming you have established a database connection

                                      // Include the connection.php file
                                      require_once __DIR__ ."/api/connection.php";
                                      // Prepare the SQL query
                                      $query = "SELECT COUNT(*) AS allUser FROM users";

                                      // Execute the query
                                      $result = mysqli_query($conn, $query);

                                      // Check if the query was successful
                                      if ($result) {
                                          // Fetch the result as an associative array
                                          $row = mysqli_fetch_assoc($result);

                                          // Access the count of total members
                                          $totalUsers = $row['allUser'];

                                          // Output the count
                                          echo $totalUsers . "" ;
                                      } else {
                                          // Handle the query error
                                          echo "Error: " . mysqli_error($conn);
                                      }
                                      ?></span></button>
                                    </div>
                                    <div class="col-md-3 w-auto" >
                                      <button class="btn text-primary-emphasis fw-medium filter-btn" data-role="mem">Members <span><?php
                                      // Assuming you have established a database connection

                                      // Include the connection.php file
                                      require_once __DIR__ ."/api/connection.php";
                                      // Prepare the SQL query
                                      $query = "SELECT COUNT(*) AS member FROM users WHERE role = 'mem' ";

                                      // Execute the query
                                      $result = mysqli_query($conn, $query);

                                      // Check if the query was successful
                                      if ($result) {
                                          // Fetch the result as an associative array
                                          $row = mysqli_fetch_assoc($result);

                                          // Access the count of total members
                                          $totalMember = $row['member'];

                                          // Output the count
                                          echo $totalMember . "" ;
                                      } else {
                                          // Handle the query error
                                          echo "Error: " . mysqli_error($conn);
                                      }
                                      ?>
                                  </span>
                                </button>
                                    </div>
                                    <div class="col-md-3 w-auto" >
                                      <button class="btn text-success fw-medium filter-btn" data-role="admin">Admin <span>
                                        <?php
                                      // Assuming you have established a database connection

                                      // Include the connection.php file
                                      require_once __DIR__ ."/api/connection.php";
                                      // Prepare the SQL query
                                      $query = "SELECT COUNT(*) AS admin FROM users WHERE role = 'admin' ";

                                      // Execute the query
                                      $result = mysqli_query($conn, $query);

                                      // Check if the query was successful
                                      if ($result) {
                                          // Fetch the result as an associative array
                                          $row = mysqli_fetch_assoc($result);

                                          // Access the count of total members
                                          $totalAdmin = $row['admin'];

                                          // Output the count
                                          echo $totalAdmin . "" ;
                                      } else {
                                          // Handle the query error
                                          echo "Error: " . mysqli_error($conn);
                                      }
                                      ?></span></button>
                                    </div>
                                    <div class="col-md-3 w-auto" >
                                      <button class="btn text-success fw-medium filter-btn" data-role="master">Master <span>
                                        <?php
                                      // Assuming you have established a database connection

                                      // Include the connection.php file
                                      require_once __DIR__ ."/api/connection.php";
                                      // Prepare the SQL query
                                      $query = "SELECT COUNT(*) AS master FROM users WHERE role = 'master' ";

                                      // Execute the query
                                      $result = mysqli_query($conn, $query);

                                      // Check if the query was successful
                                      if ($result) {
                                          // Fetch the result as an associative array
                                          $row = mysqli_fetch_assoc($result);

                                          // Access the count of total members
                                          $totalMaster = $row['master'];

                                          // Output the count
                                          echo $totalMaster . "" ;
                                      } else {
                                          // Handle the query error
                                          echo "Error: " . mysqli_error($conn);
                                      }
                                      ?></span></button>
                                    </div>

                                  </div>
                              </div>
                               <!-- /Search bar -->

                            </div>
                            <div class="col-lg-4">
                              <a href="/master/repositories/new/member.php"><button class="btn btn-primary" id="add-mem" style="float: right;">Add Member</button></a>
                              <a href="/master/repositories/new/admin.php"><button class="btn btn-success" id="add-mem" style="float: right;">Add Admin</button></a>
                            </div>
                              
                            </div>
                          </div>
                        </div>
                        
                      </div>
                        <div class="table table-responsive" id="client-repositories">
                        <table class="table" id="client-table" style="font-size: large;">
                                <tr>
                                  <th class='fw-medium' >#</th> 
                                  <th class='fw-medium' >Account Number</th>
                                  <th class='fw-medium' >Name</th>
                                  <th class='fw-medium' >Birth Date</th>
                                  <th class='fw-medium' >Role</th>
                                  <th class='fw-medium' >Status</th>
                                  <th class='fw-medium' >Actions</th>
                                </tr>
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
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>

<!-- Fetching Search -->
<script>
$(document).ready(function() {
    var currentStatus = 1;

    function getPage(page = 1) {
        var query = $('#search-input').val(); 
        filterUsers('all', page, query); 
    }

    function filterUsers(role, page = 1, query = $('#search-input').val()) {
        $.ajax({
            url: '/master/repositories/api/fetchUsers.php',
            type: 'GET',
            data: { role: role, page: page, query: query },
            success: function(data) {
                $('#client-table').html(data);
                updatePagination(page, role, query); 
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', error);
            }
        });
    }

    function updatePagination(currentPage, role = 'all', query = '') {
        $.ajax({
            url: '/master/repositories/api/getTotalPages.php',
            type: 'GET',
            data: { role: role, query: query },
            success: function(response) {
                var response = JSON.parse(response);
                var total = Number(response.total_pages);
                var paginationHtml = '';
                for (var i = 1; i <= total; i++) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    paginationHtml += `<li class="page-item ${activeClass}"><button class="page-link" data-role="${role}" data-page="${i}" data-query="${query}">${i}</button></li>`;
                }
                $('#pagination').html(paginationHtml);
                attachPaginationEventListeners(); // Attach event listeners after updating HTML
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }

    function attachPaginationEventListeners() {
        $('.page-link').off('click').on('click', function() { 
            var role = $(this).data('role');
            var page = $(this).data('page');
            var query = $(this).data('query');
            filterUsers(role, page, query);
        });
    }

    $('.filter-btn').on('click', function() {
        var role = $(this).data('role');
        filterUsers(role);
    });

    $('#search-input').on('keyup', function() {
        var query = $(this).val();
        filterUsers('all', 1, query); 
    });

    getPage(); 
});
</script>
</body>
</html>
