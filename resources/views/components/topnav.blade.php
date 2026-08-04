<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <a href="{{ url('/') }}" class="navbar-brand">Al-Fuadi</a>
    {{-- <div class="navbar-nav">
        <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
    </div> --}}
    <form class="form-inline ml-auto">
        <ul class="navbar-nav">
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('img/avatar/avatar-3.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, User</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item has-icon"><i class="far fa-user"></i> Profile</a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i>
                    Logout</a>
            </div>
        </li>
    </ul>
</nav>
