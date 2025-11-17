@extends('layouts.user')

@section('content')
    <main class="p-6 bg-gray-50 min-h-screen flex-1" x-data="{
        openReq: false,
        editData: {},
        init() {
            window.addEventListener('close-request-modal', () => {
                this.openReq = false;
            });
        }
    }">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">📦 Stok Barang BLUD</h3>
            <form action="" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..."
                    class="border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none px-4 py-2 rounded-lg w-72 transition-all">
            </form>
        </div>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-500">
                <h4 class="text-lg font-semibold text-white">Daftar Stok Barang BLUD</h4>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-blue-100 text-gray-800">
                            <th class="p-4 text-sm font-semibold">No</th>
                            <th class="p-4 text-sm font-semibold">Nama Barang</th>
                            <th class="p-4 text-sm font-semibold">Jumlah</th>
                            <th class="p-4 text-sm font-semibold">Deskripsi</th>
                            <th class="p-4 text-sm font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700">
                        @forelse ($stoks as $st)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="p-4 font-semibold text-gray-700">{{ $loop->iteration }}</td>
                                <td class="p-4">{{ $st->nama_barang }}</td>
                                <td class="p-4">{{ $st->jumlah_stok }}</td>
                                <td class="p-4">{{ $st->deskripsi }}</td>
                                <td class="p-4 text-center">
                                    @if ($st->jumlah_stok > 0)
                                        <button @click="openReq = true; editData = {{ json_encode($st) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow-sm transition">
                                            Request
                                        </button>
                                    @else
                                        <span
                                            class="bg-gray-400 text-white px-3 py-1 rounded-lg text-sm font-medium cursor-not-allowed shadow-sm">
                                            Habis
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data stok.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div x-show="openReq" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📝 Request Barang</h2>

                <form id="reqForm" method="POST" action="{{ route('request.store') }}">
                    @csrf
                    <input type="hidden" name="barang_id" :value="editData.id">

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Nama Barang</label>
                        <input type="text" x-model="editData.nama_barang" readonly
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-1">Jumlah yang di-request</label>
                        <input type="number" name="jumlah" min="1" placeholder="Masukkan jumlah..."
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none rounded-lg px-4 py-2"
                            required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="openReq = false"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 1000
});
</script>
@endif
@endsection
