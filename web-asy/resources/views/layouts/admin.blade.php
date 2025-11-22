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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: linear-gradient(135deg, #a8f0a5, #d2f8d2);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            font-size: 22px;
            font-weight: 700;
            color: #007b33;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 12px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .sidebar ul li a i {
            margin-right: 8px;
        }

        .sidebar ul li a:hover {
            background-color: #b6f4b0;
            color: #007b33;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 25px;
            background-color: #4CAF50;
            border-bottom: 2px solid #388E3C;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar img.logo {
            height: 40px;
        }

        .navbar .user-info {
            font-weight: bold;
            color: white;
        }

        .navbar .user-info form {
            display: inline;
        }

        .navbar .user-info button {
            background: none;
            border: none;
            color: #ffe0e0;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #ffffff;
        }

        .alert {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
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
        <aside class="sidebar">
            <h2>ASY-PAY</h2>
            <ul>
                @php $role = auth()->user()->role ?? 'guest'; @endphp

                @if($role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.siswa.index') }}"><i class="fa fa-users"></i> Siswa</a></li>
                    <li><a href="{{ route('admin.kategori.index') }}"><i class="fa fa-tags"></i> Kategori Pembayaran</a></li>
                    <li><a href="{{ route('admin.pembayaran.index') }}"><i class="fa fa-credit-card"></i> Pembayaran</a></li>
                    <li><a href="{{ route('admin.laporan.index') }}"><i class="fa fa-file-alt"></i> Laporan</a></li>
                    <li><a href="{{ route('admin.kasir.index') }}"><i class="fa fa-cash-register"></i> Kasir</a></li>
                @elseif($role === 'siswa')
                    <li><a href="{{ route('siswa.dashboard') }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('siswa.pembayaran.index') }}"><i class="fa fa-money-bill-wave"></i> Pembayaran</a></li>
                    <li><a href="{{ route('siswa.riwayat.index') }}"><i class="fa fa-history"></i> Riwayat Pembayaran</a></li>
                @endif
            </ul>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Navbar -->
            <div class="navbar">
                <img src="{{ asset('images/logomadrasah.png') }}" alt="Logo" class="logo">
                <div class="user-info">
                    👤 {{ auth()->user()->name ?? 'Guest' }}
                    @auth
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>

            <!-- Page Content -->
            <main class="content">
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
                @stack('scripts')
            </main>
        </div>
    </div>
</body>
</html>
