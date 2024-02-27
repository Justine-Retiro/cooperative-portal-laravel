<?php
// Include the connection.php file
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}

require_once 'connection.php';

$sql = "SELECT clients.first_name, clients.last_name FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE users.user_id = " . $_SESSION['user_id'] . " AND users.role = 'master'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
    }
    // error_log(print_r($_SESSION, true));
} else {
    // error_log(print_r($_SESSION, true));
    // If no results, redirect to login.php
    header("Location: /");
    exit;
}

$sql = "SELECT * FROM clients WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);

// Prepare the SQL query
$query1 = "SELECT COUNT(*) AS total_members FROM clients";
$result1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_assoc($result1);
$totalMembers = $row1['total_members'];

$query2 = "SELECT COUNT(*) AS new_borrowers FROM loan_applications WHERE application_status = 'Pending'";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);
$new_borrowers = $row2['new_borrowers'];

$query4 = "SELECT COUNT(*) AS accepted FROM loan_applications WHERE application_status = 'Accepted'";
$result4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($result4);
$accepted = $row4['accepted'];

$query5 = "SELECT COUNT(*) AS rejected FROM loan_applications WHERE application_status = 'Rejected'";
$result5 = mysqli_query($conn, $query5);
$row5 = mysqli_fetch_assoc($result5);
$rejected = $row5['rejected'];

$query3 = "SELECT COUNT(*) AS total_admin FROM users WHERE role = 'Admin'";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_assoc($result3);
$totalAdmin = $row3['total_admin'];
// Close the database connection
mysqli_close($conn);
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
                <h1 id="user-greet">
                  Hi, <?php echo "<span>" . $_SESSION['first_name'] . "</span>"?>
                </h1>
                <h1>
                  Dashboard Overview
                </h1>
                <div class="row " style="margin-top: 2em;">
                  <!-- Requests -->
                  <div class="col-md-2 w-auto mb-3">
                    <div id="card-container" style="width: auto;">
                      <div id="card-title">
                          <table>
                            <tr>
                                <td>New loan request</td>
                            </tr>
                            <tr>
                                <th style="font-size: 25px;"><?php echo $new_borrowers?> Member/s</th>
                            </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 w-auto mb-3">
                    <div id="card-container" style="width: auto;">
                      <div id="card-title">
                          <table>
                            <tr>
                                <td>Total of Accepted Loans</td>
                            </tr>
                            <tr>
                                <th style="font-size: 25px;"><?php echo $accepted?> Application/s</th>
                            </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 w-auto mb-3">
                    <div id="card-container" style="width: auto;">
                      <div id="card-title">
                          <table>
                            <tr>
                                <td>Total of Rejected Loans</td>
                            </tr>
                            <tr>
                                <th style="font-size: 25px;"><?php echo $rejected?> Application/s</th>
                            </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!-- Count -->
                  <div class="col-md-2 w-auto mb-3">
                    <div id="card-container">
                      <div id="card-title">
                        <table>
                          <tr>
                            <td>Total of Members</td>
                          </tr>
                          <tr>
                            <th style="font-size: 25px;"><?php echo $totalMembers?> Member/s</th>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!-- /Requets -->
                  <div class="col-md-2 w-auto mb-3">
                    <div id="card-container">
                      <div id="card-title">
                        <table>
                          <tr>
                            <td>Total of Admin</td>
                          </tr>
                          <tr>
                            <th style="font-size: 25px;"><?php echo $totalAdmin?> Admin/s</th>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
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
