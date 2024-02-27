<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}
require_once __DIR__ . "/api/connection.php";
include 'api/repositoriesHeader.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Repositories</title>
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

    <link rel="stylesheet" href="repositories.css">
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
                  Members Repositories
                </h1>
                <div class="row" style="margin-top: 2em;">
                  <!-- Table -->
                  <div class="row">
                    
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="row">
                          <div class="col-lg-11">
                            <div class="col-lg-3" id="search-top-bar">
                                <div class="input-group me-3">
                                  <input class="form-control border rounded" type="text" placeholder="Search" id="search-input">
                                  <!-- <span class="input-group-append">
                                      <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3" type="button">
                                          <i class="fa fa-search"></i>
                                      </button>
                                  </span> -->
                                </div>
                              <a href="/admin/repositories/new/member.php"><button class="btn btn-primary" id="add-mem" style="float: right;">Add member</button></a>
                          </div>
                          </div>
                        </div>
                        
                      </div>
                        <div class="table table-responsive" id="client-repositories">
                        <table id="repository_table" class="table" style="font-size: large;">
                                <?php
                                  include 'api/fetchClients.php';
                                ?>
                            </table>  
                        </div>
                        <div id="pagination" class="pagination">
                        </div>
                    </div>
                </div>
                  <!-- /Table -->

                  <!-- Toaster -->

                  <!-- /Toaster -->
              </div>
            </div>
          </div>
        </div>
        <!-- /#page-content-wrapper -->
              <!-- /#page-content-wrapper -->
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Sidebar -->
<script src="script.js"></script>

<!-- Fetching Search -->
<script>
function getPage(page = 1) {
    $.ajax({
        url: '/admin/repositories/api/fetchClients.php',
        type: 'GET',
        data: { page: page },
        success: function(data) {
            $('#repository_table').html(data);
            updatePagination(page);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
}

function loadPage(page) {
    getPage(page);
}


function updatePagination(currentPage) {
    $.ajax({
        url: '/admin/repositories/api/getTotalPages.php',
        type: 'GET',
        success: function(response) {
            var response = JSON.parse(response);
            var total = Number(response.total_pages); // Corrected variable name
            var paginationHtml = '';
            for (var i = 1; i <= total; i++) { // Use 'total' instead of 'totalPages'
                if (i == currentPage) {
                    paginationHtml += '<li class="page-item active"><button class="page-link" onclick="loadPage(' + i + ')">' + i + '</button></li>';
                } else {
                    paginationHtml += '<li class="page-item"><button class="page-link" onclick="loadPage(' + i + ')">' + i + '</button></li>';
                }
            }
            $('#pagination').html(paginationHtml);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
}


// Searching Data
$('#search-input').on('keyup', function() {
        var query = $(this).val();
        searchLoans(query);
    });

  function searchLoans(query) {
    $.ajax({
        url: '/admin/repositories/api/searchRepositories.php',
        type: 'GET',
        data: { query: query },
        success: function(data) {
            $('#repository_table').html(data);
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        }
    });
}

$(document).ready(function() {
    getPage();
});
</script>

</body>
</html>
