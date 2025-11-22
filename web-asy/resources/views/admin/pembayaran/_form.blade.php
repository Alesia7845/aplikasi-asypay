@csrf

<div class="mb-3">
    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
    <select name="kategori_id" id="kategori_id" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategori as $k)
            <option value="{{ $k->id }}" {{ old('kategori_id', $pembayaran->kategori_id ?? '') == $k->id ? 'selected' : '' }}>
                {{ $k->nama }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
    <input type="text" name="nis" placeholder="Masukkan NIS siswa" required>
</div>

<div class="mb-3">
    <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pembayaran->nama ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="kelas" class="form-label">Kelas</label>
    <input type="text" name="kelas" id="kelas" class="form-control" value="{{ old('kelas', $pembayaran->kelas ?? '') }}">
</div>

<div class="mb-3">
    <label for="jumlah" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', $pembayaran->jumlah ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="tanggal_buat" class="form-label">Tanggal Buat <span class="text-danger">*</span></label>
    <input type="date" name="tanggal_buat" id="tanggal_buat" class="form-control" value="{{ old('tanggal_buat', $pembayaran->tanggal_buat ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="tanggal_tempo" class="form-label">Tanggal Tempo</label>
    <input type="date" name="tanggal_tempo" id="tanggal_tempo" class="form-control" value="{{ old('tanggal_tempo', $pembayaran->tanggal_tempo ?? '') }}">
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" class="form-control">
        <option value="belum lunas" {{ old('status', $pembayaran->status ?? '') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
        <option value="lunas" {{ old('status', $pembayaran->status ?? '') == 'lunas' ? 'selected' : '' }}>Lunas</option>
    </select>
</div>

<div class="d-flex justify-content-between mt-4">
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
</div>
