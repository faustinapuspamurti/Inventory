@extends('layouts.admin')

@section('content')
    <main class="p-6 bg-gray-50 min-h-screen flex-1" x-data>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-500">
                <h4 class="text-lg font-semibold text-white">🔔 Notifikasi Permintaan Barang</h4>

                <button onclick="window.location.href='{{ route('notifikasi.export') }}'"
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
                <form action="{{ route('admin.notifikasi') }}" method="GET"
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

                        <a href="{{ route('admin.notifikasi') }}"
                            style="background-color:#A1E3F9; color:#1F2937; border:none; border-radius:6px; padding:6px 14px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block;">
                            ♻️ Reset
                        </a>
                    </div>

                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border border-gray-200 rounded-lg">
                    <thead class="bg-blue-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Lokawisata</th>
                            <th class="py-3 px-4">Nama Barang</th>
                            <th class="py-3 px-4">Jumlah</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notifikasis as $notif)
                            <tr class="border-b hover:bg-blue-50" x-data="{ status: '{{ $notif->status }}', openModal: false, approvedQty: {{ $notif->jumlah }}, maxQty: {{ $notif->jumlah }} }" id="notif-{{ $notif->id }}">
                                <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4">{{ $notif->created_at->format('d M Y') }}</td>
                                <td class="py-2 px-4">{{ $notif->nama_lokawisata }}</td>
                                <td class="py-2 px-4">{{ $notif->nama_barang }}</td>
                                <td class="py-2 px-4">
                                    <span x-text="approvedQty"></span>
                                </td>
                                <td class="py-2 px-4 space-x-2 text-center">
                                    <div x-show="status === 'pending'" class="space-x-2">
                                        <button @click="openModal = true"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow-sm transition">
                                            Approve
                                        </button>
                                        <button
                                            @click="updateStatus('{{ route('admin.notifikasi.reject', $notif->id) }}', 'rejected', {{ $notif->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow-sm transition">
                                            Reject
                                        </button>
                                    </div>

                                    <div x-show="status === 'approved'">
                                        <span class="text-green-600 font-semibold">✅ Disetujui</span>
                                    </div>
                                    <div x-show="status === 'rejected'">
                                        <span class="text-red-600 font-semibold">❌ Ditolak</span>
                                    </div>

                                    <div x-show="openModal" x-transition.opacity x-cloak
                                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                        <div @click.away="openModal = false"
                                            class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Setujui Permintaan</h2>
                                            <form
                                                @submit.prevent="confirmApprove('{{ route('admin.notifikasi.approve', $notif->id) }}')">
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600 mb-1">Jumlah Disetujui</label>
                                                    <input type="number" x-model="approvedQty" min="1" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                                                </div>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" @click="openModal = false"
                                                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Batal</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition">Setujui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-gray-500">Data belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </main>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
function updateStatus(url, newStatus, id) {
    Swal.fire({
        title: newStatus === 'approved' ? 'Setujui Permintaan?' : 'Tolak Permintaan?',
        text: newStatus === 'approved' ? 'Barang akan disetujui.' : 'Permintaan akan ditolak.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: newStatus === 'approved' ? 'Ya, Setujui' : 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: newStatus === 'approved' ? '#16a34a' : '#dc2626',
    }).then(async (result) => {
        if (!result.isConfirmed) return;

        try {
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) throw new Error('Gagal memperbarui status.');

            const data = await response.json();
            const row = document.getElementById('notif-' + id);
            if (row) Alpine.$data(row).status = newStatus;

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message
            });
        }
    });
}

// Modal approve
function confirmApprove(url) {
    const row = event.target.closest('tr');
    const data = Alpine.$data(row);

    fetch(url, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ jumlah_disetujui: data.approvedQty })
    })
    .then(res => res.json())
    .then(res => {
        data.status = 'approved';
        data.openModal = false;
        // update kolom jumlah otomatis
        data.approvedQty = data.approvedQty;

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: res.message,
            timer: 1500,
            showConfirmButton: false
        });
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: err.message
        });
    });
}
</script>

@endsection
