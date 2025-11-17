<?php

namespace App\Http\Controllers\user\stok_barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Notifikasi;

class UserStokBarangController extends Controller
{
    public function index(Request $request){

        $query = Barang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_barang', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }   

        $stoks = $query->orderBy('id', 'desc')->get();

        return view('user.stok_barang.index', compact('stoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        $lokawisata = auth()->user()->lokawisatas()->first();

        Notifikasi::create([
            'barang_id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'lokawisata_id' => $lokawisata->id,
            'nama_lokawisata' => $lokawisata->nama_lokawisata,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
            'pesan' => 'Request baru dari ' . $lokawisata->nama_lokawisata . ' untuk barang ' . $barang->nama_barang,
        ]);

        return redirect()->back()->with('success', 'Request barang berhasil dikirim!');
    }
}
