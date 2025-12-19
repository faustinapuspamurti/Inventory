@extends('layouts.admin')

@section('content')
    <main class="p-6 bg-gray-50 min-h-screen flex-1"
        x-data="{ openAdd: {{ $errors->any() ? 'true' : 'false' }}, openEdit: false, editData: {} }">

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-500">
                <h4 class="text-lg font-semibold text-white">📥 Daftar Barang Masuk</h4>

                <button onclick="window.location.href='{{ route('barang_masuk.export') }}'"
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
                <form action="{{ route('barang_masuk.index') }}" method="GET"
                    style="display:flex; justify-content:space-between; align-items:center; flex-wrap:nowrap; width:100%; gap:12px;">

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

                    <div style="display:flex; align-items:center; gap:10px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..."
                            style="border:1px solid #D1D5DB; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; width:250px;">

                        <a href="{{ route('barang_masuk.index') }}"
                            style="background-color:#A1E3F9; color:#1F2937; border:none; border-radius:6px; padding:6px 14px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block;">
                            ♻️ Reset
                        </a>
                    </div>

                </form>
            </div>

            <div class="overflow-x-auto">
                <div style="max-height: 70vh; overflow-y: auto;" class="rounded-b-xl">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="bg-blue-100 text-gray-800">
                                <th class="p-4 text-sm font-semibold">No</th>
                                <th class="p-4 text-sm font-semibold">Tanggal</th>
                                <th class="p-4 text-sm font-semibold">Nama Barang</th>
                                <th class="p-4 text-sm font-semibold">Jumlah</th>
                                <th class="p-4 text-sm font-semibold">Deskripsi</th>
                                <th class="p-4 text-sm font-semibold">Bukti</th>
                                <th class="p-4 text-sm font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700">
                            @forelse ($barangMasuk as $bm)
                                <tr
                                    class="@if ($loop->even) bg-gray-50 @else bg-white @endif border-b border-gray-100 hover:bg-blue-50 transition">
                                    <td class="p-4 font-semibold text-gray-700">
                                        {{ ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="p-4">{{ \Carbon\Carbon::parse($bm->tanggal_masuk)->format('d-m-Y') }}</td>
                                    <td class="p-4">{{ $bm->barang->nama_barang }}</td>
                                    <td class="p-4">{{ $bm->jumlah_masuk }}</td>
                                    <td class="p-4">{{ $bm->deskripsi }}</td>
                                    <td class="p-4">
                                        @if ($bm->evidence)
                                            <a href="{{ Storage::url($bm->evidence) }}" target="_blank"
                                                class="text-blue-600 hover:underline">Lihat Bukti</a>
                                        @else
                                            <span class="text-gray-500">Tidak ada bukti</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <button
                                            @click="
                        openEdit = true; 
                        editData = {
                        id: {{ $bm->id }},
                        barang_id: {{ $bm->barang_id }},
                        tanggal_masuk: '{{ $bm->tanggal_masuk }}',
                        jumlah_masuk: {{ $bm->jumlah_masuk }},
                        deskripsi: '{{ $bm->deskripsi }}',
                        evidence: '{{ $bm->evidence }}',
                        nama_barang: '{{ $bm->barang->nama_barang }}',
                        }"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow-sm transition">
                                            Edit
                                        </button>

                                        <form id="delete-form-{{ $bm->id }}" method="POST"
                                            action="{{ route('barang_masuk.destroy', $bm->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-id="{{ $bm->id }}"
                                                class="delete-button bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow-sm transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4 text-gray-500">Data barang belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @php
            $perPage = $barangMasuk->perPage();
            $currentPage = $barangMasuk->currentPage();
            $lastPage = $barangMasuk->lastPage();
            $total = $barangMasuk->total();
            $from = $total ? $perPage * ($currentPage - 1) + 1 : 0;
            $to = $total ? min($perPage * $currentPage, $total) : 0;
            $queryParams = request()->only(['search', 'start_date', 'end_date']);
            $baseQuery = array_merge($queryParams, ['per_page' => $perPage]);
        @endphp
        <div class="mt-4 px-4 py-3 bg-white border border-gray-200 rounded-lg shadow-sm space-y-3">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <form method="GET" action="{{ route('barang_masuk.index') }}" class="flex items-center gap-2 text-sm">
                    @foreach ($queryParams as $key => $value)
                        @if (!is_null($value))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label for="per_page" class="text-gray-700">Tampilkan</label>
                    <select id="per_page" name="per_page"
                        class="border rounded px-2 py-1 text-sm focus:ring-blue-400 focus:border-blue-400"
                        onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" {{ $perPage === $option ? 'selected' : '' }}>
                                {{ $option }} per halaman
                            </option>
                        @endforeach
                    </select>
                    <span class="text-gray-700">item</span>
                </form>

                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700">
                    <span>Menampilkan {{ $from }}-{{ $to }} dari {{ $total }}</span>
                    <div class="flex items-center gap-2">
                        @php
                            $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
                            $nextPage = $currentPage < $lastPage ? $currentPage + 1 : $lastPage;
                        @endphp
                        <a href="{{ $currentPage === 1 ? '#' : request()->fullUrlWithQuery(array_merge($baseQuery, ['page' => 1])) }}"
                            class="px-2 py-1 border rounded {{ $currentPage === 1 ? 'text-gray-400 cursor-not-allowed bg-gray-100' : 'text-blue-600 hover:bg-blue-50' }}"
                            aria-disabled="{{ $currentPage === 1 ? 'true' : 'false' }}"><< Pertama</a>
                        <a href="{{ $currentPage === 1 ? '#' : request()->fullUrlWithQuery(array_merge($baseQuery, ['page' => $prevPage])) }}"
                            class="px-2 py-1 border rounded {{ $currentPage === 1 ? 'text-gray-400 cursor-not-allowed bg-gray-100' : 'text-blue-600 hover:bg-blue-50' }}"
                            aria-disabled="{{ $currentPage === 1 ? 'true' : 'false' }}">< Sebelumnya</a>
                        <form method="GET" action="{{ route('barang_masuk.index') }}"
                            class="flex items-center gap-2">
                            @foreach ($queryParams as $key => $value)
                                @if (!is_null($value))
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <input type="hidden" name="per_page" value="{{ $perPage }}">
                            <label for="page" class="text-gray-700">Halaman</label>
                            <input id="page" name="page" type="number" min="1" max="{{ $lastPage }}"
                                value="{{ $currentPage }}"
                                class="w-16 border rounded px-2 py-1 text-sm focus:ring-blue-400 focus:border-blue-400">
                            <span class="text-gray-600">/ {{ $lastPage }}</span>
                            <button type="submit"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Pergi</button>
                        </form>
                        <a href="{{ $currentPage === $lastPage ? '#' : request()->fullUrlWithQuery(array_merge($baseQuery, ['page' => $nextPage])) }}"
                            class="px-2 py-1 border rounded {{ $currentPage === $lastPage ? 'text-gray-400 cursor-not-allowed bg-gray-100' : 'text-blue-600 hover:bg-blue-50' }}"
                            aria-disabled="{{ $currentPage === $lastPage ? 'true' : 'false' }}">Berikutnya ></a>
                        <a href="{{ $currentPage === $lastPage ? '#' : request()->fullUrlWithQuery(array_merge($baseQuery, ['page' => $lastPage])) }}"
                            class="px-2 py-1 border rounded {{ $currentPage === $lastPage ? 'text-gray-400 cursor-not-allowed bg-gray-100' : 'text-blue-600 hover:bg-blue-50' }}"
                            aria-disabled="{{ $currentPage === $lastPage ? 'true' : 'false' }}">Terakhir >></a>
                    </div>
                </div>
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
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambah Barang Masuk</h2>
                <form action="{{ route('barang_masuk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                            <select name="barang_id" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($stoks as $st)
                                    <option value="{{ $st->id }}" {{ old('barang_id') == $st->id ? 'selected' : '' }}>
                                        {{ $st->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Jumlah</label>
                            <input type="number" name="jumlah_masuk" min="1" value="{{ old('jumlah_masuk') }}" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                            <textarea name="deskripsi"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Bukti (opsional)</label>

                            <input type="file" name="evidence"
                                class="w-full border rounded-lg px-3 py-2
                                @error('evidence') border-red-500 @enderror">

                            @error('evidence')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Barang Masuk</h2>
                <form :action="`/admin/barang_masuk/${editData.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                            <input type="date" name="tanggal_masuk" x-model="editData.tanggal_masuk" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                            <input type="text" :value="editData.nama_barang" readonly
                                class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700 outline-none">
                            <input type="hidden" name="barang_id" :value="editData.barang_id">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Jumlah</label>
                            <input type="number" name="jumlah_masuk" min="1" x-model="editData.jumlah_masuk"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editData.deskripsi"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Bukti (opsional)</label>

                            <input type="file" name="evidence"
                                class="w-full border rounded-lg px-3 py-2
                                @error('evidence') border-red-500 @enderror">

                            @error('evidence')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    Swal.fire({
                        title: 'Yakin ingin dihapus?',
                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) document.getElementById(`delete-form-${id}`)
                            .submit();
                    });
                });
            });

            @if ($errors->any())
                // Keep add modal open and show validation alert without leaving the form
                const root = document.querySelector('[x-data]');
                if (root && root.__x) {
                    root.__x.$data.openAdd = true;
                }
                Swal.fire({
                    title: 'Gagal!',
                    text: @json(implode("\n", $errors->all())),
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            @endif

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
