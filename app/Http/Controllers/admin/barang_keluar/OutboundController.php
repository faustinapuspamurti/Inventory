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
        $perPageOptions = [10, 25, 50, 100, 250];
        $perPage = $request->integer('per_page', 10);
        $perPage = in_array($perPage, $perPageOptions) ? $perPage : 10;

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

        if ($request->filled('lokawisata_id')) {
            $query->where('lokawisata_id', $request->lokawisata_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('tanggal_keluar', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal_keluar', $request->start_date);
        }

        $keluars = $query
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $barangs = Barang::all();
        $wisatas = Lokawisata::all();

        return view('admin.barang_keluar.index', compact('keluars', 'barangs', 'wisatas', 'perPageOptions'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'barang_id' => 'required|exists:barang,id',
                'lokawisata_id' => 'required|exists:lokawisata,id',
                'tanggal_keluar' => 'required|date',
                'jumlah_keluar' => 'required|integer|min:1',
                'keterangan' => 'nullable|string',
                'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ],
            [
                'barang_id.required'      => 'Barang wajib dipilih.',
                'barang_id.exists'        => 'Barang yang dipilih tidak valid.',
                'lokawisata_id.required'  => 'Lokawisata wajib dipilih.',
                'lokawisata_id.exists'    => 'Lokawisata yang dipilih tidak valid.',
                'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
                'tanggal_keluar.date'     => 'Format tanggal keluar tidak valid.',
                'jumlah_keluar.required'  => 'Jumlah keluar wajib diisi.',
                'jumlah_keluar.integer'   => 'Jumlah keluar harus berupa angka.',
                'jumlah_keluar.min'       => 'Jumlah keluar minimal 1.',
                'keterangan.string'       => 'Keterangan harus berupa teks.',
                'evidence.file'           => 'Bukti harus berupa file.',
                'evidence.mimes'          => 'Bukti hanya boleh berformat JPG, PNG, atau PDF.',
                'evidence.max'            => 'Ukuran bukti maksimal 2 MB.',
            ]
        );

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jumlah_keluar > $barang->jumlah_stok) {
            return redirect()->back()
                ->with('error', 'Jumlah yang dikeluarkan melebihi stok! Stok tersedia: ' . $barang->jumlah_stok)
                ->withInput();
        }

        $evidence = null;
        if ($request->hasFile('evidence')) {
            $evidence = $request->file('evidence')->store('assets/Outbound/evidence', 'public');
        }

        $harga_satuan = $barang->harga_satuan;
        $harga_total = $harga_satuan * $request->jumlah_keluar;

        Barang_keluar::create([
            'barang_id' => $request->barang_id,
            'lokawisata_id' => $request->lokawisata_id,
            'tanggal_keluar' => $request->tanggal_keluar,
            'jumlah_keluar' => $request->jumlah_keluar,
            'harga_satuan' => $harga_satuan,
            'harga_total' => $harga_total,
            'keterangan' => $request->keterangan,
            'evidence' => $evidence,
        ]);

        $barang->jumlah_stok -= $request->jumlah_keluar;
        $barang->harga_total = $barang->jumlah_stok * $barang->harga_satuan;
        $barang->save();

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil ditambahkan dan stok diperbarui.');
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

    public function export(Request $request)
    {
        return Excel::download(
            new OutBarangExport(
                $request->start_date,
                $request->end_date,
                $request->search,
                $request->lokawisata_id
            ),
            'barang_keluar.xlsx'
        );
    }
}
