<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang_masuk;
use App\Models\Barang_keluar;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stokPerBulan = Barang::selectRaw('MONTH(created_at) as bulan, SUM(jumlah_stok) as total_stok')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->mapWithKeys(fn($item) => [Carbon::create()->month($item->bulan)->translatedFormat('F') => $item->total_stok])
            ->toArray();

        $barangMasuk = Barang_masuk::selectRaw('DATE(created_at) as tanggal, SUM(jumlah_masuk) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal')
            ->toArray();

        $barangKeluar = Barang_keluar::selectRaw('DATE(created_at) as tanggal, SUM(jumlah_keluar) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal')
            ->toArray();

            // Format label bulan
        $labelBulan = array_keys($stokPerBulan);
        $dataBulan = array_values($stokPerBulan);

        // Format label harian (misal: Sen, 21 Okt)
        $labelHarianMasuk = collect(array_keys($barangMasuk))
            ->map(fn($tgl) => Carbon::parse($tgl)->translatedFormat('D, d M'))
            ->toArray();
        $dataHarianMasuk = array_values($barangMasuk);

        $labelHarianKeluar = collect(array_keys($barangKeluar))
            ->map(fn($tgl) => Carbon::parse($tgl)->translatedFormat('D, d M'))
            ->toArray();
        $dataHarianKeluar = array_values($barangKeluar);

        return view('admin.dashboard', compact(
            'labelBulan', 'dataBulan',
            'labelHarianMasuk', 'dataHarianMasuk',
            'labelHarianKeluar', 'dataHarianKeluar'
        ));
    }
}
