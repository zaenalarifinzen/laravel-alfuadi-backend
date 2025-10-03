<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Al Fuadi</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">AF</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class='{{ Request::is('dashboard-general-dashboard') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-house"></i> <span>Dashboard</span></a>
            </li>

            <li class="menu-header">Alfuadi Database</li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('surahs.index') }}"><i class="fas fa-list-ul"></i> <span>Daftar Surat</span></a>
            </li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-object-group"></i> <span>Grouping Ayat</span></a>
            </li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-keyboard"></i> <span>Input I'rob</span></a>
            </li>

            <li class="menu-header">Aplikasi Quran Al-Fuadi</li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"><i class="fa-brands fa-youtube"></i> <span>Video Al-Fuadi</span></a>
            </li>

            <li class="menu-header">Organize</li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-user"></i> <span>User</span></a>
            </li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-utensils"></i> <span>Products</span></a>
            </li>
    </aside>
</div>
