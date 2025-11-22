<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ASY PAY - Pembayaran Madrasah</title>
    @vite('resources/css/app.css')
    <style>
        /* Background dari folder public/images */
        body {
            background: url('{{ asset('images/islam.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        /* Efek glassmorphism */
        .glass-box {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .glass-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45);
        }

        /* Navbar */
        nav {
            background-color: rgba(22, 101, 52, 0.7);
            backdrop-filter: blur(10px);
        }

        nav a {
            transition: all 0.3s ease;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Efek teks gradasi */
        .gradient-text {
            background: linear-gradient(90deg, #6ee7b7, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Tombol utama */
        .btn-primary {
            background-color: #15803d;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #166534;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full p-4 flex justify-between items-center shadow-lg z-10">
        <h1 class="text-2xl font-extrabold gradient-text tracking-wide">ASY PAY</h1>
        <div>
            @auth
                <span class="mr-4">Halo, {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-semibold">Logout</button>
                </form>
            @else
                
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="min-h-screen flex items-center justify-center px-6 pt-24">
        <div class="glass-box p-10 text-center max-w-2xl w-full">
            <img src="{{ asset('images/logobaru.jpeg') }}" alt="Logo Pembayaran" class="w-24 mx-auto mb-6 opacity-90">
            <h1 class="text-4xl font-bold mb-4 leading-snug">
                Selamat Datang di <span class="gradient-text">Sistem Pembayaran Madrasah</span>
            </h1>

            @auth
                <p class="text-lg mt-4">Anda sudah login sebagai <strong>{{ auth()->user()->name }}</strong>.</p>
            @else
                <p class="text-lg mt-4 mb-6">Silakan login untuk melanjutkan ke sistem pembayaran.</p>
                <a href="{{ route('login') }}" class="btn-primary inline-block shadow-md">Login Sekarang</a>
            @endauth
        </div>
    </div>

</body>
</html>
