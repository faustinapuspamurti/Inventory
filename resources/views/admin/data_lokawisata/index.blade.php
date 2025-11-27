@extends('layouts.admin')

@section('content')
    <main class="p-6 bg-gray-50 min-h-screen flex-1" x-data="{ openAdd: false, openEdit: false, editData: {} }">

        <div class="flex justify-between items-center mb-8">
            <h3 class="text-2xl font-semibold text-gray-800">🏔️ Lokawisata</h3>

            <button @click="openAdd = true"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition-all shadow-sm">
                + Tambah Lokawisata
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse ($lokawisatas as $lok)
                <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2 mb-1">
                            🏕️ {{ $lok->nama_lokawisata }}
                        </h2>
                        <p class="text-sm text-gray-600 italic mb-3">
                            📍 {{ $lok->alamat ?? 'Alamat belum tersedia' }}
                        </p>
                        <p class="text-gray-700 text-sm leading-relaxed mb-4">
                            {{ $lok->keterangan ?? 'Belum ada deskripsi.' }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 mt-2">
                        <button @click="openEdit = true; editData = {{ $lok->toJson() }}"
                            class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400 transition">
                            ✏️ Edit
                        </button>
                        <form id="delete-form-{{ $lok->id }}" method="POST"
                            action="{{ route('lokawisata.destroy', $lok->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" data-id="{{ $lok->id }}"
                                class="delete-button px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-500 transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>

                    <div class="border-t border-gray-200 mt-5 pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">
                            📦 Rekapan Barang Keluar
                        </h3>

                        <div class="rounded-lg border border-gray-200 overflow-hidden">
                            <div class="max-h-56 overflow-y-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-100 text-gray-700 sticky top-0 z-[1]">
                                        <tr>
                                            <th class="p-3 font-semibold">Tanggal</th>
                                            <th class="p-3 font-semibold">Nama Barang</th>
                                            <th class="p-3 font-semibold text-center">Jumlah</th>
                                            <th class="p-3 font-semibold">Keterangan</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-100">
                                        @php
                                            $filtered = $barangKeluar
                                                ->filter(fn($item) => $item->lokawisata_id === $lok->id)
                                                ->values();
                                        @endphp

                                        @forelse ($filtered as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="p-3">{{ $item['tanggal_keluar'] }}</td>
                                                <td class="p-3">{{ $item->barang->nama_barang ?? '-' }}</td>
                                                <td class="p-3 text-center">{{ $item['jumlah_keluar'] }}</td>
                                                <td class="p-3">{{ $item['keterangan'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-3 text-center text-gray-500 italic">
                                                    Belum ada data barang keluar.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <p class="text-gray-500 text-center col-span-full py-10">Belum ada data lokawisata.</p>
            @endforelse
        </div>

        <div x-show="openAdd" x-cloak x-transition.opacity
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="openAdd = false" class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Lokawisata</h2>
                <form action="{{ route('lokawisata.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lokawisata</label>
                            <input type="text" name="nama_lokawisata" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Alamat</label>
                            <input type="text" name="alamat"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                            <textarea name="keterangan" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                                rows="3"></textarea>
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
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="openEdit = false" class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Lokawisata</h2>
                <form :action="'/admin/lokawisata/' + editData.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lokawisata</label>
                            <input type="text" name="nama_lokawisata" x-model="editData.nama_lokawisata" required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Alamat</label>
                            <input type="text" name="alamat" x-model="editData.alamat"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                            <textarea name="keterangan" x-model="editData.keterangan"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
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
                            document.getElementById(`delete-form-${id}`).submit();
                        }
                    });
                });
            });

            @if (session('success'))
                Swal.fire({
                    title: "Berhasil!",
                    text: @json(session('success')),
                    icon: "success"
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    title: "Gagal!",
                    text: @json(session('error')),
                    icon: "error"
                });
            @endif
        });
    </script>
@endsection
