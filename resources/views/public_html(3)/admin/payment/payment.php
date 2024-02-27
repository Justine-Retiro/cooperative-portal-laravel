<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
require_once __DIR__ . "/api/connection.php";
include 'api/paymentHeader.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Repositories</title>
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

    <link rel="stylesheet" href="payment.css">
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
                  Payment Repositories
                </h1>
                <div class="row" style="margin-top: 2em;">
                  <!-- Table -->
                  <div class="row">
                    
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="row">
                          <div class="col-lg-11">
                            <div class="col-lg-3" id="search-top-bar">
                              <div class="input-group" >
                                <input class="form-control border rounded " type="text" placeholder="Search" id="search-input">
                              </div>
                              <!-- <a href="/Admin/Repositories/New/member.php"><button class="btn btn-success btn-lg" id="add-mem" style="float: right;">Add</button></a> -->
                          </div>
                          </div>
                        </div>
                        
                      </div>
                        <div class="table-responsive" id="client-repositories">
                          <table class="table table-hover table-fixed table-lock-height" style="font-size: large;" >
                                <thead>
                                <tr>
                                    <th class='fw-medium'>#</th>
                                    <th class='fw-medium'>Account Number</th>
                                    <th class='fw-medium'>Name</th>
                                    <th class='fw-medium'>Balance</th>
                                    <th class='fw-medium'>Remarks</th>
                                    <th class='fw-medium'>Status</th>
                                    <th class='fw-medium'>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                include 'api/fetchPayment.php';
                                ?>
                                </tbody>
                          </table>  
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn2.hubspot.net/hubfs/476360/Chart.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>
<!-- Generate Account-->
<script src="/admin/repositories/static/generate.js"></script>
<script>
  // Searching Data
$('#search-input').on('keyup', function() {
        var query = $(this).val();
        searchLoans(query);
    });

  function searchLoans(query) {
    $.ajax({
        url: '/Admin/Payment/api/searchPayments.php',
        type: 'GET',
        data: { query: query },
        success: function(data) {
            $('#client-repositories').html(data);
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        }
    });
}
</script>
</body>
</html>
