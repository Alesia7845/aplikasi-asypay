import 'package:flutter/material.dart';

class RiwayatTagihan extends StatelessWidget {
  const RiwayatTagihan({super.key});

  @override
  Widget build(BuildContext context) {
    final List<Map<String, dynamic>> riwayat = [
      {
        "nama": "SPP September",
        "tanggal": "2025-09-15",
        "jumlah": 150000,
        "status": "Lunas"
      },
      {
        "nama": "Seragam Putih",
        "tanggal": "2025-08-20",
        "jumlah": 200000,
        "status": "Lunas"
      },
    ];

    return Scaffold(
      backgroundColor: const Color(0xFFF8F6FA),
      appBar: AppBar(
        backgroundColor: Colors.lightBlue[100],
        title: const Text(
          "Riwayat Pembayaran",
          style: TextStyle(color: Colors.black),
        ),
        centerTitle: true,
        iconTheme: const IconThemeData(color: Colors.black),
      ),
      body: ListView.builder(
        itemCount: riwayat.length,
        padding: const EdgeInsets.all(16),
        itemBuilder: (context, index) {
          final item = riwayat[index];
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
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
                  item["nama"],
                  style: const TextStyle(
                      fontWeight: FontWeight.bold, fontSize: 16),
                ),
                const SizedBox(height: 6),
                Text("Tanggal: ${item["tanggal"]}"),
                Text("Jumlah: Rp ${item["jumlah"]}"),
                Text(
                  "Status: ${item["status"]}",
                  style: TextStyle(
                    color: item["status"] == "Lunas"
                        ? Colors.green
                        : Colors.orange,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
