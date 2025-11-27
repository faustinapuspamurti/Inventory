@extends('layouts.user')

@section('content')
    <main class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold mb-6">Selamat Datang, {{ auth()->user()->username }} 👋</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-2 text-gray-700">Barang Masuk per Minggu</h2>
                <p class="text-3xl font-bold text-green-600 mb-4">{{ $totalMingguIni }}</p>
                <canvas id="chartBarangHariIni" height="100"></canvas>
            </div>
        </div>
    </main>

    <script>
        const ctx2 = document.getElementById('chartBarangHariIni').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
             data: {
                labels: ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'],
                datasets: [{
                    label: 'Barang Masuk',
                    data: @json($chartMingguan),
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
