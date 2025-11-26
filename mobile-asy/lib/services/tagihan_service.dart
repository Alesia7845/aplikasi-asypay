import 'dart:convert';
import 'package:http/http.dart' as http;

const String baseUrl =
    "https://zoogloeal-pycnidial-sunny.ngrok-free.dev/api";

class TagihanService {
  Future<Map<String, dynamic>> getTagihanSummary(String nis) async {
    final response = await http.get(
      Uri.parse("$baseUrl/tagihan/$nis"),
      headers: {
        "Accept": "application/json",
      },
    );

    print("Response dari API: ${response.body}");
    return jsonDecode(response.body);
  }

  // ============================
  // ✅ 2. Ambil daftar tagihan
  // ============================
  Future<List<dynamic>> fetchTagihanByNis(String nis) async {
    final url = Uri.parse("$baseUrl/tagihan/$nis");

    try {
      final response = await http.get(
        url,
        headers: {
          "User-Agent": "Mozilla/5.0",
          "Accept": "application/json",
        },
      );

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);

        if (json['status'] == 'success' && json['data'] != null) {
          final data = json['data'];

          if (data['daftar_tagihan'] != null &&
              data['daftar_tagihan'] is List) {
            return data['daftar_tagihan'];
          } else {
            return []; // tidak ada list
          }
        }
      }

      return [];
    } catch (e) {
      print("Error fetch tagihan: $e");
      return [];
    }
  }

  // =============================
  // ✅ 3. Ambil detail tagihan
  // =============================
  Future<Map<String, dynamic>> getDetailTagihan(
      int id, String nis, String token) async {
    final url = Uri.parse("$baseUrl/pembayaran/$id/$nis");

    final response = await http.get(
      url,
      headers: {'Authorization': 'Bearer $token'},
    );

    final data = jsonDecode(response.body);

    if (response.statusCode == 200 && data['status'] == 'success') {
      return data['data'];
    } else {
      throw Exception(data['message'] ?? "Detail tidak ditemukan");
    }
  }

  // =========================
  // ✅ 4. Bayar tagihan
  // =========================
  Future<bool> bayarTagihan(int id, String token) async {
    final url = Uri.parse("$baseUrl/pembayaran/$id/bayar");

    try {
      final response = await http.post(
        url,
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
        },
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == 'success') {
        return true;
      }

      return false;
    } catch (e) {
      throw Exception("Kesalahan: $e");
    }
  }
}
