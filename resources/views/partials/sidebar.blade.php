@if (Auth::user()->role_id == 1)
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav nav-pills nav-stacked" id="menu">
        <div class="logo-container">
            <img src="{{ asset('assets/logo.png') }}" class="p-2" alt="" >
        </div>
        <li>Menu</li>
        <li>
            <a href="{{ route('admin.dashboard') }}">
            <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-border-all"></i>
            </span>
            <span class="nav-text">Dashboard</span>
            </a>
        </li>
        @if (Auth::user()->permission_id == 1)
        <li>
            <a href="{{ route('admin.repositories') }}">
            <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-inbox"></i>
            </span>
            <span class="nav-text">Repositories</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.loan.home') }}">
            <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-archive"></i>
            </span>
            <span class="nav-text">Members Loan</span>
            </a>
        </li>
        <li>
            <a href="/master/backup/backup.php">
                <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-layer-backward"></i>
                </span>
                <span class="nav-text fs-7" style="font-size: 15px;">Backup</span>
            </a>
        </li>
        @elseif (Auth::user()->permission_id == 2)
        <li>
            <a href="{{ route('admin.payment') }}">
            <span class="fa-stack fa-lg pull-left"
                ><i class="bi bi-wallet2"></i></span>
                <span class="nav-text">Payment</span> 
            </a
            >
        </li>
        @elseif (Auth::user()->permission_id == 3)
        <li>
            <a href="{{ route('admin.repositories') }}">
            <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-inbox"></i>
            </span>
            <span class="nav-text">Repositories</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.loan.home') }}">
            <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-archive"></i>
            </span>
            <span class="nav-text">Members Loan</span>
            </a>
        </li>
        <li>
            <a href="/master/backup/backup.php">
                <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-layer-backward"></i>
                </span>
                <span class="nav-text fs-7" style="font-size: 15px;">Backup</span>
            </a>
        </li>
        @endif
        <li>Settings</li>
        <li>
            <a href="{{ route('admin.profile') }}">
            <span class="fa-stack fa-lg pull-left">
                <i class="bi bi-person"></i>
            </span>
            <span class="nav-text">Profile</span>
            </a>
        </li>
        <li>
            <a href="{{ route('logout') }}"  onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
            ><span class="fa-stack fa-lg pull-left">
                <i class="bi bi-box-arrow-left"></i></span
            ><span class="nav-text">Logout</span></a
            >
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>            
        </li>
        </ul>
  </div>
@else
<div id="sidebar-wrapper">
    <ul class="sidebar-nav nav-pills nav-stacked" id="menu">
    <div class="logo-container">
        <img src="{{ asset('assets/logo.png') }}" class="p-2" alt="">
    </div>
    <li>Menu</li>
    <li>
        <a href="{{ route('member.dashboard') }}">
        <span class="fa-stack fa-lg pull-left">
            <i class="bi bi-border-all"></i>
        </span>
        <span class="nav-text">Dashboard</span>
        </a>
    </li>
    
    <li>
        <a href="{{ route('member.account') }}">
        <span class="fa-stack fa-lg pull-left">
            <i class="bi bi-inbox"></i>
        </span>
        <span class="nav-text">Account</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.loan') }}">
        <span class="fa-stack fa-lg pull-left"
            ><i class="bi bi-wallet2"></i></span>
            <span class="nav-text">Loan</span> 
        </a
        >
    </li>

    <li>Settings</li>
    <li>
        <a href="{{ route('member.profile') }}">
        <span class="fa-stack fa-lg pull-left">
            <i class="bi bi-person"></i>
        </span>
        <span class="nav-text">Profile</span>
        </a>
    </li>
    {{-- <li>
        <a href="#"
            data-bs-toggle="modal"
            data-bs-target="#helpModal" ><span class="fa-stack fa-lg pull-left"
            ><i class="bi bi-info-circle"></i></span
        ><span class="nav-text">Help</span></a>
    </li> --}}
    <li>
        <a href="{{ route('logout') }}"  onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"
        ><span class="fa-stack fa-lg pull-left">
            <i class="bi bi-box-arrow-left"></i></span
        ><span class="nav-text">Logout</span></a
        >
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>            
    </li>
    </ul>
</div>
@endif
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
