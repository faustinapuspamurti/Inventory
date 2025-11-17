@extends('layouts.admin')

@section('content')
    <main class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">
            Selamat Datang, {{ auth()->user()->username }}
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 max-w-6xl mx-auto">

            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-2">Total Stok Barang per Bulan</h2>
                <p class="text-3xl font-bold text-blue-600 mb-4">
                    {{ array_sum($dataBulan) }}
                </p>
                <canvas id="chartStokBarang" height="100"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-2">Barang Masuk (7 Hari Terakhir)</h2>
                <p class="text-3xl font-bold text-green-600 mb-4">
                    {{ array_sum($dataHarianMasuk) }}
                </p>
                <canvas id="chartBarangMasuk" height="100"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-2">Barang Keluar (7 Hari Terakhir)</h2>
                <p class="text-3xl font-bold text-red-600 mb-4">
                    {{ array_sum($dataHarianKeluar) }}
                </p>
                <canvas id="chartBarangKeluar" height="100"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-2">Notifikasi Baru</h2>
                <p class="text-3xl font-bold text-yellow-600 mb-4"></p>
                <canvas id="chartNotifikasi" height="100"></canvas>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const hariLabels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        const stokPerBulan = @json(array_values($dataBulan));
        const barangMasuk = @json(array_values($dataHarianMasuk));
        const barangKeluar = @json(array_values($dataHarianKeluar));

        new Chart(document.getElementById('chartStokBarang'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Total Stok',
                    data: stokPerBulan,
                    borderColor: 'rgba(37,99,235,0.9)',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(document.getElementById('chartBarangMasuk'), {
            type: 'bar',
            data: {
                labels: hariLabels,
                datasets: [{
                    label: 'Barang Masuk',
                    data: barangMasuk,
                    backgroundColor: 'rgba(34,197,94,0.7)',
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        display: false
                    }
                }
            }
        });

        new Chart(document.getElementById('chartBarangKeluar'), {
            type: 'bar',
            data: {
                labels: hariLabels,
                datasets: [{
                    label: 'Barang Keluar',
                    data: barangKeluar,
                    backgroundColor: 'rgba(239,68,68,0.7)',
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        display: false
                    }
                }
            }
        });

        new Chart(document.getElementById(''), {
            type: 'bar',
            data: {
                labels: ['Permintaan Baru'],
                datasets: [{
                    label: 'Notifikasi',
                    data: [notifBaru],
                    backgroundColor: 'rgba(234,179,8,0.8)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
