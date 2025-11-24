import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

String formatTanggal(String isoDate) {
  try {
    DateTime date = DateTime.parse(isoDate);
    return "${date.day}-${date.month}-${date.year}";
  } catch (_) {
    return isoDate; // fallback
  }
}

class TagihanAktif extends StatefulWidget {
  const TagihanAktif({super.key});

  @override
  State<TagihanAktif> createState() => _TagihanAktifState();
}

class _TagihanAktifState extends State<TagihanAktif> {
  List<Map<String, dynamic>> _tagihanList = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchTagihan();
  }

  Future<void> fetchTagihan() async {
    final prefs = await SharedPreferences.getInstance();
    final nis = prefs.getString("nis") ?? "";
    final url = Uri.parse(
        "https://rachael-nitrolic-inscriptively.ngrok-free.dev/api/tagihan/$nis");

    try {
      final response = await http.get(url);
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        if (data["status"] == "success" && data["data"] != null) {
          final daftar = data["data"]["daftar_tagihan"] ?? [];

          setState(() {
            _tagihanList = List<Map<String, dynamic>>.from(daftar);
            isLoading = false;
          });
        } else {
          setState(() => isLoading = false);
        }
      } else {
        setState(() => isLoading = false);
      }
    } catch (e) {
      print("Error: $e");
      setState(() => isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.lightBlue[100],
      appBar: AppBar(
        backgroundColor: Colors.lightBlue[100],
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.black),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          "Pembayaran",
          style: TextStyle(
            color: Colors.black,
            fontWeight: FontWeight.bold,
          ),
        ),
        centerTitle: true,
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : _tagihanList.isEmpty
              ? const Center(
                  child: Text(
                    "Tidak ada tagihan aktif",
                    style: TextStyle(fontSize: 16, color: Colors.black54),
                  ),
                )
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _tagihanList.length,
                  itemBuilder: (context, index) {
                    final item = _tagihanList[index];

                    final nama = item["kategori"]?["nama"] ?? "-";
                    final jumlah = item["jumlah"] ?? 0;
                    final tempo = item["tanggal_tempo"] != null
                        ? formatTanggal(item["tanggal_tempo"])
                        : "-";
                    return Padding(
                      padding: const EdgeInsets.only(bottom: 12),
                      child: Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(12),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black12,
                              blurRadius: 4,
                              offset: Offset(0, 2),
                            ),
                          ],
                        ),
                        child: Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // =====================================================
                            // KIRI — Nama, Jumlah, Tanggal
                            // =====================================================
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    nama,
                                    style: const TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    "Rp ${jumlah.toString()}",
                                    style: const TextStyle(fontSize: 14),
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    "Jatuh Tempo: ${formatTanggal(tempo)}",
                                    style: const TextStyle(
                                      fontSize: 12,
                                      color: Colors.black54,
                                    ),
                                  ),
                                ],
                              ),
                            ),

                            const SizedBox(width: 12),

                            // =====================================================
                            // KANAN — Status + Tombol Bayar
                            // =====================================================
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.end,
                              children: [
                                // STATUS
                                Text(
                                  item["status"] == "belum lunas"
                                      ? "BELUM"
                                      : "LUNAS",
                                  style: TextStyle(
                                    fontSize: 14,
                                    fontWeight: FontWeight.bold,
                                    color: item["status"] == "belum lunas"
                                        ? Colors.orange
                                        : Colors.green,
                                  ),
                                ),

                                const SizedBox(height: 8),

                                Container(
                                  width: 80, // ikuti lebar tombol
                                  height: 1.2,
                                  color: Colors.grey[300],
                                ),

                                const SizedBox(height: 10),

                                // TOMBOL BAYAR (hanya muncul jika belum lunas)
                                if (item["status"] == "belum lunas")
                                  SizedBox(
                                    width: 80,
                                    height: 32,
                                    child: ElevatedButton(
                                      onPressed: () {
                                        // pindah ke detail pembayaran
                                      },
                                      style: ElevatedButton.styleFrom(
                                        backgroundColor: const Color.fromARGB(
                                            255, 130, 166, 229),
                                        padding: EdgeInsets.zero,
                                        shape: RoundedRectangleBorder(
                                          borderRadius:
                                              BorderRadius.circular(6),
                                        ),
                                      ),
                                      child: const Text(
                                        "Bayar",
                                        style: TextStyle(fontSize: 12),
                                      ),
                                    ),
                                  ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}
