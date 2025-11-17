<?php

namespace App\Http\Controllers\admin\barang_keluar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang_keluar;
use App\Models\Barang;
use App\Models\Lokawisata;
use App\Exports\OutBarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class OutboundController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang_keluar::with(['barang', 'lokawisata']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($subQuery) use ($search) {
                    $subQuery->where('nama_barang', 'like', '%' . $search . '%');
                })
                ->orWhereHas('lokawisata', function ($subQuery) use ($search) {
                    $subQuery->where('nama_lokawisata', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('tanggal_keluar', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal_keluar', $request->start_date);
        }

        $keluars = $query->orderBy('id', 'desc')->get();

        $barangs = Barang::all();
        $wisatas = Lokawisata::all();

        return view('admin.barang_keluar.index', compact('keluars', 'barangs', 'wisatas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'lokawisata_id' => 'required|exists:lokawisata,id',
            'tanggal_keluar' => 'required|date',
            'jumlah_keluar' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        $harga_satuan = $barang->harga_satuan;

        // Hitung total harga barang keluar
        $harga_total = $harga_satuan * $request->jumlah_keluar;

        // Simpan ke tabel barang_keluar
        Barang_keluar::create([
            'barang_id' => $request->barang_id,
            'lokawisata_id' => $request->lokawisata_id,
            'tanggal_keluar' => $request->tanggal_keluar,
            'jumlah_keluar' => $request->jumlah_keluar,
            'harga_satuan' => $harga_satuan,
            'harga_total' => $harga_total,
            'keterangan' => $request->keterangan,
        ]);

        // Kurangi stok barang di tabel barang
        $barang->jumlah_stok = max(0, $barang->jumlah_stok - $request->jumlah_keluar);

        // Kurangi harga total stok
        $barang->harga_total = $barang->jumlah_stok * $barang->harga_satuan;

        $barang->save();

        return redirect()->route('barang_keluar.index')
            ->with('success', 'Barang keluar berhasil ditambahkan dan stok diperbarui.');
    }

    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = Barang::find($barangKeluar->barang_id);

        if ($barang) {
            $barang->jumlah_stok += $barangKeluar->jumlah_keluar;
            $barang->save();
        }

        $barangKeluar->delete();

        return redirect()->route('barang_keluar.index')->with('success', 'Data barang keluar dihapus dan stok diperbarui.');
    }

    public function export()
    {
        return Excel::download(new OutBarangExport, 'barang_keluar.xlsx');
    }
}
