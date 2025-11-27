<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang_keluar;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    public function index()
    {
        $lokasiId = auth()->user()->lokawisata_id;

        $start = Carbon::now()->startOfWeek(); // Senin
        $end   = Carbon::now()->endOfWeek();   // Minggu

        $dataMingguan = Barang_keluar::select(
                DB::raw('WEEKDAY(tanggal_keluar) as hari'),  // 0 = Senin ... 6 = Minggu
                DB::raw('SUM(jumlah_keluar) as total')
            )
            ->where('lokawisata_id', $lokasiId)  // Filter lokasi user login
            ->whereBetween('tanggal_keluar', [$start, $end])
            ->groupBy('hari')
            ->get();

        // array default 7 hari
        $chartMingguan = array_fill(0, 7, 0);

        foreach ($dataMingguan as $d) {
            $chartMingguan[$d->hari] = $d->total;
        }

        $totalMingguIni = array_sum($chartMingguan);
dd([
    'lokasi_user' => $lokasiId,
    'start' => $start,
    'end' => $end,
    'query_data' => $dataMingguan,
    'asli_tabel' => Barang_keluar::where('lokawisata_id', $lokasiId)->get(),
]);
        return view('user.dashboard', compact('chartMingguan', 'totalMingguIni'));
    }


}
