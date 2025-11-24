<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ASY-PAY - @yield('title', 'Dashboard')</title>

    <!-- External CSS & JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Styles -->
    <style>
        :root {
            --brand-green: #4CAF50;
            --brand-green-dark: #388E3C;
            --sidebar-w: 240px;
            --sidebar-w-mini: 72px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ===== Sidebar (Normal) ===== */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(135deg, #a8f0a5, #d2f8d2);
            padding: 20px 16px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.08);
            position: relative;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid rgba(0, 123, 51, 0.1);
        }

        .sidebar .brand .brand-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            border-radius: 8px;
        }

        .sidebar .brand .brand-text {
            font-size: 22px;
            font-weight: 700;
            color: #007b33;
            white-space: nowrap;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }

        .sidebar li {
            margin-bottom: 6px;
        }

        /* Link menu dengan hover effect yang lebih smooth */
        .sidebar a {
            text-decoration: none;
            color: #223;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            transition: all 0.2s ease;
            position: relative;
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar a i {
            width: 24px;
            text-align: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .menu-text {
            white-space: nowrap;
            display: inline-block;
            vertical-align: middle;
        }

        .sidebar a:hover {
            background-color: #b6f4b0;
            color: #007b33;
            transform: translateX(4px);
        }

        .sidebar a.active {
            background-color: #007b33;
            color: white;
        }

        /* ===== Mini Mode ===== */
        .sidebar.mini {
            width: var(--sidebar-w-mini);
            padding: 20px 8px;
        }

        .sidebar.mini .brand .brand-text {
            display: none;
        }

        .sidebar.mini .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            visibility: hidden;
        }

        .sidebar.mini a {
            justify-content: center;
            transform: translateX(0) !important;
        }

        /* Tooltip saat mini */
        .sidebar.mini a[data-title]:hover::after {
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: #223;
            color: #fff;
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
            white-space: nowrap;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            z-index: 200;
            pointer-events: none;
        }

        /* ===== Auto-expand saat hover ===== */
        .sidebar.mini:hover,
        .sidebar.mini.hover-open {
            width: var(--sidebar-w);
            padding: 20px 16px;
        }

        .sidebar.mini:hover .brand .brand-text,
        .sidebar.mini.hover-open .brand .brand-text {
            display: inline;
        }

        .sidebar.mini:hover .menu-text,
        .sidebar.mini.hover-open .menu-text {
            opacity: 1;
            width: auto;
            visibility: visible;
        }

        .sidebar.mini:hover a,
        .sidebar.mini.hover-open a {
            justify-content: flex-start;
        }

        .sidebar.mini:hover a[data-title]::after,
        .sidebar.mini.hover-open a[data-title]::after {
            display: none;
        }

        /* ===== Main wrapper ===== */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* ===== Navbar dengan Profile Picture ===== */
        .navbar {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            align-items: center;
            padding: 14px 24px;
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar .left-group {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Toggle button */
        .toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .toggle-btn:active {
            transform: scale(0.95);
        }

        .navbar img.logo {
            height: 44px;
            border-radius: 8px;
        }

        /* User Info dengan Profile Picture */
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 16px 6px 6px;
            border-radius: 50px;
            transition: background 0.2s ease;
        }

        .user-info:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            background: white;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-weight: 600;
            color: white;
            font-size: 14px;
            line-height: 1.2;
        }

        .user-role {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.85);
            text-transform: capitalize;
        }

        .logout-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 18px;
            padding: 6px;
            margin-left: 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.1);
        }

        /* ===== Content Area ===== */
        .content {
            flex: 1;
            padding: 28px;
            background-color: #ffffff;
            overflow-y: auto;
        }

        /* ===== Alerts ===== */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .alert i {
            font-size: 18px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1000;
            }

            .main-wrapper {
                margin-left: var(--sidebar-w);
                transition: margin-left 0.3s ease;
            }

            .sidebar.mini ~ .main-wrapper {
                margin-left: var(--sidebar-w-mini);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-w-mini);
                padding: 20px 8px;
            }

            .sidebar .brand .brand-text {
                display: none;
            }

            .menu-text {
                opacity: 0;
                width: 0;
                visibility: hidden;
            }

            .navbar {
                padding: 12px 16px;
            }

            .content {
                padding: 20px 16px;
            }

            .user-details {
                display: none;
            }

            .main-wrapper {
                margin-left: var(--sidebar-w-mini);
            }
        }

        @media (max-width: 480px) {
            .navbar img.logo {
                height: 36px;
            }

            .toggle-btn {
                width: 36px;
                height: 36px;
            }

            .user-avatar {
                width: 34px;
                height: 34px;
            }
        }
    </style>

    <!-- CSRF Setup for AJAX -->
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    @stack('head')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="appSidebar">
            <div class="brand">
                <img src="{{ asset('images/logo.jpg') }}" class="brand-logo" alt="Logo">
                <span class="brand-text">ASY-PAY</span>
            </div>

            <ul>
                @php $role = auth()->user()->role ?? 'guest'; @endphp

                @if($role === 'admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" data-title="Dashboard" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-tachometer-alt"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.siswa.index') }}" data-title="Siswa" class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                            <i class="fa fa-users"></i>
                            <span class="menu-text">Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kategori.index') }}" data-title="Kategori Pembayaran" class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                            <i class="fa fa-tags"></i>
                            <span class="menu-text">Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pembayaran.index') }}" data-title="Pembayaran" class="{{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                            <i class="fa fa-credit-card"></i>
                            <span class="menu-text">Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan.index') }}" data-title="Laporan" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                            <i class="fa fa-file-alt"></i>
                            <span class="menu-text">Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kasir.index') }}" data-title="Kasir" class="{{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
                            <i class="fa fa-cash-register"></i>
                            <span class="menu-text">Kasir</span>
                        </a>
                    </li>
                @elseif($role === 'siswa')
                    <li>
                        <a href="{{ route('siswa.dashboard') }}" data-title="Dashboard" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-tachometer-alt"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.pembayaran.index') }}" data-title="Pembayaran" class="{{ request()->routeIs('siswa.pembayaran.*') ? 'active' : '' }}">
                            <i class="fa fa-money-bill-wave"></i>
                            <span class="menu-text">Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.riwayat.index') }}" data-title="Riwayat" class="{{ request()->routeIs('siswa.riwayat.*') ? 'active' : '' }}">
                            <i class="fa fa-history"></i>
                            <span class="menu-text">Riwayat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.index') }}" data-title="Profil" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="fa fa-user-circle"></i>
                            <span class="menu-text">Profil</span>
                        </a>
                    </li>
                @endif
            </ul>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Navbar -->
            <div class="navbar">
                <div class="left-group">
                    <!-- Toggle Button -->
                    <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                        <i class="fa fa-bars"></i>
                    </button>
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo">
                </div>

                <div class="user-info">
                    @auth
                        @php
                            $user = auth()->user();
                            
                            // Multi-path photo detection untuk navbar
                            $photoUrl = null;
                            
                            if ($user->foto) {
                                // Jika foto adalah URL lengkap
                                if (filter_var($user->foto, FILTER_VALIDATE_URL)) {
                                    $photoUrl = $user->foto;
                                }
                                // Cek di storage/app/public (RECOMMENDED)
                                elseif (file_exists(storage_path('app/public/' . $user->foto))) {
                                    $photoUrl = asset('storage/' . $user->foto);
                                }
                                // Cek di public/foto_siswa
                                elseif (file_exists(public_path('foto_siswa/' . $user->foto))) {
                                    $photoUrl = asset('foto_siswa/' . $user->foto);
                                }
                                // Cek di public/images/users
                                elseif (file_exists(public_path('images/users/' . $user->foto))) {
                                    $photoUrl = asset('images/users/' . $user->foto);
                                }
                                // Cek path langsung di public
                                elseif (file_exists(public_path($user->foto))) {
                                    $photoUrl = asset($user->foto);
                                }
                            }
                            
                            // Fallback ke default avatar
                            $finalPhotoUrl = $photoUrl ?: asset('images/default-avatar.png');
                        @endphp
                        
                        <img src="{{ $finalPhotoUrl }}" 
                             alt="{{ $user->name }}" 
                             class="user-avatar"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                        
                        <div class="user-details">
                            <span class="user-name">{{ $user->name }}</span>
                            <span class="user-role">{{ $user->role }}</span>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="logout-btn" title="Logout">
                                <i class="fa fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" 
                             alt="Guest" 
                             class="user-avatar">
                        <div class="user-details">
                            <span class="user-name">Guest</span>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Page Content -->
            <main class="content">
                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fa fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mini Sidebar Toggle & Auto-Expand Logic -->
    <script>
        (function() {
            const sidebar = document.getElementById('appSidebar');
            const btn = document.getElementById('sidebarToggle');
            const LS_KEY = 'asy_pay_sidebar_mini';

            // Terapkan state tersimpan
            try {
                const saved = localStorage.getItem(LS_KEY);
                if (saved === '1') {
                    sidebar.classList.add('mini');
                }
            } catch(e) {}

            // Toggle manual via klik tombol
            btn?.addEventListener('click', function() {
                sidebar.classList.toggle('mini');
                try {
                    localStorage.setItem(LS_KEY, sidebar.classList.contains('mini') ? '1' : '0');
                } catch(e) {}
            });

            // Auto expand ketika mouse hover
            sidebar.addEventListener('mouseenter', () => {
                if (sidebar.classList.contains('mini')) {
                    sidebar.classList.add('hover-open');
                }
            });

            sidebar.addEventListener('mouseleave', () => {
                sidebar.classList.remove('hover-open');
            });

            // Aksesibilitas: fokus keyboard juga membuka
            sidebar.addEventListener('focusin', () => {
                if (sidebar.classList.contains('mini')) {
                    sidebar.classList.add('hover-open');
                }
            });

            sidebar.addEventListener('focusout', (e) => {
                if (!sidebar.contains(e.relatedTarget)) {
                    sidebar.classList.remove('hover-open');
                }
            });

            // Auto dismiss alerts setelah 5 detik
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    alert.style.animation = 'slideDown 0.3s ease reverse';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>