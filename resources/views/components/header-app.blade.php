<nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav mr-3">
        <li>
            <a href="#" data-toggle="sidebar" class="nav-link sidebar-gone-show"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <a href="{{ route('home') }}" class="navbar-brand">Al-Fuadi</a>
    <form class="form-inline mr-auto">
        <div class="nav-collapse">
            <ul class="navbar-nav">
                <li class="nav-item"><a href="{{ route('quran.index') }}" class="nav-link">Al-Quran</a></li>
                <li class="nav-item"><a href="{{ route('metode-al-fuadi.jilid-1') }}" class="nav-link">Metode
                        Al-Fuadi</a></li>
            </ul>
        </div>
    </form>
    <ul class="navbar-nav navbar-right">
        @auth
            <li class="dropdown"><a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image" src="{{ asset('img/avatar/avatar-3.png') }}" class="rounded-circle mr-1">
                    <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('profile') }}" class="dropdown-item has-icon">
                        <i class="far fa-user"></i> Profil
                    </a>
                    @if (auth()->user()->roles !== 'user')
                        <a href="{{ route('dashboard') }}" class="dropdown-item has-icon">
                            <i class="fas fa-border-all"></i>Dashboard
                        </a>
                    @endif
                    <a href="{{ route('exercise-level.index') }}" class="dropdown-item has-icon">
                        <i class="far fa-pen-to-square"></i> Latihan
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item has-icon text-danger"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        @else
            <li>
                <a href="{{ route('login') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-right-to-bracket"></i> Masuk
                </a>
            </li>
        @endauth
    </ul>
</nav>
