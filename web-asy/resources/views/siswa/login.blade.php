<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa</title>
</head>
<body>

    <h2>Login Siswa</h2>

    <form method="POST" action="{{ route('siswa.login.submit') }}">
        @csrf

        <div>
            <label for="nis">NIS</label>
            <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required>
            @error('nis')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Login</button>
    </form>

</body>
</html>
