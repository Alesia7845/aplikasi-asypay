class TunggakanModel {
  final String nama;
  final int jumlah;
  final String tanggalTempo;
  final String status;
  final Map<String, dynamic>? kategori;

  TunggakanModel({
    required this.nama,
    required this.jumlah,
    required this.tanggalTempo,
    required this.status,
    this.kategori,
  });

  factory TunggakanModel.fromJson(Map<String, dynamic> json) {
    return TunggakanModel(
      nama: json["kategori"]?["nama"] ?? json["nama"] ?? "-",
      jumlah: json["jumlah"] ?? 0,
      tanggalTempo: json["tanggal_tempo"] ?? "-",
      status: json["status"] ?? "-",
      kategori: json["kategori"],
    );
  }
}
