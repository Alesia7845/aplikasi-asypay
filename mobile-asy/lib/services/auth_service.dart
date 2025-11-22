import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:async';

class AuthService {
  final String baseUrl =
      "https://rachael-nitrolic-inscriptively.ngrok-free.dev/api"; // <-- tambahkan /api di sini

  Future<Map<String, dynamic>> login(String nis, String password) async {
    try {
      final url = Uri.parse('$baseUrl/login');
      final response = await http.post(
        url,
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({"nis": nis, "password": password}),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == 'success') {
        // Simpan token di local storage
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', data['token']);
        await prefs.setString('nis', data['user']['nis']);
        await prefs.setString('nama', data['user']['name']);
        await prefs.setString('kelas', data['user']['kelas']);
        await prefs.setString('email', data['user']['email']);

        return {
          'success': true,
          'message': data['message'],
          'user': data['user']
        };
      } else {
        return {'success': false, 'message': data['message'] ?? 'Login gagal'};
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  // Cek apakah user masih login
  Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.containsKey('token');
  }

  // Ambil token
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  // Logout
  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
  }
}
