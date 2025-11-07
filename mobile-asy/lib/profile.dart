import 'package:flutter/material.dart';

class Profil extends StatelessWidget {
  const Profil({super.key});

  @override
  Widget build(BuildContext context) {
    final Map<String, dynamic> siswa = {
      "nama": "Salsa Nabila",
      "nis": "2305002",
      "kelas": "XII MIPA 3",
      "email": "salsa@madrasah.sch.id"
    };

    return Scaffold(
      backgroundColor: const Color(0xFFF8F6FA),
      appBar: AppBar(
        backgroundColor: Colors.lightBlue[100],
        title: const Text(
          "Profil Saya",
          style: TextStyle(color: Colors.black),
        ),
        centerTitle: true,
        iconTheme: const IconThemeData(color: Colors.black),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            const CircleAvatar(
              radius: 50,
              backgroundColor: Colors.blueAccent,
              child: Icon(Icons.person, size: 60, color: Colors.white),
            ),
            const SizedBox(height: 20),
            Text(
              siswa["nama"],
              style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            Text("NIS: ${siswa["nis"]}"),
            const SizedBox(height: 12),
            Text("Kelas: ${siswa["kelas"]}"),
            Text("Email: ${siswa["email"]}"),
            const SizedBox(height: 24),
            ElevatedButton.icon(
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.lightBlue[100],
                foregroundColor: Colors.black,
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
              ),
              onPressed: () {
                // TODO: log out nanti
              },
              icon: const Icon(Icons.logout),
              label: const Text("Keluar"),
            ),
          ],
        ),
      ),
    );
  }
}
