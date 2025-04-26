@php
    $homeLink = null;

    if (auth()->user()->role == 'admin' || auth()->user()->role == 'super-admin') {
        $homeLink = route('dashboard');
    }

    $baseUrl = config('app.url');

    $baseUrl = explode('://', $baseUrl)[1];

    if (request()->secure()) {
        $baseUrl = 'https://' . $baseUrl;
    } else {
        $baseUrl = 'http://' . $baseUrl;
    }
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ $homeLink }}" class="app-brand-link">
            <img src="{{ $baseUrl }}/assets/images/logo-unima.png" class="img-fluid" style="width: 40px" />
            <span class="app-brand-text demo menu-text fw-bold ms-2 text-uppercase">E-Survey Unima</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        @canany(['admin', 'super-admin'])
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-chart-line"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manajemen Angket</span>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.category.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.category.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-list"></i>
                    <div data-i18n="Kategori">Kategori</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.survey.results.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.survey.results.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-bar-chart-alt-2"></i>
                    <div data-i18n="Kategori">Hasil Angket</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.periode.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.periode.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-calendar"></i>
                    <div data-i18n="Manajemen Periode">Manajemen Periode</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manajemen Pengguna</span>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.student.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.student.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-user"></i>
                    <div data-i18n="Mahasiswa">Mahasiswa</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.quality-assurance.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.quality-assurance.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-user-graduate"></i>
                    <div data-i18n="Kajur">Tim Penjaminan Mutu</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.administrator.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.administrator.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-user-gear"></i>
                    <div data-i18n="Admin">Admin</div>
                </a>
            </li>
        @endcanany
    </ul>
</aside>
