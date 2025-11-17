<?php

namespace App\Http\Controllers\admin\barang_masuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang_masuk;
use App\Models\Barang;
use App\Exports\InBarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class InboundController extends Controller
{

    public function index(Request $request)
    {
        $query = Barang_masuk::query();

        if ($request->filled('search')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('tanggal_masuk', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal_masuk', $request->start_date);
        }

        $barangMasuk = $query->orderBy('tanggal_masuk', 'desc')->get();

        $stoks = Barang::all();

        return view('admin.barang_masuk.index', compact('barangMasuk', 'stoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        Barang_masuk::create([
            'barang_id' => $request->barang_id,
            'jumlah_masuk' => $request->jumlah_masuk,
            'tanggal_masuk' => $request->tanggal_masuk,
            'deskripsi' => $request->deskripsi,
        ]);

        $barang = Barang::find($request->barang_id);
        if ($barang) {
            $barang->jumlah_stok += $request->jumlah_masuk;
            $harga_satuan = $barang->harga_satuan;
            $barang->harga_total = $barang->jumlah_stok * $harga_satuan;
            $barang->save();
        }

        return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil ditambahkan dan stok diperbarui!');
    }


    public function destroy($id)
    {
        $barangMasuk = Barang_masuk::findOrFail($id);

        $barang = Barang::find($barangMasuk->barang_id);

        if ($barang) {
            $barang->jumlah_stok -= $barangMasuk->jumlah_masuk;
            if ($barang->jumlah_stok < 0) {
                $barang->jumlah_stok = 0;
            }
            $barang->save();
        }
        $barangMasuk->delete();

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk dihapus dan stok diperbarui.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_masuk' => 'required|date',
            'jumlah_masuk' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        $barangMasuk = Barang_masuk::findOrFail($id);
        $barang = Barang::findOrFail($request->barang_id);
        $barang->jumlah_stok -= $barangMasuk->jumlah_masuk;

        $barangMasuk->update([
            'barang_id' => $request->barang_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'jumlah_masuk' => $request->jumlah_masuk,
            'deskripsi' => $request->deskripsi,
        ]);

        $barang->jumlah_stok += $request->jumlah_masuk;
        $barang->save();

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk berhasil diperbarui!');
    }

    public function export()
    {
        return Excel::download(new InBarangExport, 'barang_masuk.xlsx');
    }

}
