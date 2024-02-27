@auth
<nav class="navbar navbar-default no-margin">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header fixed-brand pe-3 ps-1 d-flex align-items-center">
    <button
      type="button"
      class="btn navbar-toggle collapsed"
      data-toggle="collapse"
      id="menu-toggle" onclick="toggleIcon()">
      <i class="bi bi-layout-sidebar" id="menu-icon"></i>
    </button>
    <a class="navbar-brand ms-1 ps-2 border-start py-0" style="font-size: 18px;" href="javascript:void(0)">Credit Cooperative Partners</a>
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
            aria-hidden="true"
          ></span>
        </button>
      </li>
    </ul>
  </div>
  <!-- bs-example-navbar-collapse-1 -->
</nav>
<script src="{{ asset('js/script.js') }}"></script>

@endauth
