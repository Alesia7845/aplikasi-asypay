import 'dart:convert';
import 'package:http/http.dart' as http;

class TagihanService {
  final String baseUrl =
      "https://rachael-nitrolic-inscriptively.ngrok-free.dev/api";

  Future<Map<String, dynamic>> getTagihanSummary(String nis) async {
    final url = Uri.parse("$baseUrl/tagihan/$nis");

    try {
      final response = await http.get(url);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        if (data['status'] == 'success' && data['data'] != null) {
          final tagihanData = data['data'];
          return {
            'success': true,
            'aktif': tagihanData['tagihan_aktif'] ?? 0,
            'tunggakan': tagihanData['tunggakan'] ?? 0,
          };
        } else {
          return {
            'success': false,
            'message': data['message'] ?? 'Data tidak ditemukan'
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Gagal mengambil data (${response.statusCode})'
        };
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }
}
