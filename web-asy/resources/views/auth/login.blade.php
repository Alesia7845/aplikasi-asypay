<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login -ASYPAY</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-blue-200 flex items-center justify-center min-h-screen">

    <div class="bg-green rounded-2xl shadow-lg flex flex-col md:flex-row overflow-hidden w-[90%] md:w-[850px]">
        <!-- Bagian Kiri -->
        <div class="hidden md:flex md:w-1/2 items-center justify-center p-6 bg-gray-50">
            <img src="{{ asset('images/landing.jpg') }}" alt="Ilustrasi Login" class="w-4/5 rounded-lg shadow-md">
        </div>

        <!-- Bagian Kanan -->
        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center bg-white">
            <!-- Logo dan Judul -->
            <div class="brand">
                <img src="{{ asset('images/logo.jpg') }}" class="brand-logo" alt="Logo">
            </div>

            <!-- Pesan Status -->
            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Pesan Error -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        <!-- Form Login -->
<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf
    <div class="bg-green-100 border border-green-200 rounded-xl p-6 shadow-inner">
        <!-- Email -->
        <div>
            <label class="block text-gray-700 text-sm mb-1">Email</label>
            <input type="email" name="email" placeholder="Masukkan email anda"
                value="{{ old('email') }}"
                required autofocus autocomplete="username"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none bg-white">
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label class="block text-gray-700 text-sm mb-1">Password</label>
            <input type="password" name="password" placeholder="Masukkan password anda"
                required autocomplete="current-password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none bg-white">
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mt-4">
            <input id="remember_me" type="checkbox" name="remember"
                class="rounded border-gray-300 text-green-600 focus:ring-green-400">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                Ingat saya
            </label>
        </div>

        <!-- Tombol Login -->
        <div class="pt-4">
            <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg py-2 transition duration-200 shadow">
                LOGIN
            </button>
        </div>
    </div>
</form>

        </div>
    </div>

</body>
</html>
