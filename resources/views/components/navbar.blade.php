<nav class="navbar navbar-secondary navbar-expand-lg sticky-top">
    <div class="container">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i><span>Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('quran.index') }}"
                    class="nav-link {{ request()->routeIs('quran.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i><span>Al-Quran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('metode-al-fuadi.jilid-1') }}"
                    class="nav-link {{ request()->routeIs('metode-al-fuadi.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i><span>Metode Al-Fuadi</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
