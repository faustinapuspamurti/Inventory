<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang_masuk;
use App\Models\Barang_keluar;
use App\Models\Barang;
use App\Models\Notifikasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stokPerBulanRaw = Barang::selectRaw('MONTH(created_at) as bulan, SUM(jumlah_stok) as total_stok')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $allMonths = [
            'Jan'=>0,'Feb'=>0,'Mar'=>0,'Apr'=>0,'Mei'=>0,'Jun'=>0,
            'Jul'=>0,'Agu'=>0,'Sep'=>0,'Okt'=>0,'Nov'=>0,'Des'=>0
        ];

        foreach($stokPerBulanRaw as $item){
            $bulan = Carbon::createFromDate(now()->year, $item->bulan, 1)->translatedFormat('M');
            $allMonths[$bulan] = $item->total_stok;
        }

        $labelBulan = array_keys($allMonths);
        $dataBulanChart = array_values($allMonths);

        $weekDays = ['Mon'=>'Senin','Tue'=>'Selasa','Wed'=>'Rabu','Thu'=>'Kamis','Fri'=>'Jumat','Sat'=>'Sabtu','Sun'=>'Minggu'];

        $barangMasukRaw = Barang_masuk::selectRaw('DATE(created_at) as tanggal, SUM(jumlah_masuk) as total')
            ->where('created_at', '>=', now()->startOfWeek()) // mulai Senin
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal')
            ->toArray();

        $dataHarianMasuk = [];
        $labelHarianMasuk = [];
        foreach($weekDays as $key=>$day){
            $found = false;
            foreach($barangMasukRaw as $tgl => $total){
                if(Carbon::parse($tgl)->format('D') === $key){
                    $dataHarianMasuk[] = $total;
                    $labelHarianMasuk[] = $day;
                    $found = true;
                    break;
                }
            }
            if(!$found){
                $dataHarianMasuk[] = 0;
                $labelHarianMasuk[] = $day;
            }
        }

        $barangKeluarRaw = Barang_keluar::selectRaw('DATE(created_at) as tanggal, SUM(jumlah_keluar) as total')
            ->where('created_at', '>=', now()->startOfWeek())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal')
            ->toArray();

        $dataHarianKeluar = [];
        $labelHarianKeluar = [];
        foreach($weekDays as $key=>$day){
            $found = false;
            foreach($barangKeluarRaw as $tgl => $total){
                if(Carbon::parse($tgl)->format('D') === $key){
                    $dataHarianKeluar[] = $total;
                    $labelHarianKeluar[] = $day;
                    $found = true;
                    break;
                }
            }
            if(!$found){
                $dataHarianKeluar[] = 0;
                $labelHarianKeluar[] = $day;
            }
        }

        $jumlahNotifikasi = Notifikasi::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'labelBulan', 'dataBulanChart',
            'labelHarianMasuk', 'dataHarianMasuk',
            'labelHarianKeluar', 'dataHarianKeluar',
            'jumlahNotifikasi'
        ));
    }
}
