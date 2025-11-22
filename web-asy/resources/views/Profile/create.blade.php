<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Profil</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f8;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            background: #fff;
            padding: 20px 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back {
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
        }

        .back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Tambah Profil Siswa</h2>
    <form action="{{ route('profile.store') }}" method="POST">
        @csrf
        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Kelas</label>
        <input type="text" name="kelas" required>

        <label>NIS</label>
        <input type="text" name="nis" required>

        <label>Alamat</label>
        <textarea name="alamat" required></textarea>

        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('profile.index') }}" class="back">← Kembali</a>
</div>
</body>
</html>
