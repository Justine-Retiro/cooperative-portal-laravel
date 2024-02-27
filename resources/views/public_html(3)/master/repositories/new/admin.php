<?php
session_start();
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
    <title>New admin</title>
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
                    <li class="breadcrumb-item"><a href="/master/repositories/repositories.php" class="text-decoration-none">Repositories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add member</li>
                  </ol>
                </nav>
                <div class="row" style="margin-top: 2em;">
                  <div class="row" id="top-nav">
                    
                  <div class="col-lg-8" id="acc-nav">
                    <p>Account Number</p>
                    <!-- Auto generated -->
                    <p id="acc-number" class="pb-1"><?php echo $account_number; ?>
                      <button class="btn  py-0 mb-1" onclick="copyToClipboard('#acc-number')"><i class="bi bi-clipboard"></i></button>
                    </p>
                    
                  </div>
                  <div class="col-lg-8" id="acc-nav">
                    <p>Account default password</p>
                    <!-- Auto generated -->
                    <p id="acc-password" class="pb-1"><?php echo $password; ?>
                      <button class="btn py-0 mb-1" onclick="copyToClipboard('#acc-password')"><i class="bi bi-clipboard"></i></button>
                    </p>
                  </div>

                  <!-- Table -->
                  <form id="memberForm" action="/master/repositories/api/newAdmin.php" method="post" onsubmit="return validateForm()">                  
                  <div class="row d-flex justify-content-center">
                    <input type="hidden" name="account_number" value="<?php echo $account_number; ?>">
                    <input type="hidden" name="password" value="<?php echo $password; ?>">

                    <div class="col-lg-4">
                      <label for="lastName">Last Name</label>
                      <input type="text" class="form-control" name="last_name" id="last_name">

                      <label for="middle_name">Middle Name</label>
                      <input type="text" class="form-control" name="middle_name" id="middle_name">

                      <label for="first_name">First Name</label>
                      <input type="text" class="form-control" name="first_name" id="first_name">

                      <label for="birth_date">Date of Birth</label>
                      <input type="date" class="form-control" name="birth_date" id="birth_date">

                    </div>
                    <div class="col-lg-4">
                      <label for="position">Work Position</label>
                      <input type="text" class="form-control" name="position" id="position">

                      <label for="account_status">Account Status</label>
                      <select class="form-control" name="account_status" id="account_status">
                        <option>Active</option>
                        <option>Not Active</option>
                      </select>

                      <label for="role">Role</label>
                      <select class="form-control" name="role" id="role">
                        <option value="admin">Admin</option>
                        <option value="master">Master</option>
                      </select>
                    </div>
                    <div class="col-lg-8">
                    <button class="btn btn-success btn-lg mt-5 float-end" id="toast-submit" type="submit">Save</button>

                    </div>
                  </div>
                  </form>
                  <!-- /Table -->

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>
<!-- Clipboard -->
<script src="/master/repositories/static/clipboard.js"></script>
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
function validateForm() {
    var firstName = document.getElementById("first_name").value;
    var lastName = document.getElementById("last_name").value;

    if (firstName === "" || lastName === "") {
        alert("Please fill out all required fields.");
        return false;
    }

    return true;
}

$(document).ready(function() {
    $("#memberForm").on("submit", function(e) {
        e.preventDefault();

        if (!validateForm()) {
            return; // Stop the function if validation fails
        }

        $.ajax({
            url: "https://cooperative-portal-2324.com/master/repositories/api/newAdmin.php",
            type: "post",
            data: $(this).serialize(),
            dataType: 'json', // Expect JSON response from the server
            success: function(response) {
                if (response.status === 'success') {
                    // Show the toaster
                    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(liveToast)
                    toastBootstrap.show()

                    // Update the timestamp
                    var now = new Date();
                    var timestamp = now.getSeconds() + " seconds ago.";
                    document.getElementById("timestamp").innerText = timestamp;

                    // Redirect to repositories.php after a delay
                    setTimeout(function() {
                        window.location.href = "https://cooperative-portal-2324.com/master/repositories/repositories.php";
                    }, 1000);
                } else {
                    console.log(response.message);
                }
            },
            error: function(xhr, status, error) {
               console.error("Error: " + error);
                console.error("Response Text: " + xhr.responseText);
                // Optionally parse and log the response text if it's JSON
                try {
                    var jsonResponse = JSON.parse(xhr.responseText);
                    console.log(jsonResponse);
                } catch(e) {
                    console.error("Parsing error:", e);
                }
            }
        });
    });
});
</script>



</body>
</html>
