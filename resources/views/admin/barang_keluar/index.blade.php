@extends('layouts.admin')

@section('content')
    <main class="p-6 bg-gray-50 min-h-screen flex-1" x-data="{ openAdd: false }">

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-500">
                <h4 class="text-lg font-semibold text-white">📤 Daftar Barang Keluar</h4>
                <!-- Tombol Export Excel -->
                <button onclick="window.location.href='{{ route('barang_keluar.export') }}'"
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
            <!-- Filter dan Pencarian -->
            <div
                style="display:flex; justify-content:space-between; align-items:center; padding:12px 24px; background-color:#F8FEFE; border-bottom:1px solid #A1E3F9;">
                <form action="{{ route('barang_keluar.index') }}" method="GET"
                    style="display:flex; justify-content:space-between; align-items:center; flex-wrap:nowrap; width:100%; gap:12px;">

                    <!-- Bagian kiri: Filter tanggal -->
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="font-size:13px; color:#6B7280;">Dari</span>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                style="border:1px solid #A1E3F9; border-radius:6px; padding:6px 10px; font-size:14px; color:#374151; background-color:#fff; outline:none;">
                        </div>

                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="font-size:13px; color:#6B7280;">Sampai</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                style="border:1px solid #A1E3F9; border-radius:6px; padding:6px 10px; font-size:14px; color:#374151; background-color:#fff; outline:none;">
                        </div>

                        <button type="submit"
                            style="background-color:#33A8C7; color:#fff; border:none; border-radius:6px; padding:6px 14px; font-size:13px; cursor:pointer; transition:background-color 0.2s ease;">
                            🔍 Filter
                        </button>
                    </div>

                    <!-- Bagian kanan: Pencarian dan Reset -->
                    <div style="display:flex; align-items:center; gap:10px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..."
                            style="border:1px solid #D1D5DB; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; width:250px;">

                        <a href="{{ route('barang_keluar.index') }}"
                            style="background-color:#A1E3F9; color:#1F2937; border:none; border-radius:6px; padding:6px 14px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block;">
                            ♻️ Reset
                        </a>
                    </div>

                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-blue-100 text-gray-800">
                            <th class="p-4 text-sm font-semibold">No</th>
                            <th class="p-4 text-sm font-semibold">Tanggal</th>
                            <th class="p-4 text-sm font-semibold">Lokawisata</th>
                            <th class="p-4 text-sm font-semibold">Nama Barang</th>
                            <th class="p-4 text-sm font-semibold">Jumlah</th>
                            <th class="p-4 text-sm font-semibold">Harga Satuan</th>
                            <th class="p-4 text-sm font-semibold">Harga Total</th>
                            <th class="p-4 text-sm font-semibold">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700">
                        @forelse ($keluars as $row)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="p-4 font-semibold text-gray-700">{{ $loop->iteration }}</td>
                                <td class="p-4">
                                    {{ \Carbon\Carbon::parse($row->tanggal_keluar)->format('d-m-Y') }}
                                </td>
                                </td>
                                <td class="p-4">{{ $row->lokawisata->nama_lokawisata }}</td>
                                <td class="p-4">{{ $row->barang->nama_barang }}</td>
                                <td class="p-4">{{ $row->jumlah_keluar }}</td>
                                <td class="p-4">{{ $row->harga_satuan }}</td>
                                <td class="p-4">{{ $row->harga_total }}</td>
                                <td class="p-4">{{ $row->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-6 text-gray-500">Belum ada data barang keluar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Floating Button Tambah Barang -->
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

        <div x-show="openAdd" x-transition.opacity
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
            <div @click.away="openAdd = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 transform transition-all scale-100">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambah Barang Keluar</h2>
                <form action="{{ route('barang_keluar.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                            <input type="date" name="tanggal_keluar"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lokawisata</label>
                            <select name="lokawisata_id"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                                required>
                                <option value="">-- Pilih Lokawisata --</option>
                                @foreach ($wisatas as $lok)
                                    <option value="{{ $lok->id }}">{{ $lok->nama_lokawisata }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                            <select name="barang_id"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                                required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Jumlah</label>
                            <input type="number" name="jumlah_keluar" min="1"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Keterangan</label>
                            <textarea name="keterangan" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                                required></textarea>
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
    </main>

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
@endsection
