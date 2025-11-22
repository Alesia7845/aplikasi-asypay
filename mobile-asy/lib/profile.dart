import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:path_provider/path_provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

class Profil extends StatefulWidget {
  const Profil({super.key});

  @override
  State<Profil> createState() => _ProfilState();
}

class _ProfilState extends State<Profil> {
  String nama = "";
  String nis = "";
  String kelas = "";
  String email = "";
  String fotoPath = "";

  @override
  void initState() {
    super.initState();
    loadUserProfile();
  }

  Future<void> loadUserProfile() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      nama = prefs.getString("nama") ?? "-";
      nis = prefs.getString("nis") ?? "-";
      kelas = prefs.getString("kelas") ?? "-";
      email = prefs.getString("email") ?? "-";
      fotoPath = prefs.getString("foto") ?? ""; // SIMPAN FOTO LOKAL
    });
  }

  Future<void> pilihFoto() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      // Pastikan path_provider sudah ter-install
      final dir = await getApplicationDocumentsDirectory();
      final newPath = "${dir.path}/profile.jpg";

      // Simpan foto ke folder internal app
      final savedImage = await File(pickedFile.path).copy(newPath);

      // Simpan path ke SharedPreferences
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString("foto", savedImage.path);

      setState(() {
        fotoPath = savedImage.path;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFEFF7FF), // 💙 Warna tema baru

      body: SingleChildScrollView(
        padding: const EdgeInsets.all(18),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            const SizedBox(height: 20),

            // =====================================
            //         TITLE
            // =====================================
            const Text(
              "Profil Saya",
              style: TextStyle(
                fontSize: 26,
                fontWeight: FontWeight.bold,
                color: Color(0xFF1068A6),
              ),
            ),

            const SizedBox(height: 25),

            // =====================================
            //         PROFILE CARD
            // =====================================
            Container(
              padding: const EdgeInsets.all(22),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black12,
                    blurRadius: 10,
                    offset: Offset(0, 4),
                  )
                ],
              ),
              child: Column(
                children: [
                  // FOTO PROFIL + BUTTON GANTI FOTO
                  Stack(
                    children: [
                      CircleAvatar(
                        radius: 60,
                        backgroundColor: Colors.blue[200],
                        backgroundImage: fotoPath.isNotEmpty
                            ? FileImage(File(fotoPath))
                            : null,
                        child: fotoPath.isEmpty
                            ? const Icon(Icons.person,
                                size: 70, color: Colors.white)
                            : null,
                      ),

                      // BUTTON EDIT FOTO
                      Positioned(
                        bottom: 0,
                        right: 0,
                        child: InkWell(
                          onTap: pilihFoto,
                          child: Container(
                            padding: const EdgeInsets.all(6),
                            decoration: BoxDecoration(
                              color: Colors.blue,
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(
                              Icons.camera_alt,
                              color: Colors.white,
                              size: 20,
                            ),
                          ),
                        ),
                      )
                    ],
                  ),

                  const SizedBox(height: 20),

                  // NAMA
                  Text(
                    nama,
                    style: const TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF0E4C84),
                    ),
                  ),

                  const SizedBox(height: 10),

                  infoItem("NIS", nis),
                  infoItem("Kelas", kelas),
                  infoItem("Email", email),
                ],
              ),
            ),

            const SizedBox(height: 30),
          ],
        ),
      ),
    );
  }

  // WIDGET INFO BARIS
  Widget infoItem(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 5),
      child: Row(
        children: [
          Text(
            "$label : ",
            style: const TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w600,
              color: Color(0xFF0E4C84),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 16),
            ),
          )
        ],
      ),
    );
  }
}
