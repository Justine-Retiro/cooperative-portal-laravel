<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
if (isset($_GET["account_number"])) {
  $account_number = $_GET["account_number"];
  
  // Retrieve the account details from the database and display them
  require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/repositories/api/connection.php";

  $query = "SELECT * FROM clients WHERE account_number = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $account_number);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc(); // Fetch the data into an associative array
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $data['first_name'] . ' ' . $data['last_name']; ?> Repository</title>
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
        include '../../components/sidebar.php';
      ?>
      <!-- /#sidebar-wrapper -->

      <!-- Page Content -->
        <div id="page-content-wrapper">
          <div class="container-fluid xyz">
            <div class="row">
              <div class="col-lg-12">
                <h1>
                  Members Details
                </h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/repositories/repositories.php" class="text-decoration-none">Repositories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit member</li>
                  </ol>
                </nav>
                <div class="row" style="margin-top: 2em;">
                    <div class="col-lg-8 col-md-8 col-sm-8 flex-start flex-column">
                        <p class="mb-1">Account Number</p>
                        <!-- Auto generated -->
                        <div class="row">
                          <div class="col-md-8 ps-0 d-flex justify-content-between">
                            <div id="acc-number" class="pb-1 w-auto"><?php echo $account_number; ?>
                              <button class="btn py-0 mb-1" onclick="copyToClipboard('#acc-number')"><i class="bi bi-clipboard"></i></button>
                            </div>
                          </div>
                        </div>
                    </div>

                  <div class="col-lg-12 my-3">
                    <!-- Table -->
                    <!-- Table -->
                    <form id="memberForm" action="/admin/repositories/api/editData.php" method="post" onsubmit="return validateForm()">                  
                      <div class="container">
                        <input type="hidden" name="account_number" value="<?php echo $account_number; ?>">
                      
                        <!-- Row 1 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name">
                          </div>
                          <div class="col-lg-4">
                            <label for="middle_name">Middle Name</label>
                              <input type="text" class="form-control" name="middle_name" id="middle_name">
                          </div>
                          <div class="col-lg-4">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name">
                          </div>
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="city_address">City Address</label>
                            <input type="text" class="form-control" name="city_address" id="city_address">
                          </div>
                          <div class="col-lg-4">
                            <label for="phone_num">Phone Number</label>
                            <input type="text" class="form-control" name="phone_num" id="phone_num">
                          </div>
                          <div class="col-lg-4">
                            <label for="position">Work Position</label>
                            <input type="text" class="form-control" name="position" id="position">
                          </div>
                        </div>
                        
                        <!-- Row 3 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="civil_status">Civil Status</label>
                            <select class="form-control" name="civil_status" id="civil_status">
                              <option select>Select one</option>  
                              <option>Single</option>
                              <option>Married</option>
                              <option>Divorced</option>
                              <option>Widowed</option>
                            </select>
                          </div>
                          <div class="col-lg-4">
                            <label for="spouse_name">Name of Spouse</label>
                              <input type="text" class="form-control" name="spouse_name" id="spouse_name">
                          </div>
                          <div class="col-lg-4">
                            <label for="provincial_address">Provincial Address</label>
                            <input type="text" class="form-control" name="provincial_address" id="provincial_address">
                          </div>
                        </div>
                        
                        <!-- Row 4-->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="citizenship">Citizenship</label>
                            <input type="text" class="form-control" name="citizenship" id="citizenship">
                          </div>
                          <div class="col-lg-4">
                            <label for="birth_date">Date of Birth</label>
                              <input type="date" class="form-control" name="birth_date" id="birth_date">
                          </div>
                          <div class="col-lg-4">
                            <label for="birth_place">Place of Birth</label>
                            <input type="text" class="form-control" name="birth_place" id="birth_place">
                          </div>
                        </div>
                        <!-- Row 5-->
                        <div class="row mb-3">
                            <div class="col-lg-4">
                              <label for="taxID_num">Tax Identification Number</label>
                              <input type="text" class="form-control" name="taxID_num" id="taxID_num">
                            </div>
                            <div class="col-lg-4">
                              <label for="mailing_address">Mailing Address</label>
                              <input type="text" class="form-control" name="mailing_address" id="mailing_address">
                            </div>
                            <div class="col-lg-4">
                              <label for="date_employed">Date of Employment</label>
                              <input type="date" class="form-control" name="date_employed" id="date_employed">
                            </div>
                        </div>
                        <!-- Row 6-->
                        <div class="row mb-3">
                            <div class="col-lg-4">
                              <label for="natureOf_work">Nature of Work</label>
                              <select class="form-control" name="natureOf_work" id="natureOf_work">
                                <option>Teaching</option>
                                <option>Non-Teaching</option>
                                <option>Others</option>
                              </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                              <label for="account_status">Account Status</label>
                              <select class="form-control" name="account_status" id="account_status">
                                <option>Active</option>
                                <option>Not Active</option>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="amountOf_share">Amount of shares</label>
                              <input type="text" class="form-control" name="amountOf_share" id="amountOf_share">
                              <button class="btn btn-success btn-lg mt-3" id="toast-submit" type="submit" style="float: right;">Save</button>
                            </div>
                          </div>
                        
                      </form>
                      <!-- /Table -->
                  </div>
                  
                  
                  <!-- Toaster -->
                  <div class="toast-container position-fixed bottom-0 end-0 p-3">
                  <div id="clipboard" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                      <span class="rounded bg-success px-1 me-2"><i class="bi bi-check2 text-success"></i></span>
                      <strong class="me-auto">Clipboard</strong>
                      <small id="timestamp"></small>
                      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                      Copied successfully
                    </div>
                  </div>
                </div>
                  <!-- /Toaster -->
                  
                  
                  <!-- Fetching data -->
                  
                <div class="container">
                    <div class="row mb-3">
                          <div class="col-lg-4">
                            <p><strong>Last Name:</strong> <span id="lastNameText"><?php echo $data['last_name']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Middle Name:</strong> <span id="middleNameText"><?php echo $data['middle_name']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>First Name:</strong> <span id="firstNameText"><?php echo $data['first_name']; ?></span></p>
                          </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <p><strong>City Address:</strong> <span id="cityAddressText"><?php echo $data['city_address']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Phone Number:</strong> <span id="contactAddressText"><?php echo $data['phone_num']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Work Position:</strong> <span id="workPositionText"><?php echo $data['position']; ?></span></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <p><strong>Civil Status:</strong> <span id="civil_status"><?php echo $data['civil_status']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Name of Spouse:</strong> <span id="spouseNameText"><?php echo $data['spouse_name']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Provincial Address:</strong> <span id="provincialAddressText"><?php echo $data['provincial_address']; ?></span></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <p><strong>Citizenship:</strong> <span id="citizenshipText"><?php echo $data['citizenship']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Date of Birth:</strong> <span id="dateOfBirthText"><?php echo $data['birth_date']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Place of Birth:</strong> <span id="placeOfBirthText"><?php echo $data['birth_place']; ?></span></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <p><strong>Tax Identification Number:</strong> <span id="taxIdentificationNumberText"><?php echo $data['taxID_num']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Mailing Address:</strong> <span id="mailingAddressText"><?php echo $data['mailing_address']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Date of Employment:</strong> <span id="date_employed"><?php echo $data['date_employed']; ?></span></p>
                            </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <p><strong>Nature of work:</strong> <span id="natureOfWork"><?php echo $data['natureOf_work']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Account Status:</strong> <span id="natureOfWork"><?php echo $data['account_status']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Amount of shares:</strong> <span id="amountOfshares"><?php echo $data['amountOf_share']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Balance:</strong> <span id="balance"><?php echo $data['balance']; ?></span></p>
                            </div>
                            <div class="col-lg-4">
                            <p><strong>Remarks:</strong> <span id="remarks"><?php echo $data['remarks']; ?></span></p>
                        </div>
                    </div>
                </div>
                  <!-- /Fetching Data -->
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>
<!-- Fetching data -->
<script src="/admin/repositories/static/fetch.js"></script>
<!-- Clipboard -->
<script src="/admin/repositories/static/clipboard.js"></script>
</body>
</html>
