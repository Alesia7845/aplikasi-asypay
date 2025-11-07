import 'package:flutter/material.dart';
import 'login_page.dart';
import 'home_page.dart';
import 'tagihan_aktif.dart';
import 'total_tunggakan.dart';
import 'riwayat_tagihan.dart';
import 'profile.dart';
import 'services/auth_service.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Asy-Pay',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      initialRoute: '/',
      routes: {
        '/': (context) => const LoginPage(),
        '/home': (context) => const HomePage(),
        '/tagihan_aktif': (context) => const TagihanAktif(),
        '/total_tunggakan': (context) => const TotalTunggakan(),
        '/riwayat_tagihan': (context) => const RiwayatTagihan(),
        '/profile': (context) => const Profil(),
      },
    );
  }
}
