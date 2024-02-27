<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
include __DIR__ . "/../api/connection.php";

$account_number = $_SESSION['account_number'];

$sql = "SELECT * FROM clients WHERE account_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['account_number']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

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

    <link rel="stylesheet" href="edit.css">
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
                  Profile
                </h1>
                <div class="d-flex align-items-center">
                  <span>Account Number</span><span class="ms-2 rounded bg-info-subtle px-3 py-2"><?php echo $account_number; ?> </span>
                </div>
                <div class="row" style="margin-top: 2em;">
                </div>
                  <!-- Table -->
                  <form action="/member/profile/api/editdata.php" method="post">
                      <div class="container">
                        <input type="hidden" name="account_number" value="<?php echo $account_number; ?>">
                      
                        <h3>Personal Details</h3>
                        <hr>

                        <!-- Row 1 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $data["last_name"] ?>" readonly>
                          </div>
                          <div class="col-lg-4">
                            <label for="middle_name">Middle Name</label>
                              <input type="text" class="form-control" name="middle_name" id="middle_name" value="<?php echo $data["middle_name"] ?>" readonly>
                          </div>
                          <div class="col-lg-4">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $data["first_name"] ?>" readonly>
                          </div>
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="city_address">City Address</label>
                            <input type="text" class="form-control" name="city_address" id="city_address" value="<?php echo $data["city_address"] ?>">
                          </div>
                          <div class="col-lg-4">
                            <label for="phone_num">Phone Number</label>
                            <input type="text" class="form-control" name="phone_num" id="phone_num" value="<?php echo $data["phone_num"] ?>">
                          </div>
                          <div class="col-lg-4">
                            <label for="position">Work Position</label>
                            <input type="text" class="form-control" name="position" id="position" value="<?php echo $data["position"] ?>">
                          </div>
                        </div>
                        
                        <!-- Row 3 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="civil_status">Civil Status</label>
                            <select class="form-control" name="civil_status" id="civil_status">
                              <option value="">Select one</option>  
                              <option value="Single" <?php echo ($data["civil_status"] == 'Single') ? 'selected' : ''; ?>>Single</option>
                              <option value="Married" <?php echo ($data["civil_status"] == 'Married') ? 'selected' : ''; ?>>Married</option>
                              <option value="Divorced" <?php echo ($data["civil_status"] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                              <option value="Widowed" <?php echo ($data["civil_status"] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                            </select>
                          </div>
                          <div class="col-lg-4">
                            <label for="spouse_name">Name of Spouse</label>
                              <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="<?php echo $data["spouse_name"] ?>">
                          </div>
                          <div class="col-lg-4">
                            <label for="provincial_address">Provincial Address</label>
                            <input type="text" class="form-control" name="provincial_address" id="provincial_address" value="<?php echo $data["provincial_address"] ?>">
                          </div>
                        </div>
                        
                        <!-- Row 4-->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="citizenship">Citizenship</label>
                            <input type="text" class="form-control" name="citizenship" id="citizenship" value="<?php echo $data["citizenship"] ?>">
                          </div>
                          <div class="col-lg-4">
                            <label for="birth_date">Date of Birth</label>
                              <input type="date" class="form-control" name="birth_date" id="birth_date" value="<?php echo $data["birth_date"] ?>">
                          </div>
                          <div class="col-lg-4">
                            <label for="birth_place">Place of Birth</label>
                            <input type="text" class="form-control" name="birth_place" id="birth_place" value="<?php echo $data["birth_place"] ?>">
                          </div>
                        </div>
                        <!-- Row 5-->
                        <div class="row mb-3">
                            <div class="col-lg-4">
                              <label for="taxID_num">Tax Identification Number</label>
                              <input type="text" class="form-control" name="taxID_num" id="taxID_num" value="<?php echo $data["taxID_num"] ?>">
                            </div>
                            <div class="col-lg-4">
                              <label for="mailing_address">Mailing Address</label>
                              <input type="text" class="form-control" name="mailing_address" id="mailing_address" value="<?php echo $data["mailing_address"] ?>">
                            </div>
                            <div class="col-lg-4">
                              <label for="date_employed">Date of Employment</label>
                              <input type="date" class="form-control" name="date_employed" id="date_employed" value="<?php echo $data["date_employed"] ?>">
                            </div>
                        </div>
                        <!-- Row 6-->
                        <div class="row mb-3">
                            <div class="col-lg-4">
                              <label for="natureOf_work">Nature of Work</label>
                              <select class="form-control" name="natureOf_work" id="natureOf_work" value="<?php echo $data["natureOf_work"] ?>">
                                <option>Teaching</option>
                                <option>Non-Teaching</option>
                                <option>Others</option>
                              </select>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-success float-end changeprofile" type="submit">Save profile</button>
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
                    <form id="code_verify" action="/member/profile/api/editdata.php" method="post">
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
                                            <input type="hidden" id="resend_countdown" value="60">
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
                  
                  
                  <!-- /Table -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>
<script src="static/validation.js"></script>
</body>
</html>
