import 'package:flutter/material.dart';

class KonfirmasiTunaiPage extends StatelessWidget {
  final int total;
  final String namaTagihan;

  KonfirmasiTunaiPage({required this.total, required this.namaTagihan});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Ringkasan Pembayaran")),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Card(
              child: ListTile(
                title: Text(namaTagihan,
                    style: TextStyle(fontWeight: FontWeight.bold)),
                subtitle: Text("Rp. $total\nJatuh Tempo: 23-09-25"),
              ),
            ),
            SizedBox(height: 20),
            Container(
              padding: EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: Colors.blue.shade100,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Column(
                children: [
                  Icon(Icons.check_circle, size: 50, color: Colors.black),
                  SizedBox(height: 12),
                  Text(
                    "Silakan bayar langsung ke bendahara madrasah,\nstatus akan diverifikasi oleh admin setelah pembayaran diterima.",
                    textAlign: TextAlign.center,
                    style: TextStyle(fontSize: 14),
                  ),
                ],
              ),
            ),
            Spacer(),
            ElevatedButton(
              onPressed: () {
                Navigator.popUntil(context, (route) => route.isFirst);
              },
              child: Text("Kembali ke Beranda"),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.blue,
                foregroundColor: Colors.white,
                minimumSize: Size(double.infinity, 50),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
