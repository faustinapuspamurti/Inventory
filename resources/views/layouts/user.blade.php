<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <title>User</title>
</head>

<body class="bg-gray-100">
    <div class="flex flex-col min-h-screen">
        <header class="bg-white shadow-md p-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/img/logo blud.png') }}" alt="BLUD" class="h-10">
            </div>

            <div class="flex items-center">
                <span id="tanggalSekarang" class="mr-4 font-medium text-gray-700 text-xl"></span>
            </div>
        </header>

        <div class="flex flex-1">
            <aside class="w-64 bg-white shadow-md flex flex-col">
                <nav class="p-4">
                    <ul>
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-100 text-gray-800">🏠 Dashboard</a>
                        </li>

                        <li class="mt-2">
                            <a href="{{ route('stok_barang') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-100 text-gray-800">📦 Stok Barang</a>
                        </li>

                        <li>
                            <a href="{{ route('request') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-100 text-gray-800">📝 Permintaan</a>
                        </li>

                        <li>
                            <a href="{{ route('notifikasi') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-100 text-gray-800">🔔 Notifikasi</a>
                        </li>
                    </ul>
                </nav>
                <div class="px-4 py-3 border-t mt-auto">
                    <form id="logout-form" method="POST" action="{{ route('logout.post') }}">
                        @csrf
                        <button type="button" id="btn-logout"
                            class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 mt-1">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>

        <footer class="bg-white p-4 text-center text-sm text-gray-500">
            Logistik BLUD Pariwisata Banyumas
        </footer>
    </div>
</body>

</html>

<script>
    const spanTanggal = document.getElementById("tanggalSekarang");
    const hariNama = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const bulanNama = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const tanggal = new Date();
    const hari = hariNama[tanggal.getDay()];
    const tanggalAngka = tanggal.getDate();
    const bulan = bulanNama[tanggal.getMonth()];
    const tahun = tanggal.getFullYear();

    spanTanggal.textContent = `${hari}, ${tanggalAngka} ${bulan} ${tahun}`;

    const dropdowns = ['data_barang'];

    function toggleDropdown(id) {
        dropdowns.forEach(name => {
            const el = document.getElementById('dropdown-' + name);
            if (name === id) {
                el.classList.toggle('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    document.addEventListener('click', function(e) {
        const isInside = dropdowns.some(name => {
            return e.target.closest(`#dropdown-${name}`) || e.target.closest(
                `button[onclick*="${name}"]`);
        });

        if (!isInside) {
            dropdowns.forEach(name => {
                document.getElementById('dropdown-' + name).classList.add('hidden');
            });
        }
    });

    document.getElementById('btn-logout').addEventListener('click', function() {
        Swal.fire({
            title: 'Keluar dari sesi?',
            text: 'Anda akan keluar dari dashboard.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            confirmButtonColor: '#ef4444' // opsional
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
