import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

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
            "Daftar tagihan aktif",
            style: TextStyle(
              color: Colors.black,
              fontWeight: FontWeight.bold,
            ),
          ),
          centerTitle: true,
        ),
        body: isLoading
            ? const Center(child: CircularProgressIndicator())
            : ListView.builder(
                padding: const EdgeInsets.all(12),
                itemCount: _tagihanList.length,
                itemBuilder: (context, index) {
                  final item = _tagihanList[index];

                  // Ambil nama kategori dari nested object
                  final nama = item["kategori"]?["nama"] ?? "-";

                  final jumlah = item["jumlah"] ?? 0;
                  final tanggalTempo = item["tanggal_tempo"] ?? "-";

                  return Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: GestureDetector(
                      onTap: () {
                        showDialog(
                          context: context,
                          builder: (context) => AlertDialog(
                            title: Text(nama),
                            content: Text(
                              "Jumlah: Rp. $jumlah\n"
                              "Jatuh Tempo: $tanggalTempo",
                            ),
                          ),
                        );
                      },
                      child: Container(
                        padding: const EdgeInsets.all(12),
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
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              nama,
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 16,
                              ),
                            ),
                            SizedBox(height: 4),
                            Text(
                              "Rp. $jumlah",
                              style: const TextStyle(fontSize: 14),
                            ),
                            SizedBox(height: 4),
                            Text(
                              "Jatuh Tempo: $tanggalTempo",
                              style: const TextStyle(
                                fontSize: 12,
                                color: Colors.black54,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  );
                },
              ));
  }
}
