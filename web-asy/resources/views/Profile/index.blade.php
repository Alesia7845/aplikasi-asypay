<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil Siswa</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            background: #f0f2f5;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        .info {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Profil Siswa</h2>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <div class="info"><span class="label">Nama:</span> {{ $profile->nama }}</div>
        <div class="info"><span class="label">Kelas:</span> {{ $profile->kelas }}</div>
        <div class="info"><span class="label">NIS:</span> {{ $profile->nis }}</div>
        <div class="info"><span class="label">Alamat:</span> {{ $profile->alamat }}</div>

        <a href="{{ route('profile.edit', $profile->id) }}" class="btn">Edit Profil</a>
    </div>
</body>
</html>
