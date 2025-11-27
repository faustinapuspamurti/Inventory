<?php

namespace App\Http\Controllers\admin\stok_barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StokBarangExport;

class StokBarangController extends Controller
{
    public function index(Request $request){

        $query = Barang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_barang', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }

        $stoks = $query->orderBy('id', 'desc')->get();

        return view('admin.stok_barang.index', compact('stoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer|min:1',
            'satuan' => 'required|string|max:255',
            'harga_satuan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        
        $harga_total = $request->jumlah_stok * $request->harga_satuan;

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'jumlah_stok' => $request->jumlah_stok,
            'satuan' => $request->satuan,
            'harga_satuan' => $request->harga_satuan,
            'harga_total' => $harga_total,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('stok_barang.index')->with('success', 'Stok barang berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('stok_barang.index')->with('success', 'Stok barang berhasil dihapus');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        $stok = Barang::findOrFail($id);

        $stok->nama_barang = $request->nama_barang;
        $stok->jumlah_stok = $request->jumlah_stok;
        $stok->deskripsi = $request->deskripsi;

        $stok->harga_total = $stok->jumlah_stok * $stok->harga_satuan;

        $stok->save();

        return redirect()->route('stok_barang.index')->with('success', 'Stok barang berhasil diperbarui dan harga total diperbarui');
    }


    public function export()
    {
        return Excel::download(new StokBarangExport, 'stok_barang.xlsx');
    }
}