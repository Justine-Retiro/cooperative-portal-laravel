<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../../api/connection.php';

// Generate a random 4-digit loan reference number
$loanRefNo = rand(0, 99999);
$loanRefNo = str_pad(rand(0, 99999), 4, "1", STR_PAD_LEFT);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loan application</title>
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
              id="menu-toggle-2">
              <span
                class="glyphicon glyphicon-th-large"
                aria-hidden="true"></span>
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

        <!-- Content -->

        <div class="page-content-wrapper">
            <div class="container-fluid xyz">
                <div class="row py-md-4 d-flex align-items-center">
                    <div class="col-lg-12">
                      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="/member/loan/loan.php" class="text-decoration-none">Loan</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Application</li>
                        </ol>
                      </nav>
                      
                      <h1 id="user-greet">Application for loan</h1>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <form id="loanForm" action="/member/loan/api/newLoan.php" method="post" enctype="multipart/form-data">                    
                    <div class="row">
                      <div class="col-lg-4">
                            <input type="hidden" name="loanNo" value="<?php echo $loanRefNo; ?>">
                            <input type="hidden" name="application_status" value="Pending">
                            <div class="mb-3">
                              <label for="name">Name</label>  
                              <?php
                              require_once __DIR__ . '/../../api/connection.php';

                              $sql = "SELECT * FROM clients WHERE account_number = '{$_SESSION['account_number']}'";
                              $result = mysqli_query($conn, $sql);
                              $row = mysqli_fetch_assoc($result);
                              $customerName = $row["first_name"] . " " . $row["last_name"];
                              ?>

                              <input type="text" class="form-control" name="customer_name" id="name" value="<?php echo $customerName; ?>">
                            </div>
                            
                            <div class="mb-3">
                              <label for="college">College/Dept</label>  
                              <input type="text" class="form-control" name="college" id="college" placeholder="College/Dept" required>
                            </div>
                            
                            <div class="mb-3">
                              <label for="contact">Contact No.</label>  
                              <input type="number" class="form-control" name="contact" id="contact" placeholder="Contact No." required>
                            </div>
                            
                            <div class="mb-3">
                              <label for="dob">Date of Birth</label>
                              <input type="date" class="form-control" name="dob" id="dob" placeholder="Date of Birth" value="<?php echo $row["birth_date"];?>">
                            </div>

                            <div class="mb-3">        
                              <label for="taxID_num">Tax Identification Number</label>
                              <input type="text" class="form-control" name="taxID_num" id="taxID_num" value="<?php echo $row["taxID_num"]?>">
                            </div>
                      </div>
                      <div class="col-lg-4">
                        
                          <div class="mb-3">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" name="age" id="age" placeholder="Age" required>
                          </div>
                        
                          <div class="mb-3">
                            <label for="doe">Date of Employed</label>  
                            <input type="date" class="form-control" name="doe" id="doe" placeholder="Date of Employed" value="<?php echo $row["date_employed"]; ?>">
                          </div>
                            
                          <div class="mb-3">
                            <label for="retirement">Year of Retirement</label>  
                            <input type="number" class="form-control" name="retirement" id="retirement" placeholder="Year of Retirement" required>
                          </div>
                        
                          <div class="mb-3">
                            <label for="work_position">Work Position</label>  
                            <input type="text" class="form-control" name="work_position" id="work_position" placeholder="Work Position" value="<?php echo $row["position"]; ?>" required>
                          </div>

                          <div class="mb-3">
                            <label for="amount_before">Loan Amount</label>  
                            <input type="number" class="form-control" name="amount_before" id="amount_before" placeholder="Loan Amount" oninput="calculateInterest()">
                          </div>
                            
                      </div>
                      <div class="col-lg-4">

                          <div class="mb-3">
                            <label for="loan_type">Loan Type</label>
                            <select class="form-control" id="loan_type" name="loan_type">
                                <option value="Regular" selected>Regular</option>
                                <option value="Regular renew">Regular w/ renewal</option>
                                <option value="Providential">Providential</option>
                                <option value="Providential renew">Providential w/ renewal</option>
                            </select>
                          </div>

                          <div class="mb-3">
                            <label for="doa">Date of Application</label>  
                            <input type="date" class="form-control" name="doa" id="doa" placeholder="Date of Application" value="<?php echo date('Y-m-d'); ?>">
                          </div>
                          
                          <div class="mb-3">
                            <label for="loan_term_Type">Loan term Type</label>  
                            <select class="form-control" id="loan_term_Type" name="loan_term_Type">
                              <option value="">Select loan type</option>  
                              <option value="month/s">Months</option>
                            </select>
                        </div>
                                                    
                        <div class="mb-3">
                            <label for="time_pay">Loan Term</label>  
                            <input type="number" class="form-control" name="time_pay" id="time_pay" placeholder="Loan Term" oninput="calculateInterest()" disabled required>
                        </div>


                          <div class="mb-3">
                            <label for="amount_after">Amount to pay</label>  
                            <input type="text" class="form-control" name="amount_after" id="amount_after" oninput="calculateInterest()" readonly required>
                          </div>
                      </div>
                      <div class="col-lg-12">
                        <br>
                        <h4 >Terms and agreement</h4>
                        <p>I hereby authorize the NEUST Community Credit Cooperative/NEUST Cashier to deduct
                          the monthly amortization of my loan from my pay slip. 
                          I AGREE THAT ANY LATE PAYMENT
                          WILL BE SUBJECTED TO A PENALTY OF 3% PER MONTH OF DELAY. Furthermore, default in
                          payments for three (3) months will be ground for the coop to take this matter into court and the
                          balance should be due and demandable.</p>
                          
                            <label for="signature" class="mb-2">Upload picture of signature (3 signatures)</label>  
                            <input type="file" class="form-control" name="signature" id="signature" required>
                          <br>
                            <label for="homepay_receipt" class="mb-2">Upload picture of take home pay receipt</label>
                            <input type="file" class="form-control" name="homepay_receipt" id="homepay_receipt" required>
                          <br>
                          <button class="btn btn-primary" type="submit" style="float: right;">Apply</button>
                      </div>

                      <div class="col-lg-12 my-5">
                          <div class="card">
                              <div class="card-header">
                                <h4 class="mt-2">Summary</h4>
                              </div>
                              <div class="card-body">
                                <div class="" id="summary">
                                  <!-- Reserved -->
                                </div>
                            </div>
                          </div>
                      </div>                      
                    </form>
                  </div>
              </div>
            </div>
        </div>
        

<!-- CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn2.hubspot.net/hubfs/476360/utils.js"></script>
<!-- Sidebar Script -->
<script src="../script.js"></script>
<!-- Validation Script-->
<script src="../static/application.js"></script>

</body>
</html>