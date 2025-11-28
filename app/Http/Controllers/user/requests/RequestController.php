<?php

namespace App\Http\Controllers\user\requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Barang;
use App\Models\Lokawisata;

class RequestController extends Controller
{
   public function index(Request $request)
    {
        $lokawisata = Lokawisata::find(auth()->user()->lokawisata_id);

        if (!$lokawisata) {
            $data = collect([]);
            return view('user.request.index', compact('data'));
        }

        $query = Notifikasi::where('lokawisata_id', $lokawisata->id)
            ->whereIn('status', ['approved', 'rejected']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // FILTER SEARCH (nama barang)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('nama_barang', 'like', '%' . $search . '%');
        }

        // Eksekusi Query
        $data = $query->orderBy('created_at', 'desc')->get();

        return view('user.request.index', compact('data'));
    }



}
