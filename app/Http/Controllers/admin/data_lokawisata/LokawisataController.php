<?php

namespace App\Http\Controllers\admin\data_lokawisata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokawisata;
use App\Models\Barang_keluar;

class LokawisataController extends Controller
{
    public function index() 
    {
        $lokawisatas = Lokawisata::all();

        // WAJIB pakai get() tanpa toArray()
        $barangKeluar = Barang_keluar::with('barang')
            ->orderBy('created_at', 'desc')
            ->get(); // <-- harus Collection, jangan diubah jadi array

        // dd($barangKeluar->toArray());

        return view('admin.data_lokawisata.index', compact('lokawisatas', 'barangKeluar'));
    }


    public function store(Request $request) 
    {
        $request->validate([
            'nama_lokawisata' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'alamat' => 'required|string|max:255',
        ]);
        Lokawisata::create($request->all());
        return redirect()->route('lokawisata.index')->with('success', 'Lokawisata berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lokawisata' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'alamat' => 'required|string|max:255',
        ]);

        $lokawisata = Lokawisata::findOrFail($id);
        $lokawisata->update([
            'nama_lokawisata' => $request->nama_lokawisata,
            'keterangan' => $request->keterangan,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('lokawisata.index')->with('success', 'Lokawisata berhasil diperbarui!');
    }

    public function destroy($id) 
    {
        Lokawisata::destroy($id);
        return redirect()->route('lokawisata.index')->with('success', 'Lokawisata berhasil dihapus!');
    }
}
