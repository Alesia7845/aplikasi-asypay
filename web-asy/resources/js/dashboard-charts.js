
document.addEventListener('DOMContentLoaded', function () {
    // Data awal kosong, nanti diupdate dari backend
    let months = []; // nanti diisi data bulan dari API / backend
    let totals = []; // data total pembayaran

    // Ambil elemen canvas
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    const ctxBar = document.getElementById('barChart').getContext('2d');

    // Inisialisasi Line Chart kosong dulu
    const lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Pembayaran',
                data: totals,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1000000,
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Inisialisasi Bar Chart kosong dulu
    const barChart = new Chart(ctxBar, {
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
                    text: 'Grafik Real-Time Pembayaran per Bulan'
                },
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Fungsi fetch data line chart dari API (misal endpoint: /api/line-chart-data)
    function fetchLineChartData() {
        fetch('/api/line-chart-data')
            .then(response => response.json())
            .then(data => {
                // Update label dan data lineChart
                lineChart.data.labels = data.months.map(m => `Bulan ${m}`);
                lineChart.data.datasets[0].data = data.totals;
                lineChart.update();
            })
            .catch(err => console.error('Gagal fetch line chart:', err));
    }

    // Fungsi fetch data bar chart dari API (/api/chart-data)
    function fetchBarChartData() {
        fetch('/api/chart-data')
            .then(response => response.json())
            .then(data => {
                barChart.data.labels = data.labels;
                barChart.data.datasets[0].data = data.values;
                barChart.update();
            })
            .catch(err => console.error('Gagal fetch bar chart:', err));
    }

    // Jalankan fetch pertama kali dan interval update
    fetchLineChartData();
    fetchBarChartData();
    setInterval(fetchBarChartData, 10000); // update bar chart setiap 10 detik
    setInterval(fetchLineChartData, 60000); // update line chart setiap 60 detik (optional)
});
