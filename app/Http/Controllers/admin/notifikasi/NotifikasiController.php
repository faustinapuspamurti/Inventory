<?php

namespace App\Http\Controllers\admin\notifikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Barang;

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

        $notif->jumlah = $request->jumlah_disetujui; // update jumlah
        $notif->status = 'approved';
        $notif->save();

        $barang = Barang::find($notif->barang_id); // ambil barang sesuai relasi
        if ($barang) {
            $barang->jumlah_stok -= $request->jumlah_disetujui; // kolom di tabel jumlah_stok
            if ($barang->jumlah_stok < 0) $barang->jumlah_stok = 0;
            $barang->save();
        }

        return response()->json([
            'message' => 'Permintaan berhasil disetujui dan jumlah diperbarui di database.'
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
