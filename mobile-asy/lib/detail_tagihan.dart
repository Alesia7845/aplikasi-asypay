import 'package:flutter/material.dart';

class DetailTagihan extends StatelessWidget {
  final Map<String, dynamic> tagihan;

  const DetailTagihan({super.key, required this.tagihan});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8F6FA),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            boxShadow: [
              BoxShadow(
                color: Colors.black12,
                blurRadius: 5,
                offset: const Offset(0, 2),
              ),
            ],
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                tagihan["nama_tagihan"] ?? "Nama Tagihan Tidak Diketahui",
                style:
                    const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 12),
              Text("Jumlah: Rp ${tagihan["jumlah"] ?? '-'}"),
              Text("Jatuh Tempo: ${tagihan["tanggal_jatuh_tempo"] ?? '-'}"),
              const SizedBox(height: 16),
              const Text(
                "Keterangan:",
                style: TextStyle(fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              Text(tagihan["keterangan"] ?? "Tidak ada keterangan tambahan."),
              const Spacer(),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () {
                    // TODO: logika bayar nanti
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.lightBlue[100],
                    foregroundColor: Colors.black,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    padding: const EdgeInsets.all(12),
                  ),
                  child: const Text("Bayar Sekarang"),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
