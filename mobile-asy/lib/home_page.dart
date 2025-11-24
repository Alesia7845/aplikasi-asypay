import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:asy_pay/services/tagihan_service.dart';
import 'package:asy_pay/tagihan_aktif.dart';
import 'package:asy_pay/riwayat_tagihan.dart';
import 'package:asy_pay/profile.dart';
import 'dart:async';

// ===================================================================
//                            HOME PAGE
// ===================================================================

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

  Future<void> fetchNama() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      namaUser = prefs.getString('nama') ?? "User";
      isLoadingNama = false;
    });
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
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
        title: Image.asset('assets/logo_bawah.png', height: 32),
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

// ===================================================================
//                       HOME CONTENT (BERANDA)
// ===================================================================

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

  // Data siswa
  String nis = "-";
  String kelas = "-";
  String tahunAjaran = "-";

  // SLIDER
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<String> bannerImages = [
    "assets/poto1.png",
    "assets/poto2.png",
    "assets/poto3.png",
  ];

  @override
  void initState() {
    super.initState();
    fetchData();
    startAutoSlide();
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  // ---------------- AUTO SLIDER ----------------
  void startAutoSlide() {
    Timer.periodic(const Duration(seconds: 4), (timer) {
      if (!mounted) return;

      _currentPage = (_currentPage + 1) % bannerImages.length;

      _pageController.animateToPage(
        _currentPage,
        duration: const Duration(milliseconds: 500),
        curve: Curves.easeInOut,
      );
    });
  }

  // ---------------- FETCH DATA ----------------
  Future<void> fetchData() async {
    try {
      final prefs = await SharedPreferences.getInstance();

      nis = prefs.getString('nis') ?? "-";
      kelas = prefs.getString('kelas') ?? "-";
      tahunAjaran = prefs.getString('tahun_ajaran') ?? "-";

      if (nis != "-") {
        final result = await TagihanService().getTagihanSummary(nis);

        if (result['status'] == "success") {
          final data = result['data'];
          tagihanAktif = data['tagihan_aktif'] ?? 0;
          tunggakan = data['tunggakan'] ?? 0;
        }
      }
    } catch (e) {
      print("Error fetch data: $e");
    }

    setState(() {
      isLoading = false;
    });
  }

  // ===================================================================
  //                               UI
  // ===================================================================

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

                // ------------ CARD INFO SISWA ------------
                _infoSiswaCard(),

                const SizedBox(height: 16),

                // ------------------ SLIDER IMAGE ------------------
                SizedBox(
                  height: 180,
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(12),
                    child: PageView.builder(
                      controller: _pageController,
                      itemCount: bannerImages.length,
                      itemBuilder: (context, index) {
                        return Image.asset(
                          bannerImages[index],
                          fit: BoxFit.cover,
                        );
                      },
                    ),
                  ),
                ),

                const SizedBox(height: 10),

                // ---------------- DOT INDICATOR -----------------
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: List.generate(
                    bannerImages.length,
                    (index) => AnimatedContainer(
                      duration: const Duration(milliseconds: 300),
                      margin: const EdgeInsets.symmetric(horizontal: 4),
                      width: _currentPage == index ? 12 : 8,
                      height: 8,
                      decoration: BoxDecoration(
                        color:
                            _currentPage == index ? Colors.blue : Colors.grey,
                        borderRadius: BorderRadius.circular(10),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),

        // ---------------- BOTTOM INFO CARD -----------------
        Container(
          padding: const EdgeInsets.all(10),
          child: isLoading
              ? const Center(child: CircularProgressIndicator())
              : Row(
                  children: [
                    Expanded(
                        child:
                            _infoCard("Daftar tagihan aktif", "$tagihanAktif")),
                    const SizedBox(width: 12),
                    Expanded(child: _infoCard("Total tunggakan", "$tunggakan")),
                  ],
                ),
        ),
      ],
    );
  }

  // ------------------- CARD INFO SISWA -------------------
  Widget _infoSiswaCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 6,
            offset: const Offset(0, 3),
          )
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text("Informasi Siswa",
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          const SizedBox(height: 10),
          _rowInfo("Nama", widget.namaUser),
          _rowInfo("NIS", nis),
          _rowInfo("Kelas", kelas),
          _rowInfo("Tahun Ajaran", tahunAjaran),
        ],
      ),
    );
  }

  Widget _rowInfo(String title, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(title, style: const TextStyle(color: Colors.black54)),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  // ------------------- INFO CARD TAGIHAN -------------------
  Widget _infoCard(String title, String value) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.lightBlue[100],
        borderRadius: BorderRadius.circular(10),
      ),
      child: Column(
        children: [
          Text(title,
              textAlign: TextAlign.center,
              style:
                  const TextStyle(fontSize: 14, fontWeight: FontWeight.w600)),
          const SizedBox(height: 8),
          Text(value,
              style:
                  const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }
}
