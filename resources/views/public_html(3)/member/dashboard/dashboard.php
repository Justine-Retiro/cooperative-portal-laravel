<?php
include __DIR__ . "/../api/session.php";

// session_start();
// if (!isset($_SESSION['loggedin']) ||  $_SESSION['loggedin'] !== true) {
//     // user is not logged in, redirect them to the login page
//     header('Location: login.php');
//     exit;
// }
require_once __DIR__ . '/../api/connection.php';

$sql = "SELECT * FROM clients WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);

$sql = "SELECT balance FROM clients WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$_SESSION['balance'] = $row['balance'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <!-- CDN's -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/172203/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    <!-- Style -->
    <link rel="stylesheet" href="style.css" />

    <link rel="stylesheet" href="dashboard.css">
</head>
  <body>
    <nav class="navbar navbar-default no-margin">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header fixed-brand mx-4">
        
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
            <div class="row"></div>
              <div class="col-lg-12">
                <h1 id="user-greet">
                  Hi, <?php echo "<span>" . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . "</span>"?>
                </h1>
                <h1>
                  Dashboard Overview
                </h1>
                <div class="row" style="margin-top: 2em;">
                  <!-- Chart -->
                  <div class="col-lg-2 w-auto mb-5">
                    <div id="card-container">
                      <div id="card-title">
                          <table>
                            <tr>
                                <td>Account Balance</td>
                            </tr>
                            <tr>
                              <th style="font-size: 25px;">₱<?php echo number_format($_SESSION["balance"], 2, '.', ',')?></th>
                            </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Reports -->
                  <div class="col-md-12 mt-md-5">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="pt-2">History</h3>
                        <div class="dropdown">
                          <button type="button" class="btn btn-link dropdown-toggle p-0" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><button class="dropdown-item" onclick="location.reload();">Refresh</button></li>
                          </ul>
                        </div>
                      </div>
                      <div class="table-responsive text-nowrap">
                        <table class="table table-fixed table-lock-height">
                            <thead>
                          <tr>
                            <th>Transaction</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
                            $sql = "SELECT * FROM transaction_history WHERE account_number IN (SELECT account_number FROM clients WHERE user_id = " . $_SESSION['user_id'] . ") ORDER BY history_id DESC";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                              while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['audit_description'] . "</td>";
                                echo "<td>" . $row['transaction_type'] . "</td>";
                                echo "<td>" . date("M d, Y", strtotime($row['transaction_date'])) . "</td>";
                                echo "<td>" . $row['transaction_status'] . "</td>";
                                echo "</tr>";
                              }
                            } else {
                              echo "<tr><td colspan='4'>No transactions found</td></tr>";
                            }
                          ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  
                  
                  <!-- /Reports -->
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- Sidebar -->
<script src="script.js"></script>

<!-- Chart -->
<script>
$(document).ready(function() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    // Check if there's a toastr message in the cookie
    $(document).ready(function() {
    // Check if there's a toastr message in the cookie
    var toastrMessage = getCookie('toastr');
    if (toastrMessage) {
        // If there is, show the toastr and delete the cookie
        toastr.success(decodeURIComponent(toastrMessage));
        var now = new Date();
        now.setTime(now.getTime() - 1);
        document.cookie = 'toastr=; expires=' + now.toUTCString() + '; path=/;';
    }
});
});
// Function to get a cookie by name
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
</script>

</body>
</html>
