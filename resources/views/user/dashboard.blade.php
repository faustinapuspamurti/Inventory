@extends('layouts.user')

@section('content')
    <main class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold mb-6">Selamat Datang, {{ auth()->user()->username }} 👋</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 max-w-6xl mx-auto">

            <!-- CARD 1: Jumlah Barang Masuk per Bulan -->
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-2 text-gray-700">Jumlah Barang Masuk</h2>
                <p class="text-3xl font-bold text-blue-600 mb-4">1,250</p>
                <canvas id="chartJumlahBarang" height="100"></canvas>
            </div>

            <!-- CARD 2: Barang Masuk per Hari -->
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-2 text-gray-700">Barang Masuk per Minggu</h2>
                <p class="text-3xl font-bold text-green-600 mb-4">10</p>
                <canvas id="chartBarangHariIni" height="100"></canvas>
            </div>

        </div>
    </main>


    <script>
        const ctx1 = document.getElementById('chartJumlahBarang').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ],
                datasets: [{
                    label: 'Total Barang Masuk',
                    data: [120, 180, 220, 150, 300, 250, 270, 320, 400, 350, 370, 390],
                    borderColor: 'rgba(37, 99, 235, 0.9)',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: true
                    },
                    y: {
                        display: false
                    }
                }
            }
        });

        const ctx2 = document.getElementById('chartBarangHariIni').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Barang Masuk',
                    data: [0, 0, 0, 5, 5, 0, 0], // Kamis & Jumat = 5
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderRadius: 6,
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: true
                    },
                    y: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
