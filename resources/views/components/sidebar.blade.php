<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="/home">Al-Fuadi</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">AF</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class='{{ Request::is('home') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-house"></i> <span>Dashboard</span></a>
            </li>

            <li class="menu-header">Alfuadi Database</li>
            <li class="nav-item dropdown {{ $type_menu === 'Al-Fuadi Database' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-book-quran"></i>
                    <span>Al-Quran</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('surahs') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('surahs.index') }}">Surah</a>
                    </li>
                    <li class="{{ Request::is('verses') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('verses.index') }}">Ayat</a>
                    </li>
                    <li class="{{ Request::is('wordgroups') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('wordgroups.index') }}">Grup Kalimat</a>
                    </li>
                    <li class="{{ Request::is('words') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('words.index') }}">Kalimat</a>
                    </li>
                </ul>
            </li>

            @if (auth()->check() && auth()->user()->roles !== 'user')
                <li class="menu-header">Tools</li>
                <li class="{{ Request::is('grouping') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('wordgroups.grouping') }}"><i
                            class="fas fa-object-group"></i>Grouping Ayat</a>
                </li>
                @if (auth()->check() && auth()->user()->roles === 'administrator')
                    <li class="{{ Request::is('words/create') ? 'active' : '' }}">
                        <a class="nav-link beep beep-sidebar" href="{{ route('words.create') }}"><i
                                class="fas fa-keyboard"></i>Input Irob</a>
                    </li>
                @endif
            @endif

            @if (auth()->check() && auth()->user()->roles === 'administrator')
                <li class="menu-header">Aplikasi Quran Al-Fuadi</li>
                <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                    <a class="nav-link" href="404"><i class="fas fa-film"></i>
                        <span>Video Al-Fuadi</span></a>
                </li>

                <li class="menu-header">Organize</li>
                <li class="{{ Request::is('users') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-user"></i>
                        <span>User</span></a>
                </li>
                {{-- <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-utensils"></i>
                        <span>Products</span></a>
                </li> --}}
                <li class="{{ Request::is('example') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('page.templatepage') }}"><i class="fas fa-file-lines"></i>
                        <span>Template</span></a>
                </li>
            @endif
    </aside>
</div>
