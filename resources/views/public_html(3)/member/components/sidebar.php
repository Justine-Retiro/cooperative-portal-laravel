<head>
    <link rel="shortcut icon" href="/../assets/favicon.ico" type="image/x-icon">
</head>

<div id="sidebar-wrapper">
<ul class="sidebar-nav nav-pills nav-stacked" id="menu">
  <div class="logo-container">
    <img src="/../assets/logo.png" class="p-2" alt="">
  </div>
  <li>Menu</li>
  <li>
    <a href="/member/dashboard/dashboard.php">
      <span class="fa-stack fa-lg pull-left">
        <i class="bi bi-border-all"></i>
      </span>
      <span class="nav-text">Dashboard</span>
    </a>
  </li>
  <li>
    <a href="/member/profile/profile.php">
      <span class="fa-stack fa-lg pull-left">
        <i class="bi bi-person"></i>
      </span>
      <span class="nav-text">Profile</span>
    </a>
  </li>
  <li>
    <a href="/member/account/account.php">
      <span class="fa-stack fa-lg pull-left">
        <i class="bi bi-inbox"></i>
      </span>
      <span class="nav-text">Account</span>
    </a>
  </li>
  <li>
    <a href="/member/loan/loan.php">
      <span class="fa-stack fa-lg pull-left"
        ><i class="bi bi-wallet2"></i></span>
        <span class="nav-text">Loan</span> 
      </a
    >
  </li>

  <li>Settings</li>
  <li>
    <a href="#"
        data-bs-toggle="modal"
         data-bs-target="#helpModal" ><span class="fa-stack fa-lg pull-left"
        ><i class="bi bi-info-circle"></i></span
      ><span class="nav-text">Help</span></a>
  </li>
  <li>
    <a href="/globalApi/logout.php"
      ><span class="fa-stack fa-lg pull-left">
        <i class="bi bi-box-arrow-left"></i></span
      ><span class="nav-text">Logout</span></a
    >
  </li>
</ul>
</div>

<!-- Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel">Helps</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Cooperative Contacts
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
