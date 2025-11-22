<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik Pembayaran Per Bulan - ASY PAY</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        #chart-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div id="chart-container">
        <h2>Grafik Pembayaran Per Bulan</h2>
        <canvas id="chartPembayaran" height="400"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('chartPembayaran').getContext('2d');

        const chartPembayaran = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Total Pembayaran (Rp)',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Total Pembayaran Siswa per Bulan (Real-Time)'
                    }
                }
            }
        });

        // Ambil data dari endpoint API
        function fetchData() {
            fetch('/api/chart-data')
                .then(response => response.json())
                .then(data => {
                    chartPembayaran.data.labels = data.labels;
                    chartPembayaran.data.datasets[0].data = data.values;
                    chartPembayaran.update();
                })
                .catch(error => {
                    console.error('Gagal memuat data chart:', error);
                });
        }

        fetchData();
        setInterval(fetchData, 10000); // Refresh setiap 10 detik
    </script>
</body>
</html>
