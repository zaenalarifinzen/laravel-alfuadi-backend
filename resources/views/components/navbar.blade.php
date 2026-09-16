<nav class="navbar navbar-secondary navbar-expand-lg sticky-top">
    <div class="container">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <span>Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('quran.index') }}"
                    class="nav-link {{ request()->routeIs('quran.*') ? 'active' : '' }}">
                    <span>Al-Quran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('metode-alfuadi') }}"
                    class="nav-link {{ request()->routeIs('metode-al-fuadi.*') ? 'active' : '' }}">
                    </i><span>Metode Al-Fuadi</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('courses') }}"
                    class="nav-link {{ request()->routeIs('metode-al-fuadi.*') ? 'active' : '' }}">
                    </i><span>Kelas Online</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
