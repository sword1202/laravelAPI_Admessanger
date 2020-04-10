<header class="app-header navbar">
  <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="#"></a>
  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
    <span class="navbar-toggler-icon"></span>
  </button>

  <ul class="nav navbar-nav d-md-down-none">
{{-- 
    <li class="nav-item px-3">
      <a class="nav-link" href="#">Users</a>
    </li>
    <li class="nav-item px-3">
      <a class="nav-link" href="#">Settings</a>
    </li>
 --}}
  </ul>
  <ul class="nav navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        @if (Auth::check())
          <img src="{{ secure_asset('img/avatars/default_avatar.png') }}" class="img-avatar" alt="{{ Auth::user()->email }}">
        @else
          <img src="{{ secure_asset('img/avatars/default_avatar.png') }}" class="img-avatar" alt="admin@bootstrapmaster.com">
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header text-center">
          <strong>{{ auth()->user()->name }}'s Account</strong>
        </div>

        <!-- <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
        <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>
        <a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="badge badge-secondary">42</span></a> -->
        <div class="divider"></div>
        <a class="dropdown-item" href="{{ url('/change_password') }}"><i class="fa fa-lock"></i>Change Password</a>
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i>
          Logout
        </a>
        {{-- <a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-lock"                                             onclick="event.preventDefault(); document.getElementById('logout-form').submit();"></i> Logout</a> --}}
      </div>
    </li>
  </ul>
  <!-- <button class="navbar-toggler aside-menu-toggler" type="button">
    <span class="navbar-toggler-icon"></span>
  </button> -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
  {{ csrf_field() }}
</form>
</header>
