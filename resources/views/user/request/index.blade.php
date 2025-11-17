@extends('layouts.user')

@section('content')
    <main class="p-6 bg-gray-50 min-h-screen flex-1">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">📝 Riwayat Permintaan</h3>
        </div>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-500">
                <h4 class="text-lg font-semibold text-white">Riwayat Permintaan Barang</h4>
            </div>

            <div
                style="display:flex; justify-content:space-between; align-items:center; padding:12px 24px; background-color:#F8FEFE; border-bottom:1px solid #A1E3F9;">
                <form action="{{ route('request') }}" method="GET"
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
                        <a href="{{ route('request') }}"
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
                            <th class="p-4 text-sm font-semibold">Nama Barang</th>
                            <th class="p-4 text-sm font-semibold">Jumlah</th>
                            <th class="p-4 text-sm font-semibold">Status</th>
                            <th class="p-4 text-sm font-semibold">Pesan</th>
                            <th class="p-4 text-sm font-semibold text-center">Tanggal</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700">
                        @forelse ($data as $index => $item)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="p-4 font-semibold text-gray-700">{{ $loop->iteration }}</td>
                                <td class="p-4">{{ $item->nama_barang }}</td>
                                <td class="p-4">{{ $item->jumlah }}</td>
                                <td class="p-4 text-center">
                        @if ($item->status === 'approved')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Disetujui
                            </span>
                        @elseif ($item->status === 'rejected')
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Ditolak
                            </span>
                        @else
                            <button
                                @click="openApproveModal({{ $item->id }}, {{ $item->jumlah }})"
                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Pending
                            </button>
                        @endif
                    </td>
                                <td class="p-4">{{ $item->pesan ?? '-' }}</td>
                                <td class="p-4 text-center">{{ $item->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-500">Belum ada riwayat permintaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
