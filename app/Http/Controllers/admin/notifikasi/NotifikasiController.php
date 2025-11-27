<?php

namespace App\Http\Controllers\admin\notifikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Barang;
use App\Models\Barang_keluar;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::orderBy('created_at', 'desc')->get();

        return view('admin.notifikasi.index', compact('notifikasis'));
    }

    public function approve(Request $request, $id)
{
    $notif = Notifikasi::findOrFail($id);

    $request->validate([
        'jumlah_disetujui' => 'required|integer|min:1',
    ]);

    $barang = Barang::find($notif->barang_id);

    $jumlahLama = $notif->jumlah;

    if ($request->jumlah_disetujui > $barang->jumlah_stok) {
        return response()->json([
            'status' => 'error',
            'message' => 'Jumlah yang disetujui melebihi stok tersedia! Stok saat ini: ' . $barang->jumlah_stok,
            'jumlah_lama' => $jumlahLama
        ], 400);
    }

    $notif->jumlah = $request->jumlah_disetujui;
    $notif->status = 'approved';
    $notif->save();

    $barang->jumlah_stok -= $request->jumlah_disetujui;
    $barang->save();

    Barang_keluar::create([
        'tanggal_keluar' => Carbon::now(),
        'lokawisata_id' => $notif->lokawisata_id,
        'barang_id' => $notif->barang_id,
        'jumlah_keluar' => $request->jumlah_disetujui,
        'harga_satuan' => $barang->harga_satuan,
        'harga_total' => $barang->harga_satuan * $request->jumlah_disetujui,
        'keterangan' => 'Pengeluaran otomatis dari request',
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Permintaan disetujui, stok berkurang dan laporan dibuat.'
    ]);
}


    public function reject($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update([
            'status' => 'rejected'
        ]);

        return response()->json([
        'success' => true,
        'message' => 'Notifikasi ditolak',
        ]);

    }

}
