<?php
session_start();
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['account_number']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}

require_once "../api/connection.php";

// Check if the session variable for account_number is set
if (isset($_SESSION["account_number"])) {
    $account_number = $_SESSION["account_number"];
      
    require_once "../api/connection.php";

    $query = "SELECT * FROM clients WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc(); // Fetch the data into an associative array
    
    $sql = "SELECT users.email 
        FROM users 
        INNER JOIN clients ON users.user_id = clients.user_id 
        WHERE clients.account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['account_number']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <!-- CDN's -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/172203/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

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

      <!-- Page Content -->
        <div id="page-content-wrapper">
          <div class="container-fluid xyz">
            <div class="row">
              <div class="col-lg-12">
                <h2>Profile</h2>
                <div class="d-flex align-items-center">
                  <span>Account Number</span><span class="ms-2 rounded bg-info-subtle px-3 py-2"><?php echo $account_number; ?> </span>
                </div>
                <hr>
                <form id="profileForm" action="/master/profile/api/editData.php" method="post">
                    <div class="row ">
                        <input type="hidden" name="account_number" value="<?php echo trim($account_number); ?>">
                        <div class="col-lg-4 mb-3">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $data['last_name']; ?>">
                        </div>
                        <div class="col-lg-4 mb-3">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" id="middle_name" value="<?php echo $data['middle_name']; ?>">
                        </div>
                        <div class="col-lg-4 mb-3">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $data['first_name']; ?>">
                        </div>
                        <div class="col-lg-4">
                        <label for="birth_date">Date of Birth</label>
                        <input type="date" class="form-control" name="birth_date" id="birth_date" value="<?php echo $data['birth_date']; ?>">
                        </div>
                        
                        <div class="col-lg-4">
                        <label for="position">Work Position</label>
                        <input type="text" class="form-control" name="position" id="position" value="<?php echo $data['position']; ?>">
                        </div>

                        <div class="col-lg-12">
                            <button class="btn btn-success mt-5 float-end changeprofile" type="submit">Save profile</button>
                        </div>
                    </div>

                          <h3>Email</h3>
                          <hr>

                          <div class="row">
                            <div class="col-lg-4">
                                <div class="row w-auto">
                                    <input type="hidden" name="account_number" value="<?php echo trim($account_number); ?>">
                                    <div class="col-lg-12 mb-3">
                                        <label for="email">New Email</label>
                                        <input type="email" class="form-control email-input" name="email" id="email" >
                                    </div>
                                </div>
                                <div class="row w-auto">
                                    <div class="col-lg-12 mb-3">
                                        <label for="confirm_email">Confirm Email</label>
                                        <input type="email" class="form-control email-input" name="confirm_email" id="confirm_email">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-lg-4 mb-2">
                                <div class="alert alert-danger d-none d-flex align-items-center" style=" max-width: 350px; height: 100%;" role="alert" id="alert_email">
                                    <!-- Container for error -->
                                </div>
                            </div>
                            <div class="row w-auto">
                                <div class="col-lg-12 mb-3">
                                    <div >Current email: <div class="text-success"><?php echo $row["email"]; ?> </div> </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-success mt-3 float-end changeemail" type="submit">Confirm</button>
                            </div>
                        </div>
                        
                        <h3>Password</h3>
                          <hr>

                          <div class="row">
                            <div class="col-lg-4">
                                <div class="row w-auto">
                                    <input type="hidden" name="account_number" value="<?php echo trim($account_number); ?>">
                                    <div class="col-lg-12 mb-3">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" class="form-control password-input" name="current_password" id="current_password">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control password-input" name="new_password" id="new_password">
                                    </div>
                                </div>
                                <div class="row w-auto">
                                    <div class="col-lg-12 mb-3">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control password-input" name="confirm_password" id="confirm_password">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <input type="checkbox" id="show_password">
                                    <label for="show_password">Show Password</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="alert alert-danger d-none d-flex align-items-center" style=" max-width: 350px; height: 100%;" role="alert" id="alert_password">
                                    <!-- Container for error -->
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-success mt-5 float-end changepassword" type="submit">Change password</button>
                            </div>
                        </div>
                      <!-- /Table -->
                    </div>
                  </form>
                  
                  <!-- Email Verification Modal -->
                    <form id="code_verify" action="/master/profile/api/editData.php" method="post">
                        <div class="modal fade" id="confirmModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="confirmModalLabel">Verify Email</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="account_number" value="<?php echo trim($account_number); ?>">
                                        <div class="col-lg-12 mb-3">
                                            <label for="text">Verification Code</label>
                                            <input type="text" class="form-control code-input" name="verification_code" id="code" >
                                            <input type="hidden" id="resend_countdown" value="10">
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <p> Didn't get the code?
                                            <span id="resend_code" class="text-primary" disabled>Resend <span id="resend_timer"></span></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="code_verify" class="btn btn-primary">Verify</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>
<script src="static/validation.js"></script>

</body>
</html>
