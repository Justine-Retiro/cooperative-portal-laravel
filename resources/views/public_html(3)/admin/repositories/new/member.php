<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
// Generate a random account number and password
$account_number = rand(600000000, 699999999);
$account_number = str_pad($account_number, 9, "6", STR_PAD_LEFT);

$password = "123"; // Default password is "cooperative member" with special characters
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Repository application</title>
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

    <link rel="stylesheet" href="member.css">
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
                    <li class="breadcrumb-item active" aria-current="page">Add member</li>
                  </ol>
                </nav>
                <div class="row mt-1">
                  <div class="row my-2" id="top-nav">
                      <div class="col-lg-8 col-md-8 col-sm-8 flex-start flex-column">
                        <p class="mb-1">Account Number</p>
                        <!-- Auto generated -->
                        <div class="row">
                          <div class="col-md-8 d-flex justify-content-between">
                            <div id="acc-number" class="pb-1 w-auto"><?php echo $account_number; ?>
                              <button class="btn py-0 mb-1" onclick="copyToClipboard('#acc-number')"><i class="bi bi-clipboard"></i></button>
                            </div>
                          </div>
                        </div>
                        
                        <p class="mb-1">Account default password</p>
                        <!-- Auto generated -->
                        <div class="row">
                            
                          <div class="col-md-8 d-flex justify-content-between">
                            <div id="acc-password" class="pb-1 w-auto"><?php echo $password; ?>
                              <button class="btn py-0 mb-1" onclick="copyToClipboard('#acc-password')"><i class="bi bi-clipboard"></i></button>
                            </div>
                          </div>
                        </div>
                  </div>

                  <div class="col-lg-12">
                    <!-- Table -->
                    <form id="memberForm" action="/admin/repositories/api/newEntry.php" method="post" onsubmit="return validateForm()">                  
                      <div class="container mt-3">
                        <input type="hidden" name="account_number" value="<?php echo $account_number; ?>">
                        <input type="hidden" name="password" value="<?php echo $password; ?>">
                        <input type="hidden" name="role" value="mem">
                        
                        <!-- Row 1 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" required>
                          </div>
                          <div class="col-lg-4">
                            <label for="middle_name">Middle Name</label>
                              <input type="text" class="form-control" name="middle_name" id="middle_name" required>
                          </div>
                          <div class="col-lg-4">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" required>
                          </div>
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="city_address">City Address</label>
                            <input type="text" class="form-control" name="city_address" id="city_address" required>
                          </div>
                          <div class="col-lg-4">
                            <label for="phone_num">Phone Number</label>
                            <input type="text" class="form-control" name="phone_num" id="phone_num" required>
                          </div>
                          <div class="col-lg-4">
                            <label for="position">Work Position</label>
                            <input type="text" class="form-control" name="position" id="position" required>
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
                              <input type="text" class="form-control" name="spouse_name" id="spouse_name" required>
                          </div>
                          <div class="col-lg-4">
                            <label for="provincial_address">Provincial Address</label>
                            <input type="text" class="form-control" name="provincial_address" id="provincial_address" required>
                          </div>
                        </div>
                          
                        <div class="row mb-3">
                          <div class="col-lg-4">
                            <label for="citizenship">Citizenship</label>
                            <input type="text" class="form-control" name="citizenship" id="citizenship" required>
                          </div>
                          <div class="col-lg-4">
                            <label for="birth_date">Date of Birth</label>
                              <input type="date" class="form-control" name="birth_date" id="birth_date" required>
                          </div>
                          <div class="col-lg-4">
                            <label for="birth_place">Place of Birth</label>
                            <input type="text" class="form-control" name="birth_place" id="birth_place" required>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-lg-4">
                              <label for="taxID_num">Tax Identification Number</label>
                              <input type="text" class="form-control" name="taxID_num" id="taxID_num" required>
                            </div>
                            <div class="col-lg-4">
                              <label for="mailing_address">Mailing Address</label>
                              <input type="text" class="form-control" name="mailing_address" id="mailing_address" required>
                            </div>
                            <div class="col-lg-4">
                              <label for="date_employed">Date of Employment</label>
                              <input type="date" class="form-control" name="date_employed" id="date_employed" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-4">
                              <label for="natureOf_work">Nature of Work</label>
                              <select class="form-control" name="natureOf_work" id="natureOf_work" required>
                                <option>Teaching</option>
                                <option>Non-Teaching</option>
                                <option>Others</option>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="account_status">Account Status</label>
                              <select class="form-control" name="account_status" id="account_status" required>
                                <option>Active</option>
                                <option>Not Active</option>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="amountOf_share">Amount of shares</label>
                              <input type="text" class="form-control" name="amountOf_share" id="amountOf_share" required>
                            </div>
                          </div>
                        <button class="btn btn-success btn-lg mt-3" id="toast-submit" type="submit" style="float: right;">Save</button>
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

                  <!-- Toaster -->
                  <div class="toast-container position-fixed bottom-0 end-0 p-3">
                  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                      <span class="rounded bg-success px-1 me-2"><i class="bi bi-check2 text-success"></i></span>
                      <strong class="me-auto">Form</strong>
                      <small id="timestamp"></small>
                      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                      Entry has been successfully recorded
                    </div>
                  </div>
                </div>
                  <!-- /Toaster -->
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>
<!-- Fetching data -->
<script src="/admin/repositories/static/fetch.js"></script>
<!-- Clipboard -->
<script src="/admin/repositories/static/clipboard.js"></script>
<!-- Toaster Alert -->
<script>
// Toaster
// Form submission
$(document).ready(function() {
  $('#civil_status').change(function() {
    if ($(this).val() === 'Single') {
      $('#spouse_name').val('None');
    } else {
      $('#spouse_name').val('');
    }
  });
});

$(document).ready(function() {
  $("#memberForm").on("submit", function(e) {
    e.preventDefault();

    // Form validation
    var firstName = document.getElementById("first_name").value;
    var lastName = document.getElementById("last_name").value;
    var citizenship = document.getElementById("citizenship").value;
    var cityAddress = document.getElementById("city_address").value;
    var phoneNumber = document.getElementById("phone_num").value;
    var position = document.getElementById("position").value;

    // Check if the required fields are empty
    if (
      firstName === "" ||
      lastName === "" ||
      citizenship === "" ||
      cityAddress === "" ||
      phoneNumber === "" ||
      position === ""
    ) {
      alert("Please fill out all required fields.");
      return false; // Prevent form submission
    }

    // AJAX request
    $.ajax({
      url: "/admin/repositories/api/newEntry.php",
      type: "post",
      data: $(this).serialize(),
      success: function() {
        console.log("AJAX request success");
        // Show the toaster
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(liveToast)
        toastBootstrap.show()

        // Update the timestamp
        var now = new Date();
        var timestamp = now.getSeconds() + "" + " seconds ago.";
        document.getElementById("timestamp").innerText = timestamp;

        // Redirect to repositories.php after a delay
        setTimeout(function() {
          window.location.href = "/admin/repositories/repositories.php";
        }, 1000); // 2000 milliseconds = 2 seconds
      }
    });
  });
});
</script>



</body>
</html>
