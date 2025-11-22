import 'dart:convert';
import 'package:http/http.dart' as http;

class TunggakanService {
  final String baseUrl =
      "https://rachael-nitrolic-inscriptively.ngrok-free.dev/api";

  Future<List<dynamic>> getTunggakan(String nis) async {
    final url = Uri.parse("$baseUrl/tagihan/tunggakan/$nis");

    final response = await http.get(url);
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);

      if (data['status'] == 'success') {
        return data['data'] ?? [];
      }
    }

    return [];
  }
}
