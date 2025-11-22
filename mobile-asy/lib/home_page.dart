import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:asy_pay/services/tagihan_service.dart';
import 'package:asy_pay/tagihan_aktif.dart';
import 'package:asy_pay/total_tunggakan.dart';
import 'package:asy_pay/riwayat_tagihan.dart';
import 'package:asy_pay/profile.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  int _selectedIndex = 0;

  String namaUser = "";
  bool isLoadingNama = true;

  @override
  void initState() {
    super.initState();
    fetchNama();
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  Future<void> fetchNama() async {
    final prefs = await SharedPreferences.getInstance();

    setState(() {
      namaUser = prefs.getString('nama') ?? "User";
      isLoadingNama = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    final pages = [
      HomeContent(namaUser: namaUser, isLoadingNama: isLoadingNama),
      const TagihanAktif(),
      const RiwayatTagihan(),
      const Profil(),
    ];

    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.lightBlue[100],
        elevation: 0,
        title: Row(
          children: [
            Image.asset(
              'assets/logo_bawah.png',
              height: 32,
            ),
            const SizedBox(width: 8),
          ],
        ),
        actions: [
          IconButton(
            icon:
                const Icon(Icons.notifications_outlined, color: Colors.black87),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.logout, color: Colors.black87),
            onPressed: () {
              Navigator.pushReplacementNamed(context, '/');
            },
          ),
        ],
      ),
      body: pages[_selectedIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: _onItemTapped,
        selectedItemColor: Colors.black87,
        unselectedItemColor: Colors.black45,
        backgroundColor: Colors.lightBlue[100],
        type: BottomNavigationBarType.fixed,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Beranda'),
          BottomNavigationBarItem(
              icon: Icon(Icons.receipt_long), label: 'Tagihan'),
          BottomNavigationBarItem(icon: Icon(Icons.history), label: 'Riwayat'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profil'),
        ],
      ),
    );
  }
}

// ---------------------------------------------------------
// ------------------- HOME CONTENT ------------------------
// ---------------------------------------------------------

class HomeContent extends StatefulWidget {
  final String namaUser;
  final bool isLoadingNama;

  const HomeContent({
    super.key,
    required this.namaUser,
    required this.isLoadingNama,
  });

  @override
  State<HomeContent> createState() => _HomeContentState();
}

class _HomeContentState extends State<HomeContent> {
  int tagihanAktif = 0;
  int tunggakan = 0;
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  // --------------------- FETCH DATA ------------------------

  Future<void> fetchData() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final nis = prefs.getString('nis') ?? "";
      print("NIS dari SharedPreferences: $nis");

      if (nis.isEmpty) {
        setState(() {
          isLoading = false;
        });
        return;
      }

      final tagihanService = TagihanService();
      final result = await tagihanService.getTagihanSummary(nis);

      print("Hasil API Home Page: $result");

      if (result['status'] == "success") {
        final data = result['data'];

        setState(() {
          tagihanAktif = data['tagihan_aktif'] ?? 0;
          tunggakan = data['tunggakan'] ?? 0;
        });
      } else {
        print("API tidak mengembalikan success");
      }
    } catch (e) {
      print("Error fetch data: $e");
    }

    setState(() {
      isLoading = false;
    });
  }

  // ---------------------------------------------------------
  // ---------------------- UI --------------------------------
  // ---------------------------------------------------------

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Expanded(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  widget.isLoadingNama
                      ? "Memuat..."
                      : "Selamat Datang, ${widget.namaUser}!",
                  style: const TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 16),
                ClipRRect(
                  borderRadius: BorderRadius.circular(12),
                  child: Image.asset(
                    "assets/poto1.png",
                    fit: BoxFit.cover,
                  ),
                ),
              ],
            ),
          ),
        ),

        // Bagian Bawah
        Container(
          padding: const EdgeInsets.all(10),
          child: isLoading
              ? const Center(child: CircularProgressIndicator())
              : Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: [
                    Expanded(
                      child: GestureDetector(
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (_) => const TagihanAktif(),
                            ),
                          );
                        },
                        child: _infoCard(
                          "Daftar tagihan aktif",
                          tagihanAktif.toString(),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: GestureDetector(
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                                builder: (_) => const TotalTunggakan()),
                          );
                        },
                        child: _infoCard(
                          "Total tunggakan",
                          tunggakan.toString(),
                        ),
                      ),
                    ),
                  ],
                ),
        ),
      ],
    );
  }

  Widget _infoCard(String title, String value) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.lightBlue[100],
        borderRadius: BorderRadius.circular(10),
      ),
      child: Column(
        children: [
          Text(
            title,
            textAlign: TextAlign.center,
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600),
          ),
          const SizedBox(height: 6),
          Text(
            value,
            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }
}
