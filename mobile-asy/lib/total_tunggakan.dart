import 'package:flutter/material.dart';

class TotalTunggakan extends StatefulWidget {
  const TotalTunggakan({super.key});

  @override
  State<TotalTunggakan> createState() => _TotalTunggakanState();
}

class _TotalTunggakanState extends State<TotalTunggakan> {
  // Dummy data tunggakan
  final List<Map<String, dynamic>> tunggakan = [
    {
      "jenis": "SPP Bulanan",
      "jumlah": 250000,
      "jatuhTempo": "10 Januari 2025",
      "selected": false,
    },
    {
      "jenis": "Uang Gedung",
      "jumlah": 1000000,
      "jatuhTempo": "01 Januari 2025",
      "selected": false,
    },
  ];

  @override
  Widget build(BuildContext context) {
    int total = tunggakan
        .where((item) => item["selected"] == true)
        .fold(0, (sum, item) => sum + (item["jumlah"] as int));

    return Scaffold(
      appBar: AppBar(
        title: const Text("Total Tunggakan"),
        backgroundColor: Colors.redAccent,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // 🔻 Info siswa
            Card(
              elevation: 2,
              child: ListTile(
                title: const Text("👤 Nama Siswa : Ahmad Rifai"),
                subtitle: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: const [
                    Text("🏫 Kelas : XII IPA 3"),
                    Text("📅 Periode : Januari 2025"),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),

            const Text(
              "Daftar Tunggakan",
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),

            Expanded(
              child: ListView.builder(
                itemCount: tunggakan.length,
                itemBuilder: (context, index) {
                  return Card(
                    elevation: 2,
                    margin: const EdgeInsets.symmetric(vertical: 6),
                    child: CheckboxListTile(
                      value: tunggakan[index]["selected"],
                      onChanged: (value) {
                        setState(() {
                          tunggakan[index]["selected"] = value!;
                        });
                      },
                      title: Text("📄 ${tunggakan[index]["jenis"]}"),
                      subtitle: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text("💰 Jumlah : Rp ${tunggakan[index]["jumlah"]}"),
                          Text(
                              "📅 Jatuh Tempo : ${tunggakan[index]["jatuhTempo"]}"),
                          const Text(
                            "⚠️ Status : Belum Bayar (Tunggakan)",
                            style: TextStyle(color: Colors.red),
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),

            // 🔻 Ringkasan total
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.red.shade50,
                borderRadius: BorderRadius.circular(10),
                border: Border.all(color: Colors.redAccent),
              ),
              child: Text(
                "💰 Total Dipilih: Rp $total",
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: Colors.redAccent,
                ),
              ),
            ),

            const SizedBox(height: 16),

            // 🔻 Tombol bayar & cicil
            Row(
              children: [
                Expanded(
                  child: ElevatedButton(
                    onPressed: total > 0
                        ? () {
                            // TODO: logic bayar penuh
                          }
                        : null,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.redAccent,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10)),
                    ),
                    child: const Text(
                      "Bayar Sekarang",
                      style: TextStyle(fontSize: 16, color: Colors.white),
                    ),
                  ),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: OutlinedButton(
                    onPressed: total > 0
                        ? () {
                            // TODO: logic cicil pembayaran
                          }
                        : null,
                    style: OutlinedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      side: const BorderSide(color: Colors.redAccent),
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10)),
                    ),
                    child: const Text(
                      "Cicil",
                      style: TextStyle(fontSize: 16, color: Colors.redAccent),
                    ),
                  ),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }
}
