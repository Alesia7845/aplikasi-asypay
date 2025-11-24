<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil</title>
    <style>
        body { font-family: Arial; background: #f3f3f3; padding: 20px; }
        .box { background: white; padding: 20px; border-radius: 10px; max-width: 450px; margin: auto; }
        .foto-preview { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; cursor:pointer; }
        .input { width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px; }
        .btn { width:100%; background:green; color:white; padding:10px; border-radius:5px; border:none; cursor:pointer; }
    </style>
</head>
<body>

<div class="box">

    <h2 style="text-align:center; margin-bottom:15px;">Edit Profil</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- FOTO -->
        <div style="text-align:center;margin-bottom:15px;">
            <label for="foto">
                <img src="{{ $user->foto ? asset('foto_siswa/'.$user->foto) : asset('default-avatar.png') }}"
                     class="foto-preview" id="preview">
            </label>
            <input type="file" name="foto" id="foto" class="hidden" style="display:none;" onchange="loadFile(event)">
        </div>

        <script>
            function loadFile(event) {
                document.getElementById('preview').src = URL.createObjectURL(event.target.files[0]);
            }
        </script>

        <!-- EMAIL -->
        <label>Email</label>
        <input type="email" name="email" value="{{ $user->email }}" class="input" required>

        <!-- PASSWORD (opsional) -->
        <label>Password Baru (opsional)</label>
        <input type="password" name="password" class="input" placeholder="Kosongkan jika tidak ingin mengubah">

        <button class="btn">Simpan Perubahan</button>

    </form>

</div>

</body>
</html>
