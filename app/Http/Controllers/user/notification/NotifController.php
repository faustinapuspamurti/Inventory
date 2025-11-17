<?php

namespace App\Http\Controllers\user\notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifController extends Controller
{
    public function index(Request $request)
    {
        $lokawisata = auth()->user()->lokawisatas()->first();

        if (!$lokawisata) {
            $data = collect([]);
            return view('user.notification.index', compact('data'));
        }

        $query = Notifikasi::query()
            ->where('lokawisata_id', $lokawisata->id)
            ->where('status', 'pending'); // atau whereIn jika banyak status

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('nama_barang', 'like', '%' . $search . '%');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return view('user.notification.index', compact('data'));
    }

    public function approve(Request $request, $id)
    {
        $notif = Notifikasi::findOrFail($id);

        $request->validate([
            'jumlah_disetujui' => 'required|integer|min:1',
        ]);

        $jumlahDisetujui = $request->jumlah_disetujui;

        // Update status notifikasi
        $notif->update([
            'status' => 'approved',
            'jumlah_disetujui' => $jumlahDisetujui, // tambahkan field di table jika perlu
        ]);

        // Kurangi stok barang
        $barang = $notif->barang;
        $barang->jumlah_stok -= $jumlahDisetujui;
        if ($barang->jumlah_stok < 0) $barang->jumlah_stok = 0; // aman
        $barang->save();

        return redirect()->back()->with('success', 'Notifikasi disetujui dan stok diperbarui.');
    }
}
