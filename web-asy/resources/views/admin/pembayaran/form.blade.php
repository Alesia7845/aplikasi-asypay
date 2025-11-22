 <form action="{{ route('admin.pembayaran.store') }}" method="POST">
        @csrf

        <table class="w-full border border-gray-300">
            <tbody>
                <!-- Nama -->
                <tr class="border-b border-gray-300">
                    <td class="p-3 font-medium w-1/3">Nama <span class="text-red-500">*</span></td>
                    <td class="p-3">
                        <input type="text" name="nama" id="nama" class="w-full border rounded px-3 py-2"
                            value="{{ old('nama', $pembayaran->nama ?? '') }}" required>
                        @error('nama') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </td>
                </tr>

                <!-- NIS -->
                <tr class="border-b border-gray-300">
                    <td class="p-3 font-medium">NIS <span class="text-red-500">*</span></td>
                    <td class="p-3">
                        <input type="text" name="nis" id="nis" class="w-full border rounded px-3 py-2"
                            value="{{ old('nis', $pembayaran->nis ?? '') }}" required>
                        @error('nis') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </td>
                </tr>
                
                <!-- Kelas -->
                <tr class="border-b border-gray-300">
                    <td class="p-3 font-medium">Kelas</td>
                    <td class="p-3">
                        <input type="text" name="kelas" id="kelas" class="w-full border rounded px-3 py-2"
                            value="{{ old('kelas', $pembayaran->kelas ?? '') }}">
                        @error('kelas') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </td>
                </tr>

                <!-- Jumlah -->
                <tr class="border-b border-gray-300">
                    <td class="p-3 font-medium">Jumlah (Rp) <span class="text-red-500">*</span></td>
                    <td class="p-3">
                        <input type="number" name="jumlah" id="jumlah" class="w-full border rounded px-3 py-2"
                            value="{{ old('jumlah', $pembayaran->jumlah ?? '') }}" required>
                        @error('jumlah') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </td>
                </tr>

                <!-- Tanggal Buat -->
                <tr class="border-b border-gray-300">
                    <td class="p-3 font-medium">Tanggal Buat <span class="text-red-500">*</span></td>
                    <td class="p-3">
                        <input type="date" name="tanggal_buat" id="tanggal_buat" class="w-full border rounded px-3 py-2"
                            value="{{ old('tanggal_buat', isset($pembayaran->tanggal_buat) ? \Carbon\Carbon::parse($pembayaran->tanggal_buat)->format('Y-m-d') : '') }}"
                            required>
                        @error('tanggal_buat') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </td>
                </tr>

                <!-- Tanggal Tempo -->
                <tr class="border-b border-gray-300">
                    <td class="p-3 font-medium">Tanggal Tempo</td>
                    <td class="p-3">
                        <input type="date" name="tanggal_tempo" id="tanggal_tempo" class="w-full border rounded px-3 py-2"
                            value="{{ old('tanggal_tempo', isset($pembayaran->tanggal_tempo) ? \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->format('Y-m-d') : '') }}">
                        @error('tanggal_tempo') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </td>
                </tr>

                <!-- Kategori -->
                <tr class="border-b border-gray-300">
                    <td class="p-3 font-medium">Kategori <span class="text-red-500">*</span></td>
                    <td class="p-3">
                        <select name="kategori_id" id="kategori_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->id }}" 
                                    {{ old('kategori_id', $pembayaran->kategori_id ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Tombol -->
        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
            <a href="{{ route('admin.pembayaran.index') }}" class="text-gray-600 hover:underline">Kembali</a>
        </div>
    </form>