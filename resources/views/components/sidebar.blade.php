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

            <li class="menu-header">Qur'an Alfuadi</li>
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
                    {{-- <li class="{{ Request::is('wordgroups') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('wordgroups.index') }}">Grup Kalimat</a>
                    </li>
                    <li class="{{ Request::is('words') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('words.index') }}">Kalimat</a>
                    </li> --}}
                </ul>
            </li>

            @if (auth()->check())
                <li class="menu-header">Metode Al-Fuadi</li>
                <li class="nav-item dropdown {{ $type_menu === 'metode-al-fuadi' ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                            class="fas fa-book-open"></i>
                        <span>Modul Al-Fuadi</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('metode-al-fuadi/jilid-1') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('metode-al-fuadi.jilid-1') }}">
                                <span>Jilid 1</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('modul/jilid-2') ? 'active' : '' }}">
                            <a class="nav-link" href="#">
                                <span>Jilid 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $type_menu === 'exercise' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('exercise-level.index') }}">
                        <i class="fas fa-pen-to-square"></i>
                        <span>Latihan analisa</span>
                    </a>
                </li>
            @endauth

            @if (auth()->check() && auth()->user()->roles !== 'user')
                <li class="menu-header">Tools</li>
                <li class="{{ Request::is('wordgroups/grouping') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('wordgroups.grouping') }}">
                        <i class="fas fa-object-group"></i>
                        <span>Grouping Ayat</span>
                    </a>
                </li>
                <li class="{{ Request::is('words/create') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('words.create') }}">
                        <i class="fas fa-keyboard"></i>
                        <span>Input Irob <span class="beep"></span></span>
                    </a>
                </li>
            @endif

            @if (auth()->check() && auth()->user()->roles === 'administrator')
                <li class="menu-header">Aplikasi Quran Al-Fuadi</li>
                <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                    <a class="nav-link" href="/not-found"><i class="fas fa-film"></i>
                        <span>Video Al-Fuadi</span></a>
                </li>

                <li class="menu-header">Organize</li>
                <li class="{{ Request::is('skema-nahwu') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('page.skema-nahwu') }}">
                        <i class="fas fa-sitemap"></i>
                        <span>Skema Nahwu</span></a>
                </li>
                <li class="{{ Request::is('users') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <i class="fas fa-user"></i>
                        <span>User</span></a>
                </li>
                {{-- <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-utensils"></i>
                        <span>Products</span></a>
                </li> --}}
                <li class="{{ Request::is('example') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('page.templatepage') }}"><i class="fas fa-file-lines"></i>
                        <span>Develop Page</span></a>
                </li>
            @endif
</aside>
</div>
