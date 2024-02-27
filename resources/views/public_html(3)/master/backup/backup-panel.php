<?php
require_once __DIR__ . "/api/connection.php";
include '../api/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Backup and recovery</title>
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
    <link rel="stylesheet" href="backup.css">
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
              <h2>Backup authorization verification</h2>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/master/backup/backup.php" class="text-decoration-none">Backup</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Backup panel</li>
                  </ol>
                </nav>

                <div class="col-lg-12 d-flex  align-items-center mt-3" >
                  <div class="form-wrap">
                    <form action="/master/backup/api/database_backup.php" method="post" id="">
                      <div class="form-group">
                        <label class="control-label mb-10" >Host</label>
                        <input type="text" class="form-control" placeholder="Enter Server Name EX: Localhost" name="server" id="server" required="" autocomplete="on">
                      </div>
                      <div class="form-group">
                        <label class="control-label mb-10" >Database Username</label>
                        <input type="text" class="form-control" placeholder="Enter Database Username EX: root" name="username" id="username" required="" autocomplete="on">
                      </div>
                      <div class="form-group">
                        <label class="pull-left control-label mb-10" >Database Password</label>
                        <input type="password" class="form-control" placeholder="Enter Database Password" name="password" id="password" >
                      </div>
                      <div class="form-group">
                        <label class="pull-left control-label mb-10">Database Name</label>
                        <input type="text" class="form-control" placeholder="Enter Database Name" name="dbname" id="dbname" required="" autocomplete="on">
                      </div>
                      <div class="form-group text-center mt-3">
                        <button type="submit" name="backupnow" class="btn btn-primary float-end">Initiate Backup</button>
                      </div>
                    </form>
                  </div>
                </div>
                

                <!-- <div class="col-lg-3 mt-5">
                  <form action="/master/backup-recovery/api/exporting" method="post">
                    <label >Export as an excel file</label>
                    <button class="btn btn-primary mt-3 w-100">Initiate Export</button>
                  </form>
                </div> -->
                
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>

<!-- Fetching Search -->
<script>

function filterUsers(role) {
    $.ajax({
        url: '/master/repositories/api/fetchUsers.php',
        type: 'GET',
        data: { role: role },
        success: function(data) {
            // Assuming the PHP script returns the filtered users as HTML
            // Replace the existing users with the filtered ones
            $('#client-table').html(data);
        },
        error: function(xhr, status, error) {
            // Handle any errors
            console.error('An error occurred:', error);
        }
    });
}

// Call the function with the desired role when the corresponding button is clicked
$('.filter-btn').on('click', function() {
    var role = $(this).data('role');
    filterUsers(role);
});

filterUsers('all');

// Searching Data
$('#search-input').on('keyup', function() {
        var query = $(this).val();
        searchRepositories(query);
    });

  function searchRepositories(query) {
    $.ajax({
        url: '/master/repositories/api/searchRepositories.php',
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
