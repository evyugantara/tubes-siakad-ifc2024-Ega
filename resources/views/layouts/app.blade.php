<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAKAD') - Sistem Informasi Akademik</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="wrapper">

    {{-- ========== TOP NAVBAR ========== --}}
    <header class="main-header">
        <a href="{{ route('dashboard') }}" class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>
            </svg>
            <span>SIAKAD FT UNSUR</span>
        </a>
        <span class="navbar-title">@yield('page_title', 'Sistem Informasi Akademik')</span>
        <div class="navbar-right">
            <div class="user-avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span style="color:rgba(255,255,255,0.9); padding: 0 8px; font-size:14px;">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </header>

    {{-- ========== SIDEBAR ========== --}}
    <aside class="main-sidebar">

        {{-- User Panel --}}
        <div class="sidebar-user-panel">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
                <div class="user-online">
                    <span>&#9679;</span>
                    {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Mahasiswa' }}
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <p class="sidebar-menu-label">General</p>

        <ul class="sidebar-nav">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/>
                        <rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>
                    </svg>
                    Dashboard
                </a>
            </li>

            {{-- Data Master (Admin only) --}}
            @if(auth()->user()->isAdmin())
                @php $isMasterActive = Route::is('dosen.*') || Route::is('mahasiswa.*') || Route::is('matakuliah.*'); @endphp
                <li>
                    <details class="nav-dropdown" {{ $isMasterActive ? 'open' : '' }}>
                        <summary class="nav-item {{ $isMasterActive ? 'active' : '' }}" style="display:flex; align-items:center; gap:10px; padding:12px 15px; list-style:none; cursor:pointer; border-left:3px solid {{ $isMasterActive ? 'var(--topbar-bg)' : 'transparent' }};">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0;opacity:0.8;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <span style="flex:1;">Data Master</span>
                            <svg class="chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;flex-shrink:0;">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </summary>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('dosen.index') }}" class="{{ Route::is('dosen.*') ? 'active' : '' }}">&#8250; Data Dosen</a></li>
                            <li><a href="{{ route('mahasiswa.index') }}" class="{{ Route::is('mahasiswa.*') ? 'active' : '' }}">&#8250; Data Mahasiswa</a></li>
                            <li><a href="{{ route('matakuliah.index') }}" class="{{ Route::is('matakuliah.*') ? 'active' : '' }}">&#8250; Mata Kuliah</a></li>
                        </ul>
                    </details>
                </li>
            @endif

            {{-- Jadwal --}}
            <li>
                <a href="{{ route('jadwal.index') }}" class="{{ Route::is('jadwal.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Jadwal Kuliah
                </a>
            </li>

            {{-- KRS --}}
            <li>
                <a href="{{ route('krs.index') }}" class="{{ Route::is('krs.*') && !Route::is('krs.print') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    KRS
                </a>
            </li>

        </ul>

        {{-- Logout di footer sidebar --}}
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-sidebar-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>

    </aside>

    {{-- ========== MAIN CONTENT ========== --}}
    <div class="content-wrapper">

        {{-- Content Header / Breadcrumb --}}
        <div class="content-header">
            <h1>@yield('header_title', 'Dashboard')</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">&#8962; Home</a></li>
                <li class="separator">/</li>
                <li>@yield('header_title', 'Dashboard')</li>
            </ul>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')

    </div>

    {{-- ========== FOOTER ========== --}}
   
</div>

@yield('scripts')
</body>
</html>
