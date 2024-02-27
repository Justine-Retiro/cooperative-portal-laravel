<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
include '../../payment/api/editHeader.php';
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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    
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
                    <li class="breadcrumb-item"><a href="/admin/payment/payment.php" class="text-decoration-none">Payment Repositories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Member Payment</li>
                  </ol>
                </nav>
                <div class="row" style="margin-top: 2em;">
                  <div class="col-lg-12" id="acc-nav">
                  <p>Account Number</p>
                  <!-- Auto generated -->
                  <p id="acc-number"><?php echo $account_number; ?>
                    <button class="btn" onclick="copyToClipboard('#acc-number')"><i class="bi bi-clipboard"></i></button>
                  </p>
                </div>

                <!-- Table Loan -->
                <div class="col-lg-12 px-md-4">
                  <div class="table-responsive">
                    <h2>Loan trails</h2>
                    <button class="btn btn-primary float-end mb-3" onclick="printTable()"><i class="bi bi-printer"></i>&nbsp;Print Table </button>
                    <button class="btn btn-success float-end mx-2 mb-3" onclick="exportTableToExcel('<?php echo $data["last_name"] . '_' . $data["first_name"];?>')"><i class="bi bi-card-text"></i>&nbsp;Export to Excel </button>
                      <table id="loan_trails" class="table table-hover table-bordered table-fixed table-lock-height" >
                        <thead>
                            <tr class="table-primary <?php echo ($row['remarks'] == 'Paid') ? 'paid' : ''; ?>">                              
                              <th>#</th>
                              <th class="fw-semibold">Loan ID</th>
                              <th class="fw-semibold">Customer name</th>
                              <th class="fw-semibold">Loan type</th>
                              <th class="fw-semibold">Date of applying</th>
                              <th class="fw-semibold">Loan borrowed</th>
                              <th class="fw-semibold">Loan to pay</th>
                              <th class="fw-semibold">Balance</th>
                              <th class="fw-semibold">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                          <!-- You can fetch the user's loan data here and loop through it -->
                          <?php
                            include '../../payment/api/editTable.php';
                          ?>
                        </tbody>
                      </table>
                  </div>
                </div>
                <!-- /Table Loan -->
                
                <!-- Table -->
                <form action="/admin/payment/api/editData.php" method="post" onsubmit="saveForm(event)" id="payment-form">                  
                  <div class="row m-3">
                  <h2>Accounting</h2>
                  <p>Loan reference number:</p>

                    <div class="col-md-4 mb-3">
                          <input type="text" class="form-control" id="loanNo" name="loanNo" value="<?php if (isset($loanData["loanNo"])) {echo $loanData["loanNo"];} ?>">
                          <div class="w-auto my-2">
                          <label for="currentBalance">Current Overall Balance</label>
                          <input type="text" class="form-control" id="currentBalance" value="<?php echo $data["balance"]; ?>" disabled readonly>
                            <input type="hidden" id="updated-balance" name="updatedBalance">
                        </div>
                    </div>
                    
                    <input type="hidden" name="account_number" value="<?php echo $account_number; ?>">
                    <input type="hidden" name="application_status" value="None">
                    <div class="col-lg-12">
                      <div class="row d-flex">
                          
                        <div class="col-lg-6 mb-3">
                          <label for="loanBalance">Loan Balance</label>
                          <input type="text" class="form-control" id="loanBalance" value="<?php echo $loanData["loan_balance"]; ?>" disabled readonly>
                        </div>
                        <div class="col-lg-6">
                          <label for="balance">Amount</label>
                          <input type="number" class="form-control" step="any" name="balance" id="balance" required>
                        </div>
                        
                        <div class="col-lg-6">
                          <label for="totalBalance">Total Balance</label>
                          <input type="text" class="form-control" name="totalBalance" id="totalBalance" readonly required>
                        </div>
                        <div class="col-lg-6">
                          <label for="notes">Notes</label>
                          <input type="text" class="form-control" name="notes" rows="3" id="notes"></input>
                        </div>
                      </div>
                      <label for="remarks">Remarks</label>
                      <select class="form-control" name="remarks" id="remarks">
                        <option value="Paid">Paid</option>
                        <option value="Unpaid" >Unpaid</option>
                    </select>
                    <button class="btn btn-success btn-lg" type="submit" style="float: right; margin-top: 10px;">Save</button>
                    </div>
                </form>

                  <!-- /Table -->
                  <!-- Fetching data -->
                  <div class="col-lg-12">
                    <div class="row" >
                      <div class="col-lg-4">
                        <p><strong>Last Name:</strong> <span id="lastNameText"><?php echo $data['last_name']; ?></span></p>
                        <p><strong>Citizenship:</strong> <span id="citizenshipText"><?php echo $data['citizenship']; ?></span></p>
                        <p><strong>Civil Status:</strong> <span id="civil_status"><?php echo $data['civil_status']; ?></span></p>
                        <p><strong>City Address:</strong> <span id="cityAddressText"><?php echo $data['city_address']; ?></span></p>
                        <p><strong>Phone Number:</strong> <span id="contactAddressText"><?php echo $data['phone_num']; ?></span></p>
                        <p><strong>Work Position:</strong> <span id="workPositionText"><?php echo $data['position']; ?></span></p>
                        <p><strong>Balance:</strong> <span id="balance"><?php echo $data['balance']; ?></span></p>

                      </div>
                      <div class="col-lg-4">
                        <p><strong>Middle Name:</strong> <span id="middleNameText"><?php echo $data['middle_name']; ?></span></p>
                        <p><strong>Provincial Address:</strong> <span id="provincialAddressText"><?php echo $data['provincial_address']; ?></span></p>
                        <p><strong>Mailing Address:</strong> <span id="mailingAddressText"><?php echo $data['mailing_address']; ?></span></p>
                        <p><strong>Place of Birth:</strong> <span id="placeOfBirthText"><?php echo $data['birth_place']; ?></span></p>
                        <p><strong>Nature of work:</strong> <span id="natureOfWork"><?php echo $data['natureOf_work']; ?></span></p>
                        <p><strong>Account Status:</strong> <span id="natureOfWork"><?php echo $data['account_status']; ?></span></p>
                        <p><strong>Remarks:</strong> <span id="remarks"><?php echo $data['remarks']; ?></span></p>

                      </div>
                      <div class="col-lg-4">
                        <p><strong>First Name:</strong> <span id="firstNameText"><?php echo $data['first_name']; ?></span></p>
                        <p><strong>Name of Spouse:</strong> <span id="spouseNameText"><?php echo $data['spouse_name']; ?></span></p>
                        <p><strong>Tax Identification Number:</strong> <span id="taxIdentificationNumberText"><?php echo $data['taxID_num']; ?></span></p>
                        <p><strong>Date of Birth:</strong> <span id="dateOfBirthText"><?php echo $data['birth_date']; ?></span></p>
                        <p><strong>Date of Employment:</strong> <span id="date_employed"><?php echo $data['date_employed']; ?></span></p>
                        <p><strong>Amount of shares:</strong> <span id="amountOfshares"><?php echo $data['amountOf_share']; ?></span></p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>

<!-- Sidebar -->
<script src="script.js"></script>
<!-- Clipboard -->
<script src="/admin/repositories/static/clipboard.js"></script>
<!-- Script -->
<script src="/admin/payment/static/calculation_engine.js"></script>
<script src="/admin/payment/static/request_loanId.js"></script>

<script>
function printTable() {
    var divToPrint = document.getElementById("loan_trails"); // replace with your table id
    newWin = window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
}
function exportTableToExcel(userName) {
    $("#loan_trails").tableExport({
        type:'xlsx',
        fileName: userName + '_loan_trails' // this will prefix the file name with the user's name
    }); 
}
</script>
</body>
</html>
