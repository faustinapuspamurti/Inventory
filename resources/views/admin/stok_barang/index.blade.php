@extends('layouts.admin')

@section('content')
    <main class="p-6 bg-gray-50 min-h-screen flex-1" x-data="{ openAdd: false, openEdit: false, editData: {} }">

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-500">
                <h4 class="text-lg font-semibold text-white">📦 Daftar Stok Barang</h4>

                <button onclick="window.location.href='{{ route('barang.export') }}'"
                    style="
                        background-color:#ffffff;
                        color:#2563EB;
                        font-size:14px;
                        font-weight:600;
                        border:none;
                        border-radius:8px;
                        padding:8px 16px;
                        cursor:pointer;
                        transition:all 0.2s ease;
                        box-shadow:0 1px 3px rgba(0,0,0,0.1);
                        display:flex;
                        align-items:center;
                        gap:6px;
                        "
                    onmouseover="this.style.backgroundColor='#DBEAFE'" onmouseout="this.style.backgroundColor='#ffffff'">
                    📑 Export Excel
                </button>
            </div>
            <div
                style="display:flex; justify-content:space-between; align-items:center; padding:12px 24px; background-color:#F8FEFE; border-bottom:1px solid #A1E3F9;">
                <form action="{{ route('stok_barang.index') }}" method="GET"
                    style="display:flex; justify-content:space-between; align-items:center; flex-wrap:nowrap; width:100%; gap:12px;">

                    <div style="display:flex; align-items:center; gap:10px;">
                    </div>

                    <!-- Bagian kanan: Pencarian dan Reset -->
                    <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px; margin-bottom:10px;">
                        <form action="{{ route('stok_barang.index') }}" method="GET"
                            style="display:flex; align-items:center; gap:10px;">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari barang..."
                                style="border:1px solid #D1D5DB; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; width:250px;">
                            <button type="submit"
                                style="background-color:#3B82F6; color:white; border:none; border-radius:6px; padding:6px 14px; font-size:13px; cursor:pointer;">
                                🔍 Search
                            </button>
                            <a href="{{ route('stok_barang.index') }}"
                                style="background-color:#A1E3F9; color:#1F2937; border:none; border-radius:6px; padding:6px 14px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block;">
                                ♻️ Reset
                            </a>
                        </form>
                    </div>


                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-blue-100 text-gray-800">
                            <th class="p-4 text-sm font-semibold">No</th>
                            <th class="p-4 text-sm font-semibold">Nama Barang</th>
                            <th class="p-4 text-sm font-semibold">Jumlah</th>
                            <th class="p-4 text-sm font-semibold">Satuan</th>
                            <th class="p-4 text-sm font-semibold">Harga Satuan</th>
                            <th class="p-4 text-sm font-semibold">Harga Total</th>
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
                                <td class="p-4">{{ $st->satuan }}</td>
                                <td class="p-4">{{ $st->harga_satuan }}</td>
                                <td class="p-4">{{ $st->harga_total }}</td>
                                <td class="p-4">{{ $st->deskripsi }}</td>
                                <td class="p-4 text-center">
                                    <button @click="openEdit = true; editData = {{ json_encode($st) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow-sm transition">
                                        Edit
                                    </button>
                                    <form id="delete-form-{{ $st->id }}" method="POST"
                                        action="{{ route('stok_barang.destroy', $st->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            data-id="{{ $st->id }}"class="delete-button bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow-sm transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-4 text-gray-500">Data barang belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <button @click="openAdd = true"
            style="
      position:fixed;
      bottom:30px;
      right:30px;
      background-color:#33A8C7;
      color:white;
      border:none;
      border-radius:50%;
      width:60px;
      height:60px;
      font-size:28px;
      font-weight:bold;
      box-shadow:0 4px 10px rgba(0,0,0,0.2);
      cursor:pointer;
      transition:all 0.3s ease;
    "
            onmouseover="this.style.backgroundColor='#2D8FAB'" onmouseout="this.style.backgroundColor='#33A8C7'">
            +
        </button>

        <div x-show="openAdd" x-cloak x-transition.opacity
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
            <div @click.away="openAdd = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 transform transition-all scale-100">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambah Stok Barang</h2>
                <form action="{{ route('stok_barang.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                            <input type="text" name="nama_barang" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Jumlah</label>
                            <input type="number" name="jumlah_stok"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                            <input type="text" name="satuan" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Harga Satuan</label>
                            <input type="text" name="harga_satuan" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openAdd = false"
                            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openEdit" x-cloak x-transition.opacity
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
            <div @click.away="openEdit = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 transform transition-all scale-100">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Barang</h2>
                <form :action="`/admin/stok_barang/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                            <input type="text" name="nama_barang" x-model="editData.nama_barang"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Jumlah</label>
                            <input type="number" name="jumlah_stok" x-model="editData.jumlah_stok"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                            <input type="text" name="toko" x-model="editData.satuan"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">harga Satuan</label>
                            <input type="text" name="harga_satuan" x-model="editData.harga_satuan"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editData.deskripsi"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const adminId = this.getAttribute('data-id');

                Swal.fire({
                    title: "Yakin ingin dihapus?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${adminId}`).submit();
                    }
                });
            });
        });
    });

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || window.performance.getEntriesByType("navigation")[0]?.type === "back_forward") {
            return;
        }

        @if (session('error'))
            Swal.fire({
                title: "Gagal!",
                text: @json(session('error')),
                icon: "error",
                confirmButtonColor: "#d33"
            });
        @endif

        @if (session('success'))
            Swal.fire({
                title: "Berhasil!",
                text: @json(session('success')),
                icon: "success",
                confirmButtonColor: "#3085d6"
            });
        @endif
    });
</script>
